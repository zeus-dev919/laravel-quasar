<template>
	<div class="user-info" v-if="user">
		<figure>
			<a-avatar :size="size ? size : 40" :src="user.profile_image_url" />
		</figure>
		<figcaption>
			<h5 class="user-name">
				{{ user.name }}
			</h5>
			<span
				v-if="user.user_type == 'users' && user.role_id"
				class="user-designation"
			>
				{{ user.role.display_name }}
			</span>
			<span
				v-else-if="user.user_type == 'customers' || user.user_type == 'suppliers'"
			>
				{{ user.phone ? user.phone : user.user_type }}
			</span>
		</figcaption>
	</div>
	<div v-else>
		<span>
			{{ $t("user.walk_in_customer") }}
		</span>
	</div>
</template>

<script>
import { computed } from "vue";

export default {
	props: ["user", "size"],
};
</script>

<style lang="less">
.user-info {
	display: flex;
	align-items: center;
	figure {
		margin: 0 8px 0;
	}
	.user-name {
		margin-bottom: 4px;
		font-weight: 600;
		font-size: 15px;
		line-height: 15px;
	}
	.user-designation {
		font-size: 13px;
		font-weight: 400;
		color: #9299b8;
	}
}
</style>
