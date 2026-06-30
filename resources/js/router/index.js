// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router'

import { updateSeo } from '../composables/useSeo'

const HomePage = () => import('../pages/HomePage.vue')
const SearchResults = () => import('../pages/SearchResults.vue')
const BookingPage = () => import('../pages/BookingPage.vue')
const BookingSuccess = () => import('../pages/BookingSuccess.vue')
const ServicesPage = () => import('../pages/ServicesPage.vue')
const ServiceDetail = () => import('../pages/ServiceDetail.vue')
const OurCabsPage = () => import('../pages/OurCabsPage.vue')
const AboutPage = () => import('../pages/AboutPage.vue')
const ContactPage = () => import('../pages/ContactPage.vue')
const CitiesPage = () => import('../pages/CitiesPage.vue')
const WeddingPage = () => import('../pages/WeddingPage.vue')
const NotFound = () => import('../pages/NotFound.vue')

const routes = [
  {
    path: '/',
    component: HomePage,
    name: 'home',
    meta: {
      seo: {
        title: 'Maharana Travels | Cab Booking & Taxi Service in North India',
        description: 'Book Maharana Travels for one way cabs, round trips, airport transfers and outstation taxi service with verified drivers and transparent fares.',
      },
    },
  },
  {
    path: '/search',
    component: SearchResults,
    name: 'search',
    meta: { seo: { title: 'Search Cabs | Maharana Travels', description: 'Compare available cabs and fares for your selected route with Maharana Travels.', robots: 'noindex, follow' } },
  },
  {
    path: '/booking',
    component: BookingPage,
    name: 'booking',
    meta: { seo: { title: 'Book a Cab Online | Maharana Travels', description: 'Confirm your cab booking with Maharana Travels for safe outstation, airport and local travel.', robots: 'noindex, follow' } },
  },
  {
    path: '/booking/success',
    component: BookingSuccess,
    name: 'success',
    meta: { seo: { title: 'Booking Confirmed | Maharana Travels', description: 'Your Maharana Travels cab booking request has been submitted successfully.', robots: 'noindex, nofollow' } },
  },
  {
    path: '/our-services',
    component: ServicesPage,
    name: 'services',
    meta: {
      seo: {
        title: 'Outstation Taxi Services | One Way & Round Trip Cabs',
        description: 'Explore Maharana Travels route-wise taxi services for Delhi, Chandigarh, Shimla, Manali, Amritsar, Haridwar, Rishikesh and more.',
      },
    },
  },
  {
    path: '/our-services/:slug',
    component: ServiceDetail,
    name: 'service-detail',
    meta: {
      seo: {
        title: 'Route Taxi Service | Maharana Travels',
        description: 'Book one way and round trip taxi service with Maharana Travels. Clean cars, verified drivers and transparent fares.',
      },
    },
  },
  {
    path: '/our-cabs',
    component: OurCabsPage,
    name: 'cabs',
    meta: {
      seo: {
        title: 'Our Cabs | Sedan, SUV, Crysta & Tempo Traveller',
        description: 'Choose from Maharana Travels cabs including Dzire, Ertiga, Innova Crysta, Hycross, Kia Carens and tempo travellers for comfortable journeys.',
      },
    },
  },
  {
    path: '/about',
    component: AboutPage,
    name: 'about',
    meta: {
      seo: {
        title: 'About Maharana Travels | Trusted Cab Service',
        description: 'Maharana Travels provides safe, comfortable and affordable taxi services across North India with verified drivers and 24/7 support.',
      },
    },
  },
  {
    path: '/contact',
    component: ContactPage,
    name: 'contact',
    meta: {
      seo: {
        title: 'Contact Maharana Travels | Call or WhatsApp for Cab Booking',
        description: 'Contact Maharana Travels for cab booking, airport transfer, local taxi, outstation taxi and wedding car rental support.',
      },
    },
  },
  {
    path: '/cities',
    component: CitiesPage,
    name: 'cities',
    meta: {
      seo: {
        title: 'Cities We Serve | Taxi Service Across North India',
        description: 'Maharana Travels serves Delhi, Chandigarh, Mohali, Patiala, Ludhiana, Amritsar, Shimla, Manali, Haridwar, Rishikesh and nearby cities.',
      },
    },
  },
  {
    path: '/wedding-cars',
    component: WeddingPage,
    name: 'wedding',
    meta: {
      seo: {
        title: 'Wedding Car Rental | Maharana Travels',
        description: 'Book clean and comfortable wedding cars with Maharana Travels for guest transport, family travel and special occasions.',
      },
    },
  },
  {
    path: '/:pathMatch(.*)*',
    component: NotFound,
    name: 'not-found',
    meta: { seo: { title: 'Page Not Found | Maharana Travels', description: 'The page you are looking for could not be found.', robots: 'noindex, follow' } },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0, behavior: 'smooth' }
  },
})

router.afterEach((to) => {
  updateSeo({
    ...(to.meta.seo || {}),
    path: to.path,
  })

  trackPageView(to)
})

function trackPageView(to) {
  const pagePath = to.fullPath || to.path
  const pageTitle = document.title

  window.dataLayer?.push({
    event: 'page_view',
    page_path: pagePath,
    page_title: pageTitle,
  })

  if (typeof window.gtag === 'function') {
    window.gtag('event', 'page_view', {
      page_path: pagePath,
      page_title: pageTitle,
    })
  }

  if (typeof window.fbq === 'function') {
    window.fbq('track', 'PageView')
  }

  if (to.name === 'success') {
    window.dataLayer?.push({ event: 'booking_lead' })

    if (typeof window.gtag === 'function') {
      window.gtag('event', 'generate_lead', {
        event_category: 'booking',
      })
    }

    if (typeof window.fbq === 'function') {
      window.fbq('track', 'Lead')
    }
  }
}

export default router
