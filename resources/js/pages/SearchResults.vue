<template>
  <div>
    <!-- Trip summary bar -->
    <div class="results-header">
      <div class="trip-info-bar container">
        <div class="trip-tag"><i class="fas fa-map-marker-alt"></i>{{ query.from }}</div>
        <i class="fas fa-arrow-right" style="color:var(--primary)"></i>
        <div class="trip-tag"><i class="fas fa-map-marker-alt" style="color:var(--gold)"></i>{{ query.to }}</div>
        <div class="trip-tag"><i class="fas fa-calendar"></i>{{ fmtDate(query.date) }}</div>
        <div class="trip-tag"><i class="fas fa-clock"></i>{{ query.time }}</div>
        <div class="trip-type-tag">{{ query.trip_type === 'round-trip' ? 'Round Trip' : 'One Way' }}</div>
        <button class="btn-modify" type="button" @click="$router.push('/')">
          <i class="fas fa-edit"></i> Modify
        </button>
      </div>
    </div>

    <!-- Results -->
    <div class="results-container container">
      <h2 class="results-title">
        {{ query.from }} → {{ query.to }} &mdash; Available Cabs
      </h2>

      <LoadingSpinner v-if="loading" text="Finding best cabs for you..." />

      <div v-else-if="error" class="error-box">
        <i class="fas fa-exclamation-circle"></i> {{ error }}
      </div>

      <template v-else>
        <p class="results-count">{{ cabs.length }} cabs available</p>
        <CabCard
          v-for="cab in cabs"
          :key="cab.id"
          :cab="cab"
          :pricing="{
            base: cab.base_price,
            final: cab.final_price,
            discount: cab.discount,
            discount_pct: cab.discount_pct,
            fixed_price: cab.fixed_price,
          }"
          @book="bookCab"
        />
      </template>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useBookingStore } from '../stores/booking'
import { useApi } from '../composables/useApi'
import CabCard        from '../components/common/CabCard.vue'
import LoadingSpinner from '../components/common/LoadingSpinner.vue'
import AppFooter      from '../components/layout/AppFooter.vue'

const route  = useRoute()
const router = useRouter()
const store  = useBookingStore()
const api    = useApi()

const cabs    = ref([])
const loading = ref(true)
const error   = ref('')

const query = {
  from:      route.query.from      || store.search.from,
  to:        route.query.to        || store.search.to,
  date:      route.query.date      || store.search.date,
  time:      route.query.time      || store.search.time,
  trip_type: route.query.trip_type || store.search.tripType,
}

onMounted(async () => {
  if (!query.from || !query.to) {
    router.push('/')
    return
  }
  try {
    const res  = await api.searchCabs(query)
    cabs.value = res.data
  } catch (e) {
    error.value = e?.message || 'Failed to load cabs. Please try again.'
  } finally {
    loading.value = false
  }
})

function bookCab(cab, pricing) {
  store.setSearch({
    from:     query.from,
    to:       query.to,
    date:     query.date,
    time:     query.time,
    tripType: query.trip_type,
  })
  store.selectCab(cab, pricing)
  router.push('/booking')
}

function fmtDate(d) {
  if (!d) return ''
  return new Date(d).toLocaleDateString('en-IN', { day:'numeric', month:'short', year:'numeric' })
}
</script>
