import moment from "moment";
import './axiosBase';
import './axiosAdmin';

const allModules = window.config.modules;
allModules.forEach((allModule) => {
	require(`../../../../Modules/${allModule}/Resources/assets/js/plugins`);
});

moment.suppressDeprecationWarnings = true;
window.moment = moment;