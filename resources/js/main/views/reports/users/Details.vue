<template>
	<a-drawer
		:title="data.name"
		:width="drawerWidth"
		:visible="visible"
		:body-style="{ paddingBottom: '80px' }"
		:maskClosable="true"
		:destroyOnClose="true"
		@close="onClose"
	>
		<a-row :gutter="[8, 8]" class="mb-20">
			<a-col :xs="24" :sm="24" :md="12" :lg="5" :xl="5">
				<a-select
					v-model:value="filters.payment_status"
					:placeholder="
						$t('common.select_default_text', [$t('payments.payment_status')])
					"
					:allowClear="true"
					style="width: 100%"
					optionFilterProp="title"
					show-search
				>
					<a-select-option :title="$t('common.all')" :value="'all'">
						{{ $t("common.all") }}
					</a-select-option>
					<a-select-option
						v-for="status in paymentStatus"
						:key="status.key"
						:title="status.value"
						:value="status.key"
					>
						{{ status.value }}
					</a-select-option>
				</a-select>
			</a-col>
			<a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
				<DateRangePicker
					ref="serachDateRangePicker"
					@dateTimeChanged="
						(changedDateTime) => (filters.dates = changedDateTime)
					"
				/>
			</a-col>
		</a-row>

		<OrderTable :orderType="activeOrderType" :filters="filters" :perPageItems="10" />
	</a-drawer>
</template>
<script>
import { defineComponent, reactive, ref, watch, onMounted } from "vue";
import common from "../../../../common/composable/common";
import OrderTable from "../../../components/order/OrderTable.vue";
import DateRangePicker from "../../../../common/components/common/calendar/DateRangePicker.vue";

export default defineComponent({
	props: ["data", "orderType", "visible"],
	components: {
		OrderTable,
		DateRangePicker,
	},
	setup(props, { emit }) {
		const { paymentStatus, permsArray } = common();
		const activeOrderType = ref("sales");
		const filters = reactive({
			payment_status: "all",
			user_id: undefined,
			dates: [],
		});
		const serachDateRangePicker = ref(null);

		const onClose = () => {
			filters.payment_status = "all";
			emit("closed");
		};

		watch(
			() => props.visible,
			(newVal, oldVal) => {
				if (newVal == true) {
					if (props.data.user_type == "suppliers") {
						activeOrderType.value =
							props.orderType == "" ? "purchases" : "purchase-returns";
					} else {
						activeOrderType.value =
							props.orderType == "" ? "sales" : "sales-returns";
					}

					filters.user_id = props.data.xid;
					filters.dates = [];

					if (serachDateRangePicker && serachDateRangePicker.value) {
						serachDateRangePicker.value.resetPicker();
					}
				}
			}
		);

		return {
			onClose,

			paymentStatus,
			filters,
			activeOrderType,
			drawerWidth: window.innerWidth <= 991 ? "90%" : "70%",
			serachDateRangePicker,
			permsArray,
		};
	},
});
</script>
