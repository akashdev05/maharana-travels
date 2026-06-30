// resources/js/stores/booking.js
import { defineStore } from 'pinia'

export const useBookingStore = defineStore('booking', {
  state: () => ({
    // Search parameters (filled on HomePage)
    search: {
      from:      '',
      to:        '',
      date:      '',
      time:      '',
      tripType:  'oneway',
    },

    // Cab chosen from SearchResults
    selectedCab:     null,
    selectedPricing: null,

    // Booking confirmation returned from API
    confirmation: null,
  }),

  getters: {
    hasSearch: (state) => !!(state.search.from && state.search.to && state.search.date),
  },

  actions: {
    setSearch(params) {
      this.search = { ...this.search, ...params }
    },
    selectCab(cab, pricing) {
      this.selectedCab     = cab
      this.selectedPricing = pricing
    },
    setConfirmation(data) {
      this.confirmation = data
    },
    reset() {
      this.search          = { from:'', to:'', date:'', time:'', tripType:'oneway' }
      this.selectedCab     = null
      this.selectedPricing = null
      this.confirmation    = null
    },
  },
})
