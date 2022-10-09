<template>
	<div>
		<AddEdit
			:addEditType="addEditType"
			:visible="addEditVisible"
			:url="addEditUrl"
			@addEditSuccess="addEditSuccess"
			@closed="onCloseAddEdit"
			:formData="formData"
			:data="selectedItem"
			:editItemAmount="editItemAmount"
			:pageTitle="pageTitle"
			:successMessage="successMessage"
		/>

		<a-row :gutter="[6, 12]">
			<a-col :span="6" class="col-border-right">
				<a-row :gutter="[6, 12]">
					<a-col :span="24" class="mt-30">
						<a-typography-title
							:level="5"
							v-if="
								selectedItem.order_type == 'purchases' ||
								selectedItem.order_type == 'purchase-returns'
							"
						>
							{{ $t("stock.supplier") }}
						</a-typography-title>
						<a-typography-title :level="5" v-else>
							{{ $t("stock.customer") }}
						</a-typography-title>
						<UserInfo class="mt-10" :user="selectedItem.user" />
					</a-col>
					<a-col :span="24">
						<a-statistic
							:title="$t('payments.paid_amount')"
							:value="formatAmountCurrency(selectedItem.paid_amount)"
							style="margin-right: 50px"
						/>
					</a-col>
					<a-col :span="24">
						<a-statistic
							:title="$t('payments.total_amount')"
							:value="formatAmountCurrency(selectedItem.total)"
							style="margin-right: 50px"
						/>
					</a-col>
				</a-row>
			</a-col>
			<a-col :span="18" class="mt-30">
				<div style="min-height: calc(100vh - 110px)">
					<a-tabs v-model:activeKey="activeKey" class="ml-15">
						<a-tab-pane
							v-if="
								permsArray.includes('order_payments_view') ||
								permsArray.includes('admin')
							"
							key="payments"
							:tab="$t('payments.payments')"
						>
							<a-row>
								<a-col :span="24" style="margin-bottom: 10px">
									<a-button
										v-if="
											(permsArray.includes(
												'order_payments_create'
											) ||
												permsArray.includes('admin')) &&
											selectedItem.payment_status != 'paid'
										"
										type="primary"
										@click="addItem"
									>
										<PlusOutlined />
										{{ $t("payments.add") }}
									</a-button>
								</a-col>
								<a-col :span="24">
									<div class="table-responsive">
										<a-table
											:row-key="(record) => record.xid"
											:columns="orderPaymentsColumns"
											:data-source="selectedItem.order_payments"
											:pagination="false"
										>
											<template #bodyCell="{ column, record }">
												<template
													v-if="column.dataIndex === 'date'"
												>
													{{ formatDate(record.payment.date) }}
												</template>
												<template
													v-if="column.dataIndex === 'amount'"
												>
													{{
														formatAmountCurrency(
															record.amount
														)
													}}
												</template>
												<template
													v-if="
														column.dataIndex ===
														'payment_mode_id'
													"
												>
													{{ record.payment.payment_mode.name }}
												</template>
												<template
													v-if="column.dataIndex === 'action'"
												>
													<a-button
														type="primary"
														@click="editItem(record)"
														style="margin-left: 4px"
														v-if="
															permsArray.includes(
																'order_payments_edit'
															) ||
															permsArray.includes('admin')
														"
													>
														<template #icon
															><EditOutlined
														/></template>
													</a-button>
													<a-button
														type="primary"
														@click="
															showDeleteConfirm(record.xid)
														"
														style="margin-left: 4px"
														v-if="
															permsArray.includes(
																'order_payments_delete'
															) ||
															permsArray.includes('admin')
														"
													>
														<template #icon
															><DeleteOutlined
														/></template>
													</a-button>
												</template>
											</template>
										</a-table>
									</div>
								</a-col>
							</a-row>
						</a-tab-pane>
						<a-tab-pane key="order_items" :tab="$t('product.order_items')">
							<a-row>
								<a-col :span="24">
									<div class="table-responsive">
										<a-table
											:row-key="(record) => record.xid"
											:columns="orderItemDetailsColumns"
											:data-source="selectedItem.items"
											:pagination="false"
										>
											<template #bodyCell="{ column, record }">
												<template
													v-if="
														column.dataIndex === 'product_id'
													"
												>
													{{ record.product.name }}
												</template>
												<template
													v-if="
														column.dataIndex ===
														'single_unit_price'
													"
												>
													{{
														formatAmountCurrency(
															record.single_unit_price
														)
													}}
												</template>
												<template
													v-if="
														column.dataIndex ===
														'total_discount'
													"
												>
													{{
														formatAmountCurrency(
															record.total_discount
														)
													}}
												</template>
												<template
													v-if="
														column.dataIndex === 'total_tax'
													"
												>
													{{
														formatAmountCurrency(
															record.total_tax
														)
													}}
												</template>
												<template
													v-if="column.dataIndex === 'subtotal'"
												>
													{{
														formatAmountCurrency(
															record.subtotal
														)
													}}
												</template>
											</template>
										</a-table>
									</div>
								</a-col>
							</a-row>
						</a-tab-pane>
					</a-tabs>
				</div>
			</a-col>
		</a-row>
	</div>
