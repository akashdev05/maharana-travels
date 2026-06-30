<template>
  <div>
    <PageHeader title="Complete Your Booking" subtitle="Fill in your details to confirm the cab" />

    <!-- Guard: redirect if no cab selected -->
    <div v-if="!store.selectedCab" class="container" style="padding:60px 20px;text-align:center;">
      <p style="color:#4b5563;margin-bottom:20px;">No cab selected. Please search first.</p>
      <button class="btn-book" type="button" @click="$router.push('/')">Go to Home</button>
    </div>

    <div v-else class="booking-wrapper">

      <!-- ── Left: Vehicle Summary ── -->
      <div class="vehicle-summary">
        <div class="vehicle-summary-header">
          <h3><i class="fas fa-car"></i> Selected Vehicle</h3>
        </div>

        <canvas
          ref="canvasEl"
          width="800" height="440"
          class="vehicle-canvas"
        ></canvas>

        <div class="vehicle-info-body">
          <div class="vehicle-name">{{ cab.name }}</div>

          <div class="spec-row">
            <div class="spec-item"><i class="fas fa-users"></i> {{ cab.seats }} Seats</div>
            <div class="spec-item"><i class="fas fa-suitcase"></i> {{ cab.bags }} Bags</div>
            <div class="spec-item"><i class="fas fa-snowflake"></i> AC</div>
            <div class="spec-item"><i class="fas fa-gas-pump"></i> {{ cab.fuel }}</div>
          </div>

          <div class="detail-list">
            <div class="detail-row">
              <span class="detail-label">Type</span>
              <span class="detail-val">{{ cab.type }}</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Rate</span>
              <span class="detail-val">₹{{ cab.rate_base }}/km included</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Extra km</span>
              <span class="detail-val">₹{{ cab.rate_extra }}/km</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Cancellation</span>
              <span class="detail-val">Free before 6 hrs</span>
            </div>
          </div>

          <div class="price-box" v-if="pricing?.final">
            <div class="price-line">
              <span>{{ pricing.fixed_price ? 'Fixed Fare:' : 'Base Price:' }}</span>
              <span>₹{{ pricing.base.toLocaleString('en-IN') }}</span>
            </div>
            <div v-if="hasDiscount" class="price-line">
              <span>Discount ({{ pricing.discount_pct || 5 }}%):</span>
              <span style="color:#28a745;">− ₹{{ (pricing.base - pricing.final).toLocaleString('en-IN') }}</span>
            </div>
            <div class="price-line total">
              <span>Total:</span>
              <span>₹{{ pricing.final.toLocaleString('en-IN') }}</span>
            </div>
          </div>
          <div class="price-box" v-else>
            <div class="price-line total">
              <span>Rate:</span>
              <span>₹{{ cab.price_per_km }}/km</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ── Right: Customer Form ── -->
      <div class="form-section">
        <div class="form-section-title">
          <i class="fas fa-user-edit"></i> Customer &amp; Trip Details
        </div>

        <!-- Trip info (pre-filled, editable) -->
        <div class="bf-group city-field">
          <label for="booking-source">Source Location</label>
          <div class="city-dropdown">
            <input
              type="text"
              id="booking-source"
              v-model="form.source"
              placeholder="Pickup location"
              autocomplete="off"
              @focus="openSource = true"
              @input="openSource = true"
              @blur="closeDropdown('source')"
            >
            <div v-if="openSource" class="city-options">
              <button
                v-for="city in filteredSourceCities"
                :key="`source-${city}`"
                type="button"
                class="city-option"
                @mousedown.prevent="selectCity('source', city)"
              >
                {{ city }}
              </button>
              <div v-if="!filteredSourceCities.length" class="city-empty">No city found</div>
            </div>
          </div>
        </div>
        <div class="bf-group city-field">
          <label for="booking-destination">Destination</label>
          <div class="city-dropdown">
            <input
              type="text"
              id="booking-destination"
              v-model="form.destination"
              placeholder="Destination"
              autocomplete="off"
              @focus="openDestination = true"
              @input="openDestination = true"
              @blur="closeDropdown('destination')"
            >
            <div v-if="openDestination" class="city-options">
              <button
                v-for="city in filteredDestinationCities"
                :key="`destination-${city}`"
                type="button"
                class="city-option"
                @mousedown.prevent="selectCity('destination', city)"
              >
                {{ city }}
              </button>
              <div v-if="!filteredDestinationCities.length" class="city-empty">No city found</div>
            </div>
          </div>
        </div>

        <div class="form-row-two">
          <div class="bf-group">
            <label for="booking-date">Pickup Date</label>
            <input id="booking-date" type="date" v-model="form.pickup_date" :min="today">
          </div>
          <div class="bf-group">
            <label for="booking-time">Pickup Time</label>
            <input id="booking-time" type="time" v-model="form.pickup_time">
          </div>
        </div>

        <div class="form-row-two">
          <div class="bf-group">
            <label for="booking-passengers">Passengers</label>
            <select id="booking-passengers" v-model="form.passengers">
              <option v-for="n in 17" :key="n" :value="n">{{ n }}</option>
            </select>
          </div>
          <div class="bf-group">
            <label for="booking-trip-type">Trip Type</label>
            <select id="booking-trip-type" v-model="form.trip_type">
              <option value="oneway">One Way</option>
              <option value="round-trip">Round Trip</option>
            </select>
          </div>
        </div>

        <div class="form-divider"><span>Customer Information</span></div>

        <div class="form-row-two">
          <div class="bf-group">
            <label for="booking-name">Full Name <span class="required">*</span></label>
            <input id="booking-name" type="text" v-model="form.name" placeholder="Your full name" autocomplete="name">
          </div>
          <div class="bf-group">
            <label for="booking-phone">Mobile Number <span class="required">*</span></label>
            <input id="booking-phone" type="tel" v-model="form.phone" placeholder="10-digit number" maxlength="10" autocomplete="tel">
          </div>
        </div>

        <div class="form-row-two">
          <div class="bf-group">
            <label for="booking-email">Email <span class="required">*</span></label>
            <input id="booking-email" type="email" v-model="form.email" placeholder="you@email.com" autocomplete="email">
          </div>
          <div class="bf-group">
            <label for="booking-address">Address</label>
            <input id="booking-address" type="text" v-model="form.address" placeholder="Your address" autocomplete="street-address">
          </div>
        </div>

        <p v-if="formError" class="form-error">{{ formError }}</p>

        <button class="btn-confirm" type="button" :disabled="submitting" @click="submit">
          <span v-if="submitting"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
          <span v-else><i class="fas fa-check-circle"></i> Confirm &amp; Proceed to Payment</span>
        </button>

        <div class="advance-box">
          <i class="fas fa-info-circle"></i>
          <p>
            <strong>25% Advance Payment Required</strong><br>
            Pay 25% to confirm your booking. Balance payable to the driver at pickup.
          </p>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { computed, ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useBookingStore } from '../stores/booking'
