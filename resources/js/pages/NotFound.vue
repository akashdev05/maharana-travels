<template>
  <div class="notfound-wrap">
    <div class="notfound-card">
      <div class="notfound-code">404</div>
      <h2>Page Not Found</h2>
      <p>The page you're looking for doesn't exist or has been moved.</p>
      <RouterLink to="/" class="btn-book">
        <i class="fas fa-home"></i> Back to Home
      </RouterLink>
    </div>
  </div>
</template>

<style scoped>
.notfound-wrap { min-height:70vh; display:flex; align-items:center; justify-content:center; background:#f8f9fa; padding:24px 12px; }
.notfound-card { text-align:center; padding:60px 40px; max-width:520px; }
.notfound-code { font-size:100px; font-weight:900; color:#c8102e; line-height:1; }
h2 { color:#1a1a2e; font-size:28px; font-weight:700; margin:16px 0 10px; }
p  { color:#4b5563; font-size:16px; margin-bottom:30px; }
@media(max-width:480px){
  .notfound-card{ padding:38px 14px; }
  .notfound-code{ font-size:72px; }
  h2{ font-size:23px; }
  p{ font-size:14px; line-height:1.6; }
}
</style>
