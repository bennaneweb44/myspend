<template>  
  <div class="row">    
    <div class="col-md-12 text-center" style="font-weight: bold; margin: -25px 0 40px 0">
      <span style="margin-right: 10px"><u>Total</u> : </span> <label href="#"  class="btn-warning pl-1 pr-1" style="border: 1px solid #000; border-radius: 12px; color: #000 ">{{ total.toString().replace(".", ",") }} €</label>   
      <div class="pull-right">
        <i class="fa fa-plus-circle" style="font-size: larger"></i>  
      </div>      
    </div>
    
    <div v-for="charge in charges" :key="charge.id" class="card col-lg-4 col-sm-6" style="border: none; background-color: transparent; padding: 5px 10px 30px 25px ">
        <div class="card-body bg-success" style="border: 3px solid #000; border-radius: 12px; padding-top: 15px; padding-left: 15px;">          
            <h5 class="card-title">              
              <i class="fa fa-calendar"></i> {{ charge.updatedAt | moment("DD/MM/YYYY") }}                   
              <b-button v-b-modal.edit-charge class="fa fa-pencil-square-o pull-right p-1 text-primary" 
                style="background-color: transparent; border: none; margin-top: -5px; margin-right: -35px; font-size: larger"
                @click.prevent="showModalEdit(charge.id)" 
                title="Modifer"></b-button>
            </h5>            
            <p class="card-text">{{ charge.libelle }}</p>            
            <label href="#" class="btn-primary pl-1 pr-1 montantVignette" style="margin-right: 13px; margin-bottom: 42px" >{{ charge.montant.toString().replace(".", ",") }} €</label>            
        </div>
    </div>    
    <ChargesEdit ref='charges_edit' :totalChild.sync="total"></ChargesEdit>    
  </div>
</template>

<script>

  // Imports
  import Axios from 'axios'
  import { EventBus } from '../../event-bus.js'
  import ChargesEdit from './ChargesEdit.vue'  

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
        total: 0        
      };
    },
    components: {
      Axios,
      ChargesEdit
    },
    created() {
      let app = this;                      

      Axios.get('api/charges/list').then(function (resp) {
          // Valorisation de l'objet courant
          app.charges = resp.data; 

          // total
          app.calculTotal();

      }).catch(function (err) {
          alert("Impossible de charger les charges. ");
      });
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
          EventBus.$emit('total-charges', app.total);          
        }
      },
      calculTotal() {
        let app = this;
        
        app.charges.forEach(element => {
              app.total += element.montant;
        });

        app.total = app.total.toFixed(2);
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
</style>
