import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";

const fields = () => {
	const store = useStore();
	const { t } = useI18n();
	const route = useRoute();
	const orderType = ref(route.meta.orderType);
	const columns = ref([]);
	const hashableColumns = ['user_id'];

	onMounted(() => {
		if (route.meta && route.meta.orderType) {
			orderType.value = route.meta.orderType;
		} else {
			orderType.value = "online-orders";
		}
	});

	const initData = {
		order_date: undefined,
		user_id: undefined,
		notes: "",
		order_status: undefined,
		tax_id: undefined,
		discount: 0,
		shipping: 0,
		subtotal: 0,
	};

	const initPaymentData = {
		date: undefined,
		payment_mode_id: undefined,
		amount: "",
		notes: "",
	};

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
			title: t("product.unit_price"),
			dataIndex: "single_unit_price",
		},
		{
			title: t("product.discount"),
			dataIndex: "total_discount",
		},
		{
			title: t("product.tax"),
			dataIndex: "total_tax",
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

	const orderItemDetailsColumns = [
		{
			title: t("product.product"),
			dataIndex: "product_id",
		},
		{
			title: t("product.quantity"),
			dataIndex: "quantity",
		},
		{
			title: t("product.unit_price"),
			dataIndex: "single_unit_price",
		},
		{
			title: t("product.discount"),
			dataIndex: "total_discount",
		},
		{
			title: t("product.tax"),
			dataIndex: "total_tax",
		},
		{
			title: t("product.subtotal"),
			dataIndex: "subtotal",
		},
	];

	const filterableColumns = [
		{
			key: "invoice_number",
			value: t("stock.invoice_number")
		},
	];

	const pageObject = computed(() => {
		var pageObjectDetails = {};

		if (orderType.value == "purchases") {
			pageObjectDetails = {
				type: "purchases",
				langKey: "purchase",
				menuKey: "purchases",
				userType: "suppliers",
				permission: "purchases",
			};
		} else if (orderType.value == "sales") {
			pageObjectDetails = {
				type: "sales",
				langKey: "sales",
				menuKey: "sales",
				userType: "customers",
				permission: "sales",
			};
		} else if (orderType.value == "purchase-returns") {
			pageObjectDetails = {
				type: "purchase-returns",
				langKey: "purchase_returns",
				menuKey: "purchase_returns",
				userType: "suppliers",
				permission: "purchase_returns",
			};
		} else if (orderType.value == "sales-returns") {
			pageObjectDetails = {
				type: "sales-returns",
				langKey: "sales_returns",
				menuKey: "sales_returns",
				userType: "customers",
				permission: "sales_returns",
			};
		} else if (orderType.value == "online-orders") {
			pageObjectDetails = {
				type: "online-orders",
				langKey: "online_orders",
				menuKey: "online_orders",
				userType: "customers",
				permission: "online_orders",
			};
		}

		return pageObjectDetails;
	});

	const setupTableColumns = () => {
		columns.value = [
			{
				title: t(`stock.invoice_number`),
				dataIndex: "invoice_number",
			},
			{
				title: t(`${pageObject.value.langKey}.${pageObject.value.langKey}_date`),
				dataIndex: "order_date",
			},
			{
				title: t(`${pageObject.value.langKey}.user`),
				dataIndex: "user_id",
			},
			{
				title: t("payments.paid_amount"),
				dataIndex: "paid_amount",
			},
			{
				title: t("payments.total_amount"),
				dataIndex: "total_amount",
			},
			{
				title: t(`${pageObject.value.langKey}.${pageObject.value.langKey}_status`),
				dataIndex: "order_status",
			},
			{
				title: t("payments.payment_status"),
				dataIndex: "payment_status",
			},
			{
				title: t("common.action"),
				dataIndex: "action",
			},
		];
	};

	const orderPaymentsColumns = [
		{
			title: t("payments.date"),
			dataIndex: "date",
		},
		{
			title: t("payments.amount"),
			dataIndex: "amount",
		},
		{
			title: t("payments.payment_mode"),
			dataIndex: "payment_mode_id",
		},
	];

	return {
		initData,
		initPaymentData,
		columns,
		hashableColumns,
		setupTableColumns,
		filterableColumns,
		pageObject,
		orderType,
		orderItemColumns,
		orderPaymentsColumns,
		orderItemDetailsColumns
	}
}

export default fields;