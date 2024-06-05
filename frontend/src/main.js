import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios';
import store from './store';  // Import your Vuex store


axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.withXSRFToken = true;
axios.get('/sanctum/csrf-cookie').then(() => {});
const app = createApp(App);

app.use(router);
app.use(store);  // Use your Vuex store


app.mount('#app');
