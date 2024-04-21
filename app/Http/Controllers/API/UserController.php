<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Setting;
use App\Models\Roles;
use JWTAuth;

class UserController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth:api', ['except'=>['login','register']]);
        // $this->middleware('auth:api');
    }

    public function login(Request $request){

        $user_login = [
            'user_name'=>$request->login_user,
            'password'=>$request->login_password
        ];

        $token = JWTAUTH::attempt($user_login); // ກວດຊອບ ອີເມວລ໌ ແລະ ລະຫັດຜ່ານ ແລ້ວສ້າງ token ຂື້ນມາ
        $user = Auth::user(); // ຫຼັງຈາກ login ສຳເລັດແມ່ນ ດຶງຂໍ້ມູນ User ທີ່ login ອອກມາ
        $permissions = Roles::where('id',$user->roles)->first(); // ດຶງຂໍ້ມູນສິດທິທີ່ມີຢູ່
        $setting = Setting::first(); // ດຶງຂໍ້ມູນຕັ້ງຄ່າທີ່ມີຢູ່

        if($token){
            return response()->json([
                'success' => true,
                'message' => 'ສຳເລັດ!',
                'permissions' => $permissions->permission_access,
                'setting' => $setting,
                'user' => $user,
                'token' => $token
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານບໍ່ຖຶກຕ້ອງ!',
            ]);
        }

    }

    public function logout(){
            $token = JWTAuth::getToken();
            $invalidate = JWTAuth::invalidate($token);
            if($invalidate){
                return response()->json([
                    'success' => true,
                    'message' => 'ສຳເລັດ!',
                ]);
            }
    }


    public function index(){

       if(checkRoles('USER_ACC')==false){
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

        $user = User::orderBy('id',$sort)
        ->where(
            function($query) use ($search){
                $query->where('name','LIKE',"%{$search}%")
                ->orWhere('last_name','LIKE',"%{$search}%")
                ->orWhere('address','LIKE',"%{$search}%");
            }
        )
        ->paginate($perpage);

        $roles = Roles::select('id','role_name')->get();
        
        $response = [
            'user' => $user,
            'roles' => $roles,
            'success' => true,
        ];

        return response()->json($response);
    }

    public function add(Request $request){

        try {

            if($request->file('image')){
                // ຖ້າຫາກວ່າມີ file ຊື່ image ສົ່ງມາໃຫ້ເຮັດວຽກຢູ່ນີ້
                $upload_path = "assets/img";
                $generate_new_name = time().".".$request->image->getClientOriginalExtension();
                $request->image->move(public_path($upload_path),$generate_new_name);
            } else {
                $generate_new_name = "";
            }
            
            
            $user = new User();
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->gender = $request->gender;
            $user->staff_type = $request->staff_type;
            if($request->staff_type=='user'){
                $user->user_name = $request->user_name;
                $user->password = hash::make($request->password); 
            } else {
                $user->user_name = null;
                $user->password = null;
            }
            $user->image = $generate_new_name;
            $user->tel = $request->tel;
            $user->address = $request->address;
            $user->roles = $request->roles;
            $user->user_type = 'user';
            $user->status = $request->status;
            $user->save();

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
        
        if(checkRoles('USER_ACC_EDIT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        $user = User::find($id);
        $roles = Roles::select('id','role_name')->get();

        $response = [
            'user' => $user,
            'roles' => $roles,
            'success' => true,
        ];
        return response()->json($response);

    }

    public function update(Request $request, $id){

        if(checkRoles('USER_ACC_EDIT')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        try {

            


            // ຖ້າຫາກວ່າມີ file ຊື່ image ສົ່ງມາໃຫ້ເຮັດວຽກຢູ່ນີ້
            $upload_path = "assets/img";

            $user = User::find($id);

            if($request->file('image')){

                // remove old image check if exist
                if($user->image != ''){
                    $image_path = public_path($upload_path).'/'.$user->image;
                    if(file_exists($image_path)){
                        unlink($image_path);
                    }
                }
                
                
                $generate_new_name = time().".".$request->image->getClientOriginalExtension();
                $request->image->move(public_path($upload_path),$generate_new_name);
                

                

            } else {
                
                if($request->image == ''){
                    $user->image = '';
                    $generate_new_name = '';
                } else {
                    $generate_new_name = $user->image;
                }
        
            }


            $user->name = $request->name;
                $user->last_name = $request->last_name;
                $user->gender = $request->gender;
                $user->staff_type = $request->staff_type;
                if($request->staff_type=='user'){
                    $user->user_name = $request->user_name;
                    $request->password?$user->password = hash::make($request->password):null;
                    // $user->password =$request->password?hash::make($request->password):null; 
                } else {
                    $user->user_name = null;
                    $user->password = null;
                }
                $user->image = $generate_new_name;
                $user->tel = $request->tel;
                $user->address = $request->address;
                $user->roles = $request->roles;
                $user->status = $request->status;
                $user->save();

            

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

        if(checkRoles('USER_ACC_DEL')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

        try {
            
            $user = User::find($id);
            $upload_path = "assets/img";

                if($user->image != ''){
                    $image_path = public_path($upload_path).'/'.$user->image;
                    if(file_exists($image_path)){
                        unlink($image_path);
                    }
                
                $user->delete();

                $success = true;
                $message = 'ລົບຂໍ້ມູນ ສຳເລັດ!';
            }

           
            

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
