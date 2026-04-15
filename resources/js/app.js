import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import Swal from 'sweetalert2';

import App from './App.vue';

const app = createApp(App);

app.use(createPinia());
app.use(router);

app.config.globalProperties.$swal = Swal;
window.Swal = Swal;

app.mount('#app');
