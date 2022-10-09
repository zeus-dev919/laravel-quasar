<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header
				:title="$t(`menu.${orderPageObject.menuKey}`)"
				@back="() => $router.go(-1)"
				class="p-0"
			>
				<template #extra>
					<a-button type="primary" :loading="loading" @click="onSubmit" block>
						<template #icon> <SaveOutlined /> </template>
						{{ $t("common.save") }}
					</a-button>
				</template>
			</a-page-header>
		</template>
		<template #breadcrumb>
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
					<router-link
						:to="{
							name: `admin.stock.${orderPageObject.type}.index`,
						}"
					>
						{{ $t(`menu.${orderPageObject.menuKey}`) }}
					</router-link>
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`common.create`) }}
				</a-breadcrumb-item>
			</a-breadcrumb>
		</template>
	</AdminPageHeader>

	<a-card class="page-content-container">
		<a-form layout="vertical">
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="8" :lg="8">
					<a-form-item
						:label="$t(`stock.invoice_number`)"
						name="invoice_number"
						:help="rules.invoice_number ? rules.invoice_number.message : null"
						:validateStatus="rules.invoice_number ? 'error' : null"
					>
						<a-input
							v-model:value="formData.invoice_number"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('stock.invoice_number'),
								])
							"
						/>
						<small class="small-text-message">
							{{ $t("stock.invoie_number_blank") }}
						</small>
					</a-form-item>
				</a-col>
				<a-col :xs="24" :sm="24" :md="8" :lg="8">
					<a-form-item
						:label="$t(`${orderPageObject.langKey}.user`)"
						name="user_id"
						:help="rules.user_id ? rules.user_id.message : null"
						:validateStatus="rules.user_id ? 'error' : null"
						class="required"
					>
						<span style="display: flex">
							<a-select
								v-model:value="formData.user_id"
								:placeholder="
									$t('common.select_default_text', [
										$t(`${orderPageObject.langKey}.user`),
									])
								"
								:allowClear="true"
								optionFilterProp="title"
								show-search
							>
								<a-select-option
									v-for="user in users"
									:key="user.xid"
									:value="user.xid"
									:title="user.name"
								>
									{{ user.name }}
								</a-select-option>
							</a-select>
							<SupplierAddButton
								v-if="orderPageObject.userType == 'suppliers'"
								@onAddSuccess="userAdded"
							/>
							<CustomerAddButton v-else @onAddSuccess="userAdded" />
						</span>
					</a-form-item>
				</a-col>
				<a-col :xs="24" :sm="24" :md="8" :lg="8">
					<a-form-item
						:label="
							$t(
								`${orderPageObject.langKey}.${orderPageObject.langKey}_date`
							)
						"
						name="order_date"
						:help="rules.order_date ? rules.order_date.message : null"
						:validateStatus="rules.order_date ? 'error' : null"
						class="required"
					>
						<DateTimePicker
							@dateTimeChanged="
								(changedDateTime) =>
									(formData.order_date = changedDateTime)
							"
						/>
					</a-form-item>
				</a-col>
			</a-row>
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('product.product')"
						name="orderSearchTerm"
						:help="rules.product_items ? rules.product_items.message : null"
						:validateStatus="rules.product_items ? 'error' : null"
					>
						<span style="display: flex">
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
								:not-found-content="productFetching ? undefined : null"
								@search="fetchProducts"
								size="large"
								option-label-prop="label"
								@focus="products = []"
								@select="searchValueSelected"
							>
								<template #suffixIcon><SearchOutlined /></template>
								<template v-if="productFetching" #notFoundContent>
									<a-spin size="small" />
								</template>
								<a-select-option
									v-for="product in products"
									:key="product.xid"
									:value="product.xid"
									:label="product.name"
									:product="product"
								>
									=> {{ product.name }}
								</a-select-option>
							</a-select>
							<ProductAddButton size="large" />
						</span>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
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
							<template v-if="column.dataIndex === 'unit_quantity'">
								<a-input-number
									id="inputNumber"
									v-model:value="record.quantity"
									@change="quantityChanged(record)"
									:min="0"
								/>
							</template>
							<template v-if="column.dataIndex === 'single_unit_price'">
								{{ formatAmountCurrency(record.single_unit_price) }}
							</template>
							<template v-if="column.dataIndex === 'total_discount'">
								{{ formatAmountCurrency(record.total_discount) }}
							</template>
							<template v-if="column.dataIndex === 'total_tax'">
								{{ formatAmountCurrency(record.total_tax) }}
							</template>
							<template v-if="column.dataIndex === 'subtotal'">
								{{ formatAmountCurrency(record.subtotal) }}
							</template>
							<template v-if="column.dataIndex === 'action'">
								<a-button
									type="primary"
									@click="editItem(record)"
									style="margin-left: 4px"
								>
									<template #icon><EditOutlined /></template>
								</a-button>
								<a-button
									type="primary"
									@click="showDeleteConfirm(record)"
									style="margin-left: 4px"
								>
									<template #icon><DeleteOutlined /></template>
								</a-button>
							</template>
						</template>
						<template #summary>
							<a-table-summary-row>
								<a-table-summary-cell
									:col-span="4"
								></a-table-summary-cell>
								<a-table-summary-cell>
									{{ $t("product.subtotal") }}
								</a-table-summary-cell>
								<a-table-summary-cell>
									{{ formatAmountCurrency(productsAmount.tax) }}
								</a-table-summary-cell>
								<a-table-summary-cell :col-span="2">
									{{ formatAmountCurrency(productsAmount.subtotal) }}
								</a-table-summary-cell>
							</a-table-summary-row>
						</template>
					</a-table>
				</a-col>
			</a-row>

			<a-row :gutter="16" class="mt-30">
				<a-col :xs="24" :sm="24" :md="16" :lg="16">
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('warehouse.terms_condition')"
								name="terms_condition"
								:help="
									rules.terms_condition
										? rules.terms_condition.message
										: null
								"
								:validateStatus="rules.terms_condition ? 'error' : null"
							>
								<a-textarea
									v-model:value="formData.terms_condition"
									:placeholder="$t('warehouse.terms_condition')"
									:auto-size="{ minRows: 2, maxRows: 3 }"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('stock.notes')"
								name="notes"
								:help="rules.notes ? rules.notes.message : null"
								:validateStatus="rules.notes ? 'error' : null"
							>
								<a-textarea
									v-model:value="formData.notes"
									:placeholder="$t('stock.notes')"
									:auto-size="{ minRows: 2, maxRows: 3 }"
								/>
							</a-form-item>
						</a-col>
					</a-row>
				</a-col>
				<a-col :xs="24" :sm="24" :md="8" :lg="8">
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('stock.status')"
								name="order_status"
								:help="
									rules.order_status ? rules.order_status.message : null
								"
								:validateStatus="rules.order_status ? 'error' : null"
								class="required"
							>
								<a-select
									v-model:value="formData.order_status"
									:placeholder="
										$t('common.select_default_text', [
											$t('stock.status'),
										])
									"
									:allowClear="true"
								>
									<a-select-option
										v-for="status in allOrderStatus"
										:key="status.key"
										:value="status.key"
									>
										{{ status.value }}
									</a-select-option>
								</a-select>
							</a-form-item>
						</a-col>
					</a-row>

					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('stock.order_tax')"
								name="tax_id"
								:help="rules.tax_id ? rules.tax_id.message : null"
								:validateStatus="rules.tax_id ? 'error' : null"
							>
								<span style="display: flex">
									<a-select
										v-model:value="formData.tax_id"
										:placeholder="
											$t('common.select_default_text', [
												$t('stock.order_tax'),
											])
										"
										:allowClear="true"
										@change="taxChanged"
										optionFilterProp="title"
										show-search
									>
										<a-select-option
											v-for="tax in taxes"
											:key="tax.xid"
											:value="tax.xid"
											:title="tax.name"
											:tax="tax"
										>
											{{ tax.name }} ({{ tax.rate }}%)
										</a-select-option>
									</a-select>
									<TaxAddButton @onAddSuccess="taxAdded" />
								</span>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('stock.discount')"
								name="discount"
								:help="rules.discount ? rules.discount.message : null"
								:validateStatus="rules.discount ? 'error' : null"
							>
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
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('stock.shipping')"
								name="shipping"
								:help="rules.shipping ? rules.shipping.message : null"
								:validateStatus="rules.shipping ? 'error' : null"
							>
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
					<a-row :gutter="16" class="mt-10">
						<a-col :xs="12" :sm="12" :md="12" :lg="12">
							{{ $t("stock.order_tax") }}
						</a-col>
						<a-col :xs="12" :sm="12" :md="12" :lg="12">
							{{ formatAmountCurrency(formData.tax_amount) }}
						</a-col>
					</a-row>
					<a-row :gutter="16" class="mt-10">
						<a-col :xs="12" :sm="12" :md="12" :lg="12">
							{{ $t("stock.discount") }}
						</a-col>
						<a-col :xs="12" :sm="12" :md="12" :lg="12">
							{{ formatAmountCurrency(formData.discount) }}
						</a-col>
					</a-row>
					<a-row :gutter="16" class="mt-10">
						<a-col :xs="12" :sm="12" :md="12" :lg="12">
							{{ $t("stock.shipping") }}
						</a-col>
						<a-col :xs="12" :sm="12" :md="12" :lg="12">
							{{ formatAmountCurrency(formData.shipping) }}
						</a-col>
					</a-row>
					<a-row :gutter="16" class="mt-10">
						<a-col :xs="12" :sm="12" :md="12" :lg="12">
							{{ $t("stock.grand_total") }}
						</a-col>
						<a-col :xs="12" :sm="12" :md="12" :lg="12">
							{{ formatAmountCurrency(formData.subtotal) }}
						</a-col>
					</a-row>
					<a-row :gutter="16" class="mt-20">
						<a-button
							type="primary"
							:loading="loading"
							@click="onSubmit"
							block
						>
							<template #icon> <SaveOutlined /> </template>
							{{ $t("common.save") }}
						</a-button>
					</a-row>
				</a-col>
			</a-row>
		</a-form>
	</a-card>

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
						<span style="display: flex">
							<a-select
								v-model:value="addEditFormData.tax_id"
								:placeholder="
									$t('common.select_default_text', [$t('product.tax')])
								"
								:allowClear="true"
								optionFilterProp="title"
								show-search
							>
								<a-select-option
									v-for="tax in taxes"
									:key="tax.xid"
									:value="tax.xid"
									:title="tax.name"
								>
									{{ tax.name }} ({{ tax.rate }}%)
								</a-select-option>
							</a-select>
							<TaxAddButton @onAddSuccess="taxAdded" />
						</span>
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
</template>

