<template>
	<router-view v-slot="{ Component, route }">
		<suspense>
			<template #default>
				<div class="theme-container">
					<ThemeProvider :theme="{ ...theme }">
						<LoadingApp v-if="appChecking" />
						<component v-else :is="Component" :key="route.name" />
					</ThemeProvider>
				</div>
			</template>
			<template #fallback> Loading... </template>
		</suspense>
	</router-view>
</template>

<script>
import { watch, onMounted, computed } from "vue";
import { ThemeProvider } from "vue3-styled-components";
import { theme } from "../config/theme/themeVariables";
import { changeAntdTheme } from "mini-dynamic-antd-theme";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import common from "../../common/composable/common";
import LoadingApp from "./LoadingApp.vue";

export default {
	name: "App",
	components: {
		ThemeProvider,
		LoadingApp,
	},
	setup() {
		const route = useRoute();
		const store = useStore();
		const darkTheme = "dark";
		const { updatePageTitle, appSetting } = common();
		changeAntdTheme(appSetting.value.primary_color);
		const appChecking = computed(() => store.state.auth.appChecking);

		onMounted(() => {
			setInterval(() => {
				store.dispatch("auth/refreshToken");
			}, 5 * 60 * 1000);
		});

		watch(route, (newVal, oldVal) => {
			const menuKey =
				typeof newVal.meta.menuKey == "function"
					? newVal.meta.menuKey(newVal)
					: newVal.meta.menuKey;

			updatePageTitle(menuKey.replace("-", "_"));
		});

		return {
			theme,
			darkTheme,
			appChecking,
		};
	},
};
</script>

<style>
body {
	background: #f0f2f5 !important;
}
</style>
