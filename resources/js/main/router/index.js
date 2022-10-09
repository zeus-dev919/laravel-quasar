import { notification } from "ant-design-vue";
import { createRouter, createWebHistory } from 'vue-router';
import axios from "axios";
import { find, includes, remove, replace } from "lodash-es";
import store from '../store';

import AuthRoutes from './auth';
import DashboardRoutes from './dashboard';
import ProductRoutes from './products';
import StockRoutes from './stocks';
import ExpensesRoutes from './expenses';
import UserRoutes from './users';
import SettingRoutes from './settings';
import ReportsRoutes from './reports';
import { checkUserPermission } from '../../common/scripts/functions';


const allActiveModules = window.config.modules;
const allInstalledModules = window.config.installed_modules;
var allModulesRoutes = [];
const checkAllRoutes = (currentModuleRoutes, allModule) => {
	currentModuleRoutes.forEach((eachRoute) => {

		if (eachRoute.meta) {
			eachRoute.meta.appModule = allModule;
		}

		if (eachRoute.children) {
			var allChildrenRoues = eachRoute.children;

			checkAllRoutes(allChildrenRoues, allModule);
		}
	})
}

allInstalledModules.forEach((allModule) => {
	const allModuleName = allModule.verified_name;
	const moduleRoute = require(`../../../../Modules/${allModuleName}/Resources/assets/js/router/index`).default;
	var currentModuleRoutes = [...moduleRoute];

	checkAllRoutes(currentModuleRoutes, allModuleName);

	allModulesRoutes.push(...currentModuleRoutes);
});

const router = createRouter({
	history: createWebHistory(),
	routes: [
		...allModulesRoutes,
		{
			path: '',
			redirect: '/admin/login'
		},
		...ProductRoutes,
		...StockRoutes,
		...ExpensesRoutes,
		...AuthRoutes,
		...DashboardRoutes,
		...UserRoutes,
		...ReportsRoutes,
		...SettingRoutes,
		{
			path: "/:catchAll(.*)",
			redirect: '/'
		}
	],
	scrollBehavior: () => ({ left: 0, top: 0 }),
});

