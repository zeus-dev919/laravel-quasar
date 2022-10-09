const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js').vue();

mix.less('node_modules/ant-design-vue/dist/antd.less', 'public/css/antd.css', {
	lessOptions: {
		javascriptEnabled: true,
		modifyVars: {
			'font-family': '"Nunito", sans-serif',
			// 'primary-color': '#5254cf',
			// 'link-color': '#BD10E0',
			// 'border-radius-base': '2px',
		},
	}
});
// mix.less('node_modules/ant-design-vue/dist/antd.dark.less', 'public/css/antd.dark.css', {
// 	lessOptions: {
// 		javascriptEnabled: true,
// 	}
// });
mix.less('resources/less/app.less', 'public/css');
mix.css('resources/less/pos_invoice_css.css', 'public/css');