<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Localidades extends Model
{
    protected $table = 'localidades';


    //una localidad pertenece a un municipio
    public function municipio(){
        return $this->belongsTo('App\Municipios','municipio_id','id');
    }
}
