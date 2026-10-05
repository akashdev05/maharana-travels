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
        {{ query.from }} → {{ query.to }} &mdash; {{ priceAvailable ? 'Available Cabs' : 'Fare Assistance' }}
      </h2>

      <LoadingSpinner v-if="loading" text="Finding best cabs for you..." />

      <div v-else-if="error" class="error-box">
        <i class="fas fa-exclamation-circle"></i> {{ error }}
      </div>

      <section v-else-if="!priceAvailable" class="route-enquiry-card" aria-labelledby="fare-help-title">
        <div class="enquiry-mark" aria-hidden="true">₹</div>
        <span class="enquiry-kicker">CUSTOM FARE REQUIRED</span>
        <h3 id="fare-help-title">Online fare is not available for this route yet</h3>
        <p>
          {{ noticeMessage }} Please call or WhatsApp Maharana Travels to confirm
          the exact fare and cab availability.
        </p>

        <div class="enquiry-route">
          <span>{{ query.from }}</span>
          <span class="route-arrow" aria-hidden="true">→</span>
          <span>{{ query.to }}</span>
        </div>

        <a class="enquiry-phone" :href="callUrl">{{ CONTACT_PHONE }}</a>

        <div class="enquiry-actions">
          <a class="enquiry-btn enquiry-btn-call" :href="callUrl">Call Now</a>
          <a class="enquiry-btn enquiry-btn-whatsapp" :href="whatsappUrl" target="_blank" rel="noopener">WhatsApp</a>
          <RouterLink class="enquiry-btn enquiry-btn-contact" to="/contact">Contact Form</RouterLink>
        </div>

        <button class="change-route-btn" type="button" @click="router.push('/')">Change Route</button>
      </section>

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
import { CONTACT_PHONE, phoneLink, whatsappLink } from '../utils/contactLinks'

const route  = useRoute()
const router = useRouter()
const store  = useBookingStore()
const api    = useApi()

const cabs    = ref([])
const loading = ref(true)
const error   = ref('')
const priceAvailable = ref(true)
const noticeMessage = ref('')

const query = {
  from:      route.query.from      || store.search.from,
  to:        route.query.to        || store.search.to,
  date:      route.query.date      || store.search.date,
  time:      route.query.time      || store.search.time,
  trip_type: route.query.trip_type || store.search.tripType,
}

const callUrl = phoneLink()
const whatsappUrl = whatsappLink(
  `Hello Maharana Travels, I need the fare and cab availability from ${query.from} to ${query.to} for a ${query.trip_type === 'round-trip' ? 'round trip' : 'one-way trip'}. Travel date: ${query.date}, time: ${query.time}.`,
)

onMounted(async () => {
  if (!query.from || !query.to) {
    router.push('/')
    return
  }
  try {
    const res  = await api.searchCabs(query)
    cabs.value = res.data
    priceAvailable.value = res.meta?.price_available !== false
    noticeMessage.value = res.message || 'This route needs a custom fare confirmation.'
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

<style scoped>
.route-enquiry-card {
  max-width:760px;
  margin:28px auto 46px;
  padding:38px;
  text-align:center;
  background:var(--surface);
  border:1px solid var(--border);
  border-top:4px solid var(--primary);
  border-radius:18px;
  box-shadow:var(--shadow);
}

.enquiry-mark {
  width:62px;
  height:62px;
  margin:0 auto 16px;
  display:grid;
  place-items:center;
  border-radius:50%;
  color:#fff;
  background:linear-gradient(135deg, var(--primary), var(--primary-dark));
  font-size:30px;
  font-weight:900;
  box-shadow:0 14px 30px rgba(185,28,60,.24);
}

.enquiry-kicker {
  color:var(--primary);
  font-size:12px;
  font-weight:900;
  letter-spacing:.12em;
}

.route-enquiry-card h3 {
  margin:8px 0 10px;
  color:var(--ink);
  font-size:27px;
  line-height:1.25;
}

.route-enquiry-card p {
  max-width:610px;
  margin:0 auto;
  color:var(--text-light);
  line-height:1.7;
}

.enquiry-route {
  margin:24px auto 16px;
  padding:14px 18px;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:14px;
  width:fit-content;
  max-width:100%;
  color:var(--ink);
  background:var(--bg-light);
  border:1px solid var(--border);
  border-radius:999px;
  font-weight:900;
}

.route-arrow { color:var(--primary); }

.enquiry-phone {
  display:inline-block;
  margin-bottom:20px;
  color:var(--primary);
  font-size:22px;
  font-weight:900;
  text-decoration:none;
}

.enquiry-actions {
  display:grid;
  grid-template-columns:repeat(3, minmax(0, 1fr));
  gap:10px;
}

.enquiry-btn {
  min-height:48px;
  padding:12px 16px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:10px;
  color:#fff;
  font-weight:900;
  text-decoration:none;
  transition:transform .18s, box-shadow .18s;
}

.enquiry-btn:hover { transform:translateY(-2px); }
.enquiry-btn-call { background:var(--primary); box-shadow:0 10px 22px rgba(185,28,60,.18); }
.enquiry-btn-whatsapp { background:#168746; box-shadow:0 10px 22px rgba(22,135,70,.18); }
.enquiry-btn-contact { background:var(--dark2); }

.change-route-btn {
  margin-top:14px;
  padding:10px 18px;
  color:var(--text-light);
  background:transparent;
  border:1px solid var(--border);
  border-radius:999px;
  font-weight:800;
}

@media (max-width:640px) {
  .route-enquiry-card { margin:20px auto 34px; padding:28px 16px; border-radius:14px; }
  .route-enquiry-card h3 { font-size:22px; }
  .enquiry-route { width:100%; flex-wrap:wrap; border-radius:12px; }
  .enquiry-actions { grid-template-columns:1fr; }
  .enquiry-phone { font-size:20px; }
}
</style>
