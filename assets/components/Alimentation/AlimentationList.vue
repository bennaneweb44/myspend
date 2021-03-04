<template>  
  <div class="row"> 
    
    <div class="row col-md-12 mb-5">

      <div class="col-md-4 col-sm-4 col-2 text-left">
        <b-button v-b-modal.filter-alimentation 
          class="fa fa-filter pull-left p-1 btnCreate"
          style="float: left; width: 60%"           
          title="Filtrer par date">                    
          <input type="text" v-model="currentDateFilter" disabled class="disabled" style="background-color: transparent; border: none; font-weight: bold; font-family: inherit;" />                   
        </b-button>        
        <a v-show="currentDateFilter" @click.prevent="deleteDateFilter()" style="margin-left: 1.2em; font-size: 1.5em; cursor: pointer; color: darkred"><i class="fa fa-close"></i></a>        
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
    
    
    <div v-for="alimentation in alimentations" :key="alimentation.id" class="card col-lg-4 col-sm-6" style="border: none; background-color: transparent; padding: 5px 10px 30px 25px ">
      <!-- background-color: #BAADCD !important; -->
      <div class="card-body bg-info" style="border: 3px solid #000; border-radius: 12px; padding-top: 15px; padding-left: 15px;">          
          <h5 class="card-title" style="font-size: 1.5em;">              
            <i class="fa fa-calendar"></i> {{ alimentation.updatedAt | moment("DD/MM/YYYY") }}
            <b-button v-b-modal.edit-alimentation class="fa fa-pencil-square-o pull-right p-1 text-primary btnEdit"                
              @click.prevent="showModalEdit(alimentation.id)" 
              title="Modifer"></b-button>
          </h5>            
          <p class="card-text">{{ alimentation.libelle }}</p>  
          <div class="text-left">
            <label href="#" class="btn-default pl-1 pr-1 " style="left: 0; border: 1px solid #000" ><i :class="alimentation.categorie.icone"></i> {{ alimentation.categorie.label }}</label>                     
          </div>
          
          <label href="#" class="btn-primary pl-1 pr-1 montantVignette" style="margin-right: 13px; margin-bottom: 42px" >{{ parseFloat(alimentation.montant).toFixed(2).toString().replace(".", ",") }} €</label>            
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
          categorie: null
        },        
        currentDateFilter: null,
        total: 0     
      };
    },
    components: {
      Axios,
      AlimentationEdit,
      AlimentationCreate,
      FilterAlimentation
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
        app.getAlimentationList();
      }
    }
  };
</script>

<style scoped>
  .center {
    text-align: center;
  }
  h5.card-title, h5.card-title > i {
    font-size: 26px !important;
  }  
  .montantVignette {
    position: absolute;
    bottom: 0;
    right: 10px;
    border-radius: 12px;
  }

  /*.alimentationFixe {
    background-color: #18bc9c !important;
  }*/

  .btnEdit, .btnEdit:hover, .btnEdit:active, .btnEdit:enabled {
    background-color: transparent; 
    border: none; 
    margin-top: -5px;
    margin-right: -35px;
    font-size: 1.7em;
  }

  .btnCreate, .btnCreate:hover, .btnCreate:active, .btnCreate:enabled {
    background-color: transparent; 
    color: #000; 
    border: none; 
    font-size: 1.5em; 
    cursor: pointer;
    border: none !important;
  }
  
</style>
