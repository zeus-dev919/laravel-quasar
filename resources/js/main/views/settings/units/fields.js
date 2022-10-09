import { useI18n } from "vue-i18n";

const fields = () => {
	const url = "units?fields=id,xid,name,short_name,operator,operator_value,is_deletable";
	const addEditUrl = "units";
	const { t } = useI18n();

	const initData = {
		name: "",
		short_name: "",
		parent_id: null,
		operator: "multiply",
		operator_value: 1,
	};

	const columns = [
		{
			title: t("unit.name"),
			dataIndex: "name",
		},
		{
			title: t("unit.short_name"),
			dataIndex: "short_name",
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "name",
			value: t("unit.name")
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