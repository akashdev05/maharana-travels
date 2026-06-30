<template>
  <div class="booking-card">
    <div class="booking-head">
      <span class="booking-eyebrow">Instant cab search</span>
      <h2 class="booking-title">Book Your Cab</h2>
      <p>Compare trusted vehicles for outstation, airport, local, and wedding travel.</p>
    </div>

    <!-- Trip type -->
    <fieldset class="trip-type-selection" aria-label="Trip type">
      <label class="trip-option">
        <input type="radio" v-model="form.tripType" value="oneway" name="home-trip-type">
        <span><i class="fas fa-location-arrow"></i> One Way</span>
      </label>
      <label class="trip-option">
        <input type="radio" v-model="form.tripType" value="round-trip" name="home-trip-type">
        <span><i class="fas fa-route"></i> Round Trip</span>
      </label>
    </fieldset>

    <!-- Fields -->
    <div class="booking-form">
      <div class="form-row">
        <div class="form-group city-field">
          <label for="home-from">FROM</label>
          <div class="city-dropdown">
            <input
              type="text"
              id="home-from"
              v-model="form.from"
              placeholder="Enter pickup city"
              autocomplete="off"
              @focus="openFrom = true"
              @input="openFrom = true"
              @blur="closeDropdown('from')"
            >
            <div v-if="openFrom" class="city-options">
              <button
                v-for="city in filteredFromCities"
                :key="`from-${city}`"
                type="button"
                class="city-option"
                @mousedown.prevent="selectCity('from', city)"
              >
                {{ city }}
              </button>
              <div v-if="!filteredFromCities.length" class="city-empty">No city found</div>
            </div>
          </div>
        </div>
        <div class="form-group city-field">
          <label for="home-to">TO</label>
          <div class="city-dropdown">
            <input
              type="text"
              id="home-to"
              v-model="form.to"
              placeholder="Enter destination city"
              autocomplete="off"
              @focus="openTo = true"
              @input="openTo = true"
              @blur="closeDropdown('to')"
            >
            <div v-if="openTo" class="city-options">
              <button
                v-for="city in filteredToCities"
                :key="`to-${city}`"
                type="button"
                class="city-option"
                @mousedown.prevent="selectCity('to', city)"
              >
                {{ city }}
              </button>
              <div v-if="!filteredToCities.length" class="city-empty">No city found</div>
            </div>
          </div>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="home-date">DEPARTURE DATE</label>
          <input id="home-date" type="date" v-model="form.date" :min="today">
        </div>
        <div class="form-group">
          <label for="home-time">DEPARTURE TIME</label>
          <input id="home-time" type="time" v-model="form.time">
        </div>
      </div>

      <p v-if="error" class="form-error">{{ error }}</p>

      <button class="search-btn" type="button" @click="search">
        <i class="fas fa-search"></i> Search Cabs
      </button>

      <div class="booking-trust">
        <span><i class="fas fa-shield-alt"></i> Verified drivers</span>
        <span><i class="fas fa-receipt"></i> Clear fares</span>
        <span><i class="fas fa-headset"></i> 24x7 help</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useBookingStore } from '../../stores/booking'

const router = useRouter()
const store  = useBookingStore()

const today = new Date().toISOString().split('T')[0]
const error = ref('')

const form = reactive({
  from:     store.search.from     || '',
  to:       store.search.to       || '',
  date:     store.search.date     || '',
  time:     store.search.time     || '',
  tripType: store.search.tripType || 'oneway',
})

const props = defineProps({
  cities: {
    type: Array,
    default: () => [],
  },
})

const openFrom = ref(false)
const openTo = ref(false)

const filteredFromCities = computed(() => getFilteredCities(form.from))
const filteredToCities = computed(() => getFilteredCities(form.to))

function getFilteredCities(keyword) {
  const query = keyword.trim().toLowerCase()

  if (!query) {
    return props.cities.slice(0, 5)
  }

  return props.cities
    .filter((city) => city.toLowerCase().includes(query))
    .slice(0, 8)
}

function selectCity(field, city) {
  form[field] = city

  if (field === 'from') {
    openFrom.value = false
    return
  }

  openTo.value = false
}

function closeDropdown(field) {
  window.setTimeout(() => {
    if (field === 'from') {
      openFrom.value = false
      return
    }

    openTo.value = false
  }, 120)
}

function search() {
  error.value = ''
  if (!form.from)  { error.value = 'Please enter pickup city';      return }
  if (!form.to)    { error.value = 'Please enter destination city'; return }
  if (!form.date)  { error.value = 'Please select departure date';  return }
  if (!form.time)  { error.value = 'Please select departure time';  return }

  store.setSearch({ ...form })
  router.push({
    path: '/search',
    query: {
      from:      form.from,
      to:        form.to,
      date:      form.date,
      time:      form.time,
      trip_type: form.tripType,
    },
  })
}
</script>

<style scoped>
.city-field {
  position: relative;
}

.city-dropdown {
  position: relative;
  width: 100%;
}

.city-dropdown input {
  width: 100%;
}

.city-options {
  position: absolute;
  top: calc(100% + 8px);
  left: 0;
  right: 0;
  max-height: 240px;
  overflow-y: auto;
  background: #fff;
  border: 1px solid #d8dde6;
  border-radius: 8px;
  box-shadow: 0 24px 52px rgba(17, 24, 39, 0.18);
  z-index: 20;
  overflow: hidden;
}

.city-option {
  width: 100%;
  padding: 11px 14px;
  border: none;
  border-bottom: 1px solid #f0f0f0;
  background: #fff;
  text-align: left;
  font-size: 14px;
  color: #1f2937;
  font-weight: 700;
  transition: background .18s, color .18s;
}

.city-option:last-child {
  border-bottom: none;
}

.city-option:hover {
  background: #fff1f4;
  color: #b91c3c;
}

.city-empty {
  padding: 12px 14px;
  font-size: 13px;
  color: #4b5563;
}
</style>
