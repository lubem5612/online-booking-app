import ErrorPage from "./components/ErrorPage";
import MasterLayout from "./layouts/MasterLayout";
import AuthLayout from "./layouts/AuthLayout";
import IndexPage from "./views/index";
import logout from "./views/logout";
import register from "./views/register";
import BookList from "./views/librarian/BookList";
import ReaderBookList from "./views/reader/ReaderBookList";
import ReaderCheckOuts from "./views/reader/ReaderCheckOuts";

export default [
    {
        path: '/librarian',
        component: MasterLayout,
        children: [
            {
                path: '',
                name: 'librarian_books',
                component: BookList,
                meta: [],
            }

        ]
    },
    {
        path: '/reader',
        component: MasterLayout,
        children: [
            {
                path: '',
                name: 'reader_books',
                component: ReaderBookList,
                meta: [],
            }, {
                path: 'checkouts',
                name: 'reader_checkout',
                component: ReaderCheckOuts,
                meta: [],
            },
        ]
    },
    {
        path: '/',
        component: AuthLayout,
        children: [
            {
                path: '',
                component: IndexPage,
                name: 'login',
            },
            {
                path: '/register',
                component: register,
                name: 'register',
            },
        ]
    },
    {
        path: "*",
        name: 'error_page',
        component: ErrorPage,
    },
    {
        path: '/logout',
        name: 'logout',
        component: logout,
    }
]
