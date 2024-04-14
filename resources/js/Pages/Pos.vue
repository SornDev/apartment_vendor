<template>
    <di class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <input type="text" class=" form-control" placeholder="ຄົ້ນຫາ..." v-model="Search" @keyup.enter="GetStore()" >
                </div>

            </div>

            <perfect-scrollbar>
                <div style="height:66vh;"> 
                <div class="row m-1">
                    <div class="col-md-3" v-for="item in StoreData.data" :key="item">
                        <div class="card mb-3 cursor-pointer" @click="AddProduct(item.id)">
                            <span v-for="i in ListOrder" :key="i.id">
                                    <span class="number-list" v-if="item.id == i.id" >{{ i.order_amount }}</span>
                            </span>
                            <img class="card-img-top img-pos" v-if="item.image" :src="url + '/assets/img/'+item.image">
                            <img class="card-img-top img-pos" v-else :src="url + '/assets/img/no_img.jpg'">
                            <div class="card-body p-1 text-center">
                                <h5 class="card-title m-0">{{item.name}}</h5>
                                <p class="card-text">
                                13.000 kip
                                </p>
                                
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </perfect-scrollbar>

        </div>
        <div class="col-md-4" >
            <div class="card">
                <div class="card-body p-0">
                    <div class="p-3">
                            <label for="customer_name">ຊື່ລູກຄ້າ:</label>
                            <input type="text" class=" form-control" v-model="customer_name" id="customer_name" placeholder=".....">
                            <label for="customer_tel">ເບີໂທ:</label>
                            <input type="text" class=" form-control" v-model="customer_tel" id="customer_tel" placeholder=".....">
                    </div>
                    <hr>
                    <div class="p-3">
                        <div class=" d-flex justify-content-between fs-4 fw-bold text-info" >
                            <span>ລວມຍອດເງິນ:</span>
                            <span>{{ formatPrice(TotalAmount) }} ກີບ</span>
                        </div>
                      <button class="btn btn-success fw-bold w-100 fs-5 " :disabled="!TotalAmount" @click="ShowModal()" >ຊຳລ່ະເງິນ</button>
                    </div>

                 
                    <perfect-scrollbar class="mb-3">
                        <div style="height: 40vh;">
                        <div class="table-responsive text-nowrap mb-3" >
                            <table class="table border">
                            <thead>
                                <tr>
                                <th>ລາຍລະອຽດ</th>
                                <th>ລາຄາ</th>
                                <th>ລາຄາລວມ</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr v-for="list in ListOrder" :key="list.id">
                                        <td>{{ list.name }}</td>
                                        <td class="text-end">{{ formatPrice(list.price_sell) }} <br> 
                                            <i class='bx bx-minus-circle cursor-pointer text-danger' @click="RemoveProduct(list.id)"></i>  {{ formatPrice(list.order_amount) }} <i class='bx bx-plus-circle cursor-pointer text-success' @click="AddProduct(list.id)"></i> | <i class='bx bx-trash-alt cursor-pointer text-danger' @click="DeleteProduct(list.id)"></i>
                                        </td>
                                        <td class="text-end">{{ formatPrice(list.order_amount*list.price_sell) }}</td>
                                    </tr>
                            </tbody>

                            </table>
                        </div>
                </div>
                </perfect-scrollbar>
       
                    
                </div>
            </div>
        </div>
        <!-- {{ StorData }} -->

        <div class="modal fade" id="confirm_pay_modal" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel1">ການຊຳລ່ະເງິນ</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class=" d-flex justify-content-between">
                    <span>ລວມຍອດເງິນ:</span>
                    <span>{{ formatPrice(TotalAmount) }} ກີບ</span>
                  </div>
                  <div class=" d-flex justify-content-between">
                    <span>ຮັບເງິນນຳລູກຄ້າ:</span>
                    <span>{{ formatPrice(CashAmount) }} ກີບ</span>
                  </div>
                  <div class=" d-flex justify-content-between text-danger" v-if="(CashAmount-TotalAmount)>0">
                    <span>ເງິນທອນ:</span>
                    <span>{{ formatPrice(CashAmount - TotalAmount) }} ກີບ</span>
                  </div>

                  <div class=" d-flex justify-content-center">
                    <div class="row" style=" width: 250px; ">
                <div class=" col-md-4 mt-2">
                    <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(1)" >1</button>
                </div>
                <div class=" col-md-4 mt-2">
                    <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(2)" >2</button>
                </div>
                <div class=" col-md-4 mt-2"> 
                    <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(3)" >3</button>
                </div>
                <div class=" col-md-4 mt-2">
                    <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(4)" >4</button>
                </div>
                <div class=" col-md-4 mt-2">
                    <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(5)" >5</button>
                </div>
                <div class=" col-md-4 mt-2">
                    <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(6)" >6</button>
                </div>
                <div class=" col-md-4 mt-2">
                    <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(7)" >7</button>
                </div>
                <div class=" col-md-4 mt-2">
                    <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(8)" >8</button>
                </div>
                <div class=" col-md-4 mt-2">
                    <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(9)" >9</button>
                </div>
                <div class=" col-md-4 mt-2">
                    <button class="btn btn-primary" style=" width: 60px;" @click="AddNum('00')" >00</button>
                </div>
                <div class=" col-md-4 mt-2">
                    <button class="btn btn-primary" style=" width: 60px;" @click="AddNum(0)" >0</button>
                </div>
                <div class=" col-md-4 mt-2">
                    <button class="btn btn-danger" style=" width: 60px;" @click="AddNum('-')" ><i class='bx bx-left-arrow-alt'></i></button>
                </div>


            </div>
                  </div>
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">ປິດ</button>
                  <button type="button" class="btn btn-primary" :disabled="!(CashAmount>=TotalAmount)" @click="ConfirmPay()" >ຍືນຍັນຊຳລ່ະເງິນ</button>
                </div>
              </div>
            </div>
          </div>
    </di>
