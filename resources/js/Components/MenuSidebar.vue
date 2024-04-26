<template>
    <div>
        
<aside id="layout-menu" @mouseover="mover()" @mouseout="mout()" class="layout-menu menu-vertical menu bg-menu-theme h-100">
<PerfectScrollbar>
  
<div class="app-brand d-flex justify-content-center mt-2">
  <router-link to="/" class="app-brand-link">
    <span class="app-brand-logo" v-if="store.get_setting">
      <img :src="url+'/assets/img/'+JSON.parse(store.get_setting).company_logo" v-if="JSON.parse(store.get_setting).company_logo" alt="brand logo" class="img-fluid" style="width:120px">
</span>
    
  </router-link>
  
  <!-- <a href="javascript:void(0);" @click="setmenu()"  class="layout-menu-toggle menu-link text-large ms-auto d-block">
    <i class="bx bx-chevron-left bx-sm align-middle"></i>
  </a> -->
  
</div>
<div class="text-center">
  <span class="app-brand-text demo menu-text fw-bold ms-2 text-center">
  {{store.get_setting?JSON.parse(store.get_setting).company_name:'DMS'}}
</span>
</div>

<div class="menu-inner-shadow"></div>

<ul class="menu-inner py-1">
  <!-- Dashboards -->

  <li class="menu-header small text-uppercase m-0">
      <span class="menu-header-text">ເມນູຫຼັກ</span>
    </li>

  <li class="menu-item" :class="$route.path=='/dashboard'?' active':''"> 
    <router-link to="/dashboard" class="menu-link">
      <i class='bx bxs-dashboard menu-icon'></i>
      <div class="text-truncate" >ດາດສ໌ບອດ</div>
    </router-link>
    
  </li>


  <li class="menu-item"  :class="$route.path=='/pre-doc'?' active':''" v-if="store.get_permissions.includes('DOC_ACC')||JSON.parse(store.get_user).user_type=='admin'" >
    <router-link to="/pre-doc"  class="menu-link" >
      <i class='bx bxs-file-doc menu-icon'></i>
      <div class="text-truncate" >ຮ່າງ-ແບບເອກະສານ</div>
    </router-link>
    
  </li>

  <li class="menu-item" :class="$route.path=='/tran-doc'?' active':''" v-if="store.get_permissions.includes('DOC_ACC')||JSON.parse(store.get_user).user_type=='admin'">
    <router-link to="/tran-doc" class="menu-link">
      <i class='bx bxs-book-content menu-icon'></i>
      <div class="text-truncate " >ລາຍການເອກະສານ</div>
    </router-link>
  </li>

  <li class="menu-item" :class="$route.path=='/rec'?' active':''" v-if="store.get_permissions.includes('REC_ACC')||JSON.parse(store.get_user).user_type=='admin'">
    <router-link to="/rec" class="menu-link">
      <i class='bx bxs-receipt menu-icon'></i>
      <div class="text-truncate " >ໃບບິນ</div>
    </router-link>
    
  </li>

  <!-- <li class="menu-item" :class="$route.path=='/quo'?' active':''">
    <router-link to="/quo" class="menu-link">
      <i class='bx bxs-calendar-event menu-icon'></i>
      <div class="text-truncate" >ໃບສະເໜີລາຄາ</div>
    </router-link>
    
  </li> -->

  <li class="menu-item" :class="$route.path=='/acc'?' active':''" v-if="store.get_permissions.includes('ACC_ACC')||JSON.parse(store.get_user).user_type=='admin'">
    <router-link to="/acc" class="menu-link">
      <i class='bx bxs-dollar-circle menu-icon'></i>
      <div class="text-truncate " >ບັນຊີ</div>
    </router-link>
    
  </li> 

  <li class="menu-item" :class="$route.path=='/cus'?' active':''" v-if="store.get_permissions.includes('CUS_ACC')||JSON.parse(store.get_user).user_type=='admin'">
    <router-link to="/cus" class="menu-link">
      <i class='bx bxs-user-detail menu-icon'></i>
      <div class="text-truncate " >ຂໍ້ມູນລູກຄ້າ</div>
    </router-link>
    
  </li>


  <li class="menu-header small text-uppercase m-0" v-if="store.get_permissions.includes('SET_ACC')||store.get_permissions.includes('DOCMG_ACC_EDIT')||store.get_permissions.includes('USER_ACC')||store.get_permissions.includes('SET_ROLES')||JSON.parse(store.get_user).user_type=='admin'">
      <span class="menu-header-text">ການຕັ້ງຄ່າ</span>
    </li>


