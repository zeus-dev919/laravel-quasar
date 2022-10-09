<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.payment_${paymentType}`)" class="p-0">
				<template
					v-if="
						permsArray.includes('expense_categories_create') ||
						permsArray.includes('admin')
					"
					#extra
				>
					<a-button type="primary" @click="addItem">
						<PlusOutlined />
						{{ $t("payments.add") }}
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
					{{ $t(`menu.${menuParent}`) }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`menu.payment_${paymentType}`) }}
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

		<a-row style="margin-bottom: 20px">
			<a-input-group>
				<a-row :gutter="8">
					<a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
						<a-input-group compact>
							<a-select
								style="width: 25%"
								v-model:value="table.searchColumn"
								:placeholder="$t('common.select_default_text', [''])"
							>
								<a-select-option
									v-for="filterableColumn in filterableColumns"
									:key="filterableColumn.key"
								>
									{{ filterableColumn.value }}
								</a-select-option>
							</a-select>
							<a-input-search
								style="width: 75%"
								v-model:value="table.searchString"
								show-search
								@change="onTableSearch"
								@search="onTableSearch"
								:loading="table.filterLoading"
							/>
						</a-input-group>
					</a-col>
					<a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="5">
						<a-select
							v-model:value="filters.user_id"
							:placeholder="
								$t('common.select_default_text', [$t(`user.user`)])
							"
							:allowClear="true"
							style="width: 100%"
							optionFilterProp="title"
							show-search
							@change="setUrlData"
						>
							<a-select-option
								v-for="user in users"
								:key="user.xid"
								:title="user.name"
								:value="user.xid"
							>
								{{ user.name }}
							</a-select-option>
						</a-select>
					</a-col>
				</a-row>
			</a-input-group>
		</a-row>

		<a-row>
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
						<template #bodyCell="{ column, record }">
							<template v-if="column.dataIndex === 'user_id'">
								<UserInfo :user="record.user" />
							</template>
							<template v-if="column.dataIndex === 'amount'">
								{{ formatAmountCurrency(record.amount) }}
							</template>
							<template v-if="column.dataIndex === 'action'">
								<a-button
									v-if="
										permsArray.includes('expense_categories_edit') ||
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
										permsArray.includes(
											'expense_categories_delete'
										) || permsArray.includes('admin')
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
import { onMounted, reactive, ref, watch } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import fields from "./fields";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import AddEdit from "./AddEdit.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import UserInfo from "../../../../common/components/user/UserInfo.vue";

export default {
	components: {
		PlusOutlined,
		EditOutlined,
		DeleteOutlined,
		AddEdit,
		AdminPageHeader,
		UserInfo,
	},
	setup() {
		const {
			addEditUrl,
			initData,
			columns,
			paymentType,
			menuParent,
			hashableColumns,
			filterableColumns,
		} = fields();
		const crudVariables = crud();
		const { permsArray, formatAmountCurrency, selectedWarehouse } = common();
		const filters = reactive({
			user_id: undefined,
		});
		const users = ref({});

		onMounted(() => {
			getInitialData();

			setUrlData();
		});

		const setUrlData = () => {
			crudVariables.tableUrl.value = {
				url:
					"payments?fields=id,xid,date,amount,notes,payment_mode_id,payment_number,payment_type,payment_mode_id,x_payment_mode_id,paymentMode{id,xid,name},user_id,x_user_id,user{id,xid,name,profile_image,profile_image_url,user_type}",
				filterString: `payment_type eq "${paymentType}"`,
				filters,
			};
			crudVariables.table.filterableColumns = filterableColumns;

			crudVariables.fetch({
				page: 1,
			});

			crudVariables.crudUrl.value = addEditUrl;
			crudVariables.langKey.value = "payments";
			crudVariables.initData.value = { ...initData };
			crudVariables.formData.value = { ...initData };
			crudVariables.hashableColumns.value = [...hashableColumns];
		};

		const getInitialData = () => {
			const usersPromise = axiosAdmin.post("customer-suppliers");

			Promise.all([usersPromise]).then(([usersResponse]) => {
				users.value = usersResponse.data;
			});
		};

		watch(selectedWarehouse, (newVal, oldVal) => {
			setUrlData();
		});

		return {
			columns,
			...crudVariables,
			permsArray,
			formatAmountCurrency,
			paymentType,
			menuParent,
			filters,
			users,
			setUrlData,
			filterableColumns,
		};
	},
};
</script>
