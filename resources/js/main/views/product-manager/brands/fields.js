import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "brands";
	const { t } = useI18n();

	const initData = {
		name: "",
		slug: "",
		image: undefined,
		image_url: undefined,
	};

	const columns = [
		{
			title: t("brand.name"),
			dataIndex: "name",
		},
		{
			title: t("brand.logo"),
			dataIndex: "image_url",
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
		filterableColumns
	}
}

export default fields;