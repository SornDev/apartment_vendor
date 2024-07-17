<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
            <div class="card-header d-flex">
                    <h5 class="mb-0">ລາຍງານ ລາຍຮັບ-ລາຍຈ່າຍ</h5>
                    <div v-if="loading_table"  class="spinner-grow spinner-grow-sm text-warning ms-2" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
            </div>
            
            <div class="card-body">
                <div>
                    <div class="input-group" style="width:240px">
            <span class="input-group-text" id="basic-addon11">
              <!-- <span class="me-2 cursor-pointer" @click="dmy='d'" :class="dmy=='d'?'text-success':''"><i class='bx bx-chevron-right' v-if="dmy=='d'"></i>ວັນ </span> -->
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
                </div>
                <div>
                <LineChart :chartData="chdata" :options="choption"  />
                </div>
                <div class="d-flex justify-content-center">
                <table style="width:350px" class="table table-bordered border mt-2">
                    <thead>
                        <tr>
                            <td colspan="2" class="text-center">ລວມຍອດ</td>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-end">ລາຍຮັບ:</td>
                        <td class="text-end">{{formatPrice(sum_all_income)}} ₭</td>
                    </tr>
                    <tr>
                        <td class="text-end">ລາຍຈ່າຍ:</td>
                        <td class="text-end">{{formatPrice(sum_all_expense)}} ₭</td>
                    </tr>
                    <tr>
                        <td class="text-end">ກຳໄລ:</td>
                        <td class="text-end">{{formatPrice(sum_all_profit)}} ₭</td>
                    </tr>
                    </tbody>
                </table>
                
                </div>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import { LineChart } from 'vue-chart-3';
import { Chart, registerables } from "chart.js";
import moment from "moment";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";

Chart.register(...registerables);

import mixins from '../../mixins/ulmixins';
export default {
    name: 'DmsReIncExp',
    mixins: [mixins],
    data() {
        return {
            dmy: "m",
            date:moment().format("YYYY-MM-DD"),
            config: {
            // minDate: "today",
            // mode: "range",
            wrap: true,
            altFormat: "d/m/Y",
            altInput: true,
            dateFormat: "Y-m-d",
            allowInput: false,
          },
            chdata:[],
            choption:{
                plugins:{
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                        return (
                            Number(tooltipItem.raw) .toFixed(0) .replace(/./g, function (c, i, a) { return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "." + c : c; }) + " ກີບ" );
                        },
                    },
                    mode: "index",
                    intersect: false,
                    },
              },
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                    y:{
                        ticks: {
                            display: true,
                            beginAtZero: false,
                            callback: function (value, index, values) {
                            return ( Number(value) .toFixed(0) .replace(/./g, function (c, i, a) { return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "." + c : c; }) + " ກີບ" );
                            },
                        },
                        gridLines: {
                            show: true,
                        },
                        },
                    },
                    
            },
            sum_all_income:0,
            sum_all_expense:0,
            sum_all_profit:0,
        };
    },
    components: {
        LineChart,
        flatPickr,
    },
    mounted() {
        
    },

    methods: {
        GetInEx(){
            this.GetData(`gettran/incexp?dmy=${this.dmy}&date=${this.date}`,result=>{

                    this.sum_all_income = result.sum_all_income;
                    this.sum_all_expense = result.sum_all_expense;
                    this.sum_all_profit = result.sum_all_profit;
                    this.chdata = {
                            labels: result.label,
                            datasets: [
                                {
                                    label: 'ລາຍຮັບ',
                                    fill: false,
                                    borderColor: "#3fc3ee",
                                    data: result.income,
                                },
                                {
                                    label: 'ລາຍຈ່າຍ',
                                    fill: false,
                                    borderColor: "#f1556c",
                                    data: result.expense,
                                },
                                {
                                    label: 'ກຳໄລ',
                                    fill: false,
                                    borderColor: "#f7b84b",
                                    data: result.profit,
                                },
                            
                            ]
                        }
            })
        }
    },
    created() {
        this.GetInEx();
    },
    watch: {
        dmy(){
            this.GetInEx();
        },
        date(){
            this.GetInEx();
        }
    },
};
</script>

<style lang="scss" scoped>

</style>