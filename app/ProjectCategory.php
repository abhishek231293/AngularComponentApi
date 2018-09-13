<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    protected $fillable = [
        'category', 'description', 'icon','is_active','created_at','updated_at'
    ];

    /**
     * @return array
     */
    public function getCategoryList()
    {
        return $this->get()->toArray();
    }
}
