<template>
	<div v-if="permsArray.includes('suppliers_create') || permsArray.includes('admin')">
		<a-tooltip
			v-if="tooltip"
			placement="topLeft"
			:title="$t('supplier.add')"
			arrow-point-at-center
		>
			<a-button @click="showAdd" class="ml-5 no-border-radius" :type="btnType">
				<template #icon> <PlusOutlined /> </template>
				<slot></slot>
			</a-button>
		</a-tooltip>
		<a-button v-else @click="showAdd" class="ml-5 no-border-radius" :type="btnType">
			<template #icon> <PlusOutlined /> </template>
			<slot></slot>
		</a-button>

		<AddEdit
			:addEditType="addEditType"
			:visible="visible"
			:url="supplierAddEditUrl"
			@addEditSuccess="addEditSuccess"
			@closed="onClose"
			:addEditData="formData"
			:data="formData"
			:pageTitle="$t('supplier.add')"
			:successMessage="$t('supplier.created')"
		/>
	</div>
</template>

<script>
import { defineComponent, ref } from "vue";
import { PlusOutlined } from "@ant-design/icons-vue";
import AddEdit from "./AddEdit.vue";
import fields from "./fields";
import common from "../../../common/composable/common";

export default defineComponent({
	props: {
		btnType: {
			default: "default",
		},
		tooltip: {
			default: true,
		},
	},
	emits: ["onAddSuccess"],
	components: {
		PlusOutlined,
		AddEdit,
	},
	setup(props, { emit }) {
		const { permsArray } = common();
		const { supplierInitData, supplierAddEditUrl } = fields();
		const visible = ref(false);
		const addEditType = ref("add");
		const formData = ref({ ...supplierInitData });

		const showAdd = () => {
			visible.value = true;
		};

		const addEditSuccess = () => {
			visible.value = false;
			formData.value = { ...supplierInitData };
			emit("onAddSuccess");
		};

		const onClose = () => {
			visible.value = false;
		};

		return {
			permsArray,
			visible,
			addEditType,
			supplierAddEditUrl,
			formData,
			addEditSuccess,
			onClose,
			showAdd,
		};
	},
});
</script>
