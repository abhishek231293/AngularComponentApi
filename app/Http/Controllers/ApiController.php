<?php

namespace App\Http\Controllers;

use App\Component;
use App\Project;
use App\Product;
use App\ProjectCategory;
use App\StaffCount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use JWTAuth;
use Mail;

use JWTAuthException;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function __construct()
    {
//        $currentPath    = Route::getFacadeRoot()->current()->uri();
    }

    private function _import_csv($path, $filename,$type = null)
    {
        try{
            $csv = $path .'/'. $filename;
            $fileD = fopen($csv,"r");

            while(!feof($fileD)){
                $rowData[]=fgetcsv($fileD);
            }
            if($type == 'product'){
                foreach ($rowData as $key => $value) {

                    if($key){
                        if($value && isset($value[0]) && $value[0]){
                            $inserted_data[]=array(
                                'product_id'=>$value[0],
                                'product_name'=>$value[1],
                                'is_active'=>$value[2],
                                'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
                                'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
                            );
                        }
                    }
                }

                $productModel = new Product();
                $productModel->down();
                $productModel->up();
                if($inserted_data){
                    $status = $productModel->insert($inserted_data);
                }else{
                    $status = true;
                }
                return $status;
            }else if($type == 'component'){
                foreach ($rowData as $key => $value) {
                    if($key) {
                        if ($value && isset($value[0]) && $value[0]) {
                            $inserted_data[] = array(
                                'component_id' => $value[0],
                                'component_name' => $value[2],
                                'product_id' => $value[1],
                                'is_active' => $value[3],
                                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                            );
                        }
                    }
                }

                $componentModel = new Component();
                $componentModel->down();
                $componentModel->up();
                if($inserted_data){
                    $status = $componentModel->insert($inserted_data);
                }else{
                    $status = true;
                }
                return $status;
            }

            return false;
        }catch(\Exception $e){
            $response = new \stdClass();
            $response->message = $e->getMessage();
            $response->status  = 'error';
            die(json_encode($response));
        }
    }
    public function uploadCsv(Request $request){

        try{
            $data = $request->all();

            $file = $data['csvData'];
            $type = $data['type'];
            $name = $file->getClientOriginalName();
            //check out the edit content on bottom of my answer for details on $storage
            $path = 'csv';

            // Moves file to folder on server
            $file->move($path, $name);

            // Import the moved file to DB and return OK if there were rows affected
            $status = $this->_import_csv($path, $name,$type);
            if($status){
                $response = new \stdClass();
                $response->message = 'Uploaded successfully';
                $response->status  = 'success';
                die(json_encode($response));
            }else{
                $response = new \stdClass();
                $response->message = 'Something went wrong while uploading file.';
                $response->status  = 'error';
                die(json_encode($response));
            }


//            die(json_encode($response));
        }catch(\Exception $e){
            $response = new \stdClass();
            $response->message = $e->getMessage();
            $response->status  = 'error';
            die(json_encode($response));
        }
    }

}
