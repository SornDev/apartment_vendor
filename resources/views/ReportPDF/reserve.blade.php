<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Reserve</title>
  
<!-- <link  href="{{ url('assets/css/font_lao.css')}}" rel="stylesheet" type="text/css" > -->
    <style>
        @font-face {
            font-family: 'BoonHome';
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/BoonHome-400.ttf') }}') format('truetype');
        }
        html,body,div,p,table,th,thead,tr,td,a,span  {
            font-family: 'BoonHome';
            font-size: 16px;
            margin: 15px 10px 10px 12px; 
            padding: 0; 
        }
        h1{
            font-size: 30px;
        }
        h2{
            font-size: 18px;
        }
        h3{
            font-size: 16px;
        }
        h4{
            font-size: 14px;
        }
        table{
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        .text-center{
            text-align: center;
        }
        .text-end{
            text-align: end;
            text-align: right;
        }
        .text-start{
            text-align: start;
        }
        .mt-2{
            margin-top: 20px;
        }
        .mb-4{
            margin-bottom: 40px;
        }
        .mt-4{
            margin-top: 40px;
        }
        .mb-2{
            margin-bottom: 20px;
        }
        .pb-2{
            padding-bottom: 20px;
        }
        .pb-4{
            padding-bottom: 40px;
        }
        .p-4{
            padding: 40px;
        }
        .text-danger{
            color: red;
        }
        .row{
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1 * var(--bs-gutter-y));
            margin-right: calc(-0.5 * var(--bs-gutter-x));
            margin-left: calc(-0.5 * var(--bs-gutter-x));
        }
        .row > * {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: calc(var(--bs-gutter-x) * 0.5);
            padding-left: calc(var(--bs-gutter-x) * 0.5);
            margin-top: var(--bs-gutter-y);
            }
      
        .col-6{
            flex: 0 0 auto;
  width: 50%;

        }
        .col-12{
            flex: 0 0 100%;
            max-width: 100%;
        }
        .me-4{
            margin-right: 40px;
        }
        .ms-4{
            margin-left: 40px;
        }
        .pe-4{
            padding-right: 140px;
        }
        .ps-4{
            padding-left: 140px;
        }
        .d-flex{
            display: flex;
        }
        .justify-content-center{
            justify-content: center;
        }
        .justify-content-between{
            justify-content: space-between !important;
        }
        .fs-3{
            font-size: 20px;
        }
        .fs-2{
            font-size: 22px;
        }
        .fw-bold{
            font-weight: bold;
        }
        .bg-danger{
            background-color: red;
        }
        .bg-primary{
            background-color: blue;
        }
        .bg-warning{
            background-color: yellow;
        }
        .p-0{
            padding: 0;
        }
    </style>
</head>

