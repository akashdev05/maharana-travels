<template>
  <div class="thankyou-wrap">
    <div class="thankyou-card">
      <div class="thankyou-icon">✓</div>
      <h2>Booking Confirmed!</h2>

      <div v-if="confirmation" class="confirmation-ref">
        Booking Reference: <strong>{{ confirmation.reference }}</strong>
      </div>

      <p>
        Thank you for booking with <strong>Maharana Travels</strong>.<br>
        Our team will contact you shortly to confirm your ride details.
      </p>
      <p class="help-text">
        For immediate assistance:<br>
        <a :href="phoneLink()" class="ref-phone">{{ CONTACT_PHONE }}</a>
      </p>

      <div class="success-actions">
        <RouterLink to="/" class="btn-book">
          <i class="fas fa-home"></i> Back to Home
        </RouterLink>
        <a :href="whatsappLink(successMessage)" class="btn-wa-lg" target="_blank" rel="noopener">
          <i class="fab fa-whatsapp"></i> WhatsApp Us
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useBookingStore } from '../stores/booking'
import { CONTACT_PHONE, phoneLink, whatsappLink } from '../utils/contactLinks'
const store        = useBookingStore()
const confirmation = computed(() => store.confirmation)
const successMessage = computed(() => {
  const booking = confirmation.value

  if (!booking) {
    return 'Hello Maharana Travels, I need help with my booking.'
  }

  return [
    'Hello Maharana Travels, I need help with this booking:',
    `Reference: ${booking.reference || 'Pending'}`,
    `Name: ${booking.name || ''}`,
    `Phone: ${booking.phone || ''}`,
    `Pickup: ${booking.source || ''}`,
    `Destination: ${booking.destination || ''}`,
    `Date: ${booking.pickup_date || ''}`,
    `Time: ${booking.pickup_time || ''}`,
    `Cab: ${booking.cab_name || ''}`,
  ].join('\n')
})
</script>

<style scoped>
.thankyou-wrap   { min-height:70vh; display:flex; align-items:center; justify-content:center; background:#f8f9fa; padding:30px 20px; }
.thankyou-card   { background:#fff; border-radius:16px; box-shadow:0 10px 40px rgba(0,0,0,.1); padding:50px 40px; text-align:center; max-width:500px; width:100%; }
.thankyou-icon   { width:80px; height:80px; background:linear-gradient(135deg,#0f7a3a,#0b6530); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:36px; color:#fff; }
h2               { color:#1a1a2e; font-size:26px; font-weight:800; margin-bottom:12px; }
.confirmation-ref{ background:#f0f9ff; border:1px solid #b3e0ff; border-radius:8px; padding:10px 16px; font-size:14px; color:#0066cc; margin-bottom:16px; }
p                { color:#4b5563; font-size:15px; line-height:1.7; margin-bottom:14px; }
.help-text       { font-size:14px; }
.ref-phone       { color:#c8102e; font-size:20px; font-weight:700; text-decoration:none; }
.success-actions { display:flex; gap:12px; justify-content:center; margin-top:24px; flex-wrap:wrap; }
.btn-wa-lg       { background:#0f7a3a; color:#fff; padding:12px 24px; border-radius:25px; text-decoration:none; font-size:14px; font-weight:700; display:flex; align-items:center; gap:6px; }
@media(max-width:480px){
  .thankyou-wrap{ padding:24px 12px; align-items:flex-start; }
  .thankyou-card{ padding:34px 16px; border-radius:8px; }
  .thankyou-icon{ width:66px; height:66px; font-size:30px; }
  h2{ font-size:23px; }
  .confirmation-ref{ overflow-wrap:anywhere; }
  .success-actions{ display:grid; grid-template-columns:1fr; }
  .btn-wa-lg{ justify-content:center; border-radius:8px; min-height:46px; }
}
</style>
