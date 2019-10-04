<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    protected $table = 'ventas';

    public function poliza(){
        return $this->hasOne('App\Polizas','id','polizas_id');
    }

    public function vendedor(){
        return $this->hasOne('App\User','id','vendedor_id');
    }

    public function tipo_venta(){
        return $this->hasOne('App\TiposVenta','id','tipos_venta_id');
    }

    public function beneficiarios(){
        return $this->hasMany('App\Beneficiarios','polizas_id','polizas_id');
    }

    public function tipo_poliza(){
        return $this->hasOne('App\TiposPolizas','id','tipo_polizas_id');
    }

    public function abonos(){
        return $this->hasMany('App\Abonos','ventas_id','id');
    }

    public function ventas(){
        return $this->hasMany('App\Ventas','polizas_id','num_poliza');
    }




}
