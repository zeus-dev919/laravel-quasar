<template>
	<a-drawer
		:title="pageTitle"
		:width="drawerWidth"
		:visible="visible"
		:body-style="{ paddingBottom: '80px' }"
		:footer-style="{ textAlign: 'right' }"
		:maskClosable="false"
		@close="onClose"
	>
		<a-form layout="vertical">
			<a-row :gutter="16">
				<a-col
					:xs="24"
					:sm="24"
					:md="permsArray.includes('admin') ? 12 : 24"
					:lg="permsArray.includes('admin') ? 12 : 24"
				>
					<a-form-item
						:label="$t('expense.expense_category')"
						name="expense_category_id"
						:help="
							rules.expense_category_id
								? rules.expense_category_id.message
								: null
						"
						:validateStatus="rules.expense_category_id ? 'error' : null"
						class="required"
					>
						<span style="display: flex">
							<a-select
								v-model:value="formData.expense_category_id"
								:placeholder="
									$t('common.select_default_text', [
										$t('expense.expense_category'),
									])
								"
								:allowClear="true"
								optionFilterProp="label"
								show-search
							>
								<a-select-option
									v-for="expenseCategory in expenseCategories"
									:key="expenseCategory.xid"
									:value="expenseCategory.xid"
									:label="expenseCategory.name"
								>
									{{ expenseCategory.name }}
								</a-select-option>
							</a-select>
							<ExpenseCategoryAddButton
								@onAddSuccess="expenseCategoryAdded"
							/>
						</span>
					</a-form-item>
				</a-col>
				<a-col
					v-if="permsArray.includes('admin')"
					:xs="24"
					:sm="24"
					:md="permsArray.includes('admin') ? 12 : 24"
					:lg="permsArray.includes('admin') ? 12 : 24"
				>
					<a-form-item
						:label="$t('expense.created_by_user')"
						name="user_id"
						:help="rules.user_id ? rules.user_id.message : null"
						:validateStatus="rules.user_id ? 'error' : null"
					>
						<span style="display: flex">
							<a-select
								v-model:value="formData.user_id"
								:placeholder="
									$t('common.select_default_text', [$t('expense.user')])
								"
								:allowClear="true"
								option-label-prop="label"
								optionFilterProp="label"
								show-search
							>
								<a-select-option
									v-for="staffMember in staffMembers"
									:key="staffMember.xid"
									:value="staffMember.xid"
									:label="staffMember.name"
								>
									<UserInfo :user="staffMember" size="small" />
								</a-select-option>
							</a-select>
							<StaffAddButton @onAddSuccess="staffMemberAdded" />
						</span>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="12" :lg="12">
					<a-form-item
						:label="$t('expense.date')"
						name="date"
						:help="rules.date ? rules.date.message : null"
						:validateStatus="rules.date ? 'error' : null"
						class="required"
					>
						<a-date-picker
							v-model:value="formData.date"
							:format="appSetting.date_format"
							valueFormat="YYYY-MM-DD"
							:disabled-date="disabledDate"
							style="width: 100%"
						/>
					</a-form-item>
				</a-col>
				<a-col :xs="24" :sm="24" :md="12" :lg="12">
					<a-form-item
						:label="$t('expense.amount')"
						name="amount"
						:help="rules.amount ? rules.amount.message : null"
						:validateStatus="rules.amount ? 'error' : null"
						class="required"
					>
						<a-input-number
							v-model:value="formData.amount"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('expense.amount'),
								])
							"
							min="0"
							style="width: 100%"
						>
							<template #addonBefore>
								{{ appSetting.currency.symbol }}
							</template>
						</a-input-number>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('expense.bill')"
						name="bill"
						:help="rules.bill ? rules.bill.message : null"
						:validateStatus="rules.bill ? 'error' : null"
					>
						<UploadFile
							:acceptFormat="'image/*,.pdf'"
							:formData="formData"
							folder="expenses"
							uploadField="bill"
							@onFileUploaded="
								(file) => {
									formData.bill = file.file;
									formData.bill_url = file.file_url;
								}
							"
						/>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('expense.notes')"
						name="notes"
						:help="rules.notes ? rules.notes.message : null"
						:validateStatus="rules.notes ? 'error' : null"
					>
						<a-textarea
							v-model:value="formData.notes"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('expense.notes'),
								])
							"
							:rows="4"
						/>
					</a-form-item>
				</a-col>
			</a-row>
		</a-form>
		<template #footer>
			<a-button
				type="primary"
				@click="onSubmit"
				style="margin-right: 8px"
				:loading="loading"
			>
				{{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
			</a-button>
			<a-button @click="onClose">
				{{ $t("common.cancel") }}
			</a-button>
		</template>
	</a-drawer>
</template>
<script>
import { defineComponent, ref, onMounted } from "vue";
import { PlusOutlined, LoadingOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../../common/composable/apiAdmin";
import UserInfo from "../../../../common/components/user/UserInfo.vue";
import common from "../../../../common/composable/common";
import ExpenseCategoryAddButton from "../expense-categories/AddButton.vue";
import StaffAddButton from "../../users/StaffAddButton.vue";
import UploadFile from "../../../../common/core/ui/file/UploadFile.vue";

export default defineComponent({
	props: [
		"formData",
		"data",
		"visible",
		"url",
		"addEditType",
		"pageTitle",
		"successMessage",
	],
	components: {
		PlusOutlined,
		LoadingOutlined,
		UserInfo,
		ExpenseCategoryAddButton,
		StaffAddButton,
		UploadFile,
	},
	setup(props, { emit }) {
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const expenseCategories = ref({});
		const staffMembers = ref({});
		const { appSetting, disabledDate, permsArray } = common();
		const expenseCategoryUrl = "expense-categories?limit=10000";
		const staffMemberUrl =
			"users?fields=xid,name,profile_image,profile_image_url,x_role_id,role{xid,name,display_name}&limit=10000";

		onMounted(() => {
			const expenseCategoriesPromise = axiosAdmin.get(expenseCategoryUrl);
			const staffMembersPromise = axiosAdmin.get(staffMemberUrl);

			Promise.all([expenseCategoriesPromise, staffMembersPromise]).then(
				([expenseCategoriesResponse, staffMembersResponse]) => {
					expenseCategories.value = expenseCategoriesResponse.data;
					staffMembers.value = staffMembersResponse.data;
				}
			);
		});

		const onSubmit = () => {
			addEditRequestAdmin({
				url: props.url,
				data: props.formData,
				successMessage: props.successMessage,
				success: (res) => {
					emit("addEditSuccess", res.xid);
				},
			});
		};

		const onClose = () => {
			rules.value = {};
			emit("closed");
		};

		const expenseCategoryAdded = () => {
			axiosAdmin.get(expenseCategoryUrl).then((response) => {
				expenseCategories.value = response.data;
			});
		};

		const staffMemberAdded = () => {
			axiosAdmin.get(staffMemberUrl).then((response) => {
				staffMembers.value = response.data;
			});
		};

		return {
			loading,
			rules,
			onClose,
			onSubmit,
			disabledDate,
			permsArray,

			expenseCategories,
			staffMembers,

			drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
			appSetting,

			expenseCategoryAdded,
			staffMemberAdded,
		};
	},
});
</script>

<style>
.ant-calendar-picker {
	width: 100%;
}
</style>
