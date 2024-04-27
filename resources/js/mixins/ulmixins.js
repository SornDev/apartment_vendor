
import moment from 'moment'
import axios from 'axios'
import { useStore } from '../Store/auth'

export default{
    data(){
        return{
            baseURL: window.location.origin,
            PerPage: 10,
            Sort:'asc',
            Search:'',
            EditID:'',
            FormType:true,
            loading_post:false,
            loading_table:false,
            options: {
                // prefix: '$ ',
                numeral: true,
                numeralPositiveOnly: true,
                noImmediatePrefix: true,
                rawValueTrimPrefix: true,
                numeralIntegerScale: 10,
                numeralDecimalScale: 2,
                numeralDecimalMark: ",",
                delimiter: ".",
              },
              options_number: {
                // prefix: '$ ',
                numeral: true,
                numeralPositiveOnly: true,
                noImmediatePrefix: true,
                rawValueTrimPrefix: true,
                numeralIntegerScale: 10,
                numeralDecimalScale: 2,
                numeralDecimalMark: "",
                delimiter: "",
              },
            //   polling:null,
        }
    },
    created() {
        const store = useStore()
        if(store.get_token){
            axios.defaults.headers.common['Authorization'] = 'Bearer '+store.get_token
            // axios post content type multipart/form-data
            axios.defaults.headers.post['Content-Type'] = 'multipart/form-data'  
        }
    },
    components: {
        moment, axios
    },
    methods: {
        ChangSort(){
            if(this.Sort == 'asc'){
                this.Sort = 'desc'
            }else{
                this.Sort = 'asc'
            }
        },
        formatPrice(value) {
            let val = (value/1).toFixed(0).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        date(value){
            return moment(value).format('DD/MM/YYYY')
        },
        async openLink(link) {
            const store = useStore();
            const response = await fetch(`${link}`, { headers:{ Authorization: 'Bearer '+ store.get_token}});
      
            const html = await response.text();
            const blob = new Blob([html], { type: 'text/html' });
            const blobUrl = URL.createObjectURL(blob);
            window.open(blobUrl, '_blank');
      
          },
          async AddData(url,data,result,noti){
                if(data){
                    this.loading_post = true
                    try{
                    const response = await axios.post(`${this.baseURL}/api/${url}`, data);
                    // if error auth by status code 401
                
                    if(response.data.success){
                        this.loading_post = false
                        result(response.data)
                        if(noti!='no'){
                        this.$swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500,
                          });
                        }
                    }else{
                        this.loading_post = false
                        result(response.data)
                        this.$swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "error",
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 3500,
                          });
                    }


                } catch (error) {
                    console.log(error)
                    if(error.response.status == 401){
                        // remove all data pinia and localstorage
                        const store = useStore()
                        store.remove_token()
                        store.remove_user()
                        store.remove_permissions()
                        store.remove_setting()
                        localStorage.removeItem('token')
                        localStorage.removeItem('user')
                        localStorage.removeItem('permissions')
                        localStorage.removeItem('setting')
                        // redirect to login
                        this.$router.push({name:'login'})
                    }
                }


                } else {
                    this.loading_post = true
                    try{
                    const response = await axios.post(`${this.baseURL}/api/${url}`, {});
                    
                    if(response.data.success){
                        this.loading_post = false
                        result(response.data)
                        this.$swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500,
                          });
                    }else{
                        this.loading_post = false
                        result(response.data)
                        this.$swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "error",
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 3500,
                          });
                    }
                } catch (error) {
                    if(error.response.status == 401){
                        // remove all data pinia and localstorage
                        const store = useStore()
                        store.remove_token()
                        store.remove_user()
                        store.remove_permissions()
                        store.remove_setting()
                        localStorage.removeItem('token')
                        localStorage.removeItem('user')
                        localStorage.removeItem('permissions')
                        localStorage.removeItem('setting')
                        // redirect to login
                        this.$router.push({name:'login'})
                    }
                }
                }
          },
          async EditData(url,id,result){
                // this.loading_post = true
                this.EditID = id
                try{
                const response = await axios.get(`${this.baseURL}/api/${url}/${id}`);
                    
                 
                if(response.data.success==false){
                    this.$swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "error",
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 3500,
                  });
                }

                result(response.data)

                } catch (error) {
                if(error.response.status == 401){
                    // remove all data pinia and localstorage
                    const store = useStore()
                    store.remove_token()
                    store.remove_user()
                    store.remove_permissions()
                    store.remove_setting()
                    localStorage.removeItem('token')
                    localStorage.removeItem('user')
                    localStorage.removeItem('permissions')
                    localStorage.removeItem('setting')
                    // redirect to login
                    this.$router.push({name:'login'})
                }
            }
               
                    
               

          },
          async CopyData(url,id,result){
            // this.loading_post = true
            try{
            const response = await axios.get(`${this.baseURL}/api/${url}/${id}`);
        
        result(response.data)
        } catch (error) {
            if(error.response.status == 401){
                // remove all data pinia and localstorage
                const store = useStore()
                store.remove_token()
                store.remove_user()
                store.remove_permissions()
                store.remove_setting()
                localStorage.removeItem('token')
                localStorage.removeItem('user')
                localStorage.removeItem('permissions')
                localStorage.removeItem('setting')
                // redirect to login
                this.$router.push({name:'login'})
            }
        }
            // if(response.data.success){
                // this.loading_post = false
                
            // }else{
            //     // this.loading_post = false
            //     result(response.data)
            //     this.$swal.fire({
            //         toast: true,
            //         position: "top-end",
            //         icon: "error",
            //         title: response.data.message,
            //         showConfirmButton: false,
            //         timer: 3500,
            //       });
            // }

          },
          async UpdateData(url,data,result){
                if(data){
                    this.loading_post = true
                    try{
                    const response = await axios.post(`${this.baseURL}/api/${url}`, data);
                    // console.log(response)
                    
                

                    if(response.data.success){
                        this.loading_post = false
                        result(response.data)
                        this.$swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "success",
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500,
                          });
                    }else{
                        this.loading_post = false
                        result(response.data)
                        this.$swal.fire({
                            toast: true,
                            position: "top-end",
                            icon: "error",
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 3500,
                          });
                    }
                } catch (error) {
                    // console.log(error)
                    if(error.response.status == 401){
                        // remove all data pinia and localstorage
                        const store = useStore()
                        store.remove_token()
                        store.remove_user()
                        store.remove_permissions()
                        store.remove_setting()
                        localStorage.removeItem('token')
                        localStorage.removeItem('user')
                        localStorage.removeItem('permissions')
                        localStorage.removeItem('setting')
                        // redirect to login
                        this.$router.push({name:'login'})
                    }
                }
                } else {
                    this.loading_post = false
                    result({status:'error',message:'data not found'})
                }
          },
          async DeleteData(url,id,result,msg){


            this.$swal.fire({
                title: 'ທ່ານແນ່ໃຈບໍ່?',
                text: msg?msg:"ທີ່ຈະລຶບຂໍ້ມູນນີ້!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ລຶບເລີຍ!',
                cancelButtonText: 'ຍົກເລີກ'
                }).then( async (res) =>  {
                    if (res.isConfirmed)  {
                        
                        this.loading_post = true
                        try{
                        const response = await axios.delete(`${this.baseURL}/api/${url}/${id}`);
                       
                        
                        if(response.data.success){
                            this.loading_post = false
                            result(response.data)
                            this.$swal.fire({
                                toast: true,
                                position: "top-end",
                                icon: "success",
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 1500,
                              });
                        }else{
                            this.loading_post = false
                            result(response.data)
                            this.$swal.fire({
                                toast: true,
                                position: "top-end",
                                icon: "error",
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 3500,
                              });
                        }

                    } catch (error) {
                        if(error.response.status == 401){
                            // remove all data pinia and localstorage
                            const store = useStore()
                            store.remove_token()
                            store.remove_user()
                            store.remove_permissions()
                            store.remove_setting()
                            localStorage.removeItem('token')
                            localStorage.removeItem('user')
                            localStorage.removeItem('permissions')
                            localStorage.removeItem('setting')
                            // redirect to login
                            this.$router.push({name:'login'})
                        }
                    }
                    }
                })


               
          },
          async GetData(url,result){
                this.loading_table = true
                try {
                const response = await axios.get(`${this.baseURL}/api/${url}`);
                this.loading_table = false
                

                if(response.data.success==false){
                    this.$swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "error",
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 3500,
                  });
                }

                result(response.data)

            } catch (error) {
                
                if(error.response.status == 401){
                    
                    // remove all data pinia and localstorage
                    const store = useStore()
                    store.remove_token()
                    store.remove_user()
                    store.remove_permissions()
                    store.remove_setting()
                    localStorage.removeItem('token')
                    localStorage.removeItem('user')
                    localStorage.removeItem('permissions')
                    localStorage.removeItem('setting')
                    // redirect to login
                    this.$router.push({name:'login'})
                }
            }
                // console.log(response)
                  
          },
          async GetDataPost(url,data,result){
            this.loading_table = true
            try {
                const response = await axios.post(`${this.baseURL}/api/${url}`, data);
                this.loading_table = false
                result(response.data)

                if(response.data.success==false){
                    this.$swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "error",
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 3500,
                  });
                }
                
                
            } catch (error) {
                if(error.response.status == 401){
                    // remove all data pinia and localstorage
                    const store = useStore()
                    store.remove_token()
                    store.remove_user()
                    store.remove_permissions()
                    store.remove_setting()
                    localStorage.removeItem('token')
                    localStorage.removeItem('user')
                    localStorage.removeItem('permissions')
                    localStorage.removeItem('setting')
                    // redirect to login
                    this.$router.push({name:'login'})
                }
            }
            
               
      },
    }
}