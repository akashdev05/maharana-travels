// resources/js/composables/useApi.js
import { ref } from 'vue'

const BASE = '/api'

async function http(method, url, body = null) {
  const opts = {
    method,
    headers: {
      'Content-Type': 'application/json',
      'Accept':       'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
    },
  }
  if (body) opts.body = JSON.stringify(body)
  const res  = await fetch(BASE + url, opts)
  const data = await res.json()
  if (!res.ok) throw data
  return data
}

export function useApi() {
  const loading = ref(false)
  const error   = ref(null)

  async function call(fn) {
    loading.value = true
    error.value   = null
    try {
      return await fn()
    } catch (e) {
      error.value = e?.message ?? 'Something went wrong. Please try again.'
      throw e
    } finally {
      loading.value = false
    }
  }

  return {
    loading,
    error,

    // Homepage
    getHomepage:  ()       => call(() => http('GET', '/homepage')),

    // Cabs
    getCabs:      ()       => call(() => http('GET',  '/cabs')),
    searchCabs:   (params) => call(() => http('GET',  '/cabs/search?' + new URLSearchParams(params))),

    // Services
    getServices:  ()       => call(() => http('GET',  '/services')),
    getService:   (slug)   => call(() => http('GET',  `/services/${slug}`)),

    // Booking
    createBooking:(body)   => call(() => http('POST', '/bookings', body)),

    // Contact
    sendContact:  (body)   => call(() => http('POST', '/contact',  body)),
  }
}
