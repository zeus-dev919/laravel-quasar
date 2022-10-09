<template>
	<div v-if="formData[uploadField] == undefined">
		<a-upload
			:accept="acceptFormat"
			v-model:file-list="fileList"
			name="file"
			:customRequest="customRequest"
		>
			<a-button :loading="loading">
				<template #icon>
					<UploadOutlined></UploadOutlined>
				</template>
				{{ $t("common.upload") }}
			</a-button>
		</a-upload>
	</div>
	<div v-else>
		<a-typography-link :href="formData[`${uploadField}_url`]" target="_blank">
			<a-tag color="success">
				<template #icon>
					<DownloadOutlined />
				</template>
				{{ $t("common.download") }}
			</a-tag>
		</a-typography-link>
		<a-typography-link
			@click="
				() => {
					formData[uploadField] = undefined;
					formData[`${uploadField}_url`] = undefined;
				}
			"
		>
			<a-tag color="error">
				<template #icon>
					<DeleteOutlined />
				</template>
				{{ $t("common.delete") }}
			</a-tag>
		</a-typography-link>
	</div>
</template>
<script>
import { UploadOutlined, DownloadOutlined, DeleteOutlined } from "@ant-design/icons-vue";
import { message } from "ant-design-vue";
import { defineComponent, ref } from "vue";
import { useI18n } from "vue-i18n";

export default defineComponent({
	props: {
		acceptFormat: {
			default: "image/*,.pdf",
			type: String,
		},
		formData: null,
		folder: String,
		uploadField: {
			default: "file",
			type: String,
		},
	},
	components: {
		UploadOutlined,
		DownloadOutlined,
		DeleteOutlined,
	},

	setup(props, { emit }) {
		const fileList = ref([]);
		const loading = ref(false);
		const { t } = useI18n();

		const customRequest = (info) => {
			const formData = new FormData();
			formData.append("file", info.file);
			formData.append("folder", props.folder);

			loading.value = true;

			axiosAdmin
				.post("upload-file", formData, {
					headers: {
						"Content-Type": "multipart/form-data",
					},
				})
				.then((response) => {
					fileList.value = [];
					loading.value = false;

					emit("onFileUploaded", {
						file: response.data.file,
						file_url: response.data.file_url,
					});
				})
				.catch(() => {
					loading.value = false;
					message.error(t("messages.uploading_failed"));
				});
		};

		return {
			fileList,
			loading,
			customRequest,
		};
	},
});
</script>
