import VueRouter from 'vue-router'

import home from './components/pages/Home.vue'
import about from './components/pages/About.vue'
import article from './components/articles/Article.vue'
import NotFoundComponent from './components/common/NotFoundComponent'
import error from './components/common/401'

let routes = [
    {
        path: '/',
        component: home
    },
    {
        path: '/about',
        component: about,
        meta: {
            title: '关于我们',
        },
    },
    {
        path: '/article/:id',
        name: 'article',
        component: article
    },
    {
        path: '*',
        component: NotFoundComponent,
        meta: {
            title: '404没有找到页面',
        },
    },
    {
        path: '/401',
        component: error,
        meta: {
            title: '401没有权限',
        },
    }
];

export default new VueRouter({
    // 去掉/#/
    // mode: "history",
    routes,
});