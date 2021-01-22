<template>
  <div>
    <ChargesList></ChargesList>
    <ChargesEdit class="d-none"></ChargesEdit>
  </div>
</template>

<script>

import ChargesList from './Charges/ChargesList.vue'
import ChargesEdit from './Charges/ChargesEdit.vue'

export default {
  name: 'app',
  data() {
    return {
      
    };
  },
  components: {
    ChargesList,
    ChargesEdit
  }
};
</script>

<style>
    .center {
      text-align: center;
    }
</style>