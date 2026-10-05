<template>
  <div>
    <div class="page-header">
      <div class="container">
        <h1>{{ service?.title || 'Loading...' }}</h1>
        <p>Maharana Travels — affordable, reliable taxi service with 24/7 support.</p>
      </div>
    </div>

    <LoadingSpinner v-if="loading" text="Loading cabs..." />

    <template v-else-if="service">
      <!-- ── Tabs ── -->
      <div class="tab-bar" role="tablist" aria-label="Fare type">
        <button
          class="tab-btn"
          type="button"
          role="tab"
          aria-controls="service-cabs-panel"
          :aria-selected="activeTab === 'oneway'"
          :class="{ active: activeTab === 'oneway' }"
          @click="activeTab = 'oneway'"
        >One Way</button>
        <button
          class="tab-btn"
          type="button"
          role="tab"
          aria-controls="service-cabs-panel"
          :aria-selected="activeTab === 'roundtrip'"
          :class="{ active: activeTab === 'roundtrip' }"
          @click="activeTab = 'roundtrip'"
        >Round Trip</button>
      </div>

      <!-- ── Cabs ── -->
      <div id="service-cabs-panel" role="tabpanel" style="max-width:1200px;margin:0 auto;padding:0 20px;">
        <CabCard
          v-for="cab in displayCabs"
          :key="cab.id + activeTab"
          :cab="cab"
          :pricing="cab._pricing"
          @book="bookCab"
        />
      </div>

      <!-- ── Descriptive content ── -->
      <div class="service-content-box container">
        <h2>{{ service.title }}</h2>
        <p>
          Book a comfortable cab from <strong>{{ service.from }}</strong> to
          <strong>{{ service.to }}</strong> with <strong>Maharana Travels</strong> at the best
          price. We offer one-way and round-trip taxi services with professional, verified
          drivers and well-maintained vehicles.
        </p>

        <h2>🚗 Wide Range of Cabs Available</h2>
        <p>Multiple vehicle options suit every budget and group size — from budget sedans to premium SUVs and tempo travellers for large groups.</p>

        <h2>💰 {{ service.from }} to {{ service.to }} Taxi Fare</h2>
        <ul>
          <li>Sedan (Dzire): ₹{{ fareFor('Dzire', 10) }}+ onwards</li>
          <li>SUV (Ertiga / Crysta): ₹{{ fareFor('Ertiga', 14) }}+ onwards</li>
          <li>Premium (Hycross / Kia Carens): ₹{{ fareFor('Kia Carens', 15) }}+ onwards</li>
        </ul>

        <h2>⏱️ Distance &amp; Travel Time</h2>
        <ul>
          <li><strong>Distance:</strong> ~{{ service.km }} km</li>
          <li><strong>Travel Time:</strong> ~{{ Math.ceil(service.km / 60) }} hrs</li>
        </ul>

        <h2>⭐ Why Choose Maharana Travels?</h2>
        <ul>
          <li>✔️ 24/7 cab availability</li>
          <li>✔️ Experienced &amp; verified drivers</li>
          <li>✔️ Clean, GPS-enabled vehicles</li>
          <li>✔️ Affordable pricing — no hidden charges</li>
          <li>✔️ One-way &amp; round trip options</li>
          <li>✔️ Free cancellation before 6 hours</li>
        </ul>
      </div>

      <!-- ── FAQ ── -->
      <section class="faq-section">
        <div class="container">
          <div class="section-title">FREQUENTLY ASKED QUESTIONS</div>
          <div class="faq-grid">
            <div v-for="f in faqs" :key="f.q" class="faq-item">
              <div class="faq-q"><i class="fas fa-question-circle"></i>{{ f.q }}</div>
              <div class="faq-a">{{ f.a }}</div>
            </div>
          </div>
        </div>
      </section>
    </template>

    <div v-else class="container" style="padding:60px 20px;text-align:center;">
      <p style="color:#4b5563">Service not found.</p>
      <RouterLink to="/our-services" class="btn-book" style="display:inline-block;margin-top:16px;">
        ← Back to Services
      </RouterLink>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useBookingStore } from '../stores/booking'
