import { createApp } from 'vue'

import App from './App.vue'
import router from './router'
import { setupRouterGuard } from './router/guard'
import { pinia } from './stores'
import './assets/styles.css';

const app = createApp(App)

app.use(pinia)
setupRouterGuard(router)
app.use(router)

app.mount('#app')
