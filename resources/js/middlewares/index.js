import {Base64} from "js-base64";

const isAuthenticated = !! localStorage.getItem('_user');
const authUser = JSON.parse(Base64.decode(localStorage.getItem('_user'))) || {};
export const Auth = ({next}) => {
    if (!isAuthenticated) {
        return next({name: "logout"});
    }
    return next();
};

export const Guest = ({next}) => {
    if (isAuthenticated) {
        return next({path: `/${authUser.role.name}`});
    }else  {
        return next({name: 'logout'});
    }
    //return next();
};

export const Admin = ({next}) => {
    if (authUser.role_id !== 4) {
        return next({name: 'logout'});
    }
    return next();
};
export const Staff = ({next}) => {
    if (authUser.role_id !== 2) {
        return next({name: 'logout'});
    }
    return next();
};
export const Student = ({next}) => {
    if (authUser.role_id !== 1) {
        return next({name: 'logout'});
    }
    return next();
};
export const Parent = ({next}) => {
    if (authUser.role_id !== 3) {
        return next({name: 'logout'});
    }
    return next();
};
