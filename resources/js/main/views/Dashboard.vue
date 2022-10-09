<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.dashboard`)" style="padding: 0px" />
		</template>
	</AdminPageHeader>

	<div class="dashboard-page-content-container">
		<UpdateAppAlert />

		<a-row :gutter="[8, 8]" class="mt-30 mb-10">
			<a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
				<DateRangePicker
					ref="serachDateRangePicker"
					@dateTimeChanged="
						(changedDateTime) => (filters.dates = changedDateTime)
					"
				/>
			</a-col>
		</a-row>

		<div class="mt-30 mb-20">
			<a-row :gutter="[15, 15]">
				<a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
					<StateWidget>
						<template #image>
							<LineChartOutlined style="color: #fff; font-size: 24px" />
						</template>
						<template #description>
							<h2 v-if="responseData.stateData">
								{{
									formatAmountCurrency(
										responseData.stateData.totalSales
									)
								}}
							</h2>
							<p>{{ $t("dashboard.total_sales") }}</p>
						</template>
					</StateWidget>
				</a-col>

				<a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
					<StateWidget>
						<template #image>
							<ShoppingOutlined style="color: #fff; font-size: 24px" />
						</template>
						<template #description>
							<h2 v-if="responseData.stateData">
								{{
									formatAmountCurrency(
										responseData.stateData.totalExpenses
									)
								}}
							</h2>
							<p>{{ $t("dashboard.total_expenses") }}</p>
						</template>
					</StateWidget>
				</a-col>

				<a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
					<StateWidget>
						<template #image>
							<TagOutlined style="color: #fff; font-size: 24px" />
						</template>
						<template #description>
							<h2 v-if="responseData.stateData">
								{{
									formatAmountCurrency(
										responseData.stateData.paymentSent
									)
								}}
							</h2>
							<p>{{ $t("dashboard.payment_sent") }}</p>
						</template>
					</StateWidget>
				</a-col>

				<a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
					<StateWidget>
						<template #image>
							<BankOutlined style="color: #fff; font-size: 24px" />
						</template>
						<template #description>
							<h2 v-if="responseData.stateData">
								{{
									formatAmountCurrency(
										responseData.stateData.paymentReceived
									)
								}}
							</h2>
							<p>{{ $t("dashboard.payment_received") }}</p>
						</template>
					</StateWidget>
				</a-col>
			</a-row>
		</div>

		<a-row :gutter="[18, 18]" class="mt-30 mb-20">
			<a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
				<a-card :title="$t('dashboard.top_selling_product')">
					<TopProducts :data="responseData" />
				</a-card>
			</a-col>
			<a-col :xs="24" :sm="24" :md="12" :lg="18" :xl="18">
				<a-card :title="$t('dashboard.sales_purchases')">
					<PurchaseSales :data="responseData" />
					<template
						v-if="
							permsArray.includes('sales_view') ||
							permsArray.includes('admin')
						"
						#extra
					>
						<a-button
							class="mt-10"
							type="link"
							@click="
								$router.push({
									name: 'admin.stock.sales.index',
								})
							"
						>
							{{ $t("common.view_all") }}
							<DoubleRightOutlined />
						</a-button>
					</template>
				</a-card>
			</a-col>
		</a-row>

		<a-row
			class="mt-30 mb-20"
			v-if="
				(permsArray.includes('purchases_view') ||
					permsArray.includes('sales_view') ||
					permsArray.includes('purchase_returns_view') ||
					permsArray.includes('sales_returns_view') ||
					permsArray.includes('admin')) &&
				activeOrderType != ''
			"
		>
			<a-col :span="24">
				<a-card
					:title="$t('dashboard.recent_stock_history')"
					:bodyStyle="{ paddingTop: '0px' }"
				>
					<template #extra>
						<a-tabs v-model:activeKey="activeOrderType">
							<a-tab-pane
								v-if="
									permsArray.includes('sales_view') ||
									permsArray.includes('admin')
								"
								key="sales"
								:tab="$t('menu.sales')"
							/>
							<a-tab-pane
								v-if="
									permsArray.includes('purchases_view') ||
									permsArray.includes('admin')
								"
								key="purchases"
								:tab="$t('menu.purchases')"
							/>
							<a-tab-pane
								v-if="
									permsArray.includes('purchase_returns_view') ||
									permsArray.includes('admin')
								"
								key="purchase-returns"
								:tab="$t('menu.purchase_returns')"
							/>
							<a-tab-pane
								v-if="
									permsArray.includes('sales_returns_view') ||
									permsArray.includes('admin')
								"
								key="sales-returns"
								:tab="$t('menu.sales_returns')"
							/>
						</a-tabs>
					</template>
					<a-row>
						<a-col
							:xs="24"
							:sm="24"
							:md="12"
							:lg="6"
							:xl="6"
							class="col-border-right pt-30"
						>
							<a-row
								v-if="responseData.stockHistoryStatsData"
								style="margin-right: 24px"
								class="stock-history-stats"
							>
								<a-col :span="24" class="sales mb-20">
									<a-statistic
										:title="$t('dashboard.total_sales_items')"
										:value="
											responseData.stockHistoryStatsData.totalSales
										"
									/>
								</a-col>
								<a-col :span="24" class="sales-returns mb-20">
									<a-statistic
										:title="$t('dashboard.total_sales_returns_items')"
										:value="
											responseData.stockHistoryStatsData
												.totalSalesReturn
										"
									/>
								</a-col>
								<a-col :span="24" class="purchases mb-20">
									<a-statistic
										:title="$t('dashboard.total_purchases_items')"
										:value="
											responseData.stockHistoryStatsData
												.totalPurchases
										"
									/>
								</a-col>

								<a-col :span="24" class="purchase-returns mb-20">
									<a-statistic
										:title="
											$t('dashboard.total_purchase_returns_items')
										"
										:value="
											responseData.stockHistoryStatsData
												.totalPurchaseReturn
										"
									/>
								</a-col>
							</a-row>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="18" :xl="18">
							<OrderTable
								:orderType="activeOrderType"
								:filters="filters"
								:perPageItems="5"
							/>
						</a-col>
					</a-row>
				</a-card>
			</a-col>
		</a-row>

		<a-row :gutter="[18, 18]" class="mt-30 mb-20">
			<a-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
				<a-card :title="$t('payments.payments')">
					<PaymentsChart :data="responseData" />
					<template
						v-if="
							permsArray.includes('order_payments_view') ||
							permsArray.includes('admin')
						"
						#extra
					>
						<a-button
							class="mt-10"
							type="link"
							@click="
								$router.push({ name: 'admin.reports.payments.index' })
							"
						>
							{{ $t("common.view_all") }}
							<DoubleRightOutlined />
						</a-button>
					</template>
				</a-card>
			</a-col>
		</a-row>

		<a-row :gutter="[18, 18]" class="mt-30 mb-20">
			<a-col :xs="24" :sm="24" :md="12" :lg="16" :xl="16">
				<a-card
					:title="$t('menu.stock_alert')"
					:bodyStyle="{ padding: '0px' }"
					v-if="responseData && responseData.stockAlerts"
				>
					<a-table
						:columns="stockQuantityColumns"
						:row-key="(record) => record.xid"
						:data-source="responseData.stockAlerts"
						:pagination="false"
					>
						<template #bodyCell="{ column, record }">
							<template v-if="column.dataIndex === 'current_stock'">
								{{ `${record.current_stock} ${record.short_name}` }}
							</template>
							<template v-if="column.dataIndex === 'stock_quantitiy_alert'">
								{{
									`${record.stock_quantitiy_alert} ${record.short_name}`
								}}
							</template>
						</template>
					</a-table>
					<template
						v-if="
							permsArray.includes('warehouses_view') ||
							permsArray.includes('admin')
						"
						#extra
					>
						<a-button
							class="mt-10"
							type="link"
							@click="$router.push({ name: 'admin.reports.stock.index' })"
						>
							{{ $t("common.view_all") }}
							<DoubleRightOutlined />
						</a-button>
					</template>
				</a-card>
			</a-col>
			<a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
				<a-card
					:title="$t('dashboard.top_customers')"
					:bodyStyle="{ padding: '0px' }"
					v-if="responseData && responseData.topCustomers"
				>
					<a-table
						:columns="topCustomerColumns"
						:row-key="(record) => record.customer_id"
						:data-source="responseData.topCustomers"
						:pagination="false"
					>
						<template #bodyCell="{ column, record }">
							<template v-if="column.dataIndex == 'customer_id'">
								<user-info :user="record.customer" />
							</template>
							<template v-if="column.dataIndex == 'total_amount'">
								{{ formatAmountCurrency(record.total_amount) }} <br />
								{{ $t("dashboard.total_sales") }} :
								{{ record.total_sales }}
							</template>
						</template>
					</a-table>
					<template
						v-if="
							permsArray.includes('users_view') ||
							permsArray.includes('admin')
						"
						#extra
					>
						<a-button
							class="mt-10"
							type="link"
							@click="$router.push({ name: 'admin.reports.users.index' })"
						>
							{{ $t("common.view_all") }}
							<DoubleRightOutlined />
						</a-button>
					</template>
				</a-card>
			</a-col>
		</a-row>
	</div>
</template>

<script>
import { ref, onMounted, reactive, toRef, watch } from "vue";
import {
	EyeOutlined,
	ArrowUpOutlined,
	ArrowDownOutlined,
	LineChartOutlined,
	BankOutlined,
	ShoppingOutlined,
	TagOutlined,
	DoubleRightOutlined,
} from "@ant-design/icons-vue";
import { notification } from "ant-design-vue";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";
import common from "../../common/composable/common";
import TopProducts from "../components/charts/dashboard/TopProducts.vue";
import PurchaseSales from "../components/charts/dashboard/PurchaseSales.vue";
import PaymentsChart from "../components/charts/dashboard/PaymentsChart.vue";
import StateWidget from "../../common/components/common/card/StateWidget.vue";
import Tiimeline from "../components/stock-history/Tiimeline.vue";
import OrderTable from "../components/order/OrderTable.vue";
import UserInfo from "../../common/components/user/UserInfo.vue";
import DateRangePicker from "../../common/components/common/calendar/DateRangePicker.vue";
import UpdateAppAlert from "./UpdateAppAlert.vue";
import AdminPageHeader from "../../common/layouts/AdminPageHeader.vue";

export default {
	components: {
		EyeOutlined,
		ArrowUpOutlined,
		ArrowDownOutlined,
		DoubleRightOutlined,
		StateWidget,
		TopProducts,
		PurchaseSales,
		PaymentsChart,
		OrderTable,
		UserInfo,
		Tiimeline,
		LineChartOutlined,
		BankOutlined,
		ShoppingOutlined,
		TagOutlined,
		DateRangePicker,
		UpdateAppAlert,
		AdminPageHeader,
	},
	setup() {
		const { t } = useI18n();
		const {
			formatAmountCurrency,
			appSetting,
			user,
			permsArray,
			selectedWarehouse,
		} = common();
		const activeOrderType = ref("");
		const filters = reactive({
			dates: [],
		});
		const responseData = ref([]);
		const route = useRoute();

		const stockQuantityColumns = [
			{
				title: t("product.product"),
				dataIndex: "product_name",
			},
			{
				title: t("product.quantity"),
				dataIndex: "current_stock",
			},
			{
				title: t("product.quantitiy_alert"),
				dataIndex: "stock_quantitiy_alert",
			},
		];

		const topCustomerColumns = [
			{
				title: t("stock.customer"),
				dataIndex: "customer_id",
			},
			{
				title: t("payments.total_amount"),
				dataIndex: "total_amount",
			},
		];

		onMounted(() => {
			const dashboardPromise = axiosAdmin.post("dashboard", filters);

			if (permsArray.value.includes("purchases_view")) {
				activeOrderType.value = "purchases";
			} else if (permsArray.value.includes("purchase_returns_view")) {
				activeOrderType.value = "purchase-returns";
			} else if (permsArray.value.includes("sales_returns_view")) {
				activeOrderType.value = "sales-returns";
			} else {
				activeOrderType.value = "sales";
			}

			// Message showing when comes from login page
			if (route.params && route.params.success) {
				notification.success({
					message: t("common.welcome_back", [user.value.name]),
					description: t("messages.login_success_dashboard"),
					placement: "topRight",
				});
			}

			Promise.all([dashboardPromise]).then(([dashboardResponse]) => {
				responseData.value = dashboardResponse.data;
			});
		});

		watch([filters, selectedWarehouse], (newVal, oldVal) => {
			axiosAdmin.post("dashboard", filters).then((response) => {
				responseData.value = response.data;
			});
		});

		return {
			filters,
			activeOrderType,
			responseData,

			stockQuantityColumns,
			topCustomerColumns,
			formatAmountCurrency,
			permsArray,
		};
	},
};
</script>

<style lang="less">
.ant-card-extra,
.ant-card-head-title {
	padding: 0px;
}

.ant-card-head-title {
	margin-top: 10px;
}

.stock-history-stats {
	margin-right: 20px;

	.sales {
		background: #e6f2ed;
		padding: 15px;
		border-radius: 10px;
	}

	.sales-returns {
		background: #ffefed;
		padding: 15px;
		border-radius: 10px;
	}

	.purchases {
		background: #eff3fe;
		padding: 15px;
		border-radius: 10px;
	}

	.purchase-returns {
		background: #f5f0df;
		padding: 15px;
		border-radius: 10px;
	}
}
</style>
