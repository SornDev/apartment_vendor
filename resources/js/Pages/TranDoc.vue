<template>
    <div class="card">
  <h5 class="card-header">ລາຍການເອກະສານ</h5>
  <div class="card-body">
    <div class="table-responsive text-nowrap">
        <div class=" d-flex justify-content-between mb-2">
            <div class=" d-flex justify-content-between align-items-center">
                <div class=" cursor-pointer" @click="ChangeSort()">
                  <i class='bx bx-sort-up fs-4 me-2' v-if="Sort=='asc'"></i>
                  <i class='bx bx-sort-down fs-4 me-2' v-else></i>
                </div>
                

                <select class="form-select me-2" v-model="PerPage" @change="GetDocWork()" >
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
                <select class="form-select me-2" @change="GetDocWork()" v-model="DocType">
                    <option value="" >ປະເພດເອກະສານ</option>
                    <option  v-for="list in DocCatData" :key="list.id" :value="list.id"> {{list.name}} </option>
                    
                </select>
                <select class="form-select me-2" v-model="Status" @change="GetDocWork()" >
                    <option value="" >ສະຖານະ</option>
                    <option value="padding">ກຳລັງດຳເນີນການ</option>
                    <option value="success">ສຳເລັດ</option>
                </select>
            </div>
            <div class=" d-flex">
                <input type="text" class=" form-control me-2" placeholder="ຄົ້ນຫາ..." v-model="Search" @keyup.enter="GetDocWork()">
                <button class="btn btn-primary" @click="AddDocWork()" v-if="store.get_permissions.includes('DOC_ACC_EDIT')||JSON.parse(store.get_user).user_type=='admin'">ເພີ່ມໃໝ່</button>
            </div>
        </div>
      <table class="table table-bordered">
        <thead>
          <tr class="table-info">
            <th class="fs-6 fw-bold">ເລກທີ່</th>
            <th class="fs-6 fw-bold">ວັນທີ່</th>
            <th class="fs-6 fw-bold">ປະເພດເອກະສານ</th>
            <th class="fs-6 fw-bold">ລູກຄ້າ</th>
            <th class="fs-6 fw-bold text-center">ເອກະສານ</th>
            <th class="fs-6 fw-bold text-center">ຊຳລ່ະ</th>
            <th class="fs-6 fw-bold">ຜູ້ບັນທຶກ</th>
            <th class="fs-6 fw-bold" v-if="store.get_permissions.includes('DOC_ACC_EDIT')||store.get_permissions.includes('DOC_ACC_DEL')||JSON.parse(store.get_user).user_type=='admin'">ຈັດການ</th>
          </tr>
        </thead>
        <tbody v-if="TranDocData.data.length>0">
          <tr v-for="list in TranDocData.data" :key="list">
            <td>{{list.dw_id}}</td>
            <td>{{ date(list.created_at) }}</td>
            <td> {{ list.doc_cat_name }} </td>
            <td> <span v-if="list.customer_name">{{list.customer_name}}</span> <span v-else>ລູກຄ້າທົ່ວໄປ</span> </td>
             <td> 
              <div class="d-flex align-items-center" :class="list.work_file<80?'text-danger':'text-info'">
              <div class="progress w-75 me-2" style="height: 8px;">
                <div class="progress-bar " :class="list.work_file<80?'bg-danger':'bg-info'" :style="'width:'+list.work_file+'%'" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <span>{{list.work_file}}%</span>
            </div>
                <!-- <span class="badge bg-label-success me-1" v-if="list.status=='success'">ສຳເລັດ</span>
                <span class="badge bg-label-warning me-1" v-else>ດຳເນີນການ</span> -->
             </td>
             <td>
              <span class="badge bg-label-success me-1" v-if="list.rec_status=='success'">ສຳເລັດ</span>
                <span class="badge bg-label-warning me-1" v-else>ດຳເນີນການ</span>
             </td>
             <td> {{list.user_name}} </td>
            <td class="text-center" v-if="store.get_permissions.includes('DOC_ACC_EDIT')||store.get_permissions.includes('DOC_ACC_DEL')||JSON.parse(store.get_user).user_type=='admin'">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);" @click="EditDocWork(list.id)" v-if="store.get_permissions.includes('DOC_ACC_EDIT')||JSON.parse(store.get_user).user_type=='admin'"><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a>
                  <a class="dropdown-item" href="javascript:void(0);" @click="DelDocWork(list.id)" v-if="store.get_permissions.includes('DOC_ACC_DEL')||JSON.parse(store.get_user).user_type=='admin'"><i class="bx bx-trash me-1"></i> ລຶບ</a>
                </div>
              </div>
            </td>
          </tr>
          
         
        </tbody>
        <tbody v-else>
        <tr>
            <td colspan="8" class="text-center"> <i class='bx bxs-data me-1' ></i> ບໍ່ມີຂໍ້ມູນ </td>
        </tr>
      </tbody>
      </table>
      <pagination
                            :pagination="TranDocData"
                            @paginate="GetDocWork($event)"
                            :offset="4"
                          />
    </div>
  </div>
