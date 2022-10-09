<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.payment_modes`)" class="p-0">
				<template
					v-if="
						permsArray.includes('payment_modes_create') ||
						permsArray.includes('admin')
					"
					#extra
				>
					<a-button type="primary" @click="addItem">
						<PlusOutlined />
						{{ $t("payment_mode.add") }}
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
					{{ $t(`menu.payment_modes`) }}
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

				<a-row :gutter="[15, 15]" class="mb-20">
					<a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
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
								<template #bodyCell="{ column, record }">
									<template v-if="column.dataIndex === 'action'">
										<span v-if="record.name.toLowerCase() != 'cash'">
											<a-button
												v-if="
													permsArray.includes(
														'payment_modes_edit'
													) || permsArray.includes('admin')
												"
												type="primary"
												@click="editItem(record)"
												style="margin-left: 4px"
											>
												<template #icon
													><EditOutlined
												/></template>
											</a-button>
											<a-button
												v-if="
													permsArray.includes(
														'payment_modes_delete'
													) || permsArray.includes('admin')
												"
												type="primary"
												@click="showDeleteConfirm(record.xid)"
												style="margin-left: 4px"
											>
												<template #icon
													><DeleteOutlined
												/></template>
											</a-button>
										</span>
										<span v-else> - </span>
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
		const { url, addEditUrl, initData, columns, filterableColumns } = fields();
		const crudVariables = crud();

		onMounted(() => {
			crudVariables.tableUrl.value = {
				url,
			};
			crudVariables.table.filterableColumns = filterableColumns;

			crudVariables.fetch({
				page: 1,
			});

			crudVariables.crudUrl.value = addEditUrl;
			crudVariables.langKey.value = "payment_mode";
			crudVariables.initData.value = { ...initData };
			crudVariables.formData.value = { ...initData };
		});

		return {
			permsArray,
			columns,
			...crudVariables,
			filterableColumns,
		};
	},
};
</script>
