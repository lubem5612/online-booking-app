import {api} from "../../services";
import { router } from "../../app";

const state = {
    token: null,
    user: {},
    errors: {},
    isLoggedIn: false,
    loading: false,
};

const mutations = {
    logOutUser: (state) => {
        sessionStorage.clear();
        localStorage.clear();
        state.user = {};
        state.isLoggedIn = false
    },
    storeToken: (state, payload) => state.token = payload,
    storeUser: (state, payload) => state.user = payload,
    storeErrors: (state, payload) => state.errors = payload,

    setLoading: (state, payload) => state.loading = payload,
};

const getters = {
    token: state => state.token,
    user: state => state.user,
    isLoggedIn: state => state.isLoggedIn,
    loading: state => state.loading,
};

const actions = {
    REGISTER_USER: async ({commit}, data) => {
        commit('setLoading', true);
        try {
            const res = await api.post(`/api/register`, data);
            commit('storeUser', res.data);
        }catch (error) {
            if (error.errorCode === 422) {
                commit('storeErrors', error.errors);
            }
        }
        commit('setLoading', false);
    },
    LOGIN_USER: async ({commit}, data) => {
        commit('setLoading', true);
        try {
            const res = await api.post(`/api/login`, data);
            commit('storeToken', res.data);
            api.defaults.headers.common['authorization'] = `Bearer ${res.data}`;
            let user = await api.get(`/api/user`);
            commit('storeUser', user.data);
            router.push(`/${user.data.role}`).then(res => {})
        }catch (error) {
            if (error.errorCode === 422) {
                commit('storeErrors', error.errors);
            }
        }
        commit('setLoading', false);
    },
    LOGOUT_USER: ({commit}) => {
        api.post(`/api/logout`, {}).then(res => {
            commit('logOutUser');
            router.push({name: "login"}).then((res) => {});
        }).catch(error => {
            if (error.statusCode === 419 || error.statusCode === 401) {
                Toast.fire({icon: "info", title: "session expired, logging out"});
                commit('logOutUser');
                router.push({name: 'login'}).then((res) => {});
            }
            commit('storeErrors', error.errors);
        });
    },
    GET_USER: async ({commit}) => {
        try{
            const res = await api.get('/api/user');
            commit('storeUser', res.data);
        }catch (error) {
            commit('storeErrors', error.errors);
        }
    },
};


export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
