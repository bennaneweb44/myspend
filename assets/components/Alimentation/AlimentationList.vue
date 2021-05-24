<template>  
  <div class="row"> 
    
    <div class="row col-md-12 mb-5">

      <div class="col-md-4 col-sm-4 col-2">
          <b-button v-b-modal.filter-alimentation 
            v-show="!currentDateFilter"
            class="fa fa-filter pull-left p-1 btnFilter" 
            title="Filtrer par date"
            style="margin-left: 25px;">              
          </b-button>          
          <div class="disabled" style="margin-top: -5px !important">                       
            <a v-show="currentDateFilter" @click.prevent="deleteDateFilter()" class="" style="padding-left: 25px; padding-right: 10px; font-size: 1.5em; cursor: pointer; color: darkred">
              <i class="fa fa-times-circle" title="Supprimer filtre"></i>  
            </a>
             <input type="text" v-model="currentDateFilter" disabled style="width: 45% !important; background-color: transparent; border: none; font-size: 1.2em; font-weight: bold; font-family: inherit;" />
          </div>
      </div>

      <div class="col-md-4 col-sm-4 col-8 text-center">
        <label href="#" class="btn-warning pl-3 pr-3" style="font-weight: bold; border: 2px solid #000; border-radius: 12px; color: #000 ">{{ total.toString().replace(".", ",") }} €</label>                   
      </div>
      
      <div class="col-md-4 col-sm-4 col-2">
        <b-button v-b-modal.create-alimentation 
          class="fa fa-plus-circle pull-right p-1 btnCreate" 
          title="Nouvelle alimentation"></b-button>
      </div>

    </div>
    
    
    <div v-for="alimentation in alimentations" :key="alimentation.id" class="card col-4 colxs6">
      <!-- background-color: #BAADCD !important; -->
      <div class="card-body bg-info" style="border: 3px solid #000; border-radius: 12px; padding-top: 15px; padding-left: 15px;">          
          <h5 class="card-title" style="font-size: 1.5em;">              
            <i class="fa fa-calendar"></i> {{ alimentation.updatedAt | moment("DD/MM/YYYY") }}
            <b-button v-b-modal.edit-alimentation class="fa fa-pencil-square-o pull-right p-1 text-primary btnEdit"                
              @click.prevent="showModalEdit(alimentation.id)" 
              title="Modifer"></b-button>
          </h5>            
          <p class="card-text">{{ alimentation.libelle }}</p>  
          
          <div class="text-right">
            <div class="text-left categorieDiv">
              <label href="#" class="btn-default pl-1 pr-1 categorieLabel"><i :class="alimentation.categorie.icone"></i> {{ alimentation.categorie.label }}</label>                                               
            </div>                        
            <label href="#" class="btn-primary pl-1 pr-1 montantVignette pull-right" style="margin-right: 13px; margin-bottom: 42px" >{{ parseFloat(alimentation.montant).toFixed(2).toString().replace(".", ",") }} €</label>            
          </div>
      </div>
    </div>       

    <AlimentationCreate @alimentation-ajoutee="getAlimentationList"></AlimentationCreate>
    <AlimentationEdit @alimentation-modifiee="getAlimentationList" @alimentation-supprimee="getAlimentationList"></AlimentationEdit>
    <FilterAlimentation @alimentations-filtrees="getAlimentationFilteredList"></FilterAlimentation>    
    
  </div>
</template>

