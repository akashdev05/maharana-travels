<template>
  <div>
    <PageHeader title="OUR CABS" breadcrumb="Our Cabs" subtitle="Choose From Our Wide Range Of Vehicles" />

    <div class="section-heading"><h3>Select a Car for Taxi &amp; Travel Services</h3></div>

    <section class="cabs-section" style="padding-top:10px;">
      <div class="container">
        <LoadingSpinner v-if="loading" />
        <CabCard v-for="cab in cabs" :key="cab.id" :cab="cab" @book="bookCab" />
      </div>
    </section>
    <AppFooter />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useBookingStore } from '../stores/booking'
import { useApi } from '../composables/useApi'
import PageHeader     from '../components/common/PageHeader.vue'
import CabCard        from '../components/common/CabCard.vue'
import LoadingSpinner from '../components/common/LoadingSpinner.vue'
import AppFooter      from '../components/layout/AppFooter.vue'

const router = useRouter()
const store  = useBookingStore()
const api    = useApi()
const cabs   = ref([])
const loading = ref(true)

onMounted(async () => {
  try { const r = await api.getCabs(); cabs.value = r.data }
  finally { loading.value = false }
})

function bookCab(cab) {
  store.selectCab(cab, null)
  router.push('/booking')
}
</script>
