<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.brands`)" class="p-0">
				<template
					v-if="
						permsArray.includes('brands_create') ||
						permsArray.includes('admin')
					"
					#extra
				>
					<a-button type="primary" @click="addItem">
						<PlusOutlined />
						{{ $t("brand.add") }}
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
					{{ $t(`menu.product_manager`) }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`menu.brands`) }}
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
				<a-row :gutter="[15, 15]">
					<a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="6">
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
						bordered
					>
						<template #bodyCell="{ column, text, record }">
							<template v-if="column.dataIndex === 'image_url'">
								<a-image :width="32" :src="text" />
							</template>
							<template v-if="column.dataIndex === 'action'">
								<a-button
									v-if="
										permsArray.includes('brands_edit') ||
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
										permsArray.includes('brands_delete') ||
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
import { onMounted } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import fields from "./fields";
import crud from "../../../../common/composable/crud";
import common from "../../../../common/composable/common";
import AddEdit from "./AddEdit.vue";
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
		const { addEditUrl, initData, columns, filterableColumns } = fields();
		const crudVariables = crud();
		const { permsArray } = common();

		onMounted(() => {
			crudVariables.tableUrl.value = {
				url: "brands?fields=id,xid,name,slug,image,image_url",
			};
			crudVariables.table.filterableColumns = filterableColumns;

			crudVariables.fetch({
				page: 1,
			});

			crudVariables.crudUrl.value = addEditUrl;
			crudVariables.langKey.value = "brand";
			crudVariables.initData.value = { ...initData };
			crudVariables.formData.value = { ...initData };
		});

		return {
			columns,
			filterableColumns,
			permsArray,
			...crudVariables,
		};
	},
};
</script>
