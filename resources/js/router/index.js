import { createWebHistory, createRouter } from "vue-router";

// import Home from '../Pages/Home.vue';
// import Store from '../Pages/Store.vue';
// import Pos from '../Pages/Pos.vue';
// import Transection from '../Pages/Transection.vue';
// import Report from '../Pages/Report.vue';
// import NoPage from '../Pages/No_page.vue';
// import Login from '../Pages/Login.vue';
// import Register from '../Pages/Register.vue';

import { useStore } from "../Store/auth";

const authMiddleware = (to, from, next) =>{
    const token = localStorage.getItem('web_token');
    const user = localStorage.getItem('web_user');
    const user_permissions = localStorage.getItem('web_permission');
    const user_setting = localStorage.getItem('web_setting');
    const store = useStore();

    if(token){

        // ຖ້າມີ token ໃນ localStorage
        // ຂຽນຂໍ້ມູນ token ແລະ user ເຂົ້າໄປໃນ pinia
        // console.log(user_permissions)
        store.set_token(token);
        store.set_user(user);
        store.set_permissions(user_permissions);
        store.set_setting(user_setting);
        next();
    } else {
        // ຖ້າບໍ່ມີ token ໃນ localStorage
        next({
            path: '/login',
            replace: true
        })
    }
}

export const routes = [
    {
        path: '/',
        redirect: '/dashboard'
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: () => import('../Pages/Dashboard.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'login',
        path: '/login',
        component: () => import('../Pages/Login.vue'),
    },
    {
        name: 'register',
        path: '/register',
        component: () => import('../Pages/Register.vue'),
    },
    {
        name: 'pre-doc',
        path: '/pre-doc',
        component: () => import('../Pages/PreDoc.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'tran-doc',
        path: '/tran-doc',
        component: () => import('../Pages/TranDoc.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'rec',
        path: '/rec',
        component: () => import('../Pages/Rec.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'quo',
        path: '/quo',
        component: () => import('../Pages/Quo.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'acc',
        path: '/acc',
        component: () => import('../Pages/Acc.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'cus',
        path: '/cus',
        component: () => import('../Pages/Cus.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'setting',
        path: '/setting',
        component: () => import('../Pages/Setting.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'predoc-mg',
        path: '/predoc-mg',
        component: () => import('../Pages/PreDocMg.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'user-mg',
        path: '/user-mg',
        component: () => import('../Pages/UserMg.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'user-per',
        path: '/user-per',
        component: () => import('../Pages/UserPer.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'inc-exp',
        path: '/inc-exp',
        component: () => import('../Pages/ReIncExp.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'rev',
        path: '/rev',
        component: () => import('../Pages/ReRev.vue'),
        meta: {
            middleware: [authMiddleware]
        }
    },
    {
        name: 'no_page',
        path: '/:pathMacth(.*)*',
        component: () => import('../Pages/No_page.vue'),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    scrollBehavior(){
        window.scrollTo(0,0)
    }
});

router.beforeEach((to,from,next)=>{
    const token = localStorage.getItem('web_token');
    if(to.meta.middleware){
        to.meta.middleware.forEach(middleware=>middleware(to, from, next))
    } else {
        if(to.path == '/login' || to.path == '/' ){
            if(token){
                next({
                    path:'/dashboard',
                    replace: true
                })
            } else {
                next();
            }
        } else {
            next();
        }
    }
})

export default router;