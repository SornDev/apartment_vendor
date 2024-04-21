<template>
    <div class="row">
        <div class="col-md-8">
            <div class="card card-action mb-4">
                <div class="card-header align-items-center">
                    <h5 class="card-action-title mb-0">ຕັ້ງຄ່າລະບົບ </h5>
                    <div class="card-action-element">
                    <button class="btn btn-primary btn-md " type="button" @click="UpdateDataSetting()" >ບັນທຶກ</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                            <div class="col-12">
                                <label for="logo">ໂລໂກ້:</label>
                                <div class="d-flex justify-content-center">
                                <div class="img-upload" @click="$refs.file.click" v-if="!SettingData.company_logo">
                                            <i class='bx bx-image-add' ></i>
                                        </div>

                                    <input type="file" ref="file" style="display:none;" @change="onSelected">
                                            <div class="text-center cursur" v-if="SettingData.company_logo" style="position: relative;">
                                                
                                               
                                                <img class="img-thumbnail  avatar-xxl img-preview" @click="$refs.file.click()" v-if="SettingData.company_logo"  :src="imageLogoPreview" data-holder-rendered="true">

                                              

                                           <button type="button" class="btn rounded-pill btn-icon btn-danger" @click="rm_img()" style="position: absolute; right: 30px; top: 10px;">
                                            <i class='bx bx-trash fs-4'></i></button>
                                            </div>
                                </div>
                                
                            </div>
                                <div class="col-md-12 mb-2 mt-2">
                                    <label for="bs-name" class="form-label fs-6"> ຊື່ບໍລິສັດ:</label>
                                    <input type="text" class="form-control" id="bs-name" v-model="SettingData.company_name" placeholder="......" >
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="bs-tel" class="form-label fs-6"> ເບີໂທ:</label>
                                    <input type="text" class="form-control" id="bs-tel" v-model="SettingData.company_tel" placeholder="......" >
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="bs-tel" class="form-label fs-6"> ອີເມວລ໌:</label>
                                    <input type="text" class="form-control" id="bs-tel" v-model="SettingData.company_email" placeholder="......" >
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="bs-add" class="form-label fs-6"> ທີ່ຢູ່:</label>
                                    <input type="text" class="form-control" id="bs-add" v-model="SettingData.company_address" placeholder="......" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
          <div class="col-6">
            <span>
                 ຂະໜາດບິນເລີ່ມຕົ້ນ: 
            </span>
           <div class="d-flex"> 
                <div class="col-md">
          
                    <div class="form-check form-check-inline mt-3">
                    <input class="form-check-input" type="radio" name="printdefault" id="prta4" v-model="SettingData.printer_default" value="a4">
                    <label class="form-check-label" for="prta4">A4</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="printdefault" id="prt80" v-model="SettingData.printer_default" value="80">
                    <label class="form-check-label" for="prt80">80mm</label>
                    </div>
                
          </div>
           </div>
            
          </div>

          <div class="col-6">
            <label for="vt" class="form-label fs-6"> ອາກອນມູນຄ່າເພິ່ມ(ອມພ):</label>
                <div class="input-group" style=" width:100px;">
                <input type="text" class="form-control" v-model="SettingData.tax_value" placeholder="%" >
                <span class="input-group-text" id="vt">%</span>
                </div>
          </div>
          
        </div>
                        </div>
                    </div>
                    
                </div>
                </div>
        </div>
        <!-- <div class="col-md-6">
            <div class="card card-action mb-4">
      <div class="card-header align-items-center">
        <h5 class="card-action-title mb-0">ຕັ້ງຄ່າ ຂໍ້ມູນອື່ນໆ</h5>
        <div class="card-action-element">
          <button class="btn btn-primary btn-md" type="button" >ບັນທຶກ</button>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <span>
                 ຂະໜາດບິນເລີ່ມຕົ້ນ: 
            </span>
           <div class="d-flex"> 
                <div class="col-md">
          
                    <div class="form-check form-check-inline mt-3">
                    <input class="form-check-input" type="radio" name="printdefault" id="prta4" v-model="PrintDefault" value="a4">
                    <label class="form-check-label" for="prta4">A4</label>
                    </div>
                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="printdefault" id="prt80" v-model="PrintDefault" value="80">
                    <label class="form-check-label" for="prt80">80mm</label>
                    </div>
                
          </div>
           </div>
            
          </div>

          <div class="col-6">
            <label for="vt" class="form-label fs-6"> ອາກອນມູນຄ່າເພິ່ມ(ອມພ):</label>
                <input type="text" class="form-control" id="vt" placeholder="......" >
          </div>
          
        </div>
      </div>
    </div>
        </div> -->
    </div>
</template>

<script>
import mixins from '../mixins/ulmixins';
import { useStore } from '../Store/auth';
export default {
    name: 'DmsSetting',
    mixins: [mixins],
    setup() {
        const store = useStore();
        return { store };
    },
    data() {
        return {
            SettingData: {
                company_name: '',
                company_tel: '',
                company_address: '',
                company_email: '',
                company_logo: '',
                printer_default: '',
                tax_value:''
            },
            imageLogoPreview:'',
        };
    },

    mounted() {
        
    },

    methods: {
        onSelected(event) {
            this.SettingData.company_logo = event.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(this.SettingData.company_logo);
            reader.addEventListener(
                "load",
                function () {
                this.imageLogoPreview = reader.result;
                }.bind(this),
                false
            );
        },
        rm_img(){
            this.SettingData.company_logo = '';
            this.imageLogoPreview = '';
        },
        UpdateDataSetting(){
            this.UpdateData(`setting/update`,this.SettingData,result=>{
                
                if(result.success){
                    this.SettingData = result.setting;
                localStorage.setItem('web_setting',JSON.stringify(result.setting));
                this.store.set_setting(JSON.stringify(result.setting));
                this.imageLogoPreview = window.location.origin + '/assets/img/' + result.setting.company_logo;
                }
               
            })
        },
        GetDataSetting(){
            this.GetData(`setting/get`,result=>{
                this.SettingData = result;
                this.imageLogoPreview = window.location.origin + '/assets/img/' + result.company_logo;
                
            })
        }
    },
    created() {
        this.GetDataSetting();
    }
};
</script>

<style scoped>
    .img-upload{
        width: 130px;
        height: 130px;
        font-size: 70px;
        border: 4px #e7e5e5 dashed;
        text-align: center;
        padding: 20px;
        border-radius: 30%;
        margin: 20px;
        color: #cccbcb;
        position: relative;
    }
    .img-upload:hover{
        cursor: pointer;
        border: 4px #b3b1b1 dashed;
        color: #9c9a9a;
    }
    .img-preview{
        cursor: pointer;
    width: 280px;
    height: 200px;
    object-fit: cover;
    object-position: center;
    }

</style>