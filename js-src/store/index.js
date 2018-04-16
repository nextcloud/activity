import Vue from 'vue';
import Vuex from 'vuex';
import settings from './settings';

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production';

const mutations = {
	API_FAILURE(state, error) {
		console.log(state, error);
	}
};

export default new Vuex.Store({
	modules: {
		settings
	},
	strict: debug,

	mutations
});
