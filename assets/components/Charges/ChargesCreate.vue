<template>  
  <div class="row">
    <b-modal ref="modal" id="create-charge" v-show="showModal" hide-footer hide-header>      
      <div class="d-block text-center bg-success">

        <div class="row pull-right" style="margin-top: -20px; margin-right: 0">
          <span style="font-size: 2em; cursor: pointer" @click.prevent="hideModalCreate()">&times;</span>
        </div>

        <div class="pull-left">
          <i class="fa fa-plus-circle"></i> Nouvelle charge                                  
        </div>

        <div class="mt-2 mb-2">
          <input type="date" class="form-control form-control-sm text-primary mb-1" v-model="chargeNew.createdAt" value="" />
          <input type="text" class="form-control form-control-sm text-primary mb-1" v-model="chargeNew.libelle" value="" placeholder="Titre" />    
          <textarea class="form-control form-control-sm text-primary mb-1" rows="4" v-model="chargeNew.commentaires" value="" placeholder="Commentaires (optionnel)"></textarea> 
          <input type="number" class="form-control form-control-sm text-primary mb-1" v-model="chargeNew.montant" value="" placeholder="Montant en €" />     
        </div>        

        <div class="custom-control custom-switch text-left mb-2">
          <input type="checkbox" v-model="chargeNew.categorie" class="custom-control-input" id="categorie">
          <label class="custom-control-label" for="categorie">Fixe</label>
        </div>

        <b-button @click.prevent="saveCharge()" class="btn btn-xs bg-primary" id="btnSaverCharge" style="height: 65px; padding-top: 5px" block><i class="fa fa-save"></i> Enregistrer</b-button>
      </div>
    </b-modal>
  </div>
</template>

<script>

  // Imports
  import Axios from 'axios'
  import { EventBus } from '../../event-bus.js';

  export default {
    name: 'ChargesCreate',
    data() {
      return {
        chargeNew: {   
          createdAt: this.GetFormattedDate(new Date()),
          libelle: '',
          montant: '',
          commentaires: '',
          categorie: false,
        },                
        showModal: false,
      };
    },
    components: {
      Axios
    },
    mounted() {
      let app = this;
    },
    methods: {
      hideModalCreate() {
        let app = this;
        app.$refs.modal.hide();
      },
      saveCharge() {
        let app = this;

        if ((!isNaN(app.chargeNew.montant) && app.chargeNew.montant != "" ) && app.chargeNew.createdAt != "" && app.chargeNew.libelle != "") {

          // montant : 2 chiffres après la virgule
          let montantString = app.chargeNew.montant.toString().replace(',', '.');
          app.chargeNew.montant = parseFloat(montantString).toFixed(2);

          // Object for "backend"
          let chargeObject = {
            'charge' : app.chargeNew
          }

          Axios.post('api/charges/create', chargeObject, app.GetHeaders()).then(function (resp) {

            // Transfert charge vers "ChargesList"
            if (resp.data.message == 'save_charge_ok') {                         
              app.chargeNew = {   
                createdAt: app.GetFormattedDate(new Date()),
                libelle: '',
                commentaires: '',
                montant: '',
              };

              app.$toast.open({
                  message: 'Charge ajoutée avec succès !',
                  type: 'success',
                  position: 'top-right',
              });

              app.$emit("charge-ajoutee");
            }

          }).catch(function (err) {
              alert("Impossible d'enregistrer la charge. ");
          });
          
          app.hideModalCreate();       
        } else {
          alert('Au moins un champ est invalide.');
        }

      }
    }
  };
</script>

<style>
  .center {
    text-align: center;
  }
  textarea {
    resize: vertical;
  }
</style>
