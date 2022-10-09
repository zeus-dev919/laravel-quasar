<template>
	<div class="loading-app-container">
		<a-result>
			<template #title>
				<span :style="{ color: '#7676e3' }">
					{{ $t("messages.loading_app_message") }}
				</span>
			</template>
			<template #icon>
				<img style="width: 150px" :src="appSetting.light_logo_url" />
			</template>
			<template #extra>
				<SyncOutlined :style="{ fontSize: '38px', color: '#5254cf' }" spin />
			</template>
		</a-result>
	</div>
</template>

<script>
import { defineComponent } from "vue";
import { SyncOutlined } from "@ant-design/icons-vue";
import common from "../../common/composable/common";

export default defineComponent({
	components: {
		SyncOutlined,
	},
	setup() {
		const { appSetting } = common();

		return {
			appSetting,
		};
	},
});
</script>

<style lang="less" scoped>
.loading-app-container {
	height: 100%;
	width: 100%;
	display: flex;
	position: fixed;
	align-items: center;
	justify-content: center;
	background: #f8f8ff;
}
</style>
