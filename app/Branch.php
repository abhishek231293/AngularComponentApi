<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branches';
    protected $fillable = [
        'code','name','type','open_at','close_at','branch_class','manager','services','telephone','fax','email','ownership',
        'address','region','city','region_id','latitude','longitude','updated_by','updated_at'
    ];

    public function getStoriesBranch($dataSet) {
        try {

            $data = $this->query();
            $data->select('branches.*');

            if (isset($dataSet['zone']) && $dataSet['zone'] != 'kingdom') {
                $data->where("branches.zone_name",'=',$dataSet['zone']);
            }

            if(isset($dataSet['formData'])){
                if(isset($dataSet['formData']['region']) && $dataSet['formData']['region'] ){
                    $data->whereIn('region_id',$dataSet['formData']['region']);
                }
                if(isset($dataSet['formData']['city']) && $dataSet['formData']['city'] ){
                    $data->whereIn('city',$dataSet['formData']['city']);
                }
            }

            if((isset($dataSet['range']) && $dataSet['range']) && (isset($dataSet['lat']) && $dataSet['lat']) && (isset($dataSet['lng']) && $dataSet['lng'])){
                $data->whereRaw('6371 * 2 * ASIN(
                                   SQRT(
                                     POWER(
                                       SIN(
                                         (latitude - ABS('.$dataSet["lat"].')) * PI() / 180 / 2
                                       ),
                                       2
                                     ) + COS(latitude * PI() / 180) * COS(ABS('.$dataSet["lat"].') * PI() / 180) * POWER(
                                       SIN(
                                         (longitude - '.$dataSet["lng"].') * PI() / 180 / 2
                                       ),
                                       2
                                     )
                                   )
                                 ) <= '.$dataSet["range"]);
            }
            $data = $data->where('latitude', '<>', 0)
                ->where('longitude', '<>', 0)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            return $data->get()->toArray();
        }catch(Exception $e){
            return false;
        }
    }

    function consolidatedSearch($data){
        $query = $this->query();
        $query->select('branches.*');
        $query->where('latitude', '<>', 0);
        $query->where('longitude', '<>', 0)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        $query->where('branches.code','=',$data['search-input']);

//        $query->where(function ($query) use ($data) {
//            $query->where('code','=',$data['search-input'])
//                ->orwhere('name','=',$data['search-input']);
//        });

        return $query->get()->toArray();

    }
}
