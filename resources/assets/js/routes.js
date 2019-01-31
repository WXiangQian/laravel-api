import VueRouter from 'vue-router'

import home from './components/Home.vue'
import about from './components/About.vue'

let routes = [
    {
        path: '/',
        component: home
    },
    {
        path: '/about',
        component: about
    },
];

export default new VueRouter({
    // 去掉/#/
    // mode: "history",
    routes,
});