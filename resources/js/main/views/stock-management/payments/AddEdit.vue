<template>
	<a-drawer
		:title="pageTitle"
		:width="drawerWidth"
		:visible="visible"
		:body-style="{ paddingBottom: '80px' }"
		:footer-style="{ textAlign: 'right' }"
		:maskClosable="false"
		@close="onClose"
	>
		<a-form layout="vertical">
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('payments.user')"
						name="user_id"
						:help="rules.user_id ? rules.user_id.message : null"
						:validateStatus="rules.user_id ? 'error' : null"
						class="required"
					>
						<a-select
							v-model:value="formData.user_id"
							:placeholder="
								$t('common.select_default_text', [$t('payments.user')])
							"
							:allowClear="true"
							option-label-prop="label"
							optionFilterProp="label"
							show-search
						>
							<a-select-option
								v-for="user in users"
								:key="user.xid"
								:value="user.xid"
								:label="user.name"
							>
								<UserInfo :user="user" size="small" />
							</a-select-option>
						</a-select>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16" v-if="addEditType == 'add'">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('payments.amount')"
						name="amount"
						:help="rules.amount ? rules.amount.message : null"
						:validateStatus="rules.amount ? 'error' : null"
						class="required"
					>
						<a-input
							:prefix="appSetting.currency.symbol"
							v-model:value="formData.amount"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('payments.amount'),
								])
							"
						/>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="12" :lg="12">
					<a-form-item
						:label="$t('payments.date')"
						name="date"
						:help="rules.date ? rules.date.message : null"
						:validateStatus="rules.date ? 'error' : null"
						class="required"
					>
						<a-date-picker
							v-model:value="formData.date"
							:format="appSetting.date_format"
							valueFormat="YYYY-MM-DD"
							:disabled-date="disabledDate"
							style="width: 100%"
						/>
					</a-form-item>
				</a-col>
				<a-col :xs="24" :sm="24" :md="12" :lg="12">
					<a-form-item
						:label="$t('payments.payment_mode')"
						name="payment_mode_id"
						:help="
							rules.payment_mode_id ? rules.payment_mode_id.message : null
						"
						:validateStatus="rules.payment_mode_id ? 'error' : null"
						class="required"
					>
						<span style="display: flex">
							<a-select
								v-model:value="formData.payment_mode_id"
								:placeholder="
									$t('common.select_default_text', [
										$t('payments.payment_mode'),
									])
								"
								:allowClear="true"
							>
								<a-select-option
									v-for="paymentMode in paymentModes"
									:key="paymentMode.xid"
									:value="paymentMode.xid"
								>
									{{ paymentMode.name }}
								</a-select-option>
							</a-select>
							<PaymentModeAddButton @onAddSuccess="paymentModeAdded" />
						</span>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('payments.notes')"
						name="notes"
						:help="rules.notes ? rules.notes.message : null"
						:validateStatus="rules.notes ? 'error' : null"
					>
						<a-textarea
							v-model:value="formData.notes"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('payments.notes'),
								])
							"
							:auto-size="{ minRows: 2, maxRows: 3 }"
						/>
					</a-form-item>
				</a-col>
			</a-row>

			<a-divider class="mt-0" />

			<div v-if="addEditType == 'add'">
				<a-row :gutter="16">
					<a-col :xs="24" :sm="24" :md="24" :lg="24">
						<a-form-item>
							<a-typography-paragraph type="warning" strong>
								<blockquote>
									{{ $t("payments.settle_invoice_using_payment") }}
								</blockquote>
							</a-typography-paragraph>
						</a-form-item>
					</a-col>
				</a-row>

				<SettleInvoices
					ref="settleInvoiceRef"
					:userId="formData.user_id"
					:amount="formData.amount"
				/>
			</div>
			<div v-else></div>
		</a-form>
		<template #footer>
			<a-space>
				<a-button
					key="submit"
					type="primary"
					:loading="loading"
					@click="onSubmit"
				>
					<template #icon>
						<SaveOutlined />
					</template>
					{{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
				</a-button>
				<a-button key="back" @click="onClose">
					{{ $t("common.cancel") }}
				</a-button>
			</a-space>
		</template>
	</a-drawer>
</template>
<script>
import { defineComponent, onMounted, ref } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import { forEach } from "lodash-es";
import apiAdmin from "../../../../common/composable/apiAdmin";
import UserInfo from "../../../../common/components/user/UserInfo.vue";
import common from "../../../../common/composable/common";
import PaymentModeAddButton from "../../settings/payment-modes/AddButton.vue";
import SettleInvoices from "./SettleInvoices.vue";

export default defineComponent({
	props: [
		"formData",
		"data",
		"visible",
		"url",
		"addEditType",
		"pageTitle",
		"successMessage",
	],
	components: {
		PlusOutlined,
		LoadingOutlined,
		SaveOutlined,

		UserInfo,
		PaymentModeAddButton,
		SettleInvoices,
	},
	setup(props, { emit }) {
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const { disabledDate, appSetting, formatAmountCurrency } = common();
		const paymentModes = ref([]);
		const users = ref([]);
		const usersUrl = "customer-suppliers";
		const paymentModesUrl = "payment-modes?limit=10000";
		const settleInvoiceRef = ref(null);

		onMounted(() => {
			const usersPromise = axiosAdmin.post(usersUrl);
			const paymentModesPromise = axiosAdmin.get(paymentModesUrl);

			Promise.all([usersPromise, paymentModesPromise]).then(
				([usersResponse, paymentModesResponse]) => {
					users.value = usersResponse.data;
					paymentModes.value = paymentModesResponse.data;
				}
			);
		});

		const onSubmit = () => {
			const invoices = [];

			if (props.addEditType == "add") {
				forEach(settleInvoiceRef.value.invoices, (invoice) => {
					invoices.push({
						order_id: invoice.xid,
						amount: invoice.paying_amount,
					});
				});
			}

			addEditRequestAdmin({
				url: props.url,
				data: { ...props.formData, invoices },
				successMessage: props.successMessage,
				success: (res) => {
					emit("addEditSuccess", res.xid);
				},
			});
		};

		const paymentModeAdded = () => {
			axiosAdmin.get("payment-modes?limit=10000").then((response) => {
				paymentModes.value = response.data;
			});
		};

		const onClose = () => {
			rules.value = {};
			emit("closed");
		};

		return {
			loading,
			rules,
			onClose,
			onSubmit,
			users,
			paymentModes,
			paymentModeAdded,
			settleInvoiceRef,

			appSetting,
			disabledDate,
			formatAmountCurrency,

			drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
		};
	},
});
</script>
