<?php

use Illuminate\Support\Facades\Auth;
use App\Models\Roles;
use App\Models\User;


if(!function_exists('checkRoles')){
    function checkRoles($permission){
        // $user = Auth::user();
        $user = JWTAuth::parseToken()->authenticate();
        // echo $user;
        // return $user->roles;
        $role = Roles::find($user->roles)['permission_access'];
        if(in_array($permission,json_decode($role))){
            return true;
        } else {
            return false;
        }
    
    }
}


?>