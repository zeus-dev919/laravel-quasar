<template>
	<a-row>
		<a-col :span="24">
			<div v-if="!table.loading" class="table-responsive">
				<a-table
					:columns="transactionsColumns"
					:row-key="(record) => record.xid"
					:data-source="table.data"
					:pagination="table.pagination"
					:loading="table.loading"
					@change="handleTableChange"
				>
					<template #bodyCell="{ column, record }">
						<template v-if="column.dataIndex === 'date'">
							{{ formatDate(record.date) }}
						</template>
						<template v-if="column.dataIndex === 'payment_type'">
							{{ record.type }}
						</template>
						<template v-if="column.dataIndex === 'user_id'">
							<UserInfo :user="record.user" />
						</template>
						<template v-if="column.dataIndex === 'amount'">
							{{ formatAmountCurrency(record.amount) }}
						</template>
					</template>
				</a-table>
			</div>
		</a-col>
	</a-row>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from "vue";
import datatable from "../../../../common/composable/datatable";
import common from "../../../../common/composable/common";
import UserInfo from "../../../../common/components/user/UserInfo.vue";
import fields from "./fields";

export default defineComponent({
	props: {
		user_id: null,
		result_type: {
			default: "all",
			type: String,
		},
	},
	components: {
		UserInfo,
	},
	setup(props) {
		const { transactionsColumns, transactionsHashableColumns } = fields();
		const { formatDate, formatAmountCurrency } = common();
		const datatableVariables = datatable();

		onMounted(() => {
			const propsData = props;
			getData(propsData);
		});

		const getData = (propsData) => {
			const filters = {};

			if (propsData.user_id && propsData.user_id != undefined) {
				filters.user_id = propsData.user_id;
			}

			datatableVariables.tableUrl.value = {
				url:
					"warehouse-history?fields=id,xid,date,type,amount,transaction_number,user_id,x_user_id,user{id,xid,name,profile_image,profile_image_url,user_type}",
				filters,
				extraFilters: {
					result_type: propsData.result_type,
				},
			};
			datatableVariables.hashable.value = [...transactionsHashableColumns];
			datatableVariables.table.sorter = { field: "date", order: "asc" };

			datatableVariables.fetch({
				page: 1,
			});
		};

		watch(props, (newVal, oldVal) => {
			getData(newVal);
		});

		return {
			transactionsColumns,
			...datatableVariables,

			formatDate,
			formatAmountCurrency,
		};
	},
});
</script>
