<template>
	<a-tooltip>
		<template #title>
			{{ $t("mail_settings.notificaiton_will_be_sent_to_warehouse") }}
		</template>
		<InfoCircleOutlined />
	</a-tooltip>
</template>
<script>
import { onMounted, ref } from "vue";
import { InfoCircleOutlined } from "@ant-design/icons-vue";

export default {
	components: {
		InfoCircleOutlined,
	},
};
</script>
