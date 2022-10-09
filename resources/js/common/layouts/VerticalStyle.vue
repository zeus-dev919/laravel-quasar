<template>
	<Div>
		<section id="components-layout-demo-responsive">
			<a-layout>
				<LeftSidebarBar :collapsed="collapsed" @menuSelected="menuSelected" />

				<a-layout>
					<MainArea :innerWidth="innerWidth" :collapsed="collapsed">
						<TopBar
							:collapsed="collapsed"
							@onSidebarMenuClick="menuClicked"
						/>
						<MainContentArea>
							<a-layout-content>
								<router-view></router-view>
							</a-layout-content>
						</MainContentArea>
					</MainArea>
				</a-layout>
			</a-layout>
		</section>
	</Div>
</template>

<script>
import { ref } from "vue";
import TopBar from "./TopBar.vue";
import LeftSidebarBar from "./LeftSidebar.vue";
import { Div, MainArea, MainContentArea } from "./style";

export default {
	components: {
		TopBar,
		LeftSidebarBar,
		Div,
		MainArea,
		MainContentArea,
	},
	setup() {
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
		};
	},
};
</script>

<style>
#components-layout-demo-responsive .logo {
	height: 32px;
	margin: 16px;
	text-align: center;
}

.site-layout-sub-header-background {
	background: #fff;
}

.site-layout-background {
	background: #fff;
}

[data-theme="dark"] .site-layout-sub-header-background {
	background: #141414;
}
</style>
