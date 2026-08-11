<template>
  <div>
    <section class="hero-section hero-layout">
      <div class="container hero-grid">
        <div class="hero-copy">
          <p class="hero-kicker">Safe journey, royal experience</p>
          <h1>Taxi Service in Chandigarh Tricity for Airport and Outstation Travel</h1>
          <p class="hero-text">
            Travel with professional drivers, clean vehicles, clear fares, and quick
            support from Maharana Travels across Zirakpur, Chandigarh, Mohali, Panchkula and Ramgarh.
          </p>

          <div class="hero-points">
            <div class="hero-point"><i class="fas fa-check-circle"></i> Verified drivers</div>
            <div class="hero-point"><i class="fas fa-check-circle"></i> Transparent fares</div>
            <div class="hero-point"><i class="fas fa-check-circle"></i> 24x7 support</div>
          </div>

          <div class="hero-stats" aria-label="Maharana Travels highlights">
            <div>
              <strong>24/7</strong>
              <span>Live support</span>
            </div>
          </div>

          <div class="hero-cta-row">
            <a class="btn-call" :href="phoneLink()">
              <i class="fas fa-phone"></i> {{ site.phone }}
            </a>
            <a class="btn-wa hero-wa" :href="whatsappLink()" target="_blank" rel="noopener">
              <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
          </div>
        </div>

        <BookingForm :cities="cities" />
      </div>
    </section>

    <div class="section-heading">
      <span class="section-eyebrow">Fast fare discovery</span>
      <h2>One-Way Popular Routes</h2>
      <p>Tap a route to prefill your search and compare trusted cars instantly.</p>
    </div>
    <section class="cabs-list-section">
      <div class="container">
        <LoadingSpinner v-if="loading" text="Loading routes..." />
        <div v-else class="routes-grid">
          <button
            v-for="routeItem in popularRoutes"
            :key="routeItem.url"
            class="route-card"
            type="button"
            :aria-label="`Search cabs from ${routeItem.from} to ${routeItem.to}`"
            @click="searchRoute(routeItem)"
          >
            <div class="route-icon" aria-hidden="true">
              <svg class="route-symbol" viewBox="0 0 24 24" focusable="false">
                <path d="M6 18a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm12-6a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
                <path d="M8.7 15h3.8a3.5 3.5 0 0 0 0-7H9.2" />
                <path d="m11 5-3 3 3 3" />
              </svg>
            </div>
            <div class="route-title">
              {{ routeItem.from }} → {{ routeItem.to }}<br>
              <small>{{ routeItem.to }} → {{ routeItem.from }}</small>
            </div>
            <div class="route-fare-label">Fare Start -</div>
            <div class="route-price">₹ {{ routeItem.price.toLocaleString('en-IN') }}/-</div>
          </button>
        </div>
      </div>
    </section>

    <div class="section-heading section-heading-soft">
      <span class="section-eyebrow">Premium fleet</span>
      <h2>Select a Car for Taxi &amp; Travel Services</h2>
      <p>Clean, comfortable vehicles for airport transfers, family travel, business trips, and weddings.</p>
    </div>
    <section class="cabs-section">
      <div class="container">
        <LoadingSpinner v-if="loading" text="Loading cabs..." />
        <template v-else>
          <CabCard
            v-for="cab in cabs"
            :key="cab.id"
            :cab="cab"
            @book="bookCab"
          />
        </template>
      </div>
    </section>

    <section class="home-service-area">
      <div class="container">
        <div class="section-heading inline-heading">
          <span class="section-eyebrow">Chandigarh Tricity</span>
          <h2>Taxi bookings for local, airport and intercity travel</h2>
          <p>Maharana Travels serves Zirakpur, Chandigarh, Mohali, Panchkula and Ramgarh with taxi booking, local sightseeing, airport transfers and listed outstation cab routes. For tour and travel planning in Chandigarh Tricity, contact the team with your itinerary so we can help arrange suitable transport.</p>
        </div>
        <div class="home-links">
          <RouterLink to="/taxi-service-chandigarh">Chandigarh taxi service</RouterLink>
          <RouterLink to="/taxi-service-zirakpur">Zirakpur taxi service</RouterLink>
          <RouterLink to="/taxi-service-mohali">Mohali taxi service</RouterLink>
          <RouterLink to="/taxi-service-panchkula">Panchkula taxi service</RouterLink>
          <RouterLink to="/taxi-service-ramgarh">Ramgarh taxi &amp; tour travel services</RouterLink>
          <RouterLink to="/chandigarh-airport-taxi">Chandigarh Airport taxi</RouterLink>
          <RouterLink to="/delhi-airport-taxi">Delhi Airport taxi</RouterLink>
        </div>
      </div>
    </section>

    <section class="experience-strip">
      <div class="container experience-grid">
        <div>
          <span class="section-eyebrow">Designed around comfort</span>
          <h2>Travel that feels calm from booking to drop-off.</h2>
        </div>
        <div class="experience-item">
          <i class="fas fa-user-shield"></i>
          <span>Driver details shared before pickup</span>
        </div>
        <div class="experience-item">
          <i class="fas fa-car-side"></i>
          <span>Sanitized cars with polished interiors</span>
        </div>
        <div class="experience-item">
          <i class="fas fa-wallet"></i>
          <span>Clear fare estimate before you confirm</span>
        </div>
      </div>
    </section>

    <section class="destinations-section">
      <div class="container">
        <div class="section-heading inline-heading">
          <span class="section-eyebrow">Explore more</span>
          <h2 class="sec-title">Choose From Popular Destinations</h2>
          <p>Curated city, temple, hill, and weekend routes for smooth North India travel.</p>
        </div>
        <div class="dest-grid">
          <button
            v-for="destination in destinations"
            :key="destination.name"
            class="dest-card"
            type="button"
            :aria-label="`View taxi services for ${destination.name}`"
            @click="$router.push('/our-services')"
          >
            <img
              :src="destination.img"
              :alt="destination.name"
              width="800"
              height="600"
              loading="lazy"
              @error="handleDestinationImageError"
            >
            <div class="dest-info">
              <h5>{{ destination.name }}</h5>
              <span class="btn-dest">Book Now</span>
            </div>
          </button>
        </div>
      </div>
    </section>

    <section class="advantages-section">
      <div class="container">
        <div class="section-title">OUR BEST ADVANTAGES</div>
        <div class="adv-grid">
          <div v-for="item in advantages" :key="item.title" class="adv-card">
            <div class="adv-icon"><i :class="`fas ${item.icon}`"></i></div>
            <h5>{{ item.title }}</h5>
            <p>{{ item.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <section class="faq-section">
      <div class="container">
        <div class="section-title">FREQUENTLY ASKED QUESTIONS</div>
        <div class="faq-grid">
          <div v-for="faq in faqs" :key="faq.q" class="faq-item">
            <div class="faq-q"><i class="fas fa-question-circle"></i>{{ faq.q }}</div>
            <div class="faq-a">{{ faq.a }}</div>
          </div>
        </div>
      </div>
    </section>

    <AppFooter />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useBookingStore } from '../stores/booking'
import { useApi } from '../composables/useApi'
import BookingForm from '../components/home/BookingForm.vue'
import CabCard from '../components/common/CabCard.vue'
import LoadingSpinner from '../components/common/LoadingSpinner.vue'
import AppFooter from '../components/layout/AppFooter.vue'
import { phoneLink, whatsappLink } from '../utils/contactLinks'
import { faqSchema, updateSeo } from '../composables/useSeo'
import { getInitialSiteData } from '../utils/siteData'

const router = useRouter()
const store = useBookingStore()
const api = useApi()

const loading = ref(true)
const site = ref({
  phone: '+91 9416198045',
  phone_raw: '919416198045',
})
const cities = ref([])
const popularRoutes = ref([])
const cabs = ref([])
const destinations = ref([])
const advantages = ref([])
const faqs = ref([])

onMounted(async () => {
  try {
    const initialData = getInitialSiteData()
    const data = initialData || (await api.getHomepage()).data
    applyHomepageData(data)
  } finally {
    loading.value = false
  }
})

function applyHomepageData(data) {
  site.value = data.site || site.value
  cities.value = data.cities || []
  popularRoutes.value = data.routes || []
  cabs.value = data.cabs || []
  destinations.value = data.destinations || []
  advantages.value = data.advantages || []
  faqs.value = data.faqs || []
  updateSeo({
    title: 'Taxi Service in Chandigarh Tricity | Maharana Travels',
    description: 'Book Maharana Travels for taxi and tour travel services in Zirakpur, Chandigarh, Mohali, Panchkula and Ramgarh, plus airport and outstation cabs.',
    path: '/',
    schema: faqSchema(faqs.value),
  })
}

function bookCab(cab, pricing) {
  store.selectCab(cab, pricing)
  router.push('/booking')
}

function searchRoute(routeItem) {
  const now = new Date()
  const date = now.toISOString().split('T')[0]
  const time = now.toTimeString().slice(0, 5)

  store.setSearch({
    from: routeItem.from,
    to: routeItem.to,
    date,
    time,
    tripType: 'oneway',
  })

  router.push({
    path: '/search',
    query: {
      from: routeItem.from,
      to: routeItem.to,
      date,
      time,
      trip_type: 'oneway',
    },
  })
}

function handleDestinationImageError(event) {
  if (event.target.src.endsWith('/images/hero-travel-bg.webp')) return
  event.target.src = '/images/hero-travel-bg.webp'
}
</script>
