<template>
    <div class="card">
      <div class="card-header d-flex">
        <h5 class="mb-0">ຈັດການຜູ້ໃຊ້</h5>
        <div v-if="loading_table"  class="spinner-grow spinner-grow-sm text-warning ms-2" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
      </div>
  
  <div class="card-body">
    <div class="table-responsive text-nowrap">
        <div class=" d-flex justify-content-between mb-2">
            <div class=" d-flex justify-content-between align-items-center">
                <div class=" cursor-pointer" @click="ChangeSort()">
                  <i class='bx bx-sort-up fs-4 me-2' v-if="Sort=='asc'"></i>
                  <i class='bx bx-sort-down fs-4 me-2' v-else></i>
                </div>
              
                <select class="form-select me-2" v-model="PerPage" @change="GetUser()" >
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
            
            </div>
            <div class=" d-flex">
                <input type="text" class=" form-control me-2" v-model="Search" @keyup.enter="GetUser()" placeholder="ຄົ້ນຫາ...">
                <button class="btn btn-primary" @click="AddUser()" v-if="store.get_permissions.includes('USER_ACC_EDIT')||JSON.parse(store.get_user).user_type=='admin'">ເພີ່ມໃໝ່</button>
            </div>
        </div>
      <table class="table table-bordered shadow-sm">
        <thead>
          <tr class="table-info">
            <th class="fs-6 fw-bold" width="40"></th>
            <th class="fs-6 fw-bold">ຊື່ ແລະ ນາມສະກຸນ</th>
            <th class="fs-6 fw-bold">ທີ່ຢູ່ / ຕິດຕໍ່</th>
            <th class="fs-6 fw-bold">ຊື່ຜູ້ໃຊ້</th>
            <th class="fs-6 fw-bold" width="110">ສະຖານະ</th>
            <th class="fs-6 fw-bold" width="100" v-if="store.get_permissions.includes('USER_ACC_EDIT')||store.get_permissions.includes('USER_ACC_DEL')||JSON.parse(store.get_user).user_type=='admin'">ຈັດການ</th>
          </tr>
        </thead>
        <tbody v-if="UserData.data.length>0">
          <tr v-if="loading_table" >
              <td colspan="6" class="text-center">
                  <div class="spinner-border text-warning" role="status">
                      <span class="visually-hidden">Loading...</span>
                      </div>
              </td>
          </tr>
          <tr v-else v-for="list in UserData.data" :key="list.id">
            <td> 
              <img :src="url+'/assets/img/'+list.image" v-if="list.image" class=" rounded-circle avatar imc" > 
              <img :src="url+'/assets/img/no-img.jpeg'" v-else  class=" rounded-circle avatar imc" > 
              </td>
            <td> ທ່ານ <span v-if="list.gender=='female'">ນ</span> {{list.name}} {{list.last_name}}</td>
            <td> {{list.address}} <br>   ເບີໂທ: {{list.tel}}</td>
            <td> {{list.user_name}} </td>
            <td class="text-center"> 
              <span class="badge bg-label-success" v-if="list.status=='active'">ໃຊ້ຢູ່</span>
              <span class="badge bg-label-danger" v-else >ປິດ</span>
               </td>
            <td class="text-center" v-if="store.get_permissions.includes('USER_ACC_EDIT')||store.get_permissions.includes('USER_ACC_DEL')">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);" @click="EditUser(list.id)" v-if="store.get_permissions.includes('USER_ACC_EDIT')||JSON.parse(store.get_user).user_type=='admin'"><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a>
                  <a class="dropdown-item" href="javascript:void(0);" @click="DeleteUser(list.id)" v-if="store.get_permissions.includes('USER_ACC_DEL')||JSON.parse(store.get_user).user_type=='admin'"><i class="bx bx-trash me-1"></i> ລຶບ</a>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
        <tr>
            <td colspan="6" class="text-center"> <i class='bx bxs-data me-1' ></i> ບໍ່ມີຂໍ້ມູນ </td>
        </tr>
      </tbody>
      </table>
      <pagination
                            :pagination="UserData"
                            @paginate="GetUser($event)"
                            :offset="4"
                          />
    </div>
  </div>
