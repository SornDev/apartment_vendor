<template>
    <div class="card">
  <h5 class="card-header">ລາຍການໃບສະເໜີ ລາຄາ</h5>
  <div class="card-body">
    <div class="table-responsive text-nowrap">
        <div class=" d-flex justify-content-between mb-2">
            <div class=" d-flex justify-content-between align-items-center">
                <i class='bx bx-sort-up fs-4 me-2'></i>
                <select class="form-select me-2">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                </select>
                <select class="form-select me-2">
                    <option >ສະຖານະ</option>
                    <option value="20">ກຳລັງດຳເນີນການ</option>
                    <option value="30">ສຳເລັດ</option>
                </select>
            </div>
            <div class=" d-flex">
                <input type="text" class=" form-control me-2" placeholder="ຄົ້ນຫາ...">
                <button class="btn btn-primary">ເພີ່ມໃໝ່</button>
            </div>
        </div>
      <table class="table table-bordered">
        <thead>
          <tr class="table-info">
            <th class="fs-6 fw-bold">ເລກທີ່</th>
            <th class="fs-6 fw-bold">ວັນທີ່</th>
            <th class="fs-6 fw-bold">ລູກຄ້າ</th>
            <th class="fs-6 fw-bold">ສະຖານະ</th>
            <th class="fs-6 fw-bold">ຜູ້ບັນທຶກ</th>
            <th class="fs-6 fw-bold">ຈັດການ</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="list in 20" :key="list">
            <td>QU20142233{{list}}</td>
            <td>01/01/2024</td>
            <td> ທ່ານ ນ ສົມພອນ ແກ້ວປັນຍາ </td>
             <td> 
                <span class="badge bg-label-warning me-1">ດຳເນີນການ</span>
             </td>
             <td> admin </td>
            <td class="text-center">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                  <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                </div>
              </div>
            </td>
          </tr>
          
         
        </tbody>
      </table>
    </div>
  </div>
</div>
</template>

<script>
export default {
    name: 'DmsQou',

    data() {
        return {
            
        };
    },

    mounted() {
        
    },

    methods: {
        
    },
};
</script>

<style lang="scss" scoped>

</style>