<template>
	<keep-alive>
		<component
			v-if="cssSettings.headerMenuMode == 'horizontal'"
			key="horizontal"
			v-bind:is="'HorizantalStyle'"
		/>
		<component v-else key="vertical" v-bind:is="'VerticalStyle'" />
	</keep-alive>
</template>

<script>
import common from "../../common/composable/common";
import HorizantalStyle from "./HorizantalStyle.vue";
import VerticalStyle from "./VerticalStyle.vue";
import LoadingApp from "../../main/views/LoadingApp.vue";

export default {
	components: {
		VerticalStyle,
		HorizantalStyle,
		LoadingApp,
	},
	setup() {
		const { cssSettings } = common();

		return {
			cssSettings,
		};
	},
};
</script>
