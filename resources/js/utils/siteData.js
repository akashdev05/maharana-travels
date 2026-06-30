let cachedSiteData = null

export function getInitialSiteData() {
  if (cachedSiteData) return cachedSiteData

  const node = document.getElementById('initial-site-data')
  if (!node?.textContent) return null

  try {
    cachedSiteData = JSON.parse(node.textContent)
    return cachedSiteData
  } catch {
    return null
  }
}
