import { useI18n } from "vue-i18n";

const fields = () => {
	const url = "taxes?fields=id,xid,name,rate";
	const addEditUrl = "taxes";
	const { t } = useI18n();

	const initData = {
		name: "",
		rate: "",
	};

	const columns = [
		{
			title: t("tax.name"),
			dataIndex: "name",
		},
		{
			title: t("tax.rate"),
			dataIndex: "rate",
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "name",
			value: t("tax.name")
		},
	];

	return {
		url,
		addEditUrl,
		initData,
		columns,
		filterableColumns
	}
}

export default fields;