import { nextTick } from 'vue';
import { createI18n } from 'vue-i18n';

export function setupI18n(options = { locale: 'en', warnHtmlMessage: false }) {
	const i18n = createI18n(options)
	setI18nLanguage(i18n, options.locale)
	return i18n
}

export function setI18nLanguage(i18n, locale) {
	if (i18n.mode === 'legacy') {
		i18n.global.locale = locale
	} else {
		i18n.global.locale.value = locale
	}
	/**
	 * NOTE:
	 * If you need to specify the language setting for headers, such as the `fetch` API, set it here.
	 * The following is an example for axios.
	 *
	 * axios.defaults.headers.common['Accept-Language'] = locale
	 */
	document.querySelector('html').setAttribute('lang', locale)
}

export async function loadLocaleMessages(i18n, locale) {
	const res = await axiosBase.get('lang-trans');

	setLangsLocaleMessage(res, i18n, locale);
}

export function setLangsLocaleMessage(res, i18n, locale) {
	const messages = {};

	res.data.data.map((lang) => {
		messages[lang.key] = {};

		lang.translations.map((translation) => {
			if (messages[lang.key][translation.group] == undefined) {
				messages[lang.key][translation.group] = {};
			}
			messages[lang.key][translation.group][translation.key] = translation.value;
		});
	});

	// set locale and locale message
	i18n.global.setLocaleMessage(locale, messages[locale])

	return nextTick()
}