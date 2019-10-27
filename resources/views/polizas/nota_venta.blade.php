<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nota de Venta</title>

<style type="text/css">
body{
    font-family:Helvetica, sans-serif;
    color: #002f5b;
}
#empresa{
    text-align: center;
}

#empresa h4{
    text-transform: uppercase;
    line-height:1px;
font-size: 18px;
font-weight: lighter;
}
#empresa h5{
    font-size: 14px;
    margin-top: -5px;
    line-height: 0;
}

#folio{
    text-align: right;
}

h2{
    letter-spacing: 5px;
    margin-top: 30px;
    font-size: 20px;
    text-align: center;
    font-weight: bold;
    line-height: 0;
}

h6{
    line-height: 1.5;
    font-size: 15px;
    font-weight: bold;
    text-align: center;
}

.datos{
    font-size: 15px;
    font-weight: bold;
}

.dato-des{
    font-weight: normal;
}

#datosTitular, #datosTitular th, #datosTitular td {
  border-bottom: 1px solid black;
  border-collapse: collapse;
}

 #datosTitular th, #datosTitular td {
    padding: 10px 0px 10px 0px;
}

/** Define the footer rules **/
footer {
    position: fixed; 
    bottom: 0cm; 
    left: 0cm; 
    right: 0cm;
    padding: 5px 0px 5px 0px;
    /** Extra personal styles **/
    background-color: #002f5b;
    color: white;
    text-align: center;
    font-size: 14px;
}

#watermark {
    top:30%;
    left: 25%;
    position: fixed;
    transform: rotate(-45deg);
    color: #dc3545;
    border: 3px dashed #dc3545;
    font-size: 38px;
    width: 330px;
    height: 50px;
    text-align: center;
    z-index: -1000;
}

