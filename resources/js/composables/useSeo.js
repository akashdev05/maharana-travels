const SITE_NAME = 'Maharana Travels'
const serverCanonical = document.querySelector('link[rel="canonical"]')?.href
const SITE_URL = serverCanonical ? new URL(serverCanonical).origin : window.location.origin
const DEFAULT_IMAGE = '/images/logo-180.webp'
const DEFAULT_DESCRIPTION = 'Book reliable taxi service with Maharana Travels for Chandigarh Tricity, airport transfers and outstation travel.'

function absoluteUrl(path = '/') {
  if (/^https?:\/\//i.test(path)) return path
  return `${SITE_URL}${path.startsWith('/') ? path : `/${path}`}`
}

function setMeta(selector, attributes) {
  let tag = document.head.querySelector(selector)

  if (attributes.content === null) {
    tag?.remove()
    return
  }

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

  if (!href) {
    tag?.remove()
    return
  }

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
    '@id': absoluteUrl('/#business'),
    name: SITE_NAME,
    url: SITE_URL,
    image: absoluteUrl(DEFAULT_IMAGE),
    telephone: '+91 9416198045',
    email: 'maharanatravels0001@gmail.com',
    areaServed: ['Zirakpur', 'Chandigarh', 'Mohali', 'Panchkula', 'Ramgarh', 'Chandigarh Tricity', 'Delhi NCR', 'Himachal Pradesh', 'Uttarakhand'],
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

export function breadcrumbSchema(items = []) {
  if (!items.length) return null

  return {
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    itemListElement: items.map((item, index) => ({
      '@type': 'ListItem',
      position: index + 1,
      name: item.name,
      item: absoluteUrl(item.path),
    })),
  }
}

export function serviceSchema(service) {
  if (!service) return null

  return {
    '@context': 'https://schema.org',
    '@type': 'Service',
    name: service.title,
    serviceType: service.serviceType || 'Outstation taxi service',
    provider: { '@id': absoluteUrl('/#business') },
    areaServed: [service.from, service.to],
    description: `Book ${service.from} to ${service.to} taxi with Maharana Travels. One way and round trip cabs with verified drivers, clean vehicles and transparent fares.`,
  }
}

export function updateSeo(seo = {}) {
  const title = seo.title || `${SITE_NAME} | Cab Booking | Taxi Service`
  const description = seo.description || DEFAULT_DESCRIPTION
  const path = seo.path || window.location.pathname
  const canonical = seo.canonical === false ? null : absoluteUrl(path)
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
  const serverHasBusinessSchema = document.getElementById('server-jsonld')?.textContent.includes('TaxiService')
  setJsonLd('business-jsonld', seo.business === false || serverHasBusinessSchema ? null : businessSchema())
}