<body>
    
    <div class="text-center">
        <p class="fs-2 fw-bold">ສາທາລະນະລັດ ປະຊາທິປະໄຕ ປະຊາຊົນລາວ</p>
        <p class="fs-2 fw-bold">ສັນຕິພາບ ເອກະພາບ ປະຊາທິປະໄຕ ເອກະພາບ ວັດຖະນະຖາວອນ</p>
    </div>
    <div class="mt-2">
        <div>
            {{ $hotelData[0]->hotel_name }} <br>
            ໂທ: {{ $hotelData[0]->hotel_tel }} <br>
            ທີ່ຢູ່: {{ $hotelData[0]->hotel_village }}, ເມືອງ{{ $hotelData[0]->hotel_city_name }}, ແຂວງ{{ $hotelData[0]->hotel_province_name }}
        </div>
                    <div class="text-center">
                    <h1>ລາຍງານ ຜົນດຳເນີນງານ</h1>
                    <p >ປະຈຳ:
                     
                        @if($dmy=='m')
                        <span >ເດືອນ {{\Carbon\Carbon::parse($date_tran)->format('m/Y')}}</span> 
                        @endif
                        @if($dmy=='y')
                        <span >ປີ {{\Carbon\Carbon::parse($date_tran)->format('Y')}}</span>
                        @endif
                    </p>
                    </div>

                    @php($sum_incom_im = $data_now['income_room'] + $data_now['income_pos'] - $data_now['expense_import'])
                    @php($sum_incom_im_before = $data_before['income_room'] + $data_before['income_pos'] - $data_before['expense_import'])
                    @php($sum_in_nopay_tax = $sum_incom_im +$data_now['income_other'] - $data_now['expense_serve'] - $data_now['expense_staff'] - $data_now['tax_payed'] - $data_now['expense_other_all'])
                    @php($sum_in_nopay_tax_before = $sum_incom_im_before +$data_before['income_other'] - $data_before['expense_serve'] - $data_before['expense_staff'] - $data_before['tax_payed'] - $data_before['expense_other_all'])
                    @php($sum_total_all = $sum_in_nopay_tax - $data_now['tax_must_pay'])
                    @php($sum_total_all_before = $sum_in_nopay_tax_before - $data_before['tax_must_pay'])
                    
                    <div class=" d-flex justify-content-center">
                    <div class="table-responsive">
                    <table class="table table-bordered border-secondary table-nowrap align-middle mb-0" style=" width:550px; padding: left 80px;">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" width="120" class="align-middle text-center"> ເນື້ອໃນລາຍການ</th>
                                                        <th scope="col" width="60" class="align-middle text-center">ໝາຍເຫດ</th>
                                                        <th scope="col" width="120"  class="align-middle text-center">
                                                            @if($dmy=='m')
                                                            <span >ປະຈຳເດືອນນີ້</span>
                                                            @endif
                                                            @if($dmy=='y')
                                                            <span >ປະຈຳປີນີ້</span> 
                                                            @endif
                                                            </th>
                                                        <th scope="col" width="120" class="align-middle text-center">
                                                            @if($dmy=='m')
                                                            <span >ປະຈຳເດືອນກ່ອນ</span>
                                                            @endif
                                                            @if($dmy=='y')
                                                            <span >ປະຈຳປີກ່ອນ</span> 
                                                            @endif
                                                        </th>
                                                    </tr>
                                                   
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        <td > - ລາຍຮັບຈາກ ກິດກະການທັງໝົດ</td>
                                                        <td ></td>
                                                        <td class="text-end" > {{number_format($data_now['income_room'], 0, ',', '.')}}</td>
                                                        <td class="text-end" ></td>
                                                    </tr>

                                                    <tr>
                                                        <td > - ຕົ້ນທຶນການຂາຍ</td>
                                                        <td ></td>
                                                        <td class="text-end" >{{number_format($data_now['expense_import'], 0, ',', '.')}}</td>
                                                        <td class="text-end" >{{number_format($data_before['expense_import'], 0, ',', '.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class=" fw-bold text-center" >ຜົນໄດ້ຮັບເບື້ອງຕົ້ນ</td>
                                                        <td ></td>
                                                        <td class="text-end" >{{number_format($sum_incom_im, 0, ',', '.')}}</td>
                                                        <td class="text-end" >{{number_format($sum_incom_im_before, 0, ',', '.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td >- ລາຍຮັບອື່ນໆ</td>
                                                        <td ></td>
                                                        <td class="text-end" >{{number_format($data_now['income_other'], 0, ',', '.')}}</td>
                                                        <td class="text-end" >{{number_format($data_before['income_other'], 0, ',', '.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td >- ລາຍຈ່າຍບໍລິຫານ</td>
                                                        <td ></td>
                                                        <td class="text-end" >{{number_format($data_now['expense_serve'], 0, ',', '.')}}</td>
                                                        <td class="text-end" >{{number_format($data_before['expense_serve'], 0, ',', '.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td >- ລາຍຈ່າຍເງິນເດືອນ ພະນັກງານ</td>
                                                        <td ></td>
                                                        <td class="text-end" >{{number_format($data_now['expense_staff'], 0, ',', '.')}}</td>
                                                        <td class="text-end" >{{number_format($data_before['expense_staff'], 0, ',', '.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td >- ອາກອນທີ່ຈ່າຍໄປແລ້ວ</td>
                                                        <td ></td>
                                                        <td class="text-end" >{{number_format($data_now['tax_payed'], 0, ',', '.')}}</td>
                                                        <td class="text-end" > {{number_format($data_before['tax_payed'], 0, ',', '.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td >- ລາຍຈ່າຍອື່ນໆ</td>
                                                        <td ></td>
                                                        <td class="text-end" >{{number_format($data_now['expense_other_all'], 0, ',', '.')}}</td>
                                                        <td class="text-end" >{{number_format($data_before['expense_other_all'], 0, ',', '.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class=" fw-bold text-center" >ຜົນໄດ້ຮັບກ່ອນເສຍອາກອນ</td>
                                                        <td ></td>
                                                        <td class="text-end" >{{number_format($sum_in_nopay_tax, 0, ',', '.')}}</td>
                                                        <td class="text-end" >{{number_format($sum_in_nopay_tax_before, 0, ',', '.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td >- ອາກອນຕ້ອງຈ່າຍ</td>
                                                        <td ></td>
                                                        <td class="text-end" >{{number_format($data_now['tax_must_pay'], 0, ',', '.')}}</td>
                                                        <td class="text-end" >{{number_format($data_before['tax_must_pay'], 0, ',', '.')}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class=" fw-bold text-center" >ຜົນໄດ້ຮັບສຸດທິ</td>
                                                        <td ></td>
                                                        <td class="text-end" >{{number_format($sum_total_all, 0, ',', '.')}}</td>
                                                        <td class="text-end" >{{number_format($sum_total_all_before, 0, ',', '.')}}</td>
                                                    </tr>
                                                    
                                                    
                                                </tbody>
                                            </table>
                        <div class="text-end" style="padding-right:40px">ວັນທີ່: {{ date('d/m/Y') }} </div>
                    </div>
                    </div>
                 
                    
                  </div>

                  <div class="p-4" >
                        <div style="display: inline-block; width:45%; padding-left:60px" class="fs-2 fw-bold fs-2 fw-bold " >
                         ຜູ້ອຳນວຍການ
                        </div>
                        <div style="display: inline-block; width:35%; padding-right:40px" class="fs-2 fw-bold  fs-2 fw-bold text-end" >
                         ບັນຊີ
                        </div>
                       
                    </div>
                     </div>
                     <script type="text/php">
        if (isset($pdf)) {
            $text = "(ໜ້າ {PAGE_NUM} ຈາກ {PAGE_COUNT}) - ສ້າງໂດຍ: ". Auth::user()->name ." ວັນທີ່: ".date('d/m/Y ເວລາ: H:i:s');
            $size = 8;
            $font = $fontMetrics->getFont("BoonHome", "italic");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) /2;
            $y = $pdf->get_height() - 20;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>