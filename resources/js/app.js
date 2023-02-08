import { createApp } from "vue";
import App from "./App.vue";
import router from "./routes";
import vuetify from "../plugins/vuetify";
import "devextreme/dist/css/dx.light.css";

const app = createApp(App);
app.use(router);
app.use(vuetify);
app.mount("#app");
