<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reporte específico</title>

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


* {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }
    h4{
        margin-top: -10px;
        font-size: 14px;
        font-style: italic;
        color: #8f9ba6;
    }
    tr:nth-child(even) {background-color: #d2edf7;}
        #datos th, #datos td {
        border-bottom: 1px solid #ddd;
    }
    #datos{
        border-collapse: collapse;
    }
    #datos td{ height: 14px };
</style>
</head>
<body>
        <table width="100%" >
                <tr>
                <td valign="top"><img src='{{$file}}' alt="" width="150"/></td>
                    <td align="right">
                        <h3>{{$empresa[0]->nombre}}</h3>
                        <h4>"Seguridad a tu Sonrisa"</h4>
                        <pre style="margin-top:-10px;">
                            {{$empresa[0]->representante}}
                            {{$empresa[0]->calle}} # {{$empresa[0]->numero}} Col. {{$empresa[0]->colonia}} {{$empresa[0]->descripcion}} C.P. {{$empresa[0]->cp}} {{$empresa[0]->ciudad}}
                            {{$empresa[0]->email}}
                            Tel. de Citas {{$empresa[0]->telefono}}
                        </pre>
                    </td>
                </tr>
              </table>

    <footer>
    Actualizado para el día {{fechahora_completa()}}.
    </footer>
    @if ($pagos)
    <table width="100%">
            <tr>
                <td><strong>Reporte:</strong>Reporte de Pagos</td> 
            </tr>
          </table>
          <br/>
          <table width="100%" id="datos">
            <thead style="background-color: lightgray;">
              <tr>
                <th align="center">#</th>
                <th align="center">Fecha</th>
                <th align="center">Titular</th>
                <th align="center">Ruta</th>
                <th align="center">Cobrador</th>
                <th align="center">Venta</th>
                <th align="center">Tipo de Venta</th>
                <th align="center">Tipo de Póliza</th>
                <th align="center">Cantidad</th>
              </tr>
            </thead>
           
            <tbody>
                @php
                ini_set('max_execution_time', 300); //300 seconds = 5 minutes
                ini_set('memory_limit', '3000M'); //This might be too large, but depends on the data set
                    $total_cobrado=0;
                @endphp
                @foreach ($pagos as $pago)
                    <tr>
                    <td align="center">{{$pago->id}}</td>
                        <td align="center">{{strtolower(fecha_abr($pago->fecha_abono))}}</td>
                        <td align="center">{{$pago->nombre}}</td>
                        <td align="center">{{$pago->ruta}}</td>
                        <td align="center">{{$pago->name}}</td>
                        <td align="center">{{$pago->ventaId}}</td>
                        <td align="center">{{$pago->tipoVenta}}</td>
                        <td align="center">{{$pago->tipoPoliza}}</td>
                        <td align="center">{{number_format($pago->cantidad,2,".",",")}}</td>
                    </tr>
                    @if ($pago->status==1)
                        @php
                            $total_cobrado+=$pago->cantidad;
                        @endphp
                    @endif
                @endforeach
                <tr>
                    <td colspan="8" align="right"></td>
                    <td colspan="1" align="right">{{number_format($total_cobrado,2,".",",")}}</td>
                </tr>
            </tbody>
          </table>
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