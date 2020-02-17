<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reporte de Cobranza</title>

<style type="text/css">
body{
    font-family:Helvetica, sans-serif;
    color: #1675ab;
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
    background-color: #1675ab;
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
        text-transform: uppercase;
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

    #encabezado tr td p{
        line-height: 3px !important;
        text-transform: uppercase;
    }

    #datos{
       border-collapse: collapse;
    }
    #datos td,#datos th{ 
    height: 39px;
    border: 1px solid #1675ab; 
    };



    .header,
.footer {
    width: 100%;
    text-align: center;
    position: fixed;
}

footer{
    color:#fff;
    font-size: 12px;
}
.pagenum:before {
    content: counter(page);
}
</style>
</head>
<body>
    <div style="width:100% !important; background-color:#1675ab; color:#fff;">
         <table width="100%" id="encabezado">
                <tr>
                    <td align="center">
                        <h3>CIERRE DE COBRANZA</h3>
                        <p><strong>Semana Del: {{(fecha_abr($fecha_inicio))}} al {{(fecha_abr($fecha_fin))}}</strong></p>
                        <p>
                            <strong>
                               COBRADOR: {{$ruta[0]->name}}  - RUTA: {{$ruta[0]->ruta}}
                            </strong>
                        </p>
                        <p>Actualizado para el día {{fechahora_completa()}}.</p>
                    </td>
                </tr>
            </table>
    </div>
    <footer>
        Pág. <span class="pagenum"></span>
    </footer>
     @if (count($pagos))
     <br>
          <table width="100%" id="datos">
            <thead style="background-color: #1675ab; color:#fff; font-size:10px;">
              <tr>
                <th align="center">Día de cobro</th>
                <th align="center">Total Pólizas</th>
                <th align="center">Pólizas Abonadas</th>
                <th align="center">Pólizas no Abonadas</th>
                <th align="center">Pólizas Pagadas</th>
                <th align="center">Pólizas Canceladas</th>
                <th align="center">Pólizas Nuevas</th>
                <th align="center">Total Pólizas Activas</th>
                <th align="center">Cobranza del Día</th>
                <th align="center">Comisión 10%</th>
                <th align="center">Cobro a Entregar</th>
                <th align="center">Cobro Recibido en Ruta</th>
                <th align="center">Faltante o Restante</th>
              </tr>
            </thead>
            <tbody>
                {{-- declaro las variables para los totales a calcular --}}
                @php
                ini_set('max_execution_time', 300); //300 seconds = 5 minutes
                ini_set('memory_limit', '6000M'); //This might be too large, but depends on the data set
                    $total_polizas=0;
                    $polizas_abonadas=0;
                    $no_abonadas=0;
                    $polizas_pagadas=0;
                    $polizas_canceladas=0;
                    $polizas_nuevas=0;
                    $activas=0;
                    $cobranza_dia=0;
                    $comision=0;
                    $cobro_entregar=0;
                    $x=0;
                    if($pagos){
                        //se asgina el primero
                         $fecha=$pagos[0]->fab;
                    }

                   
                @endphp

                @foreach ($pagos as $pago)
                @if ($x==0)
                    <tr>
                       <td align="center"><strong>{{strtolower(dia($pago->fab))}}</strong></td>
                       <td align="center">{{$pago->total_polizas}}</td>
                       <td align="center">{{$pago->polizas_abonadas}}</td>
                       <td align="center">{{$pago->no_abonadas}}</td>
                       <td align="center">{{$pago->polizas_pagadas}}</td>
                       <td align="center">{{$pago->polizas_canceladas}}</td>
                       <td align="center">{{$pago->polizas_nuevas}}</td>
                       <td align="center">{{$pago->activas}}</td>
                       <td align="center">{{number_format($pago->cobranza_dia,2,".",",")}}</td>
                       <td align="center">{{number_format($pago->comision,2,".",",")}}</td>
                       <td align="center">{{number_format($pago->cobro_entregar,2,".",",")}}</td>
                       <td></td>
                       <td></td>
                    </tr>
                    @php
                    $x++;
                        $polizas_nuevas+=$pago->polizas_nuevas;
                        $total_polizas+=$pago->total_polizas;
                        $polizas_abonadas+=$pago->polizas_abonadas;
                        $polizas_pagadas+=$pago->polizas_pagadas;
                        $polizas_canceladas+=$pago->polizas_canceladas;
                        $activas+=$pago->activas;
                        $cobranza_dia+=$pago->cobranza_dia;
                        $comision+=$pago->comision;
                        $cobro_entregar+=$pago->cobro_entregar;
                    @endphp
    
                    
                    @else
                    @if ($fecha!=$pago->fab)
                    

                        @php
                            $fecha=$pago->fab;
                        @endphp
                        <tr>
                        <td align="center"><strong>{{strtolower(dia($pago->fab))}}</strong></td>
                        <td align="center">{{$pago->total_polizas}}</td>
                        <td align="center">{{$pago->polizas_abonadas}}</td>
                        <td align="center">{{$pago->no_abonadas}}</td>
                        <td align="center">{{$pago->polizas_pagadas}}</td>
                        <td align="center">{{$pago->polizas_canceladas}}</td>
                        <td align="center">{{$pago->polizas_nuevas}}</td>
                        <td align="center">{{$pago->activas}}</td>
                        <td align="center">{{number_format($pago->cobranza_dia,2,".",",")}}</td>
                        <td align="center">{{number_format($pago->comision,2,".",",")}}</td>
                        <td align="center">{{number_format($pago->cobro_entregar,2,".",",")}}</td>
                        <td></td>
                        <td></td>
                        </tr>
                        @php
                        $x+=1;
                        $polizas_nuevas+=$pago->polizas_nuevas;
                            $total_polizas+=$pago->total_polizas;
                            $polizas_abonadas+=$pago->polizas_abonadas;
                            $polizas_pagadas+=$pago->polizas_pagadas;
                            $polizas_canceladas+=$pago->polizas_canceladas;
                            $activas+=$pago->activas;
                            $cobranza_dia+=$pago->cobranza_dia;
                            $comision+=$pago->comision;
                            $cobro_entregar+=$pago->cobro_entregar;
                        @endphp
        
                    @endif
                @endif
                    
                @endforeach
                @php
                $total=count($pagos);
                @endphp
                <tr>
                       <td align="center"><strong>TOTAL</strong></td>
                       <td align="center"><strong>
                           @if ($pagos)
                               {{$pagos[$total-1]->total_polizas}}
                           @else
                               0
                           @endif
                        </strong></td>
                       <td align="center"><strong>{{$polizas_abonadas}}</strong></td>
                       <td align="center"><strong>
                            @if ($pagos)
                               {{$pagos[$total-1]->total_polizas-$polizas_abonadas}}
                           @else
                               0
                           @endif
                    </strong></td>
                       <td align="center"><strong>{{$polizas_pagadas}}</strong></td>
                       <td align="center"><strong>{{$polizas_canceladas}}</strong></td>
                        <td align="center"><strong>{{$polizas_nuevas}}</strong></td>
                       <td align="center"><strong>
                            @if ($pagos)
                               {{($pagos[$total-1]->total_polizas-$polizas_pagadas-$polizas_canceladas+$polizas_nuevas)}}
                           @else
                               0
                           @endif
                    </strong></td>
                       <td align="center"><strong>{{number_format($cobranza_dia,2,".",",")}}</strong></td>
                       <td align="center"><strong>{{number_format($comision,2,".",",")}}</strong></td>
                       <td align="center"><strong>{{number_format($cobro_entregar,2,".",",")}}</strong></td>
                       <td></td>
                       <td></td>
                    </tr>
                    <tr>
                        <td colspan="13">
                            <p>OBSERVACIÓN:</p>
                            <br><br><br><br><br><br>
                            <table width="100%">
                                <tr>
                                    <td align="center" style="border:none;">SUPERVISOR</td>
                                    <td align="center" style="border:none; ">COBRADOR</td>
                                    <td align="center" style="border:none;">GERENTE VENTAS</td>
                                </tr>
                            </table> 
                        </td>
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
