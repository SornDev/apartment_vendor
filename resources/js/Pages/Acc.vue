<template>
    <div class="card">
  <h5 class="card-header">ລາຍການທຸລະກຳ ບັນຊີ</h5>
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
                <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ລົງບັນຊີ</button>
                        <ul class="dropdown-menu" style="">
                        <li><a class="dropdown-item" @click="AddTran()" href="javascript:void(0);"> <i class='bx bx-transfer-alt fs-5 me-2'></i> ລົງບັນຊີ</a></li>
                        </ul>
                    </div>
            </div>
        </div>
      <table class="table table-bordered">
        <thead>
          <tr class="table-info">
            <th class="fs-6 fw-bold"></th>
            <th class="fs-6 fw-bold">ວັນທີ່</th>
            <th class="fs-6 fw-bold">ເລກທີທຸລະກຳ</th>
            <th class="fs-6 fw-bold">ລາຍລະອຽດ</th>
            <th class="fs-6 fw-bold">ຈຳນວນເງິນ</th>
            <th class="fs-6 fw-bold">ຈ່າຍໂດຍ</th>
            <th class="fs-6 fw-bold">ຜູ້ລົງບັນຊີ</th>
            <th class="fs-6 fw-bold">ຈັດການ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="list in TranData.data" :key="list">
            <td><i class='bx bxs-left-arrow-alt fs-3 text-success' v-if="list.tran_type=='income'"></i>
                <i class='bx bxs-right-arrow-alt fs-3 text-danger' v-else ></i>
            </td>
            <td>{{ date_change(list.tran_date) }}</td>
            <td> {{list.tran_id}} </td>
             <td > 
               {{list.tran_details}} 
             </td>
             <td class="text-end"> {{ formatPrice(list.price) }} ₭</td>
             <td>  <span v-if="list.bank_id=='bank'">ເງິນໂອນ</span> <span v-else>ເງິນສົດ</span>  </td>
             <td> {{list.user_name}}</td>
            <td class="text-center">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item text-danger" @click="RejectTran(list.id)" href="javascript:void(0);"><i class='bx bxs-eject me-1'></i> ຍົກເລີກ</a>
               
                </div>
              </div>
            </td>
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

<div class="modal modal-top modal-lg fade" id="acc_form" tabindex="-1" style="display: none" data-bs-backdrop="static" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="close_acc_form" ></button>
        </div>
        <div class="modal-body pt-0">
          <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-primary me-2" >
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


</template>

<script>
import moment from 'moment';
import mixins from '../mixins/ulmixins';
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
export default {
    mixins: [mixins],
    name: 'DmsAcc',

    data() {
        return {
            TranData:{data:[]},
            dmy:'d',
            date:moment().format('YYYY-MM-DD'),
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
        };
    },

    mounted() {
        
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
      },
      date_change(value){
            return moment(value).format('DD/MM/YYYY')
        },
        GetTran(page){
            this.GetData(`acc/get?page=${page}&dmy=${this.dmy}&date=${this.date}&search=${this.Search}&per_page=${this.PerPage}`,result=>{
                this.TranData=result
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