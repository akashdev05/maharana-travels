<template>
  <div>
    <PageHeader :title="page.h1" :breadcrumb="page.breadcrumb" :subtitle="page.intro" />

    <article class="landing-content container">
      <nav class="landing-breadcrumb" aria-label="Breadcrumb">
        <RouterLink to="/">Home</RouterLink><span aria-hidden="true">/</span><span>{{ page.breadcrumb }}</span>
      </nav>

      <section>
        <h2>{{ page.primaryHeading }}</h2>
        <p>{{ page.detail }}</p>
        <p>Choose Maharana Travels for a straightforward booking experience, a suitable vehicle for your group, and assistance by phone or WhatsApp.</p>
      </section>

      <section>
        <h2>Taxi options for your journey</h2>
        <div class="landing-grid">
          <div v-for="option in page.options" :key="option.title" class="landing-card">
            <h3>{{ option.title }}</h3>
            <p>{{ option.text }}</p>
          </div>
        </div>
      </section>

      <section>
        <h2>Book {{ page.shortName }} with Maharana Travels</h2>
        <ol class="landing-steps">
          <li>Share your pickup point, destination, date and time.</li>
          <li>Select a cab option that fits your travel plans.</li>
          <li>Confirm your booking by calling or messaging our team.</li>
        </ol>
        <div class="landing-actions">
          <RouterLink to="/" class="btn-book">Book a cab online</RouterLink>
          <a :href="phoneLink()" class="btn-call"><i class="fas fa-phone"></i> Call {{ CONTACT_PHONE }}</a>
        </div>
      </section>

      <section class="landing-links">
        <h2>Explore related taxi services</h2>
        <RouterLink v-for="link in page.links" :key="link.to" :to="link.to">{{ link.label }}</RouterLink>
      </section>

      <section class="faq-section landing-faqs">
        <h2>Frequently asked questions</h2>
        <div class="faq-grid">
          <div v-for="faq in page.faqs" :key="faq.q" class="faq-item">
            <h3 class="faq-q"><i class="fas fa-question-circle"></i>{{ faq.q }}</h3>
            <p class="faq-a">{{ faq.a }}</p>
          </div>
        </div>
      </section>
    </article>
    <AppFooter />
  </div>
</template>

<script setup>
import { computed, watchEffect } from 'vue'
import { useRoute } from 'vue-router'
import PageHeader from '../components/common/PageHeader.vue'
import AppFooter from '../components/layout/AppFooter.vue'
import { breadcrumbSchema, faqSchema, serviceSchema, updateSeo } from '../composables/useSeo'
import { CONTACT_PHONE, phoneLink } from '../utils/contactLinks'

