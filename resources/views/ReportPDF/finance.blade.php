<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Finance</title>
  
<!-- <link  href="{{ url('assets/css/font_lao.css')}}" rel="stylesheet" type="text/css" > -->
    <style>
        @font-face {
            font-family: 'PHETSARATHOT';
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/PHETSARATHOT.ttf') }}') format('truetype');
        }
        html,body,div,p,table,th,thead,tr,td,a,span  {
            font-family: 'PHETSARATHOT';
            font-size: 14px;
            margin: 15px 10px 10px 12px; 
            padding: 0; 
        }
        h1{
            font-size: 30px;
            font-family: 'PHETSARATHOT';
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
            font-family: 'PHETSARATHOT';
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
        p{
            font-family: 'PHETSARATHOT';
        
        }
        .font{
            /* font-family: 'phetsarath ot';
            font-size: 15px; */
        }
        .center {
  margin-left: auto;
  margin-right: auto;
}
    </style>
</head>

<body>
    
<div class="text-center">
        <span class="fs-2 fw-bold">ສາທາລະນະລັດ ປະຊາທິປະໄຕ ປະຊາຊົນລາວ</span><br>
        <span class="fs-2 fw-bold">ສັນຕິພາບ ເອກະພາບ ປະຊາທິປະໄຕ ເອກະພາບ ວັດຖະນະຖາວອນ</span>
    </div>
    <div class="mt-2">
        <div>
            @if($Setting->company_logo)
            <img src="{{public_path('assets/img/'.$Setting->company_logo)}}" style="width: 120px;"><br>
            @endif
            <span>
            {{ $Setting->company_name }} <br>
            ອີເມວລ໌: {{ $Setting->company_email }} <br>
            ໂທ: {{ $Setting->company_tel }} <br>
            ທີ່ຢູ່: {{ $Setting->company_address }}
            </span>
        </div>
                    <div class="text-center mt-0">
                    <h1>ລາຍງານ ຕິດຕາມເງິນສົດ</h1>
                    <p >
                        @if($dmy=='d')
                        <span >ປະຈຳ: ວັນ {{\Carbon\Carbon::parse($date_tran)->format('d/m/Y')}}</span>
                        @endif
                        @if($dmy=='m')
                        <span >ປະຈຳ: ເດືອນ {{\Carbon\Carbon::parse($date_tran)->format('m/Y')}}</span> 
                        @endif
                        @if($dmy=='y')
                        <span >ປະຈຳ: ປີ {{\Carbon\Carbon::parse($date_tran)->format('Y')}}</span>
                        @endif
                    </p>
                    </div>
                    <div class=" d-flex justify-content-center">
                    <div class="table-responsive">
                    <div class="text-end" style="padding-right:40px">ຫົວໜ່ວຍ: ກີບ </div>
                                            <table class="table table-bordered border-secondary table-nowrap align-middle mb-0" style=" width:750px;">
                                            <thead>
                                               
                                                <tr>
                                                        <th rowspan="2" width="20" class="text-center">ລ/ດ</th>
                                                        <th rowspan="2" width="45" class="text-center">ວັນເດືອນປີ</th>
                                                        <th rowspan="2" width="100" class="text-center">ເລກທີ່</th>
                                                        <th rowspan="2" width="160" class="text-center">ເນື້ອໃນ</th>
                                                        <th colspan="2" width="100" class="text-center">ຈຳນວນເງິນ</th>
                                                        <th rowspan="2" width="50" class="text-center">ຍອດເຫຼືອ</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">ຮັບເຂົ້າ</th>
                                                        <th class="text-center">ຈ່າຍອອກ</th>
                                                    </tr>
                                            </thead>
                                                <tbody>
                                              
                                                  
                                                    <tr>
                                                        <td colspan="4" class="text-end">ຍອດຍົກມາ</td>
                                                        <td class="text-end">{{number_format($sum_income_before, 0, ',', '.')}}</td>
                                                        <td class="text-end">{{number_format($sum_expense_before, 0, ',', '.')}}</td>
                                                        <td class="text-end">{{number_format($subtotal_before, 0, ',', '.')}}</td>
                                                    </tr>
                                                    @php
                                                        $sum_income = 0;
                                                        $sum_expense = 0;
                                                    @endphp

                                                    @php($num = 1)
                                                    @foreach($transection as $item)
                                                    <tr v-for="list, index in FinanceData" :key="list.id">
                                                        <td width="20" class="text-center">{{$num}}</td>
                                                        <td width="45" class="text-center">{{\Carbon\Carbon::parse($item->created_at)->format('d/m/Y')}}</td>
                                                        <td width="100" class="text-center">{{$item->tran_id}}</td>
                                                        <td width="160">{{$item->tran_details}}</td>
                                                        <td class="text-end" width="50"> 
                                                            @if($item->tran_type=='income')
                                                            {{number_format($item->price, 0, ',', '.')}} 
                                                            @endif
                                                        </td>
                                                        <td class="text-end" width="50"> 
                                                            @if($item->tran_type=='expense')
                                                            {{number_format($item->price, 0, ',', '.')}}  
                                                            @endif
                                                        </td>
                                                        <td class="text-end" width="50">{{number_format($item->subtotal, 0, ',', '.')}}</td>
                                                  
                                                    </tr>
                                                    @php($num++)
                                                    @endforeach
                                                    <tr>
                                                    @foreach ($transection as $item)
                                                        @if($item->tran_type=='income')
                                                            @php($sum_income += $item->price)
                                                        @endif
                                                        @if($item->tran_type=='expense')
                                                            @php($sum_expense += $item->price)
                                                        @endif
                                                    @endforeach
                                                       
                                                        <td colspan="4" class="text-end">ລວມຍອດ</td>
                                                        <td class="text-end">{{number_format($sum_income_before+$sum_income, 0, ',', '.')}}</td>
                                                        <td class="text-end">{{number_format($sum_expense_before+$sum_expense, 0, ',', '.')}}</td>
                                                        <td class="text-end">{{number_format(($sum_income_before+$sum_income)-($sum_expense_before+$sum_expense), 0, ',', '.')}}</td>
                                                    </tr>
                                                    
                                                    
                                                </tbody>
                                            </table>
                                            <div class="text-center">

                                            <table style="width:350px" class="center">
                                                <tr>
                                                    <td width="80" class="text-end">ຍອດຍົກມາ:</td>
                                                    <td class="text-end">{{number_format($subtotal_before, 0, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">ເກີດຂື້ນຮັບເຂົ້າ:</td>
                                                    <td class="text-end">{{number_format($sum_income, 0, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">ເກີດຂື້ນຮັບອອກ:</td>
                                                    <td class="text-end">{{number_format($sum_expense, 0, ',', '.')}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">ຍອດເຫຼືອ:</td>
                                                    <td class="text-end">{{number_format(($sum_income_before+$sum_income)-($sum_expense_before+$sum_expense), 0, ',', '.')}}</td>
                                                </tr>
                                            </table>
                                            
                                            </div>
                                            <!-- <div class="text-end" style="padding-right:40px">ວັນທີ່: {{ date('d/m/Y') }} </div> -->
                                        </div>
                    </div>
                 
                    
                  </div>

                  <div class="p-4" >
                        <div style="display: inline-block; width:45%; padding-left:40px" class="fs-2 fw-bold fs-2 fw-bold " >
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
            $font = $fontMetrics->getFont("PHETSARATHOT", "italic");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) /2;
            $y = $pdf->get_height() - 20;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>