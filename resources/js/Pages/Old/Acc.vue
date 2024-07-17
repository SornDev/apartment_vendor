<template>
    <div class="card">
      <div class="card-header d-flex">
            <h5 class="mb-0">ລາຍການທຸລະກຳ ບັນຊີ</h5>
            <div v-if="loading_table"  class="spinner-grow spinner-grow-sm text-warning ms-2" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
      </div>
  
  <div class="card-body">
    <div class="table-responsive text-nowrap">
        <div class=" d-flex justify-content-between mb-2">
            <div class="input-group" style="width:240px">
            <span class="input-group-text" id="basic-addon11">
              <span class="me-2 cursor-pointer" @click="dmy='d'" :class="dmy=='d'?'text-success':''"><i class='bx bx-chevron-right' v-if="dmy=='d'"></i>ວັນ </span>
              <span class="me-2 cursor-pointer" @click="dmy='m'" :class="dmy=='m'?'text-success':''"><i class='bx bx-chevron-right' v-if="dmy=='m'"></i> ເດືອນ </span>
              <span class="me-2 cursor-pointer" @click="dmy='y'" :class="dmy=='y'?'text-success':''"><i class='bx bx-chevron-right' v-if="dmy=='y'"></i> ປີ </span>
            
            </span>
            <flat-pickr
                          v-model="date"
                          :config="config"
                          class="form-control"
                          placeholder="ວັນທີ່"
                        />
            </div>
            <div class=" d-flex"> 
                <input type="text" class=" form-control me-2" v-model="Search" @keyup.enter="GetTran(1)" placeholder="ຄົ້ນຫາ...">
                <div class="btn-group" v-if="store.get_permissions.includes('ACC_ACC_ADD')||store.get_permissions.includes('ACC_ACC_MG')||JSON.parse(store.get_user).user_type=='admin'">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ລົງບັນຊີ</button>
                        <ul class="dropdown-menu" style="">
                        <li v-if="store.get_permissions.includes('ACC_ACC_ADD')||JSON.parse(store.get_user).user_type=='admin'" ><a class="dropdown-item" @click="AddTran()" href="javascript:void(0);"> <i class='bx bx-transfer-alt fs-5 me-2'></i> ລົງບັນຊີ</a></li>
                        <li v-if="store.get_permissions.includes('ACC_ACC_MG')||JSON.parse(store.get_user).user_type=='admin'"><a class="dropdown-item" @click="MgAcc()" href="javascript:void(0);"> <i class='bx bx-customize me-2'></i> ປະເພດລາຍຈ່າຍ</a></li>
                        </ul>
                    </div>
            </div>
        </div>
      <table class="table table-bordered shadow-sm">
        <thead>
          <tr class="table-info">
            <th class="fs-6 fw-bold text-center" colspan="2">ສະຖານະ</th>
            <th class="fs-6 fw-bold">ເລກທີທຸລະກຳ</th>
            <th class="fs-6 fw-bold">ວັນທີ່</th>
            <th class="fs-6 fw-bold">ລາຍລະອຽດ</th>
            <th class="fs-6 fw-bold">ຈຳນວນເງິນ</th>
            <th class="fs-6 fw-bold">ຈ່າຍໂດຍ</th>
            <th class="fs-6 fw-bold">ຜູ້ລົງບັນຊີ</th>
            <th class="fs-6 fw-bold" v-if="store.get_permissions.includes('ACC_REJECT')||JSON.parse(store.get_user).user_type=='admin'">ຈັດການ</th>
            
          </tr>
        </thead>
        <tbody v-if="TranData.data.length>0">
          <tr v-for="list in TranData.data" :key="list">
             <td class="p-1 text-center">
              <i class='bx bxs-check-shield text-success fs-4' v-if="list.status=='success'"></i>
              <i class='bx bxs-shield-x text-danger fs-4' v-else></i>
            </td>
            <td class="p-1 text-center"><i class='bx bxs-left-arrow-alt fs-3 text-success' v-if="list.tran_type=='income'"></i>
                <i class='bx bxs-right-arrow-alt fs-3 text-danger' v-else ></i>
            </td>
            <td> {{list.tran_id}} </td>
            <td>{{ date_change(list.tran_date) }}</td>
            
             <td > 
               {{list.tran_details}} 
             </td>
             <td class="text-end"> {{ formatPrice(list.price) }} ₭</td>
             <td>  <span v-if="list.bank_id=='bank'">ເງິນໂອນ</span> <span v-else>ເງິນສົດ</span>  </td>
             <td> {{list.user_name}}</td>
            <td class="text-center" v-if="store.get_permissions.includes('ACC_REJECT')||JSON.parse(store.get_user).user_type=='admin'">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item text-danger" @click="RejectTran(list.id)" href="javascript:void(0);"><i class='bx bxs-eject me-1'></i> ຍົກເລີກ</a>
               
                </div>
              </div>
            </td>
           
          </tr>
        </tbody>
        <tbody v-else>
        <tr>
            <td colspan="9" class="text-center"> <i class='bx bxs-data me-1' ></i> ບໍ່ມີຂໍ້ມູນ </td>
        </tr>
      </tbody>
      </table> 
      <pagination
                            :pagination="TranData"
                            @paginate="GetTran($event)"
                            :offset="4"
                          />
    </div>
  </div>
