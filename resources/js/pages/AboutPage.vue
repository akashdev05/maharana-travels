<template>
  <div>
    <PageHeader title="About Us" breadcrumb="About Us" />
    <div class="container" style="padding:50px 20px;">
      <div class="about-card">
        <h2>Welcome to Maharana Travels</h2>
        <p>Maharana Travels is your trusted cab service partner across North India. We are committed to providing safe, comfortable, and affordable taxi services for all your travel needs — whether it's an airport transfer, an outstation trip, or a local city ride.</p>
        <p>We offer a wide fleet of vehicles from hatchbacks to tempo travellers, ensuring there is a perfect cab for every group size and budget.</p>

        <div class="about-features">
          <div class="about-feat" v-for="f in features" :key="f.title">
            <div class="about-feat-icon"><i :class="`fas ${f.icon}`"></i></div>
            <div>
              <strong>{{ f.title }}</strong>
              <p>{{ f.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <AppFooter />
  </div>
</template>

<script setup>
import PageHeader from '../components/common/PageHeader.vue'
import AppFooter  from '../components/layout/AppFooter.vue'

const features = [
  { icon:'fa-shield-alt',    title:'100% Safe',          desc:'Verified drivers and well-maintained, GPS-enabled vehicles.'   },
  { icon:'fa-clock',         title:'24/7 Available',     desc:'Book anytime — our cabs run around the clock.'                 },
  { icon:'fa-ban',           title:'Free Cancellation',  desc:'Cancel up to 6 hours before pickup at zero charge.'            },
  { icon:'fa-balance-scale', title:'Transparent Pricing',desc:'No hidden fees. What you see is what you pay.'                 },
  { icon:'fa-car-crash',     title:'Fully Insured',      desc:'All vehicles carry comprehensive insurance coverage.'          },
  { icon:'fa-headset',       title:'24/7 Support',       desc:'Our support team is always a call or WhatsApp message away.'   },
]
</script>

<style scoped>
.about-card { max-width:860px; margin:0 auto; background:#fff; border-radius:14px; box-shadow:0 4px 24px rgba(0,0,0,.09); padding:40px; min-width:0; }
.about-card h2 { color:#1a1a2e; font-size:26px; font-weight:800; margin-bottom:16px; }
.about-card p  { color:#4b5563; line-height:1.8; margin-bottom:16px; font-size:15px; }
.about-features { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:30px; }
.about-feat { display:flex; gap:14px; align-items:flex-start; background:#f8f9fa; border-radius:10px; padding:16px; }
.about-feat-icon { width:42px; height:42px; background:#c8102e; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px; flex-shrink:0; }
.about-feat strong { color:#1a1a2e; font-size:14px; }
.about-feat p { color:#4b5563; font-size:13px; margin:4px 0 0; }
@media(max-width:600px){
  .about-card{ padding:24px 16px; border-radius:8px; }
  .about-card h2{ font-size:22px; line-height:1.25; }
  .about-card p{ font-size:14px; line-height:1.75; }
  .about-features{ grid-template-columns:1fr; gap:14px; margin-top:22px; }
  .about-feat{ padding:14px; }
}
</style>
