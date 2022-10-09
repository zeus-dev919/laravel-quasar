<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.categories`)" class="p-0">
				<template
					v-if="
						permsArray.includes('categories_create') ||
						permsArray.includes('admin')
					"
					#extra
				>
					<a-button type="primary" @click="addItem">
						<PlusOutlined />
						{{ $t("category.add") }}
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
					{{ $t(`menu.categories`) }}
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
			:destroyOnClose="true"
		/>

		<a-row>
			<a-col :span="24">
				<div class="table-responsive">
					<a-table
						:columns="columns"
						:row-key="(record) => record.xid"
						:data-source="allCategories"
						:defaultExpandAllRows="true"
					>
						<template #bodyCell="{ column, text, record }">
							<template v-if="column.dataIndex === 'image_url'">
								<a-image :width="32" :src="text" />
							</template>
							<template v-if="column.dataIndex === 'action'">
								<a-button
									v-if="
										permsArray.includes('categories_edit') ||
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
										(permsArray.includes('categories_delete') ||
											permsArray.includes('admin')) &&
										(!record.children || record.children.length == 0)
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
import { onMounted, ref, createVNode } from "vue";
import fields from "./fields";
import {
	PlusOutlined,
	EditOutlined,
	DeleteOutlined,
	ExclamationCircleOutlined,
} from "@ant-design/icons-vue";
import { Modal, notification } from "ant-design-vue";
import { useI18n } from "vue-i18n";
import common from "../../../../common/composable/common";
import AddEdit from "./AddEdit.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
	components: {
		PlusOutlined,
		EditOutlined,
		DeleteOutlined,
		ExclamationCircleOutlined,
		AddEdit,
		AdminPageHeader,
	},
	setup() {
		const { initData, columns } = fields();
		const { permsArray, getRecursiveCategories } = common();
		const { t } = useI18n();

		const detailsVisible = ref(false);
		const viewData = ref({});

		const addEditVisible = ref(false);
		const addEditType = ref("add");
		const addEditUrl = ref("categories");
		const allCategories = ref([]);

		const formData = ref({});

		onMounted(() => {
			getCategories();
		});

		const getCategories = () => {
			axiosAdmin
				.get(
					`categories?fields=id,xid,name,slug,parent_id,x_parent_id,image,image_url&order=parent_id asc&limit=10000`
				)
				.then((response) => {
					const allCategoriesArray = [];
					var listArray = response.data;
					// listArray = sortBy(listArray, "x_parent_id");

					listArray.forEach((node) => {
						// No parentId means top level
						if (!node.x_parent_id) return allCategoriesArray.push(node);

						// Insert node as child of parent in listArray array
						const parentIndex = listArray.findIndex(
							(el) => el.xid === node.x_parent_id
						);
						if (!listArray[parentIndex].children) {
							return (listArray[parentIndex].children = [node]);
						}

						listArray[parentIndex].children.push(node);
					});

					allCategories.value = allCategoriesArray;
				});
		};

		const viewItem = (item) => {
			viewData.value = item;
			detailsVisible.value = true;
		};

		const addItem = () => {
			addEditUrl.value = "categories";
			addEditType.value = "add";
			addEditVisible.value = true;
		};

		const addEditSuccess = (id) => {
			// If add action is performed then move page to first
			if (addEditType.value == "add") {
				formData.value = {
					name: "",
					slug: "",
					image: undefined,
					image_url: undefined,
					parent_id: null,
				};
			}

			getCategories();
			addEditVisible.value = false;
		};

		const onCloseAddEdit = () => {
			formData.value = { ...initData };
			addEditVisible.value = false;
		};

		const editItem = (item) => {
			formData.value = {
				name: item.name,
				slug: item.slug,
				image: item.image,
				image_url: item.image_url,
				parent_id: item.x_parent_id,
				_method: "PUT",
			};

			viewData.value = item;
			addEditUrl.value = `categories/${item.xid}`;
			addEditType.value = "edit";
			addEditVisible.value = true;
		};

		const showDeleteConfirm = (id) => {
			Modal.confirm({
				title: t("common.delete") + "?",
				icon: createVNode(ExclamationCircleOutlined),
				content: t("category.delete_message"),
				centered: true,
				okText: t("common.yes"),
				okType: "danger",
				cancelText: t("common.no"),
				onOk() {
					axiosAdmin.delete(`categories/${id}`).then(() => {
						getCategories();
						notification.success({
							message: t("common.success"),
							description: t("category.deleted"),
						});
					});
				},
				onCancel() {},
			});
		};

		return {
			columns,
			addEditSuccess,
			formData,
			editItem,
			addEditVisible,
			addItem,
			onCloseAddEdit,
			addEditUrl,
			addEditType,
			showDeleteConfirm,
			detailsVisible,
			viewItem,
			viewData,
			allCategories,
			permsArray,
		};
	},
};
</script>
