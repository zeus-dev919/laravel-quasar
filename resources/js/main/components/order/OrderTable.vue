<template>
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
						<template v-if="column.dataIndex === 'order_date'">
							{{ formatDate(record.order_date) }}
						</template>
						<template v-if="column.dataIndex === 'user_id'">
							<user-info :user="record.user" />
						</template>
						<template v-if="column.dataIndex === 'paid_amount'">
							{{ formatAmountCurrency(record.paid_amount) }}
						</template>
						<template v-if="column.dataIndex === 'total_amount'">
							{{ formatAmountCurrency(record.total) }}
						</template>
						<template v-if="column.dataIndex === 'payment_status'">
							<PaymentStatus :paymentStatus="record.payment_status" />
						</template>
						<template v-if="column.dataIndex === 'order_status'">
							<OrderStatus :data="record" />
						</template>
						<template v-if="column.dataIndex === 'action'">
							<a-dropdown placement="bottomRight">
								<MoreOutlined />
								<template #overlay>
									<a-menu>
										<a-menu-item
											key="view"
											v-if="
												permsArray.includes(
													`${pageObject.permission}_view`
												) || permsArray.includes('admin')
											"
											@click="viewItem(record)"
										>
											<EyeOutlined />
											{{ $t("common.view") }}
										</a-menu-item>
										<a-menu-item
											key="edit"
											v-if="
												permsArray.includes(
													`${pageObject.permission}_edit`
												) || permsArray.includes('admin')
											"
											@click="
												() =>
													$router.push({
														name: `admin.stock.${pageObject.type}.edit`,
														params: {
															id: record.xid,
														},
													})
											"
										>
											<EditOutlined />
											{{ $t("common.edit") }}
										</a-menu-item>
										<a-menu-item
											key="delete"
											v-if="
												(permsArray.includes(
													`${pageObject.permission}_delete`
												) ||
													permsArray.includes('admin')) &&
												record.payment_status == 'unpaid'
											"
											@click="showDeleteConfirm(record.xid)"
										>
											<DeleteOutlined />
											{{ $t("common.delete") }}
										</a-menu-item>
										<a-menu-item key="download">
											<a-typography-link
												:href="`${invoiceBaseUrl}/${record.unique_id}`"
												target="_blank"
											>
												<DownloadOutlined />
												{{ $t("common.download") }}
											</a-typography-link>
										</a-menu-item>
									</a-menu>
								</template>
							</a-dropdown>
						</template>
					</template>
					<template #expandedRowRender="orderItemData">
						<a-table
							v-if="
								orderItemData &&
								orderItemData.record &&
								orderItemData.record.items
							"
							:row-key="(record) => record.xid"
							:columns="orderItemDetailsColumns"
							:data-source="orderItemData.record.items"
							:pagination="false"
						>
							<template #bodyCell="{ column, record }">
								<template v-if="column.dataIndex === 'product_id'">
									{{ record.product.name }}
								</template>
								<template v-if="column.dataIndex === 'single_unit_price'">
									{{ formatAmountCurrency(record.single_unit_price) }}
								</template>
								<template v-if="column.dataIndex === 'total_discount'">
									{{ formatAmountCurrency(record.total_discount) }}
								</template>
								<template v-if="column.dataIndex === 'total_tax'">
									{{ formatAmountCurrency(record.total_tax) }}
								</template>
								<template v-if="column.dataIndex === 'subtotal'">
									{{ formatAmountCurrency(record.subtotal) }}
								</template>
							</template>
						</a-table>
					</template>
				</a-table>
			</div>
		</a-col>
	</a-row>

	<a-drawer
		placement="right"
		:closable="true"
		width="60%"
		:bodyStyle="{ paddingTop: '0px' }"
		:visible="detailsDrawerVisible"
		@close="onDetailDrawerClose"
		:headerStyle="{ width: '700px' }"
	>
		<template #title>
			<a-page-header style="padding: 0px">
				<template #title>
					{{ $t(`menu.${pageObject.langKey}`) }} #{{
						selectedItem.invoice_number
					}}
					<PaymentStatus :paymentStatus="selectedItem.payment_status" />
				</template>
			</a-page-header>
			<a-breadcrumb>
				<a-breadcrumb-item>
					<div style="width: 50%">
						<a-progress
							:percent="
								parseFloat(
									parseFloat(
										(parseFloat(selectedItem.paid_amount) /
											parseFloat(selectedItem.total)) *
											100
									).toFixed(2)
								)
							"
						/>
					</div>
				</a-breadcrumb-item>
			</a-breadcrumb>
		</template>
		<div v-if="selectedItem && selectedItem.xid">
			<Details
				:selectedItem="selectedItem"
				@goBack="restSelectedItem"
				@reloadOrder="paymentSuccess"
			/>
		</div>
	</a-drawer>
</template>

<script>
import { onMounted, watch, ref, createVNode } from "vue";
import {
	EyeOutlined,
	PlusOutlined,
	EditOutlined,
	DeleteOutlined,
	ExclamationCircleOutlined,
	MoreOutlined,
	DownloadOutlined,
} from "@ant-design/icons-vue";
import { Modal, notification } from "ant-design-vue";
import { useRoute } from "vue-router";
import fields from "../../views/stock-management/purchases/fields";
import common from "../../../common/composable/common";
import datatable from "../../../common/composable/datatable";
import PaymentStatus from "../../../common/components/order/PaymentStatus.vue";
import OrderStatus from "../../../common/components/order/OrderStatus.vue";
import Details from "../../views/stock-management/purchases/Details.vue";
import UserInfo from "../../../common/components/user/UserInfo.vue";
import { find } from "lodash-es";
import { useI18n } from "vue-i18n";

