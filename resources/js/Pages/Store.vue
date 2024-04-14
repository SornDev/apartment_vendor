<template>
    <div class="card">
  <h5 class="card-header">ລາຍການ ສະຕ໋ອກສິນຄ້າ</h5>
  <div class="card-body">
    <div v-if="ShowForm"> 
        <div class="text-end">
            <button class="btn btn-info me-2" :disabled="CheckFrom" @click="SaveStore()" >ບັນທຶກ</button>
            <button class="btn btn-danger" @click="CancelStore()">ຍົກເລີກ</button>
        </div>
        {{ FormStore }}
        <div class="row">
                <div class="col-md-3 text-center " style="position: relative;">
                    <button type="button" v-if="FormStore.image" @click="RemoveImg()" class="btn rounded-pill btn-icon btn-danger" style="position: absolute; top: 10px; right: 0px;">
                        <i class='bx bx-trash fs-5'></i>
                    </button>
                    <img :src="image_preview" alt="" @click="$refs.img_store.click()" class="cursor-pointer rounded" style=" width: 80%;">

                    <input type="file" ref="img_store" style="display: none;" @change="onSelect">
                </div>
                <div class="col-md-9">
                    
                        <div class="mb-2">
                            <label for="product_name" class="form-label fs-6">ຊື່ສິນຄ້າ:</label>
                            <input type="text" class="form-control" v-model="FormStore.name" id="product_name" placeholder="..." >
                        </div>
                        <div class="mb-2">
                            <label for="product_amount" class="form-label fs-6">ຈຳນວນ:</label>
                            <cleave  :options="options" class="form-control" v-model="FormStore.amount" id="product_amount" placeholder="..." />
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                <label for="product_price_buy" class="form-label fs-6">ລາຄາຊື້:</label>
                                <cleave  :options="options" class="form-control" v-model="FormStore.price_buy" id="product_price_buy" placeholder="..." />
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="product_price_sell" class="form-label fs-6">ລາຄາຂາຍ: </label>
                                    <cleave  :options="options" class="form-control" v-model="FormStore.price_sell" id="product_price_sell" placeholder="..." />
                                </div>
                            </div>
                        </div>
                </div>
        </div>
    </div>

    <div class="table-responsive text-nowrap" v-if="!ShowForm" >
        <div class=" d-flex justify-content-between mb-2">
            <div class=" d-flex justify-items-center"> 
                <div class="d-flex justify-items-center me-2 cursor-pointer" @click="ChangSort()">
                    <i class='bx bx-sort-up fs-3' v-if="Sort=='asc'"></i>
                    <i class='bx bx-sort-down fs-3' v-if="Sort=='desc'"></i>
                </div> 
                <select class="form-select" v-model="PerPage" @change="GetStore()" >
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
            </div>
            <div class=" d-flex">
                <input type="text" class=" form-control me-2" v-model="Search" @keyup.enter="GetStore()" placeholder="ຄົ້ນຫາ...">
                <button class="btn btn-primary" @click="AddStore()"><i class='bx bx-user-plus me-2 fs-5'></i> ເພີ່ມຂໍ້ມູນ</button>
            </div>
        </div> 
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="30">ID</th>
            <th width="120">ຮູບ</th>
            <th>ຊື່ສິນຄ້າ</th>
            <th width="120" class="text-center">ລາຄາຊື້</th>
            <th width="30" class="text-center">ຈັດການ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="list in StoreData.data" :key="list.id">
            <td> {{ list.id }} </td>
            <td> 
                <!-- {{ list.image }}  -->
                <img :src="url + '/assets/img/'+list.image" v-if="list.image"  class=" rounded" style="width: 100%;" >
                <img :src="url + '/assets/img/no_img.jpg'" v-else class=" rounded" style="width: 100%;" >
            </td>
            <td> {{ list.name  }} </td>
            <td class="text-end"> {{ formatPrice(list.price_buy) }} </td>
            <td class="text-center">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);" @click="CopyStore(list.id)"><i class='bx bx-copy-alt me-1'></i> ກ໋ອບປີ້</a>
                  <a class="dropdown-item" href="javascript:void(0);" @click="EditStore(list.id)"><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a>
                  <a class="dropdown-item" href="javascript:void(0);" @click="DeleteStore(list.id)"><i class="bx bx-trash me-1"></i> ລຶບຂໍ້ມູນ</a>
                </div>
              </div>
            </td>
          </tr>
          
        </tbody>
      </table>
      <Pagination :pagination="StoreData" :offset="4" @paginate="GetStore($event)"  />
      <button @click="showAlert">Hello world</button>
    </div>


  </div>
</div>
</template>

