import exampleComponent from './components/ExampleComponent.vue'
import test from './components/Test.vue'
import articles from './components/Articles.vue'

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
        exampleComponent,
        test,
        articles,
    },

    mounted() {
        $('[data-confirm]').on('click', function () {
            return confirm($(this).data('confirm'))
        })
    }
})