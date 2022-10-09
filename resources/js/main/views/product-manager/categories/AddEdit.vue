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
						:label="$t('category.parent_category')"
						name="parent_id"
						:help="rules.parent_id ? rules.parent_id.message : null"
						:validateStatus="rules.parent_id ? 'error' : null"
					>
						<a-tree-select
							v-model:value="formData.parent_id"
							show-search
							style="width: 100%"
							:dropdown-style="{ maxHeight: '400px', overflow: 'auto' }"
							:placeholder="
								$t('common.select_default_text', [
									$t('category.category'),
								])
							"
							:tree-data="allCategories"
							allow-clear
							tree-default-expand-all
						/>
						<small class="small-text-message">
							{{ $t("messages.leave_blank_to_create_parent_category") }}
						</small>
					</a-form-item>
				</a-col>
			</a-row>
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('category.name')"
						name="name"
						:help="rules.name ? rules.name.message : null"
						:validateStatus="rules.name ? 'error' : null"
						class="required"
					>
						<a-input
							v-model:value="formData.name"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('category.name'),
								])
							"
							v-on:keyup="formData.slug = slugify($event.target.value)"
						/>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('category.slug')"
						name="slug"
						:help="rules.slug ? rules.slug.message : null"
						:validateStatus="rules.slug ? 'error' : null"
						class="required"
					>
						<a-input
							v-model:value="formData.slug"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('category.slug'),
								])
							"
						/>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('category.logo')"
						name="image"
						:help="rules.image ? rules.image.message : null"
						:validateStatus="rules.image ? 'error' : null"
					>
						<Upload
							:formData="formData"
							folder="category"
							@onFileUploaded="
								(file) => {
									formData.image = file.file;
									formData.image_url = file.file_url;
								}
							"
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
import { defineComponent, ref, computed, watch } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import apiAdmin from "../../../../common/composable/apiAdmin";
import Upload from "../../../../common/core/ui/file/Upload.vue";
import common from "../../../../common/composable/common";

export default defineComponent({
	props: ["formData", "data", "visible", "url", "addEditType"],
	components: {
		PlusOutlined,
		LoadingOutlined,
		SaveOutlined,
		Upload,
	},
	setup(props, { emit }) {
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const { getRecursiveCategories, slugify } = common();
		const { t } = useI18n();
		const allCategories = ref([]);

		const getCategories = (xid = null) => {
			var url = "categories?fields=id,xid,name,parent_id,x_parent_id";
			if (xid != null) {
				url += `&filters=id ne ${xid}&hashable=${xid}`;
			}

			axiosAdmin.get(url).then((response) => {
				allCategories.value = getRecursiveCategories(response, xid);
			});
		};

		const pageTitle = computed(() => {
			return props.addEditType == "add" ? t("category.add") : t("category.edit");
		});

		const onSubmit = () => {
			var addEditData = { parent_id: null, ...props.formData };
			const successMessage =
				props.addEditType == "add"
					? t("category.created")
					: t("category.updated");

			addEditRequestAdmin({
				url: props.url,
				data: addEditData,
				successMessage,
				success: (res) => {
					getCategories();
					emit("addEditSuccess", res.xid);
				},
			});
		};

		const onClose = () => {
			rules.value = {};
			emit("closed");
		};

		watch(props, (newVal, oldVal) => {
			if (newVal.addEditType == "add") {
				getCategories();
			} else {
				getCategories(newVal.data.xid);
			}
		});

		return {
			loading,
			rules,
			onClose,
			onSubmit,
			pageTitle,
			allCategories,
			slugify,
		};
	},
});
</script>
