<?php

namespace App\Classes;

use App\Models\Product;
use App\Models\Settings;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\MainNotificaiton;
use Examyou\RestAPI\Exceptions\ApiException;

class Notify
{
	public static function getData($sendFor, $sendData)
	{
		$data = [];

		if (in_array($sendFor, ['stock_adjustment_create', 'stock_adjustment_update', 'stock_adjustment_delete'])) {
			$warehouse = Warehouse::find($sendData->warehouse_id);
			$product = Product::find($sendData->product_id);
			$staffMember = User::find($sendData->created_by);

			$data['to'] = $warehouse;
			$data['product'] = $product;
			$data['warehouse'] = $warehouse;
			$data['stock_adjustment'] = $sendData;
			$data['staff_member'] = $staffMember;
		} else if (in_array($sendFor, ['staff_member_create', 'staff_member_update', 'staff_member_delete'])) {
			$warehouse = Warehouse::find($sendData->warehouse_id);

			$data['to'] = $warehouse;
			$data['warehouse'] = $warehouse;
			$data['staff_member'] = $sendData;
		} else if (in_array($sendFor, [
			'purchases_create', 'purchases_update', 'purchases_delete',
			'purchase_returns_create', 'purchase_returns_update', 'purchase_returns_delete',
			'sales_create', 'sales_update', 'sales_delete',
			'sales_returns_create', 'sales_returns_update', 'sales_returns_delete',
		])) {
			$warehouse = Warehouse::find($sendData->warehouse_id);
			$staffMember = User::find($sendData->staff_user_id);

			$data['to'] = $warehouse;
			$data['warehouse'] = $warehouse;
			$data['order'] = $sendData;
			$data['staff_member'] = $staffMember;
		} else if (in_array($sendFor, ['expense_create', 'expense_update', 'expense_delete'])) {
			$warehouse = Warehouse::find($sendData->warehouse_id);
			$staffMember = User::find($sendData->user_id);

			$data['to'] = $warehouse;
			$data['warehouse'] = $warehouse;
			$data['expense'] = $sendData;
			$data['staff_member'] = $staffMember;
		}

		return $data;
	}

	public static function isAbleToSendMail($sendFor, $dataArray)
	{
		$isAbleToSendMail = false;
		$content = "";
		$title = "";

		$mailSetting = Settings::where('setting_type', 'email')
			->where('name_key', 'smtp')
			->first();

		// For Email	
		if ($mailSetting && $mailSetting->status && $mailSetting->verified) {
			// $emailNameKey = self::getNameKey($sendFor);

			$sendMailSettings = Settings::where('setting_type', 'send_mail_settings')
				// ->where('name_key', $emailNameKey)
				->first();

			if ($sendMailSettings && $sendMailSettings->credentials && in_array($sendFor, $sendMailSettings->credentials)) {
				$isAbleToSendMail = true;

				// Retrieve $content and $title from database using $sendFor
				$notificationSetting = Settings::where('setting_type', 'email_templates')
					->where('name_key', $sendFor)
					->first();

				if (!$notificationSetting) {
					throw new ApiException('No email template found for ' . $sendFor);
				}

				$title = $notificationSetting && $notificationSetting->credentials ? $notificationSetting->credentials['title'] : '';
				$content = $notificationSetting && $notificationSetting->credentials ? $notificationSetting->credentials['content'] : '';

				$title = self::getReplacedHtml($title, $dataArray);
				$content = self::getReplacedHtml($content, $dataArray);
			}
		}

		return [
			'setting' => $mailSetting,
			'isAbleToSend' => $isAbleToSendMail,
			'content' => $content,
			'title' => $title
		];
	}

	public static function getDataArray($data)
	{
		$newDataKey = [
			'product_name' => $data && isset($data['product']) && isset($data['product']['name']) ? $data['product']['name'] : '',
			'warehouse_name' => $data && isset($data['warehouse']) && isset($data['warehouse']['name']) ? $data['warehouse']['name'] : '',
			'stock_adjustment_type' => $data && isset($data['stock_adjustment']) && isset($data['stock_adjustment']['adjustment_type']) ? $data['stock_adjustment']['adjustment_type'] : '',
			'stock_adjustment_quantity' => $data && isset($data['stock_adjustment']) && isset($data['stock_adjustment']['quantity']) ? $data['stock_adjustment']['quantity'] : '',
			'staff_member_name' => $data && isset($data['staff_member']) && isset($data['staff_member']['name']) ? $data['staff_member']['name'] : '',
			'invoice_number' => $data && isset($data['order']) && isset($data['order']['invoice_number']) ? $data['order']['invoice_number'] : '',
			'expense_amount' => $data && isset($data['expense']) && isset($data['expense']['amount']) ? $data['expense']['amount'] : '',
		];

		return $newDataKey;
	}

	public static function getReplacedHtml($html, $dataArray)
	{
		preg_match_all("/##(\w+)##/m", $html, $matches);

		if (isset($matches[1])) {
			foreach ($matches[1] as $match) {
				$html = str_replace('##' . $match . '##', $dataArray[$match], $html);
			}
		}

		return $html;
	}

	public static function send($sendFor, $sendData)
	{
		$data = self::getData($sendFor, $sendData);
		$dataArray = self::getDataArray($data);
		$sender = $data['to'];

		$notficationData = [
			'send_for' => $sendFor,
			'to' => $data['to'],
			'mail' => self::isAbleToSendMail($sendFor, $dataArray),
			'data' => $data,
		];

		$sender->notify(new MainNotificaiton($notficationData));
	}
}