export default {
	props: ["orderType", "filters", "extraFilters", "pagination", "perPageItems"],
	components: {
		EyeOutlined,
		PlusOutlined,
		EditOutlined,
		DeleteOutlined,
		MoreOutlined,
		DownloadOutlined,
		ExclamationCircleOutlined,
		Details,
		UserInfo,
		Details,
		PaymentStatus,
		OrderStatus,
	},
	setup(props) {
		const {
			columns,
			hashableColumns,
			setupTableColumns,
			filterableColumns,
			pageObject,
			orderType,
			orderStatus,
			orderItemDetailsColumns,
		} = fields();
		const datatableVariables = datatable();
		const {
			formatAmountCurrency,
			invoiceBaseUrl,
			permsArray,
			calculateOrderFilterString,
			formatDate,
			selectedWarehouse,
		} = common();
		const route = useRoute();
		const { t } = useI18n();
		const detailsDrawerVisible = ref(false);

		const selectedItem = ref({});

		onMounted(() => {
			initialSetup();
		});

		const initialSetup = () => {
			orderType.value = props.orderType;
			if (props.perPageItems) {
				datatableVariables.table.pagination.pageSize = props.perPageItems;
			}
			datatableVariables.table.pagination.current = 1;
			datatableVariables.table.pagination.currentPage = 1;
			datatableVariables.hashable.value = hashableColumns;
			setupTableColumns();
			setUrlData();
		};

		const setUrlData = () => {
			const tableFilter = props.filters;

			const filterString = calculateOrderFilterString(tableFilter);

			datatableVariables.tableUrl.value = {
				url: `${props.orderType}?fields=id,xid,unique_id,invoice_number,order_type,order_date,tax_amount,discount,shipping,subtotal,paid_amount,due_amount,order_status,payment_status,total,user_id,x_user_id,user{id,xid,user_type,name,profile_image,profile_image_url,phone},orderPayments{id,xid,amount,payment_id,x_payment_id},orderPayments:payment{id,xid,amount,payment_mode_id,x_payment_mode_id,date,notes},orderPayments:payment:paymentMode{id,xid,name},items{id,xid,product_id,x_product_id,single_unit_price,unit_price,quantity,tax_rate,total_tax,tax_type,total_discount,subtotal},items:product{id,xid,name},cancelled,terms_condition`,
				filterString,
				filters: {
					user_id: tableFilter.user_id ? tableFilter.user_id : undefined,
				},
				extraFilters: tableFilter.dates ? { dates: tableFilter.dates } : {},
			};
			datatableVariables.table.filterableColumns = filterableColumns;

			if (
				tableFilter.searchColumn &&
				tableFilter.searchString &&
				tableFilter.searchString != ""
			) {
				datatableVariables.table.searchColumn = tableFilter.searchColumn;
				datatableVariables.table.searchString = tableFilter.searchString;
			} else {
				datatableVariables.table.searchColumn = undefined;
				datatableVariables.table.searchString = "";
			}

			datatableVariables.fetch({
				page: 1,
			});
		};

		const showDeleteConfirm = (id) => {
			Modal.confirm({
				title: t("common.delete") + "?",
				icon: createVNode(ExclamationCircleOutlined),
				content: t(`${pageObject.value.langKey}.delete_message`),
				centered: true,
				okText: t("common.yes"),
				okType: "danger",
				cancelText: t("common.no"),
				onOk() {
					axiosAdmin.delete(`${props.orderType}/${id}`).then(() => {
						setUrlData();
						notification.success({
							message: t("common.success"),
							description: t(`${pageObject.value.langKey}.deleted`),
						});
					});
				},
				onCancel() {},
			});
		};

		const viewItem = (record) => {
			selectedItem.value = record;
			detailsDrawerVisible.value = true;
		};

		const restSelectedItem = () => {
			selectedItem.value = {};
		};

		const paymentSuccess = () => {
			datatableVariables.fetch({
				page: datatableVariables.currentPage.value,
				success: (results) => {
					const searchResult = find(results, (result) => {
						return result.xid == selectedItem.value.xid;
					});

					if (searchResult != undefined) {
						selectedItem.value = searchResult;
					}
				},
			});
		};

		const onDetailDrawerClose = () => {
			detailsDrawerVisible.value = false;
		};

		watch(props, (newVal, oldVal) => {
			initialSetup();
			restSelectedItem();
		});

		watch(selectedWarehouse, (newVal, oldVal) => {
			setUrlData();
		});

		return {
			columns,
			...datatableVariables,
			filterableColumns,
			pageObject,

			formatDate,
			orderStatus,

			setUrlData,
			formatAmountCurrency,
			invoiceBaseUrl,
			permsArray,

			selectedItem,
			viewItem,
			restSelectedItem,
			paymentSuccess,

			showDeleteConfirm,

			detailsDrawerVisible,
			onDetailDrawerClose,
			orderItemDetailsColumns,
		};
	},
};
</script>
