const axios = jest.createMockFromModule('@nextcloud/axios')

const wsData = require('./activity_ws.json')

axios.get = function(url) {
	return new Promise((resolve, reject) => {
		if (url === 'http://localhost/ocs/v2.php/apps/activity/api/v2/activity/filter') {
			resolve({ data: wsData })
		} else {
			reject(new Error(`URL not defined ${url}`))
		}
	})
}

export default axios
