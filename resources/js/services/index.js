import axios from "axios";
import store from "../store";
import {router} from "../app";

/**
 * Create Axios Instances and Interceptors
 *
 * @type {AxiosInstance}
 */
let api = axios.create({});

api.interceptors.response.use(function (response) {
    return response.data;
}, function (error) {
    if (error.response.status === 419 || error.response.status === 401) {
        Toast.fire({icon: "info", title: "Session expired! Logging out"});
        router.push({name: 'login'}).then(res => {});
    }else if (error.response.data){
        return Promise.reject({...error.response.data, statusCode: error.response.status});
    }else if(error.request) {
        return Promise.reject({...error.request, statusCode: error.response.status});
    }else {
        return Promise.reject(error);
    }
});

let Setup = () => {
    api.interceptors.request.use(function(config) {
        const token = store.state.auth.token;
        if(token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    }, function(err) {
        return Promise.reject(err);
    });
};

/**
 * Export Axios Instance, and Setup object
 *
 * @type {Map<string, any>}
 */

export {
    api, Setup
}
