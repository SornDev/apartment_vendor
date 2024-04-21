<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index(){
        
        if(checkRoles('SET_ACC')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

       $setting = Setting::first();
         return response()->json($setting);
    }

    public function update(Request $request){
        if(checkRoles('SET_ACC')==false){
            $success = false;
            $message = 'ທ່ານ ບໍ່ມີສິດເຂົ້ງເຖິງຂໍ້ມູນ!';
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }

      try{

        $setting = Setting::first();

        $upload_path_image = "assets/img";

        // check image
        if($request->file('company_logo')){

            if($request->company_logo != ''){
                $image_path = public_path($upload_path_image).'/'.$request->company_logo;
                if(file_exists($image_path)){
                    unlink($image_path);
                }
            }

            $new_name_img = time().".".$request->company_logo->getClientOriginalExtension();
            $request->company_logo->move(public_path($upload_path_image),$new_name_img);
            $setting->company_logo = $new_name_img;
        } else {
            if($request->company_logo == ''){

                if($setting->company_logo != ''){
                    $image_path = public_path($upload_path_image).'/'.$setting->company_logo;
                    if(file_exists($image_path)){
                        unlink($image_path);
                    }
                }

                $setting->company_logo= '';
            } 
        }

        $setting->company_name = $request->company_name;
        $setting->company_tel = $request->company_tel;
        $setting->company_address = $request->company_address;
        $setting->company_email = $request->company_email;
        
        $setting->printer_default = $request->printer_default;
        $setting->tax_value = $request->tax_value;
        $setting->save();

        // $setting->update([
        //     'company_name' => $request->company_name,
        //     'company_tel' => $request->company_tel,
        //     'company_address' => $request->company_address,
        //     'company_email' => $request->company_email,
        //     'company_logo' => $new_name_img,
        //     'printer_default' => $request->printer_default,
        //     'tax_value' => $request->tax_value,
        // ]);

        $success = true;
        $message = "ອັບເດດຂໍ້ມູນສຳເລັດ!";

    } catch (\Illuminate\Database\QueryException $ex) {
        $success = false;
        $message = $ex->getMessage();
 
    }

        $response = [
            'success' => $success,
            'message' => $message,
            'setting' => $setting,
        ];
        return response()->json($response);
    }
}
