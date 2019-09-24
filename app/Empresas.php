<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    protected $table = 'empresas';


    //actualizo los datos de la empresa
    public function update_empresa(Request $request,$id){
        try {
            DB::table('empresas')->where('id',$request->id_empresa)->update(
                [
                    'logo' => $request->imagen,
                    'nombre' => $request->nombre,
                    'representante' => $request->representante,
                    'email' => $request->email,
                    'telefono' => $request->telefono,
                    'calle' => $request->calle,
                    'colonia' => $request->colonia,
                    'numero' => $request->numero,
                    'cp' => $request->cp,
                    'ciudad' => $request->ciudad,
                    'descripcion' => $request->descripcion,
                ]
            );
            return $id;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
