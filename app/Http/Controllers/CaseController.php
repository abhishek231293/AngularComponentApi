<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectCategory;
use App\StaffCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use JWTAuth;
use Mail;
use JWTAuthException;
use Illuminate\Support\Facades\DB;

class CaseController extends Controller
{
    public function __construct()
    {
//        $currentPath    = Route::getFacadeRoot()->current()->uri();
    }

    public function getCategoryList(){

        try{
            $projectCategoryModel = new ProjectCategory();
            $categoryList = $projectCategoryModel->getCategoryList();

            $response = new \stdClass();
            $response->result = $categoryList;
            $response->status  = 'success';
            $response->message = 'Category list successfully fetched';

            die(json_encode($response));
        }catch(\Exception $e){
            $response = new \stdClass();
            $response->message      = $e->getMessage();
            $response->status  = 'error';
            $response->result  = array();
            die(json_encode($response));
        }


    }
    public function getProjectList(){

        try{
            $projectModel = new Project();
            $projectList = $projectModel->getProjectList();
            $response = new \stdClass();
            $response->result = $projectList;
            $response->status  = 'success';
            $response->message = 'Project list fetched';

            die(json_encode($response));
        }catch(\Exception $e){
            $response = new \stdClass();
            $response->message      = $e->getMessage();
            $response->status  = 'error';
            $response->result  = array();
            die(json_encode($response));
        }
    }

    public function getProjectDetail(Request $request){

        try{
            $data = $request->all();
            $projectId = $data['project_id'];
            $projectModel = new Project();
            $projectDetail = $projectModel->getProjectDetail($projectId);
            $response = new \stdClass();
            $response->result  = $projectDetail;
            $response->status  = 'success';
            $response->message = 'Project list fetched';
            die(json_encode($response));
        }catch(\Exception $e){
            $response = new \stdClass();
            $response->message      = $e->getMessage();
            $response->status  = 'error';
            $response->result  = array();
            die(json_encode($response));
        }
    }

