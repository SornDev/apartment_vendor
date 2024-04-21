<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt 80mm</title>

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
        font-size: 11px;
    }
    h1,h2,h3,h4,h5,h6{
        color: #000;
    }
    .dash{
        border-bottom: 2px #000000 dashed;
        opacity: 1;
        background-color: white;
        
    }
    .dash_mini{
        border-bottom: 1px #000000 dashed;
        opacity: 1;
        background-color: white;
        
    }
    .dash_mini:last-child{
        border-bottom: 0px #000000 dashed;
        opacity: 1;
        background-color: white;
        
    }
    table, th, tr, td {
        /* border: 1px solid black; */
        font-size: 11px;
        color: black;
        --bs-body-color: black;
    }
   
    table > tbody > tr > td {
        color: #000;
        font-size: 11px;
    }
   .text-dark{
         color: black;
         font-weight: bold;
        font-size: 11px;
   }
</style>
</head>
<body style="background-color: white;">
<div class="container mt-1" style="width: 303px;">
        <div class="text-center" style="font-size: 12px;">
        @if($setting->company_logo)
                @php($img = $setting->company_logo)
                
                <img src="{{asset('assets/img/'.$img)}}" style="width: 120px;"><br>
              
                @endif
                <span class="fs-6"> <strong> {{$setting->company_name}}</strong> </span><br>
                
                ອີເມວລ໌: {{$setting->company_tel}}, 
                ເບີໂທ: {{$setting->company_tel}}<br>
                ທີ່ຢູ່: {{$setting->company_address}}

                <br>
      
                <div class="mt-2"> <strong> <span class="fs-6"> ໃບບິນຮັບເງິນ </span> </strong><div>
        </div>
     
        
       <div class=" d-flex justify-content-between mt-2 pb-2 ">
            <div class="text-start">
                ເລກທີ່: {{$id}} <br>
                ວັນທີ່: {{$date}} <br>
                
            </div>
            <div class="text-end">ພະນັກງານ: <br> {{$rec->user_name}}</div>
       </div>
 
       <div class="text-start dash mt-2 pb-2">
       ຂໍ້ມູນລູກຄ້າ: {{$rec->customer_name}} 
   
       @if($rec->customer_tel)
        <span>(+856{{$rec->customer_tel}})</span>
         @endif                     
       </div>
       <div class="text-start dash mt-2 pb-2">
        <table style="width:100%">
            <tr class="dash_mini">
                <td style="font-size: 11px;" > <strong> ລາຍການບໍລິການ </strong></td>
                <td style="font-size: 11px;" width="80" class="text-end"><strong> ຈຳນວນ/ລາຄາ </strong></td>
                <td style="font-size: 11px;" width="90" class="text-end"> <strong> ລວມ </strong></td>
            </tr>
            @php($pnum = 0)
            @php($alltotal = 0)
            @php($total = 0)
            @php($num = 1)
            @php($row_num = 11)
            @foreach($rec_list as $bl)
            @php($row_num--)
            <tr class="dash_mini">
                <td>- {{$bl->rec_name}}</td>
                <td class="text-end"> {{$bl->qty}} x {{ number_format($bl->price, 0, ',', '.')}}</td>
                @php($total += $bl->qty*$bl->price)
                <td class="text-end">{{ number_format($bl->qty*$bl->price, 0, ',', '.')}}</td>
            </tr>
            @endforeach
        </table>
       </div>
       <div class="dash">
       <table style="width:100%;" >
            <tr>
                <td style="font-size: 12px;" colspan="2" class="text-end"> ລວມທັງໝົດ</td>
                <td style="font-size: 12px;" width="90" class="text-end">{{number_format($total, 0, ',', '.')}}</td>
            </tr>
            @if($rec->rec_discount)
            <tr>
                <td style="font-size: 12px;" colspan="2" class="text-end"> ສ່ວນຫຼຸດ</td>
                <td style="font-size: 12px;" width="90" class="text-end">{{number_format($rec->rec_discount, 0, ',', '.')}}</td>
            </tr>
            @php($total -= $rec->rec_discount)
            @endif
            @if($rec->rec_vat>0)
            <tr>
                <td style="font-size: 12px;" colspan="2" class="text-end"> ອມພ ({{$setting->tax_value}}%)</td>
                <td style="font-size: 12px;" width="90" class="text-end">{{number_format(($total*$setting->tax_value)/100, 0, ',', '.')}}</td>
            </tr>
            @php($total += ($total*$setting->tax_value)/100)
            @endif
        </table>
        </div>
        <div class="">
        <table style="width:100%">
            <tr>
                <td colspan="2" style="font-size: 12px; font-weight: bold;"  class="text-end">ຍອດຊຳລະ ເງິນກີບ</td>
                <td style="font-size: 12px; font-weight: bold;" width="90" class="text-end">{{number_format($total, 0, ',', '.')}} ₭</td>
            </tr>
        </table>
        </div>
        <div class="text-start pt-1 pb-1">
           
        @if($customer_payed>0)
                                                <div> ຮັບເງິນກີບ: {{number_format($customer_payed, 0, ',', '.')}} ₭</div>
                                            @endif
                
                                                ເງິນທອນ: {{number_format($cash_back, 0, ',', '.')}} ₭ 
                                               
          
        </div>
        <div class="text-center p-2">
            ຂໍຂອບໃຈ ທີ່ໃຊ້ບໍລິການ
        </div>
    </div>

    <script>
        window.print();
        setTimeout('window.close();', 3000);
    </script>
    
</body>
</html>