<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Localidades extends Model
{
    protected $table = 'localidades';

    //un modulo puede tener solo un  grupo
    public function municipio(){
        return $this->belongsTo('App\Municipios','municipio_id','id');
    }
}
