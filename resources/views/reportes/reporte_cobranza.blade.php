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

    tr:nth-child(even) {background-color: #d2e4ee;}
        #datos th, #datos td {
        border-bottom: 1px solid #ddd;
    }
    #datos{
        border-collapse: collapse;
    }
    #datos td{ height: 14px };



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
                        <h3>CLÍNICA OLI-DENT S.R.L de C.V.</h3>
                        <p>{{$pagos[0]->name}} - {{$pagos[0]->ruta}}</p>
                        <p><strong>Reporte de Cobranza Del: {{(fecha_abr($fecha_inicio))}} al {{(fecha_abr($fecha_fin))}}</strong></p>
                        <p>Actualizado para el día {{fechahora_completa()}}.</p>
                    </td>
                </tr>
            </table>
    </div>
    <footer>
        Pág. <span class="pagenum"></span>
    </footer>
    @if ($cobro_id!='')
                            @if (count($pagos)>0)
                            <p style="font-size:12px; text-transform:uppercase; font-weight:bold;text-align:center;">Abonos cobrados por: {{$pagos[0]->cobrador}}.</p>
                            @endif
                            @else
                            <p style="font-size:12px; text-transform:uppercase; font-weight:bold;text-align:center;">Cobrado por todos los cobradores</p>
                        @endif
    @if ($pagos)
          <br/>
          <table width="100%" id="datos">
            <thead style="background-color: #1675ab; color:#fff;">
              <tr>
                <th align="center">#</th>
                <th align="center">Póliza</th>
                <th align="center">Fecha</th>
                <th align="center">Titular</th>
                <th align="center">($) Importe</th>
                <th align="center">($) Abono</th>
                <th align="center">($) Saldo</th>
              </tr>
            </thead>
            <tbody>
                {{-- declaro las variables para los totales a calcular --}}
                @php
                ini_set('max_execution_time', 300); //300 seconds = 5 minutes
                ini_set('memory_limit', '6000M'); //This might be too large, but depends on the data set
                    $cobrado_ruta=0;
                    $x=1;
                @endphp
                @foreach ($pagos as $pago)
                @if ($pago->importe>0 && $pago->id_abo!=$pago->enganche_id)
                    <tr>
                        <td align="center">{{$x}}</td>
                        <td align="center">{{$pago->polizas_id}}</td>
                        <td align="center">{{strtolower(fecha_abr($pago->fecha_abono))}}</td>
                        <td align="center">{{$pago->nombre}}</td>
                        <td align="center">{{number_format($pago->importe,2,".",",")}}</td>
                        <td align="center">{{number_format($pago->cantidad,2,".",",")}}</td>
                        <td align="center">{{number_format($pago->saldo,2,".",",")}}</td>
                    </tr>
                    @php
                        $cobrado_ruta+=$pago->cantidad;
                        $x+=1;
                    @endphp
                @endif
                @endforeach
                @php
                /**AL TOTAL RESTANTE DE LA RUTA LE SUMO LO QUE SE CAPTURO ESTE PERIDO DE FECHAS*/
                $total_ruta+=$cobrado_ruta;
                $x+=1;
                @endphp
                <tr>
                    <td style="height:20px !important;" colspan="6" align="right">
                        <strong>($) Cobrado: </strong>
                    </td>
                    <td colspan="1" align="center" style="color:#000 !important;">
                       <strong>{{number_format($cobrado_ruta,2,".",",")}}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="height:20px !important;" colspan="6" align="right">
                        <strong>($) Saldo restante de la ruta: </strong>
                    </td>
                    <td colspan="1" align="center" style="color:#000 !important;">
                       <strong>{{number_format($total_ruta,2,".",",")}}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="height:20px !important;" colspan="6" align="right">
                        <strong>(%) Recuperado en el periodo de cobranza sobre 10% de la ruta ({{number_format(($total_ruta/10),2,".",",")}}): </strong>
                    </td>
                    <td colspan="1" align="center" style="color:#000 !important;">
                        @php
                            $porcentaje_recuperado=(100*$cobrado_ruta)/($total_ruta/10);
                        @endphp
                       <strong>{{number_format($porcentaje_recuperado,2,".",",")}} %</strong>
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
