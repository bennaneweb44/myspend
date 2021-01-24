<template>
  <div>
    <ChargesList ref="charges_list"></ChargesList>
  </div>
</template>

<script>

import Vue from 'vue'
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