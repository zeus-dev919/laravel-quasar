<template>
	<a-button
		v-if="
			(module.is_free && !module.installed) ||
			(module.verified && !module.installed)
		"
		type="link"
		class="p-0"
		@click="install"
	>
		<template #icon>
			<DownloadOutlined />
		</template>
		{{ $t("common.install") }}
	</a-button>
	<a-button
		v-else-if="
			(module.is_free || module.verified) &&
			module.installed &&
			module.current_version != module.version
		"
		type="link"
		class="p-0"
		@click="update"
	>
		<template #icon>
			<ReloadOutlined />
		</template>
		{{ $t("common.update") }}
	</a-button>
	<a-button
		v-else-if="!module.is_free && !module.verified"
		type="link"
		:href="module.buy_now_url"
		class="p-0"
		target="_blank"
	>
		<template #icon>
			<ShoppingCartOutlined />
		</template>
		{{ $t("common.buy_now") }}
	</a-button>
	<a-typography-text v-else type="success" strong> - </a-typography-text>

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
		<a-row class="mt-10 mb-10" v-if="extracting == 'completed'">
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
import { useRouter } from "vue-router";

export default defineComponent({
	props: ["module", "downloading", "downloadPercentage", "extracting"],
	emits: ["install"],
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
		const router = useRouter();

		const install = () => {
			Modal.confirm({
				title: t("common.install"),
				icon: createVNode(ExclamationCircleOutlined),
				content: t("messages.are_you_sure_install_message"),
				okText: t("common.yes"),
				okType: "danger",
				cancelText: t("common.no"),
				onOk() {
					installUpdateModalTitle.value = t("common.installing") + "...";

					emit("install", props.module.verified_name);

					installUpdateModalVisible.value = true;
				},
				onCancel() {},
			});
		};

		const update = () => {
			Modal.confirm({
				title: t("common.update"),
				icon: createVNode(ExclamationCircleOutlined),
				content: t("messages.are_you_sure_update_message"),
				okText: t("common.yes"),
				okType: "danger",
				cancelText: t("common.no"),
				onOk() {
					installUpdateModalTitle.value = t("common.updating") + "...";

					emit("install", props.module.verified_name);

					installUpdateModalVisible.value = true;
				},
				onCancel() {},
			});
		};

		const closeInstallUpdateModal = () => {
			installUpdateModalVisible.value = false;

			// router.go();
			window.location.reload();
		};

		return {
			install,
			update,
			installUpdateModalVisible,
			closeInstallUpdateModal,
		};
	},
});
</script>
