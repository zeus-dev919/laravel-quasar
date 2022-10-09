import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "expense-categories";
	const { t } = useI18n();

	const initData = {
		name: "",
		description: "",
	};

	const columns = [
		{
			title: t("expense_category.name"),
			dataIndex: "name",
		},
		{
			title: t("expense_category.description"),
			dataIndex: "description",
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "name",
			value: t("common.name")
		},
	];

	return {
		addEditUrl,
		initData,
		columns,
		filterableColumns
	}
}

export default fields;