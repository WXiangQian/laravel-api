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