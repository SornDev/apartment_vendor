
<template lang="">
    
    <div class="row" v-if="window.width>425">
  <div class="col-6 col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
  
      <div class="d-flex align-items-center mb-2 pb-1 justify-content-between">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class='bx bx-notepad fs-3'></i></span>
          </div>
          <h4 class="ms-1 mb-0">{{formatPrice(DocWork[1])}}</h4>
        </div>
        <p class="mb-1">ເອກະສານ ເດືອນນີ້: {{formatPrice(DocWork[2])}}</p>
        <p class="mb-0" :class="DocWork[4]>0?'text-success':'text-danger'">
          <span class="fw-medium me-1">{{DocWork[4]}}%</span>
          <small class="text-muted">{{DocWork[5]}}</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1 justify-content-between">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-info-circle fs-3'></i></span>
          </div>
          <h4 class="ms-1 mb-0">{{formatPrice(DocPadding[1])}}</h4>
        </div>
        <p class="mb-1">ລໍດຳເນີນການ ເດືອນນິ້: {{DocPadding[2]}}</p>
        <p class="mb-0" :class="DocPadding[4]>0?'text-success':'text-danger'">
          <span class="fw-medium me-1">{{DocPadding[4]}}%</span>
          <small class="text-muted">{{DocPadding[5]}}</small>
        </p>
      </div>
    </div>
  </div>
  
  <div class="col-6 col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1 justify-content-between ">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class='bx bx-task fs-3'></i></span>
          </div>
          <h4 class="ms-1 mb-0">{{formatPrice(Income[1])}} ₭</h4>
        </div>
        <p class="mb-1">ລາຍຮັບເດືອນນີ້: {{formatPrice(Income[2])}} ₭</p>
        <p class="mb-0" :class="Income[4]>0?'text-success':'text-danger'">
          <span class="fw-medium me-1">{{Income[4]}}%</span>
          <small class="text-muted">{{Income[5]}}</small>
        </p>
      </div>
    </div>
  </div>

  <div class="col-6 col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1 justify-content-between">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-shield-minus fs-3'></i></span>
          </div>
          <h4 class="ms-1 mb-0">{{formatPrice(Expense[1])}} ₭</h4>
        </div>
        <p class="mb-1">ລາຍຈ່າຍເດືອນນີ້: {{formatPrice(Expense[2])}} ₭</p>
        <p class="mb-0" :class="Expense[4]>0?'text-success':'text-danger'">
          <span class="fw-medium me-1">{{Expense[4]}}%</span>
          <small class="text-muted">{{Expense[5]}}</small>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Mobile  -->

<div class="row"  v-else>
  <div class="col-6 col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body p-2">
  
      <div class="d-flex align-items-center mb-2 pb-1 justify-content-between">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class='bx bx-notepad fs-3'></i></span>
          </div>
          <h5 class="ms-1 mb-0">{{formatPrice(DocWork[1])}}</h5>
        </div>
        <p class="mb-1 fs-6">ເອກະສານ ເດືອນນີ້: {{formatPrice(DocWork[2])}}</p>
        <p class="mb-0" :class="DocWork[4]>0?'text-success':'text-danger'">
          <span class="fw-medium me-1">{{DocWork[4]}}%</span>
          <small class="text-muted">{{DocWork[5]}}</small>
        </p>
      </div>
    </div>
  </div>
  <div class="col-6 col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body p-2">
        <div class="d-flex align-items-center mb-2 pb-1 justify-content-between">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-info-circle fs-3'></i></span>
          </div>
          <h5 class="ms-1 mb-0">{{formatPrice(DocPadding[1])}}</h5>
        </div>
        <p class="mb-1 fs-6">ລໍດຳເນີນການ ເດືອນນິ້: {{DocPadding[2]}}</p>
        <p class="mb-0" :class="DocPadding[4]>0?'text-success':'text-danger'">
          <span class="fw-medium me-1">{{DocPadding[4]}}%</span>
          <small class="text-muted">{{DocPadding[5]}}</small>
        </p>
      </div>
    </div>
  </div>
  
  <div class="col-6 col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body p-2">
        <div class="d-flex align-items-center mb-2 pb-1 justify-content-between ">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class='bx bx-task fs-3'></i></span>
          </div>
          <h5 class="ms-1 mb-0">{{formatPrice(Income[1])}} ₭</h5>
        </div>
        <p class="mb-1 fs-6">ລາຍຮັບເດືອນນີ້: {{formatPrice(Income[2])}} ₭</p>
        <p class="mb-0" :class="Income[4]>0?'text-success':'text-danger'">
          <span class="fw-medium me-1">{{Income[4]}}%</span>
          <small class="text-muted">{{Income[5]}}</small>
        </p>
      </div>
    </div>
  </div>

  <div class="col-6 col-sm-6 col-lg-3 mb-4">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body p-2">
        <div class="d-flex align-items-center mb-2 pb-1 justify-content-between">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger"><i class='bx bx-shield-minus fs-3'></i></span>
          </div>
          <h5 class="ms-1 mb-0">{{formatPrice(Expense[1])}} ₭</h5>
        </div>
        <p class="mb-1 fs-6">ລາຍຈ່າຍເດືອນນີ້: {{formatPrice(Expense[2])}} ₭</p>
        <p class="mb-0" :class="Expense[4]>0?'text-success':'text-danger'">
          <span class="fw-medium me-1">{{Expense[4]}}%</span>
          <small class="text-muted">{{Expense[5]}}</small>
        </p>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import mixins from '../../mixins/ulmixins'
export default {
    mixins: [mixins],
    data() {
        return {
            DocWork:[0,0,0,0,0,''],
            DocPadding:[0,0,0,0,0,''],
            Income:[0,0,0,0,0,''],
            Expense:[0,0,0,0,0,''],
            window: {
                width: 0,
                height: 0
            }
        }
    },
    methods: {
      handleResize() {
            this.window.width = window.innerWidth;
            this.window.height = window.innerHeight;
        },
        getAllData(){
            this.GetData(`gettran/dashtop`,result=>{
                this.DocWork = result.DocWork
                this.DocPadding = result.DocPadding
                this.Income = result.Income
                this.Expense = result.Expense
            })
        }
    },
     created() {
        // this.getAllData()
        // this.handleResize()
    },
}
</script>
<style lang="">
    
</style>