import app from './components/App'

import 'element-ui/lib/theme-chalk/index.css';
import router from './routes'

import ElementUI from 'element-ui';
import VueRouter from 'vue-router';

import Vue from 'vue'
Vue.use(ElementUI);
Vue.use(VueRouter);

Vue.config.productionTip = false

window.Event = new Vue()

router.beforeEach((to, from, next) => {
    /* 路由发生变化修改页面meta */
    if(to.meta.content){
        let head = document.getElementsByTagName('head');
        let meta = document.createElement('meta');
        meta.content = to.meta.content;
        head[0].appendChild(meta)
    }
    /* 路由发生变化修改页面title */
    if (to.meta.title) {
        document.title = to.meta.title;
    }
    next()
});

new Vue({
    el: '#app',
    router,
    template: '',
    components: {
        app,
    },

    mounted() {
        $('[data-confirm]').on('click', function () {
            return confirm($(this).data('confirm'))
        })
    }
})