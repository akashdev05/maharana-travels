<template>
  <div>
    <PageHeader title="Contact Us" breadcrumb="Contact Us" />
    <div class="container" style="padding:50px 20px;">
      <div class="contact-grid">

        <div class="contact-card">
          <h3>Get In Touch</h3>
          <div class="bf-group">
            <label for="contact-name">Your Name <span class="required">*</span></label>
            <input id="contact-name" type="text" v-model="form.name" placeholder="Full name" autocomplete="name">
          </div>
          <div class="bf-group">
            <label for="contact-phone">Mobile <span class="required">*</span></label>
            <input id="contact-phone" type="tel" v-model="form.phone" placeholder="+91 XXXXXXXXXX" maxlength="10" autocomplete="tel">
          </div>
          <div class="bf-group">
            <label for="contact-message">Message <span class="required">*</span></label>
            <textarea id="contact-message" v-model="form.message" rows="4" placeholder="Your message..."></textarea>
          </div>

          <p v-if="errorMsg" class="form-error">{{ errorMsg }}</p>
          <p v-if="successMsg" class="form-success">{{ successMsg }}</p>

          <button class="btn-confirm" type="button" :disabled="sending" @click="send" style="margin-top:8px;">
            <i :class="sending ? 'fas fa-spinner fa-spin' : 'fas fa-paper-plane'"></i>
            {{ sending ? 'Sending...' : 'Send Message' }}
          </button>
        </div>

        <div class="contact-card">
          <h3>Contact Info</h3>
          <div class="contact-info-row" v-for="info in infos" :key="info.label">
            <i :class="`fas ${info.icon}`"></i>
            <div>
              <strong>{{ info.label }}</strong>
              <p v-if="!info.href">{{ info.value }}</p>
              <a v-else :href="info.href" :target="info.target || null">{{ info.value }}</a>
            </div>
          </div>
          <a :href="whatsappLink()" class="btn-wa" target="_blank" rel="noopener" style="margin-top:20px;display:inline-flex;">
            <i class="fab fa-whatsapp"></i> Chat on WhatsApp
          </a>
        </div>
      </div>
    </div>
    <AppFooter />
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useApi } from '../composables/useApi'
import { CONTACT_PHONE, openWhatsApp, phoneLink, whatsappLink } from '../utils/contactLinks'
import PageHeader from '../components/common/PageHeader.vue'
import AppFooter  from '../components/layout/AppFooter.vue'

const api        = useApi()
const sending    = ref(false)
const errorMsg   = ref('')
const successMsg = ref('')

const form = reactive({ name:'', phone:'', message:'' })

async function send() {
  errorMsg.value = successMsg.value = ''
  if (!form.name.trim())            { errorMsg.value = 'Please enter your name';    return }
  if (!/^\d{10}$/.test(form.phone)) { errorMsg.value = 'Enter a valid 10-digit number'; return }
  if (!form.message.trim())         { errorMsg.value = 'Please enter a message';    return }

  sending.value = true
  try {
    const res = await api.sendContact(form)
    openWhatsApp(contactMessage(form))
    successMsg.value = res.message
    form.name = form.phone = form.message = ''
  } catch (e) {
    errorMsg.value = e?.message || 'Failed to send. Try calling us directly.'
  } finally {
    sending.value = false
  }
}

const infos = [
  { icon:'fa-map-marker-alt', label:'Address',   value:'Maharana Travels, Shop No 7, Juneja Square, Highland Marg, Zirakpur, Nabha, Punjab 140603' },
  { icon:'fa-phone',          label:'Phone',     value:CONTACT_PHONE, href:phoneLink() },
  { icon:'fa-envelope',       label:'Email',     value:'maharanatravels0001@gmail.com', href:'mailto:maharanatravels0001@gmail.com' },
  { icon:'fa-clock',          label:'Hours',     value:'Available 24 / 7'                  },
]

function contactMessage(details) {
  return [
    'Hello Maharana Travels, new contact request:',
    `Name: ${details.name}`,
    `Phone: ${details.phone}`,
    `Message: ${details.message}`,
  ].join('\n')
}
</script>

<style scoped>
.contact-grid { display:grid; grid-template-columns:1fr 1fr; gap:30px; max-width:900px; margin:0 auto; }
.contact-card { background:#fff; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,.08); padding:30px; min-width:0; }
.contact-card h3 { color:#1a1a2e; font-size:20px; font-weight:700; margin-bottom:20px; }
.contact-info-row { display:flex; gap:14px; margin-bottom:18px; }
.contact-info-row i { color:#c8102e; font-size:18px; margin-top:2px; }
.contact-info-row strong { color:#1a1a2e; font-size:14px; display:block; }
.contact-info-row p { color:#4b5563; font-size:13px; margin:2px 0 0; overflow-wrap:anywhere; }
.contact-info-row a { color:#4b5563; font-size:13px; display:inline-block; margin:2px 0 0; overflow-wrap:anywhere; text-decoration:none; }
.contact-info-row a:hover { color:#c8102e; }
textarea { width:100%; padding:12px 14px; border:2px solid #e0e0e0; border-radius:10px; font-size:14px; resize:vertical; outline:none; font-family:inherit; }
textarea:focus { border-color:#c8102e; }
.form-success { color:#28a745; font-size:14px; margin-bottom:8px; }
@media(max-width:700px){
  .contact-grid{ grid-template-columns:1fr; gap:18px; }
  .contact-card{ padding:22px 16px; border-radius:8px; }
  .contact-card h3{ font-size:18px; }
  .contact-info-row{ gap:12px; }
  textarea{ font-size:16px; min-height:120px; }
}
</style>
