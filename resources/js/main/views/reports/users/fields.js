import { ref, computed } from "vue";
import { useI18n } from "vue-i18n";

const fields = () => {
	const userType = ref("customers");
	const { t } = useI18n();

	const columns = computed(() => {
		return [
			{
				title: t("user.name"),
				dataIndex: "name",
				key: "name",
				sorter: true,
			},
			{
				title: userType.value == "customers" ? t("menu.sales") : t("menu.purchases"),
				children: [
					{
						title: t("menu.purchases"),
						dataIndex: "user_details.purchase_order_count",
						sorter: true,
					},
					{
						title: t("menu.purchase_returns"),
						dataIndex: "user_details.purchase_return_count",
						sorter: true,
					},
					{
						title: t("menu.sales"),
						dataIndex: "user_details.sales_order_count",
						sorter: true,
					},
					{
						title: t("menu.sales_returns"),
						dataIndex: "user_details.sales_return_count",
						sorter: true,
					},
				]
			},
			{
				title: t("payments.total_amount"),
				dataIndex: "user_details.total_amount",
				sorter: true,
			},
			{
				title: t("payments.paid_amount"),
				dataIndex: "user_details.paid_amount",
				sorter: true,
			},
			{
				title: t("payments.due_amount"),
				dataIndex: "user_details.due_amount",
				sorter: true,
			},
		]
	});

	const filterableColumns = [
		{
			key: "name",
			value: t("common.name")
		},
		{
			key: "phone",
			value: t("user.phone")
		},
	];

	return {
		userType,
		columns,
		filterableColumns
	}
}

export default fields;