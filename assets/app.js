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

// Routing
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// App
import App from './components/App'

// Components
import Charges from './components/Charges.vue'  

if (document.getElementById('app') !== null) {
  const routes = [
    {
        path: '/charges',
        components: {
            charges: Charges
        },
        name: 'chargesList'
    }
  ];

  // Routeur
  const router = new VueRouter({ routes });

  new Vue({
    el: '#app',
    template: '<app/>',
    components: {
        App,
        Charges
    }
  },
  
  { router })
}