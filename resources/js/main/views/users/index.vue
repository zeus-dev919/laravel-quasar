<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.${langKey}s`)" class="p-0">
				<template
					v-if="
						permsArray.includes(`${userType}_create`) ||
						permsArray.includes('admin')
					"
					#extra
				>
					<a-button type="primary" @click="addItem">
						<PlusOutlined />
						{{ $t(`${langKey}.add`) }}
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
					{{ $t(`menu.parties`) }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`menu.${langKey}s`) }}
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
			:addEditData="formData"
			:data="viewData"
			:pageTitle="pageTitle"
			:successMessage="successMessage"
		/>

		<a-row v-if="userType && userType == 'users'">
			<a-col :span="24">
				<a-tabs v-model:activeKey="searchStatus" @change="setUrlData">
					<a-tab-pane
						key="all"
						:tab="`${$t('common.all')} ${$t('menu.users')}`"
					/>
					<a-tab-pane
						v-for="usrStatus in userStatus"
						:key="usrStatus.key"
						:tab="`${usrStatus.value} ${$t('menu.users')}`"
					/>
				</a-tabs>
			</a-col>
		</a-row>

		<a-row v-if="userType && userType != 'users'">
			<a-col :span="24">
				<a-tabs v-model:activeKey="searchDueType" @change="setUrlData">
					<a-tab-pane key="all" :tab="`${$t('common.all')}`" />
					<a-tab-pane key="receive" :tab="`${$t('user.to_receive')}`" />
					<a-tab-pane key="pay" :tab="`${$t('user.to_pay')}`" />
				</a-tabs>
			</a-col>
		</a-row>

		<a-row :gutter="[15, 15]" style="mb-20">
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
			<a-col
				v-if="userType && userType != 'users'"
				:xs="24"
				:sm="24"
				:md="12"
				:lg="4"
				:xl="4"
			>
				<a-select
					v-model:value="searchStatus"
					@change="setUrlData"
					show-search
					style="width: 100%"
					:placeholder="$t('common.select_default_text', [$t('user.status')])"
					:allowClear="true"
					optionFilterProp="label"
				>
					<a-select-option
						v-for="usrStatus in userStatus"
						:key="usrStatus.key"
						:value="usrStatus.key"
						:label="usrStatus.value"
					>
						{{ usrStatus.value }}
					</a-select-option>
				</a-select>
			</a-col>
		</a-row>

		<a-row :gutter="[15, 15]" class="mt-20">
			<a-col :span="24">
				<div class="table-responsive">
					<a-table
						:columns="tableColumns"
						:row-key="(record) => record.xid"
						:data-source="table.data"
						:pagination="table.pagination"
						:loading="table.loading"
						@change="handleTableChange"
					>
						<template #bodyCell="{ column, text, record }">
							<template v-if="column.dataIndex === 'name'">
								<user-info :user="record" />
							</template>
							<template v-if="column.dataIndex === 'status'">
								<a-tag :color="statusColors[text]">
									{{ $t(`common.${text}`) }}
								</a-tag>
							</template>
							<template v-if="column.dataIndex === 'created_at'">
								{{ formatDateTime(record.created_at) }}
							</template>
							<template v-if="column.dataIndex === 'balance'">
								<UserBalance :amount="record.details.due_amount" />
							</template>
							<template v-if="column.dataIndex === 'action'">
								<a-button
									type="primary"
									@click="viewItem(record)"
									style="margin-left: 4px"
								>
									<template #icon><EyeOutlined /></template>
								</a-button>
								<a-button
									v-if="
										permsArray.includes(`${userType}_edit`) ||
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
										(permsArray.includes(`${userType}_delete`) ||
											permsArray.includes('admin')) &&
										user.xid != record.xid
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

		<UserView :user="viewData" :visible="detailsVisible" @closed="onCloseDetails" />
	</a-card>
</template>

<script>
import { onMounted, ref, watch } from "vue";
import {
	EyeOutlined,
	PlusOutlined,
	EditOutlined,
	DeleteOutlined,
	UserOutlined,
	ArrowUpOutlined,
	ArrowDownOutlined,
} from "@ant-design/icons-vue";
import { useRoute } from "vue-router";
import crud from "../../../common/composable/crud";
import common from "../../../common/composable/common";
import fields from "./fields";
import UserInfo from "../../../common/components/user/UserInfo.vue";
import StateWidget from "../../../common/components/common/card/StateWidget.vue";
import AddEdit from "./AddEdit.vue";
import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
import UserBalance from "./UserBalance.vue";
import UserView from "./View.vue";

export default {
	components: {
		EyeOutlined,
		PlusOutlined,
		EditOutlined,
		DeleteOutlined,
		UserOutlined,
		ArrowUpOutlined,
		ArrowDownOutlined,
		AddEdit,
		UserInfo,

		StateWidget,
		AdminPageHeader,
		UserBalance,
		UserView,
	},
	setup() {
		const {
			statusColors,
			userStatus,
			permsArray,
			formatDateTime,
			user,
			selectedWarehouse,
		} = common();
		const {
			supplierInitData,
			customerInitData,
			staffMemberInitData,
			columns,
			supplierCustomerColumns,
			filterableColumns,
		} = fields();
		const crudVariables = crud();
		const route = useRoute();
		const userType = ref(route.meta.menuKey);
		const urlParams =
			"?fields=id,xid,user_type,name,email,profile_image,profile_image_url,phone,address,shipping_address,status,created_at,details{opening_balance,opening_balance_type,credit_period,credit_limit,due_amount,warehouse_id,x_warehouse_id},details:warehouse{id,xid,name},role_id,role{id,xid,name,display_name},warehouse_id,warehouse{xid,name}";

		const searchStatus = ref(undefined);
		const activeTabKey = ref("all");
		const searchDueType = ref("all");
		const tableColumns = ref([]);

		onMounted(() => {
			setUrlData();
			crudVariables.langKey.value = "staff_member";
			crudVariables.table.filterableColumns = filterableColumns;

			setFormData();
		});

		const setFormData = () => {
			if (userType.value == "suppliers") {
				crudVariables.initData.value = { ...supplierInitData };
				crudVariables.formData.value = { ...supplierInitData };
				crudVariables.langKey.value = "supplier";
				tableColumns.value = supplierCustomerColumns;
			} else if (userType.value == "customers") {
				crudVariables.initData.value = { ...customerInitData };
				crudVariables.formData.value = { ...customerInitData };
				crudVariables.langKey.value = "customer";
				tableColumns.value = supplierCustomerColumns;
			} else {
				crudVariables.initData.value = { ...staffMemberInitData };
				crudVariables.formData.value = { ...staffMemberInitData };
				crudVariables.langKey.value = "staff_member";
				tableColumns.value = columns;
			}
			crudVariables.restFormData();
		};

		const setUrlData = () => {
			crudVariables.crudUrl.value = userType.value;
			var filterString = "";
			var extraFilters = {};
			if (searchStatus.value != undefined && searchStatus.value != "all") {
				filterString += `status eq "${searchStatus.value}"`;
			}
			if (
				searchDueType.value != undefined &&
				searchDueType.value != "all" &&
				userType.value != "users"
			) {
				extraFilters.search_due_type = searchDueType.value;
			}
			crudVariables.tableUrl.value = {
				url: `${userType.value}${urlParams}`,
				filterString,
				extraFilters,
			};
			crudVariables.fetch({
				page: 1,
			});
		};

		watch(route, (newVal, oldVal) => {
			if (newVal.meta.menuParent == "users") {
				userType.value = newVal.meta.menuKey;

				searchStatus.value = undefined;
				crudVariables.table.searchColumn = undefined;
				crudVariables.table.searchString = "";
				searchStatus.value = "all";
				searchDueType.value = "all";

				setUrlData();
				setFormData();
			}
		});

		watch(selectedWarehouse, (newVal, oldVal) => {
			setUrlData();
		});

		return {
			statusColors,
			userStatus,
			filterableColumns,
			userType,
			tableColumns,

			searchStatus,
			setUrlData,
			searchDueType,

			activeTabKey,

			...crudVariables,
			permsArray,

			formatDateTime,
			user,
		};
	},
};
</script>
