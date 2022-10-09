import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";
import common from "../../../../common/composable/common";

const fields = () => {
	const addEditUrl = "payments";
	const { t } = useI18n();
	const route = useRoute();
	const hashableColumns = ['payment_mode_id', 'user_id'];
	const { dayjs } = common();
	const paymentType = route.meta.paymentType;
	const menuParent = route.meta.menuParent;

	const initData = {
		payment_type: paymentType,
		date: dayjs().format('YYYY-MM-DD'),
		amount: "",
		payment_mode_id: undefined,
		user_id: undefined,
		notes: "",
	};

	const columns = [
		{
			title: t("payments.date"),
			dataIndex: "date",
		},
		{
			title: t("payments.transaction_number"),
			dataIndex: "payment_number",
		},
		{
			title: t("payments.user"),
			dataIndex: "user_id",
		},
		{
			title: t("payments.amount"),
			dataIndex: "amount",
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "payment_number",
			value: t("payments.transaction_number")
		},
	];

	const settleInvoiceColumns = [
		{
			title: t("stock.invoice_number"),
			dataIndex: "invoice_number",
		},
		{
			title: t("common.date"),
			dataIndex: "date",
		},
		{
			title: t("payments.invoice_amount"),
			dataIndex: "amount",
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	return {
		addEditUrl,
		initData,
		columns,
		filterableColumns,
		hashableColumns,

		settleInvoiceColumns,
		paymentType,
		menuParent,
	}
}

export default fields;