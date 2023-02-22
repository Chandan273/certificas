import { createApp } from "vue";
import App from "./App.vue";
import router from "./routes";
import vuetify from "../plugins/vuetify";
import { createI18n } from "vue-i18n";
import messages from "./lang";
import "devextreme/dist/css/dx.light.css";
import "../js/assets/css/global.scss";

export const i18n = createI18n({
    locale: "nl",
    fallbackLocale: "nl",
    messages,
});

const app = createApp(App);
app.use(router);
app.use(vuetify);
app.use(i18n);
app.mount("#app");
