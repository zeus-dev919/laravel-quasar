<template>
	<div class="setting-sidebar">
		<perfect-scrollbar
			:options="{
				wheelSpeed: 1,
				swipeEasing: true,
				suppressScrollX: true,
			}"
		>
			<a-menu v-model:selectedKeys="selectedKeys">
				<a-menu-item
					key="company"
					v-if="
						permsArray.includes('companies_edit') ||
						permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.company.index' })"
				>
					<template #icon>
						<LaptopOutlined />
					</template>
					{{ $t("menu.company") }}
				</a-menu-item>
				<a-menu-item
					key="profile"
					@click="$router.push({ name: 'admin.settings.profile.index' })"
				>
					<template #icon>
						<UserOutlined />
					</template>
					{{ $t("menu.profile") }}
				</a-menu-item>
				<a-menu-item
					key="translations"
					v-if="
						permsArray.includes('translations_view') ||
						permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.translations.index' })"
				>
					<template #icon>
						<TranslationOutlined />
					</template>
					{{ $t("menu.translations") }}
				</a-menu-item>
				<a-menu-item
					key="warehouses"
					v-if="
						permsArray.includes('warehouses_view') ||
						permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.warehouses.index' })"
				>
					<template #icon>
						<ShopOutlined />
					</template>
					{{ $t("menu.warehouses") }}
				</a-menu-item>
				<a-menu-item
					key="roles"
					v-if="
						permsArray.includes('roles_view') || permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.roles.index' })"
				>
					<template #icon>
						<SolutionOutlined />
					</template>
					{{ $t("menu.roles") }}
				</a-menu-item>
				<a-menu-item
					key="taxes"
					v-if="
						permsArray.includes('taxes_view') || permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.taxes.index' })"
				>
					<template #icon>
						<ScheduleOutlined />
					</template>
					{{ $t("menu.taxes") }}
				</a-menu-item>
				<a-menu-item
					key="currencies"
					v-if="
						permsArray.includes('currencies_view') ||
						permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.currencies.index' })"
				>
					<template #icon>
						<DollarOutlined />
					</template>
					{{ $t("menu.currencies") }}
				</a-menu-item>
				<a-menu-item
					key="payment_modes"
					v-if="
						permsArray.includes('payment_modes_view') ||
						permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.payment_modes.index' })"
				>
					<template #icon>
						<AccountBookOutlined />
					</template>
					{{ $t("menu.payment_modes") }}
				</a-menu-item>
				<a-menu-item
					key="units"
					v-if="
						permsArray.includes('units_view') || permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.units.index' })"
				>
					<template #icon>
						<ApartmentOutlined />
					</template>
					{{ $t("menu.units") }}
				</a-menu-item>
				<a-menu-item
					key="custom_fields"
					v-if="
						permsArray.includes('custom_fields_view') ||
						permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.custom_fields.index' })"
				>
					<template #icon>
						<FormOutlined />
					</template>
					{{ $t("menu.custom_fields") }}
				</a-menu-item>
				<a-menu-item
					key="modules"
					v-if="permsArray.includes('admin')"
					@click="$router.push({ name: 'admin.settings.modules.index' })"
				>
					<template #icon>
						<AppstoreAddOutlined />
					</template>
					{{ $t("menu.modules") }}
				</a-menu-item>
				<a-menu-item
					key="storage_settings"
					v-if="
						permsArray.includes('storage_edit') ||
						permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.storage.index' })"
				>
					<template #icon>
						<FolderOpenOutlined />
					</template>
					{{ $t("menu.storage_settings") }}
				</a-menu-item>
				<a-menu-item
					key="email_settings"
					v-if="
						permsArray.includes('email_edit') || permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.email.index' })"
				>
					<template #icon>
						<MailOutlined />
					</template>
					{{ $t("menu.email_settings") }}
				</a-menu-item>
				<a-menu-item
					key="update_app"
					v-if="
						permsArray.includes('update_app') || permsArray.includes('admin')
					"
					@click="$router.push({ name: 'admin.settings.update_app.index' })"
				>
					<template #icon>
						<HistoryOutlined />
					</template>
					{{ $t("menu.update_app") }}
				</a-menu-item>
			</a-menu>
		</perfect-scrollbar>
	</div>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from "vue";
import {
	LaptopOutlined,
	UserOutlined,
	TranslationOutlined,
	ShopOutlined,
	SolutionOutlined,
	ScheduleOutlined,
	DollarOutlined,
	AccountBookOutlined,
	AppstoreAddOutlined,
	ApartmentOutlined,
	FolderOpenOutlined,
	MailOutlined,
	HistoryOutlined,
	FormOutlined,
} from "@ant-design/icons-vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import common from "../../../common/composable/common";

export default defineComponent({
	components: {
		LaptopOutlined,
		UserOutlined,
		TranslationOutlined,
		ShopOutlined,
		SolutionOutlined,
		ScheduleOutlined,
		DollarOutlined,
		AccountBookOutlined,
		AppstoreAddOutlined,
		ApartmentOutlined,
		FolderOpenOutlined,
		MailOutlined,
		HistoryOutlined,
		FormOutlined,
	},
	setup() {
		const { appSetting, user, permsArray, appModules } = common();
		const route = useRoute();
		const store = useStore();
		const selectedKeys = ref([]);

		onMounted(() => {
			const menuKey =
				typeof route.meta.menuKey == "function"
					? route.meta.menuKey(route)
					: route.meta.menuKey;

			selectedKeys.value = [menuKey.replace("-", "_")];
		});

		watch(route, (newVal, oldVal) => {
			const menuKey =
				typeof newVal.meta.menuKey == "function"
					? newVal.meta.menuKey(newVal)
					: newVal.meta.menuKey;

			selectedKeys.value = [menuKey.replace("-", "_")];
		});

		return {
			permsArray,

			selectedKeys,
		};
	},
});
</script>

<style lang="less">
.setting-sidebar .ps {
	height: calc(100vh - 145px);
}
</style>
