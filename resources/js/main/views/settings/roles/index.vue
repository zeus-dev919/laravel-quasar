<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.roles`)" class="p-0">
				<template
					v-if="
						permsArray.includes('roles_create') ||
						permsArray.includes('admin')
					"
					#extra
				>
					<a-button type="primary" @click="addItem">
						<PlusOutlined />
						{{ $t("role.add") }}
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
					{{ $t(`menu.settings`) }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`menu.roles`) }}
				</a-breadcrumb-item>
			</a-breadcrumb>
		</template>
	</AdminPageHeader>

	<a-row>
		<a-col :xs="24" :sm="24" :md="24" :lg="4" :xl="4" class="bg-setting-sidebar">
			<SettingSidebar />
		</a-col>
		<a-col :xs="24" :sm="24" :md="24" :lg="20" :xl="20">
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
									<template v-if="column.dataIndex === 'action'">
										<a-button
											v-if="
												(permsArray.includes('roles_edit') ||
													permsArray.includes('admin')) &&
												record.name != 'admin'
											"
											type="primary"
											@click="editItem(record)"
											style="margin-left: 4px"
										>
											<template #icon><EditOutlined /></template>
										</a-button>
										<a-button
											v-if="
												(permsArray.includes('roles_delete') ||
													permsArray.includes('admin')) &&
												record.name != 'admin'
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
		</a-col>
	</a-row>
</template>
<script>
import { onMounted } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import fields from "./fields";
import AddEdit from "./AddEdit.vue";
import SettingSidebar from "../SettingSidebar.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
	components: {
		PlusOutlined,
		EditOutlined,
		DeleteOutlined,
		AddEdit,
		SettingSidebar,
		AdminPageHeader,
	},
	setup() {
		const { permsArray } = common();
		const {
			url,
			addEditUrl,
			initData,
			columns,
			filterableColumns,
			preFetchData,
			getPreFetchData,
		} = fields();
		const crudVariables = crud();

		onMounted(() => {
			getPreFetchData();

			crudVariables.tableUrl.value = {
				url,
				filters: "",
			};
			crudVariables.table.filterableColumns = filterableColumns;

			crudVariables.fetch({
				page: 1,
			});

			crudVariables.crudUrl.value = addEditUrl;
			crudVariables.langKey.value = "role";
			crudVariables.initData.value = { ...initData };
			crudVariables.formData.value = { ...initData };
		});

		return {
			permsArray,
			columns,
			...crudVariables,
			filterableColumns,

			preFetchData,
		};
	},
};
</script>

<style lang="less">
.flex-column {
	flex-direction: column !important;
}

.d-flex {
	display: flex !important;
}

.tbl-responsive {
	overflow-x: auto;
}

.table {
	width: 100%;
}
.fs-6 {
	font-size: 1.075rem !important;
}

.align-middle {
	vertical-align: middle !important;
}
.table > tbody {
	vertical-align: inherit;
}
.text-gray-600 {
	color: #7e8299 !important;
}
.fw-bold {
	font-weight: 500 !important;
}
tbody,
td,
tfoot,
th,
thead,
tr {
	border-color: inherit;
	border-style: solid;
	border-width: 0;
}

.table.table-row-dashed tr {
	border-bottom-width: 1px;
	border-bottom-style: dashed;
	border-bottom-color: #eff2f5;
}

.table td:first-child,
.table th:first-child,
.table tr:first-child {
	padding-left: 0;
}

.table td,
.table th,
.table tr {
	border-color: inherit;
	border-width: inherit;
	border-style: inherit;
	text-transform: inherit;
	font-weight: inherit;
	font-size: inherit;
	color: inherit;
	height: inherit;
	min-height: inherit;
}

.form-check.form-check-custom {
	display: flex;
	align-items: center;
	padding-left: 0;
	margin: 0;
}

.me-9 {
	margin-right: 2.25rem !important;
}

.form-check {
	display: block;
	min-height: 1.5rem;
	padding-left: 2.25rem;
	margin-bottom: 0.125rem;
}

.form-check.form-check-custom.form-check-sm .form-check-input {
	height: 1.5rem;
	width: 1.5rem;
}

.form-check:not(.form-switch) .form-check-input[type="checkbox"] {
	background-size: 60% 60%;
}

.form-check.form-check-solid .form-check-input {
	border: 0;
	background-color: #eff2f5;
}

.form-check.form-check-custom .form-check-input {
	margin: 0;
	float: none;
	flex-shrink: 0;
}

.form-check .form-check-input {
	cursor: pointer;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
}

.form-check-input[type="checkbox"] {
	border-radius: 0.45em;
}

.form-check-input {
	width: 1.75rem;
	height: 1.75rem;
	margin-top: -0.125rem;
	vertical-align: top;
	background-color: #fff;
	background-repeat: no-repeat;
	background-position: center;
	background-size: contain;
	border: 1px solid rgba(0, 0, 0, 0.25);
	appearance: none;
	color-adjust: exact;
	transition: background-color 0.15s ease-in-out, background-position 0.15s ease-in-out,
		border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

button,
input,
optgroup,
select,
textarea {
	margin: 0;
	font-family: inherit;
	font-size: inherit;
	line-height: inherit;
}

.form-check.form-check-custom .form-check-label {
	margin-left: 0.55rem;
}

.form-check .form-check-label {
	cursor: pointer;
}
</style>
