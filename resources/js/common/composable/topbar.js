import { computed, ref, onMounted, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";

const topbar = () => {
	const route = useRoute();
	const { t } = useI18n();

	const headerTitle = ref("");
	const breadcrumbRoutes = ref([]);

	onMounted(() => {
		headerTitle.value = getTitle(route);
		breadcrumbRoutes.value = getbBreadcrumbRoutes(route);
	});

	const getTitle = (newVal) => {
		let menuKey =
			typeof newVal.meta.menuKey == "function"
				? newVal.meta.menuKey(newVal)
				: newVal.meta.menuKey;

		menuKey = menuKey.replace("-", "_")

		return t(`menu.${menuKey}`);
	}

	const getbBreadcrumbRoutes = (newVal) => {
		let newRoutes = [
			{
				to: 'admin.dashboard.index',
				key: 'dashboard',
				breadcrumbName: t('menu.dashboard')
			}
		];

		// Parent 
		let parentKey =
			typeof newVal.meta.menuParent == "function"
				? newVal.meta.menuParent(newVal)
				: newVal.meta.menuParent;
		parentKey = parentKey.replace("-", "_");

		if (menuKey != 'dashboard') {
			newRoutes.push({
				key: parentKey,
				breadcrumbName: t(`menu.${parentKey}`)
			});
		}

		// Child
		let menuKey =
			typeof newVal.meta.menuKey == "function"
				? newVal.meta.menuKey(newVal)
				: newVal.meta.menuKey;
		menuKey = menuKey.replace("-", "_");

		if (menuKey != 'dashboard') {
			newRoutes.push({
				key: menuKey,
				breadcrumbName: t(`menu.${menuKey}`)
			});
		}

		return newRoutes;
	}

	watch(route, (newVal, oldVal) => {
		headerTitle.value = getTitle(newVal);
		breadcrumbRoutes.value = getbBreadcrumbRoutes(newVal);
	});

	return {
		headerTitle,
		breadcrumbRoutes,
	};
}

export default topbar;