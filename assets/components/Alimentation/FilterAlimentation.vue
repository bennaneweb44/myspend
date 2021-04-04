<template>  
  <div class="row">
    <b-modal ref="modalFilters" id="filter-alimentation" v-show="showModal" hide-footer hide-header>      
      <div class="d-block text-center bg-info">

        <div class="row pull-right">
          <span style="font-size: 2em; cursor: pointer; margin-top: -20px; " @click.prevent="hideModal()">&times;</span>
        </div>

        <div class="col-md-12 text-left">
          <i class="fa fa-filter"></i> Filtrer par date                                  
        </div>

        <div class="row">
          <div class="col-md-6 mt-3">
            <span class="text-center"><i class="fa fa-calendar"></i> Mois</span>
            <select v-model="selectedMois" class="form-control" style="font-size: 1em; ">                  
              <option v-for="mois in allMois" v-bind:value="mois.value" :key="mois.value" style="font-size: .5em; width: max-content; padding: 2px">
                {{ mois.label }}
              </option>
            </select>
          </div>

          <div class="col-md-6 mt-3">
            <span class="text-center"><i class="fa fa-calendar"></i> Année</span>
            <select v-model="selectedAnnee" class="form-control" style="font-size: 1em; ">                  
              <option v-for="annee in allAnnees" v-bind:value="annee.value" :key="annee.value" style="font-size: .5em; width: max-content; padding: 2px">
                {{ annee.label }}
              </option>
            </select>
          </div>        

          <div class="col-md-12 mt-2">
            <b-button @click.prevent="applyDateFilter()" class="btn btn-xs bg-primary" style="height: 65px; padding: 0" block><i class="fa fa-check"></i> Appliquer</b-button>
          </div>
        </div>
        
      </div>
    </b-modal>
  </div>
</template>

<script>

  // Imports
  import Axios from 'axios'
  import { EventBus } from '../../event-bus.js';

  export default {
    name: 'FilterAlimentation',
    data() {
      return {
        selectedMois: {
          "value" : null,
          "label" : null
        },
        selectedAnnee: {
          "value" : null,
          "label" : null
        },
        allMois: [
          {
            "value" : "01",
            "label" : "Janvier"
          },
          {
            "value" : "02",
            "label" : "Février"
          },
          {
            "value" : "03",
            "label" : "Mars"
          },
          {
            "value" : "04",
            "label" : "Avril"
          },
          {
            "value" : "05",
            "label" : "Mai"
          },
          {
            "value" : "06",
            "label" : "Juin"
          },
          {
            "value" : "07",
            "label" : "Juillet"
          },
          {
            "value" : "08",
            "label" : "Aout"
          },
          {
            "value" : "09",
            "label" : "Septembre"
          },
          {
            "value" : "10",
            "label" : "Octobre"
          },
          {
            "value" : "11",
            "label" : "Novembre"
          },
          {
            "value" : "12",
            "label" : "Décembre"
          }
        ],
        allAnnees: [],
        dateFilterDefault: '',
        showModal: false
      };
    },
    components: {
      Axios
    },
    created() {
      let app = this;    

      // mois en cours
      let date = new Date();
      let month = date.getMonth();
      month++;
      app.selectedMois = month < 10 ? '0' + month : month;              

      let year = date.getFullYear();
      app.selectedAnnee = year;
      
      if (localStorage.getItem('filter-annee-alimentations') && localStorage.getItem('filter-mois-alimentations')) {
        app.selectedAnnee = localStorage.getItem('filter-annee-alimentations');
        app.selectedMois = localStorage.getItem('filter-mois-alimentations');
      }

      let min_annee = 2020;
      while (year >= min_annee) {
        
        let obj = {
            "value" : year,
            "label" : year
        }
        app.allAnnees.push(obj);
        year--;
      }
    },
    methods: {
      hideModal() {
        let app = this;
        app.$refs.modalFilters.hide();
      },
      applyDateFilter() {
        let app = this;        

        // stockage en local
        localStorage.setItem('filter-mois-alimentations', app.selectedMois);
        localStorage.setItem('filter-annee-alimentations', app.selectedAnnee);        
        
        // Event for filtered listing
        app.$emit('alimentations-filtrees');

        app.$toast.open({
            message: 'Filtre appliqué : ' + app.selectedMois + '/' + app.selectedAnnee + ' !',
            type: 'succès',
            position: 'top-right',
        });

        app.hideModal();
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
