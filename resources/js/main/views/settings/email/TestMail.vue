<template>
	<a-button
		v-if="formData.mail_driver == 'mail' || formData.mail_driver == 'smtp'"
		@click="showModal"
		:disabled="!verified"
	>
		<template #icon> <SendOutlined /> </template>
		{{ $t("mail_settings.send_test_mail") }}
	</a-button>
	<a-modal
		v-model:visible="visible"
		:maskClosable="false"
		:title="$t('mail_settings.send_test_mail')"
		@cancel="handleCancel"
	>
		<a-form layout="vertical">
			<a-row v-if="errorMessage != ''" :gutter="16" class="mb-20">
				<a-col :span="24">
					<a-alert :message="errorMessage" type="error" show-icon />
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('mail_settings.email')"
						name="email"
						:help="rules.email ? rules.email.message : null"
						:validateStatus="rules.email ? 'error' : null"
						class="required"
					>
						<a-input
							v-model:value="email"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('common.email'),
								])
							"
						/>
					</a-form-item>
				</a-col>
			</a-row>
		</a-form>
		<template #footer>
			<a-button key="submit" type="primary" :loading="loading" @click="sendMail">
				<template #icon> <SendOutlined /> </template>
				{{ $t("common.send") }}
			</a-button>
			<a-button key="back" @click="handleCancel">
				{{ $t("common.cancel") }}
			</a-button>
		</template>
	</a-modal>
</template>

<script>
import { defineComponent, ref } from "vue";
import { SendOutlined } from "@ant-design/icons-vue";
import { notification } from "ant-design-vue";
import { useI18n } from "vue-i18n";
import apiAdmin from "../../../../common/composable/apiAdmin";

export default defineComponent({
	props: ["formData", "verified"],
	components: {
		SendOutlined,
	},
	setup() {
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const visible = ref(false);
		const email = ref("");
		const errorMessage = ref("");
		const { t } = useI18n();

		const sendMail = () => {
			errorMessage.value = "";

			addEditRequestAdmin({
				url: `settings/email/send-test-mail`,
				data: { email: email.value },
				success: (res) => {
					errorMessage.value = "";

					if (res.status == "success") {
						// Toastr Notificaiton
						notification.success({
							placement: "bottomRight",
							message: t("common.success"),
							description: t("mail_settings.test_mail_sent_successfully"),
						});
					} else {
						errorMessage.value = res.message;
					}
				},
				error: (err) => {
					errorMessage.value = "";
				},
			});
		};

		const showModal = () => {
			visible.value = true;
		};

		const handleCancel = () => {
			visible.value = false;
			loading.value = false;
			email.value = "";
			errorMessage.value = "";
			rules.value = {};
		};

		return {
			loading,
			rules,
			email,
			visible,
			showModal,
			sendMail,
			handleCancel,
			errorMessage,
		};
	},
});
</script>

<style></style>
