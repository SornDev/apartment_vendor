<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transection;
use App\Models\DocWork;
use App\Models\DocWorkList;
use App\Models\User;
use App\Models\Receipt;
use JWTAuth;

class TransectionController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index()
    {

        if(checkRoles('ACC_ACC')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        $search = \Request::query('search');
        $dmy = \Request::query('dmy');
        $date = \Request::query('date');

        // get d, m, y from date

            $d = date('Y-m-d',strtotime($date));
     
            $m= date('m',strtotime($date));
    
            $y = date('Y',strtotime($date));
        

        // get tran paginated join user
        $tran = Transection::join('users','transections.user_id','=','users.id')
            ->where('transections.fn','!=','VAT')
            ->select('transections.*','users.name as user_name' )
            // ->where('transections.status','success')
            ->where(
                function($query) use ($search){
                    $query->where('transections.tran_id','LIKE',"%{$search}%")
                    ->orWhere('transections.rec_id','LIKE',"%{$search}%")
                    ->orWhere('transections.tran_details','LIKE',"%{$search}%");
                }
            )
            // where date, month, year
            ->where(
                function($query) use ($dmy,$date,$d,$m,$y){
                    if($dmy == 'd'){
                        $query->whereDate('transections.tran_date',$date);
                    }
                    if($dmy == 'm'){
                        // where month and year
                        $query->whereMonth('transections.tran_date',$m);
                        $query->whereYear('transections.tran_date',$y);
                        
                    
                    }
                    if($dmy == 'y'){
                        // $query->whereDate('transections.tran_date',$d);
                        // $query->whereMonth('transections.tran_date',$m);
                        $query->whereYear('transections.tran_date',$y);
                    }
                }
            )
            ->orderBy('transections.id','desc')
            ->paginate(10);
        


            return response()->json($tran);
    }

    public function delete($id){

        if(checkRoles('ACC_REJECT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        // try catch change status to reject
        try {
            $tran = Transection::find($id);
            $tran->status = 'reject';
            $tran->save();
            $success = true;
            $message = 'ອັບເດດຂໍ້ມູນ ສຳເລັດ!';

        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);


    }

    public function dashtran(){

        $user_id = JWTAuth::parseToken()->authenticate()->id;
        $user_type = JWTAuth::parseToken()->authenticate()->user_type;

        $type = \Request::query('type');

        if($user_type=='admin'){
            if($type=='padding'){
                $dashtran = DocWork::join('document_categories','doc_works.doc_cat','=','document_categories.id')
                ->join('users','doc_works.user_id','=','users.id')
                // join receipt
                ->join('receipts','doc_works.dw_id','=','receipts.doc_work_id')
                ->select('doc_works.*','document_categories.name','users.name as user_name','receipts.status as rec_status')
                ->where('doc_works.status','padding')
                ->orderBy('doc_works.id','desc')
                ->paginate(10);
            } else {
                $dashtran = DocWork::join('document_categories','doc_works.doc_cat','=','document_categories.id')
                ->join('users','doc_works.user_id','=','users.id')
                ->join('receipts','doc_works.dw_id','=','receipts.doc_work_id')
                ->select('doc_works.*','document_categories.name', 'users.name as user_name','receipts.status as rec_status')
                ->orderBy('doc_works.id','desc')
                ->paginate(10);
            }

        } else {
            if($type=='padding'){
                $dashtran = DocWork::join('document_categories','doc_works.doc_cat','=','document_categories.id')
                ->join('users','doc_works.user_id','=','users.id')
                // join receipt
                ->join('receipts','doc_works.dw_id','=','receipts.doc_work_id')
                ->select('doc_works.*','document_categories.name','users.name as user_name','receipts.status as rec_status')
                ->where('doc_works.status','padding')
                ->where('doc_works.user_id',$user_id)
                ->orderBy('doc_works.id','desc')
                ->paginate(10);
            } else {
                $dashtran = DocWork::join('document_categories','doc_works.doc_cat','=','document_categories.id')
                ->join('users','doc_works.user_id','=','users.id')
                ->join('receipts','doc_works.dw_id','=','receipts.doc_work_id')
                ->select('doc_works.*','document_categories.name', 'users.name as user_name','receipts.status as rec_status')
                ->where('doc_works.user_id',$user_id)
                ->orderBy('doc_works.id','desc')
                ->paginate(10);
            }
        }

        

        foreach($dashtran as $doc){
          
            $docworklist = DocWorkList::where('doc_work_id',$doc->dw_id)->get();
            $total_all_file = count($docworklist);

            $docworklist_file = DocWorkList::where('doc_work_id',$doc->dw_id)->where('doc_copy','!=','')->get();
            $total_file = count($docworklist_file);

            $doc->work_file = round($total_file*100/$total_all_file,0);
        }
 

        $response = [
            'dashtran' => $dashtran,
            // 'message' => $message,
        ];
        return response()->json($response);

    }

    public function gmn(){

        $user_id = JWTAuth::parseToken()->authenticate()->id;
        $user_type = JWTAuth::parseToken()->authenticate()->user_type;
        
        $year = \Request::query('year');

        if($user_type=='admin'){
            if($year=='all'){
                    $cash = Transection::where(['bank_id'=>0,'tran_type'=>'income'])
                    ->sum('price')-Transection::where(['bank_id'=>0,'tran_type'=>'expense'])
                    ->sum('price');
                    $bank = Transection::where(['bank_id'=>'bank','tran_type'=>'income'])
                        ->sum('price')-Transection::where(['bank_id'=>'bank','tran_type'=>'expense'])
                        ->sum('price');
                } else {
                    $cash = Transection::whereYear('tran_date',$year)
                    ->where(['bank_id'=>0,'tran_type'=>'income'])
                    ->sum('price')-Transection::whereYear('tran_date',$year)
                    ->where(['bank_id'=>0,'tran_type'=>'expense'])
                    ->sum('price');
        
                    $bank = Transection::whereYear('tran_date',$year)
                    ->where(['bank_id'=>'bank','tran_type'=>'income'])
                        ->sum('price')-Transection::whereYear('tran_date',$year)
                        ->where(['bank_id'=>'bank','tran_type'=>'expense'])
                        ->sum('price');
                }
        } else {
            if($year=='all'){
                $cash = Transection::where(['bank_id'=>0,'tran_type'=>'income'])
                ->where('user_id',$user_id)
                ->sum('price')-Transection::where(['bank_id'=>0,'tran_type'=>'expense'])
                ->where('user_id',$user_id)
                ->sum('price');
                $bank = Transection::where(['bank_id'=>'bank','tran_type'=>'income'])
                ->where('user_id',$user_id)
                    ->sum('price')-Transection::where(['bank_id'=>'bank','tran_type'=>'expense'])
                    ->sum('price');
            } else {
                $cash = Transection::whereYear('tran_date',$year)
                ->where('user_id',$user_id)
                ->where(['bank_id'=>0,'tran_type'=>'income'])
                ->sum('price')-Transection::whereYear('tran_date',$year)
                ->where('user_id',$user_id)
                ->where(['bank_id'=>0,'tran_type'=>'expense'])
                ->sum('price');
    
                $bank = Transection::whereYear('tran_date',$year)
                ->where('user_id',$user_id)
                ->where(['bank_id'=>'bank','tran_type'=>'income'])
                    ->sum('price')-Transection::whereYear('tran_date',$year)
                    ->where('user_id',$user_id)
                    ->where(['bank_id'=>'bank','tran_type'=>'expense'])
                    ->sum('price');
            }
        }

        // if($year=='all'){
        //     $cash = Transection::where(['bank_id'=>0,'tran_type'=>'income'])
        //     ->sum('price')-Transection::where(['bank_id'=>0,'tran_type'=>'expense'])
        //     ->sum('price');
        //     $bank = Transection::where(['bank_id'=>'bank','tran_type'=>'income'])
        //          ->sum('price')-Transection::where(['bank_id'=>'bank','tran_type'=>'expense'])
        //          ->sum('price');
        // } else {
        //     $cash = Transection::whereYear('tran_date',$year)
        //     ->where(['bank_id'=>0,'tran_type'=>'income'])
        //     ->sum('price')-Transection::whereYear('tran_date',$year)
        //     ->where(['bank_id'=>0,'tran_type'=>'expense'])
        //     ->sum('price');
 
        //     $bank = Transection::whereYear('tran_date',$year)
        //        ->where(['bank_id'=>'bank','tran_type'=>'income'])
        //          ->sum('price')-Transection::whereYear('tran_date',$year)
        //          ->where(['bank_id'=>'bank','tran_type'=>'expense'])
        //          ->sum('price');
        // }

        // add cash and bank to array


        $response =  [$cash,$bank,$cash+$bank];
        return response()->json($response);

    }

    public function dashtop(){

        // doc work all

        $user_id = JWTAuth::parseToken()->authenticate()->id;
        $user_type = JWTAuth::parseToken()->authenticate()->user_type;


        // if user is admin * else get only user doc work
        if($user_type=='admin'){

            $dwall = DocWork::count();
            $dwMonth = DocWork::whereMonth('created_at',date('m'))
            ->whereYear('created_at',date('Y'))
            ->count();

            $dwLastMonth = DocWork::whereMonth('created_at',date('m',strtotime('-1 month')))
            ->whereYear('created_at',date('Y',strtotime('-1 month')))
            ->count();

            $dwYear = DocWork::whereYear('created_at',date('Y'))
            ->count();

            
            if($dwLastMonth==0){
                $dwLastMonth = 1;
            }
            // compare dwMonth and dwLastMonth % and value up or down
            $compare = round(($dwMonth-$dwLastMonth)/$dwLastMonth*100,2); // compare percent
        
            if($compare>0){
                $Textcompare = 'ເພີ່ມຂື້ນຈາກເດືອນກ່ອນ';
            } else {
                $Textcompare = 'ຫຼຸດລົງຈາກເດືອນກ່ອນ ';
            }
            

            // doc work padding ------------------------------------

            $dwpadding = DocWork::where('status','padding')->count();

            $dwpMonth = DocWork::where('status','padding')
            ->whereMonth('created_at',date('m'))
            ->whereYear('created_at',date('Y'))
            ->count();

            $dwpLastMonth = DocWork::where('status','padding')
            ->whereMonth('created_at',date('m',strtotime('-1 month')))
            ->whereYear('created_at',date('Y',strtotime('-1 month')))
            ->count();

            $dwpYear = DocWork::where('status','padding')
            ->whereYear('created_at',date('Y'))
            ->count();

            if($dwpLastMonth==0){
                $dwpLastMonth = 1;
            }

            // compare dwpMonth and dwpLastMonth % and value up or down
            $comparepadding = round(($dwpMonth-$dwpLastMonth)/$dwpLastMonth*100,2); // compare percent

            if($comparepadding>0){
                $Textcomparepadding = 'ເພີ່ມຂື້ນຈາກເດືອນກ່ອນ';
            } else {
                $Textcomparepadding = 'ຫຼຸດລົງຈາກເດືອນກ່ອນ';
            }



            // income -------------------------

            $income = Transection::where('tran_type','income')->sum('price');

            $incomeMonth = Transection::where('tran_type','income')
            ->whereMonth('tran_date',date('m'))
            ->whereYear('tran_date',date('Y'))
            ->sum('price');

            $incomeLastMonth = Transection::where('tran_type','income')
            ->whereMonth('tran_date',date('m',strtotime('-1 month')))
            ->whereYear('tran_date',date('Y',strtotime('-1 month')))
            ->sum('price');

            $incomeYear = Transection::where('tran_type','income')
            ->whereYear('tran_date',date('Y'))
            ->sum('price');

            if($incomeLastMonth==0){
                $incomeLastMonth = 1;
            }

            // compare incomeMonth and incomeLastMonth % and value up or down
            $compareincome = round(($incomeMonth-$incomeLastMonth)/$incomeLastMonth*100,2); // compare percent

            if($compareincome>0){
                $Textcompareincome = 'ເພີ່ມຂື້ນຈາກເດືອນກ່ອນ';
            
            } else {
                $Textcompareincome = 'ຫຼຸດລົງຈາກເດືອນກ່ອນ ';
            }

            // expense -----------------

            $expense = Transection::where('tran_type','expense')->sum('price');

            $expenseMonth = Transection::where('tran_type','expense')
            ->whereMonth('tran_date',date('m'))
            ->whereYear('tran_date',date('Y'))
            ->sum('price');

            $expenseLastMonth = Transection::where('tran_type','expense')
            ->whereMonth('tran_date',date('m',strtotime('-1 month')))
            ->whereYear('tran_date',date('Y',strtotime('-1 month')))
            ->sum('price');

            $expenseYear = Transection::where('tran_type','expense')
            ->whereYear('tran_date',date('Y'))
            ->sum('price');

            if($expenseLastMonth==0){
                $expenseLastMonth = 1;
            }

            // compare expenseMonth and expenseLastMonth % and value up or down
            $compareexpense = round(($expenseMonth-$expenseLastMonth)/$expenseLastMonth*100,2); // compare percent

            if($compareexpense>0){
                $Textcompareexpense = 'ເພີ່ມຂື້ນຈາກເດືອນກ່ອນ';
            } else {
                $Textcompareexpense = 'ຫຼຸດລົງຈາກເດືອນກ່ອນ';
            }

        } else {

            /// by user

            $dwall = DocWork::where('user_id',$user_id)->count();
            $dwMonth = DocWork::whereMonth('created_at',date('m'))
            ->where('user_id',$user_id)
            ->whereYear('created_at',date('Y'))
            ->count();

            $dwLastMonth = DocWork::whereMonth('created_at',date('m',strtotime('-1 month')))
            ->where('user_id',$user_id)
            ->whereYear('created_at',date('Y',strtotime('-1 month')))
            ->count();

            $dwYear = DocWork::whereYear('created_at',date('Y'))
            ->where('user_id',$user_id)
            ->count();

            
            if($dwLastMonth==0){
                $dwLastMonth = 1;
            }
            // compare dwMonth and dwLastMonth % and value up or down
            $compare = round(($dwMonth-$dwLastMonth)/$dwLastMonth*100,2); // compare percent
        
            if($compare>0){
                $Textcompare = 'ເພີ່ມຂື້ນຈາກເດືອນກ່ອນ';
            } else {
                $Textcompare = 'ຫຼຸດລົງຈາກເດືອນກ່ອນ ';
            }
            

            // doc work padding ------------------------------------

            $dwpadding = DocWork::where('status','padding')->where('user_id',$user_id)->count();

            $dwpMonth = DocWork::where('status','padding')
            ->where('user_id',$user_id)
            ->whereMonth('created_at',date('m'))
            ->whereYear('created_at',date('Y'))
            ->count();

            $dwpLastMonth = DocWork::where('status','padding')
            ->where('user_id',$user_id)
            ->whereMonth('created_at',date('m',strtotime('-1 month')))
            ->whereYear('created_at',date('Y',strtotime('-1 month')))
            ->count();

            $dwpYear = DocWork::where('status','padding')
            ->where('user_id',$user_id)
            ->whereYear('created_at',date('Y'))
            ->count();

            if($dwpLastMonth==0){
                $dwpLastMonth = 1;
            }

            // compare dwpMonth and dwpLastMonth % and value up or down
            $comparepadding = round(($dwpMonth-$dwpLastMonth)/$dwpLastMonth*100,2); // compare percent

            if($comparepadding>0){
                $Textcomparepadding = 'ເພີ່ມຂື້ນຈາກເດືອນກ່ອນ';
            } else {
                $Textcomparepadding = 'ຫຼຸດລົງຈາກເດືອນກ່ອນ';
            }



            // income -------------------------

            $income = Transection::where('tran_type','income')->where('user_id',$user_id)->sum('price');

            $incomeMonth = Transection::where('tran_type','income')
            ->where('user_id',$user_id)
            ->whereMonth('tran_date',date('m'))
            ->whereYear('tran_date',date('Y'))
            ->sum('price');

            $incomeLastMonth = Transection::where('tran_type','income')
            ->where('user_id',$user_id)
            ->whereMonth('tran_date',date('m',strtotime('-1 month')))
            ->whereYear('tran_date',date('Y',strtotime('-1 month')))
            ->sum('price');

            $incomeYear = Transection::where('tran_type','income')
            ->where('user_id',$user_id)
            ->whereYear('tran_date',date('Y'))
            ->sum('price');

            if($incomeLastMonth==0){
                $incomeLastMonth = 1;
            }

            // compare incomeMonth and incomeLastMonth % and value up or down
            $compareincome = round(($incomeMonth-$incomeLastMonth)/$incomeLastMonth*100,2); // compare percent

            if($compareincome>0){
                $Textcompareincome = 'ເພີ່ມຂື້ນຈາກເດືອນກ່ອນ';
            
            } else {
                $Textcompareincome = 'ຫຼຸດລົງຈາກເດືອນກ່ອນ ';
            }

            // expense -----------------

            $expense = Transection::where('tran_type','expense')->where('user_id',$user_id)->sum('price');

            $expenseMonth = Transection::where('tran_type','expense')
            ->where('user_id',$user_id)
            ->whereMonth('tran_date',date('m'))
            ->whereYear('tran_date',date('Y'))
            ->sum('price');

            $expenseLastMonth = Transection::where('tran_type','expense')
            ->where('user_id',$user_id)
            ->whereMonth('tran_date',date('m',strtotime('-1 month')))
            ->whereYear('tran_date',date('Y',strtotime('-1 month')))
            ->sum('price');

            $expenseYear = Transection::where('tran_type','expense')
            ->where('user_id',$user_id)
            ->whereYear('tran_date',date('Y'))
            ->sum('price');

            if($expenseLastMonth==0){
                $expenseLastMonth = 1;
            }

            // compare expenseMonth and expenseLastMonth % and value up or down
            $compareexpense = round(($expenseMonth-$expenseLastMonth)/$expenseLastMonth*100,2); // compare percent

            if($compareexpense>0){
                $Textcompareexpense = 'ເພີ່ມຂື້ນຈາກເດືອນກ່ອນ';
            } else {
                $Textcompareexpense = 'ຫຼຸດລົງຈາກເດືອນກ່ອນ';
            }
            
        }



        



        // return all to array

        $DocWork = [$dwall,$dwpYear,$dwpMonth,$dwLastMonth,$compare,$Textcompare];
        $DocPadding = [$dwpadding,$dwpYear,$dwpMonth,$dwpLastMonth,$comparepadding,$Textcomparepadding];
        $Income = [$income,$incomeYear,$incomeMonth,$incomeMonth,$compareincome,$Textcompareincome];
        $Expense = [$expense,$expenseYear,$expenseMonth,$expenseLastMonth,$compareexpense,$Textcompareexpense];

        $response = [
            'DocWork' => $DocWork,
            'DocPadding' => $DocPadding,
            'Income' => $Income,
            'Expense' => $Expense
        ];

        return response()->json($response);


       
    }

    public function incexp(){
            
            $user_id = JWTAuth::parseToken()->authenticate()->id;
            $dmy = \Request::query('dmy');
            $date = \Request::query('date');

            // get month from date
            $month = date('m',strtotime($date));
            $year = date('Y',strtotime($date));

            $dm_name = [];
            $sum_income = [];
            $sum_expense = [];
            $sum_profit = [];

            if($dmy == 'm'){


                // get last day of month
                $last_day = date("t",strtotime($date));
                
                // get day name
                for($i=1;$i<=$last_day;$i++){
                    array_push($dm_name,"ວັນທີ ".$i);

                    // sum income where day, month, year, status = success, fn != VAT
                    $income = Transection::where('fn','!=','VAT')
                    ->where(['tran_type'=>'income','status'=>'success'])
                    ->whereDay('tran_date',$i)
                    ->whereMonth('tran_date',$month)
                    ->whereYear('tran_date',$year)
                    ->sum('price');

                    array_push($sum_income,$income);

                    // sum expense where day, month, year, status = success, fn != VAT
                    $expense = Transection::where('fn','!=','VAT')
                    ->where(['tran_type'=>'expense','status'=>'success'])
                    ->whereDay('tran_date',$i)
                    ->whereMonth('tran_date',$month)
                    ->whereYear('tran_date',$year)
                    ->sum('price');

                    array_push($sum_expense,$expense);

                    // sum profit where day, month, year
                    array_push($sum_profit,$income-$expense);
                    



                }


            } else if($dmy == 'y'){

                for($i=1;$i<=12;$i++){
                    array_push($dm_name,"ເດືອນ ".$i);

                    // sum income where month, year, status = success, fn != VAT
                    $income = Transection::where('fn','!=','VAT')
                    ->where(['tran_type'=>'income','status'=>'success'])
                    ->whereMonth('tran_date',$i)
                    ->whereYear('tran_date',$year)
                    ->sum('price');
                    array_push($sum_income,$income);

                    // sum expense where month, year, status = success, fn != VAT
                    $expense = Transection::where('fn','!=','VAT')
                    ->where(['tran_type'=>'expense','status'=>'success'])
                    ->whereMonth('tran_date',$i)
                    ->whereYear('tran_date',$year)
                    ->sum('price');
                    array_push($sum_expense,$expense);

                    // sum profit where month, year
                    array_push($sum_profit,$income-$expense);
                }
    
                $profit = $income-$expense;

            }

            // sum array income, expense, profit
            $sum_all_income = array_sum($sum_income);
            $sum_all_expense = array_sum($sum_expense);
            $sum_all_profit = array_sum($sum_profit);

           


            $response = [
                'income' => $sum_income,
                'expense' => $sum_expense,
                'profit' => $sum_profit,
                'label' => $dm_name,
                'sum_all_income' => $sum_all_income,
                'sum_all_expense' => $sum_all_expense,
                'sum_all_profit' => $sum_all_profit
            ];
            return response()->json($response);


           // sum expense
    }
}
