<template>
	<a-menu
		:theme="appSetting.left_sidebar_theme"
		:openKeys="openKeys"
		v-model:selectedKeys="selectedKeys"
		:mode="mode"
		@openChange="onOpenChange"
		:style="{ borderRight: 'none' }"
	>
		<a-menu-item
			@click="
				() => {
					menuSelected();
					$router.push({ name: 'admin.dashboard.index' });
				}
			"
			key="dashboard"
		>
			<HomeOutlined />
			<span>{{ $t("menu.dashboard") }}</span>
		</a-menu-item>

		<a-sub-menu
			key="product_manager"
			v-if="
				permsArray.includes('brands_view') ||
				permsArray.includes('categories_view') ||
				permsArray.includes('products_view') ||
				permsArray.includes('admin')
			"
		>
			<template #title>
				<span>
					<AppstoreOutlined />
					<span>{{ $t("menu.product_manager") }}</span>
				</span>
			</template>
			<a-menu-item
				@click="
					() => {
						menuSelected();
						$router.push({ name: 'admin.brands.index' });
					}
				"
				key="brands"
				v-if="permsArray.includes('brands_view') || permsArray.includes('admin')"
			>
				{{ $t("menu.brands") }}
			</a-menu-item>
			<a-menu-item
				@click="
					() => {
						menuSelected();
						$router.push({ name: 'admin.categories.index' });
					}
				"
				key="categories"
				v-if="
					permsArray.includes('categories_view') || permsArray.includes('admin')
				"
			>
				{{ $t("menu.categories") }}
			</a-menu-item>
			<a-menu-item
				@click="
					() => {
						menuSelected();
						$router.push({ name: 'admin.products.index' });
					}
				"
				key="products"
				v-if="
					permsArray.includes('products_view') || permsArray.includes('admin')
				"
			>
				{{ $t("menu.products") }}
			</a-menu-item>
		</a-sub-menu>

		<a-sub-menu
			key="stock_management"
			v-if="
				permsArray.includes('purchases_view') ||
				permsArray.includes('sales_view') ||
				permsArray.includes('purchase_returns_view') ||
				permsArray.includes('sales_returns_view') ||
				permsArray.includes('admin')
			"
		>
			<template #title>
				<span>
					<ShopOutlined />
					<span>{{ $t("menu.stock_management") }}</span>
				</span>
			</template>
			<a-menu-item
				@click="
					() => {
						menuSelected();
						$router.push({
							name: 'admin.stock.purchases.index',
						});
					}
				"
				key="purchases"
				v-if="
					permsArray.includes('purchases_view') || permsArray.includes('admin')
				"
			>
				{{ $t("menu.purchases") }}
			</a-menu-item>
			<a-menu-item
				@click="
					() => {
						menuSelected();
						$router.push({
							name: 'admin.stock.purchase-returns.index',
						});
					}
				"
				key="purchase_returns"
				v-if="
					permsArray.includes('purchase_returns_view') ||
					permsArray.includes('admin')
				"
			>
				{{ $t("menu.purchase_returns") }}
			</a-menu-item>
			<a-menu-item
				@click="
					() => {
						menuSelected();
						$router.push({
							name: 'admin.stock.sales.index',
						});
					}
				"
				key="sales"
				v-if="permsArray.includes('sales_view') || permsArray.includes('admin')"
			>
				{{ $t("menu.sales") }}
			</a-menu-item>

			<a-menu-item
				@click="
					() => {
						menuSelected();
						$router.push({
							name: 'admin.stock.sales-returns.index',
						});
					}
				"
				key="sales_returns"
				v-if="
					permsArray.includes('sales_returns_view') ||
					permsArray.includes('admin')
				"
			>
				{{ $t("menu.sales_returns") }}
			</a-menu-item>
		</a-sub-menu>

		<a-menu-item
			@click="
				() => {
					menuSelected();
					$router.push({ name: 'admin.stock_adjustment.index' });
				}
			"
			key="stock_adjustment"
			v-if="
				permsArray.includes('stock_adjustments_view') ||
				permsArray.includes('admin')
			"
		>
			<CalculatorOutlined />
			<span>{{ $t("menu.stock_adjustment") }}</span>
		</a-menu-item>

		<a-menu-item
			v-if="permsArray.includes('pos_view') || permsArray.includes('admin')"
			@click="
				() => {
					menuSelected();
					$router.push({ name: 'admin.pos.index' });
				}
			"
			key="pos"
		>
			<ShoppingCartOutlined />
			<span>{{ $t("menu.pos") }}</span>
		</a-menu-item>

		<a-sub-menu
			key="expense_manager"
			v-if="
				permsArray.includes('expense_categories_view') ||
				permsArray.includes('expenses_view') ||
				permsArray.includes('admin')
			"
		>
			<template #title>
				<span>
					<ShoppingOutlined />
					<span>{{ $t("menu.expense_manager") }}</span>
				</span>
			</template>
			<a-menu-item
				@click="
					() => {
						menuSelected();
						$router.push({
							name: 'admin.expense_categories.index',
						});
					}
				"
				key="expense_categories"
				v-if="
					permsArray.includes('expense_categories_view') ||
					permsArray.includes('admin')
				"
			>
				{{ $t("menu.expense_categories") }}
			</a-menu-item>
			<a-menu-item
				@click="
					() => {
						menuSelected();
						$router.push({ name: 'admin.expenses.index' });
					}
				"
				key="expenses"
				v-if="
					permsArray.includes('expenses_view') || permsArray.includes('admin')
				"
			>
				{{ $t("menu.expenses") }}
			</a-menu-item>
		</a-sub-menu>

		<a-sub-menu
			v-if="
				permsArray.includes('users_view') ||
				permsArray.includes('customers_view') ||
				permsArray.includes('suppliers_view') ||
				permsArray.includes('admin')
			"
			key="users"
		>
			<template #title>
				<span>
					<UserOutlined />
					<span>{{ $t("menu.users") }}</span>
				</span>
			</template>
			<a-menu-item
				v-if="permsArray.includes('users_view') || permsArray.includes('admin')"
				@click="
					() => {
						menuSelected();
						$router.push({ name: 'admin.users.index' });
					}
				"
				key="users"
			>
				{{ $t("menu.staff_members") }}
			</a-menu-item>
			<a-menu-item
				v-if="
					permsArray.includes('customers_view') || permsArray.includes('admin')
				"
				@click="
					() => {
						menuSelected();
						$router.push({ name: 'admin.customers.index' });
					}
				"
				key="customers"
			>
				{{ $t("menu.customers") }}
			</a-menu-item>
			<a-menu-item
				v-if="
					permsArray.includes('suppliers_view') || permsArray.includes('admin')
				"
				@click="
					() => {
						menuSelected();
						$router.push({ name: 'admin.suppliers.index' });
					}
				"
				key="suppliers"
			>
				{{ $t("menu.suppliers") }}
			</a-menu-item>
		</a-sub-menu>

		<a-sub-menu
			v-if="
				((permsArray.includes('purchases_view') ||
					permsArray.includes('sales_view') ||
					permsArray.includes('purchase_returns_view') ||
					permsArray.includes('sales_returns_view')) &&
					permsArray.includes('order_payments_view')) ||
				permsArray.includes('warehouses_view') ||
				permsArray.includes('customers_view') ||
				permsArray.includes('suppliers_view') ||
				permsArray.includes('admin')
			"
			key="reports"
		>
			<template #title>
				<span>
					<BarChartOutlined />
					<span>{{ $t("menu.reports") }}</span>
				</span>
			</template>
			<a-menu-item
				v-if="
					((permsArray.includes('purchases_view') ||
						permsArray.includes('sales_view') ||
						permsArray.includes('purchase_returns_view') ||
						permsArray.includes('sales_returns_view')) &&
						permsArray.includes('order_payments_view')) ||
					permsArray.includes('admin')
				"
				@click="
					() => {
						menuSelected();
						$router.push({
							name: 'admin.reports.payments.index',
						});
					}
				"
				key="payments"
			>
				{{ $t("menu.payments") }}
			</a-menu-item>
			<a-menu-item
				v-if="
					permsArray.includes('warehouses_view') || permsArray.includes('admin')
				"
				@click="
					() => {
						menuSelected();
						$router.push({ name: 'admin.reports.stock.index' });
					}
				"
				key="stock_alert"
			>
				{{ $t("menu.stock_alert") }}
			</a-menu-item>
			<a-menu-item
				v-if="
					permsArray.includes('customers_view') ||
					permsArray.includes('suppliers_view') ||
					permsArray.includes('admin')
				"
				@click="
					() => {
						menuSelected();
						$router.push({ name: 'admin.reports.users.index' });
					}
				"
				key="users_reports"
			>
				{{ $t("menu.users_reports") }}
			</a-menu-item>
		</a-sub-menu>

		<component
			v-for="(appModule, index) in appModules"
			:key="index"
			v-bind:is="appModule + 'Menu'"
			@menuSelected="menuSelected"
		/>

		<a-menu-item
			@click="
				() => {
					menuSelected();
					$router.push({ name: 'admin.settings.profile.index' });
				}
			"
			key="settings"
		>
			<SettingOutlined />
			<span>{{ $t("menu.settings") }}</span>
		</a-menu-item>

		<a-menu-item @click="logout" key="logout">
			<LogoutOutlined />
			<span>{{ $t("menu.logout") }}</span>
		</a-menu-item>
	</a-menu>
