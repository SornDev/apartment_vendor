<template>
    <div class="card">
  <!-- <h5 class="card-header">ປະເພດເອກະສານ</h5> -->
  <div class=" d-flex justify-content-between p-3">
    <div class="text-wrap">
        <input type="text" class=" form-control" v-model="Search" @keyup.enter="GetDoc()" placeholder="ຄົ້ນຫາ...">
    </div>
    
    <button type="button" class="btn rounded-pill btn-icon btn-info" :disabled="!catid" @click="AddDoc()">
                <i class='bx bx-plus fs-4'></i>
              </button>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table"> 
      <thead>
        <tr class="border table-info">
          <th class="fs-6 fw-bold">ລາຍການເອກະສານ</th>
          <th class="fs-6 fw-bold  text-center" width="120">ຈັດການ</th>
        </tr>
      </thead>
      <tbody v-if="DocData.length>0">
        <tr v-if="loading_table" >
            <td colspan="2" class="text-center">
                <div class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>
            </td>
        </tr>
        <tr v-else v-for="item in DocData" :key="item.id">
          <td>{{item.doc_name}} <span class="text-danger" v-if="item.notice">({{item.notice}}) </span></td>
          <td class="text-center">
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="javascript:void(0);"><i class='bx bxs-info-circle me-1'></i> ເບີ່ງ</a>
                <a class="dropdown-item" href="javascript:void(0);" @click="EditDoc(item.id)"><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a>
                <a class="dropdown-item" href="javascript:void(0);" @click="DeleteDoc(item.id)"><i class="bx bx-trash me-1"></i> ລຶບ</a>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr>
            <td colspan="2" class="text-center"> <i class='bx bxs-data me-1' ></i> ບໍ່ມີຂໍ້ມູນ </td>
        </tr>
      
        
      </tbody>
    </table>
  </div>
