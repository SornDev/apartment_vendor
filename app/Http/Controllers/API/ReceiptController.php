<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\Receipt;
use App\Models\ReceiptList;
use App\Models\User;
use App\Models\Transection;
use App\Models\Setting;
use JWTAuth;

class ReceiptController extends Controller
{
    //

    public function __construct(){
        // $this->middleware('auth:api');
    }

    public function index(){

        if(checkRoles('REC_ACC')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        $user_id = JWTAuth::parseToken()->authenticate()->id;
        $user_type = JWTAuth::parseToken()->authenticate()->user_type;

    

        $search = \Request::query('search');
        $perpage = \Request::query('perpage');
        $sort = \Request::query('sort');
        $status = \Request::query('status');

        if($user_type == 'admin'){

            $rec = Receipt::join('users','receipts.user_id','=','users.id')
            // join receipt_list sum price
            ->join('receipt_lists','receipts.rec_id','=','receipt_lists.rec_id')
            // ->select('receipts.*','users.user_name as user_name',\DB::raw('SUM(receipt_lists.price*receipt_lists.qty) as total_price'))
            ->select('receipts.*','users.user_name as user_name',\DB::raw('IF(receipts.rec_vat = 1, SUM(receipt_lists.price*receipt_lists.qty)+((SUM(receipt_lists.price*receipt_lists.qty)*7)/100), SUM(receipt_lists.price*receipt_lists.qty)) as total_price'))
    
            // ->where('name','LIKE',"%{$search}%")
            // ->select('receipts.*','users.user_name as user_name')
            ->where('receipts.status','LIKE',"%{$status}%")
            ->where(
                function($query) use ($search){
                    $query->where('receipts.rec_id','LIKE',"%{$search}%")
                    ->orWhere('receipts.customer_name','LIKE',"%{$search}%")
                    ->orWhere('receipts.customer_tel','LIKE',"%{$search}%")
                    ->orWhere('receipts.doc_work_id','LIKE',"%{$search}%");
                    // ->orWhere('doc_works.doc_cat','LIKE',"%{$doc_cat}%");
                }
            )
            // group by all column
            // ->groupBy('receipts.rec_id', 'receipts.user_id', 'users.user_name')
            // if receipt rec_vat = 1 then total_price = total_price + (total_price*7)/100 else total_price
            // ->addSelect(\DB::raw('IF(receipts.rec_vat = 1, SUM(receipt_lists.price*receipt_lists.qty)+((SUM(receipt_lists.price*receipt_lists.qty)*7)/100), SUM(receipt_lists.price*receipt_lists.qty)) as total_price'))
            
            // what group by
            ->groupBy('receipts.rec_id','receipts.id','receipts.doc_work_id','receipts.customer_name','receipts.customer_tel','receipts.customer_id','receipts.status','receipts.created_at','receipts.updated_at','receipts.user_id','users.user_name','receipts.quo_id','receipts.customer_address','receipts.rec_discount','receipts.rec_vat')
            ->orderBy('receipts.id',$sort)
            ->paginate($perpage);

        } else {
            $rec = Receipt::join('users','receipts.user_id','=','users.id')
            // join receipt_list sum price
            ->join('receipt_lists','receipts.rec_id','=','receipt_lists.rec_id')
            // ->select('receipts.*','users.user_name as user_name',\DB::raw('SUM(receipt_lists.price*receipt_lists.qty) as total_price'))
            ->select('receipts.*','users.user_name as user_name',\DB::raw('IF(receipts.rec_vat = 1, SUM(receipt_lists.price*receipt_lists.qty)+((SUM(receipt_lists.price*receipt_lists.qty)*7)/100), SUM(receipt_lists.price*receipt_lists.qty)) as total_price'))
    
            // ->where('name','LIKE',"%{$search}%")
            // ->select('receipts.*','users.user_name as user_name')
            ->where('receipts.status','LIKE',"%{$status}%")
            ->where(
                function($query) use ($search){
                    $query->where('receipts.rec_id','LIKE',"%{$search}%")
                    ->orWhere('receipts.customer_name','LIKE',"%{$search}%")
                    ->orWhere('receipts.customer_tel','LIKE',"%{$search}%");
                    // ->orWhere('doc_works.status','LIKE',"%{$status}%")
                    // ->orWhere('doc_works.doc_cat','LIKE',"%{$doc_cat}%");
                }
            )
            // group by all column
            // ->groupBy('receipts.rec_id', 'receipts.user_id', 'users.user_name')
            // if receipt rec_vat = 1 then total_price = total_price + (total_price*7)/100 else total_price
            // ->addSelect(\DB::raw('IF(receipts.rec_vat = 1, SUM(receipt_lists.price*receipt_lists.qty)+((SUM(receipt_lists.price*receipt_lists.qty)*7)/100), SUM(receipt_lists.price*receipt_lists.qty)) as total_price'))
            ->where('receipts.user_id',$user_id)
            // what group by
            ->groupBy('receipts.rec_id','receipts.id','receipts.doc_work_id','receipts.customer_name','receipts.customer_tel','receipts.customer_id','receipts.status','receipts.created_at','receipts.updated_at','receipts.user_id','users.user_name','receipts.quo_id','receipts.customer_address','receipts.rec_discount','receipts.rec_vat')
            ->orderBy('receipts.id',$sort)
            ->paginate($perpage);
        }

       

        return response()->json($rec);

    }

    public function add(Request $request){

        if(checkRoles('REC_ACC_EDIT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        try {

            // delete all rec where status = created
            // get user login id
            // return JWTAuth::parseToken()->authenticate()->id;
            $user_id = JWTAuth::parseToken()->authenticate()->id;
            Receipt::where(['status'=>'created', 'user_id'=>$user_id])->delete();


            $receipt_id = 'RE'.date('Y').rand(1000,9999);

            $rec = new Receipt();
            $rec->rec_id = $receipt_id;
            // $rec->customer_id = isset($request->rec_form['customer_id'])?$request->rec_form['customer_id']:null;
            // $rec->customer_name = isset($request->rec_form['customer_name'])?$request->rec_form['customer_name']:null;
            // $rec->customer_tel = isset($request->rec_form['customer_tel'])?$request->rec_form['customer_tel']:null;
            // $rec->customer_address = isset($request->rec_form['customer_address'])?$request->rec_form['customer_address']:null;
            // $rec->rec_discount = $request->pay_setting['rec_discount']?$request->pay_setting['rec_discount']:0;
            // $rec->rec_vat = $request->pay_setting['rec_vat']=='true'?1:0;
            $rec->status = 'created';
            $rec->user_id = $user_id;
            $rec->save();

            // add rec_list
            // foreach($request->rec_list_form as $item){
            //     $rec_list = new ReceiptList();
            //     $rec_list->rec_id = $receipt_id;
            //     $rec_list->rec_name = $item['rec_name'];
            //     $rec_list->qty = $item['qty'];
            //     $rec_list->price = $item['price'];
            //     $rec_list->save();
            // }

            $success = true;
            $message = 'ບັນທຶກຂໍ້ມູນ ສຳເລັດ!';

        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'bid' => $rec->id,
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);
        
    }

    public function edit($id){

        if(checkRoles('REC_ACC_EDIT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        $rec = Receipt::find($id);
        // get rec_id from rec
        // $rec_id = $rec->rec_id;
        $rec_list = ReceiptList::where('rec_id',$rec->rec_id)->get();
        $payed_list = Transection::where(['rec_id'=>$rec->rec_id,'fn'=>'REC'])->get();
        $payed = Transection::where(['rec_id'=>$rec->rec_id,'fn'=>'REC'])->sum('price');

        $response = [
            'payed' => $payed,
            'payed_list' => $payed_list,
            'rec' => $rec,
            'rec_list' => $rec_list,
        ];
        return response()->json($response);

    }

    public function update($id,Request $request){

        if(checkRoles('REC_ACC_EDIT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        try {
            $user_id = JWTAuth::parseToken()->authenticate()->id;
            $form_discount = isset($request->pay_setting['rec_discount'])?$request->pay_setting['rec_discount']:0;
            // return $request->pay_setting['rec_discount'];
            // update receipt
            $rec = Receipt::find($id);
            $rec->customer_id = $request->rec_form['customer_id'];
            $rec->customer_name = $request->rec_form['customer_name'];
            $rec->customer_tel = $request->rec_form['customer_tel'];
            $rec->customer_address = $request->rec_form['customer_address'];
            $rec->rec_discount = $form_discount;
            $rec->rec_vat = $request->pay_setting['rec_vat'];
            $rec->save();

            // check if rec_list_form length > 0 then delete rec_list
            if(count($request->rec_list_form) > 0){
                    // update rec list
                    $rec_list = ReceiptList::where('rec_id',$rec->rec_id)->get();
                    foreach($request->rec_list_form as $item){
                        if(isset($item['id'])){
                            $rec_list = ReceiptList::find($item['id']);
                            $rec_list->rec_name = $item['rec_name'];
                            $rec_list->qty = $item['qty'];
                            $rec_list->price = $item['price'];
                            $rec_list->save();
                        }
                        
                    }

                    // check if rec_list_form id != rec_list id then add new rec_list
                    foreach($request->rec_list_form as $item){
                        if(!isset($item['id'])){ 
                            $rec_list = new ReceiptList();
                            $rec_list->rec_id = $rec->rec_id;
                            $rec_list->rec_name = $item['rec_name'];
                            $rec_list->qty = $item['qty'];
                            $rec_list->price = $item['price'];
                            $rec_list->save();
                        } else {
                            $rec_list = ReceiptList::find($item['id']);
                            $rec_list->rec_name = $item['rec_name'];
                            $rec_list->qty = $item['qty'];
                            $rec_list->price = $item['price'];
                            $rec_list->save();
                            // if rec_list_form id != rec_list id then delete rec_list
                            $rec_list = ReceiptList::where('rec_id',$rec->rec_id)->whereNotIn('id',array_column($request->rec_list_form,'id'))->get();
                            foreach($rec_list as $item){
                                $item->delete();
                            }
                        }
                    }
                
            }

            if($request->pay_setting['customer_pay'] > 0){

                // get sum rec_list price where rec_id = rec_id
                // $rec_list = ReceiptList::where('rec_id',$rec->rec_id)->sum('price');
                $rec_list = ReceiptList::where('rec_id',$rec->rec_id)->get();
                $sum_rec_list_price = 0;
                foreach($rec_list as $item){
                    $sum_rec_list_price += $item->price*$item->qty;
                }
                // get price rec payed from transection
                $sum_tran_price_payed = Transection::where(['rec_id'=>$rec->rec_id,'fn'=>'REC'])->sum('price');

                $price_add_more = $sum_rec_list_price - $sum_tran_price_payed;
                // if rec_vat = 1 then vat = (sum_rec_list_price*7)/100 else vat = 0
                if($rec->rec_vat == 1){
                    $vat_price = (($sum_rec_list_price-$form_discount)*7)/100;
                } else {
                    $vat_price = 0;
                }
                // $vat_price = (($sum_rec_list_price-$request->pay_setting['rec_discount'])*7)/100;

                // payback
                $payback =($request->pay_setting['customer_pay']+$sum_tran_price_payed) - ($sum_rec_list_price - $form_discount + $vat_price);
                // echo 'customerpay:'.$request->pay_setting['customer_pay'];
                // echo 'payed:'.$sum_tran_price_payed;
                // echo 'vat:'.$vat_price;
                // echo 'allprice:'.$sum_rec_list_price;
                // echo 'discount:'.$request->pay_setting['rec_discount'];
                // echo 'payback:'.$payback;
                // check if price_add_more > 0 then add more transection
                // if($price_add_more > 0){
                    // add trnsection
                    $tran_id = 'TR'.date('Y').rand(1000,9999);

                    $tran = new Transection();
                    $tran->tran_id = $tran_id;
                    $tran->tran_type = 'income';
                    $tran->rec_id = $rec->rec_id;
                    $tran->tran_details = 'ການຊຳລະ ບິນ:'.$rec->rec_id;
                    // $tran->currency_payed = 'LAK';
                    $tran->currency = 'LAK';
                    // check PayBy
                    if($request->pay_setting['PayBy'] == 'bank'){
                        $tran->bank_id = $request->pay_setting['PayBy'];
                    } else {
                        $tran->bank_id = 0;
                    }
                    $tran->rate = 1;
                    $tran->price = $request->pay_setting['customer_pay'];
                    $tran->tran_date = date('Y-m-d');
                    $tran->status = 'success';
                    $tran->user_id = $user_id;
                    $tran->fn = 'REC'; // receipt
                    $tran->save();
                // }

                // if payback > 0 then add transection
                if($payback > 0){
                    $tran_id = 'TR'.date('Y').rand(1000,9999);

                    $tran = new Transection();
                    $tran->tran_id = $tran_id;
                    $tran->tran_type = 'expense';
                    $tran->rec_id = $rec->rec_id;
                    $tran->tran_details = 'ເງິນທອນ ບິນ:'.$rec->rec_id;
                    // $tran->currency_payed = 'LAK';
                    $tran->currency = 'LAK';
                    $tran->bank_id = 0;
                    $tran->rate = 1;
                    $tran->price = $payback;
                    $tran->tran_date = date('Y-m-d');
                    $tran->status = 'success';
                    $tran->user_id = $user_id;
                    $tran->fn = 'PB'; // payback
                    $tran->save();
                }
            }
          


            // update rec status if sum transection price where rec_id = rec_id > sum rec_list price where rec_id = rec_id
            $rec_list = ReceiptList::where('rec_id',$rec->rec_id)->get();
            $sum_rec_list_price = 0;
            foreach($rec_list as $item){
                $sum_rec_list_price += $item->price*$item->qty;
            }
            
            $sum_tran_price = Transection::where(['rec_id'=>$rec->rec_id,'fn'=>'REC'])->sum('price');
            // echo $sum_rec_list_price;
            // echo '--';
            // echo $sum_tran_price;
            if($sum_tran_price >=  ($sum_rec_list_price-$form_discount)){
               
                // check vat  
                if($request->pay_setting['rec_vat'] == 'true'){
                        $vat_price = (($sum_rec_list_price-$form_discount)*7)/100;
                        if(($sum_tran_price>=$sum_rec_list_price+$vat_price-$form_discount)){
                            $rec->status = 'success';
                            $rec->save();
                        } else {
                                $rec->status = 'padding';
                                $rec->save();
                        }
                } else {
                    // echo $sum_tran_price;
                    if($sum_tran_price>=$sum_rec_list_price-$form_discount){
                        // echo $sum_tran_price;
                        $rec->status = 'success';
                        $rec->save();
                    } else {
                        $rec->status = 'padding';
                        $rec->save();
                    }
                }
                
            } else {
                $rec->status = 'padding';
                $rec->save();
            }

           // if discount > 0 then add transection
            if($form_discount > 0){
                $tran_id = 'TR'.date('Y').rand(1000,9999);

                // if discount transection exist then update
                $old_discount_tran = Transection::where('rec_id',$rec->rec_id)->where('fn','DISC')->first();
                if($old_discount_tran){
                    $old_discount_tran->price = $form_discount;
                    $old_discount_tran->save();
                } else {

                    $tran = new Transection();
                    $tran->tran_id = $tran_id;
                    $tran->tran_type = 'expense';
                    $tran->rec_id = $rec->rec_id;
                    $tran->tran_details = 'ສ່ວນຫຼຸດ ບິນ:'.$rec->rec_id;
                    $tran->currency = 'LAK';
                     // check PayBy
                     if($request->pay_setting['PayBy'] == 'bank'){
                        $tran->bank_id = $request->pay_setting['PayBy'];
                    } else {
                        $tran->bank_id = 0;
                    }
                    $tran->rate = 1;
                    $tran->price = $form_discount;
                    $tran->tran_date = date('Y-m-d');
                    $tran->status = 'success';
                    $tran->user_id = $user_id;
                    $tran->fn = 'DISC'; // discount
                    $tran->save();
                }
            }
           
           // if vat > 0 then add transection
            if($request->pay_setting['rec_vat'] == 'true'){
                $tran_id = 'TR'.date('Y').rand(1000,9999);
                
                $vat_price = (($sum_rec_list_price-$form_discount)*7)/100;
                // echo $sum_rec_list_price;
                // get old vat transection
                $old_vat_tran = Transection::where('rec_id',$rec->rec_id)->where('fn','VAT')->first();
                if($old_vat_tran){
                    
                    $old_vat_tran->price = $vat_price;
                    // update old vat transection
                    $old_vat_tran->save();
                   
                } else {

                    if($vat_price){
                        $tran = new Transection();
                        $tran->tran_id = $tran_id;
                        $tran->tran_type = null;
                        $tran->rec_id = $rec->rec_id;
                        $tran->tran_details = 'ອມພ ບິນ:'.$rec->rec_id;
                        $tran->currency = 'LAK';
                        $tran->bank_id = null;
                        $tran->rate = 1;
                        $tran->price = $vat_price;
                        $tran->tran_date = date('Y-m-d');
                        $tran->status = 'success';
                        $tran->user_id = $user_id;
                        $tran->fn = 'VAT'; // vat
                        $tran->save();
                    }
                }
            } else {
                // change status vat transection 
                $old_vat_tran = Transection::where('rec_id',$rec->rec_id)->where('fn','VAT')->first();
                if($old_vat_tran){
                    $old_vat_tran->status = 'reject';
                    $old_vat_tran->save();
                }
            }

            // if rec status = pending then change status reject PB transection
            if($rec->status == 'padding'){
                $tran = Transection::where(['rec_id'=>$rec->rec_id,'fn'=>'PB'])->first();
                if($tran){
                    $tran->status = 'reject';
                    $tran->save();
                }
            }
         
            

            $success = true;
            $message = "ອັບເດດຂໍ້ມູນສຳເລັດ!";

        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
     
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);

    }

    public function delete($id){

        if(checkRoles('REC_ACC_DEL')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

            try {

                $rec = Receipt::find($id);

                // delete rec_list
                $rec_list = ReceiptList::where('rec_id',$rec->rec_id)->get();
                foreach($rec_list as $item){
                    // $item->delete();
                }

                $rec->status = 'reject';
                $rec->save();
                // $rec->delete();

                $success = true;
                $message = "ຂໍ້ມູນລຶບສຳເລັດ!";

        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
     
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);

        }

        public function print80($id){

            $rec = Receipt::join('users','receipts.user_id','=','users.id')
            ->select('receipts.*','users.user_name as user_name')
            ->where('receipts.rec_id',$id)->first();
            $rec_list = ReceiptList::where('rec_id',$id)->get();

            $setting = Setting::first();
            // return $rec->created_at;

            $date = explode(' ',$rec->created_at);
            $new_date =  explode('-',$date[0]);
            $tr_date = $new_date[2].'/'.$new_date[1].'/'.$new_date[0];

            // get customer payed from transection
            $payed = Transection::where(['rec_id'=>$id,'fn'=>'REC'])->sum('price');
            // get cash back from transection
            $cash_back = Transection::where(['rec_id'=>$id,'fn'=>'PB'])->sum('price');


            return view('80')
            ->with('setting',$setting)
            ->with('customer_payed',$payed)
            ->with('cash_back',$cash_back)
            ->with('date',$tr_date)
            ->with('time',$date[1])
            ->with('id',$id)
            ->with('rec',$rec)
            ->with('rec_list',$rec_list);
        }

        public function printa4($id){

            $rec = Receipt::join('users','receipts.user_id','=','users.id')
            ->select('receipts.*','users.user_name as user_name')
            ->where('receipts.rec_id',$id)->first();
            $rec_list = ReceiptList::where('rec_id',$id)->get();
            $setting = Setting::first();
            // return $rec->created_at;

            $date = explode(' ',$rec->created_at);
            $new_date =  explode('-',$date[0]);
            $tr_date = $new_date[2].'/'.$new_date[1].'/'.$new_date[0];

            // get customer payed from transection
            $payed = Transection::where(['rec_id'=>$id,'fn'=>'REC'])->sum('price');
            // get cash back from transection
            $cash_back = Transection::where(['rec_id'=>$id,'fn'=>'PB'])->sum('price');


            return view('a4')
            ->with('setting',$setting)
            ->with('customer_payed',$payed)
            ->with('cash_back',$cash_back)
            ->with('date',$tr_date)
            ->with('time',$date[1])
            ->with('id',$id)
            ->with('rec',$rec)
            ->with('rec_list',$rec_list);
        }

        public function printquo($id){

            $rec = Receipt::join('users','receipts.user_id','=','users.id')
            ->select('receipts.*','users.user_name as user_name')
            ->where('receipts.rec_id',$id)->first();
            $rec_list = ReceiptList::where('rec_id',$id)->get();
            $setting = Setting::first();
            // return $rec->created_at;

            $date = explode(' ',$rec->created_at);
            $new_date =  explode('-',$date[0]);
            $tr_date = $new_date[2].'/'.$new_date[1].'/'.$new_date[0];

            // get customer payed from transection
            $payed = Transection::where(['rec_id'=>$id,'fn'=>'REC'])->sum('price');
            // get cash back from transection
            $cash_back = Transection::where(['rec_id'=>$id,'fn'=>'PB'])->sum('price');


            return view('quo')
            ->with('setting',$setting)
            ->with('customer_payed',$payed)
            ->with('cash_back',$cash_back)
            ->with('date',$tr_date)
            ->with('time',$date[1])
            ->with('id',$id)
            ->with('rec',$rec)
            ->with('rec_list',$rec_list);
            }

}
