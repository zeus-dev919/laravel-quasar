import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "categories";
	const { t } = useI18n();
	const hashableColumns = ['parent_id'];

	const initData = {
		name: "",
		slug: "",
		image: undefined,
		image_url: undefined,
		parent_id: null,
	};

	const columns = [
		{
			title: t("category.name"),
			dataIndex: "name",
		},
		{
			title: t("category.logo"),
			dataIndex: "image_url",
		},

		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	return {
		hashableColumns,
		addEditUrl,
		initData,
		columns,
	}
}

export default fields;