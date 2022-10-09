<template>
	<div
		class="main-container"
		:style="{ backgroundImage: `url(${verifyPurchaseBackground})` }"
	>
		<div class="container-content">
			<a-row type="flex" justify="center">
				<a-col :span="8">
					<div class="text-center">
						<a-typography-title :level="4">
							{{ $t("common.verify_purchase") }}
						</a-typography-title>
					</div>

					<a-divider />

					<a-alert v-if="successMessage != ''" type="success" show-icon banner>
						<template #message>
							<span v-html="successMessage" />
						</template>
					</a-alert>
					<a-alert v-if="errorMessage != ''" type="error" show-icon banner>
						<template #message>
							<span v-html="errorMessage" />
						</template>
					</a-alert>

					<a-form layout="vertical" class="mt-20">
						<a-form-item
							:label="$t('common.purchase_code')"
							name="purchase_code"
							:help="
								rules.purchase_code ? rules.purchase_code.message : null
							"
							:validateStatus="rules.purchase_code ? 'error' : null"
							class="required"
						>
							<a-input
								v-model:value="purchaseCode"
								:placeholder="
									$t('common.placeholder_default_text', [
										$t('user.name'),
									])
								"
							/>
						</a-form-item>
						<a-form-item>
							<a-button type="primary" @click="verify" :loading="loading">
								{{ $t("common.verify_purchase") }}
							</a-button>
						</a-form-item>
					</a-form>
				</a-col>
			</a-row>
		</div>
	</div>
</template>

<script>
import { defineComponent } from "vue";
import { useRouter } from "vue-router";
import modules from "../../../common/composable/modules";

export default defineComponent({
	setup() {
		const verifyPurchaseBackground = window.config.verify_purchase_background;
		const router = useRouter();
		const {
			rules,
			purchaseCode,
			errorMessage,
			successMessage,
			loading,
			verifyPurchase,
		} = modules();

		const verify = () =>
			verifyPurchase({
				success: (res) => {
					router.push({ name: "admin.login" });
				},
			});

		return {
			rules,
			purchaseCode,
			loading,
			verify,
			errorMessage,
			successMessage,

			verifyPurchaseBackground,
		};
	},
});
</script>

<style lang="less">
.main-container {
	height: 100vh;
}

.container-content {
	margin-top: 100px;
}
</style>
