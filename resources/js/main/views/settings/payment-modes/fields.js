import { useI18n } from "vue-i18n";

const fields = () => {
	const url = "payment-modes?fields=id,xid,name";
	const addEditUrl = "payment-modes";
	const { t } = useI18n();

	const initData = {
		name: "",
	};

	const columns = [
		{
			title: t("payment_mode.name"),
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
			value: t("payment_mode.name")
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