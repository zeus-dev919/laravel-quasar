<template>
	<span>
		<a-tag v-if="paymentStatus == 'paid'" color="success">{{
			$t(`payments.${paymentStatus}`)
		}}</a-tag>
		<a-tag v-else-if="paymentStatus == 'partially_paid'" color="warning">{{
			$t(`payments.${paymentStatus}`)
		}}</a-tag>
		<a-tag v-else color="error">{{ $t(`payments.${paymentStatus}`) }}</a-tag>
	</span>
</template>

<script>
export default {
	props: ["paymentStatus"],
};
</script>
