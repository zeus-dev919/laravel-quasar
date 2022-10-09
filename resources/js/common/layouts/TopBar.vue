<template>
	<a-layout-header class="topbar-menu bg-color" :style="{ padding: '0 16px' }">
		<a-row>
			<a-col :span="12">
				<a-space>
					<MenuOutlined class="trigger" @click="showHideMenu" />
				</a-space>
			</a-col>
			<a-col :span="12">
				<HeaderRightIcons>
					<!-- <div class="">
						<MenuMode />
					</div>
					<a-divider type="vertical" /> -->

					<ChangeWarehouse v-if="permsArray.includes('admin')" />
					<a-divider type="vertical" />
					<a-dropdown placement="bottomRight">
						<a
							class="ant-dropdown-link"
							style="margin-right: 20px"
							@click.prevent
						>
							{{ selectedLang }}
							<DownOutlined />
						</a>
						<template #overlay>
							<a-menu>
								<a-menu-item
									v-for="lang in langs"
									:key="lang.key"
									@click="langSelected(lang.key)"
								>
									<a-space>
										<a-avatar
											shape="square"
											size="small"
											:src="lang.image_url"
										/>
										{{ lang.name }}
									</a-space>
								</a-menu-item>
							</a-menu>
						</template>
					</a-dropdown>
					<a-avatar size="small" :src="user.profile_image_url" />
				</HeaderRightIcons>
			</a-col>
		</a-row>
	</a-layout-header>
</template>

<script>
import { ref, reactive, computed, onMounted } from "vue";
import { useStore } from "vuex";
import { MenuOutlined, DownOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import { loadLocaleMessages } from "../i18n";
import { HeaderRightIcons } from "./style";
import common from "../../common/composable/common";
import MenuMode from "./MenuMode.vue";
import ChangeWarehouse from "./ChangeWarehouse.vue";

export default {
	props: ["collapsed"],
	components: {
		MenuOutlined,
		DownOutlined,
		HeaderRightIcons,
		MenuMode,
		ChangeWarehouse,
	},
	setup(props, { emit }) {
		const { user, permsArray } = common();
		const store = useStore();
		const selectedLang = ref(store.state.auth.lang);
		const { locale, t } = useI18n();
		const themeMode = ref(window.config.theme_mode == "light" ? false : true);
		const themeModeLoading = ref(false);

		const langSelected = async (lang) => {
			store.commit("auth/updateLang", lang);
			await loadLocaleMessages(i18n, lang);

			selectedLang.value = lang;
			locale.value = lang;
		};

		const showHideMenu = () => {
			emit("onSidebarMenuClick", !props.collapsed);
		};

		const logout = () => {
			store.dispatch("auth/logout");
		};

		const themeModeChanged = (checked) => {
			const mode = checked ? "dark" : "light";
			themeModeLoading.value = true;

			axiosAdmin
				.post("change-theme-mode", {
					theme_mode: mode,
				})
				.then((response) => {
					if (response.data.status == "success") {
						window.location.reload();
					}
					themeModeLoading.value = false;
				});
		};

		return {
			permsArray,
			logout,
			showHideMenu,
			langSelected,
			selectedLang,
			langs: computed(() => store.state.auth.allLangs),

			user,

			themeMode,
			themeModeChanged,
			themeModeLoading,
		};
	},
};
</script>

<style lang="less">
.trigger {
	font-size: 18px;
	line-height: 64px;
	padding-top: 8px;
	// padding: 0 24px;
	cursor: pointer;
	transition: color 0.3s;
}

.trigger:hover {
	color: #1890ff;
}
</style>
