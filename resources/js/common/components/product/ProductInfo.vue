<template>
	<div class="user-info">
		<figure>
			<a-avatar :size="40" :src="product.image_url" />
		</figure>
		<figcaption>
			<h1 class="user-name">
				{{ product.name }}
			</h1>
			<span v-if="product.category_id" class="user-designation">
				{{ product.category.name }}
			</span>
		</figcaption>
	</div>
</template>

<script>
export default {
	props: ["product"],
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
		font-size: 16px;
		line-height: 15px;
	}
	.user-designation {
		font-size: 13px;
		font-weight: 400;
		color: #9299b8;
	}
}
</style>
