/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css'

// start the Stimulus application
import './bootstrap'

// Vue
import Vue from 'vue'
Vue.use(require('vue-moment'))

// Modal
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

// Routing
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// App
import App from './components/App'

// Components 
import ChargesList from './components/Charges/ChargesList.vue'  
import ChargesEdit from './components/Charges/ChargesEdit.vue'  

if (document.getElementById('app') !== null) {
  const routes = [
    {
        path: '/charges',
        components: {
            chargesList: ChargesList,
            chargesEdit: ChargesEdit
        },
        name: 'charges_list'
    }
  ];

  // Routeur
  const router = new VueRouter({ routes });

  Vue.prototype.$eventBus = new Vue(); // Global event bus

  new Vue({
    el: '#app',
    router: router,
    template: '<app/>',
    components: {
      App
    }
  });
}