<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reporte</title>

<style type="text/css">
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
  <table width="100%">
    <tr>
        <td><strong>Reporte:</strong> Lista de Servicios</td> 
    </tr>
  </table>
  <br/>
  <table width="100%" id="datos">
    <thead style="background-color: lightgray;">
      <tr>
        <th align="center" width="3%">#</th>
        <th align="center" width="30%">Servicio</th>
        <th align="center" width="30%">Tipo</th>
        <th align="center" width="10%">Precio normal</th>
        <th align="center" width="10%">Precio con póliza</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($servicios as $servicio)
            <tr>
                <td align="center">{{$servicio->id}}</td>
                <td align="center">{{ucfirst(mb_strtolower($servicio->servicio,'UTF-8'))}}</td>
                <td align="center">{{ucfirst(mb_strtolower($servicio['tipo']->tipo,'UTF-8'))}}</td>
                <td align="center">{{number_format($servicio->precio_normal,2,'.',',')}}</td>
                <td align="center">
                    @if ($servicio->descuento_poliza!=100)
                        @if ($servicio->tipo_precio_id==1)
                        @php
                            $precio_poliza=($servicio->precio_normal*((100-$servicio->descuento_poliza)/100));
                        @endphp
                            {{number_format($precio_poliza,2,'.',',')}}
                            @else
                            Cita Previa - {{$servicio->descuento_poliza}} %
                        @endif
                    @else
                        <strong>Gratis</strong>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
  </table>
</body>
</html>