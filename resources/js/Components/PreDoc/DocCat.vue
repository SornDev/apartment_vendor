<template>
    <div class="card">
  <!-- <h5 class="card-header">ປະເພດເອກະສານ</h5> -->
  <div class=" d-flex justify-content-between p-3">
    <div class="text-wrap">
        <input type="text" v-model="Search" @keyup.enter="GetDocCat()" class=" form-control" placeholder="ຄົ້ນຫາ...">
    </div>
    
    <button type="button" class="btn rounded-pill btn-icon btn-info" @click="AddDocCat()">
                <i class='bx bx-plus fs-4'></i>
              </button>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <caption class="ms-4 me-4">
        <pagination
                            :pagination="DocCatData"
                            @paginate="GetDocCat($event)"
                            :offset="4"
                          />
      </caption>
      <thead>
        <tr class="border table-info">
          <th class="fs-6 fw-bold">ຊື່ປະເພດເອກະສານ</th>
          <th class="fs-6 fw-bold" width="150">ລາຄາບໍລິການ</th>
          <th class="fs-6 fw-bold text-center" width="100" >ຈັດການ</th>
          <th class="p-0" width="45" ></th>
        </tr>
      </thead>
      <tbody v-if="DocCatData.data.length>0">
        <tr v-if="loading_table" >
            <td colspan="2" class="text-center">
                <div class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>
            </td>
        </tr>
        <tr v-else class=" cursor-pointer" v-for="item in DocCatData.data" :key="item.id" @click="sent_id(item.id)">
          <td>{{item.name}}</td>
          <td class="text-end">{{ formatPrice(item.price)}} ກີບ</td>
          <td class="text-center">
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="javascript:void(0);" @click="EditDocCat(item.id)"><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a>
                <a class="dropdown-item" href="javascript:void(0);" @click="DeleteDocCat(item.id)"><i class="bx bx-trash me-1"></i> ລຶບ</a>
              </div>
            </div>
          </td>
          <td class="p-0">
            <div class=" d-flex align-items-center justify-content-center">
                <i class='bx bxs-right-arrow-circle fs-4 text-warning' v-if="doc_cat_id == item.id"></i>
            </div>
            
          </td>
        </tr>
       
        
      </tbody>
      <tbody v-else >
        <tr>
            <td colspan="3" class="text-center"> <i class='bx bxs-data me-1' ></i> ບໍ່ມີຂໍ້ມູນ </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<div class="modal modal-top fade" data-bs-backdrop="static" id="doc_cat" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
              <form class="modal-content">
                <div class="modal-header">
                  <!-- <h5 class="modal-title" id="modalTopTitle">Modal title</h5> -->
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="close_doc_cat"></button>
                </div>
                <div class="modal-body pt-0">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label  class="form-label fs-6">ຊື່ປະເພດເອກະສານ: <span class="text-danger">*</span></label>
                      <input type="text" v-model="doc_cat_name.name" class="form-control" placeholder="....">
                    </div>
                    <div class="col-6 mb-3">
                      <label  class="form-label fs-6">ລາຄາບໍລິການ: </label>
                     <div class="input-group">
                      <cleave :options="options" v-model="doc_cat_name.price" class="form-control text-end" placeholder="...." />
                      <span class="input-group-text cursor-pointer"> ກີບ </span>
                     </div>
                     
                    </div>
                  </div>
                  <div class=" d-flex justify-content-end">
                    <button type="button" class="btn btn-primary me-2" :disabled="check_bt" @click="SaveDocCat()">
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
// import mixins
import mixins from '../../mixins/ulmixins';
export default {
    mixins: [mixins],
    name: 'DmsCateDoc',
     emits: ["catid"],
    data() {
        return {
            doc_cat_name:{
              name:'',
              price:0
            },
            doc_cat_id:'',
            DocCatData:{data:[]},
        };
    },
       computed: {
        check_bt(){
            if(this.doc_cat_name.name == ''){
                return true
            } else {
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
      sent_id(id){
          this.doc_cat_id = id
          this.$emit('catid', id);
      },
        AddDocCat(){
          this.FormType = true
          this.doc_cat_name.name = ''
          this.doc_cat_name.price = 0
            var myModal = new bootstrap.Modal(document.getElementById('doc_cat'), {
                keyboard: false
              });
              myModal.show();
        },
        SaveDocCat(){

          if(this.FormType){
             this.AddData('doccat/add',this.doc_cat_name,result=>{
              // console.log(result)
              if(result.success){
               
                this.$refs.close_doc_cat.click()
                this.GetDocCat()
              }
              
             })
          }else{
            this.UpdateData('doccat/update/'+this.EditID,this.doc_cat_name,result=>{
              // console.log(result)
             
              this.$refs.close_doc_cat.click()
              this.GetDocCat()
             })
          }
            
        },
        EditDocCat(id){
          this.FormType = false

          this.GetData('doccat/edit/'+id,result=>{
            this.doc_cat_name = result
            this.EditID = id
            var myModal = new bootstrap.Modal(document.getElementById('doc_cat'), {
                keyboard: false
              });
              myModal.show();
          })

            

        },
        DeleteDocCat(id){
          this.DeleteData('doccat/delete',id,result=>{
            if(result.success){
              this.GetDocCat()
            }
            
          })
        },
        GetDocCat(page){
          this.GetData(`doccat?page=${page}&perpage=${this.PerPage}&short=${this.Sort}&search=${this.Search}`,result=>{
            this.DocCatData = result

            if(this.doc_cat_id == ''){
            // get  first id from DocCatData to doc_cat_id
            if(this.DocCatData.data.length > 0){
              this.doc_cat_id = this.DocCatData.data[0].id
              this.$emit('catid', this.doc_cat_id);
            }
          }

          })
          
        }
    },
    created() {
      this.GetDocCat()
    },
    watch: {
      Search(){
        if(this.Search == ''){
          this.GetDocCat()
        }
      }
    }
};
</script>

<style lang="scss" scoped>

</style>