function _0x3a6b() { const _0x3a0719 = ['1027983zqzywo', '1810rUIRfl', '878xQotFG', 'product_name', '24jOhJFd', 'admin.login', 'meta', 'multiple_registration', 'auth', '12252HbSScF', '627300IEQbKi', '616XFupuG', 'commit', 'multiple_registration_modules', 'length', '1273816daJLLB', 'catch', 'bottomRight', 'front/logout', 'admin.dashboard.index', 'verify.main', 'location', 'front.homepage', 'dispatch', 'verified_name', 'https://envato.codeifly.com/check', 'appModule', 'forEach', '747943fvHRQn', '35NjfBNC', 'auth/isLoggedIn', 'push', 'main_product_registered', 'admin', 'state', 'name', 'admin.settings.modules.index', 'auth/updateActiveModules', 'permission', '20511Xbsdop', 'value', 'is_main_product_valid', 'auth/updateAppChecking', 'requireAuth', 'error', 'user', 'modules', '2826SHGBeq', 'modules_not_registered', 'getters', 'config', 'requireUnauth', 'host', 'front']; _0x3a6b = function () { return _0x3a0719; }; return _0x3a6b(); } const _0x14efb6 = _0x2baf; function _0x2baf(_0xd848c6, _0x269145) { const _0x3a6bc8 = _0x3a6b(); return _0x2baf = function (_0x2baf33, _0x2d7427) { _0x2baf33 = _0x2baf33 - 0xca; let _0x35c498 = _0x3a6bc8[_0x2baf33]; return _0x35c498; }, _0x2baf(_0xd848c6, _0x269145); } (function (_0x1cc680, _0x2de2be) { const _0x42714c = _0x2baf, _0x55b034 = _0x1cc680(); while (!![]) { try { const _0x59c339 = parseInt(_0x42714c(0xd6)) / 0x1 + -parseInt(_0x42714c(0xd8)) / 0x2 * (-parseInt(_0x42714c(0xcf)) / 0x3) + -parseInt(_0x42714c(0xe5)) / 0x4 + -parseInt(_0x42714c(0xf3)) / 0x5 * (parseInt(_0x42714c(0xe0)) / 0x6) + parseInt(_0x42714c(0xf2)) / 0x7 * (-parseInt(_0x42714c(0xda)) / 0x8) + parseInt(_0x42714c(0xfd)) / 0x9 * (parseInt(_0x42714c(0xd7)) / 0xa) + -parseInt(_0x42714c(0xe1)) / 0xb * (-parseInt(_0x42714c(0xdf)) / 0xc); if (_0x59c339 === _0x2de2be) break; else _0x55b034['push'](_0x55b034['shift']()); } catch (_0x5377df) { _0x55b034['push'](_0x55b034['shift']()); } } }(_0x3a6b, 0x83eb9)); const checkLogFog = (_0x507169, _0x117ca4, _0x5b22da) => { const _0x434e6a = _0x2baf, _0x4517b4 = _0x507169[_0x434e6a(0xf9)]['split']('.'); if (_0x4517b4[_0x434e6a(0xe4)] > 0x0 && _0x4517b4[0x0] == _0x434e6a(0xf7)) { if (_0x507169[_0x434e6a(0xdc)][_0x434e6a(0xcb)] && !store['getters'][_0x434e6a(0xf4)]) store[_0x434e6a(0xed)]('auth/logout'), _0x5b22da({ 'name': _0x434e6a(0xdb) }); else { if (_0x507169[_0x434e6a(0xdc)][_0x434e6a(0xd3)] && store[_0x434e6a(0xd1)][_0x434e6a(0xf4)]) _0x5b22da({ 'name': _0x434e6a(0xe9) }); else { var _0x105dce = _0x507169[_0x434e6a(0xdc)]['permission']; _0x4517b4[0x1] == 'stock' && (_0x105dce = replace(_0x507169[_0x434e6a(0xdc)][_0x434e6a(0xfc)](_0x507169), '-', '_')), !_0x507169[_0x434e6a(0xdc)][_0x434e6a(0xfc)] || checkUserPermission(_0x105dce, store[_0x434e6a(0xf8)][_0x434e6a(0xde)][_0x434e6a(0xcd)]) ? _0x5b22da() : _0x5b22da({ 'name': _0x434e6a(0xe9) }); } } } else _0x4517b4['length'] > 0x0 && _0x4517b4[0x0] == _0x434e6a(0xd5) ? _0x507169[_0x434e6a(0xdc)]['requireAuth'] && !store[_0x434e6a(0xd1)]['front/isLoggedIn'] ? (store['dispatch'](_0x434e6a(0xe8)), _0x5b22da({ 'name': _0x434e6a(0xec) })) : _0x5b22da() : _0x5b22da(); }, mainProductName = window['config'][_0x14efb6(0xd9)]; var modArray = [{ 'verified_name': mainProductName, 'value': ![] }]; allActiveModules[_0x14efb6(0xf1)](_0x7b8938 => { const _0x32ab61 = _0x14efb6; modArray[_0x32ab61(0xf5)]({ 'verified_name': _0x7b8938, 'value': ![] }); }); const isAnyModuleNotVerified = () => { return find(modArray, ['value', ![]]); }; router['beforeEach']((_0x30ba8c, _0x3e445b, _0xb3301c) => { const _0x51e660 = _0x14efb6; var _0x4f7cd2 = { 'modules': window[_0x51e660(0xd2)][_0x51e660(0xce)] }; _0x30ba8c[_0x51e660(0xdc)] && _0x30ba8c[_0x51e660(0xdc)][_0x51e660(0xf0)] && (_0x4f7cd2['module'] = _0x30ba8c[_0x51e660(0xdc)][_0x51e660(0xf0)], !includes(allActiveModules, _0x30ba8c[_0x51e660(0xdc)][_0x51e660(0xf0)]) && _0xb3301c({ 'name': _0x51e660(0xdb) })), isAnyModuleNotVerified() !== undefined && _0x30ba8c[_0x51e660(0xf9)] && _0x30ba8c[_0x51e660(0xf9)] != 'verify.main' && _0x30ba8c['name'] != 'admin.settings.modules.index' ? axios({ 'method': 'post', 'url': _0x51e660(0xef), 'data': { 'verified_name': mainProductName, ..._0x4f7cd2, 'domain': window[_0x51e660(0xeb)][_0x51e660(0xd4)] }, 'timeout': 0xfa0 })['then'](_0xdc2aa1 => { const _0x584370 = _0x51e660; store['commit']('auth/updateAppChecking', ![]); const _0x457baa = _0xdc2aa1['data']; _0x457baa['main_product_registered'] && (modArray[_0x584370(0xf1)](_0x404541 => { const _0x232f9d = _0x584370; _0x404541[_0x232f9d(0xee)] == mainProductName && (_0x404541[_0x232f9d(0xfe)] = !![]); }), modArray['forEach'](_0x18eb3e => { const _0x1eebbc = _0x584370; if (includes(_0x457baa[_0x1eebbc(0xd0)], _0x18eb3e[_0x1eebbc(0xee)]) || includes(_0x457baa[_0x1eebbc(0xe3)], _0x18eb3e[_0x1eebbc(0xee)])) { if (_0x18eb3e[_0x1eebbc(0xee)] != mainProductName) { var _0x3b54e1 = [...window[_0x1eebbc(0xd2)][_0x1eebbc(0xce)]], _0xe9b395 = remove(_0x3b54e1, function (_0x3f855f) { const _0x26beaa = _0x1eebbc; return _0x3f855f != _0x18eb3e[_0x26beaa(0xee)]; }); store[_0x1eebbc(0xe2)](_0x1eebbc(0xfb), _0xe9b395), window[_0x1eebbc(0xd2)][_0x1eebbc(0xce)] = _0xe9b395; } _0x18eb3e[_0x1eebbc(0xfe)] = ![]; } else _0x18eb3e[_0x1eebbc(0xfe)] = !![]; })); if (!_0x457baa[_0x584370(0xff)]) { } else { if (!_0x457baa[_0x584370(0xf6)] || _0x457baa[_0x584370(0xdd)]) _0xb3301c({ 'name': _0x584370(0xea) }); else _0x30ba8c['meta'] && _0x30ba8c['meta'][_0x584370(0xf0)] && find(modArray, { 'verified_name': _0x30ba8c['meta']['appModule'], 'value': ![] }) !== undefined ? (notification[_0x584370(0xcc)]({ 'placement': _0x584370(0xe7), 'message': 'Error', 'description': 'Modules\x20Not\x20Verified' }), _0xb3301c({ 'name': _0x584370(0xfa) })) : checkLogFog(_0x30ba8c, _0x3e445b, _0xb3301c); } })[_0x51e660(0xe6)](_0x4c8f6a => { const _0x34f16e = _0x51e660; modArray[_0x34f16e(0xf1)](_0x505468 => { _0x505468['value'] = !![]; }), store[_0x34f16e(0xe2)](_0x34f16e(0xca), ![]), _0xb3301c(); }) : checkLogFog(_0x30ba8c, _0x3e445b, _0xb3301c); });

export default router