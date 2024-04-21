<template>
    <div class="card">
  <h5 class="card-header">ຂໍ້ມູນລູກຄ້າ</h5>
  <div class="card-body">
    <div class="table-responsive text-nowrap">
        <div class=" d-flex justify-content-between mb-2">
            <div class=" d-flex justify-content-between align-items-center">
                <div class=" cursor-pointer" @click="ChangeSort()">
                  <i class='bx bx-sort-up fs-4 me-2' v-if="Sort=='asc'"></i>
                  <i class='bx bx-sort-down fs-4 me-2' v-else></i>
                </div>
                <select class="form-select me-2" v-model="PerPage" @change="GetCus()">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
            
            </div>
            <div class=" d-flex">
                <input type="text" class=" form-control me-2" v-model="Search" @keyup.enter="GetCus()" placeholder="ຄົ້ນຫາ...">
                <button class="btn btn-primary" @click="AddCus()" v-if="store.get_permissions.includes('CUS_ACC_EDIT')||JSON.parse(store.get_user).user_type=='admin'">ເພີ່ມໃໝ່</button>
            </div>
        </div> 
      <table class="table table-bordered">
        <thead>
          <tr class="table-info">
            <th class="fs-6 fw-bold">ຊື່ ແລະ ນາມສະກຸນ</th>
            <th class="fs-6 fw-bold">ທີ່ຢູ່</th>
            <th class="fs-6 fw-bold">ຂໍ້ມູນຕິດຕໍ່</th>
            <th class="fs-6 fw-bold" width="160">ປະຫວັດ ບໍລິການ</th>
            <th class="fs-6 fw-bold" width="90" v-if="store.get_permissions.includes('CUS_ACC_EDIT')||store.get_permissions.includes('CUS_ACC_DEL')||JSON.parse(store.get_user).user_type=='admin'">ຈັດການ</th>
          </tr>
        </thead>
        <tbody v-if="CusData.data.length>0">
          <tr v-for="list in CusData.data" :key="list.id">
            <td> ທ່ານ <span v-if="list.gender=='female'">ນ</span> {{list.name}} {{list.last_name}}</td>
            <td> {{list.address}} </td>
            <td> ເບີໂທ: {{list.tel}} </td>
            <td class="text-center"> <i class='bx bxs-info-circle fs-4' ></i> </td>
            <td class="text-center" v-if="store.get_permissions.includes('CUS_ACC_EDIT')||store.get_permissions.includes('CUS_ACC_DEL')||JSON.parse(store.get_user).user_type=='admin'">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);" @click="EditCus(list.id)" v-if="store.get_permissions.includes('CUS_ACC_EDIT')||JSON.parse(store.get_user).user_type=='admin'"><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a>
                  <a class="dropdown-item" href="javascript:void(0);" @click="DeleteCus(list.id)" v-if="store.get_permissions.includes('CUS_ACC_DEL')||JSON.parse(store.get_user).user_type=='admin'"><i class="bx bx-trash me-1"></i> ລຶບ</a>
                </div>
              </div>
            </td>
          </tr>
          
         
        </tbody>
         <tbody v-else>
        <tr>
            <td colspan="5" class="text-center"> <i class='bx bxs-data me-1' ></i> ບໍ່ມີຂໍ້ມູນ </td>
        </tr>
      </tbody>
      </table>
      <pagination
                            :pagination="CusData"
                            @paginate="GetCus($event)"
                            :offset="4"
                          />
    </div>
  </div>
</div>
<div class="modal modal-top modal-md fade" data-bs-backdrop="static" id="cus-form" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
              <form class="modal-content">
                <div class="modal-header">
                  <!-- <h5 class="modal-title" id="modalTopTitle">Modal title</h5> -->
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="close_cus_form"></button>
                </div>
                <div class="modal-body pt-0"> 
                  
                
                   <div class="row mb-2">
                    <div class="col-md-6">
                        <label for="f-name" class="fs-6">ຊື່:</label>
                        <input class="form-control" type="text" v-model="CusForm.name" id="f-name" placeholder="...">
                    </div>
                    <div class="col-md-6">
                           <label for="f-lastname" class="fs-6">ນາມສະກຸນ:</label>
                        <input class="form-control" type="text" v-model="CusForm.last_name"  id="f-lastname" placeholder="...">
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-md-6">
                      <label for="" class="fs-6 me-2 mb-2">ເພດ:</label>
                      <div class=" d-flex align-items-center">
                          
                          <div class="form-check me-2 ">
                            <input name="gender" v-model="CusForm.gender" class="form-check-input" type="radio" value="male" id="male" >
                            <label class="form-check-label" for="male">
                              ຊາຍ
                            </label>
                          </div>

                          <div class="form-check me-2">
                            <input name="gender" v-model="CusForm.gender" class="form-check-input" type="radio" value="female" id="female" >
                            <label class="form-check-label" for="female">
                              ຍິງ
                            </label>
                          </div>
                      </div>
                      
                    </div>
                    <div class="col-md-6">
                      <label for="tel" class="form-label fs-6">ເບີໂທ:</label>
                            <input type="text" v-model="CusForm.tel" id="tel" class="form-control" placeholder="..." />
                    </div>
                  </div>

                  

                  <div class="row mb-2">
                    
                    <div class="col-md-12">
                      <label for="address" class="form-label fs-6">ທີ່ຢູ່:</label>
                            <input type="text" v-model="CusForm.address" id="address" class="form-control" placeholder="..." />
                    </div>
                  </div>

                  <div class=" d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-primary me-2" @click="SaveCus()" >ບັນທຶກ</button>
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
    setup() {
      const store = useStore()
      return { store }
    },
    name: 'DmsCus',
    mixins:[mixins],
    data() {
        return {
          CusData:{
            data:[]
            },
            CusForm:{
              name:'',
              last_name:'',
              gender:'male',
              address:'',
              tel:''
            }
        };
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
        this.GetCus()
      },
        AddCus(){
          
          this.FormType = true;
          // reset form
          this.CusForm = {
              name:'',
              last_name:'',
              gender:'male',
              address:'',
              tel:''}

              var modal = new bootstrap.Modal(document.getElementById('cus-form'), {
                keyboard: false
              });
              modal.show();
        },
        SaveCus(){
            if(this.FormType){
                this.AddData('cus/add',this.CusForm,result=>{
                  if(result.success){
                    this.GetCus()
                    this.$refs.close_cus_form.click()
                  }
                })
            }else{
              this.UpdateData('cus/update/'+this.EditID,this.CusForm,result=>{
                if(result.success){
                  this.GetCus()
                  this.$refs.close_cus_form.click()
                }
              })
            }
        },
        EditCus(id){
            this.EditData('cus/edit',id,result=>{
   
                this.CusForm = result
                this.FormType = false
                var modal = new bootstrap.Modal(document.getElementById('cus-form'), {
                  keyboard: false
                });
                modal.show();
              
            })
        },
        DeleteCus(id){
            this.DeleteData('cus/delete',id,result=>{
              if(result.success){
                this.GetCus()
              }
            })
        },
        GetCus(page){
            this.GetData(`cus/get?page=${page}&perpage=${this.PerPage}&sort=${this.Sort}&search=${this.Search}`,result=>{
              this.CusData = result
            })
        }
    },
    created() {
      this.GetCus()
    },
    watch:{
      Search: function(val){
        if(val==''){
          this.GetCus(1)
        }
      }
    }
};
</script>

<style lang="scss" scoped>

</style>