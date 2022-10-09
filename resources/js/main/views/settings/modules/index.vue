<template>
	<AdminPageHeader>
		<template #header>
			<a-page-header :title="$t(`menu.modules`)" class="p-0" />
		</template>
		<template #breadcrumb>
			<a-breadcrumb separator="-" style="font-size: 12px">
				<a-breadcrumb-item>
					<router-link :to="{ name: 'admin.dashboard.index' }">
						{{ $t(`menu.dashboard`) }}
					</router-link>
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t("menu.settings") }}
				</a-breadcrumb-item>
				<a-breadcrumb-item>
					{{ $t("menu.modules") }}
				</a-breadcrumb-item>
			</a-breadcrumb>
		</template>
	</AdminPageHeader>

	<a-row>
		<a-col :xs="24" :sm="24" :md="24" :lg="4" :xl="4" class="bg-setting-sidebar">
			<SettingSidebar />
		</a-col>
		<a-col :xs="24" :sm="24" :md="24" :lg="20" :xl="20">
			<a-row v-if="offers && offers.length > 0">
				<a-col :span="24">
					<a-carousel arrows>
						<template #prevArrow>
							<div
								class="custom-slick-arrow"
								style="left: 10px; z-index: 1"
							>
								<left-circle-outlined />
							</div>
						</template>
						<template #nextArrow>
							<div class="custom-slick-arrow" style="right: 10px">
								<right-circle-outlined />
							</div>
						</template>
						<div v-for="offer in offers" :key="offer.id">
							<a-typography-link :href="offer.url" target="_blank">
								<img
									:src="offer.image_url"
									:style="{
										width: settings.width,
										height: settings.height,
									}"
									:preview="false"
								/>
							</a-typography-link>
						</div>
					</a-carousel>
				</a-col>
			</a-row>

			<a-card class="page-content-container">
				<a-row class="mt-20">
					<a-col :span="24">
						<div class="table-responsive" v-if="allModules">
							<a-table
								:columns="modulesTableColumns"
								:row-key="(record) => record.verified_name"
								:data-source="allModules"
								:pagination="false"
							>
								<template #bodyCell="{ column, record }">
									<template v-if="column.dataIndex == 'name'">
										<a-list-item>
											<a-list-item-meta :title="record.name">
												<template #description>
													<div class="module-description">
														{{ record.description }}
														<br />
														<div class="mt-5">
															<a-tag
																:color="
																	record.is_free
																		? 'green'
																		: 'geekblue'
																"
															>
																{{
																	record.is_free
																		? $t(
																				"common.free"
																		  )
																		: $t(
																				"common.paid"
																		  )
																}}
															</a-tag>
														</div>
													</div>
												</template>
												<template #avatar>
													<a-avatar :src="record.image_url" />
												</template>
											</a-list-item-meta>
										</a-list-item>
									</template>
									<template v-if="column.dataIndex == 'verified'">
										<VerifyModule
											:module="record"
											@verifySuccess="getModuleData"
										/>
									</template>
									<template v-if="column.dataIndex == 'status'">
										<ModuleStatus
											:module="record"
											:status="record.status"
											:moduleName="record.verified_name"
											@updateSuccess="getModuleData"
										/>
									</template>
									<template v-if="column.dataIndex == 'action'">
										<ModuleAction
											:module="record"
											@install="installModule"
											:downloadPercentage="downloadPercentage"
											:downloading="downloading"
											:extracting="extracting"
										/>
									</template>
								</template>
							</a-table>
						</div>
					</a-col>
				</a-row>
			</a-card>
		</a-col>
	</a-row>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import { LeftCircleOutlined, RightCircleOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import modules from "../../../../common/composable/modules";
import ModuleStatus from "./ModuleStatus.vue";
import VerifyModule from "./VerifyModule.vue";
import ModuleAction from "./ModuleAction.vue";
import SettingSidebar from "../SettingSidebar.vue";
import AdminPageHeader from "../../../../common/layouts/AdminPageHeader.vue";

export default defineComponent({
	components: {
		ModuleStatus,
		VerifyModule,
		ModuleAction,
		LeftCircleOutlined,
		RightCircleOutlined,
		SettingSidebar,
		AdminPageHeader,
	},
	setup() {
		const { t } = useI18n();

		const {
			allModules,
			getModuleData,
			offers,
			settings,
			install,
			downloading,
			downloadPercentage,
			extracting,
		} = modules();

		const modulesTableColumns = [
			{
				title: t("module.name"),
				dataIndex: "name",
				width: "25%",
			},
			{
				title: t("module.verified"),
				dataIndex: "verified",
				width: "15%",
			},
			{
				title: t("module.current_version"),
				dataIndex: "current_version",
			},
			{
				title: t("module.latest_version"),
				dataIndex: "version",
			},
			{
				title: t("module.status"),
				dataIndex: "status",
			},
			{
				title: t("common.action"),
				dataIndex: "action",
			},
		];

		onMounted(() => {
			getModuleData();
		});

		const installModule = (moduleName) => {
			install(moduleName);
		};

		return {
			modulesTableColumns,
			allModules,
			getModuleData,
			installModule,

			offers,
			settings,
			downloading,
			downloadPercentage,
			extracting,
		};
	},
});
</script>

<style lang="less">
.module-description {
	max-width: 300px;
	word-wrap: break-word; /* IE 5.5-7 */
	white-space: -moz-pre-wrap; /* Firefox 1.0-2.0 */
	white-space: pre-wrap; /* current browsers */
}
</style>