</div>
<div class="modal modal-top modal-lg fade" id="docm" tabindex="-1" data-bs-backdrop="static" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
              <form class="modal-content">
                <div class="modal-header">
                  <!-- <h5 class="modal-title" id="modalTopTitle">Modal title</h5> -->
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="close_docm"></button>
                </div>
                <div class="modal-body pt-0"> 
                  <div class="row">
                    <div class="col-6 mb-3">
                      <label  class="form-label fs-6">ຊື່ເອກະສານ <span class="text-danger">*</span></label>
                      <input type="text" v-model="DocForm.doc_name" class="form-control" placeholder="....">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col mb-3">
                      <label  class="form-label fs-6 text-danger">ໝາຍເຫດ</label>
                      <input type="text" v-model="DocForm.notice" class="form-control" placeholder="....">
                    </div>
                  </div>
                    <div class="row">
                        <div class="col-md-4" style="position: relative;">
                            <span class="text-danger x-icon" v-if="DocForm.image" @click="rm_dimg()"><i class='bx bxs-x-circle'></i></span>
                            <label  class="form-label fs-6">ຮູບພາບ</label><br>
                            <img :src="upimg_pre" class=" rounded exp-img cursor-pointer" @click="$refs.doc_img.click()">
                            <input type="file" ref="doc_img" @change="onSelectImage" style=" display:none" class="form-control" placeholder="...."> <br>
                            <!-- <button class="btn btn-info" @click="downloadItem(url+'/assets/file/'+DocForm.image,'iamge')">ດາວໂຫຼດ</button> -->

                        </div>
                        <div class="col-md-4" style="position: relative;">
                            <span class="text-danger x-icon" v-if="DocForm.doc_example" @click="rm_dexp()"><i class='bx bxs-x-circle'></i></span>
                            <label  class="form-label fs-6">ຕົວຢ່າງເອກະສານ</label><br>
                            <img :src="upexp_pre" class=" rounded exp-img cursor-pointer" @click="$refs.doc_exp.click()">
                            <input type="file" ref="doc_exp" @change="onSelectDocExample" style=" display:none" class="form-control" placeholder="....">

                        </div>
                        <div class="col-md-4" style="position: relative;">
                            <span class="text-danger x-icon" v-if="DocForm.doc_form" @click="rm_dform()"><i class='bx bxs-x-circle'></i></span>
                            <label  class="form-label fs-6">ຟອມເອກະສານ</label><br>
                            <img :src="upform_pre" class=" rounded exp-img cursor-pointer" @click="$refs.doc_form.click()">
                            <input type="file" ref="doc_form" @change="onSelectDocForm" style=" display:none" class="form-control" placeholder="....">

                        </div>
                    </div>
                  
                  <div class=" d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-primary me-2" :disabled="check_bt" @click="SaveDoc()">
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
import mixins from '../../mixins/ulmixins'
export default {
    mixins: [mixins],
    name: 'DmsDoc',
    props: {
        catid:{
            type: Number
        }
    },

    data() {
        return {
            url: window.location.origin,
            DocData:[],
            upimg_pre: window.location.origin + '/assets/img/photo.png',
            upexp_pre: window.location.origin + '/assets/img/upload.png',
            upform_pre: window.location.origin + '/assets/img/upload.png',
            DocForm: {
                cat_id: '',
                doc_name: '',
                image: '',
                doc_example: '',
                doc_form:'',
                notice:''
            }
        };
    },
    computed: {
        check_bt(){
            if(this.DocForm.doc_name == ''){
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
        downloadItem(url,name) {
            
            // download file from url new tab and close it
           
            window.open(url, '_blank');           
           
        
            },
        rm_dimg(){
            this.DocForm.image = ''
            this.upimg_pre = this.url + '/assets/img/photo.png'
        },
        rm_dexp(){
            this.DocForm.doc_example = ''
            this.upexp_pre = this.url + '/assets/img/upload.png'
        },
        rm_dform(){
            this.DocForm.doc_form = ''
            this.upform_pre = this.url + '/assets/img/upload.png'
        },
        onSelectImage(e){
            // console.log(e);
            var file = e.target.files[0];
            if (file) {
                this.DocForm.image = file;
                var reader = new FileReader();
                    reader.onloadend = () => {
                        // this.upimg_pre = reader.result;
                        if (file.type.includes('image')) {
                            this.upimg_pre = reader.result;
                        } else if(file.type.includes('word')){
                            this.upimg_pre = this.url + '/assets/img/word.png';
                        } else if(file.type.includes('pdf')){
                            this.upimg_pre = this.url + '/assets/img/pdf.png';
                        } else {
                            this.upimg_pre = this.url + '/assets/img/document.png';
                        }
                    };
                reader.readAsDataURL(file);
            }
        },
        onSelectDocExample(e){
            // console.log(e);
            var file = e.target.files[0];
            if (file) {
                this.DocForm.doc_example = file;
                var reader = new FileReader();
                    reader.onloadend = () => {
                        // this.upexp_pre = reader.result;
                        if (file.type.includes('image')) {
                            this.upexp_pre = reader.result;
                        } else if(file.type.includes('word')){
                            this.upexp_pre = this.url + '/assets/img/word.png';
                        } else if(file.type.includes('pdf')){
                            this.upexp_pre = this.url + '/assets/img/pdf.png';
                        }else {
                            this.upexp_pre = this.url + '/assets/img/document.png';
                        }
                    };
                reader.readAsDataURL(file);
            }
        },
        onSelectDocForm(e){
            // console.log(e);
            var file = e.target.files[0];
            if (file) {
                this.DocForm.doc_form = file;
                var reader = new FileReader();
                    reader.onloadend = () => {
                        if (file.type.includes('image')) {
                            this.upform_pre = reader.result;
                        } else if(file.type.includes('word')){
                            this.upform_pre = this.url + '/assets/img/word.png';
                        } else if(file.type.includes('pdf')){
                            this.upform_pre = this.url + '/assets/img/pdf.png';
                        }else {
                            this.upform_pre = this.url + '/assets/img/document.png';
                        }
                    };
                reader.readAsDataURL(file);
            }
        },
        AddDoc(){
            this.FormType = true
            // reset DocForm
            this.DocForm = {
                cat_id: this.catid,
                doc_name: '',
                image: '',
                doc_example: '',
                doc_form:'',
                notice:''
            }
            // reset preview
            this.upimg_pre = window.location.origin + '/assets/img/photo.png'
            this.upexp_pre = window.location.origin + '/assets/img/upload.png'
            this.upform_pre = window.location.origin + '/assets/img/upload.png'

            var modal = new bootstrap.Modal(document.getElementById('docm'), {
                keyboard: false
            });
            modal.show();
        },
        SaveDoc(){

            if(this.FormType){
                // Add Doc
            
                this.AddData('doc/add',this.DocForm,result=>{
                    if(result.success){
                        this.$refs.close_docm.click()
                        this.GetDoc()
                    }
                });

            } else {
                // Update Doc
                this.UpdateData('doc/update/'+this.EditID,this.DocForm,result=>{
                    if(result.success){
                        this.$refs.close_docm.click()
                        this.GetDoc()
                    }
                });
            }

        },
        EditDoc(id){
            this.EditData('doc/edit',id,result=>{
                // if(result.success){
                    this.DocForm = result
                    this.FormType = false
                    this.EditID = id
                    var modal = new bootstrap.Modal(document.getElementById('docm'), {
                        keyboard: false
                    });
                    modal.show();

                    // show image, doc_example, doc_form
                    if(result.image){
                        
                        // check if jpg, jpeg, png include in result.image

                        if (result.image.includes('jpg') || result.image.includes('jpeg') || result.image.includes('png')) {
                            this.upimg_pre = this.url + '/assets/file/' + result.image;
                        } else if (result.image.includes('doc') || result.image.includes('docx')) {
                            this.upimg_pre = this.url + '/assets/img/word.png';
                        } else if (result.image.includes('pdf')) {
                            this.upimg_pre = this.url + '/assets/img/pdf.png';
                        } else {
                            this.upimg_pre = this.url + '/assets/img/document.png';
                        }
                        
        
                    } else {
                        this.upimg_pre = this.url + '/assets/img/photo.png';
                    }

                    if(result.doc_example){
                        // check if jpg, jpeg, png include in result.image
                        if (result.doc_example.includes('jpg') || result.doc_example.includes('jpeg') || result.doc_example.includes('png')) {
                            this.upexp_pre = this.url + '/assets/file/'+result.doc_example
                        } else if (result.doc_example.includes('doc') || result.doc_example.includes('docx')) {
                            this.upexp_pre = this.url + '/assets/img/word.png';
                        } else if (result.doc_example.includes('pdf')) {
                            this.upexp_pre = this.url + '/assets/img/pdf.png';
                        } else {
                            this.upexp_pre = this.url + '/assets/img/document.png';
                        }
                        
                    } else {
                        this.upexp_pre = this.url + '/assets/img/upload.png';
                    }

                    if(result.doc_form){
                      // check if jpg, jpeg, png include in result.image
                        if (result.doc_form.includes('jpg') || result.doc_form.includes('jpeg') || result.doc_form.includes('png')) {
                            this.upform_pre = this.url + '/assets/file/'+result.doc_form
                        } else if (result.doc_form.includes('doc') || result.doc_form.includes('docx')) {
                            this.upform_pre = this.url + '/assets/img/word.png';
                        } else if (result.doc_form.includes('pdf')) {
                            this.upform_pre = this.url + '/assets/img/pdf.png';
                        } else {
                            this.upform_pre = this.url + '/assets/img/document.png';
                        }
                        
                    } else {
                        this.upform_pre = this.url + '/assets/img/upload.png';
                    }
                // }
            });
        },
        DeleteDoc(id){
            this.DeleteData('doc/delete',id,result=>{
                if(result.success){
                    this.GetDoc()
                }
            });
        
        },
        GetDoc(){
            this.GetData(`doc/get/${this.catid}?search=${this.Search}`,result=>{
                this.DocData = result
            });
        }
        
    },
    watch: {
        catid: function(val){
            // console.log(val);
            if(val != null){
                this.GetDoc()
            }
        },
        Search: function(val){
            if(val==''){
                this.GetDoc()
            }
        }

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
 .x-icon{
    position: absolute;
    left: 150px;
    font-size: 40px;
    cursor: pointer;
 }
</style>