</div>
<div class="modal modal-top modal-lg fade" id="tran_doc" tabindex="-1" style="display: none;" data-bs-backdrop="static" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content" >
                <div class="modal-header">
                  <!-- <h5 class="modal-title" id="modalTopTitle">Modal title</h5> -->
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="close_tran_doc"></button>
                </div>
                <div class="modal-body pt-0"> 
                  <div class="row">
                    <div class="col-6 mb-3 d-flex justify-content-between">
                      <div> 
                        <!-- {{TranDocForm}} -->
                        <div v-if="TranDocForm.customer_id">
                            <span>ຊື່: {{TranDocForm.customer_name}}</span><br>
                            <span>ທີ່ຢູ່: {{TranDocForm.customer_address}}</span><br>
                            <span>ເບີໂທ: {{TranDocForm.customer_tel}}</span>
                        </div>
                        <div v-else>
                          ລູກຄ້າທົວໄປ
                        </div>
                          

                      </div>
                      <div>
                        
                          <div class="d-flex justify-content-start mb-2">
                            <div class="btn-group">
                              <button
                                type="button"
                                class="btn btn-icon rounded-pill btn-primary"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                @click="Csearch()"
                              >
                               <i class='bx bx-search'></i>
                              </button>
                              <div
                                class="dropdown-menu"
                                style="width: max-content"
                              >
                                <div class="p-2">
                                  <input
                                    type="text"
                                    class="form-control rounded-pill"
                                    placeholder="ຄົ້ນຫາ..."
                                   v-model="SearchCus"
                                   @keyup.enter="GetCus()"
                                   ref="search_cus"
                                  />
                                </div>
                                <a
                                  v-for="list in CustomerData" :key="list.id"
                                  @click="addCus(list.id,list.name,list.last_name,list.gender,list.tel,list.address)"
                                  class="dropdown-item align-middle d-flex align-items-center justify-content-between cursor-pointer"
                                >
                                  <span> <span v-if="list.gender=='female'">ນ</span> {{list.name}} {{list.last_name}}</span >
                                  <i class='bx bxs-left-top-arrow-circle ms-2'></i>
                                </a>
                              </div>
                            </div>
                          </div>
                       
                      </div>
                      
                    </div>
                    <div class="col-6 mb-3">
                        
                      
                    </div>
                  </div> 
                   <!-- {{ListDocForm}} -->
                  <div class="row">
                    <div class="col-md-6"> 
                     
                      <div class="mb-3">
                      <label for="doctype" class=" col-form-label fs-6">ປະເພດເອກະສານ:</label>
                          <select id="doctype" v-model="Dcat" @change="ChDocCat()" class="form-select">
                          <option v-for="list in DocCatData" :key="list.id" :value="list.id"> {{list.name}} </option>
                        </select>
                      </div>
                      <div class=" d-flex align-items-center">
                        <h6 class="m-0">ເອກະສານ ທີ່ຕ້ອງປະກອບ</h6> 
                        <div v-if="loading_table"  class="spinner-grow spinner-grow-sm text-warning ms-2" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <div class="demo-inline-spacing mt-3">
                      <ol class="list-group ">
                        <li v-for="(list, index) in DocData" :key="list.id" @click="Shdoc(list.id)" class="list-group-item d-flex justify-content-between align-items-center cursor-pointer "> <span>{{index+1}}. {{list.doc_name}}</span> <i class='bx bxs-right-arrow-circle fs-4 text-warning' v-if="listDoc==list.id"></i> </li>
                      </ol>
                    </div>
                    </div>
                    <div class="col-md-6">
                       <label  class="form-label fs-6" v-if="FormType" >ລາຄາບໍລິການ: </label>
                     <div class="input-group" v-if="FormType">
                      <cleave :options="options" v-model="TranDocForm.price" class="form-control text-end" placeholder="...." />
                      <span class="input-group-text cursor-pointer"> ກີບ </span>
                     </div>
                      <div class="mb-3">
                          <label for="nitice" class="fs-6 text-warning">ໝາຍເຫດ: </label>
                          <textarea name="" id="nitice" v-model="notices" class=" form-control" rows="5"></textarea>
                      </div>
                      <div class="mb-3"> 
                         <!-- {{listDoc}} -->
                        <label for="" class="fs-6 mb-2">ເອກະສານ:</label><br>
                         <img :src="upcp_pre" class=" rounded exp-img cursor-pointer" @click="$refs.doc_copy.click()">
                            <input type="file" ref="doc_copy" @change="onSelectDocCopy" style=" display:none" class="form-control" placeholder="....">
                      </div>
                    </div>
                  </div>
                    
                  
                  <div class=" d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-primary me-2"  @click="SaveDocWork()" :disabled="check_bt" >
                        <span v-if="!loading_post">ບັນທຶກ</span>
                        <div v-else class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>
                        </button>
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">ຍົກເລີກ</button>
                  
                  </div>
                </div>

              </div>
            </div>
          </div>
