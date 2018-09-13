<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Component extends Model
{
        protected $fillable = [
            'id','product_id','component_name','is_active',
        ];

        public function down()
        {
                Schema::dropIfExists('components');
        }

        public function up()
        {
                Schema::create('components', function (Blueprint $table) {
                        $table->increments('id');
                        $table->integer('component_id');
                        $table->integer('product_id');
                        $table->string('component_name');
                        $table->integer('is_active')->default(1);
                        $table->timestamps();
                });
        }
}
