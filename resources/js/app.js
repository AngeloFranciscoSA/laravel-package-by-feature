import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import Swal from 'sweetalert2';

window.Swal = Swal;

const modulePages = import.meta.glob('../../app/Modules/**/Resources/Pages/**/*.vue');

createInertiaApp({
    title: (title) => title,
    resolve: (name) => {
        const [module, ...rest] = name.split('/');
        const page = rest.join('/');
        const key = `../../app/Modules/${module}/Resources/Pages/${page}.vue`;
        return modulePages[key]();
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createPinia());

        app.config.globalProperties.$swal = Swal;
        app.mount(el);
    },
});
