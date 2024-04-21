<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
// use auth
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transection;
use App\Models\User;
use App\Models\Setting;
use JWTAuth;
use PDF;

class ReportController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function finance(Request $request){

        $user_id = JWTAuth::parseToken()->authenticate()->id;
       
        $date_tran = $request->date_tran;
        $dmy = $request->dmy;

        $day = explode("-",$date_tran)[2]; // 2021-05-20
        $month = explode("-",$date_tran)[1]; // 2021-05-20
        $year = explode("-",$date_tran)[0]; // 2021-05-20

         // get yesterday from date_tran
         $yesterday = date("Y-m-d",strtotime("-1 day",strtotime($date_tran)));
         // get last month end date from date_tran
         $last_month = date("Y-m-t",strtotime("-1 month",strtotime($date_tran)));
         // get this month from date_tran
         $this_month = date("m",strtotime($date_tran));
         // get last year from date_tran
         $last_year = date("Y",strtotime("-1 year",strtotime($date_tran)));
         // get start date this year by date_tran
         $start_year = date("Y-01-01",strtotime($date_tran));
         // get start date this month by date_tran
         $start_month = date("Y-m-01",strtotime($date_tran));
         // get end date this month by date_tran
         $end_month = date("Y-m-t",strtotime($date_tran));


         if($dmy == "d"){

            // sum price income after date_tran from transection status success
            // BetweenDates is scope in Transection model
            // echo $yesterday;
    
            // sum count where yesterday from transection status success
            $count_before = Transection::whereDate('tran_date',$yesterday)
            ->where('fn','!=','VAT')
            ->where('status','success')
            ->count();
            // echo $yesterday;
            // echo $count_before;
    
            $sum_income_before = Transection::whereBetween('tran_date',[$start_year,$yesterday])
            ->where('tran_type','income')
            ->where('fn','!=','VAT')
            ->where('status','success')
            ->sum('price');
            
            // sum price expense after date_tran from transection status success
    
            $sum_expense_before = Transection::whereBetween('tran_date',[$start_year,$yesterday])
            ->where('tran_type','expense')
            ->where('fn','!=','VAT')
            ->where('status','success')
            ->sum('price');
    
            $subtotal_before = intval($sum_income_before)-intval($sum_expense_before);
            $subtotal_before_b = 0;
            // get transection date date_tran from transection status success
    
            $transection = Transection::whereDate('tran_date',$date_tran)
            ->where('fn','!=','VAT')
            ->where('status','success')
            ->get();
    
            $loop = 0;
            foreach($transection as $t){
                if($loop == 0){
                    $t['subtotal'] = $subtotal_before;
                } else {
                    $t['subtotal'] = 0;
                }
                $loop++;
            }
    
            if($subtotal_before>0 ){
                $subtotal_before_b = $subtotal_before;
            } else {
                $subtotal_before_b = 0;
            }
    
            foreach($transection as $t){
                if($t['tran_type'] == "income"){
                   $subtotal_before_b += $t['price'];
                    $t['subtotal'] = $subtotal_before_b;
                } else if($t['tran_type'] == "expense"){
                    $subtotal_before_b -= $t['price'];
                    $t['subtotal'] = $subtotal_before_b;
                }
            }
    
    
            } 

