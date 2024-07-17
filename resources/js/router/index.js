import { createWebHistory, createRouter } from "vue-router";

// import Home from '../Pages/Home.vue';
// import Store from '../Pages/Store.vue';
// import Pos from '../Pages/Pos.vue';
// import Transection from '../Pages/Transection.vue';
// import Report from '../Pages/Report.vue';
// import NoPage from '../Pages/No_page.vue';
// import Login from '../Pages/Login.vue';
// import Register from '../Pages/Register.vue';

 import Login from '../Pages/Login.vue';
 import DashBoard from '../Pages/Dashboard.vue'

import { useStore } from "../Store/auth";

const authMiddleware = (to, from, next) =>{
    // const token = localStorage.getItem('web_token');
    // const user = localStorage.getItem('web_user');
    // const user_permissions = localStorage.getItem('web_permission');
    // const user_setting = localStorage.getItem('web_setting');
    // const store = useStore();

    // if(token){

    //     // ຖ້າມີ token ໃນ localStorage
    //     // ຂຽນຂໍ້ມູນ token ແລະ user ເຂົ້າໄປໃນ pinia
    //     // console.log(user_permissions)
    //     store.set_token(token);
    //     store.set_user(user);
    //     store.set_permissions(user_permissions);
    //     store.set_setting(user_setting);
    //     next();
    // } else {
    //     // ຖ້າບໍ່ມີ token ໃນ localStorage
    //     next({
    //         path: '/login',
    //         replace: true
    //     })
    // }
}

export const routes = [
    {
        path: '/',
        redirect: '/dashboard'
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: DashBoard,
    },
    {
        name: 'roomall',
        path: '/roomall',
        component: ()=>import('../Pages/RoomAll.vue'),
    },
    {
        name: 'acc',
        path: '/acc',
        component: ()=>import('../Pages/Acc.vue'),
    },
    {
        name: 'customer',
        path: '/customer',
        component: ()=>import('../Pages/Customer.vue'),
    },
    {
        name: 'bill',
        path: '/bill',
        component: ()=>import('../Pages/Bill.vue'),
    },
    {
        name: 'service',
        path: '/service',
        component: ()=>import('../Pages/Service.vue'),
    },

    {
        name: 'setting',
        path: '/setting',
        component: ()=>import('../Pages/Setting.vue'),
    },
    {
        name: 'user-mg',
        path: '/user-mg',
        component: ()=>import('../Pages/UserMg.vue'),
    },
    {
        name: 'user-per',
        path: '/user-per',
        component: ()=>import('../Pages/UserPer.vue'),
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
        // component: () => import('../Pages/Login.vue'),
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

// router.beforeEach((to,from,next)=>{
//     const token = localStorage.getItem('web_token');
//     if(to.meta.middleware){
//         to.meta.middleware.forEach(middleware=>middleware(to, from, next))
//     } else {
//         if(to.path == '/login' || to.path == '/' ){
//             if(token){
//                 next({
//                     path:'/dashboard',
//                     replace: true
//                 })
//             } else {
//                 next();
//             }
//         } else {
//             next();
//         }
//     }
// })

export default router;