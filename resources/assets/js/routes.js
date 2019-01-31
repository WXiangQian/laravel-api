import VueRouter from 'vue-router'

import home from './components/Home.vue'
import about from './components/About.vue'
import article from './components/Article.vue'

let routes = [
    {
        path: '/',
        component: home
    },
    {
        path: '/about',
        component: about
    },
    {
        path: '/article/:id',
        name: 'article',
        component: article
    },
];

export default new VueRouter({
    // 去掉/#/
    // mode: "history",
    routes,
});