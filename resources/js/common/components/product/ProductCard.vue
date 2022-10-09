<template>
	<ProductCard class="mb-30">
		<figure>
			<img :src="product.image_url" :alt="product.name" />
		</figure>
		<figcaption>
			<span class="quantity-box" to="#">
				{{ product.stock_quantity }} {{ product.unit.short_name }}
			</span>
			<a-typography-title class="product-single-title" :level="5">
				{{ product.name }}
			</a-typography-title>
			<p class="product-single-price">
				<span class="product-single-price__new">
					{{ formatAmountCurrency(product.subtotal) }}
				</span>

				<template v-if="product.discount_rate">
					<del class="product-single-price__old">
						{{ formatAmountCurrency(product.unit_price) }}
					</del>
					<span class="product-single-price__offer">
						{{ product.discount_rate }}% {{ $t("common.off") }}
					</span>
				</template>
			</p>
		</figcaption>
	</ProductCard>
</template>
<script>
import { ProductCard } from "./style";
import common from "../../composable/common";

export default {
	props: ["product"],
	components: { ProductCard },
	setup(props) {
		const { formatAmountCurrency } = common();

		return {
			formatAmountCurrency,
		};
	},
};
</script>
