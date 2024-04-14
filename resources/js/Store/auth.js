import { defineStore } from "pinia"; 
export const useStore = defineStore('auth',{
    state: ()=>({
        token: null,
        user: null,
        permissions:[],
    }),
    getters:{
        get_token:(state)=> state.token,
        get_user: (state)=> state.user,
        get_permissions: (state)=> state.permissions
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
        remove_token(){
            this.token = null
        },
        remove_user(){
            this.user = null
        },
        remove_permissions(){
            this.permissions = []
        }
    }
})