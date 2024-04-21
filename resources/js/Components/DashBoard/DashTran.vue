<template lang="">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                <h5 class="m-0 me-2">ລາຍການເອກະສານ</h5>
                </div>
                <div class="d-flex">
                    <div class="form-check me-2">
                    <input name="rd01" class="form-check-input" type="radio" v-model="ttype" value="padding" id="dpadding" >
                    <label class="form-check-label" for="dpadding">
                        ກຳລັງດຳເນີນການ
                    </label>
                    </div>

                    <div class="form-check">
                    <input name="rd01" class="form-check-input" type="radio" value="all" v-model="ttype"  id="dall" >
                    <label class="form-check-label" for="dall">
                        ທັງໝົດ
                    </label>
                    </div>
                    
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="table-responsive">
                <table class="dt-route-vehicles table dataTable no-footer dtr-column" >
                <thead class="border-top">
                    <tr>
                            <th class="sorting fs-6" > ເລກທີ </th>
                            <th class="sorting fs-6" > ວັນທີ </th>
                            <th class="sorting fs-6" >ປະເພດເອກະສານ</th>
                            <th class="sorting fs-6" >ຜູ້ບັນທຶກ</th>
                            <th class="sorting fs-6" >ສະຖານະ</th>
                            </tr>
                </thead>
                <tbody v-if="DataTran.data.length>0">
                    <tr class="odd" v-for="item in DataTran.data" :key="item.id">
                    <td class="sorting_1">{{item.dw_id}}</td>
                    <td>{{ date(item.created_at) }}</td>
                    <td>{{item.name}} <br>
                    <!-- <small>ລູກຄ້າ: {{item.customer_name}}</small> -->
                    </td>
                    <td>{{item.user_name}}</td>
                    <td>
                        <div class="d-flex align-items-center" :class="item.work_file<80?'text-danger':'text-info'">
                            <div class="progress w-75 me-2" style="height: 8px;">
                                <div class="progress-bar " :class="item.work_file<80?'bg-danger':'bg-info'" :style="'width:'+item.work_file+'%'" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span>{{item.work_file}}%</span>
                            </div>
                         <!-- <span class="badge bg-label-success me-1" v-if="item.status=='success'">ສຳເລັດ</span>
                <span class="badge bg-label-warning me-1" v-else>ດຳເນີນການ</span> -->
                    </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="5" class="text-center"> <i class='bx bxs-data me-1' ></i> ບໍ່ມີຂໍ້ມູນ </td>
                    </tr>
                </tbody>
                </table>
                <pagination class="mx-4"
                    :pagination="DataTran"
                    @paginate="GetTran($event)"
                    :offset="4"
                    />
                </div>
                </div>
            </div>
            </div>
        </div>

        <div class="col-md-4 ">
            <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                <h5 class="m-0 me-2">ຕິດຕາມກະແສເງິນ</h5>
                </div>
                <div class="dropdown">
                <button class="btn p-0" type="button" id="deliveryExceptions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryExceptions">
                    <a class="dropdown-item " href="javascript:void(0);" @click="DonutYear=YearNow" :class="DonutYear==YearNow?'active':''">ປະຈຳປີ {{YearNow}} <i class='bx bx-check-circle text-success' v-if="DonutYear==YearNow"></i></a>
                    <a class="dropdown-item" href="javascript:void(0);" @click="DonutYear='all'" :class="DonutYear=='all'?'active':''" >ທັງໝົດ <i class='bx bx-check-circle text-success' v-if="DonutYear=='all'"></i></a>
                </div>
                </div>
            </div>
            <div class="card-body" style="position: relative;"> 
                 <DoughnutChart :chart-data="DonutData" :options="DonutOption"  />
            </div>
            <div class=" card-footer p-0 border-0 bg-light-subtle">
                                                   <div class="row g-0 text-start">
                                                    <div class="col-12 col-sm-12">
                                                        <div class="p-3 border border-dashed border-start-0 ">
                                                            
                                                            <p class="mb-0 d-flex align-items-center" > <i class='bx bxs-circle text-warning me-2'></i> ເງິນທັງໝົດ: {{formatPrice(DonutData.datasets[0].data[2])}} ₭</p>
                                                            <p class="mb-0 d-flex align-items-center"> <i class='bx bxs-circle text-danger me-2'></i> ເງິນໃນບັນຊີ: {{formatPrice(DonutData.datasets[0].data[1])}} ₭</p>
                                                            <p class="mb-0 d-flex align-items-center"> <i class='bx bxs-circle text-info me-2'></i> ເງິນສົດ: {{formatPrice(DonutData.datasets[0].data[0])}} ₭</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
            </div>
        </div>
</div>
</template>
<script>

import { DoughnutChart } from 'vue-chart-3';
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

import moment from 'moment'
import mixins from '../../mixins/ulmixins'
export default {
    mixins: [mixins],
    data() {
        return {
            DataTran:{data:[]},
            ttype:'all',
            DonutData:
                 {
                        labels: ['ເງິນສົດ', 'ເງິນໃນບັນຊີ', 'ເງິນທັງໝົດ'],
                        datasets:[{
                            backgroundColor: ['#3fc3ee','#f1556c','#f7b84b'],
                            data: [0, 0, 0],
                        }],
                        
                    }
            ,
            DonutOption:{
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                        label: function (tooltipItem, data) {
                        return (
                           ' '+tooltipItem.label +': '+ Number(tooltipItem.raw) .toFixed(0) .replace(/./g, function (c, i, a) { return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "." + c : c; }) + " ₭" );
                        },
                    },
                    mode: "index",
                    intersect: false,
                    },
                    legend: {
                        display: false
                    }
                },
               
            },
            YearNow:moment().format('YYYY'),
            DonutYear:moment().format('YYYY'),
        }
    },
    components: {
        DoughnutChart
    },
    methods: {
        GetTran(page){
            this.GetData(`gettran/dashtran?page=${page}&type=${this.ttype}`,result=>{
                this.DataTran = result.dashtran
            })
        },
        GetMn(){
            this.GetData(`gettran/gmn?year=${this.DonutYear}`,result=>{

                this.DonutData.datasets[0].data = result
            })
        
        }
    },
    created() {
        this.GetTran()
        this.GetMn()
    },
    watch: {
        ttype(){
            this.GetTran()
            
        },
        DonutYear(){
            this.GetMn()
        }

    }
}
</script>
<style lang="">
    
</style>