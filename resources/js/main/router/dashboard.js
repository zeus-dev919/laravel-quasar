import Admin from '../../common/layouts/Admin.vue';
import Dashboard from '../views/Dashboard.vue';

export default [
	{
		path: '/',
		component: Admin,
		children: [
			{
				path: '/admin/dashboard',
				component: Dashboard,
				name: 'admin.dashboard.index',
				meta: {
					requireAuth: true,
					menuParent: "dashboard",
					menuKey: route => "dashboard",
				}
			}
		]

	}
]