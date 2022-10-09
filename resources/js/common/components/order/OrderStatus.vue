<template>
	<a-typography-text v-if="data.cancelled" type="danger">
		{{ $t("common.cancelled") }}
	</a-typography-text>
	<a-typography-text v-else>
		{{ $t(`common.${data.order_status}`) }}
	</a-typography-text>
</template>

<script>
export default {
	props: ["data"],
};
</script>