<script>

  // Imports
  import Axios from 'axios'
  import { EventBus } from '../../event-bus.js'

  // Components
  import AlimentationEdit from './AlimentationEdit.vue'
  import AlimentationCreate from './AlimentationCreate.vue'
  import FilterAlimentation from './FilterAlimentation.vue'

  export default {
    name: 'AlimentationList',
    data() {
      return {
        alimentations: {},
        alimentationAModifier: {
          id: 0,          
          updatedAt: '',
          libelle: '',
          montant: 0,
          commentaires: '',
          categorie: null,
        },        
        currentDateFilter: null,
        total: 0,
      };
    },
    components: {
      Axios,
      AlimentationEdit,
      AlimentationCreate,
      FilterAlimentation,
    },
    created() {
      let app = this;       
                          
      if (localStorage.getItem('currentDateAlimentationAffichage')) {
        app.currentDateFilter = localStorage.getItem('currentDateAlimentationAffichage');
        app.getAlimentationFilteredList();
      } else {
        app.getAlimentationList();  
      }
    },
    mounted() {
      let app = this;            
    },    
    methods: {
      showModalEdit(id) {
        let app = this;   

        app.alimentations.forEach(element => {   
          if (element.id === id) {
            app.alimentationAModifier = element;
            // date
            let date = new Date(element.updatedAt);
            app.alimentationAModifier.updatedAt = app.GetFormattedDate(date);                           
          }
        });
        
        if (app.alimentationAModifier) {                      
          EventBus.$emit('alimentation-a-modifier', app.alimentationAModifier);                
        }
      },      
      getAlimentationList() {
        let app = this;

        if (localStorage.getItem('currentDateAlimentationAffichage')) {
          app.currentDateFilter = localStorage.getItem('currentDateAlimentationAffichage');
          app.getAlimentationFilteredList();
        } else {
          Axios.get('api/alimentation/list').then(function (resp) {
            // Valorisation
            app.alimentations = resp.data.alimentations;        
            // total
            app.SetTotalAlimentations();                
          }).catch(function (err) {
              alert("Impossible de charger la liste des alimentations.");
          });
        }        
      },
      getAlimentationFilteredList() {
        let app = this;

        if (localStorage.getItem('filter-mois-alimentations') && localStorage.getItem('filter-annee-alimentations') ) {
        
          let mois = localStorage.getItem('filter-mois-alimentations');
          let annee = localStorage.getItem('filter-annee-alimentations');

          // valoriser label du filtre en cours
          let dateAffichage = mois + '/' + annee[2] + annee[3];
          app.currentDateFilter = dateAffichage;
          localStorage.setItem('currentDateAlimentationAffichage', dateAffichage);

          Axios.get('api/alimentation/list/annee/' + annee + '/mois/' + mois).then(function (resp) {
            // Valorisation
            app.alimentations = resp.data;             
            // total
            app.SetTotalAlimentations();            
          }).catch(function (err) {
              alert("Impossible de charger la liste des alimentations.");
          });
        }
      },
      SetTotalAlimentations() {
        let app = this;        
        app.total = 0;
        app.alimentations.forEach(element => {
              app.total += element.montant;
        });
        app.total = app.total.toFixed(2);
      },
      deleteDateFilter() {
        let app = this;
        localStorage.removeItem('filter-mois-alimentations');
        localStorage.removeItem('currentDateAlimentationAffichage');
        localStorage.removeItem('filter-annee-alimentations');
        app.currentDateFilter = null;
        app.$toast.open({
            message: 'Filtrage par date désactivé !',
            type: 'warning',
            position: 'top-right',
        });
        app.getAlimentationList();
      }
    }
  };
</script>

<style scoped>
  .center {
    text-align: center;
  }
  .montantVignette {
    position: absolute;
    bottom: 0;
    right: 10px;
    border-radius: 12px;
  }

  .btnEdit, .btnEdit:hover, .btnEdit:active, .btnEdit:enabled {
    background-color: transparent; 
    border: none; 
    margin-top: -5px;
    margin-right: -25px;
    font-size: 1.2em;
  }

  .btnCreate, .btnCreate:hover, .btnCreate:active, .btnCreate:enabled,
  .btnFilter, .btnFilter:hover, .btnFilter:active, .btnFilter:enabled {
    background-color: transparent;     
    border: none; 
    font-size: 1.5em; 
    cursor: pointer;
    border: none !important;
  }

  .btnFilter {
    color: #000; 
  }

  .btnCreate {
    color: #1A7D0F; 
  }

  .categorieDiv {
    position: absolute; 
    bottom: 1.4em; 
    left: 1.2em;
  }

  .categorieLabel {
    left: 0; 
    border: 1.5px solid #000; 
    border-radius: 15px; 
    font-size: 18px;
  }

  .colxs6 {
    border: none; 
    background-color: transparent; 
    padding: 5px 10px 30px 25px;
  }
  
</style>
