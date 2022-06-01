require("./bootstrap");

import { createApp } from "vue";
import DashboardPayments from "./components/DashboardPayments.vue";

const app = createApp({});
app.component("dashboard-payments", DashboardPayments).mount("#app");