</template>

<script>
import { defineComponent, ref, watch, onMounted, computed } from "vue";
import { Layout } from "ant-design-vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import {
	HomeOutlined,
	LogoutOutlined,
	UserOutlined,
	SettingOutlined,
	CloseOutlined,
	ShoppingOutlined,
	ShoppingCartOutlined,
	AppstoreOutlined,
	ShopOutlined,
	BarChartOutlined,
	CalculatorOutlined,
} from "@ant-design/icons-vue";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";
import common from "../../common/composable/common";
const { Sider } = Layout;

export default defineComponent({
	props: ["collapsed"],
	components: {
		Sider,
		PerfectScrollbar,
		Layout,

		HomeOutlined,
		LogoutOutlined,
		UserOutlined,
		SettingOutlined,
		CloseOutlined,
		ShoppingOutlined,
		ShoppingCartOutlined,
		AppstoreOutlined,
		ShopOutlined,
		BarChartOutlined,
		CalculatorOutlined,
	},
	setup(props, { emit }) {
		const { appSetting, user, permsArray, appModules, cssSettings } = common();
		const rootSubmenuKeys = [
			"dashboard",
			"product_manager",
			"stock_management",
			"pos",
			"stock_adjustment",
			"expense_manager",
			"users",
			"reports",
			"settings",
			"online_orders",
			"website_setup",
		];
		const store = useStore();
		const route = useRoute();

		const openKeys = ref([]);
		const selectedKeys = ref([]);
		const mode = ref("horizontal");

		onMounted(() => {
			setSelectedKeys(route);
		});

		const logout = () => {
			store.dispatch("auth/logout");
		};

		const menuSelected = () => {
			emit("menuSelected");
		};

		const onOpenChange = (currentOpenKeys) => {
			const latestOpenKey = currentOpenKeys.find(
				(key) => openKeys.value.indexOf(key) === -1
			);

			if (rootSubmenuKeys.indexOf(latestOpenKey) === -1) {
				openKeys.value = currentOpenKeys;
			} else {
				openKeys.value = latestOpenKey ? [latestOpenKey] : [];
			}
		};

		const setSelectedKeys = (newVal) => {
			const menuKey =
				typeof newVal.meta.menuKey == "function"
					? newVal.meta.menuKey(newVal)
					: newVal.meta.menuKey;

			if (newVal.meta.menuParent == "settings") {
				selectedKeys.value = ["settings"];
			} else {
				selectedKeys.value = [menuKey.replace("-", "_")];
			}

			if (cssSettings.value.headerMenuMode == "horizontal") {
				openKeys.value = [];
			} else {
				openKeys.value = [newVal.meta.menuParent];
			}
		};

		watch(route, (newVal, oldVal) => {
			setSelectedKeys(newVal);
		});

		return {
			mode,
			selectedKeys,
			openKeys,
			logout,
			innerWidth: window.innerWidth,

			onOpenChange,
			menuSelected,
			appSetting,
			user,
			permsArray,
			appModules,
		};
	},
});
</script>

<style lang="less"></style>