            else if($dmy == 'm'){
                // where month same $dmy = 'd'
                // sum price income after date_tran from transection status success
            
                $sum_income_before = Transection::whereBetween('tran_date',[$start_year,$last_month])
                ->where('tran_type','income')
                ->where('fn','!=','VAT')
                ->where('status','success')
                ->sum('price');
    
                // sum price expense after date_tran from transection status success
    
                $sum_expense_before = Transection::whereBetween('tran_date',[$start_year,$last_month])
                ->where('tran_type','expense')
                ->where('fn','!=','VAT')
                ->where('status','success')
                ->sum('price');
    
                $subtotal_before = intval($sum_income_before)-intval($sum_expense_before);
                $subtotal_before_b = 0;
                
                // get transection date date_tran from transection status success
    
                $transection = Transection::whereMonth('tran_date',$this_month)
                ->where('status','success')
                ->where('fn','!=','VAT')
                ->get();
    
                $loop = 0;
                foreach($transection as $t){
                    if($loop == 0){
                        $t['subtotal'] = $subtotal_before;
                    } else {
                        $t['subtotal'] = 0;
                    }
                    $loop++;
                }
    
                if($subtotal_before>0){
                    $subtotal_before_b = $subtotal_before;
                } else {
                    $subtotal_before_b = 0;
                }
    
                foreach($transection as $t){
                    if($t['tran_type'] == "income"){
                       $subtotal_before_b += $t['price'];
                        $t['subtotal'] = $subtotal_before_b;
                    } else if($t['tran_type'] == "expense"){
                        $subtotal_before_b -= $t['price'];
                        $t['subtotal'] = $subtotal_before_b;
                    }
                }
            
                // if($subtotal_before>0){
                //     $subtotal_before = $subtotal_before_b;
                // } 
       
    
            } else if($dmy == 'y'){
                // where year same $dmy = 'd'
                // sum price income after date_tran from transection status success
    
                $sum_income_before = Transection::whereYear('tran_date',$last_year)
                ->where('tran_type','income')
                ->where('fn','!=','VAT')
                ->where('status','success')
                ->sum('price');
    
                // sum price expense after date_tran from transection status success
    
                $sum_expense_before = Transection::whereYear('tran_date',$last_year)
                ->where('tran_type','expense')
                ->where('fn','!=','VAT')
                ->where('status','success')
                ->sum('price');
    
                $subtotal_before = intval($sum_income_before)-intval($sum_expense_before);
                $subtotal_before_b = 0;
                // echo $subtotal_before;
                // get transection date date_tran from transection status success
    
                $transection = Transection::whereYear('tran_date',$year)
                ->where('status','success')
                ->where('fn','!=','VAT')
                ->get();
    
                $loop = 0;
                foreach($transection as $t){
                    if($loop == 0){
                        $t['subtotal'] = $subtotal_before;
                    } else {
                        $t['subtotal'] = 0;
                    }
                    $loop++;
                }
    
                if($subtotal_before>0){
                    $subtotal_before_b = $subtotal_before;
                } else {
                    $subtotal_before_b = 0;
                }
    
                foreach($transection as $t){
                    if($t['tran_type'] == "income"){
                       $subtotal_before_b += $t['price'];
                        $t['subtotal'] = $subtotal_before_b;
                    } else if($t['tran_type'] == "expense"){
                        $subtotal_before_b -= $t['price'];
                        $t['subtotal'] = $subtotal_before_b;
                    }
                }
    
                // if($subtotal_before>0){
                //     $subtotal_before = $subtotal_before_b;
                // } 
            }


             // get hotel data
        $Setting = Setting::first();
        // get user login
        $user = User::find($user_id);
        $pdf = \Request::get('pdf');


        if($pdf == 'true'){
            $pdf = PDF::loadView('ReportPDF/finance',compact('transection','subtotal_before','dmy','date_tran','sum_income_before','sum_expense_before','Setting','user'));
        // $pdf->setPaper( 'portrait');
        $pdf->setPaper('A4', 'portrait');
        $pdf->getDomPDF()->set_option('margin_left', '85.04'); // 3cm to points
        $pdf->getDomPDF()->set_option('margin_right', '56.69'); // 2cm to points
        $pdf->getDomPDF()->set_option('margin_top', '56.69'); // 2cm to points
        $pdf->getDomPDF()->set_option('margin_bottom', '56.69'); // 2cm to points


        $path = public_path('assets/report_pdf/');
            
        // $user_id = Auth::user()->id;

        


        // return $user_id;
        $pdf->save($path.$user_id.'-'.date('YmdHis').'-cashflow_report.pdf');
   

        $file = $path.$user_id.'-'.date('YmdHis').'-cashflow_report.pdf';

        // return $file;
        if (!file_exists($file)) {
            $response = [
                'success' => false,
                'message' => 'File not found',
            ];
        } else {
            $response = [
                'success' => true,
                'fileUrl' => url('assets/report_pdf/' .$user_id.'-'.date('YmdHis').'-cashflow_report.pdf'),
                'sum_income_before' => $sum_income_before,
                'sum_expense_before' => $sum_expense_before,
                'subtotal_before' => $subtotal_before,
                'transection' => $transection
            ];
        }
        
        return response()->json($response);

        } 



        $response = [
            'success' => true,
            'sum_income_before' => $sum_income_before,
            'sum_expense_before' => $sum_expense_before,
            'subtotal_before' => $subtotal_before,
            'transection' => $transection
        ];

        return response()->json($response);


    }
}
