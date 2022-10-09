import { reactive } from "vue";
import { useI18n } from "vue-i18n";

const fields = () => {
	const url = "roles?fields=id,xid,name,display_name,description,perms.limit(10000)";
	const addEditUrl = "roles";
	const { t } = useI18n();

	const preFetchData = reactive({
		permissions: [],
	});

	const initData = {
		name: "",
		display_name: "",
		description: "",
		permissions: [],
	};

	const columns = [
		{
			title: t("role.role_name"),
			dataIndex: "display_name",
			sorter: true,
		},
		{
			title: t("role.description"),
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
			value: t("role.role_name")
		},
	];

	const getPreFetchData = () => {
		axiosAdmin
			.get("permissions?fields=id,xid,name,display_name&limit=10000")
			.then((response) => {
				preFetchData.permissions = response.data;
			});
	};

	return {
		url,
		addEditUrl,
		initData,
		columns,
		filterableColumns,

		preFetchData,
		getPreFetchData
	}
}

export default fields;