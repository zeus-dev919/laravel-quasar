<?php

namespace App\Http\Controllers\Api;

use App\Classes\Common;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Warehouse\IndexRequest;
use App\Http\Requests\Api\Warehouse\StoreRequest;
use App\Http\Requests\Api\Warehouse\UpdateRequest;
use App\Http\Requests\Api\Warehouse\DeleteRequest;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\UserDetails;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Examyou\RestAPI\Exceptions\ApiException;
use Tymon\JWTAuth\Claims\Custom;

class WarehouseController extends ApiBaseController
{
	protected $model = Warehouse::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function modifyIndex($query)
	{
		$loggedUser = user();

		if ($loggedUser && !$loggedUser->hasRole('admin')) {
			$query = $query->where('warehouses.id', '=', $loggedUser->warehouse_id);
		}

		return $query;
	}

	public function stored(Warehouse $warehouse)
	{
		$company = company();
		$companyWarehouse = $company->warehouse;

		$allProducts = Product::select('id')->get();
		foreach ($allProducts as $allProduct) {
			// Getting product details for company default warehouse
			$defaultWarehouseProductDetails = ProductDetails::withoutGlobalScope('current_warehouse')
				->where('warehouse_id', '=', $companyWarehouse->id)
				->where('product_id', '=', $allProduct->id)
				->first();

			// Inserting all products details for this warhouse
			$productDetails = new ProductDetails();
			$productDetails->warehouse_id = $warehouse->id;
			$productDetails->product_id = $allProduct->id;
			$productDetails->tax_id = $defaultWarehouseProductDetails->tax_id;
			$productDetails->mrp = $defaultWarehouseProductDetails->mrp;
			$productDetails->purchase_price = $defaultWarehouseProductDetails->purchase_price;
			$productDetails->sales_price = $defaultWarehouseProductDetails->sales_price;
			$productDetails->purchase_tax_type = $defaultWarehouseProductDetails->purchase_tax_type;
			$productDetails->sales_tax_type = $defaultWarehouseProductDetails->sales_tax_type;
			$productDetails->stock_quantitiy_alert = $defaultWarehouseProductDetails->stock_quantitiy_alert;
			$productDetails->wholesale_price = $defaultWarehouseProductDetails->wholesale_price;
			$productDetails->wholesale_quantity = $defaultWarehouseProductDetails->wholesale_quantity;
			$productDetails->save();

			// Common::updateProductCustomFields($allProduct, $productDetails->warehouse_id);
			Common::recalculateOrderStock($productDetails->warehouse_id, $allProduct->id);
		}

		// Creating user Details for each customer and supplier 
		// For this created warehouse
		$allCustomerSuppliers = Customer::withoutGlobalScope('type')
			->where('user_type', 'suppliers')
			->orWhere('user_type', 'customers')
			->get();
		foreach ($allCustomerSuppliers as $allCustomerSupplier) {
			$userDetails = new UserDetails();
			$userDetails->warehouse_id = $warehouse->xid;
			$userDetails->user_id = $allCustomerSupplier->id;
			$userDetails->credit_period = 30;
			$userDetails->save();
		}
	}

	public function destroying(Warehouse $warehouse)
	{
		if ($warehouse->id == $this->company->warehouse_id) {
			throw new ApiException('Default warehouse cannot be deleted');
		}

		return $warehouse;
	}
}
