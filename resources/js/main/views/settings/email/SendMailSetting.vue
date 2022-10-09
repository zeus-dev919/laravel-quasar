<template>
	<div class="notificaiton-setting-bar">
		<perfect-scrollbar
			:options="{
				wheelSpeed: 1,
				swipeEasing: true,
				suppressScrollX: true,
			}"
		>
			<a-card class="page-content-container">
				<template #title>
					<div :style="{ marginTop: '8px', paddingBottom: '14px' }">
						{{ $t("mail_settings.send_mail_for") }}
					</div>
				</template>
				<a-checkbox-group
					v-model:value="settings"
					style="width: 100%"
					@change="settingsChanged"
				>
					<a-row>
						<!-- Stock Adjustment -->
						<a-col :span="24" class="mb-5">
							<a-typography-title :level="5">
								{{ $t("menu.stock_adjustment") }}
								<TooltipWarehouse />
							</a-typography-title>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="stock_adjustment_create">
								{{ $t("common.on_create") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="stock_adjustment_update">
								{{ $t("common.on_update") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="stock_adjustment_delete">
								{{ $t("common.on_delete") }}
							</a-checkbox>
						</a-col>
						<a-divider />

						<!-- Purchase Returns -->
						<a-col :span="24" class="mb-5">
							<a-typography-title :level="5">
								{{ $t("menu.purchase_returns") }}
								<TooltipWarehouse />
							</a-typography-title>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="purchase_returns_create">
								{{ $t("common.on_create") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="purchase_returns_update">
								{{ $t("common.on_update") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="purchase_returns_delete">
								{{ $t("common.on_delete") }}
							</a-checkbox>
						</a-col>
						<a-divider />

						<!-- Purchase -->
						<a-col :span="24" class="mb-5">
							<a-typography-title :level="5">
								{{ $t("menu.purchases") }}
								<TooltipWarehouse />
							</a-typography-title>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="purchases_create">
								{{ $t("common.on_create") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="purchases_update">
								{{ $t("common.on_update") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="purchases_delete">
								{{ $t("common.on_delete") }}
							</a-checkbox>
						</a-col>
						<a-divider />

						<!-- Sales -->
						<a-col :span="24" class="mb-5">
							<a-typography-title :level="5">
								{{ $t("menu.sales") }}
								<TooltipWarehouse />
							</a-typography-title>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="sales_create">
								{{ $t("common.on_create") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="sales_update">
								{{ $t("common.on_update") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="sales_delete">
								{{ $t("common.on_delete") }}
							</a-checkbox>
						</a-col>
						<a-divider />

						<!-- Sales Returns -->
						<a-col :span="24" class="mb-5">
							<a-typography-title :level="5">
								{{ $t("menu.sales_returns") }}
								<TooltipWarehouse />
							</a-typography-title>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="sales_returns_create">
								{{ $t("common.on_create") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="sales_returns_update">
								{{ $t("common.on_update") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="sales_returns_delete">
								{{ $t("common.on_delete") }}
							</a-checkbox>
						</a-col>
						<a-divider />

						<!-- Expense -->
						<a-col :span="24" class="mb-5">
							<a-typography-title :level="5">
								{{ $t("menu.expenses") }}
								<TooltipWarehouse />
							</a-typography-title>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="expense_create">
								{{ $t("common.on_create") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="expense_update">
								{{ $t("common.on_update") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="expense_delete">
								{{ $t("common.on_delete") }}
							</a-checkbox>
						</a-col>
						<a-divider />

						<!-- Staff Member -->
						<a-col :span="24" class="mb-5">
							<a-typography-title :level="5">
								{{ $t("menu.staff_members") }}
								<TooltipWarehouse />
							</a-typography-title>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="staff_member_create">
								{{ $t("common.on_create") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="staff_member_update">
								{{ $t("common.on_update") }}
							</a-checkbox>
						</a-col>
						<a-col :span="24" class="mb-5">
							<a-checkbox value="staff_member_delete">
								{{ $t("common.on_delete") }}
							</a-checkbox>
						</a-col>
						<a-divider />
					</a-row>
				</a-checkbox-group>
			</a-card>
		</perfect-scrollbar>
	</div>
</template>
<script>
import { onMounted, ref } from "vue";
import { message } from "ant-design-vue";
import { useI18n } from "vue-i18n";
import apiAdmin from "../../../../common/composable/apiAdmin";
import TooltipWarehouse from "./TooltipWarehouse.vue";

export default {
	props: ["sendMailSettings"],
	components: {
		TooltipWarehouse,
	},
	setup(props) {
		const { addEditRequestAdmin } = apiAdmin();
		const { t } = useI18n();
		const settings = ref([]);

		onMounted(() => {
			settings.value = props.sendMailSettings.credentials;
		});

		const settingsChanged = (checkedValue) => {
			addEditRequestAdmin({
				url: `settings/email/send-mail-settings`,
				data: { name_key: "warehouse", settings: checkedValue },
				success: (res) => {
					message.success(t("mail_settings.send_mail_setting_saved"));
				},
			});
		};

		return {
			settings,
			settingsChanged,
		};
	},
};
</script>

<style lang="less">
.notificaiton-setting-bar .ps {
	height: calc(100vh - 145px);
}
</style>
