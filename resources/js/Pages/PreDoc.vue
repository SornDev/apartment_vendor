<template>
<!-- <h4 class="py-3 mb-4">
  ຮ່າງ-ແບບເອກະສານ
</h4> -->
    <div class="card">
        <div class="p-3 border-bottom">
            <div class="row">
         
          <div class="col-md-6 d-flex text-nowrap  "> 
             <label for="doctype" class="col-form-label fs-5 me-2 fw-bold">ປະເພດເອກະສານ:</label>
            <select id="doctype" v-model="Dcat" class="form-select " >
            <option v-for="list in DocCatData" :key="list.id" :value="list.id"> {{list.name}} </option>
          </select>
          </div>
          <div class="col-md-6 d-flex align-items-center">
            <span class="me-2 fw-bold fs-5" >ຄ່າບໍລິການ: </span> <span class="fw-bold text-info fs-5">{{ formatPrice(PriceService)}} ກີບ</span>
          </div>
          
        </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class=" d-flex align-items-center">
                        <h5 class="m-0">ເອກະສານ ທີ່ຕ້ອງປະກອບ</h5> 
                        <div v-if="loading_table"  class="spinner-grow spinner-grow-sm text-warning ms-2" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <div class="demo-inline-spacing mt-3">
                      <ol class="list-group ">
                        <li v-for="(list, index) in DocData" :key="list.id" @click="listDoc=list.id" class="list-group-item d-flex justify-content-between align-items-center cursor-pointer "> <span>{{index+1}}. {{list.doc_name}}</span> <i class='bx bxs-right-arrow-circle fs-4 text-warning' v-if="listDoc==list.id"></i> </li>
                      </ol>
                    </div>

                </div>
                <div class="col-md-6">
                  <!-- {{DocSelecte}} <hr> -->
                  <div class="mb-2">
                      <span class="fs-5 fw-bold">ຮູບພາບ:</span>
                      <div class="mb-2">
                          <img :src="baseURL+'/assets/file/'+DocSelecte.image" class=" doc-img" v-if="CheckFile(DocSelecte.image)=='image'" alt="" srcset="">
                          <img :src="baseURL+'/assets/img/word.png'" class=" avatar" v-if="CheckFile(DocSelecte.image)=='doc'" alt="" srcset="">
                          <img :src="baseURL+'/assets/img/document.png'" class=" avatar" v-if="CheckFile(DocSelecte.image)=='no'" alt="" srcset="">
                          <object width="100%" height="450" :data="baseURL+'/assets/file/'+DocSelecte.image" v-if="CheckFile(DocSelecte.image)=='pdf'"></object>
                          
                      </div>
                      <button v-if="DocSelecte.image" class="btn btn-primary" @click="downloadItem(baseURL+'/assets/file/'+DocSelecte.image)">ດາວໂຫຼດ</button>
                          <span v-else class="text-danger"> ບໍ່ມີຮູບ/ເອກະສານ </span>
                  </div>
                  <div class="mb-2">
                      <span class="fs-5 fw-bold" >ເອກະສານຕົວຢ່າງ:</span>
                      <div class="mb-2">
                          <img :src="baseURL+'/assets/file/'+DocSelecte.doc_example" class=" doc-img" v-if="CheckFile(DocSelecte.doc_example)=='image'" alt="" srcset="">
                          <img :src="baseURL+'/assets/img/word.png'" class=" avatar" v-if="CheckFile(DocSelecte.doc_example)=='doc'" alt="" srcset="">
                          <img :src="baseURL+'/assets/img/document.png'" class=" avatar" v-if="CheckFile(DocSelecte.doc_example)=='no'" alt="" srcset="">
                          <object width="100%" height="450" :data="baseURL+'/assets/file/'+DocSelecte.doc_example" v-if="CheckFile(DocSelecte.doc_example)=='pdf'"></object>
                          
                      </div>
                      <button v-if="DocSelecte.doc_example" class="btn btn-primary" @click="downloadItem(baseURL+'/assets/file/'+DocSelecte.doc_example)">ດາວໂຫຼດ</button>
                      <span v-else class="text-danger"> ບໍ່ມີຮູບ/ເອກະສານ </span>
                  </div>
                  <div class="mb-2">
                      <span class="fs-5 fw-bold">ຟອມເອກະສານ:</span>
                      <div class="mb-2">
                          <img :src="baseURL+'/assets/file/'+DocSelecte.doc_form" class=" doc-img" v-if="CheckFile(DocSelecte.doc_form)=='image'" alt="" srcset="">
                          <img :src="baseURL+'/assets/img/word.png'" class=" avatar" v-if="CheckFile(DocSelecte.doc_form)=='doc'" alt="" srcset="">
                          <img :src="baseURL+'/assets/img/document.png'" class=" avatar" v-if="CheckFile(DocSelecte.doc_form)=='no'" alt="" srcset="">
                          <object width="100%" height="450" :data="baseURL+'/assets/file/'+DocSelecte.doc_form" v-if="CheckFile(DocSelecte.doc_form)=='pdf'"></object>
                         
                      </div>
                       <button v-if="DocSelecte.doc_form" class="btn btn-primary" @click="downloadItem(baseURL+'/assets/file/'+DocSelecte.doc_form)">ດາວໂຫຼດ</button>
                      <span v-else class="text-danger"> ບໍ່ມີຮູບ/ເອກະສານ </span>
                  </div>
                  
                  
                  
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
import mixins from '../mixins/ulmixins'
export default {
    name: 'DmsPreDoc',
    mixins:[mixins],
    data() {
        return {
            DocCatData: [],
            DocData: [],
            Dcat:'',
            listDoc:'',
            DocSelecte:{}
        };
    },
  components: {

  },
  computed: {
      PriceService(){
        let price = 0;
        if(this.DocCatData.length > 0){
          price = this.DocCatData.find((i)=>i.id==this.Dcat).price;
        }
        return price;
      }
  },
    mounted() {
        
    },

    methods: {
      downloadItem(url,name) {
            
            // download file from url new tab and close it
           
            window.open(url, '_blank');           
           
        
            },
      CheckFile(file){
        if(file){
          let valitImage = ['.jpg', '.png', '.jpeg'].some(ext => file.includes(ext));
          let valitDoc = ['.doc', '.docx'].some(ext => file.includes(ext));
          let valitPdf = ['.pdf'].some(ext => file.includes(ext));
          if(valitImage){
            return 'image';
          } else if(valitDoc){
            return 'doc';
          } else if(valitPdf){
            return 'pdf';
          } else {
            return 'no';
          }
        }
      },
        GetDocCat(){
            this.GetData('doccat',result=>{
                this.DocCatData = result.data;
            });
        },
        GetDoc(id){
            this.GetData('doc/get/'+id,result=>{
                this.DocData = result;
            });
        }
    },
    created() {
      this.GetDocCat();
    },
    watch: {
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
      Dcat: function(val){
        this.GetDoc(val);
        this.listDoc = ''
        this.DocSelecte =''
      },
      listDoc: function(val){
        if(val){
          this.DocSelecte = this.DocData.find(item => item.id == val);
        }
      }
    }
};
</script>

<style scoped>
  .doc-img{
    width: 200px;
    height: 200px;
    object-fit: cover;
    object-position: center;
  }
</style>