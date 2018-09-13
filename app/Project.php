<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
        protected $fillable = [
            'id','project_category_id','branch_code','purpose_id','cost_center','action','reason','branch_type_id',
            'is_active','created_by',  'updated_by',  'created_at',  'updated_at'
        ];

        /**
         * @return array
         */
        public function getProjectList()
        {
                $data = DB::table('projects')
                    ->join('users', 'users.id', '=', 'projects.created_by')
                    ->join('purposes', 'purposes.id', '=', 'projects.purpose_id')
                    ->select('projects.id','users.first_name', 'purposes.purpose', 'projects.created_at')
                    ->get();
                return $data;
        }
        public function getProjectDetail($projectId)
        {

                $data = DB::table('projects')
                    ->join('users', 'users.id', '=', 'projects.created_by')
                    ->join('purposes', 'purposes.id', '=', 'projects.purpose_id')
                    ->join('branch_type', 'branch_type.id', '=', 'projects.branch_type_id')
                    ->join('staff_counts', 'staff_counts.project_id', '=', 'projects.id')
                    ->select('staff_counts.*','branch_type.id as branchTypeId','branch_type.type as caption','branch_type.image','purposes.id','projects.id','users.first_name'
                        ,'purposes.purpose', 'projects.action','projects.cost_center','projects.budget_cap'
                        ,'projects.reason','projects.branch_code','projects.map_url','projects.created_at')
                    ->where('projects.id',$projectId)
                    ->get();
                return $data;
        }
}
