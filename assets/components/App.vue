<template>
  <div>
    <ChargesList ref="charges_list"></ChargesList>
  </div>
</template>

<script>

// Vue
import Vue from 'vue'

// Long click for mobile
import { longClickDirective } from 'vue-long-click'
const longClickInstance = longClickDirective({delay: 400, interval: 50})
Vue.directive('longclick', longClickInstance)

// Components
import ChargesList from './Charges/ChargesList.vue'

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
    }
  },
})

export default {
  name: 'app',
  data() {
    return {      
    }
  },
  components: {
    ChargesList
  }
}

</script>

<style>
  .center {
    text-align: center;
  }
</style>