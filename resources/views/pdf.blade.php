<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>A simple, clean, and responsive HTML invoice template</title>

	<style>
		@page {
			margin: 20px;
		}

		* {
			font-family: DejaVu Sans, sans-serif;
		}

		body {
			margin: 20px;
			position: relative;
			width: 100%;
			height: auto;
			margin: 0 auto;
			color: #555555;
			background: #FFFFFF;
			font-size: 12px;
			font-family: Verdana, Arial, Helvetica, sans-serif;
		}

		.invoice-box {
			max-width: 800px;
			margin: auto;

			font-size: 14px;
			line-height: 18px;
			font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			color: #555;
		}

		.invoice-box table {
			width: 100%;
			line-height: inherit;
			text-align: left;
		}

		.invoice-box table td {
			padding: 5px;
			vertical-align: top;
		}

		.invoice-box table tr td:nth-child(5) {
			text-align: right;
		}

		.invoice-box table tr.top table td {
			padding-bottom: 20px;
		}

		.invoice-box table tr.information table td {
			padding-bottom: 40px;
		}

		.invoice-box table tr.heading td {
			background: #eee;
			border-bottom: 1px solid #ddd;
			font-weight: bold;
		}

		.invoice-box table tr.main-heading td {
			background: rgb(218, 218, 218);
			border-bottom: 1px solid #ddd;
			font-weight: bold;
		}

		.invoice-box table tr.details td {
			padding-bottom: 20px;
		}

		.invoice-box table tr.item td {
			border-bottom: 1px solid #eee;
		}

		.invoice-box table tr.item.last td {
			border-bottom: none;
		}

		@media only screen and (max-width: 600px) {
			.invoice-box table tr.top table td {
				width: 100%;
				display: block;
				text-align: left;
			}

			.invoice-box table tr.information table td {
				width: 100%;
				display: block;
				text-align: left;
			}
		}

		/** RTL **/
		.invoice-box.rtl {
			direction: rtl;
			font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
		}

		.invoice-box.rtl table {
			text-align: left;
		}

		.invoice-box.rtl table tr td:nth-child(5) {
			text-align: right;
		}

		/** New CSS **/
		.text-left {
			text-align: left;
		}

		.text-right {
			text-align: right;
		}

		.text-center {
			text-align: center;
		}

		.divider {
			line-height: 1.5715;
			color: #000000d9;
			border-top: 1px solid rgba(0, 0, 0, .10);
		}

		.company-info {
			text-align: left;
		}

		.company-title {
			font-weight: bold;
			font-size: 18px;
		}

		p {
			margin: 0;
		}

		.clearfix {
			display: block;
			clear: both;
		}

		.mt-10 {
			margin-top: 10px;
		}

		.mt-20 {
			margin-top: 20px;
		}

		.total-bg {
			background: #eee;
		}
	</style>
</head>

<body>
	<div class="invoice-box">
		<table cellpadding="0" cellspacing="0">
			<tr class="main-heading">
				@if($order->order_type == "purchases")
				<td class="text-center">Purchase Invoice</td>
				@elseif($order->order_type == "purchase-returns")
				<td class="text-center">Purchase Returns Invoice</td>
				@elseif($order->order_type == "sales-returns")
				<td class="text-center">Sales Returns Invoice</td>
				@elseif($order->order_type == "sales")
				<td class="text-center">Sales Invoice</td>
				@endif
			</tr>
		</table>

		<table cellpadding="0" cellspacing="0">
			<tr class="top">
				<td colspan="2">
					<table>
						<tr>
							<td style="vertical-align: middle;">
								<img src="{{ $company->logo_url }}" style="width: 150px">
							</td>
							<td class="text-right">
								<span class="company-title">
									{{ $company->name }} <br>
								</span>
								<span>
									{{ $company->address }} <br>
									{{ $company->email }} <br>
									{{ $company->phone }}
								</span>
							</td>


						</tr>
					</table>
				</td>
			</tr>

		</table>

		<div class="divider"></div>

		<table cellpadding="0" cellspacing="0">
			<tr class="information">
				<td colspan="2">
					<table>
						<tr>
							<td class="text-left">
								Invoice #: {{ $order->invoice_number }}<br />
								Order Date: {{ $order->order_date->format($dateTimeFormat) }}<br />
								Order Status: {{ $order->order_status }}<br />
								Payment Status: {{ $order->payment_status }}
							</td>

							<td class="text-right">
								<b>@if($order->order_type == "sales" || $order->order_type == "sales-returns") Bill To @elseif($order->order_type == "purchases" || $order->order_type == "purchase-returns") Bill From @endif:</b><br />
								{{ $order->user->name }}<br />
								@if($order->user->address || $order->user->city || $order->user->zipcode)
								{{ $order->user->address .'' .  $order->user->city .''. $order->user->zipcode }} <br />
								@endif
								@if($order->user->phone)
								{{ $order->user->phone }} <br />
								@endif
								{{ $order->user->email }}
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>


		<table cellpadding="0" cellspacing="0">

			<tr class="heading">
				<td>#</td>
				<td>Product</td>
				<td>Unit Price</td>
				<td>Quantity</td>
				<td>Total</td>
			</tr>

			@foreach($order->items as $item)
			<tr class="item">
				<td>{{ $loop->iteration }}</td>
				<td>{{ $item->product->name }}</td>
				<td>{{ $company->currency->symbol .''. $item->single_unit_price }}</td>
				<td>{{ $item->quantity . ' ' . $item->unit->short_name }}</td>
				<td>{{ $company->currency->symbol .''. $item->subtotal }}</td>
			</tr>
			@endforeach

		</table>

		<div class="divider"></div>

		<div class="mt-20">
			<div style="width: 65%; float: left;">
				<p class="mt-20" style="font-weight: bold; font-size: 14px;">
					Note:
				</p>
				<p>{{ $order->notes }}</p>
			</div>
			<div style="width: 30%; float: right;">
				<table cellpadding="0" cellspacing="0" class="mt-10">
					<tr>
						<td>Subtotal</td>
						<td class="text-right">{{ $company->currency->symbol .''. $order->subtotal }}</td>
					</tr>
					<tr>
						<td>Tax</td>
						<td class="text-right">{{ $company->currency->symbol .''. $order->tax_amount }} ({{ $order->tax_rate }}%)</td>
					</tr>
					<tr>
						<td>Discount</td>
						<td class="text-right">{{ $company->currency->symbol .''. $order->discount }}</td>
					</tr>
					<tr>
						<td>Shipping</td>
						<td class="text-right">{{ $company->currency->symbol .''. $order->shipping }}</td>
					</tr>
					<tr>
						<td class="total-bg"><b>Total</b></td>
						<td class="text-right total-bg">{{ $company->currency->symbol .''. $order->total }}</td>
					</tr>
				</table>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="divider mt-10"></div>
		<table cellpadding="0" cellspacing="0">
			<tr class="heading">
				<td>Total Items / Qty : {{ $order->total_items . ' / ' . $order->total_quantity }}</td>
				<td>Paid Amount: {{ $company->currency->symbol .''. $order->paid_amount }}</td>
				<td>Due Amount: {{ $company->currency->symbol .''. $order->due_amount }}</td>
			</tr>
		</table>
		<div class="divider"></div>

	</div>
</body>

</html>