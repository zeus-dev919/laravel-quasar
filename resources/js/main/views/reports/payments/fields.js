import { useI18n } from "vue-i18n";

const fields = () => {
	const { t } = useI18n();
	const paymentsHashableColumns = ['user_id'];

	const paymentsColumns = [
		{
			title: t("payments.date"),
			dataIndex: "date",
		},
		{
			title: t("payments.payment_number"),
			dataIndex: "payment_number",
		},
		{
			title: t("payments.payment_type"),
			dataIndex: "payment_type",
		},
		{
			title: t("payments.user"),
			dataIndex: "user_id",
		},
		{
			title: t("payments.amount"),
			dataIndex: "amount",
		},
	];

	return {
		paymentsColumns,
		paymentsHashableColumns,
	}
}

export default fields;