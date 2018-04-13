import api from './api';

const state = {
	feedLink: ''
};
const mutations = {
	setFeedLink(state, data) {
		state.feedLink = data;
	}
};
const getters = {
	getFeedLink(state) {
		return state.feedLink;
	}
}
const actions = {}

export default {state, mutations, getters, actions};
