<template>
  <div>
    <section class="wedding-hero-section">
      <div class="hero-overlay"></div>
      <div class="container">
        <div class="hero-content">
          <span class="hero-kicker">Maharana Travels Wedding Fleet</span>
          <h1>Luxury Wedding Cars</h1>
          <p class="hero-subtitle">Make your special day unforgettable</p>
          <p class="hero-description">Premium vehicles, professional drivers, and elegant wedding transport across North India.</p>
          <a href="#wedding-cars" class="btn-hero-cta">Explore Collection</a>
        </div>
      </div>
    </section>

    <section class="wedding-cars-section" id="wedding-cars">
      <div class="container">
        <div class="section-header">
          <span>Our Fleet</span>
          <h2>Our Wedding Car Collection</h2>
          <p>Choose from our premium luxury fleet for baraat, bride entry, family movement, and full-day wedding service.</p>
        </div>

        <div class="wedding-filter-wrapper" aria-label="Wedding car filters">
          <button
            v-for="filter in filters"
            :key="filter.value"
            class="wedding-filter-btn"
            :class="{ active: activeFilter === filter.value }"
            type="button"
            @click="activeFilter = filter.value"
          >
            <i :class="filter.icon"></i>
            {{ filter.label }}
          </button>
        </div>

        <div class="wedding-cars-grid">
          <article
            v-for="car in filteredCars"
            :key="car.name"
            class="wedding-car-card"
            :class="{ featured: car.featured }"
          >
            <div v-if="car.featured" class="featured-badge">Most Popular</div>
            <div class="car-image-container">
              <img :src="car.image" :alt="car.name" class="car-image" loading="lazy">
              <div class="luxury-badge" :class="car.category">
                <i :class="categoryIcon(car.category)"></i>
                {{ car.badge }}
              </div>
            </div>

            <div class="car-details">
              <h3>{{ car.name }}</h3>
              <div class="car-specs">
                <div v-for="spec in car.specs" :key="spec.text" class="spec">
                  <i :class="spec.icon"></i>
                  <span>{{ spec.text }}</span>
                </div>
              </div>
              <div class="price-section">
                <span class="price-label">Starting from</span>
                <span class="price-amount">{{ car.price }}</span>
                <span class="price-duration">/ Day</span>
              </div>
              <div class="car-actions">
                <a :href="bookingLink(car.name)" target="_blank" rel="noopener" class="btn-book-wedding">Book Now</a>
                <RouterLink :to="{ path: '/contact', query: { car: car.name } }" class="btn-enquire">Enquire</RouterLink>
              </div>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section class="wedding-services-section">
      <div class="container">
        <div class="section-header">
          <span>Wedding Services</span>
          <h2>Complete Wedding Transportation</h2>
          <p>Coordinated travel for every moment of the day, from arrival to final departure.</p>
        </div>
        <div class="service-grid">
          <div v-for="service in services" :key="service.title" class="service-card">
            <div class="service-icon">
              <i :class="service.icon"></i>
            </div>
            <h3>{{ service.title }}</h3>
            <p>{{ service.text }}</p>
          </div>
        </div>
      </div>
    </section>

    <section class="wedding-cta-section">
      <div class="container">
        <h2>Book Your Dream Wedding Car</h2>
        <p>Make your special day even more memorable with clean cars, on-time drivers, and simple booking.</p>
        <div class="cta-buttons">
          <RouterLink to="/contact" class="btn-cta-primary">Contact Us</RouterLink>
          <a :href="phoneLink()" class="btn-cta-secondary">Call Now</a>
        </div>
      </div>
    </section>

    <AppFooter />
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import AppFooter from '../components/layout/AppFooter.vue'
import { phoneLink, whatsappLink } from '../utils/contactLinks'

const activeFilter = ref('all')

const filters = [
  { value: 'all', label: 'All Cars', icon: 'fas fa-gem' },
  { value: 'luxury', label: 'Luxury', icon: 'fas fa-crown' },
  { value: 'premium', label: 'Premium', icon: 'fas fa-star' },
  { value: 'classic', label: 'Classic', icon: 'fas fa-car-side' },
]

const commonSpecs = [
  { icon: 'fas fa-snowflake', text: 'Climate Control' },
  { icon: 'fas fa-music', text: 'Premium Sound' },
]