import { useApi } from '../composables/useApi'
import { faqSchema, serviceSchema, updateSeo } from '../composables/useSeo'
import CabCard        from '../components/common/CabCard.vue'
import LoadingSpinner from '../components/common/LoadingSpinner.vue'
import AppFooter      from '../components/layout/AppFooter.vue'

const route     = useRoute()
const router    = useRouter()
const store     = useBookingStore()
const api       = useApi()

const service   = ref(null)
const cabs      = ref([])
const loading   = ref(true)
const activeTab = ref('oneway')

onMounted(async () => {
  await loadData()
})

watch(() => route.params.slug, async () => {
  loading.value = true
  await loadData()
})

async function loadData() {
  try {
    const [svcRes, cabsRes] = await Promise.all([
      api.getService(route.params.slug),
      api.getCabs(),
    ])
    service.value = svcRes.data
    cabs.value    = cabsRes.data
    updateServiceSeo()
  } finally {
    loading.value = false
  }
}

function updateServiceSeo() {
  if (!service.value) return

  updateSeo({
    title: `${service.value.from} to ${service.value.to} Taxi | One Way & Round Trip Cab`,
    description: `Book ${service.value.from} to ${service.value.to} taxi with Maharana Travels. Affordable one way and round trip cabs with verified drivers and clean vehicles.`,
    path: route.path,
    schema: [
      serviceSchema(service.value),
      faqSchema(faqs),
    ],
  })
}

const displayCabs = computed(() => {
  if (!service.value) return []
  const km   = service.value.km || 200
  const mult = activeTab.value === 'roundtrip' ? 2 : 1
  return cabs.value.map(c => {
    const fixedPrice = getRoutePrice(c.name)

    if (fixedPrice !== null) {
      return {
        ...c,
        _pricing: {
          base: fixedPrice,
          final: fixedPrice,
          discount: 0,
          discount_pct: 0,
          fixed_price: true,
        },
      }
    }

    const base  = Math.round(c.price_per_km * km * mult)
    const final = Math.round(base * 0.95)
    return { ...c, _pricing: { base, final, discount: base - final } }
  })
})

function fareFor(carName, fallbackRatePerKm) {
  const fixedPrice = getRoutePrice(carName)

  if (fixedPrice !== null) {
    return fixedPrice.toLocaleString('en-IN')
  }

  if (!service.value) return 0
  return Math.round(fallbackRatePerKm * service.value.km * 0.95).toLocaleString('en-IN')
}

function getRoutePrice(carName) {
  const prices = service.value?.route_prices || []
  const priceKey = activeTab.value === 'roundtrip' ? 'round_trip' : 'oneway'
  const carKey = normalizeCar(carName)
  const item = prices.find((price) => normalizeCar(price.car_name) === carKey)

  return item ? Number(item[priceKey]) : null
}

function normalizeCar(carName) {
  const key = carName.toLowerCase().replace(/[^a-z0-9]/g, '')

  return {
    toyotarumion: 'rumion',
    marutisuzukidzire: 'dzire',
    marutidzire: 'dzire',
    marutisuzukiertiga: 'ertiga',
    toyotainnovacrysta: 'crysta',
    innovacrysta: 'crysta',
    toyotahycross: 'hycross',
  }[key] || key
}

function bookCab(cab, pricing) {
  store.setSearch({
    from:     service.value?.from || '',
    to:       service.value?.to   || '',
    tripType: activeTab.value === 'roundtrip' ? 'round-trip' : 'oneway',
  })
  store.selectCab(cab, pricing)
  router.push('/booking')
}

const faqs = [
  { q:'How do I book a cab online?',             a:'Fill out the booking form, click Search Cabs and choose your vehicle.' },
  { q:'What payment methods do you accept?',     a:'Cash, credit/debit cards, UPI, net banking, and digital wallets.'      },
  { q:'Can I cancel my booking?',                a:'Yes, cancel anytime for free before 6 hours of the trip.'              },
  { q:'Are your drivers verified and licensed?', a:'Yes, all drivers are verified, licensed, and experienced.'             },
  { q:'Do you provide outstation services?',     a:'Yes, one-way and round-trip across North India.'                       },
  { q:'Are vehicles GPS enabled?',               a:'Yes, all vehicles have real-time GPS tracking.'                        },
]
</script>
