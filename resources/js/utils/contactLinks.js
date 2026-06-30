export const CONTACT_PHONE = '+91 9416198045'
export const CONTACT_PHONE_RAW = '919416198045'

export function phoneLink() {
  return `tel:+${CONTACT_PHONE_RAW}`
}

export function whatsappLink(message = '') {
  const query = message ? `?text=${encodeURIComponent(message)}` : ''

  return `https://wa.me/${CONTACT_PHONE_RAW}${query}`
}

export function openWhatsApp(message) {
  window.open(whatsappLink(message), '_blank', 'noopener')
}
