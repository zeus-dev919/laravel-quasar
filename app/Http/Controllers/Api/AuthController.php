<?php

namespace App\Http\Controllers\Api;

use App\Classes\Common;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\ProfileRequest;
use App\Http\Requests\Api\Auth\RefreshTokenRequest;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Lang;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Warehouse;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Examyou\RestAPI\ApiResponse;
use Examyou\RestAPI\Exceptions\ApiException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends ApiBaseController
{
	public function app()
	{
		$settings = Company::with('currency:id,name,code,symbol,position', 'warehouse:id,name')->first();

		return ApiResponse::make('Success', [
			'app' => $settings
		]);
	}

	public function allEnabledLangs()
	{
		$allLangs = Lang::select('id', 'name', 'key', 'image')->where('enabled', 1)->get();

		return ApiResponse::make('Success', [
			'langs' => $allLangs
		]);
	}

	public function pdf($uniqueId)
	{
		$order = Order::with(['user', 'items', 'items.product', 'items.unit'])
			->where('unique_id', $uniqueId)
			->first();
		$pdfData = ['order' => $order, 'company' => Company::with('currency')->first(), 'dateTimeFormat' => 'd-m-Y'];

		$html = view('pdf', $pdfData);

		$pdf = app('dompdf.wrapper');
		$pdf->loadHTML($html);
		return $pdf->download();
	}

	public function login(LoginRequest $request)
	{
		$credentials = [
			'password' =>  $request->password,
			'user_type' => 'staff_members'
		];

		if (is_numeric($request->get('email'))) {
			$credentials['phone'] = $request->email;
		} else {
			$credentials['email'] = $request->email;
		}

		if (!$token = auth('api')->attempt($credentials)) {
			throw new ApiException('These credentials do not match our records.');
		} elseif (auth('api')->user()->status === 'waiting') {
			throw new ApiException('User not verified.');
		} elseif (auth('api')->user()->status === 'disabled') {
			throw new ApiException('Account deactivated.');
		}

		return $this->respondWithToken($token);
	}

	protected function respondWithToken($token)
	{
		$user = auth('api')->user();
		$user = $user->load('role', 'role.perms', 'warehouse');

		session(['user' => $user]);

		return ApiResponse::make('Loggged in successfull', [
			'token' => $token,
			'token_type' => 'bearer',
			'expires_in' => Carbon::now()->addDays(180),
			'user' => $user
		]);
	}

	public function logout()
	{
		auth('api')->logout();

		return ApiResponse::make(__('Session closed successfully'));
	}

	public function user()
	{
		$user = auth('api')->user();
		$user = $user->load('role', 'role.perms', 'warehouse');

		session(['user' => $user]);

		return ApiResponse::make('Data successfull', [
			'user' => $user
		]);
	}

	public function refreshToken(RefreshTokenRequest $request)
	{
		$newToken = auth('api')->refresh();

		return $this->respondWithToken($newToken);
	}

	public function uploadFile(Request $request)
	{
		$result = Common::uploadFile($request);

		return ApiResponse::make('File Uploaded', $result);
	}

	public function profile(ProfileRequest $request)
	{
		$user = auth('api')->user();

		// In Demo Mode
		if (env('APP_ENV') == 'production') {
			$request = request();

			if ($request->email == 'admin@example.com' && $request->has('password') && $request->password != '') {
				throw new ApiException('Not Allowed In Demo Mode');
			}
		}

		$user->name = $request->name;
		if ($request->has('profile_image')) {
			$user->profile_image = $request->profile_image;
		}
		if ($request->password != '') {
			$user->password = $request->password;
		}
		$user->phone = $request->phone;
		$user->address = $request->address;
		$user->save();

		return ApiResponse::make('Profile updated successfull', [
			'user' => $user->load('role', 'role.perms')
		]);
	}

	public function langTrans()
	{
		$langs = Lang::with('translations')->get();

		return ApiResponse::make('Langs fetched', [
			'data' => $langs
		]);
	}

	public function dashboard(Request $request)
	{
		$data = [
			'topSellingProducts' => $this->getTopProducts(),
			'purchaseSales' => $this->getPurchaseSales(),
			'stockAlerts' => $this->getStockAlerts(5),
			'topCustomers' => $this->getSalesTopCustomers(),
			'stockHistoryStatsData' => $this->getStockHistoryStatsData(),
			'stateData' => $this->getStatsData(),
			'paymentChartData' => $this->getPaymentChartData(),
		];

		return ApiResponse::make('Data fetched', $data);
	}

	public function stockAlerts()
	{
		$data = [
			'stockAlerts' => $this->getStockAlerts(),
		];

		return ApiResponse::make('Data fetched', $data);
	}

	public function getStockAlerts($limit = null)
	{
		$request = request();
		$warehouseId = $this->getWarehouseId();

		$warehouseStocks = Product::select('products.id as product_id', 'products.name as product_name', 'product_details.current_stock', 'product_details.stock_quantitiy_alert', 'units.short_name')
			->join('product_details', 'product_details.product_id', '=', 'products.id')
			->join('units', 'units.id', '=', 'products.unit_id')
			->whereNotNull('product_details.stock_quantitiy_alert')
			->whereRaw('product_details.current_stock <= product_details.stock_quantitiy_alert');

		// If user not have admin role
		// then he can only view reords
		// of warehouse assigned to him
		$warehouseStocks = $warehouseStocks->where('product_details.warehouse_id', '=', $warehouseId);

		if ($request->has('product_id') && $request->product_id != null) {
			$productId = $this->getIdFromHash($request->product_id);
			$warehouseStocks = $warehouseStocks->where('product_details.product_id', '=', $productId);
		}
		if ($limit != null) {
			$warehouseStocks = $warehouseStocks->take($limit);
		}
		$warehouseStocks = $warehouseStocks->get();

		return $warehouseStocks;
	}

	public function getStatsData()
	{
		$request = request();
		$warehouseId = $this->getWarehouseId();

		// Total Sales
		$totalSalesAmount = Order::where('order_type', 'sales');
		// Total Expenses
		$totalExpenses = Expense::select('amount');
		// Payment Sent
		$paymentSent = Payment::where('payments.payment_type', 'out');
		// Payment Received
		$paymentReceived = Payment::where('payments.payment_type', 'in');

		// Warehouse Filter
		if ($warehouseId && $warehouseId != null) {
			$totalSalesAmount = $totalSalesAmount->where('orders.warehouse_id', $warehouseId);
			$totalExpenses = $totalExpenses->where('warehouse_id', $warehouseId);
		}

		// Dates Filters
		if ($request->has('dates') && $request->dates != null && count($request->dates) > 0) {
			$dates = $request->dates;
			$startDate = $dates[0];
			$endDate = $dates[1];

			$totalSalesAmount = $totalSalesAmount->whereRaw('DATE(orders.order_date) >= ?', [$startDate])
				->whereRaw('DATE(orders.order_date) <= ?', [$endDate]);
			$totalExpenses = $totalExpenses->whereRaw('DATE(expenses.date) >= ?', [$startDate])
				->whereRaw('DATE(expenses.date) <= ?', [$endDate]);
			$paymentSent = $paymentSent->whereRaw('DATE(payments.date) >= ?', [$startDate])
				->whereRaw('DATE(payments.date) <= ?', [$endDate]);
			$paymentReceived = $paymentReceived->whereRaw('DATE(payments.date) >= ?', [$startDate])
				->whereRaw('DATE(payments.date) <= ?', [$endDate]);
		}

		$totalSalesAmount = $totalSalesAmount->sum('total');
		$totalExpenses = $totalExpenses->sum('amount');
		$paymentSent = $paymentSent->sum('payments.amount');
		$paymentReceived = $paymentReceived->sum('payments.amount');

		return [
			'totalSales' => $totalSalesAmount,
			'totalExpenses' => $totalExpenses,
			'paymentSent' => $paymentSent,
			'paymentReceived' => $paymentReceived,
		];
	}

	public function getPaymentChartData()
	{
		$request = request();
		$warehouseId = $this->getWarehouseId();
		if ($request->has('dates') && $request->dates != null && count($request->dates) > 0) {
			$dates = $request->dates;
			$startDate = $dates[0];
			$endDate = $dates[1];
		} else {
			$startDate =  Carbon::now()->subDays(30)->format("Y-m-d");
			$endDate =  Carbon::now()->format("Y-m-d");
		}

		// Sent Payments
		$allSentPayments = Payment::select(DB::raw('date(payments.date) as date, sum(payments.amount) as total_amount'))
			->where('payments.payment_type', 'out')
			->whereRaw('DATE(payments.date) >= ?', [$startDate])
			->whereRaw('DATE(payments.date) <= ?', [$endDate]);

		// Received Payments
		$allReceivedPayments = Payment::select(DB::raw('date(payments.date) as date, sum(payments.amount) as total_amount'))
			->where('payments.payment_type', 'in')
			->whereRaw('DATE(payments.date) >= ?', [$startDate])
			->whereRaw('DATE(payments.date) <= ?', [$endDate]);



		// Sent Payments
		$allSentPayments = $allSentPayments->groupBy('payments.date')
			->orderByRaw("date(payments.date) asc")
			->pluck('total_amount', 'date');

		// Received Payments
		$allReceivedPayments = $allReceivedPayments->groupBy('payments.date')
			->orderByRaw("date(payments.date) asc")
			->pluck('total_amount', 'date');

		$periodDates = CarbonPeriod::create($startDate, $endDate);
		$datesArray = [];
		$sentPaymentsArray = [];
		$receivedPaymentsArray = [];

		// Iterate over the period
		foreach ($periodDates as $periodDate) {
			$currentDate =  $periodDate->format('Y-m-d');

			if (isset($allSentPayments[$currentDate]) || isset($allReceivedPayments[$currentDate])) {
				$datesArray[] = $currentDate;
				$sentPaymentsArray[] = isset($allSentPayments[$currentDate]) ? $allSentPayments[$currentDate] : 0;
				$receivedPaymentsArray[] = isset($allReceivedPayments[$currentDate]) ? $allReceivedPayments[$currentDate] : 0;
			}
		}

		return [
			'dates' => $datesArray,
			'sent' => $sentPaymentsArray,
			'received' => $receivedPaymentsArray,
		];
	}

	public function getStockHistoryStatsData()
	{
		$request = request();
		$warehouseId = $this->getWarehouseId();

		// Total Sales
		$totalSales = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')->where('order_type', 'sales');
		// Sales Returns
		$totalSalesReturns = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')->where('order_type', 'sales-returns');
		// Purchases
		$totalPurchases = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')->where('order_type', 'purchases');
		// Purchase Returns
		$totalPurchaseReturns = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')->where('order_type', 'purchase-returns');

		// Warehouse Filter
		if ($warehouseId && $warehouseId != null) {
			$totalSales = $totalSales->where('orders.warehouse_id', $warehouseId);
			$totalSalesReturns = $totalSalesReturns->where('warehouse_id', $warehouseId);
			$totalPurchases = $totalPurchases->where('orders.warehouse_id', $warehouseId);
			$totalPurchaseReturns = $totalPurchaseReturns->where('orders.warehouse_id', $warehouseId);
		}

		// Dates Filters
		if ($request->has('dates') && $request->dates != null && count($request->dates) > 0) {
			$dates = $request->dates;
			$startDate = $dates[0];
			$endDate = $dates[1];

			$totalSales = $totalSales->whereRaw('DATE(orders.order_date) >= ?', [$startDate])
				->whereRaw('DATE(orders.order_date) <= ?', [$endDate]);
			$totalSalesReturns = $totalSalesReturns->whereRaw('DATE(orders.order_date) >= ?', [$startDate])
				->whereRaw('DATE(orders.order_date) <= ?', [$endDate]);
			$totalPurchases = $totalPurchases->whereRaw('DATE(orders.order_date) >= ?', [$startDate])
				->whereRaw('DATE(orders.order_date) <= ?', [$endDate]);
			$totalPurchaseReturns = $totalPurchaseReturns->whereRaw('DATE(orders.order_date) >= ?', [$startDate])
				->whereRaw('DATE(orders.order_date) <= ?', [$endDate]);
		}

		$totalSales = $totalSales->sum('order_items.quantity');
		$totalPurchases = $totalPurchases->sum('order_items.quantity');
		$totalSalesReturns = $totalSalesReturns->sum('order_items.quantity');
		$totalPurchaseReturns = $totalPurchaseReturns->sum('order_items.quantity');

		return [
			'totalSales' => $totalSales,
			'totalPurchases' => $totalPurchases,
			'totalSalesReturn' => $totalPurchaseReturns,
			'totalPurchaseReturn' => $totalPurchaseReturns,
		];
	}

	public function getTopProducts()
	{
		$request = request();
		$waehouse = warehouse();
		$warehouseId = $waehouse->id;

		$colors = ["#20C997", "#5F63F2", "#ffa040", "#FFCD56", "#ff6385"];

		$maxSellingProducts = OrderItem::select('order_items.product_id', DB::raw('sum(order_items.subtotal) as total_amount'))
			->join('orders', 'orders.id', '=', 'order_items.order_id')
			->where('orders.order_type', 'sales');

		if ($warehouseId && $warehouseId != null) {
			$maxSellingProducts = $maxSellingProducts->where('orders.warehouse_id', $warehouseId);
		}

		if ($request->has('dates') && $request->dates != null && count($request->dates) > 0) {
			$dates = $request->dates;
			$startDate = $dates[0];
			$endDate = $dates[1];

			$maxSellingProducts = $maxSellingProducts->whereRaw('DATE(orders.order_date) >= ?', [$startDate])
				->whereRaw('DATE(orders.order_date) <= ?', [$endDate]);
		}

		$maxSellingProducts = $maxSellingProducts->groupBy('order_items.product_id')
			->orderByRaw("sum(order_items.subtotal) desc")
			->take(5)
			->get();

		$topSellingProductsNames = [];
		$topSellingProductsValues = [];
		$topSellingProductsColors = [];
		$counter = 0;
		foreach ($maxSellingProducts as $maxSellingProduct) {
			$product = Product::select('name')->find($maxSellingProduct->product_id);

			$topSellingProductsNames[] = $product->name;
			$topSellingProductsValues[] = $maxSellingProduct->total_amount;
			$topSellingProductsColors[] = $colors[$counter];
			$counter++;
		}

		return [
			'labels' => $topSellingProductsNames,
			'values' => $topSellingProductsValues,
			'colors' => $topSellingProductsColors,
		];
	}

	public function getWarehouseId()
	{
		$waehouse = warehouse();
		$warehouseId = $waehouse->id;

		return $warehouseId;
	}

	public function getSalesTopCustomers()
	{
		$request = request();
		$warehouseId = $this->getWarehouseId();

		$topCustomers = Order::select(DB::raw('sum(orders.total) as total_amount, user_id, count(user_id) as total_sales'))
			->join('users', 'users.id', '=', 'orders.user_id')
			->where('orders.order_type', '=', 'sales');

		if ($warehouseId && $warehouseId != null) {
			$topCustomers = $topCustomers->where('orders.warehouse_id', $warehouseId);
		}

		if ($request->has('dates') && $request->dates != null && count($request->dates) > 0) {
			$dates = $request->dates;
			$startDate = $dates[0];
			$endDate = $dates[1];

			$topCustomers = $topCustomers->whereRaw('DATE(orders.order_date) >= ?', [$startDate])
				->whereRaw('DATE(orders.order_date) <= ?', [$endDate]);
		}

		$topCustomers = $topCustomers->groupBy('user_id')
			->orderByRaw('sum(orders.total) desc')
			->take(5)
			->get();

		$results = [];

		foreach ($topCustomers as $topCustomer) {
			$customer = Customer::select('id', 'name', 'profile_image')->find($topCustomer->user_id);

			$results[] = [
				'customer_id' => $customer->xid,
				'customer' => $customer,
				'total_amount' => $topCustomer->total_amount,
				'total_sales' => $topCustomer->total_sales,
			];
		}

		return $results;
	}

	public function getPurchaseSales()
	{
		$request = request();
		$warehouseId = $this->getWarehouseId();
		if ($request->has('dates') && $request->dates != null && count($request->dates) > 0) {
			$dates = $request->dates;
			$startDate = $dates[0];
			$endDate = $dates[1];
		} else {
			$startDate =  Carbon::now()->subDays(30)->format("Y-m-d");
			$endDate =  Carbon::now()->format("Y-m-d");
		}

		$allPurchases = Order::select(DB::raw('date(orders.order_date) as date, sum(orders.total) as total_amount'))
			->where('orders.order_type', 'purchases')
			->whereRaw('DATE(orders.order_date) >= ?', [$startDate])
			->whereRaw('DATE(orders.order_date) <= ?', [$endDate]);
		if ($warehouseId && $warehouseId != null) {
			$allPurchases = $allPurchases->where('orders.warehouse_id', $warehouseId);
		}
		$allPurchases = $allPurchases->groupBy('orders.order_date')
			->orderByRaw("date(orders.order_date) asc")
			->take(5)
			->pluck('total_amount', 'date');

		$sales = Order::select(DB::raw('date(orders.order_date) as date, sum(orders.total) as total_amount'))
			->where('orders.order_type', 'sales')
			->whereRaw('DATE(orders.order_date) >= ?', [$startDate])
			->whereRaw('DATE(orders.order_date) <= ?', [$endDate]);

		if ($warehouseId && $warehouseId != null) {
			$sales = $sales->where('orders.warehouse_id', $warehouseId);
		}
		$sales = $sales->groupBy('orders.order_date')
			->orderByRaw("date(orders.order_date) asc")
			->take(5)
			->pluck('total_amount', 'date');

		$periodDates = CarbonPeriod::create($startDate, $endDate);
		$datesArray = [];
		$purchasesArray = [];
		$salesArray = [];

		// Iterate over the period
		foreach ($periodDates as $periodDate) {
			$currentDate =  $periodDate->format('Y-m-d');

			if (isset($allPurchases[$currentDate]) || isset($sales[$currentDate])) {
				$datesArray[] = $currentDate;
				$purchasesArray[] = isset($allPurchases[$currentDate]) ? $allPurchases[$currentDate] : 0;
				$salesArray[] = isset($sales[$currentDate]) ? $sales[$currentDate] : 0;
			}
		}

		return [
			'dates' => $datesArray,
			'purchases' => $purchasesArray,
			'sales' => $salesArray,
		];
	}

	public function changeThemeMode(Request $request)
	{
		$mode = $request->has('theme_mode') ? $request->theme_mode : 'light';

		session(['theme_mode' => $mode]);

		if ($mode == 'dark') {
			$this->company->left_sidebar_theme = 'dark';
			$this->company->save();
		}

		return ApiResponse::make('Success', [
			'status' => "success",
			'themeMode' => $mode,
			'themeModee' => session()->all(),
		]);
	}

	public function changeAdminWarehouse(Request $request)
	{
		$user = user();

		$warehouse = $user->hasRole('admin') && $request->has('warehouse_id') && $request->warehouse_id
			? Warehouse::find(Common::getIdFromHash($request->warehouse_id))
			: $user->warehouse;

		session(['warehouse' => $warehouse]);

		return ApiResponse::make('Success', [
			'status' => "success",
			'warehouse' => $warehouse
		]);
	}

	public function getAllTimezones()
	{
		$timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

		return ApiResponse::make('Success', [
			'timezones' => $timezones,
			'date_formates' => [
				'd-m-Y' => 'DD-MM-YYYY',
				'm-d-Y' => 'MM-DD-YYYY',
				'Y-m-d' => 'YYYY-MM-DD',
				'd.m.Y' => 'DD.MM.YYYY',
				'm.d.Y' => 'MM.DD.YYYY',
				'Y.m.d' => 'YYYY.MM.DD',
				'd/m/Y' => 'DD/MM/YYYY',
				'm/d/Y' => 'MM/DD/YYYY',
				'Y/m/d' => 'YYYY/MM/DD',
				'd/M/Y' => 'DD/MMM/YYYY',
				'd.M.Y' => 'DD.MMM.YYYY',
				'd-M-Y' => 'DD-MMM-YYYY',
				'd M Y' => 'DD MMM YYYY',
				'd F, Y' => 'DD MMMM, YYYY',
				'D/M/Y' => 'ddd/MMM/YYYY',
				'D.M.Y' => 'ddd.MMM.YYYY',
				'D-M-Y' => 'ddd-MMM-YYYY',
				'D M Y' => 'ddd MMM YYYY',
				'd D M Y' => 'DD ddd MMM YYYY',
				'D d M Y' => 'ddd DD MMM YYYY',
				'dS M Y' => 'Do MMM YYYY',
			],
			'time_formates' => [
				"hh:mm A" => '12 Hours hh:mm A',
				'hh:mm a' => '12 Hours hh:mm a',
				'hh:mm:ss A' => '12 Hours hh:mm:ss A',
				'hh:mm:ss a' => '12 Hours hh:mm:ss a',
				'HH:mm ' => '24 Hours HH:mm:ss',
				'HH:mm:ss' => '24 Hours hh:mm:ss',
			]
		]);
	}
}
