<template>  
  <div class="bg-primary">
    <b-modal ref="modal" id="edit-alimentation" v-show="showModal" hide-footer hide-header>      
      <div class="d-block text-center bg-info">

        <div class="row pull-right" style="margin-top: -20px; margin-right: 0">
          <span style="font-size: 2em; cursor: pointer" @click.prevent="hideModal()">&times;</span>
        </div>

        <div class="pull-left">
          <i class="fa fa-pencil-square"></i> Mise à jour                          
        </div>        
        <b-button @click.prevent="deleteAlimentation(alimentationToEdit.id)" class="btn btn-xs bg-danger text-center" style="height: 65px; padding-top: 5px" block><i class="fa fa-trash"></i> Supprimer</b-button>          

        <div class="mt-2 mb-2">
          <input type="date" class="form-control form-control-sm text-primary mb-1" v-model="alimentationToEdit.updatedAt" value="" />
          <input type="text" class="form-control form-control-sm text-primary mb-1" v-model="alimentationToEdit.libelle" value="" placeholder="Titre" />    
          <input type="number" class="form-control form-control-sm text-primary mb-1" v-model="alimentationToEdit.montant" value="" placeholder="Montant en €" />     

          <select v-model="idCurrentCategory" class="col-md-12 form-control form-control-sm mb-1" style="font-size: 1em; ">                  
            <option v-for="categorie in allCategories" v-bind:value="categorie.id"  :key="categorie.id" style="font-size: .5em; width: max-content; padding: 2px">
              {{ categorie.label }}
            </option>
          </select>      

        </div>        

        <b-button @click.prevent="updateAlimentation(alimentationToEdit.id)" class="btn btn-xs bg-primary" style="height: 65px; padding-top: 5px" block><i class="fa fa-save"></i> Enregistrer</b-button>        
      </div>
    </b-modal>
  </div>
</template>

<script>

  // Imports
  import Axios from 'axios'
  import { EventBus } from '../../event-bus.js';

  export default {
    name: 'AlimentationEdit',
    data() {
      return {
        alimentationToEdit: {
          id: 0,          
          updatedAt: '',
          libelle: '',
          montant: 0,
          categorie: false
        },
        allCategories: {

        },
        selectedCategory: null,
        idCurrentCategory: 0,
        showModal: false
      };
    },
    components: {
      Axios
    },
    mounted() {
      let app = this;       
      EventBus.$on('alimentation-a-modifier', alimentationAModifier => {      
        app.alimentationToEdit = alimentationAModifier;        
        app.idCurrentCategory = alimentationAModifier.categorie.id;

        // todo : select element in current category     
        Axios.get('api/alimentation/categories/list').then(function (resp) {
          // Toutes les catégories
          app.allCategories = resp.data;        
        }).catch(function (err) {
            alert("Impossible de charger la liste des catégories d'alimentation.");
        });      
      });   
    },
    beforeCreate() {
      let app = this;
      EventBus.$off('alimentation-a-modifier');  
    },    
    beforeDestroy() {
      let app = this;
      app.showModal = false;
      EventBus.$off('alimentation-a-modifier');         
    },
    methods: {
      hideModal() {
        let app = this;
        app.$refs.modal.hide();
      },
      updateAlimentation(id) {
        let app = this;

        if (!isNaN(id)) {

          // Catégorie sélectionnée
          app.allCategories.forEach(element => {
            if (element.id == app.idCurrentCategory) {              
              app.alimentationToEdit.categorie = element;
            }
          });

          // montant : 2 chiffres après la virgule
          let montantString = app.alimentationToEdit.montant.toString().replace(',', '.');
          app.alimentationToEdit.montant = parseFloat(montantString).toFixed(2);    

          // Object for "backend"
          let alimentationObject = {
            'alimentation' : app.alimentationToEdit
          }

          Axios.put('api/alimentation/update/'+id, alimentationObject, app.GetHeaders()).then(function (resp) {
            // Transfert alimentation vers "AlimentationList"
            if (resp.data.message == 'save_alimentation_ok') {
              app.$emit('alimentation-modifiee');
            }

          }).catch(function (err) {
              alert("Impossible de mettre à jour l'alimentation. ");
          });

          app.hideModal();
        }
      },
      deleteAlimentation(id) {
        let app = this;

        if (!isNaN(id)) {
          
          Axios.delete('api/alimentation/delete/'+id, app.GetHeaders()).then(function (resp) {
            // Transfert alimentation vers "AlimentationList"
            if (resp.data.message == 'delete_alimentation_ok') {
              app.$emit('alimentation-supprimee');
            }

          }).catch(function (err) {
              alert("Impossible de mettre à jour l'alimentation. ");
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
