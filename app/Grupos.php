<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupos extends Model
{
    protected $table = 'grupos';

      //un grupo tiene muchos modulos
    public function modulos()
    {
        return $this->hasMany('App\Modulos','grupos_id','id');
    }
}
