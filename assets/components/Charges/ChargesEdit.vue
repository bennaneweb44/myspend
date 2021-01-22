<template>  
  <div>
    <div class="text-center mb-2" style="text-align: center !important">
      <i class="fa fa-pencil-square"></i>  Charge
    </div>   

    <input type="date" class="form-control form-control-sm text-primary mb-1" v-model="charge.date" placeholder="Date de la charge" />
    <input type="text" class="form-control form-control-sm text-primary mb-1" v-model="charge.libelle" placeholder="Titre" />    
    <input type="number" class="form-control form-control-sm text-primary mb-1" v-model="charge.montant"  placeholder="Montant en €" />     

    <b-button class="btn btn-xs bg-primary" style="height: 50px; padding-top: 5px" block @click="$bvModal.hide('bv-modal-example')"><i class="fa fa-save"></i> Enregistrer</b-button>
  </div>
</template>

<script>

  // Imports
  import Axios from 'axios'
  import { EventBus } from '../../event-bus.js';

  export default {
    name: 'ChargesList',
    data() {
      return {
        charge: {}
      };
    },
    components: {
      Axios
    },
    created() {
      let app = this;      
      app.charge = {
        id: 0,          
        date: '',
        libelle: '',
        montant: 0
      }; 

      EventBus.$off('charge-a-modifier');
      EventBus.$on('charge-a-modifier', chargeAModifier => {
        app.charge = chargeAModifier;
        console.warn(chargeAModifier);
      });                 
    },
    mounted() {
      let app = this;                        
    },    
    methods: {
      
    }
  };
</script>

<style>
  .center {
    text-align: center;
  }
  h5.card-title, h5.card-title > i {
    font-size: 26px !important;
  }
</style>
