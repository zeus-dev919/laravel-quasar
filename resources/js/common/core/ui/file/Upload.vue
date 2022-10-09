<template>
	<a-upload
		accept="image/*"
		v-model:file-list="fileList"
		name="image"
		list-type="picture-card"
		class="avatar-uploader"
		:show-upload-list="false"
		:customRequest="customRequest"
	>
		<div v-if="formData[imageField] != undefined">
			<img style="width: 128px" :src="formData[`${imageField}_url`]" alt="avatar" />
		</div>
		<div v-else>
			<loading-outlined v-if="loading"></loading-outlined>
			<plus-outlined v-else></plus-outlined>
			<div class="ant-upload-text">{{ $t("common.upload") }}</div>
		</div>
	</a-upload>
</template>
<script>
import { PlusOutlined, LoadingOutlined } from "@ant-design/icons-vue";
import { message } from "ant-design-vue";
import { defineComponent, ref } from "vue";
import { useI18n } from "vue-i18n";

export default defineComponent({
	props: {
		formData: null,
		folder: String,
		imageField: {
			default: "image",
			type: String,
		},
	},
	components: {
		LoadingOutlined,
		PlusOutlined,
	},

	setup(props, { emit }) {
		const fileList = ref([]);
		const loading = ref(false);
		const { t } = useI18n();

		const customRequest = (info) => {
			const formData = new FormData();
			formData.append("image", info.file);
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
<style>
.avatar-uploader > .ant-upload {
	width: 128px;
	height: 128px;
}
.ant-upload-select-picture-card i {
	font-size: 32px;
	color: #999;
}

.ant-upload-select-picture-card .ant-upload-text {
	margin-top: 8px;
	color: #666;
}
</style>
