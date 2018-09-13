<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtmDetail extends Model
{
    protected $table = 'atm_details';
    protected $fillable = [
        'terminal_id','atm_code','site','site_type','region','province','city','cash_source','cash_center_name',
        'atm_model','latitude','longitude','site_location','location_category','vendor','atm_quantity'
    ];

    public function getAtmDetailList($dataSet){

        try {
//            $query = $this->leftJoin('atm_images', 'atm_images.atm_code', '=', 'atm_details.atm_code');
            $query = $this->where('latitude', '<>', 0);
            $query->where('longitude', '<>', 0)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            if(isset($dataSet['zone']) && $dataSet['zone'] && $dataSet['zone'] != 'kingdom') {
                $query->where("atm_details.zone_name",'=',$dataSet['zone']);
            }

            if((isset($dataSet['range']) && $dataSet['range']) && (isset($dataSet['lat']) && $dataSet['lat']) && (isset($dataSet['lng']) && $dataSet['lng'])){
                $query->whereRaw('6371 * 2 * ASIN(
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

            return $query->get()->toArray();
        }catch(Exception $e){
            return false;
        }

    }

    function consolidatedSearch($data){
        $query = $this->query();
        $query->select('atm_details.*');
        $query->where('latitude', '<>', 0);
        $query->where('longitude', '<>', 0)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        $query->where('atm_details.atm_code','=',$data['search-input']);
//        $query->where(function ($query) use ($data) {
//            $query->where('atm_code','=',$data['search-input'])
//                ->orwhere('site','=',$data['search-input']);
//        });

        return $query->limit(1)->get()->toArray();

    }
}
