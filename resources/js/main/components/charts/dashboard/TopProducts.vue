<template>
	<DoughnutChart ref="chartRef" :chartData="testData" :options="options" />
</template>

<script>
import { ref, watch } from "vue";
import { DoughnutChart } from "vue-chart-3";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

export default {
	props: ["data"],
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
					display: false,
					text: "Chart.js Doughnut Chart",
				},
			},
		});

		const testData = ref({});

		watch(props, (newVal, oldVal) => {
			testData.value = {
				labels: newVal.data.topSellingProducts
					? newVal.data.topSellingProducts.labels
					: [],
				datasets: [
					{
						data: newVal.data.topSellingProducts
							? newVal.data.topSellingProducts.values
							: [],
						backgroundColor: newVal.data.topSellingProducts
							? newVal.data.topSellingProducts.colors
							: [],
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
