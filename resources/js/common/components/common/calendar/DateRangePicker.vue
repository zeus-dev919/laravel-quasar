<template>
	<a-range-picker
		v-model:value="dateRangeValue"
		:format="appSetting.date_format"
		:placeholder="[$t('common.start_date'), $t('common.end_date')]"
		style="width: 100%"
		@change="dateTimeChanged"
	/>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import common from "../../../composable/common";

export default defineComponent({
	props: {
		dateRange: {
			default: [],
		},
	},
	emits: ["dateTimeChanged"],
	setup(props, { emit }) {
		const dateRangeValue = ref([]);
		const { appSetting, dayjs } = common();

		onMounted(() => {
			if (props.dateRange && props.dateRange.length > 0) {
				dateRangeValue.value = [
					dayjs(props.dateRange[0]),
					dayjs(props.dateRange[1]),
				];
			}
		});

		const dateTimeChanged = (newValue) => {
			if (newValue) {
				emit("dateTimeChanged", [
					newValue[0].startOf("day").utc().format("YYYY-MM-DD HH:mm:ss"),
					newValue[1].endOf("day").utc().format("YYYY-MM-DD HH:mm:ss"),
				]);
			} else {
				emit("dateTimeChanged", []);
			}
		};

		const resetPicker = () => {
			dateRangeValue.value = [];
		};

		return {
			appSetting,
			dateRangeValue,
			dateTimeChanged,
			resetPicker,
		};
	},
});
</script>

<style></style>
