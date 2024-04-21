<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\DocWork;

class CustomerController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index(){

        if(checkRoles('CUS_ACC')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        $search = \Request::query('search');
        $perpage = \Request::query('perpage');
        $sort = \Request::query('sort');

        $cus = Customer::orderBy('id',$sort)
        ->where(
            function($query) use ($search){
                $query->where('name','LIKE',"%{$search}%")
                ->orWhere('last_name','LIKE',"%{$search}%")
                ->orWhere('address','LIKE',"%{$search}%");
            }
        )
        ->paginate($perpage);

        return response()->json($cus);

    }

    public function search(){

        if(checkRoles('CUS_ACC')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        $search = \Request::query('search');
        // $perpage = \Request::query('perpage');
        // $sort = \Request::query('sort');

        $cus = Customer::orderBy('id','asc')
        ->where(
            function($query) use ($search){
                $query->where('name','LIKE',"%{$search}%")
                ->orWhere('last_name','LIKE',"%{$search}%")
                ->orWhere('tel','LIKE',"%{$search}%")
                ->orWhere('address','LIKE',"%{$search}%");
            }
        )->get();

        return response()->json($cus);
    }

    public function add(Request $request){

        if(checkRoles('CUS_ACC_EDIT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        try {
            
            $cus = new Customer();
            $cus->name = $request->name;
            $cus->last_name = $request->last_name;
            $cus->gender = $request->gender;
            $cus->tel = $request->tel;
            $cus->address = $request->address;
            $cus->save();

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

        if(checkRoles('CUS_ACC_EDIT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        $cus = Customer::find($id);
        return $cus;

    }

    public function update($id,Request $request){

        if(checkRoles('CUS_ACC_EDIT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        try {

            $cus = Customer::find($id);
            $cus->name = $request->name;
            $cus->last_name = $request->last_name;
            $cus->gender = $request->gender;
            $cus->tel = $request->tel;
            $cus->address = $request->address;
            $cus->save();

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

        if(checkRoles('CUS_ACC_DEL')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

            try {

                $cus = Customer::find($id);

                // check if doccat has doc
                $doc = DocWork::where('customer_id',$id)->get();
                if(count($doc) > 0){
                    $success = false;
                    $message = "ບໍ່ສາມາດລຶບຂໍ້ມູນໄດ້ ເນື່ອງຈາກຍັງນຳໃຊ້ຂໍ້ມູນ!";
                    $response = [
                        'success' => $success,
                        'message' => $message,
                    ];
                    return response()->json($response);
                } 


                $cus->delete();

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
}
