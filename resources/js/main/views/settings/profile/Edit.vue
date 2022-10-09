<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.profile`)" class="p-0">
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
					{{ $t("menu.profile") }}
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
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('user.name')"
								name="name"
								:help="rules.name ? rules.name.message : null"
								:validateStatus="rules.name ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.name"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('user.name'),
										])
									"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('user.email')"
								name="email"
								:help="rules.email ? rules.email.message : null"
								:validateStatus="rules.email ? 'error' : null"
								class="required"
							>
								<a-input
									:value="formData.email"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('user.email'),
										])
									"
									disabled
								/>
							</a-form-item>
						</a-col>
					</a-row>

					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('user.password')"
								name="password"
								:help="rules.password ? rules.password.message : null"
								:validateStatus="rules.password ? 'error' : null"
							>
								<a-input
									v-model:value="formData.password"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('user.password'),
										])
									"
									type="password"
									autocomplete="off"
								/>
								<small class="small-text-message">
									{{ $t("user.password_blank") }}
								</small>
							</a-form-item>
						</a-col>
					</a-row>

					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('user.phone')"
								name="phone"
								:help="rules.phone ? rules.phone.message : null"
								:validateStatus="rules.phone ? 'error' : null"
							>
								<a-input
									v-model:value="formData.phone"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('user.phone'),
										])
									"
								/>
							</a-form-item>
						</a-col>
					</a-row>

					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('user.profile_image')"
								name="profile_image"
								:help="
									rules.profile_image
										? rules.profile_image.message
										: null
								"
								:validateStatus="rules.profile_image ? 'error' : null"
							>
								<Upload
									:formData="formData"
									folder="user"
									imageField="profile_image"
									@onFileUploaded="
										(file) => {
											formData.profile_image = file.file;
											formData.profile_image_url = file.file_url;
										}
									"
								/>
							</a-form-item>
						</a-col>
					</a-row>

					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('user.address')"
								name="address"
								:help="rules.address ? rules.address.message : null"
								:validateStatus="rules.address ? 'error' : null"
							>
								<a-textarea
									v-model:value="formData.address"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('user.address'),
										])
									"
									:rows="4"
								/>
							</a-form-item>
						</a-col>
					</a-row>

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
import {
	EyeOutlined,
	PlusOutlined,
	EditOutlined,
	DeleteOutlined,
	ExclamationCircleOutlined,
	SaveOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import apiAdmin from "../../../../common/composable/apiAdmin";
import Upload from "../../../../common/core/ui/file/Upload.vue";
import SettingSidebar from "../SettingSidebar.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
	components: {
		EyeOutlined,
		PlusOutlined,
		EditOutlined,
		DeleteOutlined,
		ExclamationCircleOutlined,
		SaveOutlined,

		Upload,
		SettingSidebar,
		AdminPageHeader,
	},
	setup() {
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const { t } = useI18n();
		const store = useStore();
		const formData = ref({});
		const currencies = ref({});
		const user = store.state.auth.user;

		onMounted(() => {
			formData.value = {
				name: user.name,
				email: user.email,
				password: "",
				phone: user.phone,
				profile_image: user.profile_image,
				profile_image_url: user.profile_image_url,
			};
		});

		const onSubmit = () => {
			addEditRequestAdmin({
				url: `profile`,
				data: formData.value,
				successMessage: t("user.profile_updated"),
				success: (res) => {
					store.commit("auth/updateUser", res.user);
				},
			});
		};

		return {
			loading,
			rules,
			formData,
			currencies,
			onSubmit,
		};
	},
};
</script>
