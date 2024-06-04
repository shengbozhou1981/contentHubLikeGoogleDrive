import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.withXSRFToken = true;
axios.get('/sanctum/csrf-cookie').then(() => {});
const app = createApp(App);

app.use(router);

app.mount('#app');
