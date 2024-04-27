<template>
  <div class="card">
    <div class="card-header d-flex">
        <h5 class="mb-0">ລາຍການໃບບິນ</h5>
        <div v-if="loading_table"  class="spinner-grow spinner-grow-sm text-warning ms-2" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
    </div>
    
    <div class="card-body">
      <div class="table-responsive text-nowrap ">
        <div class="d-flex justify-content-between mb-2">
          <div class="d-flex justify-content-between align-items-center">
            <div class="cursor-pointer" @click="ChangeSort()">
              <i class="bx bx-sort-up fs-4 me-2" v-if="Sort == 'asc'"></i>
              <i class="bx bx-sort-down fs-4 me-2" v-else></i>
            </div>
            <select
              class="form-select me-2"
              v-model="PerPage"
              @change="GetRec()"
            >
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="30">30</option>
            </select>
            <select
              class="form-select me-2"
              v-model="Status"
              @change="GetRec()"
            >
              <option value="">ສະຖານະ</option>
              <option value="padding">ກຳລັງດຳເນີນການ</option>
              <option value="success">ສຳເລັດ</option>
              <option value="reject">ຍົກເລີກ</option>
            </select>
          </div>
          <div class="d-flex">
            <input
              type="text"
              class="form-control me-2"
              v-model="Search"
              @keyup.enter="GetRec()"
              placeholder="ຄົ້ນຫາ..."
            />
            <button class="btn btn-primary" @click="AddRec()" v-if="store.get_permissions.includes('REC_ACC_EDIT')||JSON.parse(store.get_user).user_type=='admin'">ເພີ່ມໃໝ່</button>
          </div>
        </div>
        <table class="table table-bordered shadow-sm">
          <thead>
            <tr class="table-info">
              <th class="fs-6 fw-bold" width="140">ເລກທີ່</th>
              <th class="fs-6 fw-bold" width="122">ວັນທີ່</th>
              <th class="fs-6 fw-bold">ລູກຄ້າ</th>
              <th class="fs-6 fw-bold text-center" width="160">ລາຄາ</th>
              <th class="fs-6 fw-bold" width="120">ສະຖານະ</th>
              <th class="fs-6 fw-bold">ຜູ້ບັນທຶກ</th>
              <th class="fs-6 fw-bold" width="100" v-if="store.get_permissions.includes('REC_ACC_EDIT')||store.get_permissions.includes('REC_ACC_DEL')||JSON.parse(store.get_user).user_type=='admin'" >ຈັດການ</th>
            </tr>
          </thead>
          <tbody v-if="RecData.data.length>0">
            <tr v-for="list in RecData.data" :key="list.id">
              <td>{{ list.rec_id }}</td>
              <td>{{ date(list.created_at) }}</td>
              <td>{{ list.customer_name }}</td>
              <td class="text-end">{{ formatPrice(list.total_price) }} ກີບ</td>
              <td>
                <span
                  class="badge bg-label-success me-1"
                  v-if="list.status == 'success'"
                  >ຊຳລ່ະແລ້ວ</span
                >
                <span
                  class="badge bg-label-danger me-1"
                  v-if="list.status == 'reject'"
                  >ຍົກເລີກ</span
                >
                <span
                  class="badge bg-label-warning me-1"
                  v-if="list.status == 'padding'"
                  >ດຳເນີນການ</span
                >
              </td>
              <td>{{ list.user_name }}</td>

              <td class="text-center">
                <div class="dropdown">
                  <button
                    type="button"
                    class="btn p-0 dropdown-toggle hide-arrow"
                    data-bs-toggle="dropdown"
                  >
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu"> 
                  <a
                      class="dropdown-item"
                      href="javascript:void(0);"
                      @click="PtintBill(list.rec_id,JSON.parse(store.get_setting).printer_default)"
                      ><i class='bx bx-printer me-1'></i> ປຼິນ</a
                    >

                    <a
                      class="dropdown-item"
                      href="javascript:void(0);"
                      @click="EditRec(list.id)"
                      v-if="store.get_permissions.includes('REC_ACC_EDIT')||JSON.parse(store.get_user).user_type=='admin'"
                      ><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a
                    >
                    <a
                      class="dropdown-item"
                      href="javascript:void(0);"
                      @click="DelRec(list.id)"
                      v-if="store.get_permissions.includes('REC_ACC_DEL')||JSON.parse(store.get_user).user_type=='admin'"
                      ><i class="bx bx-trash me-1"></i> ຍົກເລີກ</a
                    >
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
        <tr>
            <td colspan="7" class="text-center"> <i class='bx bxs-data me-1' ></i> ບໍ່ມີຂໍ້ມູນ </td>
        </tr>
      </tbody>
        </table>
        <pagination
          :pagination="RecData"
          @paginate="GetRec($event)"
          :offset="4"
        />
      </div>
    </div>
  </div>

  <div class="modal modal-top modal-lg fade" id="rec_form" tabindex="-1" style="display: none" data-bs-backdrop="static" aria-hidden="true" >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <h5 class="modal-title" id="modalTopTitle">Modal title</h5> -->
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
            ref="close_rec_form"
          ></button>
        </div>
        <div class="modal-body pt-0">
          <div class="row mb-3">
            <div class="col-md-6 fs-5 fw-bold">
              <span
                >ໃບບິນເລກທີ່:
                <span class="text-danger">{{ RecForm.rec_id }}</span></span
              >
            </div>
            <div class="col-md-6 text-end">
              <button type="button" class="btn btn-warning me-2" @click="PtintBill(RecForm.rec_id,'quo')">
                <i class="bx bxs-printer"></i> ສະເໜີລາຄາ
              </button>
              <button type="button" class="btn btn-warning me-2" @click="PtintBill(RecForm.rec_id,'80')">
                <i class="bx bxs-printer"></i> 80mm
              </button>
              <button type="button" class="btn btn-warning" @click="PtintBill(RecForm.rec_id,'a4')">
                <i class="bx bxs-printer"></i> A4
              </button>
              
            </div>
          </div>
          <Transition name="slide-fade">
            <div v-if="rec_form" class="outer">
              <!-- <div class="inner"> -->
              <div class="row mb-2">
                <div class="col-md-12">
                  <label for="list-name" class="form-label fs-6"
                    >ຊື່ລາຍການ</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="list-name"
                    ref="list_name"
                    v-model="RecListFormEdit.rec_name"
                  />
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-md-2">
                  <label for="list-qty" class="form-label fs-6">ຈຳນວນ</label>
                  <cleave
                    :options="options"
                    class="form-control"
                    id="list-qty"
                    v-model="RecListFormEdit.qty"
                  />
                </div>
                <div class="col-md-3">
                  <label for="list-price" class="form-label fs-6">ລາຄາ</label>
                  <div class="input-group">
                    <cleave
                      :options="options"
                      class="form-control text-end"
                      id="list-price"
                      v-model="RecListFormEdit.price"
                    />
                    <span class="input-group-text">₭</span>
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-end mt-4">
                <button
                  type="button"
                  class="btn btn-primary me-2"
                  :disabled="check_rec_list_form"
                  @click="SaveRecList()"
                >
                  <span v-if="!loading_post">ບັນທຶກ</span>
                  <div v-else class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </button>
                <button
                  type="button"
                  class="btn btn-label-secondary"
                  @click="CancelRecList"
                >
                  ຍົກເລີກ
                </button>
              </div>

              <!-- </div> -->
            </div>
          </Transition>
          <Transition name="slide-fade">
            <div v-if="!rec_form" class="outer">
              <!-- <div class="inner"> -->
              <div class="row mb-2">
                <div class="col-md-6 d-flex justify-content-between">
                  <div v-if="RecForm.customer_id">
                    <span
                      >ລູກຄ້າ: {{ RecForm.customer_name }} ({{
                        RecForm.customer_tel
                      }})</span
                    ><br />
                    <span>ທີ່ຢູ່: {{ RecForm.customer_address }}</span>
                  </div>
                  <div v-else>ລູກຄ້າ: ບຸກຄົນທົ່ວໄປ</div>
                  <div class="btn-group">
                    <button
                      type="button"
                      class="btn btn-icon rounded-pill btn-primary"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                      @click="Csearch()"
                    >
                      <i class="bx bx-search"></i>
                    </button>
                    <div class="dropdown-menu" style="width: max-content">
                      <div class="p-2">
                        <input
                          type="text"
                          class="form-control rounded-pill"
                          placeholder="ຄົ້ນຫາລູກຄ້າ..."
                          v-model="SearchCus"
                          @keyup.enter="GetCus()"
                          ref="search_cus"
                        />
                      </div>
                      <a
                        v-for="list in CustomerData"
                        :key="list.id"
                        @click="
                          addCus(
                            list.id,
                            list.name,
                            list.last_name,
                            list.gender,
                            list.tel,
                            list.address
                          )
                        "
                        class="dropdown-item align-middle d-flex align-items-center justify-content-between cursor-pointer"
                      >
                        <span>
                          <span v-if="list.gender == 'female'">ນ</span>
                          {{ list.name }} {{ list.last_name }}</span
                        >
                        <i class="bx bxs-left-top-arrow-circle ms-2"></i>
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 text-end">
                  <button
                    type="button"
                    class="btn rounded-pill btn-icon btn-info me-2"
                    @click="AddRecList()"
                    v-if="
                      RecForm.status == 'padding' || RecForm.status == 'created'
                    "
                  >
                    <i class="bx bx-plus fs-5"></i>
                  </button>
                </div>
              </div>

              <div class="table-responsive text-nowrap">
                <!-- {{RecListForm}} -->
                <div class="text-end">
                  <span class="me-4 fs-6"
                    >ການຊຳລ່ະ:
                    <span
                      class="text-success"
                      v-if="RecForm.status == 'success'"
                      ><i class="bx bxs-check-shield fs-4"></i> ຊຳລ່ະແລ້ວ
                    </span>
                    <span class="text-danger" v-else
                      ><i class="bx bxs-shield-x fs-4"></i> ຍັງບໍ່ຊຳລ່ະ</span
                    >
                  </span>
                </div>
                <table
                  class="table table-bordered align-middle table-nowrap mb-0"
                >
                  <thead class="table-light">
                    <tr>
                      <th scope="col" width="50" class="text-center fs-6">
                        ລຳດັບ
                      </th>
                      <th scope="col" class="fs-6">ເນື້ອໃນລາຍການ</th>
                      <th scope="col" width="110" class="text-center fs-6">
                        ລາຄາ
                      </th>
                      <th scope="col" width="100" class="text-center fs-6">
                        ຈຳນວນ
                      </th>
                      <th scope="col" width="150" class="text-end fs-6">ລວມ</th>
                      <th
                        scope="col"
                        class="fs-6 text-center"
                        width="88"
                        v-if="
                          RecForm.status == 'padding' ||
                          RecForm.status == 'created'
                        "
                      >
                        ຈັດການ
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(list, index) in RecListForm" :key="list.id">
                      <td class="text-center">{{ index + 1 }}</td>
                      <td>{{ list.rec_name }}</td>
                      <td class="text-end">
                        {{ formatPrice(list.price) }}
                      </td>
                      <td class="text-center">
                        {{ formatPrice(list.qty) }}
                      </td>
                      <td class="text-end">
                        {{ formatPrice(list.price * list.qty) }}
                      </td>
                      <td
                        class="text-center"
                        v-if="
                          RecForm.status == 'padding' ||
                          RecForm.status == 'created'
                        "
                      >
                        <div class="dropdown">
                          <button
                            type="button"
                            class="btn p-0 dropdown-toggle hide-arrow"
                            data-bs-toggle="dropdown"
                          >
                            <i class="bx bx-dots-vertical-rounded"></i>
                          </button>
                          <div class="dropdown-menu">
                            <a
                              class="dropdown-item"
                              href="javascript:void(0);"
                              @click="EditRecList(list.id)"
                              ><i class="bx bx-edit-alt me-1"></i> ແກ້ໄຂ</a
                            >
                            <a
                              class="dropdown-item"
                              href="javascript:void(0);"
                              @click="DeleteRecList(list.id)"
                              ><i class="bx bx-trash me-1"></i> ລຶບ</a
                            >
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" class="text-end fs-6">ລວມທັງໝົດ</td>
                      <td class="text-center">{{ formatPrice(TotalQty) }}</td>
                      <td class="text-end">
                        {{ formatPrice(TotalSum) }}
                      </td>
                      <td
                        v-if="
                          RecForm.status == 'padding' ||
                          RecForm.status == 'created'
                        "
                      ></td>
                    </tr>
                    <tr>
                      <td colspan="4" class="text-end fs-6">
                        ສ່ວນຫຼຸດ
                        <i
                          class="mdi mdi-archive-edit align-middle fs-6 text-danger"
                        ></i>
                      </td>
                      <td
                        class="text-end d-flex align-items-center justify-content-end"
                      >
                        <span v-if="RecForm.status == 'success'">
                          {{ formatPrice(PaySetting.rec_discount) }} </span
                        >
                        <cleave
                          type="text"
                          class="form-control p-1 fs-6 border-danger text-end border-1 me-1"
                          :options="options"
                          v-model="PaySetting.rec_discount"
                          v-if="
                            RecForm.status == 'padding' ||
                            RecForm.status == 'created'
                          "
                        />
                        <span
                          class="fs-6"
                          style="padding-top: 2px"
                          v-if="
                            RecForm.status == 'padding' ||
                            RecForm.status == 'created'
                          "
                          ></span
                        >
                      </td>
                      <!-- <td class="text-end">
                                {{ formatPrice(bill_discust) }}
                                
                              </td> -->
                      <td
                        v-if="
                          RecForm.status == 'padding' ||
                          RecForm.status == 'created'
                        "
                      ></td>
                    </tr>
                    <tr>
                      <td colspan="4" class="text-end">
                        <div class="d-flex justify-content-end fs-6">
                          ອມພ({{JSON.parse(store.get_setting).tax_value}}%)
                          <div
                            class="form-check form-switch ms-2"
                            v-if="
                              RecForm.status == 'padding' ||
                              RecForm.status == 'created'
                            "
                          >
                            <input
                              class="form-check-input"
                              type="checkbox"
                              role="switch"
                              :checked="false"
                              v-model="PaySetting.rec_vat"
                            />
                          </div>
                        </div>
                      </td>
                      <td class="text-end">{{ formatPrice(CalVat) }} </td>
                      <td
                        v-if="
                          RecForm.status == 'padding' ||
                          RecForm.status == 'created'
                        "
                      ></td>
                    </tr>
                  </tbody>
                </table>
                <div
                  style="position: relative"
                  v-if="!(RecForm.payed >= CalTotal)"
                >
                  <div style="position: absolute">
                    <div class="row">
                      <div class="col md-12">
                        <div
                          class="card border-dashed border-2 border-warning mt-3"
                        >
                          <div class="card-body p-3">
                            <div class="d-flex justify-content-between mt-2">
                              <span class="me-4">
                                <div class="form-check">
                                  <input
                                    class="form-check-input"
                                    type="radio"
                                    id="formCheck1"
                                    name="payby"
                                    value="cash"
                                    v-model="PaySetting.PayBy"
                                  />
                                  <label
                                    class="form-check-label"
                                    for="formCheck1"
                                  >
                                    ຊຳລ່ະດ້ວຍ ເງິນສົດ
                                  </label>
                                </div>
                              </span>
                              <span>
                                <div class="form-check">
                                  <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payby"
                                    id="formCheck2"
                                    value="bank"
                                    v-model="PaySetting.PayBy"
                                  />
                                  <label
                                    class="form-check-label"
                                    for="formCheck2"
                                  >
                                    ຊຳລ່ະດ້ວຍ ເງິນໂອນ
                                  </label>
                                </div>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <table
                  class="table align-middle table-nowrap mb-0"
                  v-if="!(RecForm.payed >= CalTotal)"
                >
                  <tr v-if="RecForm.payed">
                    <td class="text-end pe-2 fw-bold">ລວມທັງໝົດ:</td>
                    <td
                      style="width: 238px"
                      class="border px-3 py-2 text-end fs-5 fw-bold"
                    >
                      {{ formatPrice(CalTotal) }} 
                    </td>
                  </tr>
                  <tr v-if="RecForm.payed">
                    <td class="text-end pe-2 fw-bold">ຊຳລ່ະແລ້ວ:</td>
                    <td
                      style="width: 238px"
                      class="border px-3 py-2 text-end fs-5 fw-bold"
                    >
                      {{ formatPrice(RecForm.payed) }} 
                    </td>
                  </tr>
                  <tr>
                    <td class="text-end pe-2 fw-bold">ລວມເງິນຕ້ອງຊຳລ່ະ:</td>
                    <td
                      style="width: 238px"
                      class="border px-3 py-2 text-end fs-5 fw-bold"
                    >
                      {{ formatPrice(CalTotal - RecForm.payed) }} 
                    </td>
                  </tr>
                  <tr>
                    <td class="text-end pe-2 fw-bold text-info">
                      ຮັບເງິນນຳລູກຄ້າ:
                    </td>
                    <td
                      style="width: 238px"
                      class="border px-3 py-2 text-end fs-5 fw-bold d-flex align-items-center"
                    >
                      <cleave
                        type="text"
                        class="form-control text-info p-1 fs-5 fw-bold border-danger text-end border-1 me-1"
                        :options="options"
                        v-model="PaySetting.customer_pay"
                      />
                      <span class="fs-6 p-0 text-info" style="padding-top: 6px"
                        ></span
                      >
                    </td>
                  </tr>
                  <tr
                    v-if="
                      PaySetting.customer_pay - (CalTotal - RecForm.payed) > 0
                    "
                  >
                    <td class="text-end pe-2 fw-bold text-danger">ເງິນທອນ:</td>
                    <td
                      style="width: 238px"
                      class="border text-danger px-3 py-2 text-end fs-5 fw-bold"
                    >
                      {{
                        formatPrice(
                          PaySetting.customer_pay - (CalTotal - RecForm.payed)
                        )
                      }}
                      ₭
                    </td>
                  </tr>
                </table>

                <table class="table align-middle table-nowrap mb-0" v-else>
                  <tr v-if="RecForm.payed">
                    <td class="text-end pe-2 fw-bold">ລວມທັງໝົດ:</td>
                    <td
                      style="width: 238px"
                      class="border px-3 py-2 text-end fs-5 fw-bold"
                    >
                      {{ formatPrice(CalTotal) }} 
                    </td>
                  </tr>
                </table>
              </div>

              <div class="d-flex justify-content-end mt-4 align-items-center"> 
                <div class="form-check me-4 "> 
                  <input class="form-check-input" type="checkbox" id="prnt-4a" v-model="PrintA4">
                  <label class="form-check-label" for="prnt-4a">
                    ພິມບິນພ້ອມ
                  </label>
                </div>

                <button
                  type="button"
                  class="btn btn-primary me-2"
                  :disabled="check_rec_form"
                  @click="SaveRec()"
                >
                  <span v-if="!loading_post">ບັນທຶກ</span>
                  <div v-else class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                </button>
                <button
                  type="button"
                  class="btn btn-label-secondary"
                  data-bs-dismiss="modal"
                >
                  ຍົກເລີກ
                </button>
              </div>
            </div>
            <!-- </div> -->
          </Transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import mixins from "../mixins/ulmixins";
