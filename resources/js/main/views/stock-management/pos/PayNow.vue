<template>
	<a-drawer
		:title="$t('payments.order_payment')"
		width="50%"
		:maskClosable="false"
		:visible="visible"
		@close="drawerClosed"
	>
		<a-row>
			<a-col :xs="24" :sm="24" :md="8" :lg="8">
				<a-row>
					<a-col :span="24">
						<a-statistic
							:title="$t('stock.total_items')"
							:value="selectedProducts.length"
							style="margin-right: 50px"
						/>
					</a-col>
					<a-col :span="24" class="mt-20">
						<a-statistic
							:title="$t('stock.paying_amount')"
							:value="formatAmountCurrency(formData.amount)"
						/>
					</a-col>
					<a-col :span="24" class="mt-20">
						<a-statistic
							:title="$t('stock.payable_amount')"
							:value="formatAmountCurrency(data.subtotal)"
						/>
					</a-col>
					<a-col :span="24" class="mt-20">
						<a-statistic
							v-if="formData.amount <= data.subtotal"
							:title="$t('payments.due_amount')"
							:value="formatAmountCurrency(data.subtotal - formData.amount)"
						/>
						<a-statistic
							v-else
							:title="$t('stock.change_return')"
							:value="formatAmountCurrency(formData.amount - data.subtotal)"
						/>
					</a-col>
				</a-row>
			</a-col>
			<a-col :xs="24" :sm="24" :md="16" :lg="16">
				<a-form layout="vertical">
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('payments.payment_mode')"
								name="payment_mode_id"
								:help="
									rules.payment_mode_id
										? rules.payment_mode_id.message
										: null
								"
								:validateStatus="rules.payment_mode_id ? 'error' : null"
							>
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
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('stock.paying_amount')"
								name="amount"
								:help="rules.amount ? rules.amount.message : null"
								:validateStatus="rules.amount ? 'error' : null"
							>
								<a-input
									:prefix="appSetting.currency.symbol"
									v-model:value="formData.amount"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('stock.payable_amount'),
										])
									"
								/>
								<small style="color: #7c8db5 !important">
									{{ $t("stock.payable_amount") }}
									<span>
										{{ formatAmountCurrency(data.subtotal) }}
									</span>
								</small>
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
									:placeholder="$t('payments.notes')"
									:rows="5"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-button
								type="primary"
								:loading="loading"
								@click="onSubmit"
								block
							>
								<template #icon>
									<CheckOutlined />
								</template>
								{{ $t("common.pay") }}
							</a-button>
						</a-col>
					</a-row>
				</a-form>
			</a-col>
		</a-row>
	</a-drawer>
</template>

<script>
import { ref, onMounted } from "vue";
import { message } from "ant-design-vue";
import { CheckOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import common from "../../../../common/composable/common";
import apiAdmin from "../../../../common/composable/apiAdmin";

export default {
	props: ["visible", "data", "selectedProducts"],
	emits: ["closed", "success"],
	components: {
		CheckOutlined,
	},
	setup(props, { emit }) {
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const { appSetting, formatAmountCurrency } = common();
		const paymentModes = ref([]);
		const formData = ref({
			payment_mode_id: undefined,
			amount: 0,
			notes: "",
		});
		const { t } = useI18n();

		onMounted(() => {
			axiosAdmin.get("payment-modes").then((response) => {
				paymentModes.value = response.data;
			});
		});

		const drawerClosed = () => {
			formData.value = {
				payment_mode_id: undefined,
				amount: 0,
				notes: "",
			};
			emit("closed");
		};

		const onSubmit = () => {
			const newFormDataObject = {
				...formData.value,
				product_items: props.selectedProducts,
				details: props.data,
			};

			addEditRequestAdmin({
				url: "pos/save",
				data: newFormDataObject,
				successMessage: props.successMessage,
				success: (res) => {
					formData.value = {
						payment_mode_id: undefined,
						amount: 0,
						notes: "",
					};

					emit("success", res.order);
				},
			});
		};

		return {
			loading,
			rules,
			drawerClosed,
			paymentModes,
			formData,
			appSetting,
			formatAmountCurrency,
			onSubmit,
		};
	},
};
</script>

<style></style>
