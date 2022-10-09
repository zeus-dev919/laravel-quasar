import { createStore } from 'vuex';
import auth from './auth';

const allModules = window.config.modules;
var allModulesStores = {};
allModules.forEach((allModule) => {
	if (allModule) {
		const moduleStore = require(`../../../../Modules/${allModule}/Resources/assets/js/store/index`).default;

		allModulesStores = { ...allModulesStores, ...moduleStore };
	}
});

export default createStore({
	modules: {
		auth,
		...allModulesStores
	}
})