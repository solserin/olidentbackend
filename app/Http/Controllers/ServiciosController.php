<?php

namespace App\Http\Controllers;

use App\Empresas;
use App\Servicios;
use App\TipoServicios;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Storage;

class ServiciosController extends ApiController
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
        $key = Input::get('filter');
        //return $this->showAllPaginated(Servicios::with('tipo')->select(['imagen','created_at','id','name','email','roles_id','status'])->where('name', 'like', '%'.$key.'%')->orderBy('id','desc')->get());
        return $this->showAllPaginated(Servicios::with('tipo')->where('servicio', 'like', '%'.$key.'%')->where('status','1')->orderBy('id','desc')->get());
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
          'servicio' => 'required|unique:servicios,servicio',
          'precio_normal' => 'required|numeric',
          'descuento_poliza' => 'required',
          'tipo_precio_id' => 'required',
          'tipo_id' => 'required',
        ],
        [
          'required' => 'Este dato es obligatorio.',
          'numeric' => 'Este dato debe ser un número (ejemplo: 1500)',
          'unique' => 'Ya existe un servicio con este nombre, ingrese uno diferente.',
        ]
      );
      //aqui guardo el rol nuevo
      $obj = new Servicios();
      $resultado=$obj->guardar_servicio($request);
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
        return $this->showOne(Servicios::select('id','servicio','descripcion','precio_normal','descuento_poliza','tipo_precio_id','tipo_id')->where('id', $id)->first());
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
         //validacion de datos para el nuevo usuario
       request()->validate(
            [
            'servicio' => ['required',Rule::unique('servicios','servicio')->ignore($id)],
            'precio_normal' => 'required|numeric',
            'descuento_poliza' => 'required',
            'tipo_precio_id' => 'required',
            'tipo_id' => 'required',
            ],
            [
                'required' => 'Este dato es obligatorio.',
                'numeric' => 'Este dato debe ser un número (ejemplo: 1500)',
                'unique' => 'Ya existe un servicio con este nombre, ingrese uno diferente.',
            ]
        );
        //aqui actualizo el usuario
        $obj = new Servicios();
        $resultado=$obj->update_servicio($request,$id);
        return $resultado;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //aqui deberia de validar si tiene algun tipo de operaciones asociadas para cuando exista el modulo de clinicas
        //se puede eliminar
        $obj = new Servicios();
        $resultado=$obj->delete_servicio($id);
        return $resultado;
    }


    public function getB64Image($base64_image){  
        // Obtener el String base-64 de los datos         
        $image_service_str = substr($base64_image, strpos($base64_image, ",")+1);
        // Decodificar ese string y devolver los datos de la imagen        
        $image = base64_decode($image_service_str);   
        // Retornamos el string decodificado
        return $image; 
     }

     public function getB64Extension($base64_image, $full=null){  
        // Obtener mediante una expresión regular la extensión imagen y guardarla
        // en la variable "img_extension"        
        preg_match("/^data:image\/(.*);base64/i",$base64_image, $img_extension);   
        // Dependiendo si se pide la extensión completa o no retornar el arreglo con
        // los datos de la extensión en la posición 0 - 1
        return ($full) ?  $img_extension[0] : $img_extension[1];  
      }


    public function get_reporte_servicios(){
      //eliminos los archivos anteriores
        $files=Storage::disk('images_base64')->files();
        foreach($files as $fi)
        {
          Storage::disk('images_base64')->delete($fi);
        }

        $servicios=Servicios::with('tipo')->where('status',1)->orderBy('id','asc')->get();
        $empresa=DB::table('empresas')->where('id',1)->get()->toArray();

              // Obtener los datos de la imagen
        $img = $this->getB64Image($empresa[0]->logo);
        // Obtener la extensión de la Imagen
        $img_extension = $this->getB64Extension($empresa[0]->logo);
        // Crear un nombre aleatorio para la imagen
        $img_name = 'logo'. time() . '.' . $img_extension;   
        // Usando el Storage guardar en el disco creado anteriormente y pasandole a 
        // la función "put" el nombre de la imagen y los datos de la imagen como 
        // segundo parametro
        Storage::disk('images_base64')->put($img_name, $img);
        $file = storage_path('app/images_base64/'.$img_name);


        $pdf = PDF::loadView('servicios/servicios',compact('servicios','empresa','file'))->setPaper('a4', 'landscape');
        
        return $pdf->stream('archivo.pdf');
      }
   
}
