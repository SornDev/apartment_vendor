import { defineStore } from "pinia"; 
export const useStore = defineStore('auth',{
    state: ()=>({
        token: 'null',
        user: null,
        permissions:null,
        setting:null
    }),
    getters:{
        get_token:(state)=> state.token,
        get_user: (state)=> state.user,
        get_permissions: (state)=> state.permissions,
        get_setting: (state)=> state.setting
    },
    actions:{
        set_token(new_token){
            this.token = new_token
        },
        set_user(new_user){
            this.user = new_user
        },
        set_permissions(new_permissions){
            this.permissions = new_permissions
        },
        set_setting(new_setting){
            this.setting = new_setting
        },
        remove_token(){
            this.token = null
        },
        remove_user(){
            this.user = null
        },
        remove_permissions(){
            this.permissions = null
        },
        remove_setting(){
            this.setting = null
        }
    }
})