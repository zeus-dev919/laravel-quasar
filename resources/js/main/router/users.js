import Admin from '../../common/layouts/Admin.vue';
import UserIndex from '../views/users/index.vue';

export default [
	{
		path: '/',
		component: Admin,
		children: [
			{
				path: '/admin/users',
				component: UserIndex,
				name: 'admin.users.index',
				meta: {
					requireAuth: true,
					menuParent: "users",
					menuKey: "users",
					permission: "users_view"
				}
			},
			{
				path: '/admin/customers',
				component: UserIndex,
				name: 'admin.customers.index',
				meta: {
					requireAuth: true,
					menuParent: "parties",
					menuKey: "customers",
					permission: "customers_view"
				}
			},
			{
				path: '/admin/suppliers',
				component: UserIndex,
				name: 'admin.suppliers.index',
				meta: {
					requireAuth: true,
					menuParent: "parties",
					menuKey: "suppliers",
					permission: "suppliers_view"
				}
			}
		]

	}
]