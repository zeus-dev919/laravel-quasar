import { useI18n } from "vue-i18n";

const fields = () => {
	const { t } = useI18n();
	const transactionsHashableColumns = ['user_id'];

	const transactionsFilterableColumns = [
		{
			key: "name",
			value: t("user.name")
		},
	];

	const transactionsColumns = [
		{
			title: t("payments.date"),
			dataIndex: "date",
		},
		{
			title: t("payments.transaction_number"),
			dataIndex: "transaction_number",
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
		transactionsFilterableColumns,
		transactionsColumns,
		transactionsHashableColumns,
	}
}

export default fields;