</template>

<script>
import moment from 'moment';
import mixins from '../mixins/ulmixins'
import {useStore} from '../Store/auth'
export default {
  setup() {
    const store = useStore()
    return {store}
  },
    name: 'DmsTranDoc',
    mixins:[mixins],
    data() {
        return {
          upcp_pre: window.location.origin + '/assets/img/upload.png',
            TranDocData:{
              data:[]
            },
            Sort:'desc',
            DocCatData:[],
            Dcat:'',
            DocData:[],
            listDoc:'',
            DocSelecte:{},
            TranDocForm:{
              doc_cat:'',
              price:0,
              customer_id:'',
              customer_name:'',
              customer_tel:'',
              customer_address:'',
              status:'pendding'
            },
            ListDocForm:[],
            DocCatEdit:'',
            ListDocFormEdit:[],
            PriceEdit:'',
            CustomerData:[],
            SearchCus:'',
            notices:'',
            // prices:'',
            DocType:'',
            Status:''
        };
    },
    computed: {
        check_bt(){
          if(this.TranDocForm.customer_id=='' || this.TranDocForm.price==''){
            return true;
          } else {
            if(this.loading_post){
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
       ChangeSort(){
        if(this.Sort=='asc'){
          this.Sort = 'desc'
        }else{
          this.Sort = 'asc'
        }
        this.GetDocWork()
      },
      date(value){
        return moment(value).format('DD/MM/YYYY');
      },
      onSelectDocCopy(e){
            // console.log(e);
            var file = e.target.files[0];
            if (file) {
                // this.DocForm.doc_example = file;
                if(this.ListDocForm.length > 0){
                  this.ListDocForm.find(item => item.doc_id == this.listDoc).doc_copy = file;
                }
                var reader = new FileReader();
                    reader.onloadend = () => {
                        // this.upexp_pre = reader.result;
                        if (file.type.includes('image')) {
                            this.upcp_pre = reader.result;
                        } else if(file.type.includes('word')){
                            this.upcp_pre = this.baseURL + '/assets/img/word.png';
                        } else if(file.type.includes('pdf')){
                            this.upcp_pre = this.baseURL + '/assets/img/pdf.png';
                        }else {
                            this.upcp_pre = this.baseURL + '/assets/img/document.png';
                        }
                    };
                reader.readAsDataURL(file);
            }
        },
        Csearch(){
          this.SearchCus = ''
          // settimer
          setTimeout(() => {
            this.$refs.search_cus.focus();
          }, 600);
        },
        addCus(id,name,last_name,gender,tel,address){
          this.TranDocForm.customer_id = id;
          this.TranDocForm.customer_name = gender=='female'? 'ນ '+ name + ' ' + last_name: name + ' ' + last_name;
          this.TranDocForm.customer_tel = tel;
          this.TranDocForm.customer_address = address;
          this.CustomerData = []

        },
        ChDocCat(){
            this.GetDoc(this.Dcat);
            this.listDoc = ''
            this.DocSelecte =''
            this.TranDocForm.doc_cat = this.Dcat;
            this.upcp_pre = window.location.origin + '/assets/img/upload.png';
            this.TranDocForm.price = this.DocCatData.find(item => item.id == this.Dcat).price;
        },
          AddDocWork(){
            // clear form
            this.TranDocForm = {
              doc_cat:this.Dcat,
              price:0,
              customer_id:'',
              customer_name:'',
              customer_tel:'',
              customer_address:'',
              status:'pendding'
            }
            this.GetDoc(this.Dcat)
            this.FormType = true
            this.SearchCus = ''
            this.CustomerData = []
            this.notices = ''
            this.prices = ''
            this.upcp_pre = window.location.origin + '/assets/img/upload.png';
            this.TranDocForm.price = this.DocCatData.find(item => item.id == this.Dcat).price;

            var modal = new bootstrap.Modal(document.getElementById('tran_doc'), {
                keyboard: false
            });
            modal.show();
          },
          SaveDocWork(){
                if(this.FormType){
                  this.AddData(`dwork/add`,{doc_form:this.TranDocForm,list_doc:this.ListDocForm},result=>{
                    if(result.success){
                      this.GetDocWork()
                      this.$refs.close_tran_doc.click();
                    }
                  });
              } else {
                  this.UpdateData(`dwork/update/${this.EditID}`,{doc_form:this.TranDocForm,list_doc:this.ListDocForm},result=>{
                    if(result.success){
                      this.GetDocWork()
                      this.$refs.close_tran_doc.click();
                    }
                  });
              }
          },
          EditDocWork(id){
           this.EditData(`dwork/edit`,id,result=>  {

                this.Dcat = result.docwork.doc_cat;
                this.GetDocEd(result.docwork.doc_cat);
                this.TranDocForm = result.docwork;
                this.ListDocForm = result.docwork_list;

                this.DocCatEdit = result.docwork.doc_cat;
                this.ListDocFormEdit = result.docwork_list;
                this.PriceEdit = result.docwork.price;
                

                // console.log(result);
                this.FormType = false
                var modal = new bootstrap.Modal(document.getElementById('tran_doc'), {
                    keyboard: false
                });
                modal.show();
            
            });

          },
          DelDocWork(id){
            this.DeleteData(`dwork/delete`,id,result=>{
              if(result.success){
                this.GetDocWork()
              }
            });
          },
          GetDocWork(page){
            this.GetData(`dwork/get?page=${page}&perpage=${this.PerPage}&sort=${this.Sort}&search=${this.Search}&status=${this.Status}&doc_cat=${this.DocType}`,result=>{
                this.TranDocData = result;
                // console.log(result);
            });
          },
          GetDocCat(){
            this.GetData('doccat',result=>{
                this.DocCatData = result.data;
            });
        },
        GetCus(){
          // console.log('aa');
          this.GetData(`cus/search?search=${this.SearchCus}`,result=>{
            this.CustomerData = result;
          });
        },
        GetDocEd(id){
            this.GetData('doc/get/'+id,result=>{
                this.DocData = result;
                // add id to ListDocForm
                // this.ListDocForm = result.map(item => {
                //   return {
                //     doc_work_id:null,
                //     doc_id:item.id,
                //     doc_copy: null,
                //     notice:null,
                //     status:'pendding'
                //   }
                // });
                if(this.DocData.length>0){
                  // this.listDoc = this.DocData[0].id
                  this.Shdoc(this.DocData[0].id)
                }
            });
        },
         GetDoc(id){
            this.GetData('doc/get/'+id,result=>{
                this.DocData = result;
                // add id to ListDocForm
                if(!this.FormType){
                  if(this.Dcat == this.DocCatEdit){
                    this.ListDocForm = this.ListDocFormEdit;
                    this.TranDocForm.price = this.PriceEdit
                  } else {
                    this.ListDocForm = result.map(item => {
                      return {
                        doc_work_id:null,
                        doc_id:item.id,
                        doc_copy: null,
                        notice:null,
                        status:'pendding'
                      }
                    });
                  }
                } else {
                  this.ListDocForm = result.map(item => {
                    return {
                      doc_work_id:null,
                      doc_id:item.id,
                      doc_copy: null,
                      notice:null,
                      status:'pendding'
                    }
                  });
                }
               
                if(this.DocData.length>0){
                  this.listDoc = this.DocData[0].id
                  this.Shdoc(this.DocData[0].id)
                }
            });
        },
        Shdoc(val){
          
          if(val){
            this.listDoc = val;
          this.DocSelecte = this.DocData.find(item => item.id == val);
            // console.log(this.ListDocForm);
          if(this.ListDocForm.find(item => item.doc_id == val).doc_copy == '' || this.ListDocForm.find(item => item.doc_id == val).doc_copy == null){
            this.upcp_pre = window.location.origin + '/assets/img/upload.png';
          } else {
            // this.upcp_pre = URL.createObjectURL(this.ListDocForm.find(item => item.doc_id == val).doc_copy);
            // check file type

            let file = this.ListDocForm.find(item => item.doc_id == val).doc_copy
            // console.log(file);
            // if(!this.FormType){
            //   if(this.Dcat == this.DocCatEdit){
            //     file = this.ListDocFormEdit.find(item => item.doc_id == val).doc_copy
            //   }
            // }
            
            // check file is image
            if(file.type){
                if(file.type.includes('image')){
                  this.upcp_pre = URL.createObjectURL(file);
                } else if(file.type.includes('word')){
                  this.upcp_pre = this.baseURL + '/assets/img/word.png';
                } else if(file.type.includes('pdf')){
                  this.upcp_pre = this.baseURL + '/assets/img/pdf.png';
                } else {
                  this.upcp_pre = this.baseURL + '/assets/img/document.png';
                }
            } else {
                  if (file.includes('jpg') || file.includes('jpeg') || file.includes('png')) {
                      this.upcp_pre = this.baseURL + '/assets/file/' + file;
                        } else if (file.includes('doc') || file.includes('docx')) {
                            this.upcp_pre = this.baseURL + '/assets/img/word.png';
                        } else if (file.includes('pdf')) {
                            this.upcp_pre = this.baseURL + '/assets/img/pdf.png';
                        } else {
                            this.upcp_pre = this.baseURL + '/assets/img/document.png';
                        }
            }
            
          } 
          // notices
          if(this.ListDocForm.find(item => item.doc_id == val).notice){
            this.notices = this.ListDocForm.find(item => item.doc_id == val).notice;
          } else {
            this.notices = '';
          }

          //prices
          // if(this.ListDocForm.find(item => item.doc_id == val).price){
          //   this.prices = this.ListDocForm.find(item => item.doc_id == val).price;
          // } else {
          //   this.prices = 0;
          // }
          

        }
        }
    },
    created(){
      this.GetDocCat()
      this.GetDocWork()
    },
    watch: {
      Search: function(val){
        if(val.length == ''){
          this.GetDocWork()   
        }
      },
      SearchCus: function(val){
        if(val.length == 0){
          // this.GetCus();
          this.CustomerData = [];
        }
      },
      DocCatData: function(val){
          if(val.length > 0){
            
            if(this.Dcat == ''){
              let id = val[0].id;
              this.GetDoc(id);
              this.Dcat = id;
             
            } else {
              let id = this.Dcat;
              this.GetDoc(id);
            
            }
            
          }
      },
      
      notices: function(val){
        if(val){
          this.ListDocForm.find(item => item.doc_id == this.listDoc).notice = val;
        }
      },
      // prices: function(val){
      //   if(val){
      //     // console.log(this.ListDocForm);
      //     this.ListDocForm.find(item => item.doc_id == this.listDoc).price = val;
      //   }
      // },
    }
};
</script>

<style scoped>
  .exp-img{
    padding: 4px;
    width: 140px;
    height: 140px;
    object-fit: cover;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
  }
</style>