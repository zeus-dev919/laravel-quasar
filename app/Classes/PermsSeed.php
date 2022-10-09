<?php

namespace App\Classes;

use App\Models\Lang;
use App\Models\Permission;
use Nwidart\Modules\Facades\Module;

class PermsSeed
{
	public static $mainPermissionsArray = [
		// Brand
		'brands_view' => [
			'name' => 'brands_view',
			'display_name' => 'Brand View'
		],
		'brands_create' => [
			'name' => 'brands_create',
			'display_name' => 'Brand Create'
		],
		'brands_edit' => [
			'name' => 'brands_edit',
			'display_name' => 'Brand Edit'
		],
		'brands_delete' => [
			'name' => 'brands_delete',
			'display_name' => 'Brand Delete'
		],

		// Category
		'categories_view' => [
			'name' => 'categories_view',
			'display_name' => 'Category View'
		],
		'categories_create' => [
			'name' => 'categories_create',
			'display_name' => 'Category Create'
		],
		'categories_edit' => [
			'name' => 'categories_edit',
			'display_name' => 'Category Edit'
		],
		'categories_delete' => [
			'name' => 'categories_delete',
			'display_name' => 'Category Delete'
		],

		// Product
		'products_view' => [
			'name' => 'products_view',
			'display_name' => 'Product View'
		],
		'products_create' => [
			'name' => 'products_create',
			'display_name' => 'Product Create'
		],
		'products_edit' => [
			'name' => 'products_edit',
			'display_name' => 'Product Edit'
		],
		'products_delete' => [
			'name' => 'products_delete',
			'display_name' => 'Product Delete'
		],

		// Purchase
		'purchases_view' => [
			'name' => 'purchases_view',
			'display_name' => 'Purchase View'
		],
		'purchases_create' => [
			'name' => 'purchases_create',
			'display_name' => 'Purchase Create'
		],
		'purchases_edit' => [
			'name' => 'purchases_edit',
			'display_name' => 'Purchase Edit'
		],
		'purchases_delete' => [
			'name' => 'purchases_delete',
			'display_name' => 'Purchase Delete'
		],

		// Purchase Return
		'purchase_returns_view' => [
			'name' => 'purchase_returns_view',
			'display_name' => 'Purchase Return View'
		],
		'purchase_returns_create' => [
			'name' => 'purchase_returns_create',
			'display_name' => 'Purchase Return Create'
		],
		'purchase_returns_edit' => [
			'name' => 'purchase_returns_edit',
			'display_name' => 'Purchase Return Edit'
		],
		'purchase_returns_delete' => [
			'name' => 'purchase_returns_delete',
			'display_name' => 'Purchase Return Delete'
		],

		// Sales
		'sales_view' => [
			'name' => 'sales_view',
			'display_name' => 'Sales View'
		],
		'sales_create' => [
			'name' => 'sales_create',
			'display_name' => 'Sales Create'
		],
		'sales_edit' => [
			'name' => 'sales_edit',
			'display_name' => 'Sales Edit'
		],
		'sales_delete' => [
			'name' => 'sales_delete',
			'display_name' => 'Sales Delete'
		],

		// Sales Return
		'sales_returns_view' => [
			'name' => 'sales_returns_view',
			'display_name' => 'Sales Return View'
		],
		'sales_returns_create' => [
			'name' => 'sales_returns_create',
			'display_name' => 'Sales Return Create'
		],
		'sales_returns_edit' => [
			'name' => 'sales_returns_edit',
			'display_name' => 'Sales Return Edit'
		],
		'sales_returns_delete' => [
			'name' => 'sales_returns_delete',
			'display_name' => 'Sales Return Delete'
		],

		// Order Payments
		'order_payments_view' => [
			'name' => 'order_payments_view',
			'display_name' => 'Order Payments View'
		],
		'order_payments_create' => [
			'name' => 'order_payments_create',
			'display_name' => 'Order Payments Create'
		],
		'order_payments_edit' => [
			'name' => 'order_payments_edit',
			'display_name' => 'Order Payments Edit'
		],
		'order_payments_delete' => [
			'name' => 'order_payments_delete',
			'display_name' => 'Order Payments Delete'
		],

		// Stock Adjustment
		'stock_adjustments_view' => [
			'name' => 'stock_adjustments_view',
			'display_name' => 'Stock Adjustment View'
		],
		'stock_adjustments_create' => [
			'name' => 'stock_adjustments_create',
			'display_name' => 'Stock Adjustment Create'
		],
		'stock_adjustments_edit' => [
			'name' => 'stock_adjustments_edit',
			'display_name' => 'Stock Adjustment Edit'
		],
		'stock_adjustments_delete' => [
			'name' => 'stock_adjustments_delete',
			'display_name' => 'Stock Adjustment Delete'
		],

		// Expense Category
		'expense_categories_view' => [
			'name' => 'expense_categories_view',
			'display_name' => 'Expense Category View'
		],
		'expense_categories_create' => [
			'name' => 'expense_categories_create',
			'display_name' => 'Expense Category Create'
		],
		'expense_categories_edit' => [
			'name' => 'expense_categories_edit',
			'display_name' => 'Expense Category Edit'
		],
		'expense_categories_delete' => [
			'name' => 'expense_categories_delete',
			'display_name' => 'Expense Category Delete'
		],

		// Expense
		'expenses_view' => [
			'name' => 'expenses_view',
			'display_name' => 'Expense View'
		],
		'expenses_create' => [
			'name' => 'expenses_create',
			'display_name' => 'Expense Create'
		],
		'expenses_edit' => [
			'name' => 'expenses_edit',
			'display_name' => 'Expense Edit'
		],
		'expenses_delete' => [
			'name' => 'expenses_delete',
			'display_name' => 'Expense Delete'
		],

		// Unit
		'units_view' => [
			'name' => 'units_view',
			'display_name' => 'Unit View'
		],
		'units_create' => [
			'name' => 'units_create',
			'display_name' => 'Unit Create'
		],
		'units_edit' => [
			'name' => 'units_edit',
			'display_name' => 'Unit Edit'
		],
		'units_delete' => [
			'name' => 'units_delete',
			'display_name' => 'Unit Delete'
		],

		// Custom Fields
		'custom_fields_view' => [
			'name' => 'custom_fields_view',
			'display_name' => 'Custom Field View'
		],
		'custom_fields_create' => [
			'name' => 'custom_fields_create',
			'display_name' => 'Custom Field Create'
		],
		'custom_fields_edit' => [
			'name' => 'custom_fields_edit',
			'display_name' => 'Custom Field Edit'
		],
		'custom_fields_delete' => [
			'name' => 'custom_fields_delete',
			'display_name' => 'Custom Field Delete'
		],

		// Payment Mode
		'payment_modes_view' => [
			'name' => 'payment_modes_view',
			'display_name' => 'Payment Mode View'
		],
		'payment_modes_create' => [
			'name' => 'payment_modes_create',
			'display_name' => 'Payment Mode Create'
		],
		'payment_modes_edit' => [
			'name' => 'payment_modes_edit',
			'display_name' => 'Payment Mode Edit'
		],
		'payment_modes_delete' => [
			'name' => 'payment_modes_delete',
			'display_name' => 'Payment Mode Delete'
		],

		// Currency
		'currencies_view' => [
			'name' => 'currencies_view',
			'display_name' => 'Currency View'
		],
		'currencies_create' => [
			'name' => 'currencies_create',
			'display_name' => 'Currency Create'
		],
		'currencies_edit' => [
			'name' => 'currencies_edit',
			'display_name' => 'Currency Edit'
		],
		'currencies_delete' => [
			'name' => 'currencies_delete',
			'display_name' => 'Currency Delete'
		],

		// Tax
		'taxes_view' => [
			'name' => 'taxes_view',
			'display_name' => 'Tax View'
		],
		'taxes_create' => [
			'name' => 'taxes_create',
			'display_name' => 'Tax Create'
		],
		'taxes_edit' => [
			'name' => 'taxes_edit',
			'display_name' => 'Tax Edit'
		],
		'taxes_delete' => [
			'name' => 'taxes_delete',
			'display_name' => 'Tax Delete'
		],

		// Modules
		'modules_view' => [
			'name' => 'modules_view',
			'display_name' => 'Modules View'
		],

		// Role
		'roles_view' => [
			'name' => 'roles_view',
			'display_name' => 'Role View'
		],
		'roles_create' => [
			'name' => 'roles_create',
			'display_name' => 'Role Create'
		],
		'roles_edit' => [
			'name' => 'roles_edit',
			'display_name' => 'Role Edit'
		],
		'roles_delete' => [
			'name' => 'roles_delete',
			'display_name' => 'Role Delete'
		],

		// Warehouse
		'warehouses_view' => [
			'name' => 'warehouses_view',
			'display_name' => 'Warehouse View'
		],
		'warehouses_create' => [
			'name' => 'warehouses_create',
			'display_name' => 'Warehouse Create'
		],
		'warehouses_edit' => [
			'name' => 'warehouses_edit',
			'display_name' => 'Warehouse Edit'
		],
		'warehouses_delete' => [
			'name' => 'warehouses_delete',
			'display_name' => 'Warehouse Delete'
		],

		// Company
		'companies_edit' => [
			'name' => 'companies_edit',
			'display_name' => 'Company Edit'
		],

		// Translation
		'translations_view' => [
			'name' => 'translations_view',
			'display_name' => 'Translation View'
		],
		'translations_create' => [
			'name' => 'translations_create',
			'display_name' => 'Translation Create'
		],
		'translations_edit' => [
			'name' => 'translations_edit',
			'display_name' => 'Translation Edit'
		],
		'translations_delete' => [
			'name' => 'translations_delete',
			'display_name' => 'Translation Delete'
		],

		// Staff Member
		'users_view' => [
			'name' => 'users_view',
			'display_name' => 'Staff Member View'
		],
		'users_create' => [
			'name' => 'users_create',
			'display_name' => 'Staff Member Create'
		],
		'users_edit' => [
			'name' => 'users_edit',
			'display_name' => 'Staff Member Edit'
		],
		'users_delete' => [
			'name' => 'users_delete',
			'display_name' => 'Staff Member Delete'
		],

		// Customer
		'customers_view' => [
			'name' => 'customers_view',
			'display_name' => 'Customer View'
		],
		'customers_create' => [
			'name' => 'customers_create',
			'display_name' => 'Customer Create'
		],
		'customers_edit' => [
			'name' => 'customers_edit',
			'display_name' => 'Customer Edit'
		],
		'customers_delete' => [
			'name' => 'customers_delete',
			'display_name' => 'Customer Delete'
		],

		// Supplier
		'suppliers_view' => [
			'name' => 'suppliers_view',
			'display_name' => 'Supplier View'
		],
		'suppliers_create' => [
			'name' => 'suppliers_create',
			'display_name' => 'Supplier Create'
		],
		'suppliers_edit' => [
			'name' => 'suppliers_edit',
			'display_name' => 'Supplier Edit'
		],
		'suppliers_delete' => [
			'name' => 'suppliers_delete',
			'display_name' => 'Supplier Delete'
		],

		// Storage Settings
		'storage_edit' => [
			'name' => 'storage_edit',
			'display_name' => 'Storage Settings Edit'
		],

		// Email Settings
		'email_edit' => [
			'name' => 'email_edit',
			'display_name' => 'Email Settings Edit'
		],

		// POS
		'pos_view' => [
			'name' => 'pos_view',
			'display_name' => 'POS View'
		],

		// Update App
		'update_app' => [
			'name' => 'update_app',
			'display_name' => 'Update App'
		],

	];

	public static $eStorePermissions = [];

	public static function getPermissionArray($moduleName)
	{
		if ($moduleName == 'Estore') {
			return self::$eStorePermissions;
		}

		return self::$mainPermissionsArray;
	}

	public static function seedPermissions($moduleName = '')
	{
		$permissions = self::getPermissionArray($moduleName);

		foreach ($permissions as $group => $permission) {
			$permissionCount = Permission::where('name', $permission['name'])->count();

			if ($permissionCount == 0) {
				$newPermission = new Permission();
				$newPermission->name = $permission['name'];
				$newPermission->display_name = $permission['display_name'];
				$newPermission->save();
			}
		}
	}

	public static function seedAllModulesTranslations()
	{
		// Main Module
		self::seedPermissions();

		$allModules = Module::all();
		foreach ($allModules as $allModule) {
			self::seedPermissions($allModule);
		}
	}
}
