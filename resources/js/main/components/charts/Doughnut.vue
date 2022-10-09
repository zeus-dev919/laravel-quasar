<template>
	<DoughnutChart ref="chartRef" :chartData="testData" :options="options" />
</template>

<script>
import { ref, watch } from "vue";
import { DoughnutChart } from "vue-chart-3";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

export default {
	props: ["data", "title"],
	components: {
		DoughnutChart,
	},
	setup(props) {
		const chartRef = ref();

		const options = ref({
			responsive: true,
			plugins: {
				legend: {
					position: "bottom",
				},
				title: {
					display: props.title != "" ? true : false,
					text: props.title,
				},
			},
		});

		const testData = ref({
			labels: props.data.labels,
			datasets: [
				{
					data: props.data.values,
					backgroundColor: props.data.colors,
				},
			],
		});

		watch(props, (newVal, oldVal) => {
			testData.value = {
				labels: newVal.data.labels,
				datasets: [
					{
						data: newVal.data.values,
						backgroundColor: newVal.data.colors,
					},
				],
			};
		});

		return {
			chartRef,
			testData,
			options,
		};
	},
};
</script>

<style></style>