</div>

<div class="modal modal-top modal-md fade" id="acc_form" tabindex="-1" style="display: none" data-bs-backdrop="static" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
           <h5 class="modal-title" id="modalTopTitle">ລົງບັນຊີລາຍຈ່າຍ</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="close_acc_form" ></button>
        </div>
        <div class="modal-body pt-0">
          <div class="row"> 
            <!-- {{FormTranAcc}} -->
            <div class="col-6 mb-2">
                <label for="tran_id" class="form-label fs-6">ປະເພດລາຍຈ່າຍ</label>
                <select class=" form-select" v-model="FormTranAcc.acc_type_id">
                  <option :value="list.id" v-for="list in AccTData" :key="list.id"> {{ list.acc_type_name }} </option>
                </select>
            </div>
            <div class="col-6 mb-2">
              <label for="tran_date" class="form-label fs-6">ວັນທີ່ລົງທຸລະກຳ</label>
                <flat-pickr
                          v-model="FormTranAcc.tran_date"
                          :config="config"
                          class="form-control"
                          placeholder="ວັນທີ່"
                        />
            </div>
            <div class="col-6 mb-2">
              <label for="price" class="form-label fs-6">ຈຳນວນເງິນ</label>
              <div class="input-group">
               
                <cleave :options="options" class="form-control text-end" v-model="FormTranAcc.price" id="price"  placeholder="..." />
                 <span class="input-group-text" >₭</span>
              </div>
             
            </div>
            <div class="col-6 mb-2">
              <label for="" class="fs-6">ຊຳລະໂດຍ</label>
              <div class="d-flex justify-content-between mt-2">
                              <span class="me-4">
                                <div class="form-check">
                                  <input
                                    class="form-check-input"
                                    type="radio"
                                    id="formCheck1"
                                    name="payby"
                                    :value="0"
                                    v-model="FormTranAcc.PayBy"
                                  />
                                  <label
                                    class="form-check-label"
                                    for="formCheck1"
                                  >
                                    ເງິນສົດ
                                  </label>
                                </div>
                              </span>
                              <span>
                                <div class="form-check">
                                  <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payby"
                                    id="formCheck2"
                                    value="bank"
                                    v-model="FormTranAcc.PayBy"
                                  />
                                  <label
                                    class="form-check-label"
                                    for="formCheck2"
                                  >
                                    ເງິນໂອນ
                                  </label>
                                </div>
                              </span>
                            </div>
            </div>
              
            
          </div>
          <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-primary me-2" :disabled="CheckFormAcc" @click="SaveTran()"  >
                  <span v-if="!loading_post">ບັນທຶກ</span>
                  <div v-else class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </button>
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" > ຍົກເລີກ </button>
              </div>
        </div>
      </div>
    </div>
  </div>

<div class="modal modal-top modal-md fade" id="acc_mg" tabindex="-1" style="display: none" data-bs-backdrop="static" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTopTitle">ປະເພດລາຍຈ່າຍ</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="close_acc_mg" ></button>
        </div>
       
        <div class="modal-body pt-0">
          <div class="row" v-if="ShowAcc">
            <div class="col-12">
              <div class="mb-3 mt-3">
                <!-- <label for="acc_name" class="form-label fs-6">ຊື່ປະເພດລາຍຈ່າຍ</label> -->
                <input type="text" ref="ref_acc_name" v-model="AccTName" class="form-control" id="acc_name" placeholder="...">
              </div>
              <div class=" d-flex justify-content-end" >
              <button type="button" class="btn btn-primary me-2" :disabled="!AccTName" @click="SaveAccType()" >
                  <span v-if="!loading_post">ບັນທຶກ</span>
                  <div v-else class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </button>
                <button type="button" class="btn btn-label-secondary" @click="HideFromAt()" > ຍົກເລີກ </button>
              </div>
            </div>
          </div>
          <div class="row" v-else>
            <div class="col-12">
            <div class=" d-flex justify-content-end mb-2">
                <button type="button" class="btn btn-primary" @click="ShowFromAt()" >
                  ເພີ່ມໃໝ່
                </button>
            </div>
            <div class="table-responsive text-nowrap">
      <table class="table border shadow-sm ">
        <thead>
          <tr class=" bg-info ">
            <th class="fs-6 text-white fw-bold">ຊື່ປະເພດລາຍຈ່າຍ</th>
            <th class="fs-6 text-center text-white fw-bold" width="120">ຈັດການ</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr v-for="list in AccTData" :key="list.id">
            <td> {{list.acc_type_name}} </td>
            <td class="text-center">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);" @click="EditAcc(list.id)"><i class="bx bx-edit-alt me-2"></i> ແກ້ໄຂ</a>
                  <a class="dropdown-item" href="javascript:void(0);" @click="DelAcc(list.id)"><i class="bx bx-trash me-2"></i> ລຶບ</a>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
  </div>
  </div>
          </div>
          <!-- <div class="d-flex justify-content-end mt-2">
                <button type="button" class="btn btn-primary me-2" >
                  <span v-if="!loading_post">ບັນທຶກ</span>
                  <div v-else class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </button>
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" > ຍົກເລີກ </button>
              </div> -->
        </div>
      </div>
    </div>
  </div>
  


