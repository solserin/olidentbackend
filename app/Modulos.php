<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modulos extends Model
{
    protected $table = 'modulos';

     //un modulo puede tener solo un  grupo
     public function grupo(){
        return $this->belongsTo('App\Grupos','grupos_id','id');
    }
}
