<template>
  <div>
    <PageHeader title="Cities We Serve" breadcrumb="Cities" subtitle="Maharana Travels operates across all major cities in North India." />
    <div class="container" style="padding:50px 20px;">
      <div class="cities-grid">
        <button v-for="city in cities" :key="city.name" class="city-card"
             type="button"
             :aria-label="`Search taxi service for ${city.name}`"
             @click="$router.push({ path:'/', query:{ to: city.name } })">
          <i :class="`fas ${city.icon}`"></i>
          <span>{{ city.name }}</span>
          <small>{{ city.state }}</small>
        </button>
      </div>
    </div>
    <AppFooter />
  </div>
</template>

<script setup>
import PageHeader from '../components/common/PageHeader.vue'
import AppFooter  from '../components/layout/AppFooter.vue'

const cities = [
  { name:'Delhi',       state:'Delhi',   icon:'fa-city'       },
  { name:'Chandigarh',  state:'Punjab',  icon:'fa-building'   },
  { name:'Ramgarh',     state:'Service area',  icon:'fa-map-marker-alt' },
  { name:'Patiala',     state:'Punjab',  icon:'fa-landmark'   },
  { name:'Ludhiana',    state:'Punjab',  icon:'fa-industry'   },
  { name:'Amritsar',    state:'Punjab',  icon:'fa-place-of-worship' },
  { name:'Jalandhar',   state:'Punjab',  icon:'fa-city'       },
  { name:'Pathankot',   state:'Punjab',  icon:'fa-mountain'   },
  { name:'Shimla',      state:'HP',      icon:'fa-mountain'   },
  { name:'Manali',      state:'HP',      icon:'fa-snowflake'  },
  { name:'Noida',       state:'UP',      icon:'fa-building'   },
  { name:'Gurgaon',     state:'Haryana', icon:'fa-city'       },
  { name:'Haridwar',    state:'UK',      icon:'fa-place-of-worship' },
  { name:'Rishikesh',   state:'UK',      icon:'fa-water'      },
  { name:'Dehradun',    state:'UK',      icon:'fa-tree'       },
  { name:'Ambala',      state:'Haryana', icon:'fa-city'       },
]
</script>

<style scoped>
.cities-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
.city-card   { width:100%; background:#fff; border-radius:10px; box-shadow:0 3px 14px rgba(0,0,0,.08); padding:20px; text-align:center; cursor:pointer; border:2px solid #e0e0e0; transition:all .2s; min-width:0; }
.city-card:hover { border-color:#c8102e; transform:translateY(-3px); }
.city-card i    { color:#c8102e; font-size:26px; display:block; margin-bottom:8px; }
.city-card span { font-weight:700; color:#1a1a2e; font-size:14px; display:block; overflow-wrap:anywhere; }
.city-card small{ color:#6b7280; font-size:12px; }
@media(max-width:768px){ .cities-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:420px){
  .cities-grid{ grid-template-columns:1fr 1fr; gap:10px; }
  .city-card{ padding:16px 10px; border-radius:8px; }
  .city-card i{ font-size:22px; }
}
@media(max-width:330px){ .cities-grid{ grid-template-columns:1fr; } }
</style>
