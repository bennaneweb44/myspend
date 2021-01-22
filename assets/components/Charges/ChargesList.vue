<template>  
  <div class="row">    
    <div v-for="charge in charges" :key="charge.id" class="card col-12 col-md-4 p-2" style="border: none; background-color: transparent">
        <div class="card-body bg-success" style="border: 3px solid #000; border-radius: 12px; padding-top: 15px; padding-left: 15px">          
            <h5 class="card-title">              
              <i class="fa fa-calendar"></i> {{ charge.createdAt | moment("DD/MM/YYYY") }}                   
              <b-button v-b-modal.edit-charge class="fa fa-pencil-square-o pull-right p-1 text-primary" 
                style="background-color: transparent; border: none; margin-top: -5px; margin-right: -35px"
                @click.prevent="showModalEdit(charge.id)" 
                title="Modifer"></b-button>
            </h5>            
            <p class="card-text">{{ charge.libelle }}</p>            
            <label href="#" class="btn-primary pl-1 pr-1 montantVignette" style="margin-right: 13px; margin-bottom: 22px" >{{ charge.montant }} €</label>            
        </div>
    </div>    
    <ChargesEdit></ChargesEdit>    
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
          createdAt: '',
          libelle: '',
          montant: 0
        }        
      };
    },
    components: {
      Axios,
      ChargesEdit
    },
    created() {
      let app = this;                      

      Axios.get('api/charges').then(function (resp) {
          // Valorisation de l'objet courant
          app.charges = resp.data; 
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
            let date = new Date(element.createdAt);
            app.chargeAModifier.createdAt = app.GetFormattedDate(date);                           
          }
        });
        
        if (app.chargeAModifier) {               
          EventBus.$emit('charge-a-modifier', app.chargeAModifier);          
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
