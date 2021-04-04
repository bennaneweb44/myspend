<template>  
  <div class="row">
    <b-modal ref="modal" id="create-alimentation" v-show="showModal" hide-footer hide-header>      
      <div class="d-block text-center bg-info">

        <div class="row pull-right" style="margin-top: -20px; margin-right: 0">
          <span style="font-size: 2em; cursor: pointer" @click.prevent="hideModalCreate()">&times;</span>
        </div>

        <div class="pull-left">
          <i class="fa fa-plus-circle"></i> Nouvelle alimentation                                  
        </div>

        <div class="mt-2 mb-2">
          <input type="date" class="form-control form-control-sm text-primary mb-1" v-model="alimentationNew.createdAt" value="" />
          <input type="text" class="form-control form-control-sm text-primary mb-1" v-model="alimentationNew.libelle" value="" placeholder="Titre" />   
          <textarea class="form-control form-control-sm text-primary mb-1" v-model="alimentationNew.commentaires" value="" placeholder="Commentaires (optionnel)"></textarea> 
          <input type="number" class="form-control form-control-sm text-primary mb-1" v-model="alimentationNew.montant" value="" placeholder="Montant en €" />    

          <select v-model="alimentationNew.idCategorie" class="col-md-12 form-control form-control-sm mb-1" style="font-size: 1em; ">                  
            <option v-for="categorie in allCategories" v-bind:value="categorie.id"  :key="categorie.id" style="font-size: .5em; width: max-content; padding: 2px">
              {{ categorie.label }}
            </option>
          </select>  

        </div>     

        <b-button @click.prevent="saveAlimentation()" class="btn btn-xs bg-primary" id="btnSaverCharge" style="height: 65px; padding-top: 5px" block><i class="fa fa-save"></i> Enregistrer</b-button>
      </div>
    </b-modal>
  </div>
</template>

<script>

  // Imports
  import Axios from 'axios'
  import { EventBus } from '../../event-bus.js';

  export default {
    name: 'AlimentationCreate',
    data() {
      return {
        allCategories: {

        },
        alimentationNew: {   
          createdAt: this.GetFormattedDate(new Date()),
          libelle: '',
          montant: '',
          commentaires: '',
          idCategorie: 0
        },                
        showModal: false
      };
    },
    components: {
      Axios
    },
    mounted() {
      let app = this;

      this.loadCategoriesWithDefault()      ;
    },
    methods: {
      hideModalCreate() {
        let app = this;
        app.$refs.modal.hide();
      },
      saveAlimentation() {
        let app = this;

        if ((!isNaN(app.alimentationNew.montant) && app.alimentationNew.montant != "" ) && app.alimentationNew.createdAt != "" && app.alimentationNew.libelle != "" && app.alimentationNew.idCategorie > 0) {

          // montant : 2 chiffres après la virgule
          let montantString = app.alimentationNew.montant.toString().replace(',', '.');
          app.alimentationNew.montant = parseFloat(montantString).toFixed(2);

          // Object for "backend"
          let alimentationObject = {
            'alimentation' : app.alimentationNew
          }

          Axios.post('api/alimentation/create', alimentationObject, app.GetHeaders()).then(function (resp) {

            // Transfert alimentation vers "AlimentationList"
            if (resp.data.message == 'save_alimentation_ok') {                         
              app.alimentationNew = {   
                createdAt: app.GetFormattedDate(new Date()),
                libelle: '',
                montant: '',
                commentaires: '',
              };
              app.$toast.open({
                  message: 'Alimentation ajoutée avec succès !',
                  type: 'success',
                  position: 'top-right',
              });
              app.$emit("alimentation-ajoutee");
            }

          }).catch(function (err) {
              alert("Impossible d'enregistrer l'alimentation. ");
          });
          
          app.loadCategoriesWithDefault();
          app.hideModalCreate();       
        } else {
          alert('Au moins un champ est invalide.');
        }

      },
      loadCategoriesWithDefault() {
        let app = this;

        // todo : select element in current category     
        Axios.get('api/alimentation/categories/list').then(function (resp) {
          // Toutes les catégories
          app.allCategories = resp.data;                
          // Premier choix par défaut
          app.alimentationNew.idCategorie = app.allCategories[0].id;
        }).catch(function (err) {
            alert("Impossible de charger la liste des catégories d'alimentation.");
        });
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