<li class="menu-item" :class="$route.path=='/setting'?' active':''" v-if="store.get_permissions.includes('SET_ACC')||JSON.parse(store.get_user).user_type=='admin'">
    <router-link to="/setting" class="menu-link">
      <i class='bx bxs-cog menu-icon'></i>
      <div class="text-truncate " >ຕັ້ງຄ່າລະບົບ</div>
    </router-link>
    
  </li>

  <li class="menu-item" :class="$route.path=='/predoc-mg'?' active':''" v-if="store.get_permissions.includes('DOCMG_ACC_EDIT')||JSON.parse(store.get_user).user_type=='admin'">
    <router-link to="/predoc-mg" class="menu-link">
      <i class='bx bxs-calendar-edit menu-icon'></i>
      <div class="text-truncate " >ຈັດການ ຮ່າງ-ແບບເອກະສານ</div>
    </router-link>
    
  </li>

  <li class="menu-item" :class="$route.path=='/user-mg'?' active':''" v-if="store.get_permissions.includes('USER_ACC')||JSON.parse(store.get_user).user_type=='admin'">
    <router-link to="/user-mg" class="menu-link">
      <i class='bx bxs-user-circle menu-icon'></i>
      <div class="text-truncate " >ຈັດການ ຜູ້ໃຊ້</div>
    </router-link>
    
  </li>

  <li class="menu-item" :class="$route.path=='/user-per'?' active':''" v-if="store.get_permissions.includes('SET_ROLES')||JSON.parse(store.get_user).user_type=='admin'">
    <router-link to="/user-per" class="menu-link">
      <i class='bx bxs-user-check menu-icon'></i>
      <div class="text-truncate " >ຈັດການ ສິດຜູ້ໃຊ້</div>
    </router-link>
  </li>

    <li class="menu-header small text-uppercase m-0" v-if="store.get_permissions.includes('RP_ACC_IE')||store.get_permissions.includes('RP_ACC_CAS')||JSON.parse(store.get_user).user_type=='admin'">
      <span class="menu-header-text">ລາຍງານ</span>
    </li>


<li class="menu-item" :class="$route.path=='/inc-exp'?' active':''" v-if="store.get_permissions.includes('RP_ACC_IE')||JSON.parse(store.get_user).user_type=='admin'">
    <router-link to="/inc-exp" class="menu-link">
      <i class='bx bxs-notepad menu-icon'></i>
      <div class="text-truncate" >ລາຍຮັບ-ລາຍຈ່າຍ</div>
    </router-link>
    
  </li>

  <li class="menu-item" :class="$route.path=='/rev'?' active':''" v-if="store.get_permissions.includes('RP_ACC_CAS')||JSON.parse(store.get_user).user_type=='admin'">
    <router-link to="/rev" class="menu-link">
      <i class='bx bxs-wallet-alt menu-icon'></i>
      <div class="text-truncate" >ຕິດຕາມເງິນສົດ</div>
    </router-link>
    
  </li>

</ul>

</PerfectScrollbar>

</aside>
    </div>
</template>

<script>

import { useStore } from '../Store/auth';

    // ຂຽນ if else ແບບຫຍໍ້
    // $route.path=='/pos'?' active':''

    // ຂຽນແບບເຕັມ
    // let class_name = '';
    // if($route.path=='/pos'){
    //     class_name = 'active'
    // } else {
    //     class_name = ''
    // }


    // let name = '';
    // if($route.path=='/pos')
    // return name = 'active'
    // name = ''

export default {
    setup() {
        const store = useStore();
        return { store };
    },
    name: 'Minipos14MenuSidebar',

    data() {
        return {
            menu_status:'big',
            url: window.location.origin,
        };
    },
    computed:{
    }
     ,

    mounted() {
        
    },

    methods: {
        setmenu(){
          console.log(this.menu_status);
            if(this.menu_status=='big'){
                this.menu_status = 'small';
                let layout = document.body;
                layout.classList.add('light-style', 'layout-compact', 'layout-menu-fixed', 'layout-menu-collapsed');
            } else {
                this.menu_status = 'big';
                let layout = document.body;
                layout.classList.remove('light-style', 'layout-compact', 'layout-menu-fixed', 'layout-menu-collapsed','layout-menu-hover');
            }
        },
        mover(){
            if(this.menu_status=='small'){
                let layout = document.body;
                layout.classList.add('layout-menu-hover');
            }
        },
        mout(){
            if(this.menu_status=='small'){
                let layout = document.body;
                layout.classList.remove('layout-menu-hover');
            }
        }
    },
};
</script>

<style lang="scss" scoped>

</style>