const route = useRoute()
const commonLinks = [
  { to: '/taxi-service-chandigarh', label: 'Chandigarh taxi service' }, { to: '/taxi-service-zirakpur', label: 'Zirakpur taxi service' },
  { to: '/taxi-service-mohali', label: 'Mohali taxi service' }, { to: '/taxi-service-panchkula', label: 'Panchkula taxi service' },
  { to: '/taxi-service-ramgarh', label: 'Ramgarh taxi and tour travel services' },
  { to: '/chandigarh-airport-taxi', label: 'Chandigarh Airport taxi' }, { to: '/delhi-airport-taxi', label: 'Delhi Airport taxi' },
  { to: '/our-services/chandigarh-to-delhi', label: 'Chandigarh to Delhi taxi' }, { to: '/our-cabs', label: 'View available cabs' },
]
const baseFaqs = (place) => [
  { q: `How can I book a taxi in ${place}?`, a: 'Use the online booking form or contact Maharana Travels by call or WhatsApp with your trip details.' },
  { q: 'Can I book an airport pickup or drop?', a: 'Yes. Share your pickup or drop details and schedule with the booking team.' },
  { q: 'Are outstation cabs available?', a: 'Yes. Maharana Travels lists one-way and round-trip outstation taxi routes and vehicle options.' },
  { q: `Do you offer tour and travel help in ${place}?`, a: 'Maharana Travels can help book travel for local sightseeing and listed outstation destinations. Contact the team to discuss your itinerary.' },
]
const pages = {
  zirakpur: cityPage({ city: 'Zirakpur', title: 'Taxi & Tour Travel Services in Zirakpur | Maharana Travels', focus: 'Zirakpur taxi service and tour travel assistance', intro: 'Cab booking, airport transfers, local sightseeing and outstation travel from Zirakpur.', detail: 'Maharana Travels is based in Zirakpur and provides taxi booking for local journeys, airport transfers, local sightseeing and listed outstation routes. For travellers looking for tour and travels in Zirakpur or comparing a travel agency in Zirakpur, the team can help plan transport around the itinerary you have in mind.', local: 'Zirakpur travellers can book rides from the business location or another agreed pickup point.' }),
  chandigarh: cityPage({ city: 'Chandigarh', title: 'Taxi & Tour Travel Services in Chandigarh | Maharana Travels', focus: 'Chandigarh taxi service and tour travel assistance', intro: 'Taxi, airport transfer and outstation travel bookings from Chandigarh.', detail: 'Maharana Travels serves Chandigarh with cab booking for point-to-point travel, airport transfers and listed intercity routes. We also help travellers arrange transport for local sightseeing and destination travel, useful when comparing tour and travels or a travel agency in Chandigarh.', local: 'Use the booking form or contact the team with your Chandigarh pickup point and onward destination.' }),
  mohali: cityPage({ city: 'Mohali', title: 'Taxi & Tour Travel Services in Mohali | Maharana Travels', focus: 'Mohali taxi service and tour travel assistance', intro: 'Cab bookings from Mohali for airport, local sightseeing and outstation travel.', detail: 'Maharana Travels provides Mohali cab booking for local trips, airport transfers and listed one-way or round-trip travel. When you need transport for sightseeing or an outstation itinerary, our tour and travel assistance focuses on arranging the right vehicle and schedule—an option for those comparing travel agencies in Mohali.', local: 'Share your Mohali pickup details, group size and timing so the team can recommend a suitable cab.' }),
  panchkula: cityPage({ city: 'Panchkula', title: 'Taxi & Tour Travel Services in Panchkula | Maharana Travels', focus: 'Panchkula taxi service and tour travel assistance', intro: 'Taxi booking from Panchkula for airport transfers, local travel and outstation trips.', detail: 'Maharana Travels serves Panchkula passengers with local taxi bookings, airport pickup or drop and scheduled outstation cabs. The team can also arrange travel transport for local sightseeing or listed destinations, offering practical tour and travel support for people looking at travel agencies in Panchkula without making pre-set package claims.', local: 'Book from Panchkula by sharing your requested pickup, drop, date and travel time.' }),
  ramgarh: cityPage({ city: 'Ramgarh', title: 'Taxi & Tour Travel Services in Ramgarh | Maharana Travels', focus: 'Ramgarh taxi service and tour travel assistance', intro: 'Taxi booking from Ramgarh for airport transfers, local sightseeing and outstation travel.', detail: 'Maharana Travels serves Ramgarh with cab bookings for local travel, airport transfers and listed outstation journeys. If you are searching for tour and travels in Ramgarh, a travel agency near Ramgarh or a tour operator for transport planning, contact the team about local sightseeing or your chosen outstation itinerary.', local: 'For Ramgarh pickup and drop bookings, share your journey details by phone, WhatsApp or the online form.' }),
  'chandigarh-airport': airportPage('Chandigarh Airport Taxi', 'Chandigarh Airport taxi', 'Chandigarh Airport Taxi Service | Maharana Travels', 'Book a Chandigarh Airport taxi with Maharana Travels for scheduled airport pickup and drop service.', 'Chandigarh Airport'),
  'delhi-airport': airportPage('Delhi Airport (IGI) Taxi', 'Delhi Airport taxi', 'Delhi Airport Taxi Service | Maharana Travels', 'Book a Delhi Airport (IGI) taxi with Maharana Travels for scheduled airport pickup and drop service.', 'Delhi Airport'),
}
const page = computed(() => pages[route.meta.landing] || pages.chandigarh)

watchEffect(() => {
  const value = page.value
  updateSeo({ title: value.title, description: value.description, path: route.path, schema: [
    serviceSchema({ title: value.h1, from: value.schemaFrom, to: value.schemaTo, serviceType: value.serviceType }), faqSchema(value.faqs),
    breadcrumbSchema([{ name: 'Home', path: '/' }, { name: value.breadcrumb, path: route.path }]),
  ] })
})

function cityPage({ city, title, focus, intro, detail, local }) {
  return { h1: `${city} Taxi, Tour & Travel Services`, breadcrumb: `${city} taxi & tours`, title, description: `${intro} Book with Maharana Travels.`, shortName: focus, schemaFrom: city, schemaTo: 'Chandigarh Tricity', serviceType: 'Taxi and tour travel service', intro,
    primaryHeading: focus, detail,
    options: [{ title: 'Taxi and cab booking', text: local }, { title: 'Airport transfers', text: 'Arrange a scheduled airport pickup or drop based on your travel time.' }, { title: 'Tour and travel transport', text: `For local sightseeing or a transport-led tour itinerary from ${city}, Maharana Travels can help arrange a cab as your tour operator contact. Listed one-way and round-trip outstation routes are also available.` }], faqs: baseFaqs(city), links: commonLinks.filter(link => !link.label.startsWith(city)), }
}
function airportPage(h1, breadcrumb, title, description, airport) {
  return { h1, breadcrumb, title, description, shortName: h1, schemaFrom: 'Chandigarh Tricity', schemaTo: airport, serviceType: 'Airport taxi service',
    intro: `Scheduled taxi pickup and drop service for ${airport}.`, primaryHeading: `Plan your ${airport} transfer`,
    detail: `Maharana Travels offers scheduled ${airport} taxi bookings for passengers travelling from or to the Chandigarh Tricity area. Share your trip details in advance so the booking team can help arrange the right vehicle.`,
    options: [{ title: 'Airport pickup', text: `Arrange a cab after arrival at ${airport}.` }, { title: 'Airport drop', text: `Schedule a timely drop at ${airport} from Chandigarh Tricity.` }, { title: 'Outstation connections', text: 'Explore listed intercity taxi routes for onward travel.' }], faqs: baseFaqs(airport), links: commonLinks, }
}
</script>
