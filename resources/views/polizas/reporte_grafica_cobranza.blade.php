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
    #datos td{ 
        height: 14px;
     }

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
</style>
</head>
<body>
  <table width="100%" >
    <tr>
    <td valign="top"><img src='{{$file}}' alt="" width="150"/></td>
        <td align="right">
            <h3> Reporte de Cobranza del {{strtolower(fecha_no_day($datos['fecha_inicio']))}} al {{strtolower(fecha_no_day($datos['fecha_fin']))}}</h3>
            <br>
            <pre style="margin-top:-10px;">
                Cobrado: $ {{number_format($cobrado,2,'.',',')}}
                Cancelado: $ {{number_format($cancelado,2,'.',',')}}
                Total: $ {{number_format($cobrado-$cancelado,2,'.',',')}}
            </pre>
        </td>
       
    </tr>
  </table>

  <img style="margin-left: 10px !important; width:1000px; margin-top:40px !important;" src='{{$grafica}}'/>
  <footer>
        Actualizado para el día {{fechahora_completa()}}.
  </footer>
</body>
</html>