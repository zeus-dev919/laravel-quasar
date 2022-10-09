<template>
	<Details
		:visible="detailsVisible"
		@closed="detailsVisible = false"
		:itemDetails="viewData"
	/>

	<a-timeline>
		<a-timeline-item v-for="stockHistory in data" :key="stockHistory.id">
			<template #dot>
				<CloudDownloadOutlined
					v-if="stockHistory.stock_type == 'out'"
					style="font-size: 20px"
				/>
				<CloudUploadOutlined v-else style="font-size: 20px" />
			</template>
			<span>
				<a-typography-link @click="viewItem(stockHistory.product)">
					{{ stockHistory.product.name }}
				</a-typography-link>
				with
				<strong
					>{{ stockHistory.quantity }}
					{{ stockHistory.product.unit.short_name }}
				</strong>
				{{ stockHistory.order_type }} on
				{{ formatDateTime(stockHistory.created_at) }}
			</span>
		</a-timeline-item>
	</a-timeline>
</template>

<script>
import { ref } from "vue";
import { CloudDownloadOutlined, CloudUploadOutlined } from "@ant-design/icons-vue";
import Details from "../../views/product-manager/products/Details.vue";
import common from "../../../common/composable/common";

export default {
	props: ["data"],
	components: {
		CloudDownloadOutlined,
		CloudUploadOutlined,
		Details,
	},
	setup(props) {
		const { formatDateTime } = common();
		const detailsVisible = ref(false);
		const viewData = ref({});

		const viewItem = (item) => {
			viewData.value = item;
			detailsVisible.value = true;
		};

		return {
			formatDateTime,

			viewData,
			detailsVisible,
			viewItem,
		};
	},
};
</script>

<style></style>
