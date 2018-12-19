import exampleComponent from './components/ExampleComponent.vue'

import ElementUI from 'element-ui';

import Vue from 'vue'
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);
Vue.config.productionTip = false

window.Event = new Vue()

new Vue({
    el: '#app',

    components: {
        exampleComponent,
    },

    mounted() {
        $('[data-confirm]').on('click', function () {
            return confirm($(this).data('confirm'))
        })
    }
})