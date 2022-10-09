<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.expenses`)" class="p-0">
				<template
					v-if="
						permsArray.includes(`expenses_create`) ||
						permsArray.includes('admin')
					"
					#extra
				>
					<a-button type="primary" @click="addItem">
						<PlusOutlined />
						{{ $t("expense.add") }}
					</a-button>
				</template>
			</a-page-header>
		</template>
		<template #breadcrumb>
			<a-breadcrumb separator="-" style="font-size: 12px">
				<a-breadcrumb-item>
					<router-link :to="{ name: 'admin.dashboard.index' }">
						{{ $t(`menu.dashboard`) }}
					</router-link>
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`menu.expense_manager`) }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`menu.expenses`) }}
				</a-breadcrumb-item>
			</a-breadcrumb>
		</template>
	</AdminPageHeader>

	<a-card class="page-content-container">
		<AddEdit
			:addEditType="addEditType"
			:visible="addEditVisible"
			:url="addEditUrl"
			@addEditSuccess="addEditSuccess"
			@closed="onCloseAddEdit"
			:formData="formData"
			:data="viewData"
			:pageTitle="pageTitle"
			:successMessage="successMessage"
		/>

		<a-row style="mb-20" :gutter="[15, 15]">
			<a-col :xs="24" :sm="24" :md="8" :lg="4" :xl="4">
				<a-select
					v-model:value="filters.expense_category_id"
					show-search
					style="width: 100%"
					:placeholder="
						$t('common.select_default_text', [$t('expense.expense_category')])
					"
					@change="reFetchDatatable"
					:allowClear="true"
					optionFilterProp="label"
				>
					<a-select-option
						v-for="expenseCategory in preFetchData.expenseCategories"
						:key="expenseCategory.xid"
						:value="expenseCategory.xid"
						:label="expenseCategory.name"
					>
						{{ expenseCategory.name }}
					</a-select-option>
				</a-select>
			</a-col>
			<a-col :xs="24" :sm="24" :md="8" :lg="4" :xl="4">
				<a-select
					v-model:value="filters.user_id"
					show-search
					style="width: 100%"
					:placeholder="$t('common.select_default_text', [$t('expense.user')])"
					@change="reFetchDatatable"
					:allowClear="true"
					optionFilterProp="label"
				>
					<a-select-option
						v-for="staffMember in preFetchData.staffMembers"
						:key="staffMember.xid"
						:value="staffMember.xid"
						:label="staffMember.name"
					>
						{{ staffMember.name }}
					</a-select-option>
				</a-select>
			</a-col>
		</a-row>

		<a-row class="mt-20">
			<a-col :span="24">
				<div class="table-responsive">
					<a-table
						:columns="columns"
						:row-key="(record) => record.xid"
						:data-source="table.data"
						:pagination="table.pagination"
						:loading="table.loading"
						@change="handleTableChange"
					>
						<template #bodyCell="{ column, text, record }">
							<template v-if="column.dataIndex === 'expense_category_id'">
								{{ record.expense_category.name }}
							</template>
							<template v-if="column.dataIndex === 'user_id'">
								{{ record.user.name }}
							</template>
							<template v-if="column.dataIndex === 'amount'">
								{{ appSetting.currency.symbol }} {{ text }}
							</template>
							<template v-if="column.dataIndex === 'date'">
								{{ formatDate(record.date) }}
							</template>
							<template v-if="column.dataIndex === 'action'">
								<a-button
									v-if="
										permsArray.includes(`expenses_edit`) ||
										permsArray.includes('admin')
									"
									type="primary"
									@click="editItem(record)"
									style="margin-left: 4px"
								>
									<template #icon><EditOutlined /></template>
								</a-button>
								<a-button
									v-if="
										permsArray.includes(`expenses_delete`) ||
										permsArray.includes('admin')
									"
									type="primary"
									@click="showDeleteConfirm(record.xid)"
									style="margin-left: 4px"
								>
									<template #icon><DeleteOutlined /></template>
								</a-button>
							</template>
						</template>
					</a-table>
				</div>
			</a-col>
		</a-row>
	</a-card>
</template>

<script>
import { onMounted, watch } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import AddEdit from "./AddEdit.vue";
import fields from "./fields";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
	components: {
		PlusOutlined,
		EditOutlined,
		DeleteOutlined,
		AddEdit,
		AdminPageHeader,
	},
	setup() {
		const {
			url,
			addEditUrl,
			initData,
			columns,
			filters,
			preFetchData,
			getPreFetchData,
			hashableColumns,
		} = fields();
		const crudVariables = crud();
		const { appSetting, permsArray, formatDate, selectedWarehouse } = common();

		onMounted(() => {
			getPreFetchData();

			reFetchDatatable();
			crudVariables.crudUrl.value = addEditUrl;
			crudVariables.langKey.value = "expense";
			crudVariables.initData.value = { ...initData };
			crudVariables.formData.value = { ...initData };
			crudVariables.hashableColumns.value = [...hashableColumns];
		});

		const reFetchDatatable = () => {
			crudVariables.tableUrl.value = {
				url,
				filters,
			};

			crudVariables.fetch({
				page: 1,
			});
		};

		watch(selectedWarehouse, (newVal, oldVal) => {
			reFetchDatatable();
		});

		return {
			columns,
			appSetting,
			formatDate,
			...crudVariables,

			filters,
			preFetchData,
			reFetchDatatable,
			permsArray,
		};
	},
};
</script>
