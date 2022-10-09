<template>
	<div v-if="permsArray.includes('users_create') || permsArray.includes('admin')">
		<a-tooltip
			v-if="tooltip"
			placement="topLeft"
			:title="$t('staff_member.add')"
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
			:url="staffMemberAddEditUrl"
			@addEditSuccess="addEditSuccess"
			@closed="onClose"
			:addEditData="formData"
			:data="formData"
			:pageTitle="$t('staff_member.add')"
			:successMessage="$t('staff_member.created')"
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
		const { staffMemberInitData, staffMemberAddEditUrl } = fields();
		const visible = ref(false);
		const addEditType = ref("add");
		const formData = ref({ ...staffMemberInitData });

		const showAdd = () => {
			visible.value = true;
		};

		const addEditSuccess = () => {
			visible.value = false;
			formData.value = { ...staffMemberInitData };
			emit("onAddSuccess");
		};

		const onClose = () => {
			visible.value = false;
		};

		return {
			permsArray,
			visible,
			addEditType,
			staffMemberAddEditUrl,
			formData,
			addEditSuccess,
			onClose,
			showAdd,
		};
	},
});
</script>
