<template>
	<a-layout>
		<MainHeader :cssSettings="cssSettings">
			<a-layout-header class="main-header">
				<a-row>
					<a-col :span="1">
						<div class="logo">
							<img
								:src="
									appSetting.left_sidebar_theme == 'dark'
										? appSetting.small_dark_logo_url
										: appSetting.small_light_logo_url
								"
							/>
						</div>
					</a-col>
					<a-col :span="20">
						<MainMenus />
					</a-col>
					<a-col :span="3">
						<MenuMode />
					</a-col>
				</a-row>
			</a-layout-header>
		</MainHeader>
		<a-layout-content>
			<MainContentArea :cssSettings="cssSettings">
				<router-view></router-view>
			</MainContentArea>
		</a-layout-content>
	</a-layout>
</template>

<script>
import { ref } from "vue";
import TopBar from "./TopBar.vue";
import LeftSidebarBar from "./LeftSidebar.vue";
import { Div, MainArea, MainContentArea, MainHeader } from "./style";
import common from "../composable/common";
import MainMenus from "./MainMenus.vue";
import MenuMode from "./MenuMode.vue";

export default {
	components: {
		TopBar,
		LeftSidebarBar,
		Div,
		MainArea,
		MainMenus,
		MainContentArea,
		MainHeader,
		MenuMode,
	},
	setup() {
		const { appSetting, cssSettings } = common();
		const collapsed = ref(false);

		const menuClicked = (showHide) => {
			collapsed.value = showHide;
		};

		const menuSelected = () => {
			if (innerWidth <= 991) {
				collapsed.value = true;
			}
		};

		return {
			collapsed,
			menuClicked,
			menuSelected,

			innerWidth: window.innerWidth,
			appSetting,
			cssSettings,
		};
	},
};
</script>

<style lang="less" scoped>
.logo {
	float: left;
	margin-top: -3px;
	margin-right: 24px;
	img {
		height: 32px;
	}
}
</style>