import { useApi } from '../composables/useApi'
import { useWatermark } from '../composables/useWatermark'
import { openWhatsApp } from '../utils/contactLinks'
import { getInitialSiteData } from '../utils/siteData'
import PageHeader from '../components/common/PageHeader.vue'
import AppFooter  from '../components/layout/AppFooter.vue'

const router    = useRouter()
const store     = useBookingStore()
const api       = useApi()
const { stamp } = useWatermark()

const cab     = store.selectedCab     || {}
const pricing = store.selectedPricing || {}
const today   = new Date().toISOString().split('T')[0]
const hasDiscount = computed(() => Number(pricing?.discount || 0) > 0)

const canvasEl  = ref(null)
const submitting = ref(false)
const formError  = ref('')
const cities = ref([])
const openSource = ref(false)
const openDestination = ref(false)

const form = reactive({
  source:      store.search.from     || '',
  destination: store.search.to       || '',
  pickup_date: store.search.date     || '',
  pickup_time: store.search.time     || '',
  trip_type:   store.search.tripType || 'oneway',
  passengers:  1,
  name:        '',
  phone:       '',
  email:       '',
  address:     '',
})

const filteredSourceCities = computed(() => getFilteredCities(form.source))
const filteredDestinationCities = computed(() => getFilteredCities(form.destination))

