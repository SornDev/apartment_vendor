<template lang="">
    <div class="row">
        <div class="col-md-4">
            
            <div class="card text-start">
                
                <div class="card-body" style="position: relative;">
                   
                        
                        <div class="card-title d-flex">
                                <h5 class="mb-0">ປະເພດຜູ້ໃຊ້</h5>
                                <div v-if="loading_table"  class="spinner-grow spinner-grow-sm text-warning ms-2" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
                        </div>
                        <button
                        @click="AddRole()"
                            type="button"
                            class="btn rounded-pill btn-icon btn-primary"
                            style="position: absolute; top: 12px; right: 12px;"
                        >
                            <i class='bx bx-plus fs-3'></i>
                        </button>
                        
                 
                    
                     <div class="demo-inline-spacing mt-3">
          <ul class="list-group"> 
            <li v-for="item in RoleData" :key="item.id" class="list-group-item d-flex align-items-center justify-content-between">
             <span>{{item.role_name}}</span>
              <div>
               <!-- dropdown menu -->
                <div class="dropdown">
                  <button
                    class="btn btn-icon btn-icon-only"
                    type="button"
                    id="dropdownMenuButton1"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                  >
                    <i class='bx bx-dots-vertical-rounded fs-4'></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" @click="EditRole(item.id)">
                            <i class='bx bx-edit fs-5'></i> ແກ້ໄຂ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" @click="DeleteRole(item.id)">
                            <i class='bx bx-trash fs-5'></i> ລຶບ
                            </a>
                        </li>
                    </ul>
                </div>

              </div>
            </li>
            
          </ul>
        </div>
                </div>
            </div>
            
            
           
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                <div class="col-md-12 col-sm-12 col-xl-6">

               
                 <div class="input-group mb-2">
                                                        <label class="input-group-text" >ເລຶອກປະເພດຜູ້ໃຊ້:</label>
                                                        <select class="form-select" v-model="RoleSelect" @change="OnchangRole()" >
                                                            <option selected="" value="">ເລືອກ...</option>
                                                            <option :value="item.id" v-for="item in RoleData" :key="item.id" > {{item.role_name}} </option>
                                                        </select>
                                                    </div>
                                                     </div>
                                               
                <table class="table border">
                    <thead>
                        <tr class=" text-primary">
                            <th scope="col" width="180">ພາກສ່ວນໃຫ້ເຂົ້າເຖິງ</th>
                            <th scope="col">ກຳນົດສິດ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="">
                            <td >ລາຍການເອກະສານ</td>
                            <td>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="accdoc" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('DOC_ACC')" role="switch" id="accdoc">
                                    <label class="form-check-label" for="accdoc"> ສິດເຂົ້າເຖິງ </label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="editdoc" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('DOC_ACC_EDIT')" role="switch" id="editdoc">
                                    <label class="form-check-label" for="editdoc"> ສິດເຂົ້າເຖິງ ການເພີ່ມ ແລະ ອັບເດດ </label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="deldoc" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('DOC_ACC_DEL')" role="switch" id="deldoc">
                                    <label class="form-check-label" for="deldoc"> ສິດເຂົ້າເຖິງ ການລຶບ </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="">
                            <td >ໃບບິນ</td>
                            <td>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="accrec" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('REC_ACC')" role="switch" id="accrec">
                                    <label class="form-check-label" for="accrec"> ສິດເຂົ້າເຖິງ </label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="editrec" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('REC_ACC_EDIT')" role="switch" id="editrec">
                                    <label class="form-check-label" for="editrec"> ສິດເຂົ້າເຖິງ ການເພີ່ມ ແລະ ອັບເດດ </label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="delrec" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('REC_ACC_DEL')" role="switch" id="delrec">
                                    <label class="form-check-label" for="delrec"> ສິດເຂົ້າເຖິງ ການລຶບ </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="">
                            <td >ບັນຊີ</td>
                            <td>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="accacc" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('ACC_ACC')" role="switch" id="accacc">
                                    <label class="form-check-label" for="accacc"> ສິດເຂົ້າເຖິງ </label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="editacc" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('ACC_REJECT')" role="switch" id="editacc">
                                    <label class="form-check-label" for="editacc"> ສິດເຂົ້າເຖິງ ການເພີ່ມ ແລະ ອັບເດດ </label>
                                </div>
                               
                            </td>
                        </tr>
                        <tr class="">
                            <td >ຈັດການຂໍ້ມູນລູກຄ້າ</td>
                            <td>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="acccus" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('CUS_ACC')" role="switch" id="acccus">
                                    <label class="form-check-label" for="acccus"> ສິດເຂົ້າເຖິງ </label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="editcus" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('CUS_ACC_EDIT')" role="switch" id="editcus">
                                    <label class="form-check-label" for="editcus"> ສິດເຂົ້າເຖິງ ການເພີ່ມ ແລະ ອັບເດດ </label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="delcus" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('CUS_ACC_DEL')" role="switch" id="delcus">
                                    <label class="form-check-label" for="delcus"> ສິດເຂົ້າເຖິງ ການລຶບ </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="">
                            <td >ຈັດການຂໍ້ມູຜູ້ໃຊ້</td>
                            <td>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="accuser" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('USER_ACC')" role="switch" id="accuser">
                                    <label class="form-check-label" for="accuser"> ສິດເຂົ້າເຖິງ </label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="edituser" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('USER_ACC_EDIT')" role="switch" id="edituser">
                                    <label class="form-check-label" for="edituser"> ສິດເຂົ້າເຖິງ ການເພີ່ມ ແລະ ອັບເດດ </label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="deluser" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('USER_ACC_DEL')" role="switch" id="delusers">
                                    <label class="form-check-label" for="delusers"> ສິດເຂົ້າເຖິງ ການລຶບ </label>
                                </div>
                            </td>
                        </tr>

                        <tr class="">
                            <td >ຈັດການຮ່າງເອກະສານ</td>
                            <td>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="accdocmg" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('DOCMG_ACC')" role="switch" id="accdocmg">
                                    <label class="form-check-label" for="accdocmg"> ສິດເຂົ້າເຖິງ</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="editdocmg" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('DOCMG_ACC_EDIT')" role="switch" id="editdocmg">
                                    <label class="form-check-label" for="editdocmg"> ສິດເຂົ້າເຖິງ ການເພີ່ມ ແລະ ອັບເດດ </label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="deldocmg" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('DOCMG_ACC_DEL')" role="switch" id="deldocmg">
                                    <label class="form-check-label" for="deldocmg"> ສິດເຂົ້າເຖິງ ການລຶບ </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="">
                            <td >ການຕັ້ງຄ່າລະບົບ</td>
                            <td>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="setting" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('SET_ACC')" role="switch" id="setting">
                                    <label class="form-check-label" for="setting"> ສິດເຂົ້າເຖິງ ການຕັ້ງຄ່າລະບົບ</label>
                                </div>
                            </td>
                        </tr>
                         <tr class="">
                            <td >ຈັດການສິດຜູ້ໃຊ້</td>
                            <td>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="roles" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('SET_ROLES')" role="switch" id="roles">
                                    <label class="form-check-label" for="roles"> ສິດເຂົ້າເຖິງ ຈັດການສິດຜູ້ໃຊ້</label>
                                </div>
                            </td>
                        </tr>
                        <tr class="">
                            <td >ລະບົບລາຍງານ</td>
                            <td>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="rpinex" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('RP_ACC_IE')" role="switch" id="rpinex">
                                    <label class="form-check-label" for="rpinex"> ສິດເຂົ້າເຖິງ ລາຍງານ ລາຍຮັບ-ລາຍຈ່າຍ</label>
                                </div>
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input me-2" v-model="rpcashf" type="checkbox" :disabled="!RoleSelect" @click="SelectRoles('RP_ACC_CAS')" role="switch" id="rpcashf">
                                    <label class="form-check-label" for="rpcashf"> ສິດເຂົ້າເຖິງ ລາຍງານ ຕິດຕາມເງິນສົດ </label>
                                </div>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
                </div>
        </div>
    </div>


            
    </div>


    <div class="modal modal-top modal-md fade" data-bs-backdrop="static" id="role-form" tabindex="-1" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
              <form class="modal-content">
                <div class="modal-header">
                  <!-- <h5 class="modal-title" id="modalTopTitle">Modal title</h5> -->
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="close_role_form"></button>
                </div>
                <div class="modal-body pt-0"> 
          
                
                   <div class="row mb-2">
                    <div class="col-md-12">
                        <label for="f-name" class="fs-6">ຊື່ປະເພດຜູ້ໃຊ້:</label>
                        <input class="form-control" type="text" id="f-name" v-model="RoleForm.role_name" placeholder="...">
                    </div>
                  </div>
                  <div class=" d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-primary me-2" :disabled="check_form" @click="SaveRole()" >
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
import mixins from '../mixins/ulmixins'
import { useStore } from '../Store/auth'
export default {
    setup() {
        const store = useStore()
        return {
            store
        }
    },
    mixins: [mixins],
    data() {
        return {
           RoleData:[],
           RoleSeclect:null,
           RoleForm:{
            role_name:'',
            permission_access:'',
           },
           accdoc:false,
           editdoc:false,
           deldoc:false,
           accrec:false,
           editrec:false,
           delrec:false,
           accacc:false,
           editacc:false,
           acccus:false,
           editcus:false,
           delcus:false,
           accuser:false,
           edituser:false,
           deluser:false,
           accdocmg:false,
           editdocmg:false,
           deldocmg:false,
           setting:false,
           roles:false,
           rpinex:false,
           rpcashf:false,
           RolesPermission:[],
           RoleSelect:'',
        };
    },
    computed: {
        check_form(){
            if(this.RoleForm.role_name == ''){
                return true;
            }
            return false;
        }
    },
    mounted() {
    },
    methods: {
        SelectRoles(valu){
            if(this.RolesPermission.includes(valu)){
                this.RolesPermission = this.RolesPermission.filter(item => item != valu)
            } else {
                this.RolesPermission.push(valu)
            }

            let item = this.RoleData.find(i => i.id == this.RoleSelect)

            // console.log(this.RolesPermission)
            this.RoleForm.permission_access = JSON.stringify(this.RolesPermission)
            this.RoleForm.role_name = item.role_name
            this.EditID = item.id
            this.FormType = false
            this.SaveRole()
        },
        OnchangRole(){
        // set permission_access from UserRoleData where id = RoleSelect
        // console.log(this.UserRoleData);
        // console.log(this.RoleSelect);
        // this.UserRoleData to json


        // this.RolesPermission = idata.find(i => i.id == this.RoleSelect).permission_access
        
        let item = this.RoleData.find(i => i.id == this.RoleSelect);
        // console.log(item);
        if(item){
            if(item.permission_access!=null){
            this.RolesPermission = JSON.parse(item.permission_access)
        } else {
            this.RolesPermission = []
        }
        } else {
            this.RolesPermission = []
        }
        
        // console.log(item);
                this.accdoc = this.RolesPermission.includes('DOC_ACC')
                this.editdoc = this.RolesPermission.includes('DOC_ACC_EDIT')
                this.deldoc = this.RolesPermission.includes('DOC_ACC_DEL')
                this.accrec = this.RolesPermission.includes('REC_ACC')
                this.editrec = this.RolesPermission.includes('REC_ACC_EDIT')
                this.delrec = this.RolesPermission.includes('REC_ACC_DEL')
                this.acccus = this.RolesPermission.includes('CUS_ACC')
                this.editcus = this.RolesPermission.includes('CUS_ACC_EDIT')
                this.delcus = this.RolesPermission.includes('CUS_ACC_DEL')
                this.accacc = this.RolesPermission.includes('ACC_ACC')
                this.editacc = this.RolesPermission.includes('ACC_REJECT')
                this.accuser = this.RolesPermission.includes('USER_ACC')
                this.edituser = this.RolesPermission.includes('USER_ACC_EDIT')
                this.deluser = this.RolesPermission.includes('USER_ACC_DEL')
                this.accdocmg = this.RolesPermission.includes('DOCMG_ACC')
                this.editdocmg = this.RolesPermission.includes('DOCMG_ACC_EDIT')
                this.deldocmg = this.RolesPermission.includes('DOCMG_ACC_DEL')
                this.setting = this.RolesPermission.includes('SET_ACC')
                this.roles = this.RolesPermission.includes('SET_ROLES')
                this.rpinex = this.RolesPermission.includes('RP_ACC_IE')
                this.rpcashf = this.RolesPermission.includes('RP_ACC_CAS')
  
            

       },
        AddRole(){
            this.RoleForm = {
                role_name:'',
                permission_access:'',
            }
            this.FormType = true

            var modal = new bootstrap.Modal(document.getElementById('role-form'), {
                keyboard: false
            });
            modal.show();

        },
        EditRole(id){
            this.EditID = id
            this.FormType = false
            this.GetData(`roles/edit/${id}`, result=>{
                this.RoleForm = result;
                var modal = new bootstrap.Modal(document.getElementById('role-form'), {
                    keyboard: false
                });
                modal.show();
            })
        },
        DeleteRole(id){
            this.DeleteData(`roles/delete`,id, result=>{
                this.getRole();
            })
        },
        SaveRole(){
            if(this.FormType){
                this.AddData(`roles/add`, this.RoleForm, result=>{
                    this.getRole();
                    this.$refs.close_role_form.click();
                })
            }else{
                this.UpdateData(`roles/update/${this.EditID}`, this.RoleForm, result=>{

                    // console.log(this.RoleSelect)
                    // console.log(this.RolesPermission)
                    // console.log(JSON.parse(this.store.get_user).id);

                    if(this.RoleSelect == JSON.parse(this.store.get_user).id){
                        localStorage.setItem('web_permission', JSON.stringify(this.RolesPermission));
                        // this.store.set_permissions = JSON.parse(this.RolesPermission)
                    }

                    this.getRole();
                    this.$refs.close_role_form.click();
                })
            }
        },
        getRole(){
            this.GetData(`roles/get`, result=>{
                this.RoleData = result;
            })
        }
    },
    created() {
        this.getRole();
    }
};
</script>
<style lang="">
</style>