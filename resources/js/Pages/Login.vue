<template>
    <div class=" d-flex justify-content-center">

    
    <div class="authentication-wrapper authentication-basic container-p-y col-md-4">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
        <div class="card-body">

          <!-- <h4 class="mb-2">ສະບາຍດີ! 👋</h4>
          <p class="mb-4">ຍິນດີຕ້ອນຮັບ, ກະລຸນາເຂົ້າສູ່ລະບົບ</p> -->
          <div class="text-center">
            <img :src="url+'/assets/img/dms_logo.jpg'" width="40%" alt="" srcset="">
          </div>
          
          
            <div class="mb-3">
              <label for="email" class="form-label fs-6">ຊື່ຜູ້ໃຊ້:</label>
              <input type="text"  class="form-control" v-model="user_name" id="email" placeholder="...." >
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label fs-6" for="password">ລະຫັດຜ່ານ:</label>
                
              </div>
              <div class="input-group input-group-merge">
                <input :type="show_pass" id="password" @keyup.enter="login()" v-model="password" class="form-control"  placeholder="············" >
                <span class="input-group-text cursor-pointer" @click="show_pass=='password'?show_pass='text':show_pass='password'">
                  <i class="bx bx-hide" v-if="show_pass=='password'"></i>
                  <i class='bx bx-show' v-if="show_pass=='text'"></i>
                </span>
              </div>
            </div>
  
           <div class="alert alert-warning" role="alert" v-if="check_email_text || check_pass_text || text_error">
            {{ check_email_text }} {{ check_pass_text }} {{ text_error }}
          </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" :disabled="check_from_login" @click="login()" >
                <span v-if="loading" >
                  <span class="spinner-border me-1" role="status" aria-hidden="true"></span> ກຳລັງກວດສອບ...
                </span>
                
                <span v-else>ເຂົ້າສູ່ລະບົບ</span>
                </button>

              

            </div>


          <p class="text-center">
            DMS V 1.1 Beta
          </p>
        </div>
      </div>
    </div>
    <!-- /Register -->
  </div>
</div>
</template>

<script>
import { useStore} from '../Store/auth'
export default {
    name: 'Minipos14Login',
    setup() {
        const store = useStore();
        return { store };
    },
    data() {
        return {
          user_name:'',
           email:'',
           password:'',
           check_email_text:'',
           check_pass_text:'',
           show_pass:'password',
           text_error:'',
           url: window.location.origin,
           loading:false
        };
    },
    computed:{
      check_from_login(){
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        //ກວດອີເມວລ໌ 
        if(this.email){
          if(!emailRegex.test(this.email)){
            this.check_email_text = 'ອີເມວລ໌ບໍ່ຖຶກຕ້ອງ';
          }else{
            this.check_email_text = '';
          }
        }

        // ກວດລະຫັດຜ່ານ
        if(this.password){
          if(this.password.length <6){
            this.check_pass_text = 'ລະຫັດຜ່ານ ຕ້ອງຫຼາຍກ່ວາ 5 ຕົວອັກສອນ';
          } else {
            this.check_pass_text = ''
          }
        } else {
          this.check_pass_text = '';
        }

        // ທຳການກວດຊອບແລ້ວ ປິດປຸ່ມ
        if(this.user_name=='' || this.password.length <6){
          return true;
        } else {
          if(this.loading){
            return true;
          } else {
            return false;
          }
      
        }

      }
    },

    mounted() {
        
    },

    methods: {
      login(){
          if(this.user_name !='' && this.password !=''){
            this.loading = true;
            axios.post('api/login',{
                        login_user: this.user_name,
                        login_password: this.password
                    }).then((res)=>{
                      this.loading = false;
                      if(res.data.success){
                        this.user_name = '';
                        this.password = '';
                        this.$router.push('/');
                        
                        localStorage.setItem('web_token',res.data.token);
                        localStorage.setItem('web_user',JSON.stringify(res.data.user));
                        localStorage.setItem('web_permission',res.data.permissions);
                        localStorage.setItem('web_setting',JSON.stringify(res.data.setting));

                        

                      } else {
                        this.text_error = res.data.message;
                      }

                        // console.log(res);
                    }).catch((error)=>{
                        console.log(error);
                    })
          }
      }
    },
};
</script>

<style lang="scss" scoped>

</style>