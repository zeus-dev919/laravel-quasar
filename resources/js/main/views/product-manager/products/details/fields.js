import { useI18n } from "vue-i18n";

const fields = () => {
	const { t } = useI18n();
	const stockHistoryHashableColumns = ['product_id'];
	const orderItemsHashableColumns = ['product_id'];

	const productOrderColumns = [
		{
			title: t("stock.order_date"),
			dataIndex: "order_date",
		},
		{
			title: t("stock.order_type"),
			dataIndex: "order_type",
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

	const stockHistoryColumns = [
		{
			title: t("common.date"),
			dataIndex: "created_at",
		},
		{
			title: t("stock.order_type"),
			dataIndex: "order_type",
		},
		{
			title: t("product.quantity"),
			dataIndex: "quantity",
		},
		{
			title: t("product.stocks"),
			dataIndex: "stock_type",
		},
		{
			title: t("stock.remarks"),
			dataIndex: "action_type",
		},
	];


	return {
		productOrderColumns,
		stockHistoryColumns,
		stockHistoryHashableColumns,
		orderItemsHashableColumns,
	}
}

export default fields;