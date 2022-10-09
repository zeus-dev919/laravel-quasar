<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.${orderPageObject.menuKey}`)" class="p-0">
				<template
					v-if="
						permsArray.includes(`${orderPageObject.permission}_create`) ||
						permsArray.includes('admin')
					"
					#extra
				>
					<router-link
						:to="{
							name: `admin.stock.${orderPageObject.type}.create`,
						}"
					>
						<a-button type="primary">
							<PlusOutlined />
							{{ $t(`${orderPageObject.langKey}.add`) }}
						</a-button>
					</router-link>
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
					{{ $t(`menu.stock_management`) }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`menu.${orderPageObject.menuKey}`) }}
				</a-breadcrumb-item>
			</a-breadcrumb>
		</template>
	</AdminPageHeader>

	<a-card class="page-content-container">
		<a-row :gutter="[8, 8]" class="mb-20">
			<a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="4">
				<a-input-search
					style="width: 100%"
					v-model:value="filters.searchString"
					show-search
					:placeholder="
						$t('common.placeholder_search_text', [$t('stock.invoice_number')])
					"
				/>
			</a-col>
			<a-col :xs="24" :sm="24" :md="8" :lg="6" :xl="4">
				<a-select
					v-model:value="filters.user_id"
					:placeholder="
						$t('common.select_default_text', [
							$t(`${orderPageObject.langKey}.user`),
						])
					"
					:allowClear="true"
					style="width: 100%"
					optionFilterProp="title"
					show-search
				>
					<a-select-option
						v-for="user in users"
						:key="user.xid"
						:title="user.name"
						:value="user.xid"
					>
						{{ user.name }}
					</a-select-option>
				</a-select>
			</a-col>
			<a-col :xs="24" :sm="24" :md="8" :lg="6" :xl="6">
				<DateRangePicker
					ref="serachDateRangePicker"
					@dateTimeChanged="
						(changedDateTime) => (filters.dates = changedDateTime)
					"
				/>
			</a-col>
		</a-row>

		<a-row>
			<a-col :span="24">
				<a-tabs v-model:activeKey="filters.payment_status">
					<a-tab-pane
						key="all"
						:tab="`${$t('common.all')} ${$t(
							'menu.' + orderPageObject.menuKey
						)}`"
					/>
					<a-tab-pane
						v-for="status in orderStatus"
						:key="status.key"
						:tab="`${status.value}`"
					/>
				</a-tabs>
			</a-col>
		</a-row>

		<OrderTable :orderType="orderType" :filters="filters" />
	</a-card>
</template>
<script>
import { onMounted, watch, ref } from "vue";
import { PlusOutlined } from "@ant-design/icons-vue";
import { useRoute } from "vue-router";
import common from "../../../../common/composable/common";
import OrderTable from "../../../components/order/OrderTable.vue";
import DateRangePicker from "../../../../common/components/common/calendar/DateRangePicker.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
	components: {
		PlusOutlined,
		OrderTable,
		DateRangePicker,
		AdminPageHeader,
	},
	setup() {
		const {
			formatAmountCurrency,
			orderType,
			orderPageObject,
			orderStatus,
			permsArray,
		} = common();
		const route = useRoute();

		const users = ref([]);
		const serachDateRangePicker = ref(null);

		const filters = ref({
			payment_status: "all",
			user_id: undefined,
			dates: [],
			searchColumn: "invoice_number",
			searchString: "",
		});

		onMounted(() => {
			const usersPromise = axiosAdmin.get(orderPageObject.value.userType);

			Promise.all([usersPromise]).then(([usersResponse]) => {
				users.value = usersResponse.data;
			});
		});

		watch(route, (newVal, oldVal) => {
			orderType.value = newVal.params.type;

			filters.value = {
				payment_status: "all",
				user_id: undefined,
				dates: [],
				searchColumn: "invoice_number",
				searchString: "",
			};

			serachDateRangePicker.value.resetPicker();
		});

		return {
			orderPageObject,

			permsArray,
			orderStatus,
			formatAmountCurrency,

			users,

			filters,
			orderType,
			serachDateRangePicker,
		};
	},
};
</script>
