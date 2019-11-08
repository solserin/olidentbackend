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

#datosTitular, #datosTitular th, #datosTitular td {
  border-bottom: 1px solid black;
  border-collapse: collapse;
}

 #datosTitular th, #datosTitular td {
    padding: 10px 0px 10px 0px;
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

.pagado{
    color:#28a745
}
</style>
</head>
<body>

    <footer>
    Actualizado para el día {{fechahora_completa()}}.
    </footer>

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
</body>
</html>