    public function createNewCase(Request $request){

        try{

            $data = $request->all();
           // dd($data);
            $userId = JWTAuth::toUser($data['token'])->id;

            $profile_image = $this->uploadFile($data['imageData'],'photo',true);

            $projectModel = new Project();
            $projectModel->project_category_id = (isset($data['selectedProjectType']) && $data['selectedProjectType']) ? $data['selectedProjectType']: 0;
            $projectModel->purpose_id = (isset($data['purpose']) && $data['purpose']) ? $data['purpose']: 0;
            $projectModel->branch_type_id = (isset($data['branchType']) && $data['branchType']) ? $data['branchType']: 0;
            $projectModel->branch_code = (isset($data['siteProfile']['branchCode']) && $data['siteProfile']['branchCode']) ? $data['siteProfile']['branchCode']: 0;
            $projectModel->cost_center = (isset($data['siteProfile']['costCenter']) && $data['siteProfile']['costCenter']) ? $data['siteProfile']['costCenter']: 0;
            $projectModel->action = (isset($data['siteProfile']['action']) && $data['siteProfile']['action']) ? $data['siteProfile']['action']: 0;
            $projectModel->budget_cap = (isset($data['siteProfile']['budgetCap']) && $data['siteProfile']['budgetCap']) ? $data['siteProfile']['budgetCap']: 0;
            $projectModel->reason = (isset($data['siteProfile']['reason']) && $data['siteProfile']['reason']) ? $data['siteProfile']['reason']: 0;
            $projectModel->map_url = (isset($profile_image) && $profile_image) ? $profile_image : '';
            $projectModel->created_by = ($userId) ? $userId: 0;
            $projectModel->save();

            if($projectModel->id){
                $staffModel = new StaffCount();
                $staffModel->project_id = $projectModel->id;
                $staffModel->total_branch_manager = (isset($data['staffInfo']['branchManager']) && $data['staffInfo']['branchManager']) ? $data['staffInfo']['branchManager']: 0;
                $staffModel->total_operation_manager = (isset($data['staffInfo']['operationManager']) && $data['staffInfo']['operationManager']) ? $data['staffInfo']['operationManager']: 0;
                $staffModel->total_lounge_manager = (isset($data['staffInfo']['loungeManager']) && $data['staffInfo']['loungeManager']) ? $data['staffInfo']['loungeManager']: 0;
                $staffModel->total_showroom_supervisor = (isset($data['staffInfo']['showroomSupervisor']) && $data['staffInfo']['showroomSupervisor']) ? $data['staffInfo']['showroomSupervisor']: 0;
                $staffModel->total_head_teller = (isset($data['staffInfo']['headTeller']) && $data['staffInfo']['headTeller']) ? $data['staffInfo']['headTeller']: 0;
                $staffModel->total_teller = (isset($data['staffInfo']['teller']) && $data['staffInfo']['teller']) ? $data['staffInfo']['teller']: 0;
                $staffModel->total_vip_teller = (isset($data['staffInfo']['vipTeller']) && $data['staffInfo']['vipTeller']) ? $data['staffInfo']['vipTeller']: 0;
                $staffModel->total_relation_manager = (isset($data['staffInfo']['relationshipManager']) && $data['staffInfo']['relationshipManager']) ? $data['staffInfo']['relationshipManager']: 0;
                $staffModel->total_customers_assistance = (isset($data['staffInfo']['customerAssistance']) && $data['staffInfo']['customerAssistance']) ? $data['staffInfo']['customerAssistance']: 0;
                $staffModel->total_sales_advisor = (isset($data['staffInfo']['salesAdvisor']) && $data['staffInfo']['salesAdvisor']) ? $data['staffInfo']['salesAdvisor']: 0;
                $staffModel->total_sales_manager = (isset($data['staffInfo']['salesManager']) && $data['staffInfo']['salesManager']) ? $data['staffInfo']['salesManager']: 0;
                $staffModel->total_sales_representative = (isset($data['staffInfo']['salesServiceRep']) && $data['staffInfo']['salesServiceRep']) ? $data['staffInfo']['salesServiceRep']: 0;
                $staffModel->total_teller_representative = (isset($data['staffInfo']['serviceTellerRep']) && $data['staffInfo']['serviceTellerRep']) ? $data['staffInfo']['serviceTellerRep']: 0;
                $staffModel->total_guard = (isset($data['staffInfo']['guard']) && $data['staffInfo']['guard']) ? $data['staffInfo']['guard']: 0;
                $staffModel->total_staff = (isset($data['staffInfo']['totalStaff']) && $data['staffInfo']['totalStaff']) ? $data['staffInfo']['totalStaff']: 0;
                $staffModel->save();
            }

            $response = new \stdClass();
            $response->status  = 'success';
            $response->message = 'Case created successfully';

            $user['name'] = 'Abhishek';
            $user['email'] = 'abhishekg@aeologic.com';

            \Mail::send('email.notification', ['user' => $user], function ($m) use ($user) {
                $m->from('report.ard@4c360.com', 'ARD Team');
                $m->to($user['email'], $user['name'])->subject('New purchase request');
            });

            $user['name'] = 'Yashdeep';
            $user['email'] = 'yashdeep@aeologic.com';
            \Mail::send('email.notification-admin', ['user' => $user], function ($m) use ($user) {
                $m->from('ard.team@gmail.com', 'ARD Team');
                $m->to($user['email'], $user['name'])->subject('New purchase request');
            });

            die(json_encode($response));
        }catch(\Exception $e){
            $response = new \stdClass();
            $response->message      = $e->getMessage();
            $response->status  = 'error';
            die(json_encode($response));
        }
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
    public function updateProject(Request $request){

        try{

            $data = $request->all();
            $userId = JWTAuth::toUser($data['token'])->id;
            $dataSet['branch_type_id'] = (isset($data['branchType']) && $data['branchType']) ? $data['branchType']: 0;
            $dataSet['branch_code'] = (isset($data['siteProfile']['branchCode']) && $data['siteProfile']['branchCode']) ? $data['siteProfile']['branchCode']: 0;
            $dataSet['cost_center'] = (isset($data['siteProfile']['costCenter']) && $data['siteProfile']['costCenter']) ? $data['siteProfile']['costCenter']: 0;
            $dataSet['action'] = (isset($data['siteProfile']['action']) && $data['siteProfile']['action']) ? $data['siteProfile']['action']: 0;
            $dataSet['budget_cap'] = (isset($data['siteProfile']['budgetCap']) && $data['siteProfile']['budgetCap']) ? $data['siteProfile']['budgetCap']: 0;
            $dataSet['reason'] = (isset($data['siteProfile']['reason']) && $data['siteProfile']['reason']) ? $data['siteProfile']['reason']: 0;
            $dataSet['updated_by'] = ($userId) ? $userId: 0;
            $projectId = (isset($data['project_id'])) ? $data['project_id']: 0;
            $resp = DB::table('projects')
                ->where('id', $projectId)
                ->update($dataSet);
            if($resp){
                $staffData['total_branch_manager'] = (isset($data['staffInfo']['branchManager']) && $data['staffInfo']['branchManager']) ? $data['staffInfo']['branchManager']: 0;
                $staffData['total_operation_manager'] = (isset($data['staffInfo']['operationManager']) && $data['staffInfo']['operationManager']) ? $data['staffInfo']['operationManager']: 0;
                $staffData['total_lounge_manager'] = (isset($data['staffInfo']['loungeManager']) && $data['staffInfo']['loungeManager']) ? $data['staffInfo']['loungeManager']: 0;
                $staffData['total_showroom_supervisor'] = (isset($data['staffInfo']['showroomSupervisor']) && $data['staffInfo']['showroomSupervisor']) ? $data['staffInfo']['showroomSupervisor']: 0;
                $staffData['total_head_teller'] = (isset($data['staffInfo']['headTeller']) && $data['staffInfo']['headTeller']) ? $data['staffInfo']['headTeller']: 0;
                $staffData['total_teller'] = (isset($data['staffInfo']['teller']) && $data['staffInfo']['teller']) ? $data['staffInfo']['teller']: 0;
                $staffData['total_vip_teller'] = (isset($data['staffInfo']['vipTeller']) && $data['staffInfo']['vipTeller']) ? $data['staffInfo']['vipTeller']: 0;
                $staffData['total_relation_manager'] = (isset($data['staffInfo']['relationshipManager']) && $data['staffInfo']['relationshipManager']) ? $data['staffInfo']['relationshipManager']: 0;
                $staffData['total_customers_assistance'] = (isset($data['staffInfo']['customerAssistance']) && $data['staffInfo']['customerAssistance']) ? $data['staffInfo']['customerAssistance']: 0;
                $staffData['total_sales_advisor'] = (isset($data['staffInfo']['salesAdvisor']) && $data['staffInfo']['salesAdvisor']) ? $data['staffInfo']['salesAdvisor']: 0;
                $staffData['total_sales_manager'] = (isset($data['staffInfo']['salesManager']) && $data['staffInfo']['salesManager']) ? $data['staffInfo']['salesManager']: 0;
                $staffData['total_sales_representative'] = (isset($data['staffInfo']['salesServiceRep']) && $data['staffInfo']['salesServiceRep']) ? $data['staffInfo']['salesServiceRep']: 0;
                $staffData['total_teller_representative'] = (isset($data['staffInfo']['serviceTellerRep']) && $data['staffInfo']['serviceTellerRep']) ? $data['staffInfo']['serviceTellerRep']: 0;
                $staffData['total_guard'] = (isset($data['staffInfo']['guard']) && $data['staffInfo']['guard']) ? $data['staffInfo']['guard']: 0;
                $staffData['total_staff'] = (isset($data['staffInfo']['totalStaff']) && $data['staffInfo']['totalStaff']) ? $data['staffInfo']['totalStaff']: 0;
            }
            $respStaff = DB::table('staff_counts')
                ->where('project_id', $projectId)
                ->update($staffData);
            if($respStaff){
                $response = new \stdClass();
                $response->status  = 'success';
                $response->message = 'Project Updated Successfully';
            }else{
                $response = new \stdClass();
                $response->status  = 'erro';
                $response->message = 'Something Went Wrong';
            }


            die(json_encode($response));
        }catch(\Exception $e){
            $response = new \stdClass();
            $response->message      = $e->getMessage();
            $response->status  = 'error';
            die(json_encode($response));
        }
    }

}
