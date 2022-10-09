import { useI18n } from "vue-i18n";

const fields = () => {
	const url = "custom-fields?fields=id,xid,name,value,type";
	const addEditUrl = "custom-fields";
	const { t } = useI18n();

	const initData = {
		name: "",
		value: "",
		type: "text",
	};

	const columns = [
		{
			title: t("custom_field.name"),
			dataIndex: "name",
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "name",
			value: t("custom_field.name")
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