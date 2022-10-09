<?php

namespace App\Http\Controllers\Api;

use App\Classes\Common;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Product\IndexRequest;
use App\Http\Requests\Api\Product\StoreRequest;
use App\Http\Requests\Api\Product\UpdateRequest;
use App\Http\Requests\Api\Product\DeleteRequest;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Examyou\RestAPI\ApiResponse;
use Vinkla\Hashids\Facades\Hashids;

class ProductController extends ApiBaseController
{
	protected $model = Product::class;

	protected $indexRequest = IndexRequest::class;
	protected $storeRequest = StoreRequest::class;
	protected $updateRequest = UpdateRequest::class;
	protected $deleteRequest = DeleteRequest::class;

	public function modifyIndex($query)
	{
		$request = request();
		$warehouse = warehouse();

		$query = $query->join('product_details', 'product_details.product_id', '=', 'products.id')
			->where('product_details.warehouse_id', $warehouse->id);

		if ($request->has('fetch_stock_alert') && $request->fetch_stock_alert) {
			$query = $query->whereNotNull('stock_quantitiy_alert')
				->whereRaw('product_details.current_stock <= product_details.stock_quantitiy_alert');
		};

		return $query;
	}

	public function storing(Product $product)
	{
		$product->user_id = user()->id;

		return $product;
	}
	public function stored(Product $product)
	{
		$request = request();
		$allWarehouses = Warehouse::select('id')->get();

		foreach ($allWarehouses as $allWarehouse) {
			$productDetails = new ProductDetails();
			$productDetails->warehouse_id = $allWarehouse->id;
			$productDetails->product_id = $product->id;

			$productDetails->tax_id = $request->has('tax_id') && $request->tax_id != '' ? $request->tax_id : null;
			$productDetails->mrp = $request->mrp;
			$productDetails->purchase_price = $request->purchase_price;
			$productDetails->sales_price = $request->sales_price;
			$productDetails->purchase_tax_type = $request->purchase_tax_type;
			$productDetails->sales_tax_type = $request->sales_tax_type;
			$productDetails->stock_quantitiy_alert = $request->stock_quantitiy_alert;
			$productDetails->wholesale_price = $request->wholesale_price;
			$productDetails->wholesale_quantity = $request->wholesale_quantity;
			$productDetails->save();

			Common::updateProductCustomFields($product, $allWarehouse->id);
		}

		// Saving Opening Stock and date for current product
		// Becuase these values will be different for each product
		// At warehouse
		$currentProductDetails = $product->details;
		$currentProductDetails->opening_stock = $request->opening_stock;
		$currentProductDetails->opening_stock_date = $request->opening_stock_date;
		$currentProductDetails->save();

		Common::recalculateOrderStock($currentProductDetails->warehouse_id, $product->id);
	}

	public function updated(Product $product)
	{
		$request = request();

		$currentProductDetails = $product->details;
		$currentProductDetails->tax_id = $request->has('tax_id') && $request->tax_id != '' ? $request->tax_id : null;
		$currentProductDetails->mrp = $request->mrp;
		$currentProductDetails->purchase_price = $request->purchase_price;
		$currentProductDetails->sales_price = $request->sales_price;
		$currentProductDetails->purchase_tax_type = $request->purchase_tax_type;
		$currentProductDetails->sales_tax_type = $request->sales_tax_type;
		$currentProductDetails->stock_quantitiy_alert = $request->stock_quantitiy_alert;
		$currentProductDetails->wholesale_price = $request->wholesale_price;
		$currentProductDetails->wholesale_quantity = $request->wholesale_quantity;
		$currentProductDetails->opening_stock = $request->opening_stock;
		$currentProductDetails->opening_stock_date = $request->opening_stock_date;
		$currentProductDetails->save();

		Common::updateProductCustomFields($product, $currentProductDetails->warehouse_id);
		Common::recalculateOrderStock($currentProductDetails->warehouse_id, $product->id);
	}

	public function searchProduct(Request $request)
	{
		$warehouse = warehouse();
		$searchTerm = $request->search_term;
		$orderType = $request->order_type;
		$warehouseId = $warehouse->id;

		$products = Product::select('products.id', 'products.name', 'products.image', 'products.unit_id')
			->where('products.name', 'LIKE', "%$searchTerm%");

		if ($request->has('products')) {
			$selectedProducts = $request->products;
			$convertedSelectedProducts = [];
			if (count($selectedProducts) > 0) {
				foreach ($selectedProducts as $selectedProduct) {
					$convertedSelectedProducts[] = $this->getIdFromHash($selectedProduct);
				}
			}
			$products = $products->whereNotIn('products.id', $convertedSelectedProducts);
		}

		$products = $products->get();

		$allProducs = [];

		if ($warehouseId == '') {
			return $allProducs;
		} else {
			$warehouseHashId = Hashids::decode($warehouseId);
			if (count($warehouseHashId) > 0) {
				$warehouseId = $warehouseHashId[0];
			}
		}

		foreach ($products as $product) {
			$productDetails = $product->details;
			$tax = Tax::find($productDetails->tax_id);

			if ($orderType == 'purchases' || ($orderType == 'sales' && $productDetails->current_stock > 0) || ($orderType == 'sales-returns') || ($orderType == 'purchase-returns' && $productDetails->current_stock > 0)) {
				$stockQuantity = $productDetails->current_stock;
				$unit = $product->unit_id != null ? Unit::find($product->unit_id) : null;

				if ($orderType == 'purchases' || $orderType == 'purchase-returns') {
					$unitPrice = $productDetails->purchase_price;
					$taxType = $productDetails->purchase_tax_type;
				} else if ($orderType == 'sales' || $orderType == 'sales-returns') {
					$unitPrice = $productDetails->sales_price;
					$taxType = $productDetails->sales_tax_type;
				}

				$singleUnitPrice = $unitPrice;

				if ($tax && $tax->rate != '') {
					$taxRate = $tax->rate;

					if ($taxType == 'inclusive') {
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
					'x_tax_id'    =>  $tax ? $tax->xid : null,
					'tax_type'    =>  $taxType,
					'tax_rate'    =>  $taxRate,
					'total_tax'    =>  $taxAmount,
					'x_unit_id'    =>  Hashids::encode($product->unit_id),
					'unit'    =>  $unit,
					'unit_price'    =>  $unitPrice,
					'single_unit_price'    =>  $singleUnitPrice,
					'subtotal'    =>  $subTotal,
					'quantity'    =>  1,
					'stock_quantity'    =>  $stockQuantity,
					'unit_short_name'    =>  $unit ? $unit->short_name : '',
				];
			}

			// All Type products
			if (!$request->has('order_type')) {
				$allProducs[] = [
					'xid'    =>  $product->xid,
					'name'    =>  $product->name,
					'image'    =>  $product->image,
					'image_url'    =>  $product->image_url,
					'stock_quantity'    =>  $productDetails->current_stock,
				];
			}
		}

		return ApiResponse::make('Fetched Successfully', $allProducs);
	}

	public function getWarehouseStock(Request $request)
	{
		$warehouse = warehouse();
		$stockValue = "-";

		$warehouseId = $warehouse->id;
		$productId = $this->getIdFromHash($request->product_id);

		if ($warehouseId != '' && $productId != '') {
			$stock = ProductDetails::withoutGlobalScope('current_warehouse')
				->where('warehouse_id', '=', $warehouseId)
				->where('product_id', '=', $productId)
				->first();

			if ($stock) {
				$stockValue = $stock->current_stock;
			}
		}

		return ApiResponse::make('Fetched Successfully', ['stock' => $stockValue]);
	}
}
