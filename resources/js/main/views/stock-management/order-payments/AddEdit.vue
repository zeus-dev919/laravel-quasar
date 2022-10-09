<template>
	<a-modal
		:visible="visible"
		:closable="false"
		:centered="true"
		:title="pageTitle"
		@ok="onSubmit"
	>
		<a-form layout="vertical">
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
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
			</a-row>
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
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
						<small style="color: #7c8db5 !important">
							{{ $t("common.max_amount") }}
							<span v-if="addEditType == 'edit'">
								{{
									formatAmountCurrency(data.due_amount + editItemAmount)
								}}
							</span>
							<span v-else>
								{{ formatAmountCurrency(data.due_amount) }}
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
		</a-form>
		<template #footer>
			<a-button key="submit" type="primary" :loading="loading" @click="onSubmit">
				<template #icon>
					<SaveOutlined />
				</template>
				{{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
			</a-button>
			<a-button key="back" @click="onClose">
				{{ $t("common.cancel") }}
			</a-button>
		</template>
	</a-modal>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import common from "../../../../common/composable/common";
import PaymentModeAddButton from "../../settings/payment-modes/AddButton.vue";

export default defineComponent({
	props: [
		"formData",
		"data",
		"visible",
		"url",
		"addEditType",
		"editItemAmount",
		"pageTitle",
		"successMessage",
	],
	components: {
		PlusOutlined,
		LoadingOutlined,
		SaveOutlined,
		PaymentModeAddButton,
	},
	setup(props, { emit }) {
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const { disabledDate, appSetting, formatAmountCurrency } = common();

		const paymentModes = ref([]);

		onMounted(() => {
			getInitialData();
		});

		const getInitialData = () => {
			axiosAdmin.get("payment-modes?limit=10000").then((response) => {
				paymentModes.value = response.data;
			});
		};

		const onSubmit = () => {
			addEditRequestAdmin({
				url: props.url,
				data: props.formData,
				successMessage: props.successMessage,
				success: (res) => {
					emit("addEditSuccess", res.xid);
				},
			});
		};

		const paymentModeAdded = () => {
			getInitialData();
		};

		const onClose = () => {
			rules.value = {};
			emit("closed");
		};

		return {
			paymentModes,
			appSetting,
			formatAmountCurrency,
			disabledDate,
			loading,
			rules,
			onClose,
			onSubmit,
			paymentModeAdded,
		};
	},
});
</script>
