<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Nota de Venta</title>

<style type="text/css">
img{
    max-width:70px;
    margin-top: 15px;
}
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
    font-size: 22px;
    font-weight: bold;
    text-align:center;
}
#empresa h5{
    text-align:center;
    font-size: 18px;
    line-height: 0;
    margin-top: -10px;
    font-weight: lighter;
}

table {
  border-collapse: collapse;
}

.titular{
  border: 1px solid #002f5b;
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
    top:40%;
    left: 35%;
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

.telefonos{
    font-size: 13px;
}
.telefonos table{
    margin-top: 10px;
}

.dato{
    font-weight: bold;
    font-size: 11px;
}
.dato-des{
    font-size: 11px;
}
.dato-subrayar{
    font-size: 11px;
    background-color:#002f5b;
    color: #fff;
}

.titular .dato {
    font-size: 11px;
    font-weight: normal;
    text-transform: uppercase;
}

.abonos {
margin-top: 5px;
width: 100%;
  border-collapse: collapse;
  text-align: center;
}
.abonos th{
    text-transform: uppercase;
    font-size: 10px;
}
.abonos td{
    font-size: 11px;
}

.abonos, .abonos th, .abonos td {
  border: 1px solid #002f5b;
}

.eliminado {
    text-decoration: line-through;
    color: red;
}

.clausulas{
    margin-top: 2px;
    font-size: 10px;
    text-align: justify;
    border: 1px solid #002f5b;
}

.clausulas tr td{
    padding: 5px;
}
html{
    margin:5px auto 0px 10px;
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

   <!-- <footer>
    Actualizado para el día {{fechahora_completa()}}.
    </footer>-->
    @if($venta)
    @php
        $total=$venta[0]['total'];
    @endphp
    <table width="100%">
        <tr>
            <?php
for ($x = 0; $x < 2; $x++) {
?>
        @php
        $venta[0]['total']=$total;
        @endphp
           <td width="5px">
            </td>
            <td width="50%">
                <div style="height:745px;border:1px solid #002f5b; border-radius:5px; padding:9px;">
                <table width="100%">
                    <tr>
                        <td valign="top" width="20%" style="float:left;">
                            <img src='{{public_path('images/logo-vertical.jpg')}}' alt="" width="150"/>
                        </td>  
                        <td width="80%" id="empresa">
                            <h4>Póliza Dental</h4>
                            <h5>OLI-DENT</h5>
                            <div class="telefonos">
                                <table width="100%" style="text-align:center;">
                                    <tr>
                                        <td width="50%">
                                            Citas Villa Unión: 669 193 0497
                                        </td>
                                        <td width="50%">
                                            Citas Villa Unión: 669 193 0497
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                    <table width="100%" style="text-align:center;">
                                        <tr>
                                            <td>
                                                <span class="dato">
                                                    Fecha:
                                                </span><br><span class="dato-des">{{fecha_abr($venta[0]['poliza']['fecha_afiliacion'])}}</span>
                                            </td>
                                            <td>
                                                <span class="dato">
                                                    Núm. Póliza:
                                                </span><br><span class="dato-des">{{$venta[0]['polizas_id']}}</span>
                                            </td>
                                            <td>
                                                <span class="dato">
                                                    Ref. Venta:
                                                </span><br><span class="dato-des">{{$venta[0]['id']}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div>
                                        <table width="100%" style="text-align:center;">
                                            <tr>
                                                <td>
                                                    <span class="dato">
                                                        Tipo de Póliza:
                                                    </span><br><span class="dato-des">{{$venta[0]['tipo_poliza']['tipo']}}</span>
                                                </td>
                                                <td>
                                                    <span class="dato">
                                                       Tipo de Venta:
                                                    </span><br><span class="dato-des">{{$venta[0]['tipo_venta']['tipo']}}</span>
                                                </td>
                                            </tr>
                                        </table>
                                </div>
                        </td> 
                    </tr>
                </table>
                <table class="titular" style="margin-top:4px;" width="100%">
                    <tr>
                        <td width="10%">
                            <div class="dato-subrayar">
                                NOMBRE: 
                            </div>
                        </td>
                        <td width="60%">
                            <span class="dato">
                                    {{strtolower($venta[0]['beneficiarios'][0]['nombre'])}}
                            </span>
                        </td>
                        <td width="10%">
                        <div class="dato-subrayar">
                            EDAD: 
                        </div>
                        </td>
                        <td width="20%">
                            <span class="dato">
                                    {{strtolower($venta[0]['beneficiarios'][0]['edad'])}}
                            </span>
                        </td>
                    </tr>
                </table>
                <table class="titular" width="100%" style="margin-top:2px;">
                        <tr>
                            <td width="10%">
                                <div class="dato-subrayar">
                                    TELEFONO: 
                                </div>
                            </td>
                            <td width="20%">
                                <span class="dato">
                                        {{strtolower($venta[0]['beneficiarios'][0]['telefono'])}}
                                </span>
                            </td>
                            <td width="10%">
                            <div class="dato-subrayar">
                                LOCALIDAD: 
                            </div>
                            </td>
                            <td width="60%">
                                <span class="dato">
                                    {{ucfirst($localidad[0]['nombre'])}}, {{ucfirst($localidad[0]['municipio']['nombre'])}}, Sin.
                                </span>
                            </td>
                        </tr>
                        
                    </table>
                    <table class="titular" width="100%" style="margin-top:2px;">
                        <tr>
                            <td width="15%">
                                <div class="dato-subrayar">
                                    DIRECCIÓN: 
                                </div>
                            </td>
                            <td width="85%">
                                <div class="dato">
                                        Calle {{ucfirst($venta[0]['beneficiarios'][0]['calle'])}} # {{ucfirst($venta[0]['beneficiarios'][0]['numero'])}} Col. {{ucfirst($venta[0]['beneficiarios'][0]['colonia'])}}.
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table class="abonos">
                        <tr>
                            <th width="10%">#</th>
                            <th width="10%">Ref.</th>
                            <th width="20%">fecha</th>
                            <th width="15%">firma cobrador</th>
                            <th width="15%">firma supervisor</th>
                            <th width="10%">abono</th>
                            <th width="10%">saldo</th>
                        </tr>
                        @php
                            $pagos_hechos=count($venta[0]['abonos']);
                        @endphp
                        @foreach ($venta[0]['abonos'] as $index=>$abono)
                        @if ($abono->status==1)
                        @php
                           $venta[0]['total']-=$abono->cantidad; 
                        @endphp
                        <tr>
                            <td>{{$index+1}}</td>
                            <td>
                                {{$abono->id}}
                            </td>
                            <td>
                                {{fecha_abr($abono->fecha_abono)}}
                            </td>
                            <td></td>
                            <td></td>
                            <td>{{number_format($abono->cantidad,2,".",",")}}</td>
                            <td>{{number_format(($venta[0]['total']),2,".",",")}}</td>
                        </tr>
                        @else
                            <tr class="eliminado">
                                <td>{{$index+1}}</td>
                                <td>
                                        {{$abono->id}}
                                    </td>
                                <td>
                                        {{fecha_abr($abono->fecha_abono)}}
                                </td>
                                <td></td>
                                <td></td>
                                <td>{{number_format($abono->cantidad,2,".",",")}}</td>
                                <td>{{number_format(($venta[0]['total']),2,".",",")}}</td>
                            </tr>
                        @endif
                        @endforeach
                        @for ($i =  $pagos_hechos; $i <15; $i++)
                            <tr>
                                <td>-</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor

                        <tr>
                                <td colspan="5" align="right">
                                     <span style="margin-right:3px; font-weight:bold;">$ Aboando </span>
                                </td>
                                <td>
                                {{number_format(($venta[0]['abonado']),2,".",",")}}
                                </td>
                                <td>
                                    {{number_format(($venta[0]['restante']),2,".",",")}}
                                </td>
                        </tr>
                    </table>
                    <table width="100%" class="clausulas" style="background-color:#002f5b; color:#fff; text-align:center;">
                            <tr>
                                <td>
                                    CONTRATO
                                </td>
                            </tr>
                        </table>
                    <table width="100%" class="clausulas">
                        <tr>
                            <td>
                                    En virtud de esta póliza, mediante el pago de la prima que corresponda y en las condiciones y términos que aquí se establecen, cubrirán las prestaciones y servicios detallados a continuación. Para el tratamiento integral de las enfermedades dentales que se prestaran a los asegurados incorporados a esta póliza, solo si ellos son indicados por el medico (odontólogo) especialista designados por el paciente que se encuentran identificados con las condiciones particulares.
                                    <ol type="1" style="margin-left:-20px;">
                                        <li>
                                            Esta póliza dental sirve únicamente para proporcionar los descuentos que vienen en este documento.
                                        </li>
                                        <li>
                                            El costo de la póliza individual es de $398.00 m.n, la póliza familiar es de $698.00 m.n y póliza la familiar plus es de $998.00 m.n.
                                        </li>
                                        <li>
                                            CLINICA OLI-DENT no se hace responsable por parámetros y ofrecimientos no estipulados o mencionados en la póliza de descuento.
                                        </li>
                                        <li>
                                               La póliza individual cubre únicamente a la persona acreditada como titular.
                                            </li>
                                            <li>
                                                Toda limpieza dental será sin costo alguno para el titular de la póliza al momento de contratarla.
                                            </li>
                                            <li>
                                                Toda limpieza aplicada a los beneficiarios será activa cuando la póliza sea liquidada.
                                            </li>

                                    </ol>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <?php
}
?>
       </tr>
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