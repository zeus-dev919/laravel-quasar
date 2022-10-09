<template>
	<a-row>
		<a-col :span="24">
			<div v-if="!table.loading" class="table-responsive">
				<a-table
					:columns="productOrderColumns"
					:row-key="(record) => record.xid"
					:data-source="table.data"
					:pagination="table.pagination"
					:loading="table.loading"
					@change="handleTableChange"
				>
					<template #bodyCell="{ column, record }">
						<template v-if="column.dataIndex === 'order_type'">
							{{ getOrderTypeFromstring(record.order.order_type) }}
						</template>
						<template v-if="column.dataIndex === 'order_date'">
							{{ formatDateTime(record.order.order_date) }}
						</template>
						<template v-if="column.dataIndex === 'quantity'">
							{{ `${record.quantity} ${record.unit.short_name}` }}
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
			</div>
		</a-col>
	</a-row>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import datatable from "../../../../../common/composable/datatable";
import common from "../../../../../common/composable/common";
import fields from "./fields";

export default defineComponent({
	props: ["product"],
	setup(props) {
		const { productOrderColumns, orderItemsHashableColumns } = fields();
		const {
			formatDate,
			formatDateTime,
			formatAmountCurrency,
			getOrderTypeFromstring,
		} = common();
		const datatableVariables = datatable();

		onMounted(() => {
			datatableVariables.tableUrl.value = {
				url:
					"order-items?fields=id,xid,quantity,single_unit_price,unit_price,total_discount,discount_rate,total_tax,tax_rate,subtotal,order_id,x_order_id,order{id,xid,order_type,order_date},unit_id,x_unit_id,unit{id,xid,short_name}",
				filters: {
					product_id: props.product.xid,
				},
			};
			datatableVariables.hashable.value = [...orderItemsHashableColumns];
			datatableVariables.table.sorter = {
				field: "orders.order_date",
				order: "asc",
			};

			datatableVariables.fetch({
				page: 1,
			});
		});

		return {
			productOrderColumns,
			orderItemsHashableColumns,
			...datatableVariables,

			formatDate,
			formatDateTime,
			formatAmountCurrency,
			getOrderTypeFromstring,
		};
	},
});
</script>
