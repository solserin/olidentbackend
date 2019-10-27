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
        <td><strong>Reporte:</strong> Roles</td>
    </tr>

  </table>

  <br/>

  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th align="center">#</th>
        <th align="center" width="75%">Rol</th>
        <th align="center">Núm. Usuarios</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($roles as $rol)
            <tr>
                <td align="center">{{$rol->id}}</td>
                <td align="center">{{$rol->rol}}</td>
                <td align="center">{{$rol->usuarios_count}}</td>
            </tr>
            @if (($rol->usuarios_count)>0)
                <tr>
                    <td colspan="3" >
                            <table width="85%" style="margin:0px auto 0px auto;">
                                <thead style="background-color: lightgray;">
                                      <tr>
                                        <th align="center"># Usuario</th>
                                        <th align="center">Nombre</th>
                                        <th align="center">Usuario</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                            @foreach ($rol->usuarios as $usuario)
                                <tr>
                                    <td align="center">{{$usuario->id}}</td>
                                    <td align="center">{{$usuario->name}}</td>
                                    <td align="center">{{$usuario->email}}</td>
                                </tr>
                            @endforeach
                                    </tbody>
                            </table>
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
  </table>

</body>
</html>