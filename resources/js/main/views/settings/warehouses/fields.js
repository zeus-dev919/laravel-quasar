import { ref } from "vue";
import { useI18n } from "vue-i18n";

const fields = () => {
	const addEditUrl = "warehouses";
	const url = "warehouses?fields=id,xid,logo,logo_url,name,email,phone,address,show_email_on_invoice,show_phone_on_invoice,terms_condition,bank_details,signature,signature_url";
	const { t } = useI18n();

	const initData = {
		name: "",
		email: "",
		phone: "",
		logo: undefined,
		logo_url: undefined,
		show_email_on_invoice: 0,
		show_phone_on_invoice: 0,
		address: "",
		terms_condition: `1. Goods once sold will not be taken back or exchanged 
2. All disputes are subject to [ENTER_YOUR_CITY_NAME] jurisdiction only`,
		bank_details: "",
		signature: undefined,
		signature_url: undefined,
	};

	const columns = [
		{
			title: t("warehouse.logo"),
			dataIndex: "logo",
		},
		{
			title: t("warehouse.name"),
			dataIndex: "name",
			sorter: true,
		},
		{
			title: t("warehouse.email"),
			dataIndex: "email",
			sorter: true,
		},
		{
			title: t("warehouse.phone"),
			dataIndex: "phone",
		},

		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filterableColumns = [
		{
			key: "name",
			value: t("warehouse.name")
		},
		{
			key: "email",
			value: t("warehouse.email")
		},
		{
			key: "phone",
			value: t("warehouse.phone")
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