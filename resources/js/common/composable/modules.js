import { ref } from "vue";
import axios from "axios";
import { forEach, find, includes, remove } from "lodash-es";
import { notification, message } from "ant-design-vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";

const modules = () => {
	const allModules = ref([]);
	const rules = ref({});
	const purchaseCode = ref("");
	const errorMessage = ref("");
	const successMessage = ref("");
	const loading = ref(false);
	const { t } = useI18n();
	const productName = ref(window.config.product_name);
	const version = ref(window.config.product_version);
	const store = useStore();
	const offers = ref([]);
	const settings = ref([]);
	const downloading = ref(false);
	const extracting = ref("");
	const downloadPercentage = ref(0);
	var getDownloadTimer;

	const getModuleData = () => {
		const mainProductName = window.config.product_name;

		const modulesPromise = axiosAdmin.get("modules");
		const envatoProductsPromise = axios.post("https://envato.codeifly.com/product", {
			verified_name: mainProductName,
			domain: window.location.host,
		});
		var allModulesData = [];

		Promise.all([modulesPromise, envatoProductsPromise]).then(
			([modulesResponse, envatoProductsResponse]) => {
				const product = envatoProductsResponse.data.product;
				const installedModules = window.config.installed_modules;
				const enabledModules = window.config.modules;
				offers.value = envatoProductsResponse.data.offers;
				settings.value = envatoProductsResponse.data.settings;

				forEach(product.modules, (productModule) => {
					const currentVersion = find(installedModules, [
						"verified_name",
						productModule.verified_name,
					]);

					allModulesData.push({
						...productModule,
						current_version: currentVersion
							? currentVersion.current_version
							: "-",
						status: includes(enabledModules, productModule.verified_name),
						installed: currentVersion == undefined ? false : true,
					});

					// Change status to false for unregistered modules
					if (!productModule.verified || productModule.other_domain_verified) {
						var newModArray = [...window.config.modules];
						var newEnabledModules = remove(newModArray, function (newValue) {
							return newValue != productModule.verified_name;
						});
						store.commit(
							"auth/updateActiveModules",
							newEnabledModules
						);

						window.config.modules = newEnabledModules;
					}
				});

				allModules.value = allModulesData;
			}

		);
	};

	const verifyPurchase = (configObject) => {
		const { success } = configObject;

		loading.value = true;
		rules.value = {};
		errorMessage.value = "";

		axios
			.post("https://envato.codeifly.com/verify", {
				verified_name: productName.value,
				purchase_code: purchaseCode.value,
				domain: window.location.host,
				app_url: window.location.href,
				version: version.value,
			})
			.then((response) => {
				loading.value = false;

				if (response.data.status == "success") {
					loading.value = false;
					notification.success({
						message: t("common.success"),
					});
					successMessage.value = t("messages.verify_success");

					success(response.data);
				} else {
					notification.error({
						message: t("common.error"),
					});
					errorMessage.value = response.data.message;
				}

			})
			.catch((error) => {
				loading.value = false;
				const err = error.response.data;
				const errorCode = error.response.status;
				var errorRules = {};

				if (errorCode == 422) {
					if (err.errors && typeof err.errors != "undefined") {
						var keys = Object.keys(err.errors);
						for (var i = 0; i < keys.length; i++) {
							// Escape dot that comes with error in array fields
							var key = keys[i].replace(".", "\\.");

							errorRules[key] = {
								required: true,
								message: err.errors[keys[i]][0],
							};
						}
					}

					rules.value = errorRules;
					message.error(t("common.fix_errors"));
				} else {
					errorMessage.value = err.message
						? err.message
						: t("messages.somehing_went_wrong");
				}
			});
	}

	const install = (moduleName) => {
		downloading.value = true;
		downloadPercentage.value = 0;
		extracting.value = "";
		const postArray = {
			verified_name: moduleName,
			domain: window.location.host,
		};

		getDownloadTimer = window.setInterval(function () {
			setDownloadPercentage();
		}, 1500);

		axiosAdmin
			.post("modules/install", postArray)
			.then((response) => {
				downloading.value = false;
				downloadPercentage.value = 100;
				extracting.value = "started";

				// Extracting Zip File
				axiosAdmin
					.post("modules/extract", postArray)
					.then((extractResponse) => {
						extracting.value = "completed";

						axios
							.post("https://envato.codeifly.com/version-update", {
								verified_name: extractResponse.data.verified_name,
								version: extractResponse.data.version,
								domain: window.location.host,
							})

						store.commit(
							"auth/updateActiveModules",
							extractResponse.data.enabled_modules
						);

						window.config.modules = extractResponse.data.enabled_modules;
						window.config.installed_modules = extractResponse.data.installed_modules;
					})
					.catch((error) => {
						extracting.value = "failed";
					});
			})
			.catch((error) => {
				downloading.value = false;
				downloadPercentage.value = 0;
				clearInterval(getDownloadTimer);
			});
	}

	const setDownloadPercentage = () => {
		axiosAdmin("modules/download-percentage").then((response) => {
			downloadPercentage.value = parseInt(response.data.percentage);

			if (downloadPercentage.value >= 100) {
				clearInterval(getDownloadTimer);
			}
		});
	}

	return {
		allModules,
		getModuleData,
		install,

		verifyPurchase,
		rules,
		purchaseCode,
		errorMessage,
		successMessage,
		loading,
		productName,
		version,

		offers,
		settings,
		downloading,
		downloadPercentage,
		extracting,
	};
}

export default modules;