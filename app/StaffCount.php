<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffCount extends Model
{
    
    protected $fillable = [
        'id' ,'project_id', 'total_branch_manager',  'total_operation_manager',  'total_lounge_manager',
        'total_showroom_supervisor',  'total_head_teller',  'total_teller',  'total_vip_teller',
        'total_relation_manager',  'total_customers_assistance',  'total_sales_advisor',
        'total_sales_manager',  'total_sales_representative',  'total_teller_representative',
        'total_guard',  'total_staff',  'updated_by',  'updated_at'
    ];

}