</template>

<script>
import moment from 'moment';
import mixins from '../../mixins/ulmixins';
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import {useStore} from '../../Store/auth'
export default {
  setup() {
    const store = useStore()
    return { store }
  },
    mixins: [mixins],
    name: 'DmsAcc',

    data() {
        return {
            TranData:{data:[]},
            dmy:'d',
            date:moment().format('YYYY-MM-DD')||'',
            Search:'',
            config: {
              // minDate: "today",
              // mode: "range",
              wrap: true,
              altFormat: "d/m/Y",
              altInput: true,
              dateFormat: "Y-m-d",
              allowInput: false,
            },
            FormTranAcc:{
              acc_type_id:'',
              tran_date:moment().format('YYYY-MM-DD'),
              price:0,
              PayBy:0
            },
            AccTData:[],
            AccTName:'',
            ShowAcc:false,
        };
    },

    mounted() {
        
    },
    computed: {
     
      CheckFormAcc(){
        if(this.FormTranAcc.acc_type_id==''||this.FormTranAcc.price==0){
          return true
        } else {
          return false
        }
      }
    },
    components: {
        flatPickr,moment
    },
    methods: {
      AddTran(){
        var modal = new bootstrap.Modal(document.getElementById('acc_form'), {
          keyboard: false
        })
        modal.show()
        this.FormTranAcc.PayBy = 0
        this.FormTranAcc.price = 0
        if(this.AccTData.length>0){
          this.FormTranAcc.acc_type_id = this.AccTData[0].id
        }
      },
      SaveTran(){

        this.AddData(`acc/add`,this.FormTranAcc,result=>{
          if(result.success){
            this.GetTran(1)
            // close modal
            this.$refs.close_acc_form.click()
          }
        })
      },
      MgAcc(){
        var modal = new bootstrap.Modal(document.getElementById('acc_mg'), {
          keyboard: false
        })
        modal.show()
      },
      date_change(value){
            return moment(value).format('DD/MM/YYYY')
        },
        GetTran(page){
            this.GetData(`acc/get?page=${page}&dmy=${this.dmy}&date=${this.date}&search=${this.Search}&per_page=${this.PerPage}`,result=>{
                this.TranData=result
            })
      
        },
        ShowFromAt(){
          this.ShowAcc=true
          this.AccTName = ''
          // set interval
          setTimeout(() => {
            this.$refs.ref_acc_name.focus()
          }, 800);

          // this.polling = setInterval(() => {
          //   this.$refs.ref_acc_name.focus()
          //   clearInterval(this.polling);
          // }, 800);

        },
        HideFromAt(){
          this.ShowAcc=false
        },
        GetAcc(){
          this.GetData(`acc_type/get`,result=>{
            this.AccTData=result
          })
        },
        EditAcc(id){
          this.FormType = false
          this.GetData(`acc_type/edit/${id}`,result=>{
            this.AccTName=result.acc_type_name
            this.ShowAcc = true
            this.EditID = id
            // set interval
            setTimeout(() => {
              this.$refs.ref_acc_name.focus()
            }, 800);

          })
        },
        SaveAccType(){
            if(this.FormType){
              this.AddData(`acc_type/add`,{acc_type_name:this.AccTName},result=>{
                if(result.success){
                     this.GetAcc()
                     this.ShowAcc = false
                }
               
              })
            } else {
              this.UpdateData(`acc_type/update/${this.EditID}`,{acc_type_name:this.AccTName},result=>{
                if(result.success){
                     this.GetAcc()
                     this.ShowAcc = false
                }
              })
            }
        },
        DelAcc(id){
          this.DeleteData(`acc_type/delete`,id,result=>{
            if(result.success){
                     this.GetAcc()
                }
          })
        },
        RejectTran(id){
          this.DeleteData(`acc/delete`,id,result=>{
            this.GetTran(1)
          },'ທີ່ຈະຍົກເລີກການລົງບັນຊີ!')
        }
    },
    created() {
        this.GetTran(1)
        this.GetAcc()
    },
    watch: {
        date(){
            this.GetTran(1)
        },
        dmy(){
            this.GetTran(1)
        },
        Search: function(val){
            if(val==''){
              this.GetTran(1)
            }
          }
        }
};
</script>

<style lang="scss" scoped>

</style>