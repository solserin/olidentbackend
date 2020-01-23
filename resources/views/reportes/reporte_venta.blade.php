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
                        <p><strong>Reporte de Venta Del: {{(fecha_abr($fecha_inicio))}} al {{(fecha_abr($fecha_fin))}}</strong></p>
                        <p>Actualizado para el día {{fechahora_completa()}}.</p>
                    </td>
                </tr>
            </table>
    </div>
    <footer>
        Pág. <span class="pagenum"></span>
    </footer>
    @if ($ventas)
          <br/>
          <table width="100%" id="datos">
            <thead style="background-color: #1675ab; color:#fff;">
              <tr>
                <th align="center">#</th>
                <th align="center">Póliza</th>
                <th align="center">Fecha Venta</th>
                <th align="center">Titular</th>
                <th align="center">Vendedor</th>
                <th align="center">Ruta</th>
                <th align="center">($) Importe</th>
                <th align="center">($) Comisión</th>
                <th align="center">($) Enganche</th>
                <th align="center">($) Sobre Enganche</th>
              </tr>
            </thead>
            <tbody>
                {{-- declaro las variables para los totales a calcular --}}
                @php
                ini_set('max_execution_time', 300); //300 seconds = 5 minutes
                ini_set('memory_limit', '6000M'); //This might be too large, but depends on the data set
                    $x=1;
                    $total_importe=0;
                    $comision=0;
                    $enganche=0;
                    $sobreenganche=0;
                @endphp
                @foreach ($ventas as $venta)
                    <tr>
                        <td align="center">{{$x}}</td>
                        <td align="center">{{$venta->num_poliza}}</td>
                        <td align="center">{{strtolower(fecha_abr($venta->fecha_venta))}}</td>
                        <td align="center">{{$venta->nombre}}</td>
                        <td align="center">{{$venta->vendedor}}</td>
                        <td align="center">{{$venta->ruta}}</td>
                        <td align="center">{{number_format($venta->precio,2,".",",")}}</td>
                        <td align="center">{{number_format($venta->comision_vendedor,2,".",",")}}</td>
                        <td align="center">{{number_format($venta->enganche,2,".",",")}}</td>
                        <td align="center">{{number_format($venta->sobre_enganche,2,".",",")}}</td>
                    </tr>
                    @php
                        $x+=1;
                        $total_importe+=$venta->precio;
                        $comision+=$venta->comision_vendedor;
                        $enganche+=$venta->enganche;
                        $sobreenganche+=$venta->sobre_enganche;
                    @endphp
                @endforeach
                <tr>
                    <td style="height:20px !important;" colspan="6" align="right">
                        <strong>($) Total: </strong>
                    </td>
                    <td colspan="1" align="center" style="color:#000 !important;">
                       <strong>{{number_format($total_importe,2,".",",")}}</strong>
                    </td>
                      <td colspan="1" align="center" style="color:#000 !important;">
                       <strong>{{number_format($comision,2,".",",")}}</strong>
                    </td>
                    <td colspan="1" align="center" style="color:#000 !important;">
                       <strong>{{number_format($enganche,2,".",",")}}</strong>
                    </td>
                    <td colspan="1" align="center" style="color:#000 !important;">
                       <strong>{{number_format($sobreenganche,2,".",",")}}</strong>
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
