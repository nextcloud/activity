const axios = jest.createMockFromModule('@nextcloud/axios')

const wsData = require('./activity_ws.json')

axios.get = function(url) {
	return new Promise((resolve, reject) => {
		if (url === 'http://localhostundefined/ocs/v2.php/apps/activity/api/v2/activity/filter') {
			resolve({ data: wsData })
		} else {
			// eslint-disable-next-line no-console
			console.log(url)
		}
	})
}

export default axios
