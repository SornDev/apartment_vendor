<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roles;

class RolesController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index(){
            
            $roles = Roles::orderBy('id','asc')->get();
            return response()->json($roles);

    }

    /// create function add for add new roles use try catch
    public function add(Request $request){

        try {
            
            $roles = new Roles(); 
            $roles->role_name = $request->role_name;
            $roles->permission_access = $request->permission_access;
            $roles->save();

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

        $roles = Roles::find($id);
        return $roles;
    }

    public function update(Request $request, $id){

        try {
            
            $roles = Roles::find($id);
            $roles->role_name = $request->role_name;
            $roles->permission_access = $request->permission_access;
            $roles->save();

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

        try {
            
            $roles = Roles::find($id);
            $roles->delete();

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
