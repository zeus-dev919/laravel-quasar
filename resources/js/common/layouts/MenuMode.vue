<template>
	<a-switch
		checkedValue="horizontal"
		unCheckedValue="vertical"
		:checked="menuMode"
		@change="menuModeChanged"
		size="small"
	/>
</template>

<script>
import { ref, defineComponent } from "vue";
import { useStore } from "vuex";
import common from "../../common/composable/common";

export default defineComponent({
	setup() {
		const { cssSettings } = common();
		const store = useStore();
		const menuMode = ref(cssSettings.value.headerMenuMode);

		const menuModeChanged = (mode) => {
			console.log(mode);
			store.commit("auth/updateCssSettings", {
				...cssSettings.value,
				headerMenuMode: mode,
			});
		};

		return {
			menuMode,
			menuModeChanged,
		};
	},
});
</script>

<style></style>
