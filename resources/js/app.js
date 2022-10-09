require('./common/plugins');

import { createApp } from 'vue';
import Antd from 'ant-design-vue';
import PerfectScrollbar from 'vue3-perfect-scrollbar';
import App from './main/views/App.vue';
import routes from './main/router'
import store from './main/store';
import { setupI18n, loadLocaleMessages } from './common/i18n';

import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css';
import VueBarcode from '@chenfengyuan/vue-barcode';

async function bootstrap() {
	if (store.getters["auth/isLoggedIn"]) {
		store.dispatch("auth/updateUser");
	}

	store.dispatch("auth/updateApp");
	store.dispatch("auth/updateAllLangs");
	store.dispatch("auth/updateAllWarehouses");
	store.commit("auth/updateActiveModules", window.config.modules);

	const app = createApp(App);

	const i18n = setupI18n({ legacy: false, globalInjection: true, locale: store.state.auth.lang, warnHtmlMessage: false });
	await loadLocaleMessages(i18n, store.state.auth.lang);

	// app.config.devtools = true;
	// app.config.debug = true;

	app.use(i18n);
	app.use(Antd);
	app.use(PerfectScrollbar)
	app.use(store);
	app.use(routes);

	app.component(VueBarcode.name, VueBarcode);
	const allModules = window.config.installed_modules;
	allModules.forEach((allModule) => {
		const moduleName = allModule.verified_name;
		const moduleMenu = require(`../../Modules/${moduleName}/Resources/assets/js/views/menu/admin.vue`)
			.default;

		app.component(moduleName + 'Menu', moduleMenu);
	});



	window.i18n = i18n;

	app.mount("#app");
}

bootstrap();



