<template>
  <div id="maharana-app">
    <AppHeader />
    <main>
      <RouterView />
    </main>
    <MobileBar />
    <FloatCall />
  </div>
</template>

<script setup>
import AppHeader from './components/layout/AppHeader.vue'
import MobileBar from './components/layout/MobileBar.vue'
import FloatCall from './components/layout/FloatCall.vue'
</script>
