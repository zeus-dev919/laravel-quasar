<template>
	<tr>
		<th v-for="field in resultData.fields" :key="'datatable_header-field_'+field.id">
			{{ field.name }} <i v-if="field.sortEnable" class="fa fa-sort"></i>
		</th>
	</tr>
</template>

<script>
export default {
	props: [
		'datatableObject'
	],
	setup(props) {
		
		const { resultData } = props.datatableObject;
		
		return {
			resultData
		}
	}
}
</script>

<style>

</style>