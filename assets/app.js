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

// Global methos
Vue.mixin({
  methods: {
    GetFormattedDate(date) {        
      let month = date . getMonth() +1;
      let day = date . getDate();
      let year = date . getFullYear();

      return year + '-' + (month < 10 ? '0' + month : month) + '-' + (day < 10 ? '0' + day : day);
    },
    GetHeaders() {
      let options = {
        headers: {
          'Content-Type': 'application/json'
        }
      }      
      return options;
    },
  },
})

// Components 
import ChargesList from './components/Charges/ChargesList.vue'  
import AlimentationList from './components/Alimentation/AlimentationList.vue'  

if (document.getElementById('appCharges') !== null) {
  const routes = [
    {
        path: '/charges',
        components: {
            ChargesList
        },
        name: 'charges_list',
    }
  ];

  // Routeur
  const router = new VueRouter({ mode: 'history', routes: routes });

  Vue.prototype.$eventBus = new Vue(); // Global event bus

  new Vue({
    el: '#appCharges',
    router: router,
    template: '<ChargesList/>',
    components: {
      ChargesList,
    },
  });
}
else if (document.getElementById('appAlimentation') !== null) {
  const routes = [
    {
      path: '/alimentation',
      components: {
          AlimentationList,
      },
      name: 'alimentation_list',
    }
  ];

  // Routeur
  const router = new VueRouter({ mode: 'history', routes: routes });

  Vue.prototype.$eventBus = new Vue(); // Global event bus

  new Vue({
    el: '#appAlimentation',
    router: router,
    template: '<alimentationList/>',
    components: {
      AlimentationList,
    },
  });
}