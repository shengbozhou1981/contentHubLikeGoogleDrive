import { createStore } from 'vuex';

export default createStore({
  state: {
    user: JSON.parse(localStorage.getItem('user')) || null,
    recentItems: [],
    deletedItems: [],
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
      localStorage.setItem('user', JSON.stringify(user));
    },
    clearUser(state) {
      state.user = null;
      localStorage.removeItem('user');
    },
    ADD_RECENT_ITEM(state, item) {
      state.recentItems.push(item);
    },
    ADD_DELETED_ITEM(state, item) {
      state.deletedItems.push(item);
    },
  },
  actions: {
    addRecentItem({ commit }, item) {
      commit('ADD_RECENT_ITEM', item);
    },
    addDeletedItem({ commit }, item) {
      commit('ADD_DELETED_ITEM', item);
    },
  },
  getters: {
    recentItems: state => state.recentItems,
    deletedItems: state => state.deletedItems,
  },
});