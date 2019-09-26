<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Aloha!</title>

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
</style>

</head>
<body>

  <table width="100%">
    <tr>
        <td valign="top"><img src="https://www.sitio.solserin.com/assets/images/website/logo_de_solserin.png" alt="" width="150"/></td>
        <td align="right">
            <h3>Clínica Dental <strong>OLI DENT</strong></h3>
            <h4>"Seguridad a tu Sonrisa"</h4>
            <pre style="margin-top:-10px;">
               
                Dra. Cynthia Oliva López Martínez - Cirujano Dentista - U.A.S
                Francisco I Madero #407 Sur. Entre 5 de Febrero y 20 de Noviembre. Villa Unión Mazatlan.
                Olident.salud@gmail.com
                Tel. de Citas 6691 930497
                Facebook. Clinica Olident
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

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th align="center">#</th>
        <th align="center" width="75%">Servicio</th>
        <th align="center">Tipo</th>
        <th align="center">Precio normal</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($servicios as $servicio)
        @foreach ($servicio['servicios'] as $item)
            <tr>
               
                <td align="center">{{$item->id}}</td>
                <td align="center">{{ucfirst(strtolower($item->servicio))}}</td>
                <td align="center">{{ucfirst(strtolower($servicio->tipo))}}</td>
                <td align="center">{{number_format($item->precio_normal, 2, '.', ',')}}</td>
            </tr>
        @endforeach
           
        @endforeach
    </tbody>
  </table>

</body>
</html>