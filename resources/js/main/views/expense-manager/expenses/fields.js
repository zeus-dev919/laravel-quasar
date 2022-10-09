import { reactive, ref } from 'vue';
import { useI18n } from "vue-i18n";

const fields = () => {
	const { t } = useI18n();
	const addEditUrl = "expenses";
	const url = ref("expenses?fields=id,xid,bill,bill_url,expense_category_id,x_expense_category_id,expenseCategory{id,xid,name},amount,user_id,x_user_id,user{id,xid,name},notes,date");
	const hashableColumns = ['user_id', 'expense_category_id'];

	const preFetchData = reactive({
		expenseCategories: [],
		staffMembers: [],
	});

	const initData = {
		expense_category_id: undefined,
		amount: "",
		bill: undefined,
		bill_url: undefined,
		date: undefined,
		user_id: undefined,
		notes: "",
	};

	const columns = [
		{
			title: t("expense.expense_category"),
			dataIndex: "expense_category_id",
		},
		{
			title: t("expense.amount"),
			dataIndex: "amount",
		},
		{
			title: t("expense.date"),
			dataIndex: "date",
		},
		{
			title: t("expense.created_by_user"),
			dataIndex: "user_id",
		},
		{
			title: t("common.action"),
			dataIndex: "action",
		},
	];

	const filters = reactive({
		expense_category_id: undefined,
		user_id: undefined,
	});

	const getPreFetchData = () => {
		const expenseCategoriesPromise = axiosAdmin.get(
			"expense-categories?limit=10000"
		);
		const staffMembersPromise = axiosAdmin.get(
			"users?limit=10000"
		);

		Promise.all([expenseCategoriesPromise, staffMembersPromise]).then(
			([expenseCategoriesResponse, staffMembersResponse]) => {
				preFetchData.expenseCategories =
					expenseCategoriesResponse.data;
				preFetchData.staffMembers = staffMembersResponse.data;
			}
		);
	};

	return {
		url,
		addEditUrl,
		initData,
		columns,
		filters,
		hashableColumns,

		preFetchData,
		getPreFetchData
	}
}

export default fields;