</template>

<script>
import { useStore } from '../Store/auth'
export default {
    setup(){
        const store = useStore();
        return { store }
    },
    name: 'Minipos14Pos',

    data() {
        return {
            url: window.location.origin,
            Sort:'desc',
            PerPage: '30',
            Search:'',
            StoreData: [],
            ListOrder:[],
            CashAmount:'',
            customer_name:'',
            customer_tel:''
        };
    },

    mounted() {
        
    },
    computed:{
        TotalAmount(){
            let caculate = this.ListOrder.reduce((num, item)=> parseInt(num) + (parseInt(item.order_amount) * parseInt(item.price_sell)),0)
            return caculate
        }
    },

    methods: {
        AddNum(num){
            if(num == '-'){
                this.CashAmount = this.CashAmount.slice(0,-1); // 120 = 12
            } else {
                this.CashAmount = this.CashAmount + num; // 1 + 2 = 12 ///* 3
            }
        },
        ShowModal(){
            $('#confirm_pay_modal').modal('show')
        },
        ConfirmPay(){
                axios.post('api/transection/add',{
                    customer_name: this.customer_name,
                    customer_tel: this.customer_tel,
                    listorder: this.ListOrder,
                    acc_type: 'income'
                }, { headers:{ Authorization:"Bearer "+ this.store.get_token } }).then((res)=>{

                    if(res.data.success){
    

                        this.$swal({
                            position:'top-end',
                            toast: true,
                            title: res.data.message,
                            icon: "success",
                            showConfirmButton: false,
                            timer:2500
                        });

                    } else {

                        this.$swal({
                            title: res.data.message,
                            icon: "error",
                            showConfirmButton: false,
                            timer:3500
                        });

                    }

                }).catch((error)=>{
                    console.log(error)
                    if(error){
                        if(error.response.status == 401){
                            this.store.remove_token()
                            this.store.remove_user()
                            localStorage.removeItem("web_token")
                            localStorage.removeItem("web_user")
                            this.$router.push("/login")

                        }
                    }
                })
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        AddProduct(id){
            // console.log(id);
            let item = this.StoreData.data.find((i)=>i.id == id)

            let list_order_item = this.ListOrder.find((i)=>i.id == id)

                if(list_order_item){

                    list_order_item.order_amount++;

                } else {
                    // console.log(item);
                    this.ListOrder.push({
                        id: item.id,
                        name: item.name,
                        order_amount: 1,
                        price_sell: item.price_sell
                    })
                }

            
        },
        RemoveProduct(id){
            let item = this.ListOrder.find((i)=>i.id == id)
            if(item){
                item.order_amount--;
                if(item.order_amount<1){
                    this.ListOrder.splice(this.ListOrder.map((i)=>id).indexOf(id),1)
                }
            }
        },
        DeleteProduct(id){
            this.ListOrder.splice(this.ListOrder.map((i)=>id).indexOf(id),1)
        },
        GetStore(page){
            axios.get(`api/store?page=${page}&sort=${this.Sort}&perpage=${this.PerPage}&search=${this.Search}`, { headers:{ Authorization:"Bearer "+ this.store.get_token } }).then((res)=>{

                    this.StoreData = res.data

                }).catch((error)=>{
                    console.log(error)
                    if(error){
                        if(error.response.status == 401){
                            this.store.remove_token()
                            this.store.remove_user()
                            localStorage.removeItem("web_token")
                            localStorage.removeItem("web_user")
                            this.$router.push("/login")

                        }
                    }
                });
        }
    },
    created(){
            this.GetStore()
        },
    watch:{
        Search(){
            
            if(this.Search == ''){
                this.GetStore()
            }
        }
    }
        
    }
</script>

<style  scoped>

/* .ps {
  height: 400px;
} */

.img-pos{
    object-fit: cover;
    width: 100%;
    height: 110px;
    object-position: center;
}
.number-list{
    position: absolute;
    background: blue;
    color: white;
    padding: 5px;
    right: 0px;
    border-radius: 0px 10px 0px 10px;
}

</style>