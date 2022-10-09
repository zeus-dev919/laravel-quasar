<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.payments`)" class="p-0" />
		</template>
		<template #breadcrumb>
			<a-breadcrumb separator="-" style="font-size: 12px">
				<a-breadcrumb-item>
					<router-link :to="{ name: 'admin.dashboard.index' }">
						{{ $t(`menu.dashboard`) }}
					</router-link>
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`menu.reports`) }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t(`menu.payments`) }}
				</a-breadcrumb-item>
			</a-breadcrumb>
		</template>
	</AdminPageHeader>

	<a-card class="page-content-container">
		<a-row :gutter="15" class="mb-20">
			<a-col :xs="24" :sm="24" :md="7" :lg="4" :xl="4">
				<a-select
					v-model:value="filters.user_id"
					:placeholder="$t('common.select_default_text', [$t(`user.user`)])"
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

			<a-col :xs="24" :sm="24" :md="10" :lg="6" :xl="6">
				<DateRangePicker
					@dateTimeChanged="
						(changedDateTime) => {
							filters.dates = changedDateTime;
						}
					"
				/>
			</a-col>
		</a-row>

		<Payments :user_id="filters.user_id" :dates="filters.dates" />
	</a-card>
</template>
<script>
import { onMounted, onBeforeMount, ref, reactive } from "vue";
import { useRouter } from "vue-router";
import table from "../../../../common/composable/datatable";
import common from "../../../../common/composable/common";
import UserInfo from "../../../../common/components/user/UserInfo.vue";
import DateRangePicker from "../../../../common/components/common/calendar/DateRangePicker.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";
import Payments from "./Payments.vue";

export default {
	components: {
		UserInfo,
		DateRangePicker,
		AdminPageHeader,
		Payments,
	},
	setup() {
		const datatable = table();
		const { permsArray } = common();
		const filters = reactive({
			user_id: undefined,
			payment_mode_id: undefined,
			dates: [],
		});
		const users = ref({});
		const router = useRouter();

		onBeforeMount(() => {
			if (
				!(
					((permsArray.value.includes("purchases_view") ||
						permsArray.value.includes("sales_view") ||
						permsArray.value.includes("purchase_returns_view") ||
						permsArray.value.includes("sales_returns_view")) &&
						permsArray.value.includes("order_payments_view")) ||
					permsArray.value.includes("admin")
				)
			) {
				router.push("admin.dashboard.index");
			}
		});

		onMounted(() => {
			const usersPromise = axiosAdmin.post("customer-suppliers");

			Promise.all([usersPromise]).then(([usersResponse]) => {
				users.value = usersResponse.data;
			});
		});

		return {
			...datatable,
			filters,
			users,
			permsArray,
		};
	},
};
</script>
