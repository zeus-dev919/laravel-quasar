<?php

namespace App\Http\Controllers\Api;

use App\Classes\Common;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Order\PosRequest;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Tax;
use App\Models\Unit;
use Carbon\Carbon;
use Examyou\RestAPI\ApiResponse;

class PosController extends ApiBaseController
{
	public function posProducts()
	{
		$request = request();
		$allProducs = [];
		$warehouse = warehouse();
		$warehouseId = $warehouse->id;

		$products = Product::select(
			'products.id',
			'products.name',
			'products.image',
			'product_details.sales_price',
			'products.unit_id',
			'product_details.sales_tax_type',
			'product_details.tax_id',
			'product_details.current_stock',
			'taxes.rate'
		)
			->join('product_details', 'product_details.product_id', '=', 'products.id')
			->leftJoin('taxes', 'taxes.id', '=', 'product_details.tax_id')
			->join('units', 'units.id', '=', 'products.unit_id')
			->where('product_details.warehouse_id', '=', $warehouseId)
			->where('product_details.current_stock', '>', 0);

		// Category Filters
		if ($request->has('category_id') && $request->category_id != "") {
			$categoryId = $this->getIdFromHash($request->category_id);
			$products = $products->where('category_id', '=', $categoryId);
		}

		// Brand Filters
		if ($request->has('brand_id') && $request->brand_id != "") {
			$brandId = $this->getIdFromHash($request->brand_id);
			$products = $products->where('brand_id', '=', $brandId);
		}


		$products =	$products->get();

		foreach ($products as $product) {
			$stockQuantity = $product->current_stock;
			$unit = $product->unit_id != null ? Unit::find($product->unit_id) : null;
			$tax = $product->tax_id != null ? Tax::find($product->tax_id) : null;
			$taxType = $product->sales_tax_type;

			$unitPrice = $product->sales_price;
			$singleUnitPrice = $unitPrice;

			if ($product->rate != '') {
				$taxRate = $product->rate;

				if ($product->sales_tax_type == 'inclusive') {
					$subTotal = $singleUnitPrice;
					$singleUnitPrice =  ($singleUnitPrice * 100) / (100 + $taxRate);
					$taxAmount = ($singleUnitPrice) * ($taxRate / 100);
				} else {
					$taxAmount =  ($singleUnitPrice * ($taxRate / 100));
					$subTotal = $singleUnitPrice + $taxAmount;
				}
			} else {
				$taxAmount = 0;
				$taxRate = 0;
				$subTotal = $singleUnitPrice;
			}

			$allProducs[] = [
				'item_id'    =>  '',
				'xid'    =>  $product->xid,
				'name'    =>  $product->name,
				'image'    =>  $product->image,
				'image_url'    =>  $product->image_url,
				'discount_rate'    =>  0,
				'total_discount'    =>  0,
				'x_tax_id'    => $tax ? $tax->xid : null,
				'tax_type'    =>  $taxType,
				'tax_rate'    =>  $taxRate,
				'total_tax'    =>  $taxAmount,
				'x_unit_id'    =>  $unit ? $unit->xid : null,
				'unit'    =>  $unit,
				'unit_price'    =>  $unitPrice,
				'single_unit_price'    =>  $singleUnitPrice,
				'subtotal'    =>  $subTotal,
				'quantity'    =>  1,
				'stock_quantity'    =>  $stockQuantity,
				'unit_short_name'    =>  $unit ? $unit->short_name : '',
			];
		}

		$data = [
			'products' => $allProducs,
		];

		return ApiResponse::make('Data fetched', $data);
	}

	public function savePosPayments(PosRequest $request)
	{
		$loggedInUser = user();
		$warehouse = warehouse();
		$orderDetails = $request->details;
		$oldOrderId = "";

		$order = new Order();
		$order->order_type = "sales";
		$order->invoice_type = "pos";
		$order->unique_id = Common::generateOrderUniqueId();
		$order->invoice_number = Common::generateInvoiceNumber("sales");
		$order->order_date = Carbon::now();
		$order->warehouse_id = $warehouse->id;
		$order->user_id = isset($orderDetails['user_id']) ? $orderDetails['user_id'] : null;
		$order->tax_id = isset($orderDetails['tax_id']) ? $orderDetails['tax_id'] : null;
		$order->tax_rate = $orderDetails['tax_rate'];
		$order->tax_amount = $orderDetails['tax_amount'];
		$order->discount = $orderDetails['discount'];
		$order->shipping = $orderDetails['shipping'];
		$order->subtotal = 0;
		$order->total = $orderDetails['subtotal'];
		$order->paid_amount = 0;
		$order->due_amount = $order->total;
		$order->order_status = "pending";
		$order->staff_user_id = $loggedInUser->id;
		$order->save();

		Common::storeAndUpdateOrder($order, $oldOrderId);

		// Save Order Payment
		if ($request->amount > 0 && $request->payment_mode_id != '') {
			$payment = new Payment();
			$payment->warehouse_id = $warehouse->id;
			$payment->payment_type = "in";
			$payment->date = Carbon::now();
			$payment->amount = $request->amount;
			$payment->paid_amount = $request->amount;
			$payment->payment_mode_id = $request->payment_mode_id;
			$payment->notes = $request->notes;
			$payment->user_id = $order->user_id;
			$payment->save();

			// Generate and save payment number
			$paymentType = 'payment-' . $payment->payment_type;
			$payment->payment_number = $this->getTransactionNumber($paymentType, $payment->id);
			$payment->save();

			$orderPayment = new OrderPayment();
			$orderPayment->order_id = $order->id;
			$orderPayment->payment_id = $payment->id;
			$orderPayment->amount = $request->amount;
			$orderPayment->save();
		}

		Common::updateOrderAmount($order->id);

		$savedOrder = Order::select('id', 'unique_id', 'invoice_number', 'user_id', 'order_date', 'discount', 'shipping', 'tax_amount', 'subtotal', 'total', 'paid_amount', 'due_amount', 'total_items', 'total_quantity')
			->with(['user:id,name', 'items:id,order_id,product_id,unit_id,unit_price,subtotal,quantity', 'items.product:id,name', 'items.unit:id,name,short_name'])
			->find($order->id);

		return ApiResponse::make('Data fetched', [
			'order' => $savedOrder
		]);
	}
}
