<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transection;

class TransectionController extends Controller
{
    //

    public function index()
    {
        $search = \Request::query('search');
        $dmy = \Request::query('dmy');
        $date = \Request::query('date');

        // get d, m, y from date

            $d = date('Y-m-d',strtotime($date));
     
            $m= date('m',strtotime($date));
    
            $y = date('Y',strtotime($date));
        

        // get tran paginated join user
        $tran = Transection::join('users','transections.user_id','=','users.id')
            ->select('transections.*','users.name as user_name' )
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
}
