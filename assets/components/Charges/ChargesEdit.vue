<template>  
  <div class="row">
    <b-modal ref="modal" id="edit-charge" v-show="showModal" hide-footer hide-header>      
      <div class="d-block text-center bg-success">

        <div class="row pull-right" style="margin-top: -20px; margin-right: 0">
          <span style="font-size: 2em; cursor: pointer" @click.prevent="hideModal()">&times;</span>
        </div>

        <div class="pull-left">
          <i class="fa fa-pencil-square"></i> Mise à jour                          
        </div>        
        <b-button @click.prevent="deleteCharge(chargeToEdit.id)" class="btn btn-xs bg-danger text-center" style="height: 65px; padding-top: 5px" block><i class="fa fa-trash"></i> Supprimer</b-button>          

        <div class="mt-2 mb-2">
          <input type="date" class="form-control form-control-sm text-primary mb-1" v-model="chargeToEdit.updatedAt" value="" />
          <input type="text" class="form-control form-control-sm text-primary mb-1" v-model="chargeToEdit.libelle" value="" placeholder="Titre" />   
          <textarea class="form-control form-control-sm text-primary mb-1" v-model="chargeToEdit.commentaires" value="" placeholder="Commentaires (optionnel)"></textarea>  
          <input type="number" class="form-control form-control-sm text-primary mb-1" v-model="chargeToEdit.montant" value="" placeholder="Montant en €" />     
        </div>        

        <div class="custom-control custom-switch text-left mb-2">
          <input type="checkbox" v-model="categorieChecked" v-bind:checked="chargeToEdit.categorie.id == 1" class="custom-control-input" id="categorie">
          <label class="custom-control-label" for="categorie">Fixe</label>
        </div>

        <b-button @click.prevent="updateCharge(chargeToEdit.id)" class="btn btn-xs bg-primary" style="height: 65px; padding-top: 5px" block><i class="fa fa-save"></i> Enregistrer</b-button>        
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
        chargeToEdit: {
          id: 0,          
          updatedAt: '',
          libelle: '',
          commentaires: '',
          montant: 0,
          categorie: false,
        },
        categorieChecked: false,
        showModal: false
      };
    },
    components: {
      Axios,
    },
    mounted() {
      let app = this;       
      EventBus.$on('charge-a-modifier', chargeAModifier => {                                
        app.chargeToEdit = chargeAModifier;
        app.categorieChecked = app.chargeToEdit.categorie.id == 1 ? true : false;
      });   
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
      },
      updateCharge(id) {
        let app = this;

        if (!isNaN(id)) {

          // montant : 2 chiffres après la virgule
          let montantString = app.chargeToEdit.montant.toString().replace(',', '.');
          app.chargeToEdit.montant = parseFloat(montantString).toFixed(2);

          // Catégorie
          app.chargeToEdit.categorie = app.categorieChecked;          

          // Object for "backend"
          let chargeObject = {
            'charge' : app.chargeToEdit
          }

          Axios.put('api/charges/update/'+id, chargeObject, app.GetHeaders()).then(function (resp) {
            // Transfert charge vers "ChargesList"
            if (resp.data.message == 'save_charge_ok') {
              app.$emit('charge-modifiee');
              app.$toast.open({
                  message: 'Charge mise à jour avec succès !',
                  type: 'success',
              });
            }

          }).catch(function (err) {
              alert("Impossible de mettre à jour la charge. ");
          });

          app.hideModal();
        }
      },
      deleteCharge(id) {
        let app = this;

        if (!isNaN(id)) {
          
          Axios.delete('api/charges/delete/'+id, app.GetHeaders()).then(function (resp) {
            // Transfert charge vers "ChargesList"
            if (resp.data.message == 'delete_charge_ok') {
              app.$toast.open({
                  message: 'Charge supprimée !',
                  type: 'error',
              });
              app.$emit('charge-supprimee');
            }

          }).catch(function (err) {
              alert("Impossible de mettre à jour la charge. ");
          });

          app.hideModal();
        }
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
