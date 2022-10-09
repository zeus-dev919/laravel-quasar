import { computed, ref, onMounted } from "vue";
import moment from "moment";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import { forEach } from "lodash-es";
import { useRoute } from "vue-router";
import { checkUserPermission } from "../scripts/functions";
import dayjs from 'dayjs';

moment.suppressDeprecationWarnings = true;
var utc = require('dayjs/plugin/utc')
var timezone = require('dayjs/plugin/timezone');
dayjs.extend(utc);
dayjs.extend(timezone);

const common = () => {
	const route = useRoute();
	const store = useStore();
	const { t } = useI18n();
	const orderType = ref(route.meta.orderType);
	const invoiceBaseUrl = window.config.invoice_url;

	const cssSettings = computed(() => store.state.auth.cssSettings);
	const appModules = computed(() => store.state.auth.activeModules);
	const appSetting = computed(() => store.state.auth.appSetting);
	const user = computed(() => store.state.auth.user);
	const selectedWarehouse = computed(() => store.state.auth.warehouse);
	const allWarehouses = computed(() => store.state.auth.all_warehouses);

	onMounted(() => {
		if (route.meta && route.meta.orderType) {
			orderType.value = route.meta.orderType;
		} else {
			orderType.value = "online-orders";
		}
	});

	const statusColors = {
		enabled: "success",
		disabled: "error",
	};

	const userStatus = [
		{
			key: "enabled",
			value: t("common.enabled")
		},
		{
			key: "disabled",
			value: t("common.disabled")
		}
	];

	const taxTypes = [
		{
			key: "inclusive",
			value: t("product.inclusive")
		},
		{
			key: "exclusive",
			value: t("product.exclusive")
		}
	];

	const disabledDate = (current) => {
		// Can not select days before today and today
		return current && current > moment().endOf("day");
	};

	const dayjsObject = (date) => {
		if (date == undefined) {
			return dayjs()
				.tz(appSetting.value.timezone);
		} else {
			return dayjs(date)
				.tz(appSetting.value.timezone);
		}
	}

	const formatDate = (date) => {
		if (date == undefined) {
			return dayjs()
				.tz(appSetting.value.timezone)
				.format(
					`${appSetting.value.date_format}`
				)
		} else {
			return dayjs(date)
				.tz(appSetting.value.timezone)
				.format(
					`${appSetting.value.date_format}`
				)
		}
	}

	const formatDateTime = (dateTime) => {
		if (dateTime == undefined) {
			return dayjs()
				.tz(appSetting.value.timezone)
				.format(
					`${appSetting.value.date_format} ${appSetting.value.time_format}`
				)
		} else {
			return dayjs(dateTime)
				.tz(appSetting.value.timezone)
				.format(
					`${appSetting.value.date_format} ${appSetting.value.time_format}`
				)
		}
	}

	const formatAmount = (amount) => {
		return parseFloat(parseFloat(amount).toFixed(2));
	};

	const formatAmountCurrency = (amount) => {
		const newAmount = parseFloat(Math.abs(amount)).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		const newAmountString = `${appSetting.value.currency.symbol}${newAmount}`;

		return amount < 0 ? `- ${newAmountString}` : newAmountString;
	};

	const calculateOrderFilterString = (filters) => {
		var filterString = "";

		if (
			filters.payment_status != undefined &&
			filters.payment_status != "all"
		) {
			if (filters.payment_status == "pending") {
				filterString += `(payment_status eq "${filters.payment_status}" or payment_status eq "partially_paid" or payment_status eq "unpaid")`;
			} else {
				filterString += `payment_status eq "${filters.payment_status}"`;
			}
		}

		// Order Status
		if (
			filters.order_status != undefined &&
			filters.order_status != "all"
		) {
			if (orderType == 'online-orders') {
				if (filters.order_status == 'cancelled') {
					filterString += ` and cancelled eq 1`;
				} else if (filters.order_status == 'pending') {
					filterString += ` and ((order_status eq "ordered" or order_status eq "confirmed" or order_status eq "processing" or order_status eq "shipping") and cancelled ne 1)`;
				} else {
					filterString += ` and (order_status eq "${filters.order_status}" and cancelled ne 1)`;
				}
			} else {
				filterString += ` and order_status eq "${filters.order_status}"`;
			}
		}

		return filterString;
	}

	const calculateFilterString = (filters) => {
		var filterString = "";

		forEach(filters, (filterValue, filterKey) => {
			if (filterValue != undefined) {
				filterString += `${filterKey} eq "${filterValue}" and `;
			}
		})

		if (filterString.length > 0) {
			filterString = filterString.substring(0, filterString.length - 4);
		}

		return filterString;
	}

	const checkPermission = (permissionName) => checkUserPermission(permissionName, user.value);

	const updatePageTitle = (pageName) => {
		store.commit("auth/updatePageTitle", t(`menu.${pageName}`));
	}

	const permsArray = computed(() => {
		const permsArrayList = user && user.value && user.value.role ? [user.value.role.name] : [];

		if (user && user.value && user.value.role) {
			forEach(user.value.role.perms, (permission) => {
				permsArrayList.push(permission.name);
			});
		}

		return permsArrayList;
	});

	const orderPageObject = computed(() => {
		var pageObjectDetails = {};

		if (orderType.value == "purchases") {
			pageObjectDetails = {
				type: "purchases",
				langKey: "purchase",
				menuKey: "purchases",
				userType: "suppliers",
				permission: "purchases",
			};
		} else if (orderType.value == "sales") {
			pageObjectDetails = {
				type: "sales",
				langKey: "sales",
				menuKey: "sales",
				userType: "customers",
				permission: "sales",
			};
		} else if (orderType.value == "purchase-returns") {
			pageObjectDetails = {
				type: "purchase-returns",
				langKey: "purchase_returns",
				menuKey: "purchase_returns",
				userType: "suppliers",
				permission: "purchase_returns",
			};
		} else if (orderType.value == "sales-returns") {
			pageObjectDetails = {
				type: "sales-returns",
				langKey: "sales_returns",
				menuKey: "sales_returns",
				userType: "customers",
				permission: "sales_returns",
			};
		} else if (orderType.value == "online-orders") {
			pageObjectDetails = {
				type: "online-orders",
				langKey: "online_orders",
				menuKey: "online_orders",
				userType: "customers",
				permission: "online_orders",
			};
		}

		return pageObjectDetails;
	});

	const getOrderTypeFromstring = (stringVal) => {
		const orderType = stringVal.replace("-", "_");

		return t(`menu.${orderType}`);
	}

	const orderStatus = [
		{
			key: "pending",
			value: t("common.unpaid"),
		},
		{
			key: "paid",
			value: t("common.paid"),
		},
		// {
		// 	key: "cancelled",
		// 	value: t("common.cancelled"),
		// },
	];

	const paymentStatus = [
		{
			key: "pending",
			value: t("common.pending"),
		},
		{
			key: "paid",
			value: t("common.paid"),
		},
		{
			key: "cancelled",
			value: t("common.cancelled"),
		},
	];

	const purchaseOrderStatus = [
		{
			key: "received",
			value: t("common.received"),
		},
		{
			key: "pending",
			value: t("common.pending"),
		},
		{
			key: "ordered",
			value: t("common.ordered"),
		},
	];

	const purchaseReturnStatus = [
		{
			key: "completed",
			value: t("common.completed"),
		},
		{
			key: "pending",
			value: t("common.pending"),
		},
	];

	const onlineOrderChangeStatus = [
		{
			key: "confirmed",
			value: t("common.confirmed"),
		},
		{
			key: "processing",
			value: t("common.processing"),
		},
		{
			key: "shipping",
			value: t("common.shipping"),
		},
	];

	const salesOrderStatus = [
		{
			key: "ordered",
			value: t("common.ordered"),
		},
		...onlineOrderChangeStatus,
		{
			key: "delivered",
			value: t("common.delivered"),
		},
	];

	const salesReturnStatus = [
		{
			key: "received",
			value: t("common.received"),
		},
		{
			key: "pending",
			value: t("common.pending"),
		},
	];

	const barcodeSymbology = [
		{
			key: "CODE128",
			value: "CODE128"
		},
		{
			key: "CODE39",
			value: "CODE39"
		},
	];

	const getRecursiveCategories = (response, excludeId = null) => {
		const allCategoriesArray = [];
		const listArray = [];

		response.data.map((responseArray) => {
			if (excludeId == null || (excludeId != null && responseArray.x_parent_id != excludeId)) {
				listArray.push({
					xid: responseArray.xid,
					x_parent_id: responseArray.x_parent_id,
					title: responseArray.name,
					value: responseArray.xid,
				});
			}
		});

		listArray.forEach((node) => {
			// No parentId means top level
			if (!node.x_parent_id) return allCategoriesArray.push(node);

			// Insert node as child of parent in listArray array
			const parentIndex = listArray.findIndex(
				(el) => el.xid === node.x_parent_id
			);
			if (!listArray[parentIndex].children) {
				return (listArray[parentIndex].children = [node]);
			}

			listArray[parentIndex].children.push(node);
		});

		return allCategoriesArray;
	}

	const filterTreeNode = (inputValue, treeNode) => {
		const treeString = treeNode.props.title.toLowerCase();

		return treeString.includes(inputValue.toLowerCase());
	};

	const slugify = (text) => {
		return text
			.toString()
			.toLowerCase()
			.replace(/\s+/g, "-") // Replace spaces with -
			.replace(/[^\w\-]+/g, "") // Remove all non-word chars
			.replace(/\-\-+/g, "-") // Replace multiple - with single -
			.replace(/^-+/, "") // Trim - from start of text
			.replace(/-+$/, ""); // Trim - from end of text
	};

	const convertToPositive = (amount) => {
		return amount < 0 ? amount * -1 : amount;
	}

	return {
		appSetting,
		user,
		selectedWarehouse,
		allWarehouses,
		checkPermission,
		permsArray,
		statusColors,
		userStatus,
		taxTypes,
		barcodeSymbology,

		disabledDate,
		formatAmount,
		formatAmountCurrency,
		convertToPositive,

		calculateOrderFilterString,
		calculateFilterString,
		updatePageTitle,

		// For Stock routes
		orderType,
		orderPageObject,
		orderStatus,
		paymentStatus,
		purchaseOrderStatus,
		onlineOrderChangeStatus,
		salesOrderStatus,
		purchaseReturnStatus,
		salesReturnStatus,

		getRecursiveCategories,
		filterTreeNode,
		getOrderTypeFromstring,

		invoiceBaseUrl,
		appModules,
		dayjs,
		formatDate,
		formatDateTime,
		dayjsObject,
		slugify,

		cssSettings,
	};
}

export default common;