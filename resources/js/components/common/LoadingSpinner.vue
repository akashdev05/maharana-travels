<template>
  <div class="spinner-wrap">
    <div class="spinner"></div>
    <p v-if="text" class="spinner-text">{{ text }}</p>
  </div>
</template>

<script setup>
defineProps({ text: { type: String, default: 'Loading...' } })
</script>

<style scoped>
.spinner-wrap { display:flex; flex-direction:column; align-items:center; padding:40px; }
.spinner {
  width:44px; height:44px;
  border:4px solid #e0e0e0;
  border-top-color:#c8102e;
  border-radius:50%;
  animation:spin .8s linear infinite;
}
@keyframes spin { to { transform:rotate(360deg); } }
.spinner-text { margin-top:12px; color:#4b5563; font-size:14px; }
</style>
