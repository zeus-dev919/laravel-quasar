<template>
	<BarChart ref="chartRef" :chartData="testData" :options="options" />
</template>

<script>
import { ref, watch } from "vue";
import { BarChart } from "vue-chart-3";
import { Chart, registerables } from "chart.js";
import { useI18n } from "vue-i18n";

Chart.register(...registerables);

export default {
	props: ["data"],
	components: {
		BarChart,
	},
	setup(props) {
		const chartRef = ref();
		const { t } = useI18n();

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
				labels: newVal.data.purchaseSales.dates
					? newVal.data.purchaseSales.dates
					: [],
				datasets: [
					{
						label: t("menu.purchases"),
						data: newVal.data.purchaseSales.purchases
							? newVal.data.purchaseSales.purchases
							: [],
						backgroundColor: "#20C997",
					},
					{
						label: t("menu.sales"),
						data: newVal.data.purchaseSales.sales
							? newVal.data.purchaseSales.sales
							: [],
						backgroundColor: "#FFCD56",
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
