<template>
  <div>
    <ChargesList></ChargesList>
  </div>
</template>

<script>

import Vue from 'vue'
import ChargesList from './Charges/ChargesList.vue'

// Global methos
Vue.mixin({
  methods: {
    GetFormattedDate(date) {        
      var month = date . getMonth() +1;
      var day = date . getDate();
      var year = date . getFullYear();
      return year + '-' + (month < 10 ? '0' + month : month) + '-' + day;
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