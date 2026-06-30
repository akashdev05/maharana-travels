<template>
  <div>
    <!-- ── Top Bar ── -->
    <div class="top-header">
      <div class="container">
        <RouterLink to="/" class="logo-section">
          <span class="logo-mark">
            <img
              class="site-logo"
              src="/images/logo-180.webp"
              alt="Maharana Travels"
              width="180"
              height="180"
              fetchpriority="high"
            >
          </span>
          <span class="logo-copy">
            <span class="logo-title">Maharana Travels</span>
            <span class="logo-tagline">Safe Journey, Royal Experience</span>
          </span>
        </RouterLink>

        <div class="top-actions">
          <button
            class="theme-toggle"
            type="button"
            :aria-label="theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
            :aria-pressed="theme === 'dark'"
            :title="theme === 'dark' ? 'Light mode' : 'Dark mode'"
            @click="toggleTheme"
          >
            <span class="theme-toggle-icon" :class="theme === 'dark' ? 'sun' : 'moon'" aria-hidden="true"></span>
            <span class="theme-toggle-text">{{ theme === 'dark' ? 'Light' : 'Dark' }}</span>
          </button>
          <a :href="whatsappLink()" class="btn-wa" target="_blank" rel="noopener">
            <i class="fab fa-whatsapp"></i> WhatsApp
          </a>
          <a :href="phoneLink()" class="btn-call">
            <i class="fas fa-phone"></i> {{ CONTACT_PHONE }}
          </a>
        </div>
      </div>
    </div>

    <!-- ── Navbar ── -->
    <nav class="main-navbar" aria-label="Primary navigation">
      <div class="container">
        <ul id="primary-navigation" class="nav-links" :class="{ open: menuOpen }">
          <li v-for="link in navLinks" :key="link.to">
            <RouterLink :to="link.to" @click="menuOpen = false">{{ link.label }}</RouterLink>
          </li>
        </ul>
        <button
          class="hamburger"
          type="button"
          :aria-expanded="menuOpen"
          aria-controls="primary-navigation"
          aria-label="Menu"
          @click="menuOpen = !menuOpen"
        >
          <span class="hamburger-icon" :class="{ open: menuOpen }" aria-hidden="true"></span>
        </button>
      </div>
    </nav>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { CONTACT_PHONE, phoneLink, whatsappLink } from '../../utils/contactLinks'

const menuOpen  = ref(false)
const theme = ref('light')

const navLinks = [
  { to: '/',             label: 'Home'        },
  { to: '/about',        label: 'About Us'    },
  { to: '/our-cabs',     label: 'Our Cabs'    },
  { to: '/our-services', label: 'Our Services'},
  { to: '/cities',       label: 'Cities'      },
  { to: '/wedding-cars', label: 'Wedding Car' },
  { to: '/contact',      label: 'Contact Us'  },
]

onMounted(() => {
  const savedTheme = window.localStorage.getItem('maharana-theme')
  const preferredTheme = window.matchMedia?.('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
  theme.value = savedTheme || preferredTheme
  applyTheme(theme.value)
})

function toggleTheme() {
  theme.value = theme.value === 'dark' ? 'light' : 'dark'
  window.localStorage.setItem('maharana-theme', theme.value)
  applyTheme(theme.value)
}

function applyTheme(value) {
  document.documentElement.dataset.theme = value
  document.querySelector('meta[name="theme-color"]')?.setAttribute(
    'content',
    value === 'dark' ? '#0f172a' : '#ffffff',
  )
}
</script>
