import { createApp } from 'vue'
// import Vue from 'vue';
import App from './App.vue'
import router from "./routes";
import 'devextreme/dist/css/dx.light.css';
import vuetify from '../plugins/vuetify'

createApp(App).use(router).use(vuetify).mount('#app');
