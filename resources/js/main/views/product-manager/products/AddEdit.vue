<template>
	<a-drawer
		:title="pageTitle"
		:width="drawerWidth"
		:visible="visible"
		:body-style="{ paddingBottom: '80px' }"
		:footer-style="{ textAlign: 'right' }"
		:maskClosable="false"
		@close="onClose"
	>
		<a-form layout="vertical">
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="8" :lg="8">
					<a-row :gutter="16">
						<a-col :span="24">
							<a-form-item
								:label="$t('product.image')"
								name="image"
								:help="rules.image ? rules.image.message : null"
								:validateStatus="rules.image ? 'error' : null"
							>
								<Upload
									:formData="formData"
									folder="product"
									@onFileUploaded="
										(file) => {
											formData.image = file.file;
											formData.image_url = file.file_url;
										}
									"
								/>
							</a-form-item>
						</a-col>
					</a-row>
				</a-col>
				<a-col :xs="24" :sm="24" :md="16" :lg="16">
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('product.name')"
								name="name"
								:help="rules.name ? rules.name.message : null"
								:validateStatus="rules.name ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.name"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('product.name'),
										])
									"
									v-on:keyup="
										formData.slug = slugify($event.target.value)
									"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('product.slug')"
								name="slug"
								:help="rules.slug ? rules.slug.message : null"
								:validateStatus="rules.slug ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.slug"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('product.slug'),
										])
									"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('product.unit')"
								name="unit_id"
								:help="rules.unit_id ? rules.unit_id.message : null"
								:validateStatus="rules.unit_id ? 'error' : null"
								class="required"
							>
								<span style="display: flex">
									<a-select
										v-model:value="formData.unit_id"
										:placeholder="
											$t('common.select_default_text', [
												$t('product.unit'),
											])
										"
										:allowClear="true"
										@change="
											(value, option) => (selectedUnit = option)
										"
									>
										<a-select-option
											v-for="unit in units"
											:key="unit.xid"
											:value="unit.xid"
											:short_name="unit.short_name"
										>
											{{ unit.name }} ({{ unit.short_name }})
										</a-select-option>
									</a-select>
									<UnitAddButton @onAddSuccess="unitAdded" />
								</span>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								name="stock_quantitiy_alert"
								:help="
									rules.stock_quantitiy_alert
										? rules.stock_quantitiy_alert.message
										: null
								"
								:validateStatus="
									rules.stock_quantitiy_alert ? 'error' : null
								"
							>
								<template #label>
									<InputLabelPopover
										:label="$t('product.quantitiy_alert')"
										:content="$t('popover.quantitiy_alert')"
									/>
								</template>
								<a-input-number
									v-model:value="formData.stock_quantitiy_alert"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('product.quantitiy_alert'),
										])
									"
									min="0"
									style="width: 100%"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('product.category')"
								name="category_id"
								:help="
									rules.category_id ? rules.category_id.message : null
								"
								:validateStatus="rules.category_id ? 'error' : null"
								class="required"
							>
								<span style="display: flex">
									<a-tree-select
										:key="'categories_total' + categories.length"
										v-model:value="formData.category_id"
										show-search
										style="width: 100%"
										:dropdown-style="{
											maxHeight: '250px',
											overflow: 'auto',
										}"
										:placeholder="
											$t('common.select_default_text', [
												$t('product.category'),
											])
										"
										:treeData="categories"
										allow-clear
										tree-default-expand-all
									/>
									<CategoryAddButton @onAddSuccess="categoryAdded" />
								</span>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('product.brand')"
								name="brand_id"
								:help="rules.brand_id ? rules.brand_id.message : null"
								:validateStatus="rules.brand_id ? 'error' : null"
							>
								<span style="display: flex">
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
									<BrandAddButton @onAddSuccess="brandAdded" />
								</span>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="8" :lg="8">
							<a-form-item
								:label="$t('product.barcode_symbology')"
								name="barcode_symbology"
								:help="
									rules.barcode_symbology
										? rules.barcode_symbology.message
										: null
								"
								:validateStatus="rules.barcode_symbology ? 'error' : null"
								class="required"
							>
								<a-select
									v-model:value="formData.barcode_symbology"
									:placeholder="
										$t('common.select_default_text', [
											$t('product.barcode_symbology'),
										])
									"
								>
									<a-select-option
										v-for="barcodeSym in barcodeSymbology"
										:key="barcodeSym.key"
										:value="barcodeSym.value"
									>
										{{ barcodeSym.value }}
									</a-select-option>
								</a-select>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="16" :lg="16">
							<a-form-item
								:label="$t('product.item_code')"
								name="item_code"
								:help="rules.item_code ? rules.item_code.message : null"
								:validateStatus="rules.item_code ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.item_code"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('product.item_code'),
										])
									"
								>
									<template #addonAfter>
										<a-button
											v-if="formData.item_code == ''"
											type="text"
											size="small"
											@click="generateBarCode"
										>
											<template #icon>
												<BarcodeOutlined />
											</template>
											{{ $t("product.generate_barcode") }}
										</a-button>
										<Barcode
											:itemCode="formData.item_code"
											:barcodeSymbology="formData.barcode_symbology"
											:options="{ height: 75, format: 'CODE128A' }"
											v-else
										/>
									</template>
								</a-input>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('product.opening_stock')"
								name="opening_stock"
								:help="
									rules.opening_stock
										? rules.opening_stock.message
										: null
								"
								:validateStatus="rules.opening_stock ? 'error' : null"
							>
								<a-input
									v-model:value="formData.opening_stock"
									placeholder="0"
								>
									<template #addonAfter>
										{{
											selectedUnit && selectedUnit.short_name
												? selectedUnit.short_name
												: ""
										}}
									</template>
								</a-input>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('product.opening_stock_date')"
								name="opening_stock_date"
								:help="
									rules.opening_stock_date
										? rules.opening_stock_date.message
										: null
								"
								:validateStatus="
									rules.opening_stock_date ? 'error' : null
								"
							>
								<a-date-picker
									v-model:value="formData.opening_stock_date"
									:format="appSetting.date_format"
									valueFormat="YYYY-MM-DD"
									style="width: 100%"
								/>
							</a-form-item>
						</a-col>
					</a-row>
				</a-col>
			</a-row>

			<form-item-heading>
				{{ $t("product.price_tax") }}
			</form-item-heading>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="8" :lg="8">
					<a-form-item
						:label="$t('product.purchase_price')"
						name="purchase_price"
						:help="rules.purchase_price ? rules.purchase_price.message : null"
						:validateStatus="rules.purchase_price ? 'error' : null"
						class="required"
					>
						<a-input-number
							v-model:value="formData.purchase_price"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('product.purchase_price'),
								])
							"
							min="0"
							style="width: 100%"
						>
							<template #addonBefore>
								{{ appSetting.currency.symbol }}
							</template>
							<template #addonAfter>
								<a-select
									v-model:value="formData.purchase_tax_type"
									style="width: 120px"
								>
									<a-select-option value="inclusive">
										{{ $t("common.with_tax") }}
									</a-select-option>
									<a-select-option value="exclusive">
										{{ $t("common.without_tax") }}
									</a-select-option>
								</a-select>
							</template>
						</a-input-number>
					</a-form-item>
				</a-col>
				<a-col :xs="24" :sm="24" :md="8" :lg="8">
					<a-form-item
						:label="$t('product.sales_price')"
						name="sales_price"
						:help="rules.sales_price ? rules.sales_price.message : null"
						:validateStatus="rules.sales_price ? 'error' : null"
						class="required"
					>
						<a-input-number
							v-model:value="formData.sales_price"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('product.sales_price'),
								])
							"
							min="0"
							style="width: 100%"
						>
							<template #addonBefore>
								{{ appSetting.currency.symbol }}
							</template>
							<template #addonAfter>
								<a-select
									v-model:value="formData.sales_tax_type"
									style="width: 120px"
								>
									<a-select-option value="inclusive">
										{{ $t("common.with_tax") }}
									</a-select-option>
									<a-select-option value="exclusive">
										{{ $t("common.without_tax") }}
									</a-select-option>
								</a-select>
							</template>
						</a-input-number>
					</a-form-item>
				</a-col>
				<a-col :xs="24" :sm="24" :md="8" :lg="8">
					<a-form-item
						:label="$t('product.mrp')"
						name="mrp"
						:help="rules.mrp ? rules.mrp.message : null"
						:validateStatus="rules.mrp ? 'error' : null"
					>
						<a-input-number
							v-model:value="formData.mrp"
							:placeholder="
								$t('common.placeholder_default_text', [$t('product.mrp')])
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
				<a-col :xs="24" :sm="24" :md="12" :lg="12">
					<a-form-item
						:label="$t('product.tax')"
						name="tax_id"
						:help="rules.tax_id ? rules.tax_id.message : null"
						:validateStatus="rules.tax_id ? 'error' : null"
					>
						<span style="display: flex">
							<a-select
								v-model:value="formData.tax_id"
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
							<TaxAddButton @onAddSuccess="taxAdded" />
						</span>
					</a-form-item>
				</a-col>
			</a-row>

			<form-item-heading>
				{{ $t("product.custom_fields") }}
			</form-item-heading>
			<a-row :gutter="16">
				<a-col
					:xs="24"
					:sm="24"
					:md="6"
					:lg="6"
					v-for="customField in customFields"
					:key="customField.xid"
				>
					<a-form-item :label="customField.name" :name="customField.name">
						<a-input
							v-model:value="customFieldsData[customField.name]"
							:placeholder="customField.name"
						/>
					</a-form-item>
				</a-col>
			</a-row>

			<!-- <form-item-heading>
				{{ $t("product.wholesale_rate") }}
			</form-item-heading>
			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="12" :lg="12">
					<a-form-item
						:label="$t('product.wholesale_price')"
						name="wholesale_price"
						:help="
							rules.wholesale_price ? rules.wholesale_price.message : null
						"
						:validateStatus="rules.wholesale_price ? 'error' : null"
					>
						<a-input v-model:value="formData.wholesale_price" placeholder="0">
							<template #addonAfter>
								<span v-if="formData.sales_tax_type == 'inclusive'">
									{{ $t("common.with_tax") }}
								</span>
								<span v-else>{{ $t("common.without_tax") }}</span>
							</template>
						</a-input>
					</a-form-item>
				</a-col>
				<a-col :xs="24" :sm="24" :md="12" :lg="12">
					<a-form-item
						:label="$t('product.wholesale_quantity')"
						name="wholesale_quantity"
						:help="
							rules.wholesale_quantity
								? rules.wholesale_quantity.message
								: null
						"
						:validateStatus="rules.wholesale_quantity ? 'error' : null"
					>
						<a-input
							v-model:value="formData.wholesale_quantity"
							:placeholder="$t('product.enter_min_quantity')"
						>
							<template #addonAfter>
								{{
									selectedUnit && selectedUnit.short_name
										? selectedUnit.short_name
										: ""
								}}
							</template>
						</a-input>
					</a-form-item>
				</a-col>
			</a-row> -->

			<a-row>
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('product.description')"
						name="description"
						:help="rules.description ? rules.description.message : null"
						:validateStatus="rules.description ? 'error' : null"
					>
						<a-textarea
							v-model:value="formData.description"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('product.description'),
								])
							"
							:rows="4"
						/>
					</a-form-item>
				</a-col>
			</a-row>
		</a-form>
		<template #footer>
			<a-button
				type="primary"
				:loading="loading"
				@click="onSubmit"
				style="margin-right: 8px"
			>
				<template #icon>
					<SaveOutlined />
				</template>
				{{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
			</a-button>
			<a-button @click="onClose">
				{{ $t("common.cancel") }}
			</a-button>
		</template>
	</a-drawer>
</template>
<script>
import { defineComponent, ref, onMounted, watch } from "vue";
import {
	PlusOutlined,
	LoadingOutlined,
	SaveOutlined,
	BarcodeOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import moment from "moment";
import { forEach, find } from "lodash-es";
import apiAdmin from "../../../../common/composable/apiAdmin";
import common from "../../../../common/composable/common";
import Upload from "../../../../common/core/ui/file/Upload.vue";
import UserInfo from "../../../../common/components/user/UserInfo.vue";
import FormItemHeading from "../../../../common/components/common/typography/FormItemHeading.vue";
import InputLabelPopover from "../../../../common/components/common/typography/InputLabelPopover.vue";
import Barcode from "./Barcode.vue";
import BrandAddButton from "../brands/AddButton.vue";
import CategoryAddButton from "../categories/AddButton.vue";
import UnitAddButton from "../../settings/units/AddButton.vue";
import TaxAddButton from "../../settings/taxes/AddButton.vue";

export default defineComponent({
	props: [
		"formData",
		"data",
		"visible",
		"url",
		"addEditType",
		"pageTitle",
		"successMessage",
	],
	components: {
		PlusOutlined,
		LoadingOutlined,
		SaveOutlined,
		Upload,
		UserInfo,
		FormItemHeading,
		InputLabelPopover,
		BarcodeOutlined,
		Barcode,
		BrandAddButton,
		CategoryAddButton,
		UnitAddButton,
		TaxAddButton,
	},
	setup(props, { emit }) {
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const { taxTypes, barcodeSymbology, appSetting, slugify } = common();
		const { t } = useI18n();
		const brands = ref([]);
		const categories = ref([]);
		const units = ref([]);
		const taxes = ref([]);
		const customFields = ref([]);
		const customFieldsData = ref({});
		const selectedUnit = ref({});
		const store = useStore();
		const brandsUrl = "brands?limit=10000";
		const categoriesUrl = "categories?limit=10000";
		const unitsUrl = "units?limit=10000";
		const taxesUrl = "taxes?limit=10000";
		const customFieldsUrl = "custom-fields?limit=10000";

		onMounted(() => {
			moment.suppressDeprecationWarnings = true;
			const brandsPromise = axiosAdmin.get(brandsUrl);
			const categoriesPromise = axiosAdmin.get(categoriesUrl);
			const unitsPromise = axiosAdmin.get(unitsUrl);
			const taxesPromise = axiosAdmin.get(taxesUrl);
			const customFieldsPromise = axiosAdmin.get(customFieldsUrl);

			Promise.all([
				brandsPromise,
				categoriesPromise,
				unitsPromise,
				taxesPromise,
				customFieldsPromise,
			]).then(
				([
					brandsResponse,
					categoriesResponse,
					unitsResponse,
					taxesResponse,
					customFieldsResponse,
				]) => {
					brands.value = brandsResponse.data;
					units.value = unitsResponse.data;
					taxes.value = taxesResponse.data;
					customFields.value = customFieldsResponse.data;
					selectedUnit.value = find(units.value, [
						"xid",
						props.formData.unit_id,
					]);

					setCategories(categoriesResponse.data);
				}
			);
		});

		const setCategories = (categoryResponseData) => {
			// Category Tree
			const allCategoriesArray = [];
			const listArray = [];
			categoryResponseData.map((responseArray) => {
				listArray.push({
					xid: responseArray.xid,
					x_parent_id: responseArray.x_parent_id,
					title: responseArray.name,
					value: responseArray.xid,
					// disabled: responseArray.x_parent_id == null ? true : false,
					disabled: false,
				});
			});

			listArray.forEach((node) => {
				// No parentId means top level
				if (!node.x_parent_id) return allCategoriesArray.push(node);

				// Insert node as child of parent in listArray array
				const parentIndex = listArray.findIndex(
					(el) => el.xid === node.x_parent_id
				);
				if (!listArray[parentIndex].children) {
					return (listArray[parentIndex].children = [node]);
				}

				listArray[parentIndex].children.push(node);
			});

			categories.value = allCategoriesArray;
		};

		const disabledDate = (current) => {
			// Can not select days before today and today
			return current && current > moment().endOf("day");
		};

		const generateBarCode = () => {
			props.formData.item_code = parseInt(Math.random() * 10000000000);
		};

		const onSubmit = () => {
			const newData = {
				...props.formData,
				tax_id: props.formData.tax_id == null ? "" : props.formData.tax_id,
				custom_fields: customFieldsData.value,
			};

			addEditRequestAdmin({
				url: props.url,
				data: newData,
				successMessage: props.successMessage,
				success: (res) => {
					emit("addEditSuccess", res.xid);
				},
			});
		};

		const onClose = () => {
			rules.value = {};
			emit("closed");
		};

		const brandAdded = () => {
			axiosAdmin.get(brandsUrl).then((response) => {
				brands.value = response.data;
			});
		};

		const categoryAdded = () => {
			axiosAdmin.get(categoriesUrl).then((response) => {
				setCategories(response.data);
			});
		};

		const unitAdded = () => {
			axiosAdmin.get(unitsUrl).then((response) => {
				units.value = response.data;
			});
		};

		const taxAdded = () => {
			axiosAdmin.get(taxesUrl).then((response) => {
				taxes.value = response.data;
			});
		};

		watch(
			() => props.visible,
			(newVal, oldVal) => {
				if (newVal == true) {
					var newFields = {};
					forEach(customFields.value, (customField) => {
						if (
							props.addEditType == "add" ||
							props.formData.custom_fields.length == 0
						) {
							newFields[customField.name] = "";
						} else {
							var searchedField = find(props.formData.custom_fields, [
								"field_name",
								customField.name,
							]);
							newFields[customField.name] =
								searchedField === undefined
									? ""
									: searchedField.field_value;
						}
					});
					customFieldsData.value = { ...newFields };

					selectedUnit.value = find(units.value, [
						"xid",
						props.formData.unit_id,
					]);
				}
			}
		);

		return {
			loading,
			rules,
			onClose,
			onSubmit,
			disabledDate,

			brands,
			categories,
			taxes,
			units,

			slugify,
			generateBarCode,

			drawerWidth: window.innerWidth <= 991 ? "90%" : "60%",
			appSetting,

			customFields,
			customFieldsData,
			taxTypes,
			barcodeSymbology,
			selectedUnit,

			brandAdded,
			categoryAdded,
			unitAdded,
			taxAdded,
		};
	},
});
</script>

<style>
.ant-calendar-picker {
	width: 100%;
}
</style>
