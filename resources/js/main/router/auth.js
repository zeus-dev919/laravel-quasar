import Login from '../views/auth/Login.vue';
import Verify from '../views/auth/Verify.vue';

export default [
	{
		path: '/admin/login',
		component: Login,
		name: 'admin.login',
		meta: {
			requireUnauth: true,
			menuKey: route => "login",
		}
	},
	{
		path: '/admin/verify',
		component: Verify,
		name: 'verify.main',
		meta: {
			requireUnauth: true,
			menuKey: route => "verify_product",
		}
	},
]