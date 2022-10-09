<template>
	<a-date-picker
		v-model:value="dateTimeValue"
		:format="formatOrderDate"
		:disabled-date="disabledDate"
		show-time
		:placeholder="$t('common.date_time')"
		style="width: 100%"
		@change="dateTimeChanged"
	/>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import common from "../../../composable/common";

export default defineComponent({
	props: {
		dateTime: {
			default: undefined,
		},
	},
	emits: ["dateTimeChanged"],
	setup(props, { emit }) {
		const dateTimeValue = ref(undefined);
		const { disabledDate, formatDateTime, dayjs } = common();

		onMounted(() => {
			if (props.dateTime) {
				dateTimeValue.value = dayjs(props.dateTime);
			}
		});

		const formatOrderDate = (newValue) => {
			return newValue ? formatDateTime(newValue.format()) : undefined;
		};

		const dateTimeChanged = (newValue) => {
			const emitValue = newValue
				? newValue.utc().format("YYYY-MM-DDTHH:mm:ssZ")
				: undefined;
			emit("dateTimeChanged", emitValue);
		};

		return {
			dateTimeValue,
			disabledDate,
			formatOrderDate,
			dateTimeChanged,
		};
	},
});
</script>

<style></style>
