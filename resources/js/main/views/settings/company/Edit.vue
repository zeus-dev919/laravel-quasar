<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.company`)" class="p-0">
				<template
					v-if="
						permsArray.includes('companies_edit') ||
						permsArray.includes('admin')
					"
					#extra
				>
					<a-button type="primary" @click="onSubmit">
						<template #icon> <SaveOutlined /> </template>
						{{ $t("common.update") }}
					</a-button>
				</template>
			</a-page-header>
		</template>
		<template #breadcrumb>
			<a-breadcrumb separator="-" style="font-size: 12px">
				<a-breadcrumb-item>
					<router-link :to="{ name: 'admin.dashboard.index' }">
						{{ $t(`menu.dashboard`) }}
					</router-link>
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t("menu.settings") }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t("menu.company") }}
				</a-breadcrumb-item>
			</a-breadcrumb>
		</template>
	</AdminPageHeader>

	<a-row>
		<a-col :xs="24" :sm="24" :md="24" :lg="4" :xl="4" class="bg-setting-sidebar">
			<SettingSidebar />
		</a-col>
		<a-col :xs="24" :sm="24" :md="24" :lg="20" :xl="20">
			<a-card class="page-content-container">
				<a-form layout="vertical">
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('company.name')"
								name="name"
								:help="rules.name ? rules.name.message : null"
								:validateStatus="rules.name ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.name"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('company.name'),
										])
									"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('company.short_name')"
								name="short_name"
								:help="rules.short_name ? rules.short_name.message : null"
								:validateStatus="rules.short_name ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.short_name"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('company.short_name'),
										])
									"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('company.email')"
								name="email"
								:help="rules.email ? rules.email.message : null"
								:validateStatus="rules.email ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.email"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('company.email'),
										])
									"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('company.phone')"
								name="phone"
								:help="rules.phone ? rules.phone.message : null"
								:validateStatus="rules.phone ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.phone"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('company.phone'),
										])
									"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('company.address')"
								name="address"
								:help="rules.address ? rules.address.message : null"
								:validateStatus="rules.address ? 'error' : null"
							>
								<a-textarea
									v-model:value="formData.address"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('company.address'),
										])
									"
									:rows="4"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('company.left_sidebar_theme')"
								name="left_sidebar_theme"
								:help="
									rules.left_sidebar_theme
										? rules.left_sidebar_theme.message
										: null
								"
								:validateStatus="
									rules.left_sidebar_theme ? 'error' : null
								"
							>
								<a-select
									v-model:value="formData.left_sidebar_theme"
									:placeholder="
										$t('common.select_default_text', [
											$t('company.left_sidebar_theme'),
										])
									"
								>
									<a-select-option key="dark" value="dark">
										{{ $t("company.dark") }}
									</a-select-option>
									<a-select-option
										v-if="appThemeMode != 'dark'"
										key="light"
										value="light"
									>
										{{ $t("company.light") }}
									</a-select-option>
								</a-select>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('company.primary_color')"
								name="primary_color"
								:help="
									rules.primary_color
										? rules.primary_color.message
										: null
								"
								:validateStatus="rules.primary_color ? 'error' : null"
							>
								<color-picker
									v-model:pureColor="formData.primary_color"
									format="hex"
									useType="pure"
									v-model:gradientColor="gradientColor"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="6" :lg="6">
							<a-form-item
								:label="$t('company.dark_logo')"
								name="dark_logo"
								:help="rules.dark_logo ? rules.dark_logo.message : null"
								:validateStatus="rules.dark_logo ? 'error' : null"
							>
								<Upload
									:formData="formData"
									folder="company"
									imageField="dark_logo"
									@onFileUploaded="
										(file) => {
											formData.dark_logo = file.file;
											formData.dark_logo_url = file.file_url;
										}
									"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="6" :lg="6">
							<a-form-item
								:label="$t('company.light_logo')"
								name="light_logo"
								:help="rules.light_logo ? rules.light_logo.message : null"
								:validateStatus="rules.light_logo ? 'error' : null"
							>
								<Upload
									:formData="formData"
									folder="company"
									imageField="light_logo"
									@onFileUploaded="
										(file) => {
											formData.light_logo = file.file;
											formData.light_logo_url = file.file_url;
										}
									"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="6" :lg="6">
							<a-form-item
								:label="$t('company.small_dark_logo')"
								name="small_dark_logo"
								:help="
									rules.small_dark_logo
										? rules.small_dark_logo.message
										: null
								"
								:validateStatus="rules.small_dark_logo ? 'error' : null"
							>
								<Upload
									:formData="formData"
									folder="company"
									imageField="small_dark_logo"
									@onFileUploaded="
										(file) => {
											formData.small_dark_logo = file.file;
											formData.small_dark_logo_url = file.file_url;
										}
									"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="6" :lg="6">
							<a-form-item
								:label="$t('company.small_light_logo')"
								name="small_light_logo"
								:help="
									rules.small_light_logo
										? rules.small_light_logo.message
										: null
								"
								:validateStatus="rules.small_light_logo ? 'error' : null"
							>
								<Upload
									:formData="formData"
									folder="company"
									imageField="small_light_logo"
									@onFileUploaded="
										(file) => {
											formData.small_light_logo = file.file;
											formData.small_light_logo_url = file.file_url;
										}
									"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('company.currency')"
								name="currency_id"
								:help="
									rules.currency_id ? rules.currency_id.message : null
								"
								:validateStatus="rules.currency_id ? 'error' : null"
								class="required"
							>
								<span style="display: flex">
									<a-select
										v-model:value="formData.currency_id"
										:placeholder="
											$t('common.select_default_text', [
												$t('company.currency'),
											])
										"
										:allowClear="true"
										optionFilterProp="label"
										show-search
									>
										<a-select-option
											v-for="currency in currencies"
											:key="currency.xid"
											:value="currency.xid"
										>
											{{ `${currency.name} (${currency.symbol})` }}
										</a-select-option>
									</a-select>
									<CurrencyAddButton @onAddSuccess="currencyAdded" />
								</span>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('warehouse.warehouse')"
								name="warehouse_id"
								:help="
									rules.warehouse_id ? rules.warehouse_id.message : null
								"
								:validateStatus="rules.warehouse_id ? 'error' : null"
								class="required"
							>
								<span style="display: flex">
									<a-select
										v-model:value="formData.warehouse_id"
										:placeholder="
											$t('common.select_default_text', [
												$t('warehouse.warehouse'),
											])
										"
										:allowClear="true"
										optionFilterProp="label"
										show-search
									>
										<a-select-option
											v-for="warehouse in warehouses"
											:key="warehouse.xid"
											:value="warehouse.xid"
											:label="warehouse.name"
										>
											{{ warehouse.name }}
										</a-select-option>
									</a-select>
									<WarehouseAddButton @onAddSuccess="warehouseAdded" />
								</span>
							</a-form-item>
						</a-col>
					</a-row>

					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="4" :lg="4">
							<a-form-item
								:label="$t('company.auto_detect_timezone')"
								name="auto_detect_timezone"
								:help="
									rules.auto_detect_timezone
										? rules.auto_detect_timezone.message
										: null
								"
								:validateStatus="
									rules.auto_detect_timezone ? 'error' : null
								"
							>
								<a-switch
									v-model:checked="formData.auto_detect_timezone"
									:checkedValue="1"
									:unCheckedValue="0"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="8" :lg="8">
							<a-form-item
								:label="$t('company.default_timezone')"
								name="timezone"
								:help="rules.timezone ? rules.timezone.message : null"
								:validateStatus="rules.timezone ? 'error' : null"
								class="required"
							>
								<a-select
									v-model:value="formData.timezone"
									:placeholder="
										$t('common.select_default_text', [
											$t('company.default_timezone'),
										])
									"
									:allowClear="true"
									optionFilterProp="value"
									show-search
								>
									<a-select-option
										v-for="(timezoneValue, timezoneKey) in timezones"
										:key="timezoneKey"
										:value="timezoneValue"
									>
										{{ timezoneValue }}
									</a-select-option>
								</a-select>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="4" :lg="4">
							<a-form-item
								:label="$t('company.app_debug')"
								name="app_debug"
								:help="rules.app_debug ? rules.app_debug.message : null"
								:validateStatus="rules.app_debug ? 'error' : null"
							>
								<a-switch
									v-model:checked="formData.app_debug"
									:checkedValue="1"
									:unCheckedValue="0"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="4" :lg="4">
							<a-form-item
								:label="$t('company.update_app_notification')"
								name="update_app_notification"
								:help="
									rules.update_app_notification
										? rules.update_app_notification.message
										: null
								"
								:validateStatus="
									rules.update_app_notification ? 'error' : null
								"
							>
								<a-switch
									v-model:checked="formData.update_app_notification"
									:checkedValue="1"
									:unCheckedValue="0"
								/>
							</a-form-item>
						</a-col>
					</a-row>

					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('company.date_format')"
								name="date_format"
								:help="
									rules.date_format ? rules.date_format.message : null
								"
								:validateStatus="rules.date_format ? 'error' : null"
								class="required"
							>
								<a-select
									v-model:value="formData.date_format"
									:placeholder="
										$t('common.select_default_text', [
											$t('company.date_format'),
										])
									"
									:allowClear="true"
									optionFilterProp="value"
									show-search
								>
									<a-select-option
										v-for="(
											dateFormatValue, dateFormatKey
										) in dateFormats"
										:key="dateFormatKey"
										:value="dateFormatValue"
									>
										{{
											`(${dateFormatKey}) => ` +
											dayjsObject().format(dateFormatValue)
										}}
									</a-select-option>
								</a-select>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('company.time_format')"
								name="time_format"
								:help="
									rules.time_format ? rules.time_format.message : null
								"
								:validateStatus="rules.time_format ? 'error' : null"
								class="required"
							>
								<a-select
									v-model:value="formData.time_format"
									:placeholder="
										$t('common.select_default_text', [
											$t('company.time_format'),
										])
									"
									:allowClear="true"
									optionFilterProp="value"
									show-search
								>
									<a-select-option
										v-for="(
											timeFormatValue, timeFormatKey
										) in timeFormats"
										:key="timeFormatKey"
										:value="timeFormatKey"
									>
										{{
											`(${timeFormatValue}) => ` +
											dayjsObject().format(timeFormatKey)
										}}
									</a-select-option>
								</a-select>
							</a-form-item>
						</a-col>
					</a-row>

					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item>
								<a-button
									type="primary"
									@click="onSubmit"
									:loading="loading"
								>
									<template #icon> <SaveOutlined /> </template>
									{{ $t("common.update") }}
								</a-button>
							</a-form-item>
						</a-col>
					</a-row>
				</a-form>
			</a-card>
		</a-col>
	</a-row>
</template>
<script>
import { onMounted, ref } from "vue";
import {
	EyeOutlined,
	PlusOutlined,
	EditOutlined,
	DeleteOutlined,
	ExclamationCircleOutlined,
	SaveOutlined,
} from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { changeAntdTheme } from "mini-dynamic-antd-theme";
import { ColorPicker } from "vue3-colorpicker";
import "vue3-colorpicker/style.css";
import Upload from "../../../../common/core/ui/file/Upload.vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import CurrencyAddButton from "../currency/AddButton.vue";
import WarehouseAddButton from "../warehouses/AddButton.vue";
import common from "../../../../common/composable/common";
import SettingSidebar from "../SettingSidebar.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
	components: {
		EyeOutlined,
		PlusOutlined,
		EditOutlined,
		DeleteOutlined,
		ExclamationCircleOutlined,
		SaveOutlined,
		ColorPicker,

		Upload,
		CurrencyAddButton,
		WarehouseAddButton,
		SettingSidebar,
		AdminPageHeader,
	},
	setup() {
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const { permsArray, appSetting, dayjsObject } = common();
		const { t } = useI18n();
		const store = useStore();
		const formData = ref({});
		const currencies = ref([]);
		const warehouses = ref([]);
		const timezones = ref([]);
		const dateFormats = ref([]);
		const timeFormats = ref([]);
		const company = appSetting.value;
		const currencyUrl = "currencies?limit=10000";
		const warehouseUrl = "warehouses?limit=10000";
		const timezoneUrl = "timezones";
		const gradientColor = ref(
			"linear-gradient(0deg, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 1) 100%)"
		);
		const appThemeMode = window.config.theme_mode;

		onMounted(() => {
			const warehousePromise = axiosAdmin.get(warehouseUrl);
			const currencyPromise = axiosAdmin.get(currencyUrl);
			const timezonePromise = axiosAdmin.get(timezoneUrl);

			Promise.all([warehousePromise, currencyPromise, timezonePromise]).then(
				([warehousesResponse, currenciesResponse, timezonesResponse]) => {
					warehouses.value = warehousesResponse.data;
					currencies.value = currenciesResponse.data;
					timezones.value = timezonesResponse.data.timezones;
					dateFormats.value = timezonesResponse.data.date_formates;
					timeFormats.value = timezonesResponse.data.time_formates;

					formData.value = {
						name: company.name,
						short_name: company.short_name,
						email: company.email,
						phone: company.phone,
						address: company.address,
						left_sidebar_theme: company.left_sidebar_theme,
						dark_logo: company.dark_logo,
						dark_logo_url: company.dark_logo_url,
						light_logo: company.light_logo,
						light_logo_url: company.light_logo_url,
						small_dark_logo: company.small_dark_logo,
						small_light_logo: company.small_light_logo,
						small_dark_logo_url: company.small_dark_logo_url,
						small_light_logo_url: company.small_light_logo_url,
						currency_id: company.x_currency_id,
						warehouse_id: company.x_warehouse_id,
						primary_color: company.primary_color,
						timezone: company.timezone,
						date_format: company.date_format,
						time_format: company.time_format,
						auto_detect_timezone: company.auto_detect_timezone,
						app_debug: company.app_debug,
						update_app_notification: company.update_app_notification,
						_method: "PUT",
					};
				}
			);
		});

		const onSubmit = () => {
			addEditRequestAdmin({
				url: `companies/${company.xid}`,
				data: formData.value,
				successMessage: t("company.updated"),
				success: (res) => {
					changeAntdTheme(formData.value.primary_color);
					store.dispatch("auth/updateApp");
				},
			});
		};

		const currencyAdded = () => {
			axiosAdmin.get(currencyUrl).then((response) => {
				currencies.value = response.data;
			});
		};

		const warehouseAdded = () => {
			axiosAdmin.get(warehouseUrl).then((response) => {
				warehouses.value = response.data;
			});
		};

		return {
			permsArray,
			formData,
			loading,
			rules,
			warehouses,
			currencies,
			onSubmit,
			currencyAdded,
			warehouseAdded,
			gradientColor,
			timezones,
			dateFormats,
			timeFormats,
			dayjsObject,
			appThemeMode,
		};
	},
};
</script>
