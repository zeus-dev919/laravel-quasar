<template>
	<a-card
		class="page-content-sub-header breadcrumb-left-border"
		:bodyStyle="{ padding: '0px', margin: '0px 16px 0' }"
	>
		<a-row>
			<a-col :span="24">
				<a-page-header
					:title="$t('menu.pos')"
					@back="() => $router.go(-1)"
					class="p-0"
				/>
			</a-col>
			<a-col :span="24">
				<a-breadcrumb separator="-" style="font-size: 12px">
					<a-breadcrumb-item>
						<router-link :to="{ name: 'admin.dashboard.index' }">
							{{ $t(`menu.dashboard`) }}
						</router-link>
					</a-breadcrumb-item>
					<a-breadcrumb-item>
						{{ $t(`menu.stock_management`) }}
					</a-breadcrumb-item>
					<a-breadcrumb-item>
						{{ $t("menu.pos") }}
					</a-breadcrumb-item>
				</a-breadcrumb>
			</a-col>
		</a-row>
	</a-card>

	<a-form layout="vertical">
		<a-row :gutter="[8, 8]" class="mt-5 mb-30" style="margin: 10px 16px 0">
			<a-col :xs="24" :sm="24" :md="24" :lg="10" :xl="10">
				<a-card :style="containerStyle">
					<div class="bill-body">
						<div class="bill-filters">
							<a-row :gutter="16">
								<a-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
									<span style="display: flex">
										<a-select
											v-model:value="formData.user_id"
											:placeholder="$t('user.walk_in_customer')"
											:allowClear="true"
											style="width: 100%"
											optionFilterProp="title"
											show-search
										>
											<a-select-option
												v-for="customer in customers"
												:key="customer.xid"
												:title="customer.name"
												:value="customer.xid"
											>
												{{ customer.name }}
											</a-select-option>
										</a-select>
										<CustomerAddButton
											@onAddSuccess="customerAdded"
										/>
									</span>
								</a-col>
							</a-row>
							<a-row class="mt-20 mb-30">
								<a-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24">
									<a-select
										v-model:value="orderSearchTerm"
										show-search
										:filter-option="false"
										:placeholder="
											$t('common.select_default_text', [
												$t('product.product'),
											])
										"
										style="width: 100%"
										:not-found-content="
											productFetching ? undefined : null
										"
										@search="fetchProducts"
										option-label-prop="label"
										@focus="products = []"
										@select="searchValueSelected"
									>
										<template #suffixIcon>
											<SearchOutlined />
										</template>
										<template v-if="productFetching" #notFoundContent>
											<a-spin size="small" />
										</template>
										<a-select-option
											v-for="product in products"
											:key="product.id"
											:value="product.id"
											:label="product.name"
											:product="product"
										>
											=> {{ product.name }}
										</a-select-option>
									</a-select>
								</a-col>
							</a-row>
						</div>
						<div class="bill-table">
							<a-row class="mt-20 mb-30">
								<a-col :xs="24" :sm="24" :md="24" :lg="24">
									<a-table
										:row-key="(record) => record.xid"
										:dataSource="selectedProducts"
										:columns="orderItemColumns"
										:pagination="false"
									>
										<template #bodyCell="{ column, record }">
											<template v-if="column.dataIndex === 'name'">
												{{ record.name }} <br />
												<small>
													<a-typography-text code>
														{{ $t("product.avl_qty") }}
														{{
															`${record.stock_quantity}${record.unit_short_name}`
														}}
													</a-typography-text>
												</small>
											</template>
											<template
												v-if="
													column.dataIndex === 'unit_quantity'
												"
											>
												<a-input-number
													id="inputNumber"
													v-model:value="record.quantity"
													:min="0"
													@change="quantityChanged(record)"
												/>
											</template>
											<template
												v-if="column.dataIndex === 'subtotal'"
											>
												{{
													formatAmountCurrency(record.subtotal)
												}}
											</template>
											<template
												v-if="column.dataIndex === 'action'"
											>
												<a-button
													type="primary"
													@click="editItem(record)"
													style="
														margin-left: 4px;
														margin-top: 4px;
													"
												>
													<template #icon
														><EditOutlined
													/></template>
												</a-button>
												<a-button
													type="primary"
													@click="showDeleteConfirm(record)"
													style="
														margin-left: 4px;
														margin-top: 4px;
													"
												>
													<template #icon
														><DeleteOutlined
													/></template>
												</a-button>
											</template>
										</template>
									</a-table>
								</a-col>
							</a-row>
						</div>
					</div>
					<div class="bill-footer">
						<a-row :gutter="[16, 16]">
							<a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
								<a-form-item :label="$t('stock.order_tax')">
									<a-select
										v-model:value="formData.tax_id"
										:placeholder="
											$t('common.select_default_text', [
												$t('stock.order_tax'),
											])
										"
										:allowClear="true"
										style="width: 100%"
										@change="taxChanged"
									>
										<a-select-option
											v-for="tax in taxes"
											:key="tax.xid"
											:value="tax.xid"
											:tax="tax"
										>
											{{ tax.name }} ({{ tax.rate }}%)
										</a-select-option>
									</a-select>
								</a-form-item>
							</a-col>
							<a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
								<a-form-item :label="$t('stock.discount')">
									<a-input-number
										v-model:value="formData.discount"
										:placeholder="
											$t('common.placeholder_default_text', [
												$t('stock.discount'),
											])
										"
										@change="recalculateFinalTotal"
										min="0"
										style="width: 100%"
									>
										<template #addonBefore>
											{{ appSetting.currency.symbol }}
										</template>
									</a-input-number>
								</a-form-item>
							</a-col>
							<a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
								<a-form-item :label="$t('stock.shipping')">
									<a-input-number
										v-model:value="formData.shipping"
										:placeholder="
											$t('common.placeholder_default_text', [
												$t('stock.shipping'),
											])
										"
										@change="recalculateFinalTotal"
										min="0"
										style="width: 100%"
									>
										<template #addonBefore>
											{{ appSetting.currency.symbol }}
										</template>
									</a-input-number>
								</a-form-item>
							</a-col>
						</a-row>
						<a-row :gutter="16">
							<a-col :xxl="16" :xl="16" :sm="24" :xs="24">
								<a-button
									class="mt-30"
									type="primary"
									@click="payNow"
									:disabled="
										formData.subtotal <= 0 ||
										formData.user_id == undefined ||
										formData.user_id == '' ||
										!formData.user_id
									"
									block
								>
									{{ $t("stock.pay_now") }}
								</a-button>
								<a-button class="mt-10" @click="resetPos" block>
									{{ $t("stock.reset") }}
								</a-button>
							</a-col>
							<a-col :xxl="8" :xl="8" :sm="24" :xs="24">
								<OrderSummary>
									<div class="invoice-summary-inner">
										<ul class="summary-list">
											<li>
												<span class="summary-list-title">
													{{ $t("product.tax") }} :
												</span>
												<span class="summary-list-text">
													{{
														formatAmountCurrency(
															formData.tax_amount
														)
													}}
												</span>
											</li>
											<li>
												<span class="summary-list-title">
													{{ $t("stock.discount") }} :
												</span>
												<span class="summary-list-text">
													{{
														formatAmountCurrency(
															formData.discount
														)
													}}
												</span>
											</li>
											<li>
												<span class="summary-list-title">
													{{ $t("stock.shipping") }} :
												</span>
												<span class="summary-list-text">
													{{
														formatAmountCurrency(
															formData.shipping
														)
													}}
												</span>
											</li>
										</ul>
										<a-typography-title
											:level="4"
											class="summary-total"
										>
											<span class="summary-total-label">
												{{ $t("stock.grand_total") }} :
											</span>
											<span class="summary-total-amount">
												{{
													formatAmountCurrency(
														formData.subtotal
													)
												}}
											</span>
										</a-typography-title>
									</div>
								</OrderSummary>
							</a-col>
						</a-row>
					</div>
				</a-card>
			</a-col>
			<a-col :xs="24" :sm="24" :md="24" :lg="14" :xl="14">
				<a-card :style="containerStyle">
					<a-row :gutter="[16, 16]">
						<a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
							<a-select
								v-model:value="formData.brand_id"
								:placeholder="
									$t('common.select_default_text', [
										$t('product.brand'),
									])
								"
								:allowClear="true"
								style="width: 100%"
								optionFilterProp="title"
								show-search
								@change="reFetchProducts"
							>
								<a-select-option
									v-for="brand in brands"
									:key="brand.xid"
									:title="brand.name"
									:value="brand.xid"
								>
									{{ brand.name }}
								</a-select-option>
							</a-select>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
							<a-input-group compact>
								<a-select
									v-model:value="formData.category_id"
									:placeholder="
										$t('common.select_default_text', [
											$t('product.category'),
										])
									"
									:allowClear="true"
									style="width: 100%"
									optionFilterProp="title"
									show-search
									@change="reFetchProducts"
								>
									<a-select-option
										v-for="category in categories"
										:key="category.xid"
										:title="category.name"
										:value="category.xid"
									>
										{{ category.name }}
									</a-select-option>
								</a-select>
							</a-input-group>
						</a-col>
					</a-row>
					<div class="mt-20">
						<a-row :gutter="30">
							<a-col
								v-for="item in productLists"
								:key="item.xid"
								:xxl="6"
								:lg="12"
								:md="12"
								:xs="24"
								@click="selectSaleProduct(item)"
							>
								<ProductCard :product="item" />
							</a-col>
						</a-row>
					</div>
				</a-card>
			</a-col>
		</a-row>
	</a-form>

	<a-modal
		:visible="addEditVisible"
		:closable="false"
		:centered="true"
		:title="addEditPageTitle"
		@ok="onAddEditSubmit"
	>
		<a-form layout="vertical">
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('product.unit_price')"
						name="unit_price"
						:help="
							addEditRules.unit_price
								? addEditRules.unit_price.message
								: null
						"
						:validateStatus="addEditRules.unit_price ? 'error' : null"
					>
						<a-input-number
							v-model:value="addEditFormData.unit_price"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('product.unit_price'),
								])
							"
							min="0"
							style="width: 100%"
						>
							<template #addonBefore>
								{{ appSetting.currency.symbol }}
							</template>
						</a-input-number>
					</a-form-item>
				</a-col>
			</a-row>
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('product.discount')"
						name="discount_rate"
						:help="
							addEditRules.discount_rate
								? addEditRules.discount_rate.message
								: null
						"
						:validateStatus="addEditRules.discount_rate ? 'error' : null"
					>
						<a-input-number
							v-model:value="addEditFormData.discount_rate"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('product.discount'),
								])
							"
							min="0"
							style="width: 100%"
						>
							<template #addonAfter>%</template>
						</a-input-number>
					</a-form-item>
				</a-col>
			</a-row>
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('product.tax')"
						name="tax_id"
						:help="addEditRules.tax_id ? addEditRules.tax_id.message : null"
						:validateStatus="addEditRules.tax_id ? 'error' : null"
					>
						<a-select
							v-model:value="addEditFormData.tax_id"
							:placeholder="
								$t('common.select_default_text', [$t('product.tax')])
							"
							:allowClear="true"
						>
							<a-select-option
								v-for="tax in taxes"
								:key="tax.xid"
								:value="tax.xid"
							>
								{{ tax.name }} ({{ tax.rate }}%)
							</a-select-option>
						</a-select>
					</a-form-item>
				</a-col>
			</a-row>
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('product.tax_type')"
						name="tax_type"
						:help="
							addEditRules.tax_type ? addEditRules.tax_type.message : null
						"
						:validateStatus="addEditRules.tax_type ? 'error' : null"
					>
						<a-select
							v-model:value="addEditFormData.tax_type"
							:placeholder="
								$t('common.select_default_text', [$t('product.tax_type')])
							"
							:allowClear="true"
						>
							<a-select-option
								v-for="taxType in taxTypes"
								:key="taxType.key"
								:value="taxType.key"
							>
								{{ taxType.value }}
							</a-select-option>
						</a-select>
					</a-form-item>
				</a-col>
			</a-row>
		</a-form>
		<template #footer>
			<a-button
				key="submit"
				type="primary"
				:loading="addEditFormSubmitting"
				@click="onAddEditSubmit"
			>
				<template #icon>
					<SaveOutlined />
				</template>
				{{ $t("common.update") }}
			</a-button>
			<a-button key="back" @click="onAddEditClose">
				{{ $t("common.cancel") }}
			</a-button>
		</template>
	</a-modal>

	<PayNow
		:visible="payNowVisible"
		@closed="payNowClosed"
		@success="payNowSuccess"
		:data="formData"
		:selectedProducts="selectedProducts"
	/>

	<InvoiceModal
		:visible="printInvoiceModalVisible"
		:order="printInvoiceOrder"
		@closed="printInvoiceModalVisible = false"
	/>
