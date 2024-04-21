<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transection;

class ReportController extends Controller
{
    //

    public function __construct(){
        $this->middleware("auth:api");
    }

    public function finance(Request $request){



    }
}
