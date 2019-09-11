<?php

namespace App\Exceptions;

use Exception;
use BadMethodCallException;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{

    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //con esto validamos cuando no se cumplen con las validaciones en las peticiones post
        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception,$request);
        }
        //con esto validamos cuando no retorna datos una peticion a la bd
        if($exception instanceof ModelNotFoundException){
            $modelo=strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe ninguna instancia de {$modelo} con el id especificado",404);
        }

         //con esto validamos cuando un usuario no esta autenticado
         if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request,$exception);
        }

        //con esto validamos cuando un hace una peticion no permitida
        if($exception instanceof AuthorizationException){
            return $this->errorResponse('No posee permisos para ejecutar esta accion',403);
        }

         //con esto validamos rutas que no existe
         if($exception instanceof NotFoundHttpException){
            return $this->errorResponse('No existe la ruta especificada',404);
        }

         //con esto validamos que las rutas usen el metodo correcto
         if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('No esta permitido acceder con este metodo',405);
        }

        //con esto validamos las excepciones de http mas comunes
        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
        }

         //con esto validamos si el metodo llamado existe
         if($exception instanceof BadMethodCallException){
            return $this->errorResponse('El metodo solicitado no existe',500);
        }

        

         //,manejando query exceptions
         if($exception instanceof QueryException){
             $codigo_error=$exception->errorInfo[1];
             if($codigo_error==1451){
                 return $this->errorResponse('No se puede eliminar de forma permanente el recurso porque esta relacionado con algun otro.',409);
             }
            
        }

        //manejando fallas inesperadas como por ejemplo problemas con el server
        //si la aplicacion esta en modo de depuracion mostramos el mensaje original con los datos del error y si
        //no mandamos el mensaje de error con json
        if(config('app.debug')){
            //mensaje original de laravel   
            return parent::render($request, $exception);
        }


        return $this->errorResponse('Falla inesperada del servidor',500);
        

       
    }




    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('No autenticado',401);
    }

   //aqui estamos redefiniendo esta funcion de la clase padre render
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors=$e->validator->errors()->getMessages();
        return $this->errorResponse($errors,422);
    }
}
