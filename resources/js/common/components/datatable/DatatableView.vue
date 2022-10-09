<template>
  <div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-sm-12 col-md-6">
				<div>
					<label>Show 
						<select v-model="resultData.perPage" @change="fetchResults(1)" name="users-table_length" class="form-control form-control-sm">
							<option v-for="perPageEntry in resultData.perPageEntriesArray" :key="'per_page_entry'+ perPageEntry" :value="perPageEntry">{{ perPageEntry }}</option>
						</select> 
						entries
					</label>
				</div>
				</div>
				<div class="col-sm-12 col-md-6 text-right">
					<div>
						<label>Search:
							<input type="search" class="form-control form-control-sm" v-model="resultData.searchTerm" placeholder="" @change="searched" @keyup="searched">
							</label>
						</div>
				</div>
			</div>
			<slot></slot>
			<div class="row">
			<div class="col-sm-12 col-md-5">
				{{ pageDetailsMessage }}
			</div>
			<div class="col-sm-12 col-md-7 text-right">
				<nav class="d-inline-block">
					<ul class="pagination mb-0">
						<li class="page-item disabled">
							<a
								class="page-link"
								href="#"
								tabindex="-1"
							><i class="fas fa-chevron-left"></i></a>
						</li>
						<li
							v-for="page in pages"
							:key="'pagination_key_'+page"
							class="page-item"
							:class="{ 'active' : page == resultData.currentPageNumber, 'disabled': page == resultData.morePageString }"
							@click="fetchResults(page)"
						><a
								class="page-link"
								href="javascript:"
							>{{ page }} <span class="sr-only">(current)</span></a></li>
						<li class="page-item">
							<a
								class="page-link"
								href="#"
							><i class="fas fa-chevron-right"></i></a>
						</li>
					</ul>
				</nav>
			</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	props: [
		'datatableObject'
	],
	setup(props) {
		
		const { resultData, pages, fetchResults, pageDetailsMessage, searched } = props.datatableObject;
		
		return {
			resultData,
			pages,
			fetchResults,
			pageDetailsMessage,
			searched,
		}
	}
}
</script>

<style>

</style>