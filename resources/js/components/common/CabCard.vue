<template>
  <div class="cab-card">
    <div class="cab-inner">

      <!-- Image with canvas watermark -->
      <div class="cab-img-wrap">
        <canvas
          ref="canvasEl"
          :width="376"
          :height="260"
          :title="cab.name"
        ></canvas>
        <div class="cab-name">{{ cab.name }}</div>
      </div>

      <!-- Details -->
      <div class="cab-details">
        <div class="cab-type-badge">
          {{ cab.type }} &nbsp;|&nbsp; {{ cab.seats }} Seats &nbsp;|&nbsp;
          {{ cab.bags }} Bags &nbsp;|&nbsp; AC
        </div>
        <div class="cab-features-row">
          <div class="cab-feat">
            <i class="fas fa-tachometer-alt"></i>
            <div>
              <strong>KM Charges</strong>
              ₹{{ cab.rate_base }}/km incl &middot; ₹{{ cab.rate_extra }}/km extra
            </div>
          </div>
          <div class="cab-feat">
            <i class="fas fa-gas-pump"></i>
            <div><strong>Fuel</strong>{{ cab.fuel }}</div>
          </div>
          <div class="cab-feat">
            <i class="fas fa-heart"></i>
            <div><strong>Cancellation</strong>Free before 6 hrs</div>
          </div>
        </div>
      </div>

      <!-- Pricing -->
      <div class="cab-pricing">
        <template v-if="pricing">
          <div v-if="hasDiscount" class="price-original">
            ₹{{ pricing.base.toLocaleString('en-IN') }}
            <span class="disc-badge">{{ pricing.discount_pct || 5 }}% off</span>
          </div>
          <div class="price-final">₹{{ pricing.final.toLocaleString('en-IN') }}</div>
          <div class="price-per-km">{{ pricing.fixed_price ? 'fixed fare' : 'incl. taxes' }}</div>
        </template>
        <template v-else>
          <div class="price-final">₹{{ cab.price_per_km }}</div>
          <div class="price-per-km">per/km</div>
        </template>

        <button class="btn-book" type="button" @click="$emit('book', cab, pricing)">
          Book Now
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted, watch } from 'vue'
import { useWatermark } from '../../composables/useWatermark'

const props = defineProps({
  cab:     { type: Object, required: true },
  pricing: { type: Object, default: null  },
})

defineEmits(['book'])

const canvasEl  = ref(null)
const { stamp } = useWatermark()
const hasDiscount = computed(() => Number(props.pricing?.discount || 0) > 0)
let observer = null
let hasDrawn = false

function draw() {
  hasDrawn = true
  if (canvasEl.value && props.cab?.image) {
    stamp(canvasEl.value, props.cab.image)
  }
}

onMounted(() => {
  if (!canvasEl.value || !('IntersectionObserver' in window)) {
    draw()
    return
  }

  window.setTimeout(() => {
    if (!hasDrawn) {
      draw()
    }
  }, 800)

  observer = new IntersectionObserver((entries) => {
    if (!entries.some((entry) => entry.isIntersecting)) return
    observer?.disconnect()
    observer = null
    draw()
  }, { rootMargin: '240px' })

  observer.observe(canvasEl.value)
})

watch(() => props.cab?.image, () => {
  if (hasDrawn) {
    draw()
  }
})

onUnmounted(() => {
  observer?.disconnect()
})
</script>