</template>

<script>
import { ref, createVNode, computed } from "vue";
import {
	LeftOutlined,
	PlusOutlined,
	EditOutlined,
	DeleteOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import common from "../../../../common/composable/common";
import fields from "./fields";
import UserInfo from "../../../../common/components/user/UserInfo.vue";
import AddEdit from "../order-payments/AddEdit.vue";

export default {
	props: ["selectedItem"],
	components: {
		PlusOutlined,
		EditOutlined,
		DeleteOutlined,
		LeftOutlined,
		UserInfo,
		AddEdit,
	},
	setup(props, { emit }) {
		const {
			initPaymentData,
			orderPaymentsColumns,
			orderItemDetailsColumns,
		} = fields();
		const { formatAmountCurrency, permsArray, formatDate } = common();
		const { t } = useI18n();

		const addEditVisible = ref(false);
		const addEditType = ref("add");
		const addEditUrl = ref("order-payments");
		const formData = ref({});
		const viewData = ref({});
		const editItemAmount = ref(0);
		const activeKeyName =
			permsArray.value.includes("order_payments_view") ||
			permsArray.value.includes("admin")
				? "payments"
				: "order_items";
		const activeKey = ref(activeKeyName);

		const pageTitle = computed(() => {
			return addEditType.value == "add" ? t(`payments.add`) : t(`payments.edit`);
		});

		const successMessage = computed(() => {
			return addEditType.value == "add"
				? t(`payments.created`)
				: t(`payments.updated`);
		});

		const onClose = () => {
			emit("goBack");
		};

		const addItem = () => {
			addEditUrl.value = `order-payments`;
			addEditType.value = "add";
			formData.value = { ...initPaymentData, order_id: props.selectedItem.xid };
			addEditVisible.value = true;
			editItemAmount.value = 0;
		};

		const addEditSuccess = (id) => {
			emit("reloadOrder");

			// If add action is performed then move page to first
			if (addEditType.value == "add") {
				formData.value = {
					order_id: props.selectedItem.xid,
					date: undefined,
					payment_mode_id: undefined,
					amount: "",
					notes: "",
				};
			}
			addEditVisible.value = false;
		};

		const onCloseAddEdit = () => {
			formData.value = { ...initPaymentData, order_id: props.selectedItem.xid };
			addEditVisible.value = false;
			editItemAmount.value = 0;
		};

		return {
			onClose,

			formatDate,
			formatAmountCurrency,
			orderPaymentsColumns,
			orderItemDetailsColumns,
			activeKey,
			addEditSuccess,
			formData,
			addEditVisible,
			addItem,
			onCloseAddEdit,
			addEditUrl,
			addEditType,
			viewData,
			permsArray,
			editItemAmount,

			pageTitle,
			successMessage,
		};
	},
};
</script>