onMounted(async () => {
  if (canvasEl.value && cab.image) {
    stamp(canvasEl.value, cab.image)
  }

  const initialData = getInitialSiteData()
  if (initialData?.cities) {
    cities.value = initialData.cities
    return
  }

  try {
    const res = await api.getHomepage()
    cities.value = res.data.cities || []
  } catch (e) {
    cities.value = []
  }
})

function getFilteredCities(keyword) {
  const query = keyword.trim().toLowerCase()

  if (!query) {
    return cities.value.slice(0, 6)
  }

  return cities.value
    .filter((city) => city.toLowerCase().includes(query))
    .slice(0, 8)
}

function selectCity(field, city) {
  form[field] = city

  if (field === 'source') {
    openSource.value = false
    return
  }

  openDestination.value = false
}

function closeDropdown(field) {
  window.setTimeout(() => {
    if (field === 'source') {
      openSource.value = false
      return
    }

    openDestination.value = false
  }, 120)
}

async function submit() {
  formError.value = ''
  if (!form.source)               { formError.value = 'Please enter source location';             return }
  if (!form.destination)          { formError.value = 'Please enter destination';                 return }
  if (!form.name.trim())          { formError.value = 'Please enter your full name';              return }
  if (!/^\d{10}$/.test(form.phone)) { formError.value = 'Please enter a valid 10-digit mobile'; return }
  if (!form.email.includes('@'))  { formError.value = 'Please enter a valid email address';      return }

  submitting.value = true
  try {
    const resolvedPricing = await resolvePricing()
    const booking = {
      cab_id:      cab.id,
      cab_name:    cab.name,
      final_price: resolvedPricing?.final || pricing?.final || 0,
      ...form,
    }
    const res = await api.createBooking(booking)
    store.setConfirmation(res.data)
    openWhatsApp(bookingMessage({
      ...booking,
      reference: res.data?.reference,
    }))
    router.push('/booking/success')
  } catch (e) {
    formError.value = e?.message || 'Booking failed. Please try again.'
  } finally {
    submitting.value = false
  }
}

async function resolvePricing() {
  if (
    pricing?.final &&
    store.search.from === form.source &&
    store.search.to === form.destination &&
    store.search.date === form.pickup_date &&
    store.search.time === form.pickup_time &&
    store.search.tripType === form.trip_type
  ) {
    return pricing
  }

  const res = await api.searchCabs({
    from: form.source,
    to: form.destination,
    date: form.pickup_date,
    time: form.pickup_time,
    trip_type: form.trip_type,
  })
  const quotedCab = (res.data || []).find((item) => item.id === cab.id)

  if (!quotedCab) {
    return pricing?.final ? pricing : null
  }

  return {
    base: quotedCab.base_price,
    final: quotedCab.final_price,
    discount: quotedCab.discount,
    discount_pct: quotedCab.discount_pct,
    fixed_price: quotedCab.fixed_price,
  }
}

function bookingMessage(booking) {
  const tripType = booking.trip_type === 'round-trip' ? 'Round Trip' : 'One Way'
  const fare = Number(booking.final_price || 0).toLocaleString('en-IN')

  return [
    'Hello Maharana Travels, new booking request:',
    `Reference: ${booking.reference || 'Pending'}`,
    `Name: ${booking.name}`,
    `Phone: ${booking.phone}`,
    `Email: ${booking.email}`,
    `Pickup: ${booking.source}`,
    `Destination: ${booking.destination}`,
    `Date: ${booking.pickup_date}`,
    `Time: ${booking.pickup_time}`,
    `Trip Type: ${tripType}`,
    `Passengers: ${booking.passengers}`,
    `Cab: ${booking.cab_name}`,
    `Fare: Rs. ${fare}`,
    `Address: ${booking.address || 'Not provided'}`,
  ].join('\n')
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
  z-index: 30;
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
