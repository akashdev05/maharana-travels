<template>
  <div class="mobile-bar">
    <a :href="phoneLink()" class="bar-call">
      <i class="fas fa-phone"></i> {{ CONTACT_PHONE }}
    </a>
    <a :href="whatsappLink()" class="bar-wa" target="_blank" rel="noopener">
      <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
  </div>
</template>

<script setup>
import { CONTACT_PHONE, phoneLink, whatsappLink } from '../../utils/contactLinks'
</script>
