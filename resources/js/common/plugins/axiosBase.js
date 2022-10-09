import axios from 'axios';

var axiosBase = axios.create({
	baseURL: window.config.path + '/api/v1'
});

// Axios default headers
axiosBase.defaults.headers['Accept'] = 'application/json';
axiosBase.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axiosBase.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;

// Axios error listener
axiosBase.interceptors.response.use(function (response) {
	return response.data;
}, function (error) {
	const errorCode = error.response.status;
	if (errorCode === 401) {
		// If error 401 redirect to login
		window.location.href = window.config.path;

		// throw new Error('Unauthorized');
	} else {
		// If it is an uncontrolled error, display http status
		// Ladda.stopAll();
		// swal('Error ' + error.response.status, error.response.statusText, 'error');
		return Promise.reject(error.response);
	}
});

/**
 * Set global so you don't have to import it
 */
window.axiosBase = axiosBase;
