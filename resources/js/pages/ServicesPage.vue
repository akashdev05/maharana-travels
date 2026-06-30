<template>
  <div>
    <PageHeader title="Our Services" breadcrumb="Our Services" />

    <div class="container" style="padding-top:30px;padding-bottom:50px;">
      <LoadingSpinner v-if="loading" />

      <div v-else>
        <div class="services-grid">
          <button
            v-for="s in paged"
            :key="s.id"
            class="service-card"
            type="button"
            :aria-label="`View cab rates for ${s.title}`"
            @click="$router.push(`/our-services/${s.slug}`)"
          >
            <img
              src="/images/card.png"
              :alt="s.title"
              loading="lazy"
            >
            <div class="service-card-body">
              <h3>{{ s.title }}</h3>
              <div class="service-card-arrow">
                <i class="fas fa-arrow-right"></i> View Cabs &amp; Rates
              </div>
            </div>
          </button>
        </div>

        <!-- Pagination -->
        <div class="pagination">
          <button
            class="page-btn"
            type="button"
            :disabled="page === 1"
            @click="page--"
          >Previous</button>

          <button
            v-for="p in totalPages" :key="p"
            class="page-btn"
            type="button"
            :class="{ active: p === page }"
            :aria-current="p === page ? 'page' : null"
            @click="page = p"
          >{{ p }}</button>

          <button
            class="page-btn"
            type="button"
            :disabled="page === totalPages"
            @click="page++"
          >Next</button>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useApi } from '../composables/useApi'
import PageHeader     from '../components/common/PageHeader.vue'
import LoadingSpinner from '../components/common/LoadingSpinner.vue'
import AppFooter      from '../components/layout/AppFooter.vue'

const api      = useApi()
const services = ref([])
const loading  = ref(true)
const page     = ref(1)
const PER_PAGE = 12

onMounted(async () => {
  try {
    const res    = await api.getServices()
    services.value = res.data
  } finally {
    loading.value = false
  }
})

const totalPages = computed(() => Math.ceil(services.value.length / PER_PAGE))
const paged      = computed(() => {
  const start = (page.value - 1) * PER_PAGE
  return services.value.slice(start, start + PER_PAGE)
})
</script>
