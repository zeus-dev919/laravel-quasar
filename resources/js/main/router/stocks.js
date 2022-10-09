import Admin from '../../common/layouts/Admin.vue';
import Create from '../views/stock-management/purchases/Create.vue';
import Edit from '../views/stock-management/purchases/Edit.vue';
import Purchases from '../views/stock-management/purchases/index.vue';

import Pos from "../views/stock-management/pos/index.vue";
import StockAdjustment from "../views/stock-management/adjustment/index.vue";

import Payment from "../views/stock-management/payments/index.vue";

export default [
	{
		path: '/admin/stock/pos',
		component: Pos,
		name: 'admin.pos.index',
		meta: {
			requireAuth: true,
			menuParent: "pos",
			menuKey: "pos",
			permission: "pos_view",
		}
	},
	{
		path: '/admin/stock/',
		component: Admin,
		children: [
			{
				path: 'adjustment',
				component: StockAdjustment,
				name: 'admin.stock_adjustment.index',
				meta: {
					requireAuth: true,
					menuParent: "stock_adjustment",
					menuKey: "stock_adjustment",
					permission: "stock_adjustments_view",
				}
			},
			// Purchases
			{
				path: 'purchases/edit/:id',
				component: Edit,
				name: 'admin.stock.purchases.edit',
				meta: {
					requireAuth: true,
					menuParent: "purchases",
					menuKey: route => 'purchases',
					permission: route => 'purchases_edit',
					orderType: "purchases"
				}
			},
			{
				path: 'purchases/create',
				component: Create,
				name: 'admin.stock.purchases.create',
				meta: {
					requireAuth: true,
					menuParent: "purchases",
					menuKey: route => "purchases",
					permission: route => 'purchases_create',
					orderType: "purchases"
				}
			},
			{
				path: 'purchases',
				component: Purchases,
				name: 'admin.stock.purchases.index',
				meta: {
					requireAuth: true,
					menuParent: "purchases",
					menuKey: route => "purchases",
					permission: route => 'purchases_view',
					orderType: "purchases"
				}
			},

			// Purchase Returns
			{
				path: 'purchase-returns/edit/:id',
				component: Edit,
				name: 'admin.stock.purchase-returns.edit',
				meta: {
					requireAuth: true,
					menuParent: "purchases",
					menuKey: route => 'purchase_returns',
					permission: route => 'purchase_returns_edit',
					orderType: "purchase-returns"
				}
			},
			{
				path: 'purchase-returns/create',
				component: Create,
				name: 'admin.stock.purchase-returns.create',
				meta: {
					requireAuth: true,
					menuParent: "purchases",
					menuKey: route => "purchase_returns",
					permission: route => 'purchase_returns_create',
					orderType: "purchase-returns"
				}
			},
			{
				path: 'purchase-returns',
				component: Purchases,
				name: 'admin.stock.purchase-returns.index',
				meta: {
					requireAuth: true,
					menuParent: "purchases",
					menuKey: route => "purchase_returns",
					permission: route => 'purchase_returns_view',
					orderType: "purchase-returns"
				}
			},

			// Sales
			{
				path: 'sales/edit/:id',
				component: Edit,
				name: 'admin.stock.sales.edit',
				meta: {
					requireAuth: true,
					menuParent: "sales",
					menuKey: route => 'sales',
					permission: route => 'sales_edit',
					orderType: "sales"
				}
			},
			{
				path: 'sales/create',
				component: Create,
				name: 'admin.stock.sales.create',
				meta: {
					requireAuth: true,
					menuParent: "sales",
					menuKey: route => "sales",
					permission: route => 'sales_create',
					orderType: "sales"
				}
			},
			{
				path: 'sales',
				component: Purchases,
				name: 'admin.stock.sales.index',
				meta: {
					requireAuth: true,
					menuParent: "sales",
					menuKey: route => "sales",
					permission: route => 'sales_view',
					orderType: "sales"
				}
			},

			// Sales Returns 
			{
				path: 'sales-returns/edit/:id',
				component: Edit,
				name: 'admin.stock.sales-returns.edit',
				meta: {
					requireAuth: true,
					menuParent: "sales",
					menuKey: route => 'sales_returns',
					permission: route => 'sales_returns_edit',
					orderType: "sales-returns"
				}
			},
			{
				path: 'sales-returns/create',
				component: Create,
				name: 'admin.stock.sales-returns.create',
				meta: {
					requireAuth: true,
					menuParent: "sales",
					menuKey: route => "sales_returns",
					permission: route => 'sales_returns_create',
					orderType: "sales-returns"
				}
			},
			{
				path: 'sales-returns',
				component: Purchases,
				name: 'admin.stock.sales-returns.index',
				meta: {
					requireAuth: true,
					menuParent: "sales",
					menuKey: route => "sales_returns",
					permission: route => 'sales_returns_view',
					orderType: "sales-returns"
				}
			},
		]
	},
	{
		path: '/admin/payment/',
		component: Admin,
		children: [
			{
				path: 'in',
				component: Payment,
				name: 'admin.payments.in',
				meta: {
					requireAuth: true,
					menuParent: "sales",
					menuKey: "payment_in",
					permission: "payment_in",
					paymentType: "in",
				}
			},
			{
				path: 'out',
				component: Payment,
				name: 'admin.payments.out',
				meta: {
					requireAuth: true,
					menuParent: "purchases",
					menuKey: "payment_out",
					permission: "payment_out",
					paymentType: "out",
				}
			},
		]
	}
]