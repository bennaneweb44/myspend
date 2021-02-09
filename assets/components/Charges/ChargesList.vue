<template>  
  <div class="row"> 
    
    <div class="row col-md-12 mb-5">

      <div class="col-md-4 col-sm-4 col-2 text-left">
        <b-button v-b-modal.filter-charges 
          class="fa fa-filter pull-left p-1 btnCreate"
          style="float: left; width: 60%" 
          title="Filtrer par date">                    
          <input type="text" disabled class="disabled" style="background-color: transparent; border: none; font-weight: bold; font-family: inherit;" v-model="currentDateFilter" />          
        </b-button>        
        <a v-show="currentDateFilter" @click.prevent="deleteDateFilter()" style="margin-left: 1.2em; font-size: 1.5em; cursor: pointer; color: darkred"><i class="fa fa-close"></i></a>
      </div>

      <div class="col-md-4 col-sm-4 col-8 text-center">
        <label href="#" class="btn-warning pl-3 pr-3" style="font-weight: bold; border: 2px solid #000; border-radius: 12px; color: #000 ">{{ total.toString().replace(".", ",") }} €</label>                   
      </div>
      
      <div class="col-md-4 col-sm-4 col-2">
        <b-button v-b-modal.create-charge 
          class="fa fa-plus-circle pull-right p-1 btnCreate" 
          title="Nouvelle charge"></b-button>
      </div>

    </div>
    
    
    <div v-for="charge in charges" :key="charge.id" class="card col-lg-4 col-sm-6" style="border: none; background-color: transparent; padding: 5px 10px 30px 25px ">
      <!-- background-color: #BAADCD !important; -->
      <div class="card-body bg-success" v-bind:class="{ chargeFixe: charge.categorie.id == 1 }" style="border: 3px solid #000; border-radius: 12px; padding-top: 15px; padding-left: 15px;">          
          <h5 class="card-title" style="font-size: 1.5em;">              
            <i class="fa fa-calendar"></i> {{ charge.updatedAt | moment("DD/MM/YYYY") }}
            <b-button v-b-modal.edit-charge class="fa fa-pencil-square-o pull-right p-1 text-primary btnEdit"                
              @click.prevent="showModalEdit(charge.id)" 
              title="Modifer"></b-button>
          </h5>            
          <p class="card-text">{{ charge.libelle }}</p>            
          <label href="#" class="btn-primary pl-1 pr-1 montantVignette" style="margin-right: 13px; margin-bottom: 42px" >{{ parseFloat(charge.montant).toFixed(2).toString().replace(".", ",") }} €</label>            
      </div>
    </div>       

    <ChargesCreate @charge-ajoutee="getChargesList"></ChargesCreate>
    <ChargesEdit @charge-modifiee="getChargesList"></ChargesEdit>
    <FilterCharges @charges-filtrees="getChargesFilteredList"></FilterCharges>          
    
  </div>
</template>

<script>

  // Imports
  import Axios from 'axios'
  import { EventBus } from '../../event-bus.js'

  // Components
  import ChargesEdit from './ChargesEdit.vue'
  import ChargesCreate from './ChargesCreate.vue'
  import FilterCharges from './FilterCharges.vue'

  export default {
    name: 'ChargesList',
    data() {
      return {
        charges: {},
        chargeAModifier: {
          id: 0,          
          updatedAt: '',
          libelle: '',
          montant: 0
        },
        chargeNouvelle: {
          id: 0,          
          createdAt: '',
          libelle: '',
          montant: 0
        },
        currentDateFilter: null,
        total: 0        
      };
    },
    components: {
      Axios,
      ChargesEdit,
      ChargesCreate,
      FilterCharges
    },
    created() {
      let app = this;       
                          
      if (localStorage.getItem('currentDateAffichage')) {
        app.currentDateFilter = localStorage.getItem('currentDateAffichage');
        app.getChargesFilteredList();
      } else {
        app.getChargesList();  
      }
    },
    mounted() {
      let app = this;            
    },    
    methods: {
      showModalEdit(id) {
        let app = this;   

        app.charges.forEach(element => {   
          if (element.id === id) {
            app.chargeAModifier = element;
            // date
            let date = new Date(element.updatedAt);
            app.chargeAModifier.updatedAt = app.GetFormattedDate(date);                           
          }
        });
        
        if (app.chargeAModifier) {                      
          EventBus.$emit('charge-a-modifier', app.chargeAModifier);                
        }
      },      
      getChargesList() {
        let app = this;

        if (localStorage.getItem('currentDateAffichage')) {
          app.currentDateFilter = localStorage.getItem('currentDateAffichage');
          app.getChargesFilteredList();
        } else {
          Axios.get('api/charges/list').then(function (resp) {
            // Valorisation
            app.charges = resp.data; 
            // total
            app.SetTotalCharges();                
          }).catch(function (err) {
              alert("Impossible de charger la liste des charges.");
          });
        }        
      },
      getChargesFilteredList() {
        let app = this;

        if (localStorage.getItem('filter-mois-charges') && localStorage.getItem('filter-annee-charges') ) {
        
          let mois = localStorage.getItem('filter-mois-charges');
          let annee = localStorage.getItem('filter-annee-charges');

          // valoriser label du filtre en cours
          let dateAffichage = mois + '/' + annee[2] + annee[3];
          app.currentDateFilter = dateAffichage;
          localStorage.setItem('currentDateAffichage', dateAffichage);

          Axios.get('api/charges/list/annee/' + annee + '/mois/' + mois).then(function (resp) {
            // Valorisation
            app.charges = resp.data;             
            // total
            app.SetTotalCharges();                
          }).catch(function (err) {
              alert("Impossible de charger la liste des charges.");
          });
        }
      },
      SetTotalCharges() {
        let app = this;        
        app.total = 0;
        app.charges.forEach(element => {
              app.total += element.montant;
        });
        app.total = app.total.toFixed(2);
      },
      deleteDateFilter() {
        let app = this;
        localStorage.removeItem('filter-mois-charges');
        localStorage.removeItem('currentDateAffichage');
        localStorage.removeItem('filter-annee-charges');
        app.currentDateFilter = null;
        app.getChargesList();
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
  .modal-body {
    background-color: #18bc9c;
  }
  .montantVignette {
    position: absolute;
    bottom: 0;
    right: 10px;
    border-radius: 12px;
  }

  .chargeFixe {
    background-color: #19cc98 !important;
  }

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
