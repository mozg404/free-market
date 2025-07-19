import '../css/app.css';
import axios from 'axios';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { renderApp } from '@inertiaui/modal-vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
        return pages[`./pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: renderApp(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el)
    },
})
