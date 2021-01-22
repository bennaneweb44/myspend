<template>  
  <div class="row">    
    <div v-for="charge in charges" :key="charge.id" class="card d-flex col-12 col-md-4 p-2 bg-transparent" style="border: none">
        <div class="card-body bg-success" style="border: 3px solid #000; padding-top: 15px; padding-left: 15px">          
            <h5 class="card-title">
              <b>
                <i class="fa fa-calendar"></i> {{ charge.createdAt | moment("DD / MM / YYYY") }}                   
                <i class="fa fa-pencil-square-o pull-right" style="font-size: 25px; margin: 10px -20px !important; cursor: pointer" ></i> 
              </b>  
            </h5>
            
            <p class="card-text">{{ charge.libelle }}</p>
            <label href="#" class="btn-primary pull-right mr-1 pr-1 pl-1 montantVignette">{{ charge.montant }} €</label>
        </div>
    </div>    
  </div>
</template>

<script>
  import Axios from 'axios'

  export default {
    name: 'Charges',
    data() {
      return {
        charges: {}
      };
    },
    components: {
      Axios
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

    }
  };
</script>

<style>
  .center {
    text-align: center;
  }
</style>