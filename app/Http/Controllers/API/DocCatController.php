<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentCategory;
use App\Models\Document;

class DocCatController extends Controller
{
    //

    public function index(){

        $search = \Request::query('search');
        $perpage = \Request::query('perpage');
        $sort = \Request::query('sort');

        $doccat = DocumentCategory::orderBy('id','asc')
        ->where('name','LIKE',"%{$search}%")
        ->paginate($perpage);

        return response()->json($doccat);

    }

    public function add(Request $request){

        try {
            
            $doccat = new DocumentCategory();
            $doccat->name = $request->name;
            $doccat->price = $request->price;
            $doccat->save();

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

        $doccat = DocumentCategory::find($id);
        return $doccat;

    }

    public function update($id,Request $request){

        try {

            $doccat = DocumentCategory::find($id);
            $doccat->name = $request->name;
            $doccat->price = $request->price;
            $doccat->save();

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

            try {

                $doccat = DocumentCategory::find($id);

                // check if doccat has doc
                $doc = Document::where('cat_id',$id)->get();
                if(count($doc) > 0){
                    $success = false;
                    $message = "ບໍ່ສາມາດລຶບຂໍ້ມູນໄດ້ ເນື່ອງຈາກຍັງມີລາຍການເອກະສານ!";
                    $response = [
                        'success' => $success,
                        'message' => $message,
                    ];
                    return response()->json($response);
                } 


                $doccat->delete();

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
