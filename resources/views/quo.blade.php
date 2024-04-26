<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quo</title>
     <!-- Core CSS -->
     <link rel="stylesheet" href="{{ url('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css">
    <link rel="stylesheet" href="{{ url('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css">
    <link rel="stylesheet" href="{{ url('assets/css/demo.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/font_lao.css') }}">
    <!-- icon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


    
    <style>
      html,body,div,p,table,tr,td,a,span {
        color: #000;   
        font-size: 14px;
    }
    h1,h2,h3,h4,h5,h6{
        color: #000;
    }

    table, th, tr, td {
        border: 1px solid black;
        font-size: 14px;
        color: black;
        --bs-body-color: black;
    }
   
    table > tbody > tr > td {
        color: #000;
    }
   .text-dark{
         color: black;
         font-weight: bold;
        font-size: 14px;
   }
    </style>
</head>
<body style="background-color: white;">
    
<div class="container" style="width: 794px;" >
        <div class="text-center fs-6 mt-4">
            ສາທາລະນະລັດ ປະຊາທິປະໄຕ ປະຊາຊົນລາວ <br>
            ສັນຕິພາບ ເອກະລາດ ປະຊາທິປະໄຕ ເອກະພາບ ວັດຖະນະຖາວອນ
        </div>
   
       <div class=" d-flex justify-content-between mt-4">
       <div style="font-size: 12px;">
                @if($setting->company_logo)
                @php($img = $setting->company_logo)
                
                <img src="{{asset('assets/img/'.$img)}}" style="width: 80px;"><br>
              
                @endif
                <span class="fs-6"> <strong> {{$setting->company_name}}</strong> </span><br>
                
                ອີເມວລ໌: {{$setting->company_email}} <br>
                ເບີໂທ: {{$setting->company_tel}}<br>
                ທີ່ຢູ່: {{$setting->company_address}}
            </div>
            <div class="text-end d-flex align-items-end" style="font-size: 12px;">ເລກທີ່:  {{$id}}  <br> ວັນທີ່: {{$date}} <br> ເວລາ: {{$time}}</div>
       </div>
       <div class="text-center p-4">
        <h3>ໃບສະເໜີລາຄາ</h3>
       </div>
       <div class="mt-4">
       <div class=" d-flex justify-content-between mb-2 ">
            <div style="width:500px">ຂໍ້ມູນລູກຄ້າ: {{$rec->customer_name}} @if($rec->customer_tel) <span>(+856{{$rec->customer_tel}})</span> @endif <br>
            @if($rec->customer_address) <span> ທີ່ຢູ່:  {{$rec->customer_address}} </span> @endif
            </div>
            <div class=" align-bottom">ພະນັກງານ: {{$rec->user_name}}</div>
       </div>
       <table class="table align-middle table-nowrap mb-0" >
                                                <thead>
                                                    <tr>
                                                        <th scope="col" width="50" class="text-center text-dark">ລຳດັບ</th>
                                                        <th scope="col" class="text-dark">ເນື້ອໃນລາຍການ</th>
                                                        <th scope="col" width="110" class="text-center text-dark">ລາຄາ</th>
                                                        <th scope="col" width="80" class="text-center text-dark">ຈຳນວນ</th>
                                                        <th scope="col" width="150" class="text-end text-dark">ລວມ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php($pnum = 0)
                                                    @php($alltotal = 0)
                                                    @php($total = 0)
                                                    @php($num = 1)
                                                    @php($row_num = 11)
                                                    @foreach($rec_list as $bl)
                                                    @php($row_num--)
                                                    <tr>
                                                        <td class="text-center">{{$num++}}</td>
                                                        <td> {{$bl->rec_name}}</td>
                                                        <td class="text-end">{{ number_format($bl->price, 0, ',', '.')}}</td>
                                                        <td class="text-center">{{$bl->qty}}
                                                        @php($pnum +=$bl->qty)
                                                        </td>
                                                        <td class="text-end">{{ number_format($bl->qty*$bl->price, 0, ',', '.')}}
                                                        @php($total += $bl->qty*$bl->price)
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="3" class="text-end">ລວມທັງໝົດ</td>
                                                        <td class="text-center">{{$pnum}}</td>
                                                        <td class="text-end">{{number_format($total, 0, ',', '.')}}</td>
                                                    </tr>

                                                    @if($rec->rec_discount)
                                                     <tr>
                                                        <td colspan="4" class="text-end">ສ່ວນຫຼຸດ </td>
                                                        <td class="text-end">{{number_format($rec->rec_discount, 0, ',', '.')}}</td>
                                                    </tr>
                                                    @php($total -= $rec->rec_discount)
                                                    @endif
                                                    @if($rec->rec_vat>0)
                                                    <tr>
                                                        <td colspan="4" class="text-end">ອມພ ({{$setting->tax_value}}%)</td>
                                                        <td class="text-end">{{number_format(($total*$setting->tax_value)/100, 0, ',', '.')}}</td>
                                                    </tr>
                                                    @php($total += ($total*$setting->tax_value)/100)
                                                    @endif
                                                </tbody>
                                            </table>
                                            <div class=" d-flex justify-content-end">

                                           
                                           
                                            <table class="table  table-bordered align-middle table-nowrap mb-0 border-1 border-secondary" style="width: 230px;">
                                                <tbody>
                                                   
                                                    <tr>
                                                        <td width="80" class="text-end">ເງິນກີບ</td>
                                                        <td width="155" class="text-end">{{number_format($total, 0, ',', '.')}} </td>
                                                    </tr>
                                                 
                                                </tbody>
                                            </table>

                                             </div>
                                             <div class=" d-flex justify-content-center">
                                                <div class=" d-flex justify-content-end p-5" style=" width:930px;">
                                              
                                                    <h5>ພະນັກງານ</h5>
                                                </div>
                                    </div>
       </div>
    </div>


    <script>
        window.print();
        setTimeout('window.close();', 3000);
    </script>
    
</body>
</html>