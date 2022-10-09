<template>
	<a-tabs v-model:activeKey="activeKey">
		<a-tab-pane key="product_order_items" :tab="$t('product.product_orders')">
			<ProductOrderItems :product="product" />
		</a-tab-pane>
		<a-tab-pane key="stock_history" :tab="$t('product.stock_history')">
			<StockHistory :product="product" />
		</a-tab-pane>
	</a-tabs>
</template>

<script>
import { defineComponent, ref } from "vue";
import StockHistory from "./StockHistory.vue";
import ProductOrderItems from "./ProductOrderItems.vue";

export default defineComponent({
	props: ["product"],
	components: {
		StockHistory,
		ProductOrderItems,
	},
	setup() {
		return {
			activeKey: ref("product_order_items"),
		};
	},
});
</script>
