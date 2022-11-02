import Vue from "vue";
import Bootstrap from "./bootstrap";
import VueRouter from "vue-router";
import VueAxios from "vue-axios";
import store from "./store";
import routes from "./routes";
import vuetify from "./vuetify";
import dayjs from "dayjs";
import Form from "vform";
import { HasError, AlertError } from "vform/src/components/bootstrap5";
import Swal from "sweetalert2";
import {VueEditor} from "vue2-editor";
import VueApp from "./views/App";
import {Setup, api} from "./services";
import middlewarePipeline from "./middlewares/middlewarePipeline";
import ProcessingOverlay from "./components/ProcessingOverlay";
import TruncateWord from "./components/TruncateWord";

//dayjs plugins
let advanceFormat = require("dayjs/plugin/advancedFormat");
let relativeTime = require("dayjs/plugin/relativeTime");
let utc = require("dayjs/plugin/utc");
let badMutable = require('dayjs/plugin/badMutable');
let customParseFormat = require("dayjs/plugin/customParseFormat");
dayjs.extend(advanceFormat);
dayjs.extend(relativeTime);
dayjs.extend(utc);
dayjs.extend(badMutable);
dayjs.extend(customParseFormat);

//modules
Vue.use(VueRouter, VueAxios, Bootstrap);

//components
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component('processing-overlay', ProcessingOverlay);
Vue.component('truncate-word', TruncateWord);
Vue.component('editor', VueEditor);

//item numbering
const ItemNumbering = (index, page, per_page) => {
    let number = index + 1;
    let multiplier = (page - 1) * per_page;
    return multiplier + number;
};

//defaults and global
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: toast => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    }
});
window.Swal = Swal;
window.Toast = Toast;

window.Form = Form;
window.Fire = new Vue();
window.dayjs = dayjs;
window.api = api;
window.ItemNumbering = ItemNumbering;

//axios setup
Setup();

//router
export const router = new VueRouter({ mode: "history", routes: routes });
router.beforeEach((to, from, next) => {
    if (!to.meta.middleware) {
        return next()
    }
    const middleware = to.meta.middleware;

    const context = {
        to,
        from,
        next,
        store
    };

    return middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1)
    })
});

//filters
Vue.filter("dashboardDateFormat", function(value) {
    if (value) {
        return dayjs(String(value)).format("Do MMM [,] YYYY");
    }
    return "Null";
});
Vue.filter("dateFromNow", function(value) {
    if (value) {

        if (dayjs(String(value)).year() < 2000) {
            const date = dayjs(String(value));
            date.set("year", dayjs(String(value)).year() + 2000);
            return date.fromNow();
        }

        return dayjs(String(value)).fromNow();
    }
    return "Null";
});
Vue.filter("dateWithDashes", function(value) {
    if (value) {
        return dayjs(String(value)).format("DD-MM-YYYY");
    }
    return "Null";
});
Vue.filter("dateWithSlashes", function(value) {
    if (value) {
        return dayjs(value).format("DD/MM/YYYY");
    }
    return "Null";
});
Vue.filter("fullDateTime", function(value) {
    if (value) {
        return dayjs(value).format("YYYY-M-DD HH:mm:ss");
    }
    return "Null";
});

new Vue({
    components: { VueApp },
    vuetify,
    router,
    store
}).$mount("#app");