const cars = [
  {
    name: 'Mercedes Benz S-Class',
    category: 'luxury',
    badge: 'Luxury',
    price: 'Rs. 15,000',
    image: 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=600&h=400&fit=crop',
    specs: [{ icon: 'fas fa-users', text: '4 Passengers' }, ...commonSpecs],
  },
  {
    name: 'BMW 7 Series',
    category: 'luxury',
    badge: 'Luxury',
    price: 'Rs. 14,000',
    image: 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=600&h=400&fit=crop',
    specs: [{ icon: 'fas fa-users', text: '4 Passengers' }, ...commonSpecs],
  },
  {
    name: 'Audi A8',
    category: 'luxury',
    badge: 'Luxury',
    price: 'Rs. 13,500',
    image: 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=600&h=400&fit=crop',
    specs: [{ icon: 'fas fa-users', text: '4 Passengers' }, ...commonSpecs],
  },
  {
    name: 'Jaguar XJ',
    category: 'premium',
    badge: 'Premium',
    price: 'Rs. 12,000',
    image: 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=600&h=400&fit=crop',
    specs: [{ icon: 'fas fa-users', text: '4 Passengers' }, ...commonSpecs],
  },
  {
    name: 'Rolls Royce Phantom',
    category: 'luxury',
    badge: 'Ultra Luxury',
    price: 'Rs. 25,000',
    image: 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=600&h=400&fit=crop',
    specs: [{ icon: 'fas fa-users', text: '4 Passengers' }, ...commonSpecs],
    featured: true,
  },
  {
    name: 'Range Rover',
    category: 'premium',
    badge: 'Premium',
    price: 'Rs. 16,000',
    image: 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=600&h=400&fit=crop',
    specs: [{ icon: 'fas fa-users', text: '5 Passengers' }, ...commonSpecs],
  },
  {
    name: 'Toyota Fortuner',
    category: 'premium',
    badge: 'Premium',
    price: 'Rs. 10,000',
    image: 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=600&h=400&fit=crop',
    specs: [{ icon: 'fas fa-users', text: '7 Passengers' }, ...commonSpecs],
  },
  {
    name: 'Mercedes E-Class',
    category: 'classic',
    badge: 'Classic',
    price: 'Rs. 8,500',
    image: 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=600&h=400&fit=crop',
    specs: [{ icon: 'fas fa-users', text: '4 Passengers' }, ...commonSpecs],
  },
  {
    name: 'BMW 5 Series',
    category: 'classic',
    badge: 'Classic',
    price: 'Rs. 8,000',
    image: 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=600&h=400&fit=crop',
    specs: [{ icon: 'fas fa-users', text: '4 Passengers' }, ...commonSpecs],
  },
]

const services = [
  { icon: 'fas fa-car', title: 'Baraat Car', text: "Luxury vehicles for the groom's procession." },
  { icon: 'fas fa-heart', title: 'Bride Car', text: "Elegant cars for the bride's arrival." },
  { icon: 'fas fa-users', title: 'Family Cars', text: 'Comfortable vehicles for family members and guests.' },
  { icon: 'fas fa-route', title: 'Full Day Service', text: 'Complete wedding day transportation with driver support.' },
]

const filteredCars = computed(() => {
  if (activeFilter.value === 'all') {
    return cars
  }

  return cars.filter((car) => car.category === activeFilter.value)
})

function categoryIcon(category) {
  if (category === 'premium') return 'fas fa-star'
  if (category === 'classic') return 'fas fa-car-side'
  return 'fas fa-crown'
}

function bookingLink(carName) {
  return whatsappLink(`Hello Maharana Travels, I want to book ${carName} for a wedding.`)
}
</script>

<style scoped>
.wedding-hero-section {
  position: relative;
  min-height: 540px;
  display: flex;
  align-items: center;
  overflow: hidden;
  color: #fff;
  background:
    linear-gradient(90deg, rgba(20, 18, 20, .78), rgba(20, 18, 20, .32)),
    url('https://images.unsplash.com/photo-1511911767837-4b0f3b77c003?w=1600&h=900&fit=crop') center/cover;
}

.hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(0, 0, 0, .18), rgba(0, 0, 0, .42));
}

.hero-content {
  position: relative;
  max-width: 720px;
  padding: 90px 0;
}

