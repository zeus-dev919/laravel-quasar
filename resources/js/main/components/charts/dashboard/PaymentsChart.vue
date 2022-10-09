<template>
	<LineChart ref="chartRef" :chartData="testData" :options="options" />
</template>

<script>
import { ref, watch } from "vue";
import { LineChart } from "vue-chart-3";
import { Chart, registerables } from "chart.js";
import { useI18n } from "vue-i18n";

Chart.register(...registerables);

export default {
	props: ["data"],
	components: {
		LineChart,
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
					text: "",
				},
			},
		});

		const testData = ref({});

		watch(props, (newVal, oldVal) => {
			testData.value = {
				labels: newVal.data.paymentChartData
					? newVal.data.paymentChartData.dates
					: [],
				datasets: [
					{
						label: t("dashboard.payment_sent"),
						data: newVal.data.paymentChartData.sent
							? newVal.data.paymentChartData.sent
							: [],
						borderColor: "#20C997",
						backgroundColor: "#20C997",
					},
					{
						label: t("dashboard.payment_received"),
						data: newVal.data.paymentChartData.received
							? newVal.data.paymentChartData.received
							: [],
						borderColor: "#FFCD56",
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
