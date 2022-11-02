import {api} from "../../services";
import { router } from "../../app";

const state = {
    book: {},
    books: [],
    bookCount: 0,

    booking: {},
    bookings: [],
    bookingCount: 0,

    errors: {},
    isError: false,
    loading: false,
};

const mutations = {
    saveBooks: (state, payload) => {
        state.books = payload.data;
        state.bookCount = Math.ceil(payload.total / payload.per_page);
    },
    saveBook: (state, payload) => state.book = payload,
    setLoading: (state, payload) => state.loading = payload,
    saveError: (state, payload) => state.errors = payload,
    setError: (state, payload) => state.isError = payload,
};

const getters = {
    books: state => state.books,
    book: state => state.book,
    bookCount: state => state.bookCount,
    loading: state => state.loading,


};

const actions = {
    GET_BOOKS: ({commit, state}, {search='', status='', date='', page=1, limit=10}) => {
        commit('setLoading', true);
        commit('setError', false);
        api.get(`/api/books?search=${search}&status=${status}&date=${date}&page=${page}&limit=${limit}`)
            .then(res => {
                commit('saveBooks', res.data);
            }).catch(err => {
            commit('saveError', error.errors);
            commit('setError', true);
            Toast.fire({icon: "error", title: err.message});
        }).finally(() => {
            if (state.isError == false) {
                commit('setLoading', false);
            }
        });
    },
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
