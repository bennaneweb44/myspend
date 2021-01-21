<template>
  <div>
    <div class="row containerData">    
      <div v-for="charge in charges" :key="charge.id" class="card d-flex mr-2 ml-2 mb-2 bg-success" style="width: 16rem;">
          <div class="card-body">
              <h5 class="card-title"><b><i class="fa fa-calendar"></i> {{ charge.createdAt | moment("DD / MM / YYYY") }} </b></h5>
              <p class="card-text">{{ charge.libelle }}</p>
              <label href="#" class="btn-primary pull-right pr-1 pl-1 montantVignette">{{ charge.montant }} €</label>
          </div>
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
    }
  };
</script>

<style>
  .center {
    text-align: center;
  }
</style>