<script>
import { onMounted, ref, toRefs } from "vue";
import {
	EyeOutlined,
	PlusOutlined,
	EditOutlined,
	DeleteOutlined,
	ExclamationCircleOutlined,
	SearchOutlined,
	SaveOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";
import apiAdmin from "../../../../common/composable/apiAdmin";
import stockManagement from "./stockManagement";
import common from "../../../../common/composable/common";
import fields from "./fields";
import SupplierAddButton from "../../users/SupplierAddButton.vue";
import CustomerAddButton from "../../users/CustomerAddButton.vue";
import TaxAddButton from "../../settings/taxes/AddButton.vue";
import ProductAddButton from "../../product-manager/products/AddButton.vue";
import DateTimePicker from "../../../../common/components/common/calendar/DateTimePicker.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
	components: {
		EyeOutlined,
		PlusOutlined,
		EditOutlined,
		DeleteOutlined,
		ExclamationCircleOutlined,
		SearchOutlined,
		SaveOutlined,

		SupplierAddButton,
		CustomerAddButton,
		TaxAddButton,
		ProductAddButton,
		DateTimePicker,
		AdminPageHeader,
	},
	setup() {
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const {
			appSetting,
			formatAmount,
			formatAmountCurrency,
			taxTypes,
			orderStatus,
			purchaseOrderStatus,
			salesOrderStatus,
			salesReturnStatus,
			purchaseReturnStatus,
			permsArray,
		} = common();
		const { orderItemColumns } = fields();
		const {
			state,
			orderType,
			orderPageObject,
			selectedProducts,
			formData,
			productsAmount,
			taxes,

			recalculateValues,
			fetchProducts,
			searchValueSelected,
			quantityChanged,
			recalculateFinalTotal,
			showDeleteConfirm,
			taxChanged,
			editItem,

			// Add Edit
			addEditVisible,
			addEditFormData,
			addEditFormSubmitting,
			addEditRules,
			addEditPageTitle,
			onAddEditSubmit,
			onAddEditClose,
		} = stockManagement();
		const { t } = useI18n();
		const users = ref([]);
		const allUnits = ref([]);
		const router = useRouter();
		const allOrderStatus = ref([]);
		const taxUrl = "taxes?limit=10000";
		const unitUrl = "units?limit=10000";
		const usersUrl = `${orderPageObject.value.userType}?limit=10000`;

		onMounted(() => {
			const taxesPromise = axiosAdmin.get(taxUrl);
			const unitsPromise = axiosAdmin.get(unitUrl);
			const usersPromise = axiosAdmin.get(usersUrl);

			Promise.all([usersPromise, taxesPromise, unitsPromise]).then(
				([usersResponse, taxResponse, unitResponse]) => {
					users.value = usersResponse.data;
					taxes.value = taxResponse.data;
					allUnits.value = unitResponse.data;
				}
			);

			if (orderType.value == "purchases") {
				allOrderStatus.value = purchaseOrderStatus;
			} else if (orderType.value == "sales") {
				allOrderStatus.value = salesOrderStatus;
			} else if (orderType.value == "sales-returns") {
				allOrderStatus.value = salesReturnStatus;
			} else if (orderType.value == "purchase-returns") {
				allOrderStatus.value = purchaseReturnStatus;
			}
		});

		const onSubmit = () => {
			const newFormDataObject = {
				...formData.value,
				order_type: orderPageObject.value.type,
				total: formData.value.subtotal,
				total_items: selectedProducts.value.length,
				product_items: selectedProducts.value,
			};

			addEditRequestAdmin({
				url: orderType.value,
				data: newFormDataObject,
				successMessage: t(`${orderPageObject.value.langKey}.created`),
				success: (res) => {
					router.push({
						name: `admin.stock.${orderType.value}.index`,
					});
				},
			});
		};

		const userAdded = () => {
			axiosAdmin.get(usersUrl).then((response) => {
				users.value = response.data;
			});
		};

		const unitAdded = () => {
			axiosAdmin.get(unitUrl).then((response) => {
				allUnits.value = response.data;
			});
		};

		const taxAdded = () => {
			axiosAdmin.get(taxUrl).then((response) => {
				taxes.value = response.data;
			});
		};

		return {
			...toRefs(state),
			formData,
			productsAmount,
			rules,
			loading,
			users,
			taxes,
			onSubmit,
			fetchProducts,
			searchValueSelected,
			selectedProducts,
			showDeleteConfirm,
			quantityChanged,
			formatAmountCurrency,
			taxChanged,
			recalculateFinalTotal,
			appSetting,
			editItem,
			orderPageObject,

			orderItemColumns,

			// Add Edit
			addEditVisible,
			addEditFormData,
			addEditFormSubmitting,
			addEditRules,
			addEditPageTitle,
			onAddEditSubmit,
			onAddEditClose,
			allOrderStatus,
			taxTypes,
			permsArray,

			userAdded,
			unitAdded,
			taxAdded,
		};
	},
};
</script>