</div>
<div class="modal modal-top modal-md fade" data-bs-backdrop="static" id="user-form" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
              <form class="modal-content">
                <div class="modal-header">
                  <!-- <h5 class="modal-title" id="modalTopTitle">Modal title</h5> -->
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="close_use_form"></button>
                </div>
                <div class="modal-body pt-0"> 
                  <div class="row">
                    <div class="col-12 mb-3">
                      <div class="d-flex justify-content-center">
                        <div class="position-relative">
                          <img :src="user_img_pre" @click="$refs.user_img.click()" class="rounded-circle av-p cursor-pointer " alt="" srcset="">
                          <div class="position-absolute top-0 end-0">
                            <label  class="btn btn-icon btn-danger rounded-circle" v-if="UserForm.image" @click="rmimg()" style="width: 40px;height: 40px;"><i class='bx bx-x fs-4'></i></label>
                            <input type="file" ref="user_img" @change="onSelectImage($event)" class="d-none">
                          </div>
                        </div>
                      </div>

                      <!-- created form user data by UserForm -->
                     
                    </div>
                  </div>
                
                   <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="f-name" class="fs-6">ຊື່:</label>
                        <input class="form-control" type="text" v-model="UserForm.name" id="f-name" placeholder="...">
                    </div>
                    <div class="col-md-6">
                           <label for="f-lastname" class="fs-6">ນາມສະກຸນ:</label>
                        <input class="form-control" type="text" v-model="UserForm.last_name"  id="f-lastname" placeholder="...">
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-md-6">
                      <label for="" class="fs-6 me-2 mb-2">ເພດ:</label>
                      <div class=" d-flex align-items-center">
                          
                          <div class="form-check me-2 ">
                            <input name="gender" v-model="UserForm.gender" class="form-check-input" type="radio" value="male" id="male" >
                            <label class="form-check-label" for="male">
                              ຊາຍ
                            </label>
                          </div>

                          <div class="form-check me-2">
                            <input name="gender" v-model="UserForm.gender" class="form-check-input" type="radio" value="female" id="female" >
                            <label class="form-check-label" for="female">
                              ຍິງ
                            </label>
                          </div>
                      </div>
                      
                    </div>
                    <div class="col-md-6">
                    
                        <label for="st" class="form-label fs-6">ປະເພດຜູ້ໃຊ້:</label>
                        <select id="st" v-model="UserForm.staff_type" class="form-select">
                          <option value="staff">ພະນັກງານ</option>
                          <option value="user">ພະນັກງານ ແລະ ຜູ້ໃຊ້ງານ</option>
                        </select>
                   
                    </div>
                  </div>

                  <div class="row mb-2" v-if="UserForm.staff_type=='user'">
                    <div class="col-md-6">
                      <label for="user-name" class="form-label fs-6">ຊື່ຜູ້ໃຊ້:</label>
                            <input type="text" v-model="UserForm.user_name" id="user-name" class="form-control" placeholder="..." />
                    </div>
                    <div class="col-md-6">
                      <label for="user-pass" class="form-label fs-6">ລະຫັດຜ່ານ:</label>
                            <input type="password" v-model="UserForm.password" id="user-pass" class="form-control" placeholder="..." />
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-md-6">
                      <label for="tel" class="form-label fs-6">ເບີໂທ:</label>
                            <input type="text" v-model="UserForm.tel" id="tel" class="form-control" placeholder="..." />
                    </div>
                    <div class="col-md-6">
                      <label for="address" class="form-label fs-6">ທີ່ຢູ່:</label>
                            <input type="text" v-model="UserForm.address" id="address" class="form-control" placeholder="..." />
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-md-6">
                     <label for="rolse" class="form-label fs-6">ສິດຜູ້ໃຊ້:</label>
                        <select id="st" v-model="UserForm.roles" class="form-select">
                          <option :value="item.id" v-for="item in RolesData" :key="item.id"> {{item.role_name}} </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                     
                     <label for="status" class="form-label fs-6">ສະຖານະ:</label>
                        <select id="st" v-model="UserForm.status" class="form-select">
                          <option value="active">ໃຊ້ງານຢູ່</option>
                          <option value="disable">ປິດ</option>
                        </select>
                    </div>
                  </div>
                  
                  
                  <div class=" d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-primary me-2" :disabled="check_bt" @click="SaveUser()" >
                       <span v-if="!loading_post">ບັນທຶກ</span>
                        <div v-else class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>
                    </button>
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ຍົກເລີກ</button>
                  
                  </div>
                </div>

              </form>
            </div>
          </div>
</template>

