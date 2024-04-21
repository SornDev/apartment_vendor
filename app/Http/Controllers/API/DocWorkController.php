<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// auth
use Illuminate\Support\Facades\Auth;
use App\Models\DocWork;
use App\Models\DocWorkList;
use App\Models\DocumentCategory;
use App\Models\Receipt;
use App\Models\ReceiptList;
use JWTAuth;


class DocWorkController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index(){

        if(checkRoles('DOC_ACC')==false){
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
        $doc_cat = \Request::query('doc_cat');
        
        if($user_type=='admin'){

        $docwork = DocWork::join('document_categories','doc_works.doc_cat','=','document_categories.id')    
        ->join('users','doc_works.user_id','=','users.id')
        ->join('receipts','doc_works.dw_id','=','receipts.doc_work_id')
        // join docworklist
        // ->join('doc_work_lists','doc_works.dw_id','=','doc_work_lists.doc_work_id')
        ->select('doc_works.*','document_categories.name as doc_cat_name','users.user_name as user_name','receipts.status as rec_status')
        ->where('doc_works.status','LIKE',"%{$status}%")
        ->where('doc_works.doc_cat','LIKE',"%{$doc_cat}%")
        ->where(
            function($query) use ($search){
                $query->where('doc_works.customer_name','LIKE',"%{$search}%")
                ->orWhere('doc_works.customer_tel','LIKE',"%{$search}%")
                ->orWhere('document_categories.name','LIKE',"%{$search}%")
                ->orWhere('doc_works.dw_id','LIKE',"%{$search}%");
            }
        )
        
        ->orderBy('doc_works.id',$sort)
        ->paginate($perpage);
        
        } else {
            $docwork = DocWork::join('document_categories','doc_works.doc_cat','=','document_categories.id')    
            ->join('users','doc_works.user_id','=','users.id')
            ->join('receipts','doc_works.dw_id','=','receipts.doc_work_id')
            // join docworklist
            // ->join('doc_work_lists','doc_works.dw_id','=','doc_work_lists.doc_work_id')
            ->select('doc_works.*','document_categories.name as doc_cat_name','users.user_name as user_name','receipts.status as rec_status')
            ->where('doc_works.status','LIKE',"%{$status}%")
            ->where('doc_works.doc_cat','LIKE',"%{$doc_cat}%")
            ->where(
                function($query) use ($search){
                    $query->where('doc_works.customer_name','LIKE',"%{$search}%")
                    ->orWhere('doc_works.customer_tel','LIKE',"%{$search}%")
                    ->orWhere('document_categories.name','LIKE',"%{$search}%")
                    ->orWhere('doc_works.dw_id','LIKE',"%{$search}%");
                }
            )
            ->where('doc_works.user_id',$user_id)
            ->orderBy('doc_works.id',$sort)
            ->paginate($perpage);
        }

        // calculate total file in docworklist
        foreach($docwork as $doc){
            $docworklist = DocWorkList::where('doc_work_id',$doc->dw_id)->get();
            $total_all_file = count($docworklist);

            $docworklist_file = DocWorkList::where('doc_work_id',$doc->dw_id)->where('doc_copy','!=','')->get();
            $total_file = count($docworklist_file);

            $doc->work_file = round($total_file*100/$total_all_file,0);
        }


        return response()->json($docwork);
    }

