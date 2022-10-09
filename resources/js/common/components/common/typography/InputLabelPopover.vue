<template>
	<span>
		{{ label }}
		<a-popover trigger="hover">
			<template #title>
				{{ label }}
			</template>
			<template #content>
				<div v-html="escapeSctipyTab(content)"></div>
			</template>
			<InfoCircleOutlined />
		</a-popover>
	</span>
</template>

<script>
import { InfoCircleOutlined } from "@ant-design/icons-vue";
export default {
	props: ["label", "content"],
	components: {
		InfoCircleOutlined,
	},
	setup() {
		const escapeSctipyTab = (text) => {
			return text.replace(/<script.*?>.*?<\/script>/gi, "");
		};

		return {
			escapeSctipyTab,
		};
	},
};
</script>

<style></style>
