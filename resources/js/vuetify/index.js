import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify);

const opts = {
    theme: {
        themes: {
            light: {
                white: '#FFFFFF',
                primary: '#263238',
                secondary: '#01579B',
                accent: '#3E2723',
                error: '#F44336',
                info: '#283593',
                success: '#8BC34A',
                warning: '#FF9800',
            },
        },
    },
};

export default new Vuetify(opts)
