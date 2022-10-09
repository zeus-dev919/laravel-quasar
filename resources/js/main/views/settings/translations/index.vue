<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.translations`)" class="p-0">
				<template #extra>
					<a-space>
						<LangAddButton
							v-if="
								permsArray.includes('translations_create') ||
								permsArray.includes('admin')
							"
							@onAddSuccess="langAdded"
							btnType="primary"
							:tooltip="false"
						>
							{{ $t("langs.add") }}
						</LangAddButton>
						<a-button
							type="primary"
							@click="$router.push({ name: 'admin.settings.langs.index' })"
						>
							<EyeOutlined />
							{{ $t("langs.view_all_langs") }}
						</a-button>
					</a-space>
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
					{{ $t(`menu.settings`) }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t("menu.translations") }}
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
				<a-space class="mb-30">
					<a-select
						v-model:value="group"
						show-search
						placeholder="Select group..."
						style="width: 200px"
						@change="getData"
					>
						<a-select-option
							v-for="groupKey in groupKeys"
							:key="groupKey"
							:value="groupKey"
							>{{ groupKey }}</a-select-option
						>
					</a-select>
					<FetchNewTranslation @fetchTranslationsSuccesss="getData" />
				</a-space>

				<a-table
					:columns="columns"
					:row-key="(record) => record.group"
					:data-source="table.data"
					:pagination="false"
				>
					<template #bodyCell="{ column, text, record }">
						<template v-for="langKey in langKeys" :key="langKey">
							<template v-if="column.dataIndex === langKey.key">
								<div class="editable-cell">
									<div
										v-if="editableData[record[`${langKey.key}_id`]]"
										class="editable-cell-input-wrapper"
									>
										<a-input
											v-model:value="
												editableData[record[`${langKey.key}_id`]]
													.value
											"
											@pressEnter="
												save(record[`${langKey.key}_id`])
											"
										/>
										<check-outlined
											class="editable-cell-icon-check"
											@click="save(record[`${langKey.key}_id`])"
										/>
									</div>
									<div v-else class="editable-cell-text-wrapper">
										{{ text || " " }}
										<edit-outlined
											class="editable-cell-icon"
											@click="edit(record, langKey.key)"
										/>
									</div>
								</div>
							</template>
						</template>
					</template>
				</a-table>
			</a-card>
		</a-col>
	</a-row>
</template>

<script>
import { onMounted, ref, reactive, computed } from "vue";
import {
	PlusOutlined,
	EditOutlined,
	CheckOutlined,
	EyeOutlined,
} from "@ant-design/icons-vue";
import { useStore } from "vuex";
import SettingSidebar from "../SettingSidebar.vue";
import common from "../../../../common/composable/common";
import FetchNewTranslation from "./FetchNewTranslation.vue";
import LangAddButton from "./langs/AddButton.vue";
import { setLangsLocaleMessage } from "../../../../common/i18n";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default {
	components: {
		EditOutlined,
		CheckOutlined,
		SettingSidebar,
		PlusOutlined,
		FetchNewTranslation,
		LangAddButton,
		EyeOutlined,
		AdminPageHeader,
	},
	setup() {
		const { permsArray } = common();
		const apiResponse = ref([]);
		const columns = ref([]);
		const langKeys = ref([]);
		const group = ref(undefined);
		const groupKeys = ref([]);
		const store = useStore();
		const selectedLang = computed(() => store.state.auth.lang);

		const table = reactive({
			data: [],
		});
		const editableData = reactive({});
		const activeTab = ref(0);

		const edit = (record, key) => {
			editableData[record[`${key}_id`]] = {
				id: record[`${key}_id`],
				value: record[key],
			};
		};

		const save = (id) => {
			axiosAdmin
				.post(`translations/${id}`, {
					value: editableData[id].value,
					_method: "PUT",
				})
				.then(() => {
					getData();
					delete editableData[id];
				});
		};

		var messages = {};

		const updateTableData = () => {
			const tableColumns = [
				{
					title: "Group",
					key: "group",
					dataIndex: "group",
					fixed: "left",
				},
			];
			const langColumns = [];
			const translationGroups = [];

			apiResponse.value.map((langData) => {
				tableColumns.push({
					title: langData.name,
					key: langData.key,
					dataIndex: langData.key,
				});
				langColumns.push({
					key: langData.key,
				});
			});

			langKeys.value = langColumns;

			columns.value = tableColumns;
			const groupKeyObject = [];

			apiResponse.value.map((lang) => {
				messages[lang.key] = {};

				lang.translations.map((translation) => {
					if (messages[lang.key][translation.group] == undefined) {
						messages[lang.key][translation.group] = {};

						if (lang.key == "en") {
							groupKeyObject.push(translation.group);
						}
					}
					messages[lang.key][translation.group][translation.key] = {
						id: translation.xid,
						value: translation.value,
					};
				});
			});

			// We only set group value at first time
			if (group.value === undefined) {
				group.value = groupKeyObject[0];
			}
			const myObj = messages["en"][group.value];

			const fullData = [];

			Object.keys(myObj).forEach((key) => {
				const firstObject = {};

				tableColumns.map((tblclm) => {
					if (tblclm.key == "group") {
						firstObject[tblclm.key] = key;
					} else {
						firstObject[tblclm.key] =
							messages[tblclm.key][group.value][key] &&
							messages[tblclm.key][group.value][key]["value"]
								? messages[tblclm.key][group.value][key]["value"]
								: "";
						firstObject[`${tblclm.key}_id`] =
							messages[tblclm.key][group.value][key] &&
							messages[tblclm.key][group.value][key]["id"]
								? messages[tblclm.key][group.value][key]["id"]
								: "";
					}
				});

				fullData.push(firstObject);
			});

			groupKeys.value = groupKeyObject;
			table.data = fullData;
		};

		onMounted(() => {
			getData();
		});

		const getData = () => {
			axiosAdmin.get("lang-trans").then((res) => {
				apiResponse.value = res.data.data;
				setLangsLocaleMessage(res, i18n, selectedLang.value);
				updateTableData();
			});
		};

		const langAdded = () => {
			getData();
		};

		return {
			columns,
			table,
			editableData,
			activeTab,
			langKeys,
			group,
			groupKeys,
			getData,
			edit,
			save,
			permsArray,
			langAdded,
		};
	},
};
</script>

<style lang="less">
.editable-cell {
	position: relative;
	.editable-cell-input-wrapper,
	.editable-cell-text-wrapper {
		padding-right: 24px;
	}

	.editable-cell-text-wrapper {
		padding: 5px 24px 5px 5px;
	}

	.editable-cell-icon,
	.editable-cell-icon-check {
		position: absolute;
		right: 0;
		width: 20px;
		cursor: pointer;
	}

	.editable-cell-icon {
		margin-top: 4px;
		display: none;
	}

	.editable-cell-icon-check {
		line-height: 28px;
	}

	.editable-cell-icon:hover,
	.editable-cell-icon-check:hover {
		color: #108ee9;
	}

	.editable-add-btn {
		margin-bottom: 8px;
	}
}
.editable-cell:hover .editable-cell-icon {
	display: inline-block;
}
</style>
