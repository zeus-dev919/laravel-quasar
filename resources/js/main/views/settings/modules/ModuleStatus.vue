<template>
	<span v-if="module.verified && module.installed">
		<a-tooltip>
			<template v-if="!module.verified || module.other_domain_verified" #title>
				<span v-html="$t('messages.first_verify_module_message')" />
			</template>
			<a-switch
				v-model:checked="checked"
				:loading="loading"
				@change="updateStatus"
				:disabled="!module.verified || module.other_domain_verified"
			/>
		</a-tooltip>
	</span>
	<span v-else> - </span>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import { notification } from "ant-design-vue";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";

export default defineComponent({
	props: ["module"],
	emits: ["updateSuccess"],
	setup(props, { emit }) {
		const checked = ref(false);
		const loading = ref(false);
		const store = useStore();
		const { t } = useI18n();

		onMounted(() => {
			if (!props.module.verified || props.module.other_domain_verified) {
				checked.value = false;
			} else {
				checked.value = props.module.status;
			}
		});

		const updateStatus = (checked, event) => {
			axiosAdmin
				.post("modules/update-status", {
					verified_name: props.module.verified_name,
					checked,
				})
				.then((response) => {
					notification.success({
						placement: "bottomRight",
						message: t("common.success"),
						description: t("module.module_status_updated"),
					});

					store.commit(
						"auth/updateActiveModules",
						response.data.enabled_modules
					);

					window.config.modules = response.data.enabled_modules;

					emit("updateSuccess");
				});
		};
		return {
			checked,
			loading,
			updateStatus,
		};
	},
});
</script>

<style></style>
