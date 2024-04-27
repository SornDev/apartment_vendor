<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccType;

class AccTypeController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index(){

        $search = \Request::query('search');
        $perpage = \Request::query('perpage');
        $sort = \Request::query('sort');

        $acctype = AccType::orderBy('id','asc')->get();
        // ->where('acc_type_name','LIKE',"%{$search}%")
        // ->paginate($perpage);

        return response()->json($acctype);

    }

    public function add(Request $request){

        if(checkRoles('ACC_ACC_MG')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        try {

            
            
            $acctype = new AccType();
            $acctype->acc_type_name = $request->acc_type_name;
            $acctype->save();

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

        if(checkRoles('ACC_ACC_MG')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

            $acctype = AccType::find($id);
            return response()->json($acctype);

    }

    public function update(Request $request, $id){

        if(checkRoles('ACC_ACC_MG')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        try {
            
            $acctype = AccType::find($id);
            $acctype->acc_type_name = $request->acc_type_name;
            $acctype->save();

            $success = true;
            $message = 'ປັບປຸງຂໍ້ມູນ ສຳເລັດ!';

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

        if(checkRoles('ACC_ACC_MG')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }
        try {
            
            $acctype = AccType::find($id);
            $acctype->delete();

            $success = true;
            $message = 'ລຶບຂໍ້ມູນ ສຳເລັດ!';

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
