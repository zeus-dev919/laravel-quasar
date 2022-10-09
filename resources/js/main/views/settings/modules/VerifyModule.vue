<template>
	<div v-if="module.other_domain_verified">
		<a-tag :color="'red'">
			{{ $t("messages.other_domain_linked") }}
		</a-tag>
		<br />
		<a-button type="link" class="p-0 mt-10" @click="showModal">
			{{ $t("module.verify_purchase_code") }}
		</a-button>
	</div>

	<a-typography-text v-else-if="module.verified" type="success" strong>
		{{ $t("module.verified") }}
	</a-typography-text>
	<a-typography-text v-else-if="module.is_free" type="success" strong>
		-
	</a-typography-text>
	<a-button v-else type="link" class="p-0" @click="showModal">
		{{ $t("module.verify_purchase_code") }}
	</a-button>

	<a-modal
		v-model:visible="visible"
		:title="$t('module.verify_purchase_code')"
		:maskClosable="false"
		@cancel="handleCancel"
	>
		<a-alert
			v-if="successMessage != ''"
			class="mb-10"
			type="success"
			show-icon
			banner
		>
			<template #message>
				<span v-html="successMessage" />
			</template>
		</a-alert>
		<a-alert v-if="errorMessage != ''" class="mb-10" type="error" show-icon banner>
			<template #message>
				<span v-html="errorMessage" />
			</template>
		</a-alert>

		<a-row class="mb-10" v-if="module.other_domain_verified && errorMessage == ''">
			<a-col :span="24">
				<a-alert type="error" show-icon>
					<template #message>
						{{ $t("messages.other_domain_linked_with_purchase_code") }}
					</template>
				</a-alert>
			</a-col>
		</a-row>

		<a-row class="mb-10">
			<a-col :span="24">
				<a-typography-paragraph>
					<blockquote>
						<a-typography-link
							href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-"
							target="_blank"
						>
							<InfoCircleOutlined />
							{{ $t("messages.click_here_to_find_purchase_code") }}
						</a-typography-link>
					</blockquote>
				</a-typography-paragraph>
			</a-col>
		</a-row>

		<a-form layout="vertical">
			<a-form-item :label="$t('common.domain')" name="domain">
				<a-typography-text strong>
					{{ domain }}
				</a-typography-text>
			</a-form-item>
			<a-form-item
				:label="$t('common.purchase_code')"
				name="purchase_code"
				:help="rules.purchase_code ? rules.purchase_code.message : null"
				:validateStatus="rules.purchase_code ? 'error' : null"
				class="required"
			>
				<a-input
					v-model:value="purchaseCode"
					:placeholder="
						$t('common.placeholder_default_text', [
							$t('common.purchase_code'),
						])
					"
				/>
			</a-form-item>
		</a-form>
		<template #footer>
			<a-button key="submit" type="primary" @click="verify" :loading="loading">
				<template #icon>
					<CheckOutlined />
				</template>
				{{ $t("common.verify") }}
			</a-button>
			<a-button key="back" @click="handleCancel">
				{{ $t("common.cancel") }}
			</a-button>
		</template>
	</a-modal>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import { CheckOutlined, InfoCircleOutlined } from "@ant-design/icons-vue";
import modules from "../../../../common/composable/modules";

export default defineComponent({
	props: ["module"],
	emits: ["verifySuccess"],
	components: {
		CheckOutlined,
		InfoCircleOutlined,
	},
	setup(props, { emit }) {
		const visible = ref(false);
		const domain = window.location.host;
		const {
			rules,
			purchaseCode,
			errorMessage,
			successMessage,
			loading,
			verifyPurchase,
			productName,
			version,
		} = modules();

		onMounted(() => {
			productName.value = props.module.verified_name;
			version.value = props.module.current_version;
		});

		const showModal = () => {
			visible.value = true;
		};

		const handleCancel = () => {
			purchaseCode.value = "";
			visible.value = false;
		};

		const verify = () =>
			verifyPurchase({
				success: (res) => {
					emit("verifySuccess");
					purchaseCode.value = "";
					visible.value = false;
				},
			});

		return {
			visible,
			showModal,
			handleCancel,
			domain,

			rules,
			purchaseCode,
			loading,
			verify,
			errorMessage,
			successMessage,
		};
	},
});
</script>

<style></style>
