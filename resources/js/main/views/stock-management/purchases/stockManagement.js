import { ref, reactive, computed, watch } from "vue";
import { useRoute } from "vue-router";
import { debounce } from "lodash-es";
import common from "../../../../common/composable/common";

const stockManagement = () => {
	const { formatAmount, orderType, orderPageObject, selectedWarehouse } = common();
	const route = useRoute();
	const selectedProducts = ref([]);
	const selectedProductIds = ref([]);
	const removedOrderItemsIds = ref([]);
	const state = reactive({
		orderSearchTerm: [],
		productFetching: false,
		products: [],
	});
	const formData = ref({
		order_type: route.params.type,
		invoice_number: "",
		order_date: undefined,
		warehouse_id: selectedWarehouse.value.xid,
		user_id: undefined,
		terms_condition: selectedWarehouse.value.terms_condition,
		notes: "",
		order_status: undefined,
		tax_id: undefined,
		tax_rate: 0,
		tax_amount: 0,
		discount: 0,
		shipping: 0,
		subtotal: 0,
	});
	const taxes = ref([]);
	const productsAmount = ref({
		subtotal: 0,
		tax: 0
	});

	// AddEdit
	const addEditVisible = ref(false);
	const addEditFormSubmitting = ref(false);
	const addEditFormData = ref({});
	const addEditRules = ref([]);
	const addEditPageTitle = ref("");

	const fetchProducts = debounce((value) => {
		state.products = [];

		if (value != "") {
			state.productFetching = true;
			let url = `search-product`;

			axiosAdmin
				.post(url, {
					order_type: orderPageObject.value.type,
					search_term: value,
					warehouse_id: formData.value.warehouse_id,
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
	};

	const recalculateValues = (product) => {
		var quantityValue = parseFloat(product.quantity);
		var maxQuantity = parseFloat(product.stock_quantity);
		var orderItemId = product.item_id;
		const unitPrice = parseFloat(product.unit_price);

		// Check if entered quantity value is greater
		quantityValue =
			quantityValue > maxQuantity && (orderPageObject.value.type == "sales" || orderPageObject.value.type == "purchase-returns")
				? maxQuantity
				: quantityValue;

		// Discount Amount
		const discountRate = product.discount_rate;
		const totalDiscount = discountRate > 0 ? ((discountRate / 100) * (unitPrice)) : 0;
		const totalPriceAfterDiscount = unitPrice - totalDiscount;

		var taxAmount = 0;
		var subtotal = totalPriceAfterDiscount;
		var singleUnitPrice = unitPrice;

		// Tax Amount
		if (product.tax_rate > 0) {
			if (product.tax_type == "inclusive") {
				singleUnitPrice = (totalPriceAfterDiscount * 100) / (100 + product.tax_rate);
				taxAmount = (singleUnitPrice) * (product.tax_rate / 100);
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
			single_unit_price: singleUnitPrice
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
		let taxAmount = 0;
		selectedProducts.value.map((selectedProduct) => {
			total += selectedProduct.subtotal;
		});
		const discountAmount = formData.value.discount != "" ? parseFloat(formData.value.discount) : 0;
		const taxRate = formData.value.tax_rate != "" ? parseFloat(formData.value.tax_rate) : 0;

		selectedProducts.value.map((selectedProduct) => {
			taxAmount += selectedProduct.total_tax;
		});
		productsAmount.value.subtotal = total;
		productsAmount.value.tax = taxAmount;

		total = total - discountAmount;

		const tax = total * (taxRate / 100);

		total = total + parseFloat(formData.value.shipping);

		formData.value.subtotal = formatAmount(total + tax);
		formData.value.tax_amount = formatAmount(tax);
		formData.value.discount = discountAmount;


	};

	const calculateProductAmount = () => {
		let total = 0;
		let taxAmount = 0;
		selectedProducts.value.map((selectedProduct) => {
			total += selectedProduct.subtotal;
		});

		selectedProducts.value.map((selectedProduct) => {
			taxAmount += selectedProduct.total_tax;
		});
		productsAmount.value.subtotal = total;
		productsAmount.value.tax = taxAmount;
	}

	const showDeleteConfirm = (product) => {
		// Delete selected product and rearrange SN
		const newResults = [];
		let counter = 1;
		selectedProducts.value.map((selectedProduct) => {

			if (selectedProduct.item_id != null) {
				removedOrderItemsIds.value = [...removedOrderItemsIds.value, selectedProduct.item_id];
			}

			if (selectedProduct.xid != product.xid) {
				newResults.push({
					...selectedProduct,
					sn: counter,
					single_unit_price: formatAmount(selectedProduct.single_unit_price),
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

	// For Add Edit
	const onAddEditSubmit = () => {
		const record = selectedProducts.value.filter(
			(selectedProduct) => selectedProduct.xid == addEditFormData.value.id
		);

		const selecteTax = taxes.value.filter(
			(tax) => tax.xid == addEditFormData.value.tax_id
		);

		const taxType = addEditFormData.value.tax_type != undefined ? addEditFormData.value.tax_type : 'exclusive';

		const newData = {
			...record[0],
			discount_rate: parseFloat(addEditFormData.value.discount_rate),
			unit_price: parseFloat(addEditFormData.value.unit_price),
			tax_id: addEditFormData.value.tax_id,
			tax_rate: selecteTax[0] ? selecteTax[0].rate : 0,
			tax_type: taxType
		};
		quantityChanged(newData);
		onAddEditClose();
	};

	const onAddEditClose = () => {
		addEditFormData.value = {};
		addEditVisible.value = false;
	};

	watch(route, (newVal, oldVal) => {
		orderType.value = newVal.params.type;
		formData.value = {
			...formData.value,
			order_type: newVal.params.type,
		};
	});

	return {
		state,
		route,
		orderType,
		orderPageObject,
		selectedProducts,
		selectedProductIds,
		formData,
		productsAmount,
		taxes,

		fetchProducts,
		searchValueSelected,
		recalculateValues,
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
		removedOrderItemsIds,

		calculateProductAmount,
	};

};

export default stockManagement;