.hero-kicker {
  display: inline-flex;
  margin-bottom: 14px;
  color: #ffd66b;
  font-size: 13px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: .08em;
}

.hero-content h1 {
  margin: 0 0 12px;
  font-size: 72px;
  line-height: 1.02;
  font-weight: 900;
  letter-spacing: 0;
}

.hero-subtitle {
  margin: 0 0 10px;
  font-size: 25px;
  font-weight: 700;
}

.hero-description {
  max-width: 560px;
  margin: 0 0 28px;
  color: rgba(255, 255, 255, .94);
  font-size: 17px;
  line-height: 1.7;
}

.btn-hero-cta,
.btn-cta-primary,
.btn-book-wedding {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 46px;
  padding: 12px 24px;
  border: 0;
  border-radius: 8px;
  background: #c8102e;
  color: #fff;
  font-size: 14px;
  font-weight: 800;
  text-decoration: none;
  transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
}

.btn-hero-cta:hover,
.btn-cta-primary:hover,
.btn-book-wedding:hover {
  color: #fff;
  background: #a50d26;
  box-shadow: 0 14px 28px rgba(200, 16, 46, .28);
  transform: translateY(-2px);
}

.wedding-cars-section,
.wedding-services-section {
  padding: 70px 0;
  background: #f7f8fb;
}

.wedding-services-section {
  background: #fff;
}

.section-header {
  max-width: 760px;
  margin: 0 auto 34px;
  text-align: center;
}

.section-header span {
  display: block;
  margin-bottom: 8px;
  color: #c8102e;
  font-size: 13px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: .08em;
}

.section-header h2 {
  margin: 0 0 10px;
  color: #1a1a2e;
  font-size: 34px;
  font-weight: 900;
}

.section-header p {
  margin: 0;
  color: #4b5563;
  font-size: 15px;
  line-height: 1.7;
}

.wedding-filter-wrapper {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 12px;
  margin-bottom: 34px;
}

.wedding-filter-btn {
  min-height: 44px;
  padding: 10px 18px;
  border: 1px solid #e2e5ec;
  border-radius: 8px;
  background: #fff;
  color: #313145;
  font-size: 14px;
  font-weight: 800;
  cursor: pointer;
  transition: all .2s ease;
}

.wedding-filter-btn i {
  margin-right: 7px;
  color: #c8102e;
}

.wedding-filter-btn.active,
.wedding-filter-btn:hover {
  border-color: #c8102e;
  background: #c8102e;
  color: #fff;
  box-shadow: 0 10px 22px rgba(200, 16, 46, .2);
}

.wedding-filter-btn.active i,
.wedding-filter-btn:hover i {
  color: #fff;
}

.wedding-cars-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 24px;
}

.wedding-car-card {
  position: relative;
  overflow: hidden;
  border: 1px solid #eceef4;
  border-radius: 8px;
  background: #fff;
  box-shadow: 0 12px 28px rgba(12, 18, 33, .08);
  transition: transform .2s ease, box-shadow .2s ease;
}

.wedding-car-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 18px 38px rgba(12, 18, 33, .13);
}

.wedding-car-card.featured {
  border-color: #f4bd2a;
}

.featured-badge {
  position: absolute;
  top: 14px;
  left: 14px;
  z-index: 2;
  padding: 7px 11px;
  border-radius: 8px;
  background: #f4bd2a;
  color: #1a1a2e;
  font-size: 12px;
  font-weight: 900;
}

.car-image-container {
  position: relative;
  aspect-ratio: 3 / 2;
  overflow: hidden;
  background: #eceef4;
}

.car-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform .28s ease;
}

.wedding-car-card:hover .car-image {
  transform: scale(1.05);
}

.luxury-badge {
  position: absolute;
  right: 14px;
  bottom: 14px;
  padding: 7px 11px;
  border-radius: 8px;
  background: rgba(25, 25, 38, .88);
  color: #fff;
  font-size: 12px;
  font-weight: 900;
}

.luxury-badge.premium {
  background: rgba(200, 16, 46, .92);
}

.luxury-badge.classic {
  background: rgba(39, 121, 104, .92);
}

.car-details {
  padding: 22px;
}

.car-details h3 {
  margin: 0 0 16px;
  color: #1a1a2e;
  font-size: 21px;
  font-weight: 900;
}

