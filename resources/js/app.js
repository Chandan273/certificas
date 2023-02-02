import { createApp } from 'vue'
// import Vue from 'vue';
import App from './App.vue'
import router from "./routes";
import 'devextreme/dist/css/dx.light.css';

import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
  components,
  directives,
})

createApp(App).use(router).use(vuetify).mount('#app');