<script>
import mixins from '../mixins/ulmixins'
import { useStore } from '../Store/auth'
export default {
    name: 'DmsUserMg',
    setup() {
      const store = useStore()
      return { store }
    },
    mixins:[mixins],
    data() {
        return {
            url: window.location.origin,
            user_img_pre: window.location.origin+'/assets/img/no-img.jpeg',
            UserData:{
              data:[]
            },
            RolesData:[],
            UserForm:{
              name:'',
              last_name:'',
              gender:'male',
              staff_type:'user',
              user_name:'',
              password:'',
              image:'',
              tel:'',
              address:'',
              roles:'',
              status:'active',
            }
        };
    },
    computed:{
        check_bt(){
          if(this.UserForm.name==''){
            return true
          }else if(this.UserForm.last_name==''){
            return true
          }else if(this.UserForm.tel==''){
            return true
          }else if(this.UserForm.address==''){
            return true
          }else if(this.UserForm.staff_type=='user' && this.UserForm.user_name==''){
            return true
          }else if(this.UserForm.staff_type=='user' && this.UserForm.password==''){
            return true
          }else{
            if(this.loading_post){
              return true
            } else {
              return false
            }
          }
        }
    },
    mounted() {
        
    },

    methods: {
      ChangeSort(){
        if(this.Sort=='asc'){
          this.Sort = 'desc'
        }else{
          this.Sort = 'asc'
        }
        this.GetUser()
      },
      rmimg(){
            this.UserForm.image = ''
            this.user_img_pre = this.url+'/assets/img/no-img.jpeg'
      },
      onSelectImage(e){
            // console.log(e);
            var file = e.target.files[0];
            if (file) {
                this.UserForm.image = file;
                var reader = new FileReader();
                    reader.onloadend = () => {
                        // this.upimg_pre = reader.result;
                        if (file.type.includes('image')) {
                            this.user_img_pre = reader.result;
                        } else if(file.type.includes('word')){
                            this.user_img_pre = this.url + '/assets/img/word.png';
                        } else if(file.type.includes('pdf')){
                            this.user_img_pre = this.url + '/assets/img/pdf.png';
                        } else {
                            this.user_img_pre = this.url + '/assets/img/document.png';
                        }
                    };
                reader.readAsDataURL(file);
            }
        },
        AddUser(){
            this.FormType = true
            // reset form
            this.UserForm = {
               name:'',
              last_name:'',
              gender:'male',
              staff_type:'user',
              user_name:'',
              password:'',
              image:'',
              tel:'',
              address:'',
              roles:'',
              status:'active',
            }

            var modal = new bootstrap.Modal(document.getElementById('user-form'), {
                keyboard: false
            })
            modal.show()
        },
        SaveUser(){
            if(this.FormType){
              this.AddData('user/add',this.UserForm,result=>{
                if(result.success){
                  this.$refs.close_use_form.click()
                  this.GetUser()
                }
              })
            }else{
              this.UpdateData('user/update/'+this.EditID,this.UserForm,result=>{
                if(result.success){
                  this.$refs.close_use_form.click()
                  this.GetUser()
                }
              })
            
            }
        },
        EditUser(id){
          this.FormType = false
          this.EditData('user/edit',id,result=>{
            if(result.success){
              this.EditID = id
              this.UserForm = result.user
              if(result.user.image){
                this.user_img_pre = this.url+'/assets/img/'+result.user.image
              } else {
                this.user_img_pre = this.url+'/assets/img/no-img.jpeg'
              }
              var modal = new bootstrap.Modal(document.getElementById('user-form'), {
                  keyboard: false
              })
              modal.show()
            }
            // this.RolesData = result.roles
            // this.UserForm = result.user
            // if(result.user.image){
            //   this.user_img_pre = this.url+'/assets/img/'+result.user.image
            // } else {
            //   this.user_img_pre = this.url+'/assets/img/no-img.jpeg'
            // }
            // var modal = new bootstrap.Modal(document.getElementById('user-form'), {
            //     keyboard: false
            // })
            // modal.show()
          })
        },
        DeleteUser(id){
          this.DeleteData('user/delete',id,result=>{
            if(result.success){
              this.GetUser()
            }
          })
        },
        GetUser(page){
          this.GetData(`user/get?page=${page}&perpage=${this.PerPage}&sort=${this.Sort}&search=${this.Search}`,result=>{
            this.UserData = result.user
            this.RolesData = result.roles
          })
        }
    },
    created() {
      this.GetUser()
    },
    watch:{
      Search:function(val){
        if(val==''){
          this.GetUser()
        }
      }
    }
};
</script>

<style  scoped>
  .av-p{
    width: 200px;
    height: 200px;
    object-fit: cover;
    object-position: center;
  }
  .imc{
    object-fit: cover;
    object-position: center;
  }
</style>