.car-specs {
  display: grid;
  gap: 9px;
  margin-bottom: 18px;
}

.spec {
  display: flex;
  align-items: center;
  gap: 9px;
  color: #5f6473;
  font-size: 14px;
}

.spec i {
  width: 18px;
  color: #c8102e;
}

.price-section {
  display: flex;
  align-items: baseline;
  gap: 6px;
  padding-top: 16px;
  border-top: 1px solid #eceef4;
}

.price-label,
.price-duration {
  color: #7b8090;
  font-size: 13px;
}

.price-amount {
  color: #c8102e;
  font-size: 25px;
  font-weight: 900;
}

.car-actions {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-top: 18px;
}

.btn-enquire,
.btn-cta-secondary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 46px;
  padding: 12px 20px;
  border: 1px solid #1a1a2e;
  border-radius: 8px;
  background: #fff;
  color: #1a1a2e;
  font-size: 14px;
  font-weight: 800;
  text-decoration: none;
  transition: all .2s ease;
}

.btn-enquire:hover,
.btn-cta-secondary:hover {
  background: #1a1a2e;
  color: #fff;
}

.service-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 22px;
}

.service-card {
  padding: 28px 22px;
  border: 1px solid #eceef4;
  border-radius: 8px;
  background: #fff;
  text-align: center;
  box-shadow: 0 10px 24px rgba(12, 18, 33, .07);
}

.service-icon {
  width: 58px;
  height: 58px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 16px;
  border-radius: 50%;
  background: #fff2f4;
  color: #c8102e;
  font-size: 23px;
}

.service-card h3 {
  margin: 0 0 9px;
  color: #1a1a2e;
  font-size: 18px;
  font-weight: 900;
}

.service-card p {
  margin: 0;
  color: #4b5563;
  font-size: 14px;
  line-height: 1.6;
}

.wedding-cta-section {
  padding: 64px 0;
  background: #191926;
  color: #fff;
  text-align: center;
}

.wedding-cta-section h2 {
  margin: 0 0 10px;
  font-size: 34px;
  font-weight: 900;
}

.wedding-cta-section p {
  max-width: 620px;
  margin: 0 auto 24px;
  color: rgba(255, 255, 255, .9);
  line-height: 1.7;
}

.cta-buttons {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 14px;
}

.wedding-cta-section .btn-cta-secondary {
  border-color: rgba(255, 255, 255, .9);
  background: transparent;
  color: #fff;
}

.wedding-cta-section .btn-cta-secondary:hover {
  background: #fff;
  color: #1a1a2e;
}

@media (max-width: 992px) {
  .hero-content h1 {
    font-size: 54px;
  }

  .wedding-cars-grid,
  .service-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 700px) {
  .wedding-hero-section {
    min-height: 460px;
  }

  .hero-content {
    padding: 62px 0;
    text-align: center;
  }

  .hero-content h1 {
    font-size: 38px;
  }

  .hero-description {
    margin-left: auto;
    margin-right: auto;
  }

  .hero-subtitle {
    font-size: 20px;
  }

  .wedding-cars-section,
  .wedding-services-section {
    padding: 44px 0;
  }

  .section-header h2,
  .wedding-cta-section h2 {
    font-size: 27px;
  }

  .wedding-cars-grid,
  .service-grid {
    grid-template-columns: 1fr;
  }

  .car-actions {
    grid-template-columns: 1fr;
  }

  .price-section {
    flex-wrap: wrap;
  }

  .cta-buttons {
    display: grid;
    grid-template-columns: 1fr;
  }
}

@media (max-width: 430px) {
  .wedding-hero-section {
    min-height: 430px;
  }

  .hero-content {
    padding: 52px 0;
  }

  .hero-kicker {
    font-size: 11px;
  }

  .hero-content h1 {
    font-size: 32px;
  }

  .hero-description {
    font-size: 14px;
  }

  .wedding-filter-btn {
    flex: 1 1 calc(50% - 8px);
    padding: 10px 12px;
  }

  .car-details {
    padding: 18px;
  }

  .car-details h3 {
    font-size: 18px;
  }

  .price-amount {
    font-size: 22px;
  }
}

@media (max-width: 340px) {
  .wedding-filter-btn {
    flex-basis: 100%;
  }
}
</style>
