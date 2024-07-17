<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex">
                    <h5 class="mb-0">ລາຍງານ ຕິດຕາມເງິນສົດ</h5>
                    <div v-if="loading_table"  class="spinner-grow spinner-grow-sm text-warning ms-2" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
                </div>
            
            <div class="card-body">

                <div class="d-sm-flex align-items-center justify-content-between border-bottom pb-2" >
                    <div class="align-items-center">
                      <!-- input date -->
                      <div class="input-group input-group" style="width: 220px">
                        <span class="input-group-text">
                          <span :class="dmy == 'd'?'text-success':''">
                          <i class='bx bx-chevron-right' v-if="dmy == 'd'"></i>
                           <span class="cursor-pointer  me-2" @click="dmy = 'd'" >ວັນ</span >
                          </span>
                         <span :class="dmy == 'm'?'text-success':''">
                           <i class='bx bx-chevron-right' v-if="dmy == 'm'"></i>
                           <span class="cursor-pointer me-2" @click="dmy = 'm'" >ເດືອນ</span > 
                         </span>
                           <span :class="dmy == 'y'?'text-success':''">
                           <i class='bx bx-chevron-right' v-if="dmy == 'y'"></i>
                           <span class="cursor-pointer" @click="dmy = 'y'" >ປີ</span >
                           </span>
                        </span>
                        <flat-pickr
                          v-model="date_tran"
                          :config="config"
                          class="form-control"
                          placeholder="ວັນທີ່"
                        />
                      </div>
                    </div>
                    <div>
                    
                      <button class="btn btn-info" @click="GetReport('true')">
                      <span v-if="created_report">
                        <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                                ກຳລັງສ້າງ...
                      </span>
                       <span v-else class="d-flex align-items-center">
                        <i class='bx bxs-file-pdf fs-4 me-1'></i>
                        ສ້າງລາຍງານ PDF
                       </span>
                        
                      </button>
                    </div>
                  </div>
                <div>
                

                        <div class="mt-2 mb-4">
                    <div class="text-center">
                    <h4>ລາຍງານ ຕິດຕາມເງິນສົດ</h4>
                    <p class="text-danger">ປະຈຳ:
                       <span v-if="dmy=='d'">ວັນ {{GetShowDate}}</span>
                     <span v-if="dmy=='m'">ເດືອນ {{GetShowMonth}}</span> <span v-if="dmy=='y'">ປີ {{GetShowYear}}</span></p>
                    </div>
                    <div class=" d-flex justify-content-center">
                    <div class="table-responsive mt-4">
                                            <table class="table table-bordered border-secondary table-nowrap align-middle mb-0" style=" width:800px;">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" rowspan="2" width="80" class="align-middle text-center fs-6"> ລ/ດ</th>
                                                        <th scope="col" rowspan="2" width="120" class="align-middle text-center fs-6">ວັນເດືອນປີ</th>
                                                        <th scope="col" rowspan="2" width="80"  class="align-middle text-center fs-6">ເລກທີ່</th>
                                                        <th scope="col" rowspan="2"  class="align-middle text-center fs-6">ເນື້ອໃນ</th>
                                                        <th scope="col" colspan="2" class="align-middle text-center fs-6">ຈຳນວນເງິນ</th>
                                                        <th scope="col" rowspan="2" width="130" class="align-middle text-center fs-6">ຍອດເຫຼືອ</th>
                                                    </tr>
                                                    <tr>
                                                      <th  class="align-middle text-center fs-6" width="130" >ຮັບເຂົ້າ</th>
                                                      <th  class="align-middle text-center fs-6" width="130" >ຈ່າຍອອກ</th>
                                                      
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        
                                                        <td colspan="4" class="text-end">ຍອດຍົກມາ</td>
                                                        <td class="text-end">{{formatPrice(DataBefore.income)}}</td>
                                                        <td class="text-end">{{formatPrice(DataBefore.expense)}}</td>
                                                        <td class="text-end">{{formatPrice(DataBefore.total)}}</td>
                                                    </tr>
                                                    <tr v-for="list, index in FinanceData" :key="list.id">
                                                        <td class="text-center">{{index+1}}</td>
                                                        <td>{{GetDate(list.created_at)}}</td>
                                                        <td>{{list.tran_id}}</td>
                                                        <td>{{list.tran_details}}</td>
                                                        <td class="text-end"> <span v-if="list.tran_type=='income'"> {{formatPrice(list.price)}} </span></td>
                                                        <td class="text-end"> <span v-if="list.tran_type=='expense'"> {{formatPrice(list.price)}}  </span> </td>
                                                        <td class="text-end">{{formatPrice(list.subtotal)}}</td>
                                                    </tr>
                                                    
                                                    <tr v-if="!FinanceData.length">
                                                      <td colspan="7" class="p-4 text-center"> 
                                                        <div class="d-flex align-items-center justify-content-center w-100">
                                                                    <i class='bx bx-data fs-4 text-muted'></i> <span class=" text-muted"> ບໍ່ມີຂໍ້ມູນການເຄື່ອນໄຫວ... </span> 
                                                            </div>
                                                         </td>
                                                    </tr>
                                                    <tr>
                                                        
                                                        <td colspan="4" class="text-end">ລວມຍອດ</td>
                                                        <td class="text-end">{{formatPrice(subtotal_income)}}</td>
                                                        <td class="text-end">{{formatPrice(subtotal_expense)}}</td>
                                                        <td class="text-end">{{formatPrice(sum_suntotal)}}</td>
                                                    </tr>
                                                    
                                                    
                                                </tbody>
                                            </table>

                                        <div class=" d-flex justify-content-center mt-2">
                                            <table class="border table-bordered" style="width:350px" >
                                                <tbody>
                                                <tr>
                                                    <td width="180" class="text-end p-1">ຍອດຍົກມາ:</td>
                                                    <td class="text-end p-1">{{formatPrice(DataBefore.total)}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end p-1">ເກີດຂື້ນຮັບເຂົ້າ:</td>
                                                    <td class="text-end p-1">{{formatPrice(subtotal_income_no)}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end p-1">ເກີດຂື້ນຈ່າຍອອກ:</td>
                                                    <td class="text-end p-1">{{formatPrice(subtotal_expense_no)}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end p-1">ຍອດເຫຼືອ:</td>
                                                    <td class="text-end p-1">{{formatPrice(sum_suntotal)}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                    </div>
                                        </div>

                                       
                    </div>
                  </div>

                </div>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import mixins from '../../mixins/ulmixins'
export default {
    name: 'DmsReRev',
    mixins: [mixins],
    data() {
        return {
            created_report:false,
            DataBefore:{
              income:0,
              expense:0,
              total:0
            },
            FinanceData:[],
             date_tran: moment().format("YYYY-MM-DD"),
            dmy: "m",
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
    components: {
        flatPickr,moment
    },
    mounted() {
        
    },
    computed: {
        subtotal_income_no(){
            let sum = 0;
            this.FinanceData.forEach((item) => {
                if(item.tran_type == 'income'){
                    sum += parseInt(item.price||0);
                }
            });
            return sum;
        },
        subtotal_expense_no(){
            let sum = 0;
            
            this.FinanceData.forEach((item) => {
                if(item.tran_type == 'expense'){
                    sum += parseInt(item.price||0);
                    // console.log(item.price)
                }
            });
            return sum;
        },
        subtotal_income(){
            let sum = parseInt(this.DataBefore.income);
            this.FinanceData.forEach((item) => {
                if(item.tran_type == 'income'){
                    sum += parseInt(item.price||0);
                }
            });
            return sum;
        },
        subtotal_expense(){
            let sum = parseInt(this.DataBefore.expense);
            
            this.FinanceData.forEach((item) => {
                if(item.tran_type == 'expense'){
                    sum += parseInt(item.price||0);
                    // console.log(item.price)
                }
            });
            return sum;
        },
        sum_suntotal(){
            return parseInt(this.subtotal_income) - parseInt(this.subtotal_expense);
        },
        GetShowDate(){
            return this.GetDate(this.date_tran)
        },
        GetShowMonth(){
            return this.GetMonth(this.date_tran)
        },
        GetShowYear(){
            return this.GetYear(this.date_tran)
        }
    },
    methods: {
         GetDate(date) {
        return moment(date).format("DD/MM/YYYY");
      },
      GetMonth(date) {
        return moment(date).format("MM/YYYY");
      },
      GetYear(date) {
        return moment(date).format("YYYY");
      },
        GetReport(pdf){
            this.created_report = true;
            this.GetDataPost(`report/finance?pdf=${pdf}`, {date_tran: this.date_tran, dmy: this.dmy},result=>{
            this.created_report = false;
              if(result.success){
                    this.DataBefore.income = result.sum_income_before;
                    this.DataBefore.expense = result.sum_expense_before;
                    this.DataBefore.total = result.subtotal_before;
                    this.FinanceData = result.transection;

                    if(result.fileUrl){
                      // generate new  url
                        window.open(result.fileUrl, '_blank');
                    }
               }
            })

        }
    },
    created() {
        this.GetReport();
    },
    watch: {
        date_tran: function (val) {
            this.GetReport();
        },
        dmy: function (val) {
            this.GetReport();
        },
    },
};
</script>

<style  scoped>
    table > thead > tr > th {
        font-size: 12px;
    }
    table > tbody > tr > td {
        font-size: 12px;
    }
  
</style>