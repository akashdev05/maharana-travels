<template>
  <div>
    <!-- CTA -->
    <section class="cta-section">
      <div class="container text-center">
        <h2>Book Online Today And Travel In Comfort</h2>
        <p>On Your Next Trip</p>
        <p>
          Call Us On:
          <a :href="phoneLink()"><strong>{{ CONTACT_PHONE }}</strong></a>
          &nbsp;|&nbsp; Email:
          <a href="mailto:maharanatravels0001@gmail.com"><strong>maharanatravels0001@gmail.com</strong></a>
        </p>
        <RouterLink to="/" class="btn-cta">Online Booking &rsaquo;</RouterLink>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-grid container">

        <div class="footer-col">
          <h5>About Us</h5>
          <p>Maharana Travels is your trusted partner for reliable and comfortable transportation across North India.</p>
          <div class="social-icons" aria-label="Social media">
            <span class="social-icon fb" aria-label="Facebook">
              <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                <path d="M15.1 8.2h-2V6.9c0-.5.3-.6.6-.6H15V4h-2c-2.2 0-3.3 1.3-3.3 3.1v1.1H8v2.6h1.7V20h3.4v-9.2h1.8l.2-2.6Z" />
              </svg>
            </span>
            <span class="social-icon ig" aria-label="Instagram">
              <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                <rect x="5" y="5" width="14" height="14" rx="4" />
                <circle cx="12" cy="12" r="3.2" />
                <circle cx="16.3" cy="7.7" r="1" />
              </svg>
            </span>
            <span class="social-icon yt" aria-label="YouTube">
              <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                <path d="M21 12s0-3.2-.4-4.7c-.2-.8-.8-1.4-1.6-1.6C17.5 5.3 12 5.3 12 5.3s-5.5 0-7 .4c-.8.2-1.4.8-1.6 1.6C3 8.8 3 12 3 12s0 3.2.4 4.7c.2.8.8 1.4 1.6 1.6 1.5.4 7 .4 7 .4s5.5 0 7-.4c.8-.2 1.4-.8 1.6-1.6.4-1.5.4-4.7.4-4.7Z" />
                <path class="play" d="m10.5 9.2 4.5 2.8-4.5 2.8V9.2Z" />
              </svg>
            </span>
          </div>
        </div>

        <div class="footer-col">
          <h5>Quick Links</h5>
          <ul class="footer-links">
            <li v-for="l in quickLinks" :key="l.to">
              <RouterLink :to="l.to">{{ l.label }}</RouterLink>
            </li>
          </ul>
        </div>

        <div class="footer-col">
          <h5>Our Vehicles</h5>
          <ul class="footer-links">
            <li v-for="v in vehicles" :key="v">
              <RouterLink to="/our-cabs">{{ v }}</RouterLink>
            </li>
          </ul>
        </div>

        <div class="footer-col">
          <h5>Contact Us</h5>
          <p><i class="fas fa-map-marker-alt mr-1"></i> Maharana Travels, Shop No 7, Juneja Square, Highland Marg, Zirakpur, Nabha, Punjab 140603</p>
          <p class="mt-2"><i class="fas fa-phone mr-1"></i> <a :href="phoneLink()">{{ CONTACT_PHONE }}</a></p>
          <p class="mt-2"><i class="fas fa-envelope mr-1"></i> <a href="mailto:maharanatravels0001@gmail.com">maharanatravels0001@gmail.com</a></p>
        </div>
      </div>

      <div class="footer-bottom">
        © {{ year }} Maharana Travels. All Rights Reserved
      </div>
    </footer>
  </div>
</template>

<script setup>
import { CONTACT_PHONE, phoneLink } from '../../utils/contactLinks'

const year = new Date().getFullYear()

const quickLinks = [
  { to: '/our-cabs',     label: 'Our Cabs'     },
  { to: '/our-services', label: 'Our Services'  },
  { to: '/taxi-service-chandigarh', label: 'Chandigarh Taxi' },
  { to: '/taxi-service-ramgarh', label: 'Ramgarh Taxi & Tours' },
  { to: '/chandigarh-airport-taxi', label: 'Airport Taxi' },
  { to: '/cities',       label: 'Cities'        },
  { to: '/about',        label: 'About Us'      },
  { to: '/contact',      label: 'Contact Us'    },
]

const vehicles = ['Maruti Dzire', 'Toyota Crysta', 'Kia Carens', 'Force Urbania']
</script>
