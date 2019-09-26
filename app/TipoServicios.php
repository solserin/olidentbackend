<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoServicios extends Model
{
    protected $table = 'tipo_servicios';


    
    //relacion de los servicios que tiene esta categoria de tipos de servicios
    public function servicios()
    {
        return $this->hasMany('App\Servicios','tipo_id','id');
    }


}
