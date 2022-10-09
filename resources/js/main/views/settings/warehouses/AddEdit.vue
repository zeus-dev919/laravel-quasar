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
				<a-col :xs="24" :sm="24" :md="8" :lg="8">
					<a-row :gutter="16">
						<a-col :span="24">
							<a-form-item
								:label="$t('warehouse.logo')"
								name="logo"
								:help="rules.logo ? rules.logo.message : null"
								:validateStatus="rules.logo ? 'error' : null"
							>
								<Upload
									:formData="formData"
									folder="warehouses"
									imageField="logo"
									@onFileUploaded="
										(file) => {
											formData.logo = file.file;
											formData.logo_url = file.file_url;
										}
									"
								/>
							</a-form-item>
						</a-col>
					</a-row>
				</a-col>
				<a-col :xs="24" :sm="24" :md="16" :lg="16">
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('warehouse.name')"
								name="name"
								:help="rules.name ? rules.name.message : null"
								:validateStatus="rules.name ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.name"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('warehouse.name'),
										])
									"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="16" :lg="16">
							<a-form-item
								:label="$t('warehouse.email')"
								name="email"
								:help="rules.email ? rules.email.message : null"
								:validateStatus="rules.email ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.email"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('warehouse.email'),
										])
									"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="8" :lg="8">
							<a-form-item
								:label="$t('warehouse.show_email_on_invoice')"
								name="show_email_on_invoice"
								:help="
									rules.show_email_on_invoice
										? rules.show_email_on_invoice.message
										: null
								"
								:validateStatus="
									rules.show_email_on_invoice ? 'error' : null
								"
							>
								<a-switch
									v-model:checked="formData.show_email_on_invoice"
									:checkedValue="1"
									:unCheckedValue="0"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="16" :lg="16">
							<a-form-item
								:label="$t('warehouse.phone')"
								name="phone"
								:help="rules.phone ? rules.phone.message : null"
								:validateStatus="rules.phone ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.phone"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('warehouse.phone'),
										])
									"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="8" :lg="8">
							<a-form-item
								:label="$t('warehouse.show_phone_on_invoice')"
								name="show_phone_on_invoice"
								:help="
									rules.show_phone_on_invoice
										? rules.show_phone_on_invoice.message
										: null
								"
								:validateStatus="
									rules.show_phone_on_invoice ? 'error' : null
								"
							>
								<a-switch
									v-model:checked="formData.show_phone_on_invoice"
									:checkedValue="1"
									:unCheckedValue="0"
								/>
							</a-form-item>
						</a-col>
					</a-row>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('warehouse.address')"
						name="address"
						:help="rules.address ? rules.address.message : null"
						:validateStatus="rules.address ? 'error' : null"
					>
						<a-textarea
							v-model:value="formData.address"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('warehouse.address'),
								])
							"
							:auto-size="{ minRows: 2, maxRows: 3 }"
						/>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item>
						<a-typography-paragraph type="warning" strong>
							<blockquote>
								{{ $t("warehouse.details_will_be_shown_on_invoice") }}
							</blockquote>
						</a-typography-paragraph>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('warehouse.bank_details')"
						name="bank_details"
						:help="rules.bank_details ? rules.bank_details.message : null"
						:validateStatus="rules.bank_details ? 'error' : null"
					>
						<a-textarea
							v-model:value="formData.bank_details"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('warehouse.bank_details'),
								])
							"
							:auto-size="{ minRows: 2, maxRows: 3 }"
						/>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('warehouse.terms_condition')"
						name="terms_condition"
						:help="
							rules.terms_condition ? rules.terms_condition.message : null
						"
						:validateStatus="rules.terms_condition ? 'error' : null"
					>
						<a-textarea
							v-model:value="formData.terms_condition"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('warehouse.terms_condition'),
								])
							"
							:auto-size="{ minRows: 2, maxRows: 3 }"
						/>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :span="24">
					<a-form-item
						:label="$t('warehouse.signature')"
						name="signature"
						:help="rules.signature ? rules.signature.message : null"
						:validateStatus="rules.signature ? 'error' : null"
					>
						<Upload
							:formData="formData"
							folder="warehouses"
							imageField="signature"
							@onFileUploaded="
								(file) => {
									formData.signature = file.file;
									formData.signature_url = file.file_url;
								}
							"
						/>
					</a-form-item>
				</a-col>
			</a-row>
		</a-form>
		<template #footer>
			<a-button
				type="primary"
				@click="onSubmit"
				style="margin-right: 8px"
				:loading="loading"
			>
				<template #icon> <SaveOutlined /> </template>
				{{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
			</a-button>
			<a-button @click="onClose">
				{{ $t("common.cancel") }}
			</a-button>
		</template>
	</a-drawer>
</template>

<script>
import { defineComponent } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import Upload from "../../../../common/core/ui/file/Upload.vue";
import { useStore } from "vuex";

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
		Upload,
	},
	setup(props, { emit }) {
		const store = useStore();
		const { addEditRequestAdmin, loading, rules } = apiAdmin();

		const onSubmit = () => {
			addEditRequestAdmin({
				url: props.url,
				data: props.formData,
				successMessage: props.successMessage,
				success: (res) => {
					emit("addEditSuccess", res.xid);
					store.dispatch("auth/updateAllWarehouses");
				},
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

			drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
		};
	},
});
</script>
