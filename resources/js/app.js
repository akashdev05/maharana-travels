// resources/js/app.js
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'

// Global styles
import '../css/app.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.mount('#app')
