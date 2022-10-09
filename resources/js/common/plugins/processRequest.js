import { notification, message } from 'ant-design-vue';
import { forEach } from "lodash-es";

const processRequest = (configObject) => {

	const { url, data, success, error } = configObject;
	var formData = {};

	// Replace undefined values to null
	forEach(data, function (value, key) {
		if (value == undefined) {
			formData[key] = null;
		} else {
			formData[key] = value;
		}
	});

	axiosAdmin
		.post(url, formData)
		.then(response => {
			success(response.data);
		})
		.catch(errorResponse => {
			const err = errorResponse.data;
			const errorCode = errorResponse.status;
			var errorRules = {};

			if (errorCode == 422) {
				if (err.error && typeof err.error.details != "undefined") {
					var keys = Object.keys(err.error.details);
					for (var i = 0; i < keys.length; i++) {
						// Escape dot that comes with error in array fields
						var key = keys[i].replace(".", "\\.");

						errorRules[key] = {
							required: true,
							message: err.error.details[keys[i]][0],
						};
					}
				}

				error(errorRules);

				if (configObject.t) {
					message.error(configObject.t("common.fix_errors"));
				}
			} else if (errorCode == 403) {
				error(err);
			}
		}).then(() => {
			// button.disabled = false;
		});
}

export default processRequest;