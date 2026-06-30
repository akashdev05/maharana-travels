const SITE_NAME = 'Maharana Travels'
const SITE_URL = window.location.origin
const DEFAULT_IMAGE = '/images/logo-180.webp'
const DEFAULT_DESCRIPTION = 'Book reliable taxi service with Maharana Travels for one way cabs, round trips, airport transfers and outstation taxi across North India.'

function absoluteUrl(path = '/') {
  if (/^https?:\/\//i.test(path)) return path
  return `${SITE_URL}${path.startsWith('/') ? path : `/${path}`}`
}

function setMeta(selector, attributes) {
  let tag = document.head.querySelector(selector)

  if (!tag) {
    tag = document.createElement('meta')
    document.head.appendChild(tag)
  }

  Object.entries(attributes).forEach(([key, value]) => {
    if (value !== undefined && value !== null) tag.setAttribute(key, String(value))
  })
}

function setLink(rel, href) {
  let tag = document.head.querySelector(`link[rel="${rel}"]`)

  if (!tag) {
    tag = document.createElement('link')
    tag.setAttribute('rel', rel)
    document.head.appendChild(tag)
  }

  tag.setAttribute('href', href)
}

function setJsonLd(id, data) {
  let tag = document.getElementById(id)

  if (!data) {
    tag?.remove()
    return
  }

  if (!tag) {
    tag = document.createElement('script')
    tag.id = id
    tag.type = 'application/ld+json'
    document.head.appendChild(tag)
  }

  tag.textContent = JSON.stringify(data)
}

export function businessSchema() {
  return {
    '@context': 'https://schema.org',
    '@type': 'TaxiService',
    name: SITE_NAME,
    url: SITE_URL,
    image: absoluteUrl(DEFAULT_IMAGE),
    telephone: '+91 9416198045',
    email: 'maharanatravels0001@gmail.com',
    areaServed: ['Punjab', 'Delhi NCR', 'Himachal Pradesh', 'Uttarakhand', 'North India'],
    address: {
      '@type': 'PostalAddress',
      streetAddress: 'Shop No 7, Juneja Square, Highland Marg',
      addressLocality: 'Zirakpur',
      addressRegion: 'Punjab',
      postalCode: '140603',
      addressCountry: 'IN',
    },
  }
}

export function faqSchema(faqs = []) {
  if (!faqs.length) return null

  return {
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    mainEntity: faqs.map((faq) => ({
      '@type': 'Question',
      name: faq.q,
      acceptedAnswer: {
        '@type': 'Answer',
        text: faq.a,
      },
    })),
  }
}

export function serviceSchema(service) {
  if (!service) return null

  return {
    '@context': 'https://schema.org',
    '@type': 'Service',
    name: service.title,
    serviceType: 'Outstation taxi service',
    provider: businessSchema(),
    areaServed: [service.from, service.to],
    description: `Book ${service.from} to ${service.to} taxi with Maharana Travels. One way and round trip cabs with verified drivers, clean vehicles and transparent fares.`,
  }
}

export function updateSeo(seo = {}) {
  const title = seo.title || `${SITE_NAME} | Cab Booking | Taxi Service`
  const description = seo.description || DEFAULT_DESCRIPTION
  const path = seo.path || window.location.pathname
  const canonical = absoluteUrl(path)
  const image = absoluteUrl(seo.image || DEFAULT_IMAGE)
  const type = seo.type || 'website'

  document.title = title

  setMeta('meta[name="description"]', { name: 'description', content: description })
  setMeta('meta[name="robots"]', { name: 'robots', content: seo.robots || 'index, follow' })
  setMeta('meta[property="og:title"]', { property: 'og:title', content: title })
  setMeta('meta[property="og:description"]', { property: 'og:description', content: description })
  setMeta('meta[property="og:type"]', { property: 'og:type', content: type })
  setMeta('meta[property="og:url"]', { property: 'og:url', content: canonical })
  setMeta('meta[property="og:image"]', { property: 'og:image', content: image })
  setMeta('meta[property="og:site_name"]', { property: 'og:site_name', content: SITE_NAME })
  setMeta('meta[name="twitter:card"]', { name: 'twitter:card', content: 'summary_large_image' })
  setMeta('meta[name="twitter:title"]', { name: 'twitter:title', content: title })
  setMeta('meta[name="twitter:description"]', { name: 'twitter:description', content: description })
  setMeta('meta[name="twitter:image"]', { name: 'twitter:image', content: image })
  setLink('canonical', canonical)

  setJsonLd('route-jsonld', seo.schema || null)
  setJsonLd('business-jsonld', seo.business === false ? null : businessSchema())
}
