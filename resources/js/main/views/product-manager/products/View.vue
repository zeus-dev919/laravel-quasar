<template>
	<a-drawer
		:title="product.name"
		:width="drawerWidth"
		:visible="visible"
		:body-style="{ paddingBottom: '80px' }"
		:maskClosable="false"
		@close="onClose"
	>
		<div class="product-details">
			<a-row :gutter="[16, 16]">
				<a-col :xs="24" :sm="24" :md="4" :lg="4">
					<a-image :src="product.image_url" />
				</a-col>
				<a-col :xs="24" :sm="24" :md="20" :lg="20">
					<a-row
						class="mb-10 mt-10"
						v-if="product.details && product.details.stock_quantitiy_alert"
					>
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-alert type="error">
								<template #message>
									<span
										v-html="
											$t('messages.product_out_of_stock', [
												product.details.current_stock,
												product.details.stock_quantitiy_alert,
											])
										"
									></span>
								</template>
							</a-alert>
						</a-col>
					</a-row>

					<a-row class="mb-10 mt-10">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-descriptions
								:title="$t(`product.details`)"
								layout="vertical"
								:contentStyle="{ fontWeight: 500, marginBottom: '20px' }"
							>
								<a-descriptions-item :label="$t('product.name')">
									{{ product.name }}
								</a-descriptions-item>
								<a-descriptions-item :label="$t('product.item_code')">
									{{ product.item_code }}
								</a-descriptions-item>
								<a-descriptions-item :label="$t('product.category')">
									{{
										product.category && product.category.xid
											? product.category.name
											: "-"
									}}
								</a-descriptions-item>
								<a-descriptions-item :label="$t('product.brand')">
									{{
										product.brand && product.brand.xid
											? product.brand.name
											: "-"
									}}
								</a-descriptions-item>
								<a-descriptions-item :label="$t('product.current_stock')">
									{{
										product.details && product.details.current_stock
											? `${product.details.current_stock} ${product.unit.short_name}`
											: "-"
									}}
								</a-descriptions-item>
								<a-descriptions-item
									:label="$t('product.quantitiy_alert')"
								>
									{{
										product.details &&
										product.details.stock_quantitiy_alert
											? `${product.details.stock_quantitiy_alert} ${product.unit.short_name}`
											: "-"
									}}
								</a-descriptions-item>
								<a-descriptions-item :label="$t('product.sales_price')">
									{{
										product.details && product.details.sales_price
											? formatAmountCurrency(
													product.details.sales_price
											  )
											: "-"
									}}
									{{
										product.details &&
										product.details.sales_tax_type == "inclusive"
											? ` (${$t("common.with_tax")})`
											: ` (${$t("common.without_tax")})`
									}}
								</a-descriptions-item>
								<a-descriptions-item
									:label="$t('product.purchase_price')"
								>
									{{
										product.details && product.details.purchase_price
											? formatAmountCurrency(
													product.details.purchase_price
											  )
											: "-"
									}}
									{{
										product.details &&
										product.details.purchase_tax_type == "inclusive"
											? ` (${$t("common.with_tax")})`
											: ` (${$t("common.without_tax")})`
									}}
								</a-descriptions-item>
								<a-descriptions-item :label="$t('product.mrp')">
									{{
										product.details && product.details.mrp
											? formatAmountCurrency(product.details.mrp)
											: "-"
									}}
								</a-descriptions-item>
								<a-descriptions-item :label="$t('tax.rate')">
									{{
										product.details &&
										product.details.tax &&
										product.details.tax.rate
											? `${product.details.tax.rate}%`
											: "-"
									}}
								</a-descriptions-item>
								<a-descriptions-item :label="$t('product.opening_stock')">
									{{
										product.details && product.details.opening_stock
											? `${product.details.opening_stock} ${product.unit.short_name}`
											: "-"
									}}
								</a-descriptions-item>
							</a-descriptions>
						</a-col>
					</a-row>
				</a-col>
			</a-row>
		</div>
		<ProductDetails :product="product" />
	</a-drawer>
</template>
<script>
import { defineComponent, ref, watch, computed } from "vue";
import { useI18n } from "vue-i18n";
import common from "../../../../common/composable/common";
import ProductDetails from "./details/Details.vue";

export default defineComponent({
	props: ["product", "visible"],
	emits: ["closed"],
	components: {
		ProductDetails,
	},
	setup(props, { emit }) {
		const { formatAmountCurrency } = common();
		const { t } = useI18n();

		const onClose = () => {
			emit("closed");
		};

		return {
			formatAmountCurrency,
			onClose,
			drawerWidth: window.innerWidth <= 991 ? "90%" : "60%",
		};
	},
});
</script>

<style lang="less">
.product-details {
	.ant-descriptions-item {
		padding-bottom: 5px;
	}
}
</style>
