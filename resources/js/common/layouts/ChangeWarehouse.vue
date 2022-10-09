<template>
	<a-dropdown placement="bottomRight">
		<a class="ant-dropdown-link" @click.prevent>
			{{ selectedWarehouse.name }}
			<DownOutlined />
		</a>
		<template #overlay>
			<a-menu>
				<a-menu-item
					v-for="warehouse in allWarehouses"
					:key="warehouse.xid"
					@click="warehouseChanged(warehouse.xid)"
				>
					{{ warehouse.name }}
				</a-menu-item>
			</a-menu>
		</template>
	</a-dropdown>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import { DownOutlined } from "@ant-design/icons-vue";
import { useStore } from "vuex";
import common from "../../common/composable/common";

export default defineComponent({
	components: {
		DownOutlined,
	},
	setup() {
		const store = useStore();
		const { selectedWarehouse, allWarehouses } = common();

		const warehouseChanged = (selectedWarehouseId) => {
			axiosAdmin
				.post("change-warehouse", { warehouse_id: selectedWarehouseId })
				.then((response) => {
					store.commit("auth/updateWarehouse", response.data.warehouse);
				});
		};

		return {
			allWarehouses,
			selectedWarehouse,
			warehouseChanged,
		};
	},
});
</script>

<style></style>