.pagado{
    color:#28a745
}
</style>
</head>
<body>
      @if ($venta[0]['status']==1)
        @if($estado_poliza_venta==0)
            <div id="watermark">
                Póliza Vencida
            </div>
        @endif
        @else
            <div id="watermark">
                Póliza Cancelada
            </div>
        @endif
   
    <footer>
    Actualizado para el día {{fechahora_completa()}}.
    </footer>
    @if($venta)
    <table width="100%" >
            <tr>
                <td valign="top" width="20%" style="float:left;"><img src='{{$file}}' alt="" width="150"/></td>   
                <td width="60%" id="empresa">
                    <h4>{{$empresa[0]->nombre}}</h4>
                    <h5>Seguridad a tu sonrisa</h5>
                </td> 
                <td width="20%" id="folio" style="font-size:12px !important;">
                    <span class="datos">Num. Póliza: 
                        <span class="dato-des">
                        {{$venta[0]['polizas_id']}}
                    </span></span><br>
                    <span class="datos">Venta: 
                            <span class="dato-des">
                            {{$venta[0]['id']}}
                    </span></span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h2>35% PÓLIZA DENTAL DE DESCUENTO 50%</h2>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h6>{{$empresa[0]->representante}} <br>
                           Calle: {{$empresa[0]->calle}} # {{$empresa[0]->numero}} Col. {{$empresa[0]->colonia}} {{$empresa[0]->descripcion}} <br> C.P. {{$empresa[0]->cp}} {{$empresa[0]->ciudad}}. Tel. de Citas {{$empresa[0]->telefono}}
                    </h6>
                </td>
            </tr>
        </table>
        <table width="100%" id="datosTitular">
            <tr>
                <td colspan="4">
                        <span class="datos">Vendido por: <span class="dato-des">{{$venta[0]['vendedor']['name']}}</span></span>
                </td>      
            </tr> 
            <tr>
                <td align="center">
                    <span class="datos">Tipo Venta: <br> <span class="dato-des">{{$venta[0]['tipo_venta']['tipo']}}</span></span>
                </td>  
                <td align="center">
                    <span class="datos">Fecha de Afiliación: <br> <span class="dato-des">{{strtolower(fecha_no_day($venta[0]['poliza']['fecha_afiliacion']))}}</span></span>
                </td>  
                <td colspan="1" align="center">
                    <span class="datos">Venta de poliza: <br> <span class="dato-des">{{strtolower(fecha_no_day($venta[0]['fecha_venta']))}}</span></span>
                </td>   
                <td  align="center">
                        <span class="datos">Fecha de vencimiento: <br> <span class="dato-des">{{strtolower(fecha_no_day($venta[0]['fecha_vencimiento']))}}</span></span>
                </td>   
            </tr> 
            <tr>
                <td colspan="4">
                    <span class="datos">Titular: <span class="dato-des">{{strtoupper($venta[0]['beneficiarios'][0]['nombre'])}}</span></span>
                </td>      
            </tr>
            <tr>
                <td colspan="4">
                    <span class="datos">Dirección: <span class="dato-des">Calle {{ucfirst($venta[0]['beneficiarios'][0]['calle'])}} # {{ucfirst($venta[0]['beneficiarios'][0]['numero'])}} Col. {{ucfirst($venta[0]['beneficiarios'][0]['colonia'])}} {{ucfirst($localidad[0]['nombre'])}}, {{ucfirst($localidad[0]['municipio']['nombre'])}}, Sin. </span></span>
                </td>      
            </tr>
            <tr>
                <td colspan="2">
                    <span class="datos">Tel o Cel: <span class="dato-des">{{ucfirst($venta[0]['beneficiarios'][0]['telefono'])}}</span></span>
                </td> 
                <td>
                    <span class="datos">Ocupación: <span class="dato-des">{{ucfirst($venta[0]['beneficiarios'][0]['ocupacion'])}}</span></span>
                </td>
                <td align="center">
                    <span class="datos">Edad: <span class="dato-des">{{ucfirst($venta[0]['beneficiarios'][0]['edad'])}}</span></span>
                </td> 
            </tr> 
            <tr>
                    <td colspan="1">
                        <span class="datos">Póliza: <span class="dato-des">{{ucfirst($venta[0]['tipo_poliza']['tipo'])}}</span></span>
                    </td> 
                    <td colspan="1">
                        <span class="datos">Costo: $ <span class="dato-des">{{number_format($venta[0]['tipo_poliza']['precio'],2,".",",")}} Pesos.</span></span>
                    </td>  
                    <td colspan="1">
                            <span class="datos">Abonado: $ <span class="dato-des">{{number_format($venta[0]['abonado'],2,".",",")}} Pesos.</span></span>
                    </td>
                    <td colspan="1">
                        <span class="datos">
                            Restante: 
                            @if ($venta[0]['restante']>0)
                                $ <span class="dato-des">
                                {{number_format($venta[0]['restante'],2,".",",")}} Pesos.
                            </span>
                            @else
                                <span class="pagado">
                                    Pagada
                                </span>
                            @endif
                            
                        </span>
                    </td>        
            </tr>  
        </table>
        <br>
        @if ($venta[0]['tipo_poliza']['id']>1)
        <h2>BENEFICIARIOS</h2> 
        <br>
        <br>
        <table width="100%" id="datosTitular">
        @php
            $numero_beneficiario=1;
        @endphp
       
            @foreach ($venta[0]['beneficiarios'] as $beneficiario)
                @if ($beneficiario->tipo_beneficiarios_id==2)
                <tr>
                    <td align="center">
                    <span class="datos">{{$numero_beneficiario}}</span>
                    </td> 
                    <td align="center">
                        <span class="datos"> <span class="dato-des">{{($beneficiario->nombre)}}</span></span>
                    </td>   
                    <td  align="center">
                        <span class="datos"><span class="dato-des">{{($beneficiario->edad)}}</span></span>
                    </td>   
                </tr>
                @php
                    $numero_beneficiario++;
                @endphp    
                @endif
            @endforeach  
        </table>
        @else
        <h2>BENEFICIARIOS N/A</h2>
        @endif
        
    @else
    <table width="100%" style="text-align:center;">
            <tr>
                <td valign="top"><img src='http://www.bancoppel.com/imagenes/error/404.png' alt="" width="550"/></td>   
            </tr>
            <tr>
                <td>
                    <br><br>
                    Verifique que los datos solicitados existen, ya que no hemos encontrado información relacionada a su solicitud.
                </td>
            </tr>
            
        </table>
    @endif
</body>
</html>