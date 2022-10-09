import { ref } from "vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";

const fields = () => {
	const { t } = useI18n();
	const store = useStore();
	const taxes = ref([]);
	const warehouses = ref([]);
	const customers = ref([]);
	const brands = ref([]);
	const categories = ref([]);
	const productLists = ref([]);

	const formData = ref({
		user_id: undefined,
		tax_id: undefined,
		category_id: undefined,
		brand_id: undefined,
		tax_id: undefined,
		tax_rate: 0,
		tax_amount: 0,
		discount: 0,
		shipping: 0,
		subtotal: 0,
	});

	const orderItemColumns = [
		{
			title: "#",
			dataIndex: "sn",
		},
		{
			title: t("product.name"),
			dataIndex: "name",
		},
		{
			title: t("product.quantity"),
			dataIndex: "unit_quantity",
		},
		{
			title: t("product.subtotal"),
			dataIndex: "subtotal",
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const customerUrl = "customers?limit=10000";

	const getPreFetchData = () => {
		const taxesPromise = axiosAdmin.get("taxes?limit=10000");
		const customersPromise = axiosAdmin.get(customerUrl);
		const categoriesPromise = axiosAdmin.get("categories?limit=10000");
		const brandsPromise = axiosAdmin.get("brands?limit=10000");
		const productsPromise = axiosAdmin.post("pos/products", {
			brand_id: formData.value.brand_id,
			category_id: formData.value.category_id,
		});

		Promise.all([
			taxesPromise,
			customersPromise,
			productsPromise,
			categoriesPromise,
			brandsPromise,
		]).then(
			([
				taxesResponse,
				customersResponse,
				productResponse,
				caegoriesResponse,
				brandsResponse,
			]) => {
				taxes.value = taxesResponse.data;
				customers.value = customersResponse.data;
				categories.value = caegoriesResponse.data;
				brands.value = brandsResponse.data;
				productLists.value = productResponse.data.products;
			}
		);
	};

	return {
		taxes,
		customers,
		brands,
		categories,
		productLists,
		formData,

		customerUrl,

		orderItemColumns,
		getPreFetchData,
	}
}

export default fields;