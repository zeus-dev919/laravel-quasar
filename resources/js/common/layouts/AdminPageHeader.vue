<template>
	<div class="breadcrumb-header">
		<a-card class="page-content-sub-header breadcrumb-left-border">
			<a-row>
				<a-col :span="24">
					<slot key="header" name="header"></slot>
				</a-col>
				<a-col :span="24">
					<slot key="breadcrumb" name="breadcrumb"></slot>
				</a-col>
			</a-row>
		</a-card>
	</div>
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
	setup() {
		return {};
	},
});
</script>

<style></style>
