import { forEach } from "lodash-es";

const checkUserPermission = (permissionName, user) => {
	var permissionAllowed = user.role.name == "admin" ? true : false;

	forEach(user.role.perms, (permission) => {
		if (permission.name == permissionName) {
			permissionAllowed = true;
		}
	});

	return permissionAllowed;
};

const getSubtotalAmount = (product) => {
	if (product.sales_tax_type == 'inclusive') {
		var taxRate = product.tax.rate;

		var salesPrice = product.sales_price;
		var finalSalesPrice = (salesPrice * 100) / (100 + taxRate);

		return finalSalesPrice;
	} else {
		return product.sales_price;
	}
}

const getOrderTax = (product) => {
	if (product.tax && product.tax.rate != '') {
		var taxRate = product.tax.rate;
		var salesPrice = product.sales_price;

		if (product.sales_tax_type == 'exclusive') {
			var taxAmount = (salesPrice * (taxRate / 100));
			return taxAmount;
		} else {
			var singleUnitPrice = (salesPrice * 100) / (100 + taxRate);
			var taxAmount = (singleUnitPrice) * (taxRate / 100);
			return taxAmount
		}
	} else {
		return 0;
	}
}

const getSalesPriceWithTax = (product) => {
	if (product.sales_tax_type == 'exclusive' && product.tax_id != '' && product.tax) {
		var taxRate = product.tax.rate;

		var salesPrice = product.sales_price;
		var taxAmount = (salesPrice * (taxRate / 100));
		var finalSalesPrice = salesPrice + taxAmount;

		return finalSalesPrice;
	} else {
		return product.sales_price;
	}
}

const buildAddress = (address) => {
	return `${address.address}, ${address.city}, ${address.state}, ${address.zipcode}, ${address.country}`;
};

export {
	checkUserPermission,
	getOrderTax,
	getSubtotalAmount,
	buildAddress,
	getSalesPriceWithTax
};