<?php

namespace App\Classes;

use App\Models\Settings;

class NotificationSeed
{
	public static $emailNotificaiotnArray = [
		// Stock Adjustment
		'stock_adjustment_create' => [
			'type' => "email_templates",
			'title' => "Stock adjustment created",
			'content' => "Stock adjustment created by ##staff_member_name## for warehouse ##warehouse_name## for product ##product_name## with ##stock_adjustment_quantity## (##stock_adjustment_type##) quantity",
			'other_data' => [
				'product' => [
					'prodcut_name' => 'Name',
				],
				'warehouse' => [
					'warehouse_name' => 'Name',
				],
				'stock_adjustment' => [
					'stock_adjustment_quantity' => 'Quantity',
					'stock_adjustment_type' => 'Adjustment Type',
				],
				'user' => [
					'staff_member_name' => 'Created By',
				],
			]
		],
		'stock_adjustment_update' => [
			'type' => "email_templates",
			'title' => "Stock adjustment updated",
			'content' => "Stock adjustment updated by ##staff_member_name## for warehouse ##warehouse_name## for product ##product_name## with ##stock_adjustment_quantity## (##stock_adjustment_type##) quantity",
			'other_data' => [
				'product' => [
					'prodcut_name' => 'Name',
				],
				'warehouse' => [
					'warehouse_name' => 'Name',
				],
				'stock_adjustment' => [
					'stock_adjustment_quantity' => 'Quantity',
					'stock_adjustment_type' => 'Adjustment Type',
				],
				'user' => [
					'staff_member_name' => 'Created By',
				],
			]
		],
		'stock_adjustment_delete' => [
			'type' => "email_templates",
			'title' => "Stock adjustment deleted",
			'content' => "Stock adjustment deleted by ##staff_member_name## for warehouse ##warehouse_name## for product ##product_name## with ##stock_adjustment_quantity## (##stock_adjustment_type##) quantity",
			'other_data' => [
				'product' => [
					'prodcut_name' => 'Name',
				],
				'warehouse' => [
					'warehouse_name' => 'Name',
				],
				'stock_adjustment' => [
					'stock_adjustment_quantity' => 'Quantity',
					'stock_adjustment_type' => 'Adjustment Type',
				],
				'user' => [
					'staff_member_name' => 'Created By',
				],
			]
		],

		// Staff Members
		'staff_member_create' => [
			'type' => "email_templates",
			'title' => "Staff Member created",
			'content' => "A new staff Member added with ##staff_member_name## name in your warehouse ##warehouse_name##.",
			'other_data' => []
		],
		'staff_member_update' => [
			'type' => "email_templates",
			'title' => "Staff Member updated",
			'content' => "Staff Member with name ##staff_member_name## updated in your warehouse ##warehouse_name##.",
			'other_data' => []
		],
		'staff_member_delete' => [
			'type' => "email_templates",
			'title' => "Staff Member deleted",
			'content' => "Staff member with name ##staff_member_name## deleted from your warehouse ##warehouse_name##.",
			'other_data' => []
		],

		// Purchase
		'purchases_create' => [
			'type' => "email_templates",
			'title' => "Purchase created",
			'content' => "A new purhcase added by ##staff_member_name## in your warehouse ##warehouse_name## with invoic number ##invoice_number##.",
			'other_data' => []
		],
		'purchases_update' => [
			'type' => "email_templates",
			'title' => "Purchase updated",
			'content' => "Purchase with invoice number ##invoice_number## updated by ##staff_member_name## in your warehouse ##warehouse_name##.",
			'other_data' => []
		],
		'purchases_delete' => [
			'type' => "email_templates",
			'title' => "Purchase deleted",
			'content' => "Purchase with invoice number ##invoice_number## deleted by ##staff_member_name## from your warehouse ##warehouse_name##.",
			'other_data' => []
		],

		// Purchase Returns
		'purchase_returns_create' => [
			'type' => "email_templates",
			'title' => "Purchase created",
			'content' => "A new purhcase return added by ##staff_member_name## in your warehouse ##warehouse_name## with invoic number ##invoice_number##.",
			'other_data' => []
		],
		'purchase_returns_update' => [
			'type' => "email_templates",
			'title' => "Purchase updated",
			'content' => "Purchase return with invoice number ##invoice_number## updated by ##staff_member_name## in your warehouse ##warehouse_name##.",
			'other_data' => []
		],
		'purchase_returns_delete' => [
			'type' => "email_templates",
			'title' => "Purchase return deleted",
			'content' => "Purchase return with invoice number ##invoice_number## deleted by ##staff_member_name## from your warehouse ##warehouse_name##.",
			'other_data' => []
		],

		// Sales
		'sales_create' => [
			'type' => "email_templates",
			'title' => "Sales created",
			'content' => "A new sales added by ##staff_member_name## name in your warehouse ##warehouse_name## with invoic number ##invoice_number##.",
			'other_data' => []
		],
		'sales_update' => [
			'type' => "email_templates",
			'title' => "Sales updated",
			'content' => "Sales with invoice number ##invoice_number## updated by ##staff_member_name## in your warehouse ##warehouse_name##.",
			'other_data' => []
		],
		'sales_delete' => [
			'type' => "email_templates",
			'title' => "Sales deleted",
			'content' => "Sales with invoice number ##invoice_number## deleted by ##staff_member_name## from your warehouse ##warehouse_name##.",
			'other_data' => []
		],

		// Sales Return
		'sales_returns_create' => [
			'type' => "email_templates",
			'title' => "Sales return created",
			'content' => "A new sales return added by ##staff_member_name## in your warehouse ##warehouse_name## with invoic number ##invoice_number##.",
			'other_data' => []
		],
		'sales_returns_update' => [
			'type' => "email_templates",
			'title' => "Sales return updated",
			'content' => "Sales return with invoice number ##invoice_number## updated by ##staff_member_name## in your warehouse ##warehouse_name##.",
			'other_data' => []
		],
		'sales_returns_delete' => [
			'type' => "email_templates",
			'title' => "Sales return deleted",
			'content' => "Sales return with invoice number ##invoice_number## deleted by ##staff_member_name## from your warehouse ##warehouse_name##.",
			'other_data' => []
		],

		// Sales Return
		'expense_create' => [
			'type' => "email_templates",
			'title' => "Expense created",
			'content' => "A new expense added by ##staff_member_name## in your warehouse ##warehouse_name## with amount ##expense_amount##.",
			'other_data' => []
		],
		'expense_update' => [
			'type' => "email_templates",
			'title' => "Expense updated",
			'content' => "Expense updated by ##staff_member_name## in your warehouse ##warehouse_name## with amount ##expense_amount##.",
			'other_data' => []
		],
		'expense_delete' => [
			'type' => "email_templates",
			'title' => "Expense deleted",
			'content' => "Expense deleted by ##staff_member_name## from your warehouse ##warehouse_name## with amount ##expense_amount##.",
			'other_data' => []
		],
	];


	public static function getNotificationArray($moduleName)
	{
		return self::$emailNotificaiotnArray;
	}

	public static function seedNotifications($moduleName = '')
	{
		$notifications = self::getNotificationArray($moduleName);

		foreach ($notifications as $key => $notification) {
			$notificationCount = Settings::where('setting_type', $notification['type'])
				->where('name_key', $key)
				->count();

			if ($notificationCount == 0) {
				$newNotification = new Settings();
				$newNotification->setting_type = $notification['type'];
				$newNotification->name = $notification['title'];
				$newNotification->name_key = $key;
				$newNotification->credentials = [
					'title' => $notification['title'],
					'content' => $notification['content'],
				];
				// $newNotification->other_data = $notification['other_data'];
				$newNotification->save();
			}
		}
	}

	public static function seedAllModulesNotifications()
	{
		// Main Module
		self::seedNotifications();
	}
}
