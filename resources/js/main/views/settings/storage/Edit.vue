<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.storage_settings`)" class="p-0">
				<template #extra>
					<a-button type="primary" @click="onSubmit">
						<template #icon> <SaveOutlined /> </template>
						{{ $t("common.update") }}
					</a-button>
				</template>
			</a-page-header>
		</template>
		<template #breadcrumb>
			<a-breadcrumb separator="-" style="font-size: 12px">
				<a-breadcrumb-item>
					<router-link :to="{ name: 'admin.dashboard.index' }">
						{{ $t(`menu.dashboard`) }}
					</router-link>
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t("menu.settings") }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t("menu.storage_settings") }}
				</a-breadcrumb-item>
			</a-breadcrumb>
		</template>
	</AdminPageHeader>

	<a-row>
		<a-col :xs="24" :sm="24" :md="24" :lg="4" :xl="4" class="bg-setting-sidebar">
			<SettingSidebar />
		</a-col>
		<a-col :xs="24" :sm="24" :md="24" :lg="20" :xl="20">
			<a-card class="page-content-container">
				<a-form layout="vertical">
					<a-row :gutter="16">
						<a-col :span="24">
							<a-form-item
								:label="$t('storage_settings.storage')"
								name="filesystem"
								:help="rules.filesystem ? rules.filesystem.message : null"
								:validateStatus="rules.filesystem ? 'error' : null"
							>
								<a-select
									v-model:value="formData.filesystem"
									:placeholder="
										$t('common.select_default_text', [
											$t('storage_settings.storage'),
										])
									"
								>
									<a-select-option key="local" value="local">
										{{ $t(`storage_settings.local`) }}
									</a-select-option>
									<a-select-option key="aws" value="aws">
										{{ $t(`storage_settings.aws`) }}
									</a-select-option>
								</a-select>
							</a-form-item>
						</a-col>
					</a-row>

					<div v-if="formData.filesystem == 'aws'">
						<a-row :gutter="16">
							<a-col :xs="24" :sm="24" :md="12" :lg="12">
								<a-form-item
									:label="$t('storage_settings.aws_key')"
									name="key"
									:help="rules.key ? rules.key.message : null"
									:validateStatus="rules.key ? 'error' : null"
									class="required"
								>
									<a-input
										v-model:value="formData.key"
										:placeholder="
											$t('common.placeholder_default_text', [
												$t('storage_settings.aws_key'),
											])
										"
									/>
								</a-form-item>
							</a-col>
							<a-col :xs="24" :sm="24" :md="12" :lg="12">
								<a-form-item
									:label="$t('storage_settings.aws_secret')"
									name="secret"
									:help="rules.secret ? rules.secret.message : null"
									:validateStatus="rules.secret ? 'error' : null"
									class="required"
								>
									<a-input-password
										v-model:value="formData.secret"
										:placeholder="
											$t('common.placeholder_default_text', [
												$t('storage_settings.aws_secret'),
											])
										"
									/>
								</a-form-item>
							</a-col>
						</a-row>
						<a-row :gutter="16">
							<a-col :xs="24" :sm="24" :md="12" :lg="12">
								<a-form-item
									:label="$t('storage_settings.aws_region')"
									name="region"
									:help="rules.region ? rules.region.message : null"
									:validateStatus="rules.region ? 'error' : null"
								>
									<a-select
										v-model:value="formData.region"
										:placeholder="
											$t('common.select_default_text', [
												$t('storage_settings.aws_region'),
											])
										"
										:allowClear="true"
										optionFilterProp="label"
										show-search
									>
										<a-select-option
											v-for="(
												awsRegionValue, awsRegionKey
											) in awsRegions"
											:key="awsRegionKey"
											:value="awsRegionKey"
											:label="awsRegionValue"
										>
											{{ awsRegionValue }}
										</a-select-option>
									</a-select>
								</a-form-item>
							</a-col>
							<a-col :xs="24" :sm="24" :md="12" :lg="12">
								<a-form-item
									:label="$t('storage_settings.aws_bucket')"
									name="bucket"
									:help="rules.bucket ? rules.bucket.message : null"
									:validateStatus="rules.bucket ? 'error' : null"
									class="required"
								>
									<a-input
										v-model:value="formData.bucket"
										:placeholder="
											$t('common.placeholder_default_text', [
												$t('storage_settings.aws_bucket'),
											])
										"
									/>
								</a-form-item>
							</a-col>
						</a-row>
					</div>

					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item>
								<a-button
									type="primary"
									@click="onSubmit"
									:loading="loading"
								>
									<template #icon> <SaveOutlined /> </template>
									{{ $t("common.update") }}
								</a-button>
							</a-form-item>
						</a-col>
					</a-row>
				</a-form>
			</a-card>
		</a-col>
	</a-row>
</template>
<script>
import { onMounted, ref } from "vue";
import { SaveOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import apiAdmin from "../../../../common/composable/apiAdmin";
import SettingSidebar from "../SettingSidebar.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
	components: {
		SaveOutlined,
		SettingSidebar,
		AdminPageHeader,
	},
	setup() {
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const { t } = useI18n();
		const formData = ref({});
		const awsRegions = ref([]);

		onMounted(() => {
			axiosAdmin.get("settings/storage").then((response) => {
				formData.value = response.data.storage;
				awsRegions.value = response.data.regions;
			});
		});

		const onSubmit = () => {
			addEditRequestAdmin({
				url: `settings/storage/update`,
				data: formData.value,
				successMessage: t("storage_settings.updated"),
				success: (res) => {},
			});
		};

		return {
			loading,
			rules,
			awsRegions,
			formData,

			onSubmit,
		};
	},
};
</script>
