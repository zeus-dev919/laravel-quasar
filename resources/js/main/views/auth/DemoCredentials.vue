<template>
	<div v-if="appEnv != 'envato'">
		<a-divider>
			{{ $t("common.demo_account_credentials") }}
		</a-divider>
		<a-table
			:pagination="false"
			:dataSource="demoCredentials"
			:columns="demoCredentialsColumns"
		>
			<template #bodyCell="{ column, record }">
				<template v-if="column.dataIndex === 'details'">
					Email: {{ record.email }} <br />
					Password: {{ record.password }}
				</template>
				<template v-if="column.dataIndex === 'action'">
					<a-tooltip
						@click="
							() => {
								credentials.email = record.email;
								credentials.password = record.password;
							}
						"
					>
						<template #title>
							{{
								$t("popover.click_here_to_copy_credentials", [
									record.name,
								])
							}}
						</template>
						<SnippetsOutlined />
					</a-tooltip>
				</template>
			</template>
		</a-table>
	</div>
</template>

<script>
import { defineComponent, reactive, ref } from "vue";
import { SnippetsOutlined } from "@ant-design/icons-vue";

export default defineComponent({
	props: ["credentials"],
	components: {
		SnippetsOutlined,
	},
	setup() {
		const appEnv = window.config.app_env;
		const demoCredentialsColumns = ref([
			{
				title: "Details",
				dataIndex: "details",
			},
			{
				title: "action",
				dataIndex: "action",
			},
		]);
		const demoCredentials = ref([
			{
				email: "admin@example.com",
				password: 12345678,
			},
		]);

		return {
			appEnv,
			demoCredentials,
			demoCredentialsColumns,
		};
	},
});
</script>
