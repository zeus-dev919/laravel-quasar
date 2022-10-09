<template>
	<a-row>
		<a-col :span="24">
			<div v-if="!table.loading" class="table-responsive">
				<a-table
					:columns="stockHistoryColumns"
					:row-key="(record) => record.xid"
					:data-source="table.data"
					:pagination="table.pagination"
					:loading="table.loading"
					@change="handleTableChange"
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
		const { stockHistoryColumns, stockHistoryHashableColumns } = fields();
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
					"stock-history?fields=id,xid,created_at,order_type,quantity,stock_type,action_type",
				filters: {
					product_id: props.product.xid,
				},
			};
			datatableVariables.hashable.value = [...stockHistoryHashableColumns];
			// datatableVariables.table.sorter = { field: "date", order: "asc" };

			datatableVariables.fetch({
				page: 1,
			});
		});

		return {
			stockHistoryColumns,
			...datatableVariables,

			formatDate,
			formatDateTime,
			formatAmountCurrency,
			getOrderTypeFromstring,
		};
	},
});
</script>
