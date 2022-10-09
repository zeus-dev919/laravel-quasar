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
			<a-row>
				<a-col :xs="24" :sm="24" :md="6" :lg="6">
					<a-form-item
						:label="$t('user.profile_image')"
						name="profile_image"
						:help="rules.profile_image ? rules.profile_image.message : null"
						:validateStatus="rules.profile_image ? 'error' : null"
					>
						<Upload
							:formData="formData"
							folder="user"
							imageField="profile_image"
							@onFileUploaded="
								(file) => {
									formData.profile_image = file.file;
									formData.profile_image_url = file.file_url;
								}
							"
						/>
					</a-form-item>
				</a-col>
				<a-col :xs="24" :sm="24" :md="18" :lg="18">
					<a-row
						:gutter="16"
						v-if="
							formData.user_type == 'staff_members' &&
							permsArray.includes('admin')
						"
					>
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('user.role')"
								name="role_id"
								:help="rules.role_id ? rules.role_id.message : null"
								:validateStatus="rules.role_id ? 'error' : null"
								class="required"
							>
								<span style="display: flex">
									<a-select
										v-model:value="formData.role_id"
										:placeholder="
											$t('common.select_default_text', [
												$t('user.role'),
											])
										"
										:allowClear="true"
									>
										<a-select-option
											v-for="role in roles"
											:key="role.xid"
											:value="role.xid"
										>
											{{ role.display_name }}
										</a-select-option>
									</a-select>
									<RoleAddButton @onAddSuccess="roleAdded" />
								</span>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('user.name')"
								name="name"
								:help="rules.name ? rules.name.message : null"
								:validateStatus="rules.name ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.name"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('user.name'),
										])
									"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('user.phone')"
								name="phone"
								:help="rules.phone ? rules.phone.message : null"
								:validateStatus="rules.phone ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.phone"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('user.phone'),
										])
									"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row v-if="formData.user_type == 'staff_members'">
						<a-col :span="24">
							<a-form-item
								:label="$t('user.password')"
								name="password"
								:help="rules.password ? rules.password.message : null"
								:validateStatus="rules.password ? 'error' : null"
								class="required"
							>
								<a-input-password
									v-model:value="formData.password"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('user.password'),
										])
									"
								/>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16">
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('user.email')"
								name="email"
								:help="rules.email ? rules.email.message : null"
								:validateStatus="rules.email ? 'error' : null"
								class="required"
							>
								<a-input
									v-model:value="formData.email"
									:placeholder="
										$t('common.placeholder_default_text', [
											$t('user.email'),
										])
									"
								/>
							</a-form-item>
						</a-col>
						<a-col :xs="24" :sm="24" :md="12" :lg="12">
							<a-form-item
								:label="$t('user.status')"
								name="status"
								:help="rules.status ? rules.status.message : null"
								:validateStatus="rules.status ? 'error' : null"
								class="required"
							>
								<a-select
									v-model:value="formData.status"
									:placeholder="
										$t('common.select_default_text', [
											$t('user.status'),
										])
									"
									:allowClear="true"
								>
									<a-select-option value="enabled"
										>Enabled</a-select-option
									>
									<a-select-option value="disabled"
										>Disabled</a-select-option
									>
								</a-select>
							</a-form-item>
						</a-col>
					</a-row>
					<a-row :gutter="16" v-if="formData.user_type != 'staff_members'">
						<a-col :xs="24" :sm="24" :md="24" :lg="24">
							<a-form-item
								:label="$t('user.opening_balance')"
								name="opening_balance"
								:help="
									rules.opening_balance
										? rules.opening_balance.message
										: null
								"
								:validateStatus="rules.opening_balance ? 'error' : null"
							>
								<a-input
									v-model:value="formData.opening_balance"
									placeholder="0"
								>
									<template #prefix>
										{{ appSetting.currency.symbol }}
									</template>
									<template #addonAfter>
										<a-select
											v-model:value="formData.opening_balance_type"
											style="width: 100px"
										>
											<a-select-option value="receive">
												{{ $t("user.receive") }}
											</a-select-option>
											<a-select-option value="pay">
												{{ $t("user.pay") }}
											</a-select-option>
										</a-select>
									</template>
								</a-input>
							</a-form-item>
						</a-col>
					</a-row>
				</a-col>
			</a-row>

			<a-row :gutter="16" v-if="formData.user_type != 'staff_members'">
				<a-col :xs="24" :sm="24" :md="12" :lg="12">
					<a-form-item
						:label="$t('user.credit_period')"
						name="credit_period"
						:help="rules.credit_period ? rules.credit_period.message : null"
						:validateStatus="rules.credit_period ? 'error' : null"
					>
						<a-input-number
							v-model:value="formData.credit_period"
							placeholder="0"
							:addon-after="$t('user.days')"
							type="number"
							:precision="0"
							:style="{ width: '100%' }"
						/>
					</a-form-item>
				</a-col>
				<a-col :xs="24" :sm="24" :md="12" :lg="12">
					<a-form-item
						:label="$t('user.credit_limit')"
						name="credit_limit"
						:help="rules.credit_limit ? rules.credit_limit.message : null"
						:validateStatus="rules.credit_limit ? 'error' : null"
					>
						<a-input
							v-model:value="formData.credit_limit"
							placeholder="0"
							:addon-before="appSetting.currency.symbol"
						/>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="
							formData.user_type == 'staff_members'
								? $t('user.address')
								: $t('user.billing_address')
						"
						name="address"
						:help="rules.address ? rules.address.message : null"
						:validateStatus="rules.address ? 'error' : null"
					>
						<a-textarea
							v-model:value="formData.address"
							:placeholder="
								$t('common.placeholder_default_text', [
									formData.user_type == 'staff_members'
										? $t('user.address')
										: $t('user.billing_address'),
								])
							"
							:auto-size="{ minRows: 2, maxRows: 3 }"
						/>
					</a-form-item>
				</a-col>
			</a-row>

			<a-row :gutter="16" v-if="formData.user_type != 'staff_members'">
				<a-col :xs="24" :sm="24" :md="24" :lg="24">
					<a-form-item
						:label="$t('user.shipping_address')"
						name="shipping_address"
						:help="
							rules.shipping_address ? rules.shipping_address.message : null
						"
						:validateStatus="rules.shipping_address ? 'error' : null"
					>
						<a-textarea
							v-model:value="formData.shipping_address"
							:placeholder="
								$t('common.placeholder_default_text', [
									$t('user.shipping_address'),
								])
							"
							:auto-size="{ minRows: 2, maxRows: 3 }"
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
				<template #icon> <SaveOutlined /> </template>
				{{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
			</a-button>
			<a-button @click="onClose">
				{{ $t("common.cancel") }}
			</a-button>
		</template>
	</a-drawer>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import { useStore } from "vuex";
import apiAdmin from "../../../common/composable/apiAdmin";
import Upload from "../../../common/core/ui/file/Upload.vue";
import RoleAddButton from "../settings/roles/AddButton.vue";
import common from "../../../common/composable/common";

export default defineComponent({
	props: [
		"addEditData",
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
		SaveOutlined,
		Upload,
		RoleAddButton,
	},
	setup(props, { emit }) {
		const { permsArray, user, appSetting } = common();
		const { addEditRequestAdmin, loading, rules } = apiAdmin();
		const roles = ref([]);
		const formData = ref({});
		const roleUrl = "roles?limit=10000";
		const store = useStore();

		onMounted(() => {
			const rolesPromise = axiosAdmin.get(roleUrl);

			Promise.all([rolesPromise]).then(([rolesResponse]) => {
				roles.value = rolesResponse.data;
			});

			formData.value = { ...props.addEditData };
		});

		const onSubmit = () => {
			addEditRequestAdmin({
				url: props.url,
				data: formData.value,
				successMessage: props.successMessage,
				success: (res) => {
					emit("addEditSuccess", res.xid);

					if (user.value.xid == res.xid) {
						store.dispatch("auth/updateUser");
					}
				},
			});
		};

		const onClose = () => {
			rules.value = {};
			emit("closed");
		};

		const roleAdded = () => {
			axiosAdmin.get(roleUrl).then((response) => {
				roles.value = response.data;
			});
		};

		watch(props, (newVal, oldVal) => {
			formData.value =
				newVal.addEditType == "add"
					? { ...newVal.addEditData }
					: {
							...newVal.addEditData,
							role_id:
								newVal.data.role && newVal.data.role.xid
									? newVal.data.role.xid
									: undefined,
							opening_balance:
								newVal.data.details && newVal.data.details.opening_balance
									? newVal.data.details.opening_balance
									: undefined,
							opening_balance_type:
								newVal.data.details &&
								newVal.data.details.opening_balance_type
									? newVal.data.details.opening_balance_type
									: undefined,
							credit_period:
								newVal.data.details && newVal.data.details.credit_period
									? newVal.data.details.credit_period
									: undefined,
							credit_limit:
								newVal.data.details && newVal.data.details.credit_limit
									? newVal.data.details.credit_limit
									: undefined,
							_method: "PUT",
					  };
		});

		return {
			loading,
			rules,
			onClose,
			onSubmit,
			roles,
			formData,

			roleAdded,
			permsArray,
			appSetting,

			drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
		};
	},
});
</script>
