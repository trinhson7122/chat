import "./bootstrap";

import { createApp } from "vue";
import router from "./router";
import store from "./store";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "../css/main.css";

import App from "./App.vue";
const app = createApp(App);

app.use(router).use(store).mount("#app");
