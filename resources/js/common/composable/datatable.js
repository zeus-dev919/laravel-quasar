import { reactive, ref } from "vue";
import { forEach, debounce, forOwn, includes } from "lodash-es";

const datatable = () => {

	const table = reactive({
		data: [],
		pagination: {
			pageSize: 10
		},
		loading: false,
		sorter: {},
		searchColumn: undefined,
		searchString: "",
		searchStatus: undefined,
		filterLoading: false,
		filterableColumns: [],
		selectedRowKeys: [],
	});
	const currentPage = ref(1);
	const tableUrl = ref({});
	const hashable = ref([]);

	const queryData = (params) => {
		const limit = params.limit;
		const offset = (params.page - 1) * limit;

		const url = generateUrl(limit, offset);
		return axiosAdmin.get(url);
	};

	const generateUrl = (limit, offset) => {
		var url = tableUrl.value.url;
		var filterString = "";
		var hashableString = "";
		var trimString = false;
		var trimHashable = false;

		// Filters
		if ('filterString' in tableUrl.value && tableUrl.value.filterString != "" && typeof tableUrl.value.filterString == "string") {
			filterString += `${tableUrl.value.filterString} and `;
			trimString = true;
		}

		if (typeof tableUrl.value.filters == "object") {
			forOwn(tableUrl.value.filters, (value, key) => {
				if (value != undefined && value != "") {
					filterString += `${key} eq "${value}" and `;
					trimString = true;

					// May be Hashable
					if (includes(hashable.value, key)) {
						hashableString += `${value},`
						trimHashable = true;
					}
				}
			});
		}

		if (table.searchColumn != undefined && table.searchString != "") {
			filterString += `${table.searchColumn} lk "%${table.searchString}%" and `;
			trimString = true;
			table.filterLoading = true;
		} else if (table.searchString != "" && table.filterableColumns.length > 0) {
			var newSearchString = "";
			forEach(table.filterableColumns, (filterColumn) => {
				newSearchString += `${filterColumn.key} lk "%${table.searchString}%" or `;
			});

			if (newSearchString.length > 0) {
				filterString += `(${newSearchString.substring(0, newSearchString.length - 3)})`;
				trimString = false;
			}
			table.filterLoading = true;
		}
		if (filterString.length > 0 && trimString == true) {
			url += `&filters=${encodeURIComponent(filterString.substring(0, filterString.length - 5))}`;
		} else if (filterString.length > 0 && trimString == false) {
			url += `&filters=${encodeURIComponent(filterString)}`;
		}



		// Extra Filters
		// Used for sending as query params
		// like ?order_type=purchase
		if (tableUrl.value.extraFilters && typeof tableUrl.value.extraFilters == "object") {
			forOwn(tableUrl.value.extraFilters, (value, key) => {
				if (value != undefined && value != "") {
					url += `&${key}=${value}`;

					// May be Hashable
					if (includes(hashable.value, key)) {
						hashableString += `${value},`
						trimHashable = true;
					}
				}
			});
		}

		// Order
		if (table.sorter && table.sorter.field) {
			const sortOrder = table.sorter.order == "ascend" ? "asc" : "desc";
			url += `&order=${table.sorter.field} ${sortOrder}`;
		} else {
			url += "&order=id desc";
		}

		// Offset and Limit
		url += `&offset=${offset}&limit=${limit}`;

		// Hashable
		if (trimHashable) {
			hashableString = hashableString.substring(0, hashableString.length - 1);
			url += `&hashable=${hashableString}`;
		}

		return url;
	}

	const handleTableChange = (pagination, filters, sorter) => {
		const pager = { ...table.pagination };
		pager.current = pagination.current;
		pager.pageSize = pagination.pageSize;
		table.pagination = pager;
		currentPage.value = pagination.current;
		table.sorter = sorter;

		fetch({
			limit: pagination.pageSize,
			page: pagination.current,
			sortField: sorter.field,
			sortOrder: sorter.order,
			...filters,
		});
	};

	const fetch = (params = {}) => {
		table.loading = true;
		queryData({
			limit: table.pagination.pageSize,
			...params,
		}).then((results) => {
			const data = results.data;
			const pagination = { ...table.pagination };
			// Read total count from server
			// pagination.total = data.totalCount;
			pagination.total = results.meta.paging.total;
			table.loading = false;
			table.data = data;
			table.pagination = pagination;
			table.filterLoading = false;

			if (params.success != undefined) {
				params.success(data);
			}
		});
	};

	const onTableSearch = debounce(() => {
		fetch({
			page: 1,
		});
	}, 400);

	const onRowSelectChange = (rowKeys) => {
		table.selectedRowKeys = rowKeys;
	};

	return {
		table,
		tableUrl,
		currentPage,
		hashable,

		handleTableChange,
		fetch,

		onTableSearch,
		onRowSelectChange,
	};
}

export default datatable;