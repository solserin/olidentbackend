
        <table width="100%" id="encabezado" style="background-color:#1675ab !important;">
            <tr>
                <td style="height:20px !important;" colspan="6" align="right">
                    <strong>($) Total de venta de ruta: </strong>
                </td>
                <td colspan="1" align="center" style="color:#000 !important;">

                   <strong>{{number_format($venta_ruta,2,".",",")}}</strong>
                </td>
            </tr>
            <tr>
                <td style="height:20px !important;" colspan="6" align="right">
                    <strong>($) Cobrado: </strong>
                </td>
                @php
                $porcentaje_recuperado=(100*$recuperado)/$total_ruta;
            @endphp
                <td colspan="1" align="center" style="color:#000 !important;">
                   <strong>{{number_format($recuperado,2,".",",")}} ({{number_format($porcentaje_recuperado,2,".",",")}} %)</strong>
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
        </table>
          <table width="100%" id="datos">
            <thead style="background-color: #1675ab; color:#fff;">
              <tr>
                <th align="center">#</th>
                <th align="center">Póliza</th>
                <th align="center">Fecha Venta</th>
                <th align="center">Titular</th>
                <th align="center">($) Total</th>
                <th align="center">($) Abonado</th>
                <th align="center">($) Saldo</th>
              </tr>
            </thead>
            <tbody>
                {{-- declaro las variables para los totales a calcular --}}
                @php
                ini_set('max_execution_time', 300); //300 seconds = 5 minutes
                ini_set('memory_limit', '6000M'); //This might be too large, but depends on the data set
                    $x=1;
                @endphp
                @foreach ($polizas as $poliza)
                    <tr>
                        <td align="center">{{$x}}</td>
                        <td align="center">{{$poliza->num_poliza}}</td>
                        <td align="center">{{strtolower(fecha_abr($poliza->ventas[0]->fecha_venta))}}</td>
                        <td align="center">{{$poliza->ventas[0]->nombre}}</td>
                        <td align="center">{{number_format($poliza->ventas[0]->total,2,".",",")}}</td>
                        <td align="center">{{number_format($poliza->ventas[0]->abonado,2,".",",")}}</td>
                        <td align="center">{{number_format($poliza->ventas[0]->restante,2,".",",")}}</td>
                    </tr>
                    @php
                        $x+=1;
                    @endphp
                @endforeach
                <tr>
                    <td style="height:20px !important;" colspan="6" align="right">
                        <strong>($) Total de venta de ruta: </strong>
                    </td>
                    <td colspan="1" align="center" style="color:#000 !important;">

                       <strong>{{number_format($venta_ruta,2,".",",")}}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="height:20px !important;" colspan="6" align="right">
                        <strong>($) Cobrado: </strong>
                    </td>
                    @php
                    $porcentaje_recuperado=(100*$recuperado)/$total_ruta;
                @endphp
                    <td colspan="1" align="center" style="color:#000 !important;">
                       <strong>{{number_format($recuperado,2,".",",")}} ({{number_format($porcentaje_recuperado,2,".",",")}} %)</strong>
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
            </tbody>
          </table>
