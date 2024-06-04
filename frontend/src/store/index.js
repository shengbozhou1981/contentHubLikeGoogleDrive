// import { createStore } from 'vuex';

// export default createStore({
//   state: {
//     user: JSON.parse(localStorage.getItem('user')) || null,
//   },
//   mutations: {
//     setUser(state, user) {
//       state.user = user;
//       localStorage.setItem('user', JSON.stringify(user));
//     },
//     logout(state) {
//       state.user = null;
//       localStorage.removeItem('user');
//     },
//   },
//   getters: {
//     loggedIn: state => state.user !== null,
//   },
// });