    public function add(Request $request){
        if(checkRoles('DOC_ACC_EDIT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        try {

            $upload_path_file = "assets/file";

            $docwork_id = 'D'.date('Y').rand(1000,9999);
            
            $docwork = new DocWork();
            $docwork->dw_id = $docwork_id;
            $docwork->doc_cat = $request->doc_form['doc_cat'];
            $docwork->price = isset($request->doc_form['price'])?$request->doc_form['price']:0; 
            $docwork->customer_id = $request->doc_form['customer_id'];
            $docwork->customer_name = $request->doc_form['customer_name'];
            $docwork->customer_tel = $request->doc_form['customer_tel'];
            $docwork->customer_address = $request->doc_form['customer_address'];
            $docwork->status = 'padding';
            // get user login id
            $docwork->user_id = Auth::user()->id;
            $docwork->save();

            //  create receipt
            $receipt_id = 'RE'.date('Y').rand(1000,9999);
            $receipt = new Receipt();
            $receipt->rec_id = $receipt_id;
            $receipt->doc_work_id = $docwork_id;
            $receipt->customer_id = $request->doc_form['customer_id'];
            $receipt->customer_name = $request->doc_form['customer_name'];
            $receipt->customer_tel = $request->doc_form['customer_tel'];
            $receipt->customer_address = $request->doc_form['customer_address'];
            $receipt->status = 'padding';
            $receipt->user_id = Auth::user()->id;;
            $receipt->save();

            // get document category name
            $doc_cat_name = DocumentCategory::find($request->doc_form['doc_cat'])->name;

            // add receipt list
            $receiptlist = new ReceiptList();
            $receiptlist->rec_id = $receipt_id;
            $receiptlist->rec_name = $doc_cat_name;
            $receiptlist->qty = 1;
            $receiptlist->price = isset($request->doc_form['price'])?$request->doc_form['price']:0;
            $receiptlist->save();

            foreach($request->list_doc as $doc){

              // check doc_copy
                if(isset($doc['doc_copy'])){
                    // random name
                    $nname = rand(1000,9999);
                    $new_name_doc_copy = $nname.time().".".$doc['doc_copy']->getClientOriginalExtension();
                    $doc['doc_copy']->move(public_path($upload_path_file),$new_name_doc_copy);
                } else {
                    $new_name_doc_copy = "";
                }

                // add doc work list
                $docworklist = new DocWorkList();
                $docworklist->doc_work_id = $docwork_id;
                $docworklist->doc_id = $doc['doc_id'];
                $docworklist->doc_copy = $new_name_doc_copy;
                $docworklist->notice = isset($doc['notice'])? $doc['notice']:'';
                $docworklist->status = $new_name_doc_copy?'success':'padding';
                $docworklist->save();
                
                // if all $new_name_doc_copy not empty update status to success
               
                
            }

            // check all doc_copy is not empty update status to success
            $docworklist = DocWorkList::where('doc_work_id',$docwork_id)->get();
            $count = 0;
            foreach($docworklist as $doc){
                if($doc->doc_copy != ''){
                    $count++;
                }
            }

            if(count($docworklist) == $count){
                $docwork->status = 'success';
                $docwork->save();
            }

            

            $success = true;
            $message = 'ບັນທຶກຂໍ້ມູນ ສຳເລັດ!';

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

    public function edit($id){

        if(checkRoles('DOC_ACC_EDIT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        $docwork = DocWork::find($id);
        $docwork_list = DocWorkList::where('doc_work_id',$docwork->dw_id)->get();
        $response = [
            'docwork' => $docwork,
            'docwork_list' => $docwork_list,
        ];
        return response()->json($response);

    }

    public function update(Request $request, $id){

        if(checkRoles('DOC_ACC_EDIT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        try {

            $upload_path_file = "assets/file";

            $docwork = DocWork::find($id);
            $doc_dw_id = $docwork->dw_id;
            $doc_cat_old = $docwork->doc_cat;
            $docwork->doc_cat = $request->doc_form['doc_cat'];
            $docwork->price = isset($request->doc_form['price'])?$request->doc_form['price']:0;
            $docwork->customer_id = $request->doc_form['customer_id'];
            $docwork->customer_name = $request->doc_form['customer_name'];
            $docwork->customer_tel = $request->doc_form['customer_tel'];
            $docwork->customer_address = $request->doc_form['customer_address'];
            // $docwork->status = $request->doc_form['status'];
            // $docwork->user_id = 1;
            $docwork->save();

            //

            // update doc work list


            if($request->doc_form['doc_cat'] == $doc_cat_old){
                foreach($request->list_doc as $doc){
                         // update doc work list
                         $docworklist = DocWorkList::where('doc_work_id',$docwork->dw_id)->where('doc_id',$doc['doc_id'])->first();
                        if(isset($doc['doc_copy'])){
                            
                            if(file_exists($doc['doc_copy'])){
                                // remove old doc_copy check if exist
                                if($docworklist->doc_copy){
                                    $image_path = public_path($upload_path_file).'/'.$docworklist->doc_copy;
                                    if(file_exists($image_path)){
                                        unlink($image_path);
                                    }
                                }
                                // random name
                                $nname = rand(1000,9999);
                                $new_name_doc_copy = $nname.time().".".$doc['doc_copy']->getClientOriginalExtension();
                                $doc['doc_copy']->move(public_path($upload_path_file),$new_name_doc_copy);
                                $docworklist->doc_copy = $new_name_doc_copy;
                            } else {
                                if($doc['doc_copy'] == '') {
                                    $image_path = public_path($upload_path_file).'/'.$docworklist->doc_copy;
                                        if(file_exists($image_path)){
                                            unlink($image_path);
                                        }
                                    $docworklist->doc_copy = "";
                                }
                            }
                        } 
                        $docworklist->notice = isset($doc['notice'])?$doc['notice']:'';
                        $docworklist->status = $docworklist->doc_copy?'success':'padding';
                        $docworklist->save();
                 }

            } else {

                // delete old doc work list
                $docworklist = DocWorkList::where('doc_work_id',$doc_dw_id)->get();
                foreach($docworklist as $doc_old){
                    $image_path = public_path($upload_path_file).'/'.$doc_old->doc_copy;
                    if($doc_old->doc_copy){
                        if(file_exists($image_path)){
                            unlink($image_path);
                        } 
                    }
                    
                    $doc_old->delete();
                }

                // add new doc work list
                foreach($request->list_doc as $doc){
                    // check doc_copy
                    if(isset($doc['doc_copy'])){
                        // random name
                        $nname = rand(1000,9999);
                        $new_name_doc_copy = $nname.time().".".$doc['doc_copy']->getClientOriginalExtension();
                        $doc['doc_copy']->move(public_path($upload_path_file),$new_name_doc_copy);
                    } else {
                        $new_name_doc_copy = "";
                    }

                    // add doc work list
                    $docworklist = new DocWorkList();
                    $docworklist->doc_work_id = $docwork->dw_id;
                    $docworklist->doc_id = $doc['doc_id'];
                    $docworklist->doc_copy = $new_name_doc_copy;
                    $docworklist->notice = isset($doc['notice'])? $doc['notice']:'';
                    $docworklist->status = $new_name_doc_copy?'success':'padding';
                    $docworklist->save();
                    
                    // if all $new_name_doc_copy not empty update status to success
                }
            }



             // check all doc_copy is not empty update status to success
             $docworklist = DocWorkList::where('doc_work_id',$docwork->dw_id)->get();
             $count = 0;
             foreach($docworklist as $doc){
                 if($doc->doc_copy != ''){
                     $count++;
                 }
             }
 
             if(count($docworklist) == $count){
                 $docwork->status = 'success';
                 $docwork->save();
             }

            

            $success = true;
            $message = 'ແກ້ໄຂຂໍ້ມູນ ສຳເລັດ!';

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


        if(checkRoles('DOC_ACC_DEL')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        try {
            
            $docm = DocWork::find($id);
            
            $upload_path_file = "assets/file";

            $docworklist = DocWorkList::where('doc_work_id',$docm->dw_id)->get();
            foreach($docworklist as $doc){
                $image_path = public_path($upload_path_file).'/'.$doc->doc_copy;
                if($doc->doc_copy){
                    if(file_exists($image_path)){
                        unlink($image_path);
                    } 
                }
                
                $doc->delete();
            }

          
            $docm->delete();

            

           
            $success = true;
            $message = 'ລົບຂໍ້ມູນ ສຳເລັດ!';

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
}
