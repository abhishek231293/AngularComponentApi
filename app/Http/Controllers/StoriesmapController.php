<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\AtmDetail;
use Mockery\CountValidator\Exception;

class StoriesmapController extends Controller
{
    public function index() {
        return view('storiesmap.index');
    }

    public function getAtmDetails(Request $request){
        try{
            $data = $request->all();
            $poiData = $request->input('data');
            $poiZone = $request->input('zone');

            $atmDetail = new AtmDetail();

            $atmRowsets = $atmDetail->getAtmDetailList($data);

            $returnRequest = array();
            if($atmRowsets){
                $returnRequest['status'] = 'success';
                $returnRequest['data'] = $atmRowsets;
            }else{
                $returnRequest['status'] = 'success';
                $returnRequest['data'] = array();
            }
            return $returnRequest;

        }catch(Exception $e){
            $returnRequest['status'] = 'error';
            $returnRequest['data'] = array();
            $returnRequest['message'] = $e->getMessage();
            return $returnRequest;
        }

    }

    public function getBranchDetails(Request $request){
        try{
            $data = $request->all();

            $branchDetail = new Branch();

            $branchRowsets = $branchDetail->getStoriesBranch($data);

            $returnRequest = array();
            if($branchRowsets){
                $returnRequest['status'] = 'success';
                $returnRequest['data'] = $branchRowsets;
            }else{
                $returnRequest['status'] = 'success';
                $returnRequest['data'] = array();
            }
//            dd($returnRequest);
            return $returnRequest;

        }catch(Exception $e){
            $returnRequest['status'] = 'error';
            $returnRequest['data'] = array();
            $returnRequest['message'] = $e->getMessage();
            return $returnRequest;
        }

    }

    public function consolidatedSearch(Request $request){

        try{
            $data = $request->all();

            $allBankModel = new Branch();
            $returnRowSet = $allBankModel->consolidatedSearch($data);

            if(!$returnRowSet){
                $type = 'ATM';
                $atmModel = new AtmDetail();
                $returnRowSet = $atmModel->consolidatedSearch($data);
            }else{
                $type = 'Branch';
            }

            if($returnRowSet){
                $returnRequest['status'] = 'success';

                if($data['currentRoute'] == 'analysis' || $data['currentRoute'] == 'assets'){
                    $returnRequest['data']['allData'] = $returnRowSet;
                }else{
                    $returnRequest['data'] = $returnRowSet;
                }
                $returnRequest['type'] = $type;
            }else{
                $returnRequest['status'] = 'success';
                if($data['currentRoute'] == 'analysis' || $data['currentRoute'] == 'assets'){
                    $returnRequest['data']['allData'] = array();
                }else{
                    $returnRequest['data'] = array();
                }
            }
            return $returnRequest;

        }catch(Exception $e){
            $returnRequest['status'] = 'error';
            $returnRequest['data'] = array();
            $returnRequest['message'] = $e->getMessage();
            return $returnRequest;
        }

    }

    public function saveImageMap(Request $request){
        $data['photo'] = $request->imageData;
        // dd($data);

        $profile_image = $this->uploadFile($data['photo'],'photo',true);
        dd($profile_image);
    }



    private function uploadFile($file,$type='',$is_data_string=false)
    {


        if (!$file) return null;
        try {


            $name = uniqid() . '.png';
            $img = trim($file);
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file =storage_path().'/app/public/'.$name;
            $success = file_put_contents($file, $data);
            return $name;

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Updating failed. Please try again later.',
            ]);


        }
    }
}
