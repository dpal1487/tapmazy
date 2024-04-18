import('./bootstrap');
import 'bootstrap-icons/font/bootstrap-icons.css'
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { VueReCaptcha,useReCaptcha } from 'vue-recaptcha-v3'
// Import modules...
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import CKEditor from '@ckeditor/ckeditor5-vue';

import Vue3Toastify from 'vue3-toastify';
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

import "vue3-toastify/dist/index.css";
import VueClipboard from 'vue3-clipboard'

import Echo from "laravel-echo";
// window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: "socket.io",
    // host: "http://localhost:6001",
    host: window.location.hostname + ":6001",
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    //
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue")),
    setup({ el, app, props, plugin }) {
        const captcheKey = props.initialPage.props.recaptcha_site_key;
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(CKEditor)
            .use(VueClipboard, {
                autoSetContainer: true,
                appendToBody: true,
            })
            .use(Vue3Toastify, { autoClose: 2000, theme: "colored" })
            .use(VueReCaptcha, { siteKey: captcheKey,loaderOptions: {useRecaptchaNet: true}})
            .mixin({ methods: { route } })
            .mount(el);
    },
});

// InertiaProgress.init({ color: '#4B5563' });

InertiaProgress.init({ color: '#ad3861' });