</template>

<script>
import { ref, onMounted, reactive, toRefs } from "vue";
import {
	PlusOutlined,
	EditOutlined,
	DeleteOutlined,
	SearchOutlined,
	SaveOutlined,
} from "@ant-design/icons-vue";
import { debounce } from "lodash-es";
import { useI18n } from "vue-i18n";
import common from "../../../../common/composable/common";
import { OrderSummary } from "../../../../common/components/product/style";
import fields from "./fields";
import ProductCard from "../../../../common/components/product/ProductCard.vue";
import PayNow from "./PayNow.vue";
import CustomerAddButton from "../../users/CustomerAddButton.vue";
import InvoiceModal from "./Invoice.vue";

export default {
	components: {
		PlusOutlined,
		SearchOutlined,
		EditOutlined,
		DeleteOutlined,
		SaveOutlined,

		ProductCard,
		OrderSummary,
		PayNow,
		CustomerAddButton,
		InvoiceModal,
	},
	setup() {
		const {
			taxes,
			customers,
			brands,
			categories,
			productLists,
			orderItemColumns,
			formData,
			customerUrl,
			getPreFetchData,
		} = fields();

		const selectedProducts = ref([]);
		const selectedProductIds = ref([]);
		const removedOrderItemsIds = ref([]);

		const state = reactive({
			orderSearchTerm: [],
			productFetching: false,
			products: [],
		});
		const { formatAmount, formatAmountCurrency, appSetting, taxTypes } = common();
		const { t } = useI18n();

		// AddEdit
		const addEditVisible = ref(false);
		const addEditFormSubmitting = ref(false);
		const addEditFormData = ref({});
		const addEditRules = ref([]);
		const addEditPageTitle = ref("");

		// Pay Now
		const payNowVisible = ref(false);
		const printInvoiceModalVisible = ref(false);
		const printInvoiceOrder = ref({});

		onMounted(() => {
			getPreFetchData();
		});

		const reFetchProducts = () => {
			axiosAdmin
				.post("pos/products", {
					brand_id: formData.value.brand_id,
					category_id: formData.value.category_id,
				})
				.then((productResponse) => {
					productLists.value = productResponse.data.products;
				});
		};

		const fetchProducts = debounce((value) => {
			state.products = [];

			if (value != "") {
				state.productFetching = true;
				let url = `search-product`;

				axiosAdmin
					.post(url, {
						order_type: "sales",
						search_term: value,
						products: selectedProductIds.value,
					})
					.then((response) => {
						state.products = response.data;
						state.productFetching = false;
					});
			}
		}, 300);

		const searchValueSelected = (value, option) => {
			const newProduct = option.product;
			selectSaleProduct(newProduct);
		};

		const selectSaleProduct = (newProduct) => {
			selectedProductIds.value.push(newProduct.xid);

			selectedProducts.value.push({
				...newProduct,
				sn: selectedProducts.value.length + 1,
				unit_price: formatAmount(newProduct.unit_price),
				tax_amount: formatAmount(newProduct.tax_amount),
				subtotal: formatAmount(newProduct.subtotal),
			});
			state.orderSearchTerm = [];
			state.products = [];
			recalculateFinalTotal();

			var audioObj = new Audio(appSetting.value.beep_audio_url);
			audioObj.play();
		};

		const recalculateValues = (product) => {
			var quantityValue = parseFloat(product.quantity);
			var maxQuantity = parseFloat(product.stock_quantity);
			const unitPrice = parseFloat(product.unit_price);

			// Check if entered quantity value is greater
			quantityValue = quantityValue > maxQuantity ? maxQuantity : quantityValue;

			// Discount Amount
			const discountRate = product.discount_rate;
			const totalDiscount = discountRate > 0 ? (discountRate / 100) * unitPrice : 0;
			const totalPriceAfterDiscount = unitPrice - totalDiscount;

			var taxAmount = 0;
			var subtotal = totalPriceAfterDiscount;
			var singleUnitPrice = unitPrice;

			// Tax Amount
			if (product.tax_rate > 0) {
				if (product.tax_type == "inclusive") {
					singleUnitPrice =
						(totalPriceAfterDiscount * 100) / (100 + product.tax_rate);
					taxAmount = singleUnitPrice * (product.tax_rate / 100);
				} else {
					taxAmount = totalPriceAfterDiscount * (product.tax_rate / 100);
					subtotal = totalPriceAfterDiscount + taxAmount;
					singleUnitPrice = totalPriceAfterDiscount;
				}
			}

			const newObject = {
				...product,
				total_discount: totalDiscount * quantityValue,
				subtotal: subtotal * quantityValue,
				quantity: quantityValue,
				total_tax: taxAmount * quantityValue,
				max_quantity: maxQuantity,
				single_unit_price: singleUnitPrice,
			};

			return newObject;
		};

		const quantityChanged = (record) => {
			const newResults = [];

			selectedProducts.value.map((selectedProduct) => {
				if (selectedProduct.xid == record.xid) {
					const newValueCalculated = recalculateValues(record);
					newResults.push(newValueCalculated);
				} else {
					newResults.push(selectedProduct);
				}
			});
			selectedProducts.value = newResults;

			recalculateFinalTotal();
		};

		const recalculateFinalTotal = () => {
			let total = 0;
			selectedProducts.value.map((selectedProduct) => {
				total += selectedProduct.subtotal;
			});
			const discountAmount =
				formData.value.discount != "" ? parseFloat(formData.value.discount) : 0;
			const taxRate =
				formData.value.tax_rate != "" ? parseFloat(formData.value.tax_rate) : 0;

			total = total - discountAmount;

			const tax = total * (taxRate / 100);

			total = total + parseFloat(formData.value.shipping);

			formData.value.subtotal = formatAmount(total + tax);
			formData.value.tax_amount = formatAmount(tax);
			formData.value.discount = discountAmount;
		};

		const showDeleteConfirm = (product) => {
			// Delete selected product and rearrange SN
			const newResults = [];
			let counter = 1;
			selectedProducts.value.map((selectedProduct) => {
				if (selectedProduct.item_id != null) {
					removedOrderItemsIds.value = [
						...removedOrderItemsIds.value,
						selectedProduct.item_id,
					];
				}

				if (selectedProduct.xid != product.xid) {
					newResults.push({
						...selectedProduct,
						sn: counter,
						single_unit_price: formatAmount(
							selectedProduct.single_unit_price
						),
						tax_amount: formatAmount(selectedProduct.tax_amount),
						subtotal: formatAmount(selectedProduct.subtotal),
					});

					counter++;
				}
			});
			selectedProducts.value = newResults;

			// Remove deleted product id from lists
			const filterProductIdArray = selectedProductIds.value.filter((newId) => {
				return newId != product.xid;
			});
			selectedProductIds.value = filterProductIdArray;
			recalculateFinalTotal();
		};

		const taxChanged = (value, option) => {
			formData.value.tax_rate = value == undefined ? 0 : option.tax.rate;
			recalculateFinalTotal();
		};

		// Edit a selected product
		const editItem = (product) => {
			addEditFormData.value = {
				id: product.xid,
				discount_rate: product.discount_rate,
				unit_price: product.unit_price,
				tax_id: product.x_tax_id,
				tax_type: product.tax_type == null ? undefined : product.tax_type,
			};
			addEditVisible.value = true;
			addEditPageTitle.value = product.name;
		};

		const payNow = () => {
			payNowVisible.value = true;
		};

		const payNowClosed = () => {
			payNowVisible.value = false;
		};

		const resetPos = () => {
			selectedProducts.value = [];
			selectedProductIds.value = [];

			formData.value = {
				...formData.value,
				tax_id: undefined,
				category_id: undefined,
				brand_id: undefined,
				tax_id: undefined,
				tax_rate: 0,
				tax_amount: 0,
				discount: 0,
				shipping: 0,
				subtotal: 0,
			};

			recalculateFinalTotal();
		};

		// For Add Edit
		const onAddEditSubmit = () => {
			const record = selectedProducts.value.filter(
				(selectedProduct) => selectedProduct.xid == addEditFormData.value.id
			);

			const selecteTax = taxes.value.filter(
				(tax) => tax.xid == addEditFormData.value.tax_id
			);

			const taxType =
				addEditFormData.value.tax_type != undefined
					? addEditFormData.value.tax_type
					: "exclusive";

			const newData = {
				...record[0],
				discount_rate: parseFloat(addEditFormData.value.discount_rate),
				unit_price: parseFloat(addEditFormData.value.unit_price),
				tax_id: addEditFormData.value.tax_id,
				tax_rate: selecteTax[0] ? selecteTax[0].rate : 0,
				tax_type: taxType,
			};
			quantityChanged(newData);
			onAddEditClose();
		};

		const onAddEditClose = () => {
			addEditFormData.value = {};
			addEditVisible.value = false;
		};

		// Customer
		const customerAdded = () => {
			axiosAdmin.get(customerUrl).then((response) => {
				customers.value = response.data;
			});
		};

		const payNowSuccess = (invoiceOrder) => {
			resetPos();

			formData.value = {
				...formData.value,
				user_id: undefined,
			};

			reFetchProducts();
			payNowVisible.value = false;

			printInvoiceOrder.value = invoiceOrder;
			printInvoiceModalVisible.value = true;
		};

		return {
			taxes,
			customers,
			categories,
			brands,
			productLists,
			formData,
			reFetchProducts,
			selectSaleProduct,

			taxChanged,
			quantityChanged,
			recalculateFinalTotal,

			// Pay Now
			payNow,
			payNowVisible,
			payNowClosed,
			resetPos,

			appSetting,
			...toRefs(state),
			fetchProducts,
			searchValueSelected,
			selectedProducts,
			orderItemColumns,
			formatAmount,
			formatAmountCurrency,

			containerStyle: {
				height: window.innerHeight - 50 + "px",
				"overflow-y": "overlay",
			},

			customerAdded,

			// Add Edit
			editItem,
			addEditVisible,
			addEditFormData,
			addEditFormSubmitting,
			addEditRules,
			addEditPageTitle,
			onAddEditSubmit,
			onAddEditClose,
			taxTypes,
			showDeleteConfirm,

			payNowSuccess,

			printInvoiceModalVisible,
			printInvoiceOrder,
		};
	},
};
</script>

<style lang="less">
.right-icon {
	width: 15%;
	border: 1px solid #d9d9d9;
	border-left: 0px;
	height: 32px;

	span {
		padding-left: 14px;
		padding-top: 7px;
	}
}

.bill-table {
	height: 50vh;
	overflow-y: overlay;
}
</style>
