<?php

namespace App\Http\Controllers;

use App\Ventas;
use App\Polizas;
use App\Localidades;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\ApiController;

class PolizasController extends ApiController
{
  public function __construct()
    {
        //$this->middleware('auth:api');
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return $this->showAllPaginated(Polizas::
        with(
          array('ventas'=>function($query){
              $query->select('id','polizas_id','vendedor_id',DB::raw('"'.DB::table('users')->select('name')->where('id','=','1')->first()->name.'" as vendedor'))->orderBy('id','desc');

          })
        )
        ->with('ruta')
        ->withCount(
          array('ventas as estado_servicio'=>function($query){
            $query->where('fecha_vencimiento','>',Carbon::now()->format('Y-m-d H:i:s'));
          })
        )
        ->withCount('ventas as total_ventas')
        ->with(
          array('beneficiarios'=>function($query){
            $query->select('id','nombre','polizas_id')->orderBy('id','asc');
          })
        )
        ->orderBy('num_poliza','asc')
        ->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //validacion de datos para el nuevo usuario
       request()->validate(
        [
          'usuario_registro_id' => 'required|integer',
          'num_poliza' => 'required|integer|unique:polizas,num_poliza',
          'fecha_afiliacion' => 'required|date_format:Y-m-d',
          'tipo_poliza_id'=>'required',
          'ruta_id'=>'required',
          'vendedor_id'=>'required',
          'abono'=>'required|numeric|max:'.$request->tipo_poliza_id['precio'],
          'titular'=>'required',
          'colonia'=>'required',
          'calle'=>'required',
          'edad'=>'required',
          'numero'=>'required',
          'telefono'=>'required',
          'localidad_id'=>'required',
          'beneficiarios.*.nombre'=>Rule::requiredIf($request->tipo_poliza_id['id']>1),
          'beneficiarios.*.edad'=>Rule::requiredIf($request->tipo_poliza_id['id']>1),
        ],
        [
          'max'=>'La cantidad de abono no debe superar el precio de la póliza ('.$request->tipo_poliza_id['precio'].' Pesos.)',
          'required' => 'Este dato es obligatorio.',
          'date_format'=>'Seleccione una fecha válida.',
          'integer' => 'Este dato debe ser un numero entero.',
          'numeric' => 'Este dato debe ser un numero.',
          'email' => 'Debe ingresar un email',
          'unique' => 'Esta póliza ya existe, tal vez deba ir al formulario de renovación.',
        ]
      );
    
      //aqui guardo la poliza
      $obj = new Polizas();
      // el resultad regresa el numero de venta
      $resultado=$obj->guardar_poliza($request);
      return $resultado;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }





    
    public function nota_venta(){
       $id_venta = Input::get('venta_id');
       $num_poliza = Input::get('poliza_id');
       if(!Input::get('venta_id') || !Input::get('poliza_id')){
         return $this->errorResponse('Error, esta URL no existe',404);
       }
      //eliminos los archivos anteriores
        $files=Storage::disk('images_base64')->files();
        foreach($files as $fi)
        {
          Storage::disk('images_base64')->delete($fi);
        }

        $empresa=DB::table('empresas')->where('id',1)->get()->toArray();
        //obtengo los datos de la venta
        $venta=Ventas::with('poliza')
        ->with('vendedor:id,name')
        ->with('tipo_venta')
        ->with('tipo_poliza')
        ->with('beneficiarios:id,nombre,polizas_id,tipo_beneficiarios_id,calle,colonia,numero,cp,localidad_id,ocupacion,edad,telefono')
        ->where('polizas_id',$num_poliza)
        ->where('id',$id_venta)
        ->get();
        if(!count($venta)){
          return $this->errorResponse('Error, esta URL no existe',404);
        }
        //return $venta;
        //si hay resultados de la venta cargo la localidad de la venta
        if(isset($venta[0]['beneficiarios'][0]['localidad_id'])){
          $localidad=Localidades::with('municipio')->where('id',$venta[0]['beneficiarios'][0]['localidad_id'])
          ->get()
          ->toArray();
        }else{
          $localidad='';
        }
        
        //return $venta;
       //return $venta[0]['poliza']['id'];
        // Obtener los datos de la imagen
        $img =getB64Image($empresa[0]->logo);
        // Obtener la extensión de la Imagen
        $img_extension =getB64Extension($empresa[0]->logo);
        // Crear un nombre aleatorio para la imagen
        $img_name = 'logo'. time() . '.' . $img_extension;   
        // Usando el Storage guardar en el disco creado anteriormente y pasandole a 
        // la función "put" el nombre de la imagen y los datos de la imagen como 
        // segundo parametro
        Storage::disk('images_base64')->put($img_name, $img);
        $file = storage_path('app/images_base64/'.$img_name);
      
        $pdf = PDF::loadView('polizas/nota_venta',compact('empresa','file','venta','localidad'))->setPaper('a4');
        return $pdf->stream('archivo.pdf');
      }





      public function tarjeta_cobranza(){
        $id_venta = Input::get('venta_id');
        if(!Input::get('venta_id')){
          return $this->errorResponse('Error, esta URL no existe',404);
        }
       //eliminos los archivos anteriores
         $files=Storage::disk('images_base64')->files();
         foreach($files as $fi)
         {
           Storage::disk('images_base64')->delete($fi);
         }
 
         $empresa=DB::table('empresas')->where('id',1)->get()->toArray();


         //obtengo los datos de la venta
         $venta=Ventas::with('poliza')
         ->with('vendedor:id,name')
         ->with('tipo_venta')
         ->with('abonos')
         ->with('tipo_poliza')
         ->with('beneficiarios:id,nombre,polizas_id,tipo_beneficiarios_id,calle,colonia,numero,cp,localidad_id,ocupacion,edad,telefono')
         ->where('id',$id_venta)
         ->get();
         return $venta;
         if(!count($venta)){
           return $this->errorResponse('Error, esta URL no existe',404);
         }
         //return $venta;
         //si hay resultados de la venta cargo la localidad de la venta
         if(isset($venta[0]['beneficiarios'][0]['localidad_id'])){
           $localidad=Localidades::with('municipio')->where('id',$venta[0]['beneficiarios'][0]['localidad_id'])
           ->get()
           ->toArray();
         }else{
           $localidad='';
         }
         
         //return $venta;
        //return $venta[0]['poliza']['id'];
         // Obtener los datos de la imagen
         $img =getB64Image($empresa[0]->logo);
         // Obtener la extensión de la Imagen
         $img_extension =getB64Extension($empresa[0]->logo);
         // Crear un nombre aleatorio para la imagen
         $img_name = 'logo'. time() . '.' . $img_extension;   
         // Usando el Storage guardar en el disco creado anteriormente y pasandole a 
         // la función "put" el nombre de la imagen y los datos de la imagen como 
         // segundo parametro
         Storage::disk('images_base64')->put($img_name, $img);
         $file = storage_path('app/images_base64/'.$img_name);
       
         $pdf = PDF::loadView('polizas/nota_venta',compact('empresa','file','venta','localidad'))->setPaper('a4');
         return $pdf->stream('archivo.pdf');
       }
}
