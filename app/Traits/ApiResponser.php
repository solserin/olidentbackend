<?php
namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
    private function successResponse($data,$code){
        return response()->json($data,$code);
    }

    protected function errorResponse($message,$code){
        return response()->json(['error'=>$message,'code'=>$code],$code);
    }
    //show all sin paginado
    protected function showAll(Collection $collection,$code=200){
        return $this->successResponse(['data'=>$collection],$code);
    }

    //show all paginados
    protected function showAllPaginated(Collection $collection,$code=200){
        $collection=$this->paginate($collection);
        return $this->successResponse(['data'=>$collection],$code);
    }

    //metodo de paginacion de datos
    protected function paginate(Collection $collection)
    {
        $rules=[
            'per_page'=>'integer|min:2|max:300'
        ];
        Validator::validate(request()->all(),$rules);
        //aqui sabemos cual segmento vamos a mostrar
        $page=LengthAwarePaginator::resolveCurrentPage();
        $perPage=15;
        if(request()->has('per_page')){
            $perPage=(int) request()->per_page;
        }
        $results=$collection->slice(($page-1)*$perPage,$perPage)->values();
        $paginated=new LengthAwarePaginator($results,$collection->count(),$perPage,$page,[
            'path'=>LengthAwarePaginator::resolveCurrentPath(),
        ]);
        $paginated->appends(request()->all());
        return $paginated;
    }

    protected function showOne(Model $instance,$code=200){
        return $this->successResponse(['data'=>$instance],$code);
    }
}
