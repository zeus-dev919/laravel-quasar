<template>
	<a-modal
		:visible="visible"
		:centered="true"
		:maskClosable="false"
		:title="$t('common.print_invoice')"
		width="400px"
		@cancel="onClose"
	>
		<div id="pos-invoice">
			<div style="max-width: 400px; margin: 0px auto" v-if="order && order.xid">
				<div class="invoice-header">
					<img
						class="invoice-logo"
						:src="appSetting.light_logo_url"
						:alt="appSetting.name"
					/>
				</div>
				<div class="company-details">
					<h2>{{ appSetting.name }}</h2>
					<p class="company-address">
						{{ appSetting.address }}
					</p>
					<h4 style="margin-bottom: 0px">
						{{ $t("common.phone") }}: {{ appSetting.phone }}
					</h4>
					<h4>{{ $t("common.email") }}: {{ appSetting.email }}</h4>
				</div>
				<div class="tax-invoice-details">
					<h3 class="tax-invoice-title">{{ $t("sales.tax_invoice") }}</h3>
					<table class="invoice-customer-details">
						<tr>
							<td style="width: 50%">
								{{ $t("sales.invoice") }} &nbsp;&nbsp;&nbsp;&nbsp;:
								{{ order.invoice_number }}
							</td>
							<td style="width: 50%">
								{{ $t("common.date") }} :
								{{ formatDate(order.order_date) }}
							</td>
						</tr>
						<tr>
							<td>{{ $t("stock.customer") }} : {{ order.user.name }}</td>
						</tr>
					</table>
				</div>
				<div class="tax-invoice-items">
					<table style="width: 100%">
						<thead style="background: #eee">
							<td style="width: 5%">#</td>
							<td style="width: 25%">{{ $t("common.item") }}</td>
							<td style="width: 20%">{{ $t("common.qty") }}</td>
							<td style="width: 25%">{{ $t("common.rate") }}</td>
							<td style="width: 25%; text-align: right">
								{{ $t("common.total") }}
							</td>
						</thead>
						<tbody>
							<tr
								class="item-row"
								v-for="(item, index) in order.items"
								:key="item.xid"
							>
								<td>{{ index + 1 }}</td>
								<td>{{ item.product.name }}</td>
								<td>{{ item.quantity + "" + item.unit.short_name }}</td>
								<td>{{ formatAmountCurrency(item.unit_price) }}</td>
								<td style="text-align: right">
									{{ formatAmountCurrency(item.subtotal) }}
								</td>
							</tr>
							<tr class="item-row-other">
								<td colspan="3" style="text-align: right">
									{{ $t("stock.order_tax") }}
								</td>
								<td colspan="2" style="text-align: right">
									{{ formatAmountCurrency(order.tax_amount) }}
								</td>
							</tr>
							<tr class="item-row-other">
								<td colspan="3" style="text-align: right">
									{{ $t("stock.discount") }}
								</td>
								<td colspan="2" style="text-align: right">
									{{ formatAmountCurrency(order.discount) }}
								</td>
							</tr>
							<tr class="item-row-other">
								<td colspan="3" style="text-align: right">
									{{ $t("stock.shipping") }}
								</td>
								<td colspan="2" style="text-align: right">
									{{ formatAmountCurrency(order.shipping) }}
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tax-invoice-totals">
					<table style="width: 100%">
						<tr>
							<td style="width: 30%">
								<h3 style="margin-bottom: 0px">
									{{ $t("common.items") }}: {{ order.total_items }}
								</h3>
							</td>
							<td style="width: 30%">
								<h3 style="margin-bottom: 0px">
									{{ $t("common.qty") }}: {{ order.total_quantity }}
								</h3>
							</td>
							<td style="width: 40%; text-align: center">
								<h3 style="margin-bottom: 0px">
									{{ $t("common.total") }}:
									{{ formatAmountCurrency(order.total) }}
								</h3>
							</td>
						</tr>
					</table>
				</div>
				<div class="paid-amount-deatils">
					<table style="width: 100%">
						<thead style="background: #eee">
							<td style="width: 50%">{{ $t("payments.paid_amount") }}</td>
							<td style="width: 50%">{{ $t("payments.due_amount") }}</td>
						</thead>
						<tbody>
							<tr class="paid-amount-row">
								<td>{{ formatAmountCurrency(order.paid_amount) }}</td>
								<td>{{ formatAmountCurrency(order.due_amount) }}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="barcode-details">
					<vue-barcode
						:value="order.invoice_number"
						:options="{ height: 25, width: 1, fontSize: 15 }"
						tag="svg"
					></vue-barcode>
				</div>
				<div class="thanks-details">
					<h3>Thank You For Shopping With Us. Please Come Again</h3>
				</div>
			</div>
		</div>

		<template #footer>
			<div class="footer-button">
				<a-button type="primary" @click="printInvoice">
					<template #icon>
						<PrinterOutlined />
					</template>
					{{ $t("common.print_invoice") }}
				</a-button>
			</div>
		</template>
	</a-modal>
</template>

<script>
import { ref, defineComponent } from "vue";
import { PrinterOutlined } from "@ant-design/icons-vue";
import common from "../../../../common/composable/common";
const posInvoiceCssUrl = window.config.pos_invoice_css;

export default defineComponent({
	props: ["visible", "order"],
	emits: ["closed", "success"],
	components: {
		PrinterOutlined,
	},
	setup(props, { emit }) {
		const { appSetting, formatAmountCurrency, formatDate } = common();

		const onClose = () => {
			emit("closed");
		};

		const printInvoice = () => {
			var invoiceContent = document.getElementById("pos-invoice").innerHTML;
			var newWindow = window.open("", "", "height=500, width=500");
			newWindow.document.write(
				`<link rel="stylesheet" href="${posInvoiceCssUrl}"><html><body>`
			);
			newWindow.document.write(invoiceContent);
			newWindow.document.write("</body></html>");
			newWindow.document.close();
			newWindow.print();
		};

		return {
			appSetting,
			onClose,
			formatDate,
			formatAmountCurrency,
			printInvoice,
		};
	},
});
</script>
