<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abonos extends Model
{
    protected $table = 'abonos';

    public function venta()
    {
        return $this->belongsTo('App\Ventas','ventas_id','id');
    }

}
