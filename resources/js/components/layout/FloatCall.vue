<template>
  <a :href="phoneLink()" class="float-call" title="Call Us" aria-label="Call Maharana Travels">
    <span>Call</span>
  </a>
</template>

<script setup>
import { phoneLink } from '../../utils/contactLinks'
</script>
