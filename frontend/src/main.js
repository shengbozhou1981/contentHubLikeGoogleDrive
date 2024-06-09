import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios';
import store from './store';  // Import your Vuex store
import '@fortawesome/fontawesome-free/css/all.css'
import '@mdi/font/css/materialdesignicons.css'
import './assets/css/main.css'
import Toast, { POSITION } from 'vue-toastification';
import 'vue-toastification/dist/index.css';


axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.withXSRFToken = true;
axios.get('/sanctum/csrf-cookie').then(() => {});
const app = createApp(App);

app.use(router);
app.use(store);  // Use your Vuex store
app.use(Toast,{
    position: POSITION.CENTER
  });

app.mount('#app');
// Make the app instance available globally
window.app = app