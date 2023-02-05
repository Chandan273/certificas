import { createApp } from 'vue'
// import Vue from 'vue';
import App from './App.vue'
import router from "./routes";
import 'devextreme/dist/css/dx.light.css';
import vuetify from '../plugins/vuetify'
import vueCountryRegionSelect from 'vue3-country-region-select'

const app = createApp(App);
app.use(router);
app.use(vuetify);
app.use(vueCountryRegionSelect);
app.mount('#app');
