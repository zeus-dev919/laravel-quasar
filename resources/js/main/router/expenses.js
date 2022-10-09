import Admin from '../../common/layouts/Admin.vue';
import ExpenseCategories from '../views/expense-manager/expense-categories/index.vue';
import Expenses from '../views/expense-manager/expenses/index.vue';

export default [
	{
		path: '/',
		component: Admin,
		children: [
			{
				path: '/admin/expense-categories',
				component: ExpenseCategories,
				name: 'admin.expense_categories.index',
				meta: {
					requireAuth: true,
					menuParent: "expense_manager",
					menuKey: route => "expense_categories",
					permission: "expense_categories_view",
				}
			},
			{
				path: '/admin/expenses',
				component: Expenses,
				name: 'admin.expenses.index',
				meta: {
					requireAuth: true,
					menuParent: "expense_manager",
					menuKey: route => "expenses",
					permission: "expenses_view",
				}
			}
		]

	}
]