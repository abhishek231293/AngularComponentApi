<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Product extends Model
{
        protected $fillable = [
            'id','product_name','is_active',
        ];

        public function down()
        {
                Schema::dropIfExists('products');
        }

        public function up()
        {
                Schema::create('products', function (Blueprint $table) {
                        $table->increments('id');
                        $table->integer('product_id');
                        $table->string('product_name');
                        $table->integer('is_active')->default(1);
                        $table->timestamps();
                });
        }
}
