<?php
include ("../conexion/conexion.php");
$conexion = crearConexion();



?>
<html>
    <script type="text/javascript">



    </script>
    
    
    <body style="background-color:lightblue;">
        <div align="center"><h1>Retira local</h1></div>
        <form method="POST" action="../controlador/guardar.php" id="formPedidoRetira">
        <input type="hidden" name="formulario" value="pedidoRetira"/>
        <table align="center" style="padding:50px;">
            <tr>
                <td>
                    Nombre
                </td>
                <td>
                    <input type="text" name="nombre">
                </td>
            </tr>
            <tr>
                <td>
                    Teléfono
                </td>
                <td>
                    <input type="text" name="telefono">
                </td>
            </tr>
            <tr>
                <td>
                    Paga con
                </td>
                <td>
                    <input type="text" name="paga" value="">
                </td>
            </tr>
            <tr>
                <td>
                    Notas
                </td>
                <td>
                    <textarea rows="5" cols="22" name="notas">
                    </textarea>
                </td>
            </tr>
        </table>
        <table align="center">
            <tr >
                <td>
                    <input type="submit" value="Finalizar pedido" style="width:140px;height:40px" onclick="volverPaginaPadre()" />
                </td>
                <td>
                    <input type="button" value="Borrar" style="width:140px;height:40px" onClick="window.location.href = 'finalPedido.php'">
                </td>
            </tr>
        </table>
    </form>

    </body>
</html>