<template>
	<a-button
		v-if="productStatus == 'update_available'"
		type="primary"
		@click="updateApp"
	>
		<SyncOutlined />
		{{ $t("update_app.update_app") }}
	</a-button>

	<a-modal
		v-model:visible="installUpdateModalVisible"
		:title="$t('common.installing')"
		:maskClosable="false"
		:closable="false"
		:footer="null"
	>
		<a-row class="mb-10">
			<a-col :span="24">
				<a-typography-text v-if="downloadPercentage < 100">
					<SyncOutlined spin /> {{ $t("messages.downloading_started_message") }}
				</a-typography-text>
				<a-typography-text v-else strong type="success">
					<CheckCircleOutlined /> {{ $t("module.downloading_completed") }}
				</a-typography-text>
				<a-progress
					v-if="downloadPercentage < 100"
					:percent="downloadPercentage"
				/>
			</a-col>
		</a-row>
		<a-row class="mb-10">
			<a-col :span="24">
				<a-typography-text v-if="extracting == ''" type="secondary">
					<InfoOutlined /> {{ $t("module.extract_zip_file") }}
				</a-typography-text>
				<a-typography-text v-if="extracting == 'started'">
					<SyncOutlined spin /> {{ $t("messages.file_extracting_message") }}
				</a-typography-text>
				<a-typography-text v-if="extracting == 'completed'" strong type="success">
					<CheckCircleOutlined />
					{{ $t("module.file_extracted") }}
				</a-typography-text>
			</a-col>
		</a-row>
		<a-row class="mt-20 mb-10" v-if="extracting == 'completed'">
			<a-col :span="24">
				<a-button type="primary" @click="closeInstallUpdateModal" block>
					<CheckOutlined /> {{ $t("messages.installation_success") }}
				</a-button>
			</a-col>
		</a-row>
	</a-modal>
</template>

<script>
import { defineComponent, createVNode, ref } from "vue";
import {
	DownloadOutlined,
	ShoppingCartOutlined,
	ReloadOutlined,
	ExclamationCircleOutlined,
	SyncOutlined,
	CheckCircleOutlined,
	InfoOutlined,
	CheckOutlined,
} from "@ant-design/icons-vue";
import { Modal } from "ant-design-vue";
import { useI18n } from "vue-i18n";
import axios from "axios";
import { useStore } from "vuex";

export default defineComponent({
	props: ["productStatus", "product"],
	components: {
		DownloadOutlined,
		ShoppingCartOutlined,
		ReloadOutlined,
		SyncOutlined,
		CheckCircleOutlined,
		InfoOutlined,
		CheckOutlined,
	},
	setup(props, { emit }) {
		const { t } = useI18n();
		const installUpdateModalVisible = ref(false);
		const installUpdateModalTitle = ref("");
		const downloading = ref(false);
		const extracting = ref("");
		const downloadPercentage = ref(0);
		const store = useStore();
		var getDownloadTimer;

		const updateApp = () => {
			Modal.confirm({
				title: t("common.update"),
				icon: createVNode(ExclamationCircleOutlined),
				content: createVNode(
					"div",
					{
						style: "color:red;",
					},
					t("messages.are_you_sure_update_message")
				),
				okText: t("common.yes"),
				okType: "danger",
				cancelText: t("common.no"),
				onOk() {
					installUpdateModalTitle.value = t("common.updating") + "...";

					console.log(props.product, props.product.name);

					install(props.product.name);

					installUpdateModalVisible.value = true;
				},
				onCancel() {},
			});
		};

		const install = (moduleName) => {
			downloading.value = true;
			downloadPercentage.value = 0;
			extracting.value = "";
			const postArray = {
				verified_name: moduleName,
				domain: window.location.host,
			};

			getDownloadTimer = window.setInterval(function () {
				setDownloadPercentage();
			}, 1500);

			axiosAdmin
				.post("update-app/update", postArray)
				.then((response) => {
					downloading.value = false;
					downloadPercentage.value = 100;
					extracting.value = "started";
					const newPostArray = {
						...postArray,
						file_name: response.data.file_name,
					};

					// Extracting Zip File
					axiosAdmin
						.post("update-app/extract", newPostArray)
						.then((extractResponse) => {
							extracting.value = "completed";

							axios.post("https://envato.codeifly.com/version-update", {
								verified_name: extractResponse.data.verified_name,
								version: extractResponse.data.version,
								domain: window.location.host,
							});

							store.commit(
								"auth/updateActiveModules",
								extractResponse.data.enabled_modules
							);

							window.config.modules = extractResponse.data.enabled_modules;
							window.config.installed_modules =
								extractResponse.data.installed_modules;
						})
						.catch((error) => {
							console.log(error);
							extracting.value = "failed";
						});
				})
				.catch((error) => {
					downloading.value = false;
					downloadPercentage.value = 0;
					clearInterval(getDownloadTimer);
				});
		};

		const setDownloadPercentage = () => {
			axiosAdmin("update-app/download-percentage").then((response) => {
				downloadPercentage.value = parseInt(response.data.percentage);

				if (downloadPercentage.value >= 100) {
					clearInterval(getDownloadTimer);
				}
			});
		};

		const closeInstallUpdateModal = () => {
			installUpdateModalVisible.value = false;

			store.dispatch("auth/logoutToRootUrl");
		};

		return {
			updateApp,
			installUpdateModalVisible,
			closeInstallUpdateModal,
			downloadPercentage,
			downloading,
			extracting,
		};
	},
});
</script>
