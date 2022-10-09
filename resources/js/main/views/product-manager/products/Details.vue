<template>
	<a-drawer
		:title="itemDetails.name"
		:width="drawerWidth"
		:visible="visible"
		:body-style="{ paddingBottom: '80px' }"
		:maskClosable="false"
		@close="onClose"
	>
		<a-row>
			<a-col :xs="24" :sm="24" :md="8" :lg="6">
				<a-image :width="150" :src="itemDetails.image_url" />
			</a-col>
			<a-col :xs="24" :sm="24" :md="8" :lg="18">
				<a-row v-if="itemDetails.description" class="mb-10 mt-10">
					<a-col :xs="24" :sm="24" :md="24" :lg="24">
						{{ itemDetails.description }}
					</a-col>
				</a-row>
				<a-row class="mb-10 mt-10">
					<a-col :xs="24" :sm="24" :md="8" :lg="8">
						<a-statistic
							:title="$t('product.mrp')"
							:value="formatAmountCurrency(itemDetails.mrp)"
							style="margin-right: 50px"
						/>
					</a-col>
					<a-col :xs="24" :sm="24" :md="8" :lg="8">
						<a-statistic
							:title="$t('product.purchase_price')"
							:value="formatAmountCurrency(itemDetails.purchase_price)"
							style="margin-right: 50px"
						/>
					</a-col>
					<a-col :xs="24" :sm="24" :md="8" :lg="8">
						<a-statistic
							:title="$t('product.sales_price')"
							:value="formatAmountCurrency(itemDetails.sales_price)"
						/>
					</a-col>
				</a-row>
				<hr
					style="
						height: 1px;
						border: none;
						color: #f0f0f0;
						background-color: #f0f0f0;
					"
					class="mt-10 mb-10"
				/>
				<a-row>
					<a-col :xs="24" :sm="24" :md="12" :lg="12">
						<a-typography-title :level="5">
							{{ $t("product.stocks") }}
						</a-typography-title>
						<Doughnut :data="warehouseStockChartData" style="height: 200px" />
					</a-col>
					<a-col :xs="24" :sm="24" :md="12" :lg="12">
						<a-table
							:columns="warehouseStockColumns"
							:row-key="(record) => record.id"
							:data-source="itemDetails.warehouse_stocks"
							:pagination="false"
							class="mt-30"
						>
							<template #bodyCell="{ column, record }">
								<template v-if="column.dataIndex === 'warehouse_id'">
									{{ record.warehouse.name }}
								</template>
								<template v-if="column.dataIndex === 'stock_quantity'">
									{{ record.stock_quantity }}
									{{ itemDetails.unit.short_name }}
								</template>
							</template>
						</a-table>
					</a-col>
				</a-row>
			</a-col>
		</a-row>
		<a-row class="mt-10">
			<a-col :xs="24" :sm="24" :md="24" :lg="24">
				<a-tabs v-model:activeKey="activeKey">
					<a-tab-pane key="product_orders" :tab="$t('product.product_orders')">
						<a-table
							:columns="productOrderColumns"
							:row-key="(record) => record.id"
							:data-source="itemDetails.items"
							:pagination="false"
						>
							<template #bodyCell="{ column, record }">
								<template v-if="column.dataIndex === 'order_type'">
									{{ getOrderTypeFromstring(record.order.order_type) }}
								</template>
								<template v-if="column.dataIndex === 'order_date'">
									{{ formatDateTime(record.order.order_date) }}
								</template>
								<template v-if="column.dataIndex === 'single_unit_price'">
									{{ formatAmountCurrency(record.single_unit_price) }}
								</template>
								<template v-if="column.dataIndex === 'total_discount'">
									{{ formatAmountCurrency(record.total_discount) }} ({{
										record.discount_rate
									}}%)
								</template>
								<template v-if="column.dataIndex === 'total_tax'">
									{{ formatAmountCurrency(record.total_tax) }} ({{
										record.tax_rate
									}}%)
								</template>
								<template v-if="column.dataIndex === 'subtotal'">
									{{ formatAmountCurrency(record.subtotal) }}
								</template>
							</template>
						</a-table>
					</a-tab-pane>
					<a-tab-pane key="stock_history" :tab="$t('product.stock_history')">
						<a-table
							:columns="stockHistoryColumns"
							:row-key="(record) => record.id"
							:data-source="itemDetails.stock_history"
							:pagination="false"
						>
							<template #bodyCell="{ column, record }">
								<template v-if="column.dataIndex === 'created_at'">
									{{ formatDateTime(record.created_at) }}
								</template>
								<template v-if="column.dataIndex === 'order_type'">
									{{ getOrderTypeFromstring(record.order_type) }}
								</template>
							</template>
						</a-table>
					</a-tab-pane>
				</a-tabs>
			</a-col>
		</a-row>
	</a-drawer>
</template>
<script>
import { defineComponent, ref, watch, computed } from "vue";
import { useI18n } from "vue-i18n";
import { forEach } from "lodash-es";
import common from "../../../../common/composable/common";
import Doughnut from "../../../components/charts/Doughnut.vue";

export default defineComponent({
	props: ["itemDetails", "visible"],
	components: { Doughnut },
	setup(props, { emit }) {
		const {
			formatAmountCurrency,
			getOrderTypeFromstring,
			formatDate,
			formatDateTime,
		} = common();
		const { t } = useI18n();
		const activeKey = ref("product_orders");

		const warehouseStockChartData = ref({});

		const outOfStockWarehouses = computed(() => {
			const reslults = props.itemDetails.warehouse_stocks.filter(
				(warehouseStock) =>
					warehouseStock.stock_quantity <
					props.itemDetails.stock_quantitiy_alert
			);

			return reslults;
		});

		watch(props, (newVal, oldVal) => {
			activeKey.value = "product_orders";

			if (newVal.itemDetails.warehouse_stocks) {
				var labels = [];
				var values = [];
				var colors = ["rgba(255, 99, 132, 0.2)", "rgba(54, 162, 235, 0.2)"];

				forEach(newVal.itemDetails.warehouse_stocks, (value, key) => {
					labels.push(value.warehouse.name);
					values.push(value.stock_quantity);
				});

				warehouseStockChartData.value = {
					labels,
					values,
					colors,
				};
			} else {
				warehouseStockChartData.value = {
					labels: [],
					values: [],
					colors: [],
				};
			}
		});

		const onClose = () => {
			emit("closed");
		};

		return {
			onClose,
			formatDate,
			formatDateTime,

			formatAmountCurrency,
			warehouseStockColumns,
			stockHistoryColumns,
			productOrderColumns,
			warehouseStockChartData,
			activeKey,
			getOrderTypeFromstring,
			drawerWidth: window.innerWidth <= 991 ? "90%" : "60%",
			outOfStockWarehouses,
		};
	},
});
</script>
