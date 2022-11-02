import Vue from 'vue'
import Vuex from 'vuex';

import VuexPersistedStates from "vuex-persisted-states";
import {Base64} from "js-base64";

import auth from "./modules/auth";
import book from "./modules/book";

Vue.use(Vuex);

export default new Vuex.Store({

    modules: {
        auth,
        book,
    },

    plugins: [
        VuexPersistedStates({
            setState(value) {
                return Base64.encode(JSON.stringify(value));
            },
            getState(value) {
                return JSON.parse(Base64.decode(value));
            },
        }),
    ],
});
