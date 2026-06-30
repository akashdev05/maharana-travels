// resources/js/composables/useWatermark.js
/**
 * Draws a car photo onto a <canvas>, covers the bottom strip
 * (where old branding text lives) and stamps "MAHARANA TRAVELS".
 */
export function useWatermark() {
  function stamp(canvas, src) {
    if (!canvas) return
    const ctx = canvas.getContext('2d')
    const W   = canvas.width
    const H   = canvas.height

    // Placeholder while loading
    ctx.fillStyle = '#f0f0f0'
    ctx.fillRect(0, 0, W, H)

    const img       = new Image()
    img.crossOrigin = 'anonymous'

    img.onload = () => {
      // 1. Draw the full original car photo
      ctx.drawImage(img, 0, 0, W, H)

      // 2. Cover bottom area where the old branding is baked into the image
      const coverH = Math.round(H * 0.24)
      const coverY = H - coverH

      // Sample background colour from that zone
      let bgColor = '#ffffff'
      try {
        const px = ctx.getImageData(W / 2, coverY + 4, 1, 1).data
        bgColor   = `rgb(${px[0]},${px[1]},${px[2]})`
      } catch {
        bgColor = '#f5f5f5'   // cross-origin fallback
      }

      ctx.fillStyle = bgColor
      ctx.fillRect(0, coverY, W, coverH)

      // 3. Build a clean branded footer strip
      const footerGradient = ctx.createLinearGradient(0, coverY, W, H)
      footerGradient.addColorStop(0, 'rgba(255,255,255,0.98)')
      footerGradient.addColorStop(1, bgColor)
      ctx.fillStyle = footerGradient
      ctx.fillRect(0, coverY, W, coverH)

      ctx.fillStyle = '#c8102e'
      ctx.fillRect(0, coverY, W, 4)

      // 4. Maharana Travels branding
      const midY    = coverY + coverH / 2 + 1
      const iconSz  = Math.round(coverH * 0.34)
      const textSz  = Math.round(coverH * 0.33)
      const tagSz   = Math.round(coverH * 0.18)
      const iconX   = Math.round(W * 0.05)

      ctx.font         = `${iconSz}px serif`
      ctx.textAlign    = 'left'
      ctx.textBaseline = 'middle'
      ctx.fillText('🚖', iconX, midY - Math.round(coverH * 0.08))

      ctx.font      = `bold ${textSz}px "Segoe UI", Tahoma, sans-serif`
      ctx.fillStyle = '#1a1a2e'
      const textX   = iconX + Math.round(iconSz * 1.1)
      ctx.fillText('MAHARANA TRAVELS', textX, midY - Math.round(coverH * 0.08))

      ctx.font      = `${tagSz}px "Segoe UI", Tahoma, sans-serif`
      ctx.fillStyle = '#c8102e'
      ctx.fillText('Ride with Pride, Travel with Trust', textX, midY + Math.round(coverH * 0.18))
    }

    img.onerror = () => {
      const grad = ctx.createLinearGradient(0, 0, W, H)
      grad.addColorStop(0, '#1a1a2e')
      grad.addColorStop(1, '#0f3460')
      ctx.fillStyle = grad
      ctx.fillRect(0, 0, W, H)
      ctx.font         = `${Math.round(H * 0.16)}px serif`
      ctx.textAlign    = 'center'
      ctx.textBaseline = 'middle'
      ctx.fillStyle    = '#f5a623'
      ctx.fillText('🚖', W / 2, H * 0.4)
      ctx.font      = `bold ${Math.round(H * 0.11)}px "Segoe UI", sans-serif`
      ctx.fillStyle = '#f5a623'
      ctx.fillText('MAHARANA TRAVELS', W / 2, H * 0.68)
    }

    img.src = src
  }

  return { stamp }
}
