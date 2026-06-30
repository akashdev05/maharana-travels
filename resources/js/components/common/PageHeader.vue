<template>
  <div class="page-header">
    <div class="container">
      <h1>{{ title }}</h1>
      <p v-if="subtitle">{{ subtitle }}</p>
      <div v-if="breadcrumb" class="breadcrumb-links">
        <RouterLink to="/">Home</RouterLink>
        <i class="fas fa-angle-right"></i>
        <span>{{ breadcrumb }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title:      { type: String, required: true },
  subtitle:   { type: String, default: '' },
  breadcrumb: { type: String, default: '' },
})
</script>
