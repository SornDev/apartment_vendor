<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;

class DocController extends Controller
{
    //


    public function index(){

        $doc = Document::orderBy('id','asc')->get();
        return response()->json($doc);
    }

    public function getdoc($id){

        $search = \Request::query('search');

        $doc = Document::orderBy('id','asc')
        // search
        ->where('doc_name','LIKE',"%{$search}%")
        ->where('cat_id',$id)
        ->get();
        return response()->json($doc);
    }

    public function add(Request $request){

        try {


            $upload_path_image = "assets/file";
            $upload_path_file = "assets/file";

            // check image
            if($request->file('image')){
                $new_name_img = time().".".$request->image->getClientOriginalExtension();
                $request->image->move(public_path($upload_path_image),$new_name_img);
            } else {
                $new_name_img = "";
            }

            // check doc_example
            if($request->file('doc_example')){
                $new_name_doc_example = time().".".$request->doc_example->getClientOriginalExtension();
                $request->doc_example->move(public_path($upload_path_file),$new_name_doc_example);
            } else {
                $new_name_doc_example = "";
            }

            // check doc_form
            if($request->file('doc_form')){
                $new_name_doc_form = time().".".$request->doc_form->getClientOriginalExtension();
                $request->doc_form->move(public_path($upload_path_file),$new_name_doc_form);
            } else {
                $new_name_doc_form = "";
            }
            
            
            $doc = new Document();
            $doc->cat_id = $request->cat_id;
            $doc->doc_name = $request->doc_name;
            $doc->image = $new_name_img;
            $doc->doc_example = $new_name_doc_example;
            $doc->doc_form = $new_name_doc_form;
            $doc->notice = $request->notice;
            $doc->save();

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

        $doc = Document::find($id);
        return response()->json($doc);

    }

    public function update(Request $request, $id){

        try {


          
            $upload_path_image = "assets/file";
            $upload_path_file = "assets/file";

            $doc = Document::find($id);

            // check image
            if($request->file('image')){
                // remove old image check if exist
                if($doc->image != ''){
                    $image_path = public_path($upload_path_image).'/'.$doc->image;
                    if(file_exists($image_path)){
                        unlink($image_path);
                    }
                }
                $new_name_img = time().".".$request->image->getClientOriginalExtension();
                $request->image->move(public_path($upload_path_image),$new_name_img);
                $doc->image = $new_name_img;
                
            } else {
       
                if($request->image == ''){
                   
                    // remove old image check if exist
                    if($doc->image != ''){
                        $image_path = public_path($upload_path_image).'/'.$doc->image;
                        if(file_exists($image_path)){
                            unlink($image_path);
                        }
                    }
                    $doc->image = '';
                } 
            }

            // check doc_example
            if($request->file('doc_example')){
                // remove old image check if exist
                if($doc->doc_example != ''){
                    $image_path = public_path($upload_path_file).'/'.$doc->doc_example;
                    if(file_exists($image_path)){
                        unlink($image_path);
                    }
                }
                $new_name_doc_example = time().".".$request->doc_example->getClientOriginalExtension();
                $request->doc_example->move(public_path($upload_path_file),$new_name_doc_example);
                $doc->doc_example = $new_name_doc_example;
                
            } else {
                if($request->doc_example == ''){
                    // remove old image check if exist
                    if($doc->doc_example != ''){
                        $image_path = public_path($upload_path_file).'/'.$doc->doc_example;
                        if(file_exists($image_path)){
                            unlink($image_path);
                        }
                    }
                    $doc->doc_example = '';
                }
            }

            // check doc_form
            if($request->file('doc_form')){
                // remove old image check if exist
                if($doc->doc_form != ''){
                    $image_path = public_path($upload_path_file).'/'.$doc->doc_form;
                    if(file_exists($image_path)){
                        unlink($image_path);
                    }
                }
                $new_name_doc_form = time().".".$request->doc_form->getClientOriginalExtension();
                $request->doc_form->move(public_path($upload_path_file),$new_name_doc_form);
                $doc->doc_form = $new_name_doc_form;
                
            } else {
                if($request->doc_form == ''){
                    // remove old image check if exist
                    if($doc->doc_form != ''){
                        $image_path = public_path($upload_path_file).'/'.$doc->doc_form;
                        if(file_exists($image_path)){
                            unlink($image_path);
                        }
                    }
                    $doc->doc_form = '';
                }
            }

            $doc->cat_id = $request->cat_id;
            $doc->doc_name = $request->doc_name;
            $doc->notice = $request->notice;
            $doc->save();

            

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

        try {
            
            $doc = Document::find($id);
            $upload_path_image = "assets/file";
            $upload_path_file = "assets/file";

            // remove image check if exist
            if($doc->image != ''){
                $image_path = public_path($upload_path_image).'/'.$doc->image;
                if(file_exists($image_path)){
                    unlink($image_path);
                }
            }

            // remove doc_example check if exist
            if($doc->doc_example != ''){
                $image_path = public_path($upload_path_file).'/'.$doc->doc_example;
                if(file_exists($image_path)){
                    unlink($image_path);
                }
            }

            // remove doc_form check if exist

            if($doc->doc_form != ''){
                $image_path = public_path($upload_path_file).'/'.$doc->doc_form;
                if(file_exists($image_path)){
                    unlink($image_path);
                }
            }

            $doc->delete();

            

           
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
