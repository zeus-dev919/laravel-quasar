<template>
	<a-button @click="refetchTranslations" :loading="loading">
		<template #icon>
			<SyncOutlined />
		</template>
		{{ $t("translations.fetch_new_translations") }}
	</a-button>
</template>

<script>
import { defineComponent } from "vue";
import { SyncOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import apiAdmin from "../../../../common/composable/apiAdmin";

export default defineComponent({
	emits: ["fetchTranslationsSuccesss"],
	components: {
		SyncOutlined,
	},
	setup(props, { emit }) {
		const { addEditRequestAdmin, loading } = apiAdmin();
		const { t } = useI18n();

		const refetchTranslations = () => {
			addEditRequestAdmin({
				url: "translations/refetch",
				data: {},
				successMessage: t("translations.fetched_successfully"),
				success: (res) => {
					emit("fetchTranslationsSuccesss");
				},
			});
		};

		return {
			loading,
			refetchTranslations,
		};
	},
});
</script>

<style></style>