<script>
import { useStore } from '../Store/auth'
import axios from 'axios'
export default {
    name: 'Minipos14Store',
    setup(){
        const store = useStore();
        return { store }
    },
    data() {
        return {
           url: window.location.origin,
           image_preview: window.location.origin + '/assets/img/upload-img.png',
          ShowForm:false,
          FormType:true,
          EditID:'',
          FormStore:{
            name:'',
            image:'',
            amount:'',
            price_buy:'',
            price_sell:'',
          },
          StoreData:[],
          Sort:'asc',
          PerPage:'5',
          Search:'',
          options: {
                //   prefix: '₭ ',
                  numeral: true,
                  numeralPositiveOnly: true,
                  noImmediatePrefix: true,
                  rawValueTrimPrefix: true,
                  numeralIntegerScale: 10,
                  numeralDecimalScale: 2,
                  numeralDecimalMark: ',',
                  delimiter: '.'
                }
        };
    },
    computed:{
        CheckFrom(){
                if(this.FormStore.name=='' || this.FormStore.amount=='' || this.FormStore.price_buy=='' || this.FormStore.price_sell==''){
                    return true;
                }else {
                    return false;
                }
        }
    },

    mounted() {
        
    },

    methods: {
        showAlert() {
            // Use sweetalert2
            this.$swal({
                title:' res.data.message',
                            icon: "error",
                            showConfirmButton: false,
                            timer:3500
                });
            },
            RemoveImg(){
                this.FormStore.image = '';
                this.image_preview = window.location.origin + '/assets/img/upload-img.png';

            },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        ChangSort(){
                if(this.Sort == 'asc'){
                    this.Sort = 'desc'
                } else {
                    this.Sort = 'asc'
                }

                this.GetStore()
        },
        onSelect(event){
            //  console.log(event.target.files[0])
            this.FormStore.image = event.target.files[0]
            let reader = new FileReader()
            reader.readAsDataURL(this.FormStore.image)
            reader.addEventListener("load", function(){
                this.image_preview = reader.result
            }.bind(this),false)
        },
        AddStore(){

            this.FormStore.name = '';
            this.FormStore.amount = '';
            this.FormStore.price_buy = '';
            this.FormStore.price_sell = '';
            this.FormStore.image = '';
            this.image_preview = window.location.origin + '/assets/img/upload-img.png';

            this.ShowForm = true;
            this.FormType = true;
        },
        CancelStore(){
            this.ShowForm = false;
        },
        EditStore(id){
            // console.log(id)
            this.FormType = false;
            this.EditID = id;

            axios.get(`api/store/edit/${id}`,{ headers:{ "Content-Type":"multipart/form-data", Authorization:"Bearer "+ this.store.get_token } }).then((res)=>{
                this.FormStore = res.data;

                if(res.data.image){
                    this.image_preview = this.url + '/assets/img/' + res.data.image;
                } else {
                    this.image_preview = this.url + '/assets/img/upload-img.png';
                }

                this.ShowForm = true;
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
        CopyStore(id){
            // console.log(id)
            this.FormType = true;
            this.EditID = id;

            axios.get(`api/store/edit/${id}`,{ headers:{ "Content-Type":"multipart/form-data", Authorization:"Bearer "+ this.store.get_token } }).then((res)=>{
                this.FormStore = res.data;
                this.ShowForm = true;
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
        DeleteStore(id){

            this.$swal({
                title: "ທ່ານແນ່ໃຈບໍ່?",
                text: "ທີ່ຈະລຶບຂໍ້ມູນນີ້!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "ຕົກລົງ!",
                cancelButtonText: "ຍົກເລີກ",
                }).then((result) => {
                if (result.isConfirmed) {


                    axios.delete(`api/store/delete/${id},`,{ headers:{ Authorization:"Bearer "+ this.store.get_token } }).then((res)=>{
                        if(res.data.success){
                            this.GetStore()
                            this.$swal({
                                title: "ການລຶບຂໍ້ມູນ!",
                                text: res.data.message,
                                icon: "success",
                                showConfirmButton: false,
                                timer:3500
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

                    
                }
                });


          
        },
        SaveStore(){
            // ກວດປະເພດ ຟອມ FormType = true ເປັນການເພີ່ມຂໍ້ມູນ, FormType = false ເປັນການອັບເດດຂໍ້ມູນ
            if(this.FormType){
                // ເພີ່ມຂໍ້ມູນ
                axios.post('api/store/add', this.FormStore, { headers:{ "Content-Type":"multipart/form-data", Authorization:"Bearer "+ this.store.get_token } }).then((res)=>{
                    if(res.data.success){
                        this.ShowForm = false
                        this.GetStore()

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
                    // console.log(res)
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

            } else {
                // ອັບເດດຂໍ້ມູນ
                axios.post(`api/store/update/${this.EditID}`, this.FormStore, { headers:{ "Content-Type":"multipart/form-data", Authorization:"Bearer "+ this.store.get_token }}).then((res)=>{

                    if(res.data.success){
                        this.ShowForm = false
                        this.GetStore()
                    }
                    // console.log(res)
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
            }
        },
        GetStore(page){
            axios.get(`api/store?page=${page}&sort=${this.Sort}&perpage=${this.PerPage}&search=${this.Search}`, { headers:{ Authorization:"Bearer "+ this.store.get_token } }).then((res)=>{

                    this.StoreData = res.data;

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
        this.GetStore();
    },
    watch:{
        Search(){
            if(this.Search == ''){
                this.GetStore()
            }
        }
    }
};
</script>

<style lang="scss" scoped>

</style>