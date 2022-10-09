<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.stock_alert`)" class="p-0" />
		</template>
		<template #breadcrumb>
			<a-breadcrumb separator="-" style="font-size: 12px">
				<a-breadcrumb-item>
					<router-link :to="{ name: 'admin.dashboard.index' }">
						{{ $t(`menu.dashboard`) }}
					</router-link>
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`menu.reports`) }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`menu.stock_alert`) }}
				</a-breadcrumb-item>
			</a-breadcrumb>
		</template>
	</AdminPageHeader>

	<a-card class="page-content-container">
		<a-row :gutter="[15, 15]" class="mb-20">
			<a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="6">
				<ProductSearchInput
					@valueChanged="
						(productId) => {
							searchProductId = productId;
							getTableData();
						}
					"
				/>
			</a-col>
		</a-row>

		<a-row>
			<a-col :span="24">
				<div class="table-responsive">
					<a-table
						:columns="stockAlertColumns"
						:row-key="(record) => record.xid"
						:data-source="table.data"
						:pagination="table.pagination"
						:loading="table.loading"
						@change="handleTableChange"
					>
						<template #bodyCell="{ column, record }">
							<template v-if="column.dataIndex === 'current_stock'">
								{{
									`${record.details.current_stock} ${record.unit.short_name}`
								}}
							</template>
							<template v-if="column.dataIndex === 'stock_quantitiy_alert'">
								{{
									`${record.details.stock_quantitiy_alert} ${record.unit.short_name}`
								}}
							</template>
						</template>
					</a-table>
				</div>
			</a-col>
		</a-row>
	</a-card>
</template>
<script>
import { onMounted, ref, onBeforeMount, watch } from "vue";
import { useRouter } from "vue-router";
import ProductSearchInput from "../../../../common/components/product/ProductSearchInput.vue";
import common from "../../../../common/composable/common";
import datatable from "../../../../common/composable/datatable";
import fields from "./fields";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
	components: {
		ProductSearchInput,
		AdminPageHeader,
	},
	setup() {
		const { permsArray, selectedWarehouse } = common();
		const { url, stockAlertColumns, stockAlertHashableColumns } = fields();
		const searchProductId = ref(undefined);
		const router = useRouter();
		const datatableVariables = datatable();

		onBeforeMount(() => {
			if (
				!(
					permsArray.value.includes("products_view") ||
					permsArray.value.includes("admin")
				)
			) {
				router.push("admin.dashboard.index");
			}
		});

		onMounted(() => {
			getTableData();
		});

		const getTableData = () => {
			datatableVariables.tableUrl.value = {
				url,
				filters: {
					"products.id": searchProductId.value,
				},
				extraFilters: {
					fetch_stock_alert: true,
				},
			};
			datatableVariables.hashable.value = [...stockAlertHashableColumns];

			datatableVariables.fetch({
				page: 1,
			});
		};

		watch(selectedWarehouse, (newVal, oldVal) => {
			getTableData();
		});

		return {
			stockAlertColumns,
			...datatableVariables,

			searchProductId,
			getTableData,
			permsArray,
		};
	},
};
</script>