import { useStore } from '../Store/auth';
export default {
  name: "DmsRec",
  setup() {
    const store = useStore();
    return { store };
  },
  mixins: [mixins],
  data() {
    return {
      rec_form: false,
      RecData: {
        data: [],
      },
      PrintA4:true,
      Status: "",
      Sort: "desc",
      RecForm: {
        rec_id: "",
        customer_name: "",
        customer_phone: "",
        customer_address: "",
        customer_id: "",
        status: "",
        payed: "",
      },
      RecListForm: [],
      RecListFormEdit: {
        rec_name: "",
        price: "",
        qty: "",
      },
      RecListEditID: "",
      PaySetting: {
        rec_discount: 0,
        rec_vat: false,
        rec_total: 0,
        customer_pay: 0,
        PayBy: "cash",
      },
      CustomerData: [],
      SearchCus: "",
    };
  },

  computed: {
    check_rec_list_form() {
      // console.log(this.RecListFormEdit)
      // check RecListFormEdit
      if (
        this.RecListFormEdit.rec_name == "" ||
        this.RecListFormEdit.qty == "" ||
        this.RecListFormEdit.price == ""
      ) {
        return true;
      } else {
        return false;
      }
    },
    check_rec_form() {
      if (
        this.RecListForm.length == 0 ||
        this.RecForm.customer_id == null ||
        this.RecForm.customer_id == ""
      ) {
        return true;
      } else {
        if (this.loading_post) {
          return true;
        } else {
          return false;
        }
      }
    },
    TotalSum() {
      var sum = 0;
      this.RecListForm.forEach((element) => {
        sum += element.price * element.qty;
      });
      return sum;
    },
    TotalQty() {
      var sum = 0;
      this.RecListForm.forEach((element) => {
        sum += parseInt(element.qty);
      });
      return sum;
    },
    // CalDiscust(){
    //   return this.TotalSum-this.PaySetting.rec_discount
    // },
    CalVat() {
      let vat = 0;
      if (this.PaySetting.rec_vat) {
        vat = ((this.TotalSum - this.PaySetting.rec_discount) * JSON.parse(this.store.get_setting).tax_value) / 100;
      }
      return vat;
    },
    CalTotal() {
      return this.TotalSum - this.PaySetting.rec_discount + this.CalVat;
    },
  },
  mounted() {},

  methods: {
    Csearch() {
      this.SearchCus = "";
      // settimer
      setTimeout(() => {
        this.$refs.search_cus.focus();
      }, 600);
    },
    PtintBill(id,type){
      if(type == '80')
      this.openLink(window.location.origin + `/api/rec/print/80mm/${id}`);
      if(type == 'a4')
      this.openLink(window.location.origin + `/api/rec/print/a4/${id}`);
      if(type == 'quo')
      this.openLink(window.location.origin + `/api/rec/print/quo/${id}`);
    },
    GetCus() {
      // console.log('aa');
      this.GetData(`cus/search?search=${this.SearchCus}`, (result) => {
        this.CustomerData = result;
      });
    },
    addCus(id, name, last_name, gender, tel, address) {
      this.RecForm.customer_id = id;
      this.RecForm.customer_name =
        gender == "female"
          ? "ນ " + name + " " + last_name
          : name + " " + last_name;
      this.RecForm.customer_tel = tel;
      this.RecForm.customer_address = address;
      this.CustomerData = [];
    },
    ChangeSort() {
      if (this.Sort == "asc") {
        this.Sort = "desc";
      } else {
        this.Sort = "asc";
      }
      this.GetRec();
    },
    GetRec(page) {
      this.GetData(
        `rec/get?page=${page}&perpage=${this.PerPage}&sort=${this.Sort}&search=${this.Search}&status=${this.Status}`,
        (result) => {
          this.RecData = result;
        }
      );
    },
    AddRecList() {
      this.rec_form = true;
      this.RecListEditID = "";
      this.RecListFormEdit = {
        rec_name: "",
        price: "",
        qty: 1,
      };
      setTimeout(() => {
        this.$refs.list_name.focus();
      }, 1000);
    },
    EditRecList(id) {
      this.rec_form = true;
      this.RecListEditID = id;
      this.RecListForm.forEach((element) => {
        if (element.id == id) {
          this.RecListFormEdit = element;
        }
      });
    },
    SaveRecList() {
      if (this.RecListEditID) {
        this.RecListForm.forEach((element) => {
          if (element.id == this.RecListEditID) {
            element.rec_name = this.RecListFormEdit.rec_name;
            element.price = this.RecListFormEdit.price;
            element.qty = this.RecListFormEdit.qty;
          }
        });
      } else {
        this.RecListForm.push(this.RecListFormEdit);
      }
      this.rec_form = false;
    },
    DeleteRecList(id) {
      this.RecListForm = this.RecListForm.filter((item) => item.id !== id);
    },
    CancelRecList() {
      this.rec_form = false;
    },
    AddRec() {
      // clear form
      this.RecForm = {
        rec_id: "",
        customer_name: "",
        customer_phone: "",
        customer_address: "",
        customer_id: "",
        status: "",
      };
      this.FormType = true;
      this.RecListForm = [];
      this.PaySetting.rec_discount = 0;
      this.PaySetting.customer_pay = 0;
      this.PaySetting.rec_vat = false;

      // var modal = new bootstrap.Modal(document.getElementById('rec_form'), {
      //   keyboard: false
      // })
      // modal.show()
      this.AddData(
        "rec/add",
        {
          rec_form: this.RecForm,
          rec_list_form: this.RecListForm,
          pay_setting: this.PaySetting,
        },
        (result) => {
          if (result.success) {
            this.EditRec(result.bid);
          }
        },
        "no"
      );
    },
    EditRec(id) {
      this.PrintA4 = true;
      this.FormType = false;
      this.PaySetting.customer_pay = 0;
      this.EditData(`rec/edit`, id, (result) => {
        this.RecForm = result.rec;
        this.RecForm.payed = result.payed;
        // console.log(result.rec.rec_discount)
        this.PaySetting.rec_discount = result.rec.rec_discount;
        this.PaySetting.rec_vat = result.rec.rec_vat==1 ? true : false;
        this.RecListForm = result.rec_list;
        var modal = new bootstrap.Modal(document.getElementById("rec_form"), {
          keyboard: false,
        });
        modal.show();
      });
    },
    SaveRec() {
      if (this.FormType) {
        this.AddData(
          "rec/add",
          {
            rec_form: this.RecForm,
            rec_list_form: this.RecListForm,
            pay_setting: this.PaySetting,
          },
          (result) => {
            if (result.success) {
              this.GetRec();
              this.$refs.close_rec_form.click();
            }
          }
        );
      } else {

        // console.log(this.PaySetting.rec_vat)

        if(this.PaySetting.rec_vat == true){
          this.PaySetting.rec_vat = 1
        }else{
          this.PaySetting.rec_vat = 0
        }

        this.UpdateData(
          `rec/update/${this.EditID}`,
          {
            rec_form: this.RecForm,
            rec_list_form: this.RecListForm,
            pay_setting: this.PaySetting,
          },
          (result) => {
            if (result.success) {
              this.GetRec();
              this.$refs.close_rec_form.click();
              if(this.PrintA4){
                this.PtintBill(this.RecForm.rec_id,JSON.parse(this.store.get_setting).printer_default)
              }
              // console.log(this.$refs.close_rec_form.click())
            }
          }
        );
      }
    },
    DelRec(id) {
      this.DeleteData(
        `rec/delete`,
        id,
        (result) => {
          this.GetRec();
        },
        "ທີ່ທ່ານຕ້ອງຍົກເລີກລາຍການນີ້?"
      );
    },
  },

  created() {
    this.GetRec();
  },
  watch: {
    Search: function (val) {
      if (val == "") {
        this.GetRec();
      }
    },
  },
};
</script>

<style scoped>
/* .nested-enter-active .inner,
.nested-leave-active .inner {
  transition: all 0.2s ease-in-out;
}

.nested-enter-from .inner,
.nested-leave-to .inner {
  transform: translateX(30px);
  opacity: 0;
}
.nested-enter-active .inner {
  transition-delay: 0.25s;
} */

.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.3s;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(20px);
  opacity: 0;
}
/* .modal-content{
  transform: all 1s;
} */
</style>