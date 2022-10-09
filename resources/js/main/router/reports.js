import Admin from '../../common/layouts/Admin.vue';
import Payments from '../views/reports/payments/index.vue';
import StockAlert from '../views/reports/stock-alert/index.vue';
import Users from '../views/reports/users/index.vue';
import CashBank from '../views/reports/cash-bank/index.vue';

export default [
	{
		path: '/admin/reports/',
		component: Admin,
		children: [
			{
				path: 'payments',
				component: Payments,
				name: 'admin.reports.payments.index',
				meta: {
					requireAuth: true,
					menuParent: "reports",
					menuKey: "payments",
				}
			},
			{
				path: 'stock-alert',
				component: StockAlert,
				name: 'admin.reports.stock.index',
				meta: {
					requireAuth: true,
					menuParent: "reports",
					menuKey: "stock_alert",
				}
			},
			{
				path: 'users',
				component: Users,
				name: 'admin.reports.users.index',
				meta: {
					requireAuth: true,
					menuParent: "reports",
					menuKey: "users_reports",
				}
			},
			{
				path: 'cash-bank',
				component: CashBank,
				name: 'admin.reports.cash_bank.index',
				meta: {
					requireAuth: true,
					menuParent: "cash_bank",
					menuKey: "cash_bank",
				}
			},
		]

	}
]