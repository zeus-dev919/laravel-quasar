import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "products";
	const { t } = useI18n();
	const hashableColumns = ['unit_id', 'category_id', 'brand_id', 'tax_id'];
	const multiDimensalObjectColumns = {
		'stock_quantitiy_alert': 'details.stock_quantitiy_alert',
		'mrp': 'details.mrp',
		'purchase_price': 'details.purchase_price',
		'sales_price': 'details.sales_price',
		'tax_id': 'details.x_tax_id',
		'purchase_tax_type': 'details.purchase_tax_type',
		'sales_tax_type': 'details.sales_tax_type',
		'opening_stock': 'details.opening_stock',
		'opening_stock_date': 'details.opening_stock_date',
		'wholesale_price': 'details.wholesale_price',
		'wholesale_quantity': 'details.wholesale_quantity',
	};

	const initData = {
		name: "",
		slug: "",
		image: undefined,
		image_url: undefined,
		barcode_symbology: "CODE128",
		item_code: "",
		stock_quantitiy_alert: null,
		category_id: undefined,
		brand_id: undefined,
		mrp: 0,
		purchase_price: 0,
		sales_price: 0,
		tax_id: undefined,
		unit_id: undefined,
		description: "",
		purchase_tax_type: 'exclusive',
		sales_tax_type: 'exclusive',
		custom_fields: [],
		opening_stock: undefined,
		opening_stock_date: undefined,
		wholesale_price: undefined,
		wholesale_quantity: undefined,
	};

	const columns = [
		{
			title: '',
			dataIndex: "status",
		},
		{
			title: t("product.product"),
			dataIndex: "name",
		},
		{
			title: t("product.category"),
			dataIndex: "category_id",
		},
		{
			title: t("product.brand"),
			dataIndex: "brand_id",
		},
		{
			title: t("product.sales_price"),
			dataIndex: "sales_price",
		},
		{
			title: t("product.purchase_price"),
			dataIndex: "purchase_price",
		},
		{
			title: t("product.current_stock"),
			dataIndex: "current_stock",
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "name",
			value: t("brand.name")
		},
	];

	return {
		addEditUrl,
		initData,
		columns,
		filterableColumns,
		hashableColumns,
		multiDimensalObjectColumns,
	}
}

export default fields;