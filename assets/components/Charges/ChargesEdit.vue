<template>  
  <div class="row">
    <b-modal ref="modal" id="edit-charge" v-show="showModal" hide-footer hide-header>      
      <div class="d-block text-center bg-success">

        <div class="row pull-right" style="margin-top: -20px; margin-right: 0">
          <span style="font-size: 2em; cursor: pointer" @click.prevent="hideModal()">&times;</span>
        </div>

        <div class="pull-left">
          <i class="fa fa-pencil-square "></i> Mise à jour                                  
        </div>

        <input type="date" class="form-control form-control-sm text-primary mb-1" v-model="charge.createdAt" value="" />
        <input type="text" class="form-control form-control-sm text-primary mb-1" v-model="charge.libelle" value="" placeholder="Titre" />    
        <input type="number" class="form-control form-control-sm text-primary mb-1" v-model="charge.montant" value="" placeholder="Montant en €" />     

        <b-button class="btn btn-xs bg-primary" id="btnSaverCharge" style="height: 50px; padding-top: 5px" block @click.prevent="showModal = false"><i class="fa fa-save"></i> Enregistrer</b-button>
      </div>
    </b-modal>
  </div>
</template>

<script>

  // Imports
  import Axios from 'axios'
  import { EventBus } from '../../event-bus.js';

  export default {
    name: 'ChargesEdit',
    data() {
      return {
        charge: {
          id: 0,          
          date: '',
          libelle: '',
          montant: 0
        },
        showModal: false
      };
    },
    components: {
      Axios
    },
    created() {
      let app = this;      
      app.charge = {
        id: 0,          
        createdAt: '',
        libelle: '',
        montant: 0
      }; 
      
      EventBus.$on('charge-a-modifier', chargeAModifier => {                
        app.charge = chargeAModifier;
        app.showModal = true;
      });                 
    },
    mounted() {
      let app = this;                        
    },
    beforeCreate() {
      let app = this;
      EventBus.$off('charge-a-modifier');           
    },    
    beforeDestroy() {
      let app = this;
      app.showModal = false;
      EventBus.$off('charge-a-modifier');
    },
    methods: {
      hideModal() {
        let app = this;
        app.$refs.modal.hide();
      }
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
