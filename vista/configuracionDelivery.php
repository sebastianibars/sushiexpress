<?php
include ("../conexion/conexion.php");

$conexion = crearConexion();

$delivery = "select * from delivery";
$resDelivery = $conexion->query($delivery);

$id = "";
$zona = "";
$precio = "";

if (isset($_GET["accion"])) {
    if (($_GET["accion"]) == 'editar') {
        if (isset($_GET["id"])) {
            $editarDelivery = "select * from delivery where id=" . $_GET["id"];
            $resDetalleDelivery = $conexion->query($editarDelivery);
            while ($registroDelivery = $resDetalleDelivery->fetch_array(MYSQLI_BOTH)) {
                $id = $registroDelivery["ID"];
                $zona = $registroDelivery["ZONA"];
                $precio = $registroDelivery["PRECIO"];
            }
        }
    } else if (($_GET["accion"]) == 'eliminar') {
        if (isset($_GET["id"])) {
            $eliminarDelivery = "delete from delivery where id=" . $_GET["id"];
            $conexion->query($eliminarDelivery);
            Header("Location: configuracionDelivery.php");
        }
    }
}
?>

<script type="text/javascript">

    function editar(id) {
        window.location.href = "configuracionDelivery.php?accion=editar&id=" + id;
    }

    function eliminar(id) {
        window.location.href = "configuracionDelivery.php?accion=eliminar&id=" + id;
    }

</script>
<div align="center"><h1>Configuraci&#243;n delivery</h1></div>
<body style="background-color:lightblue;">
    <table border="2" cellspacing="0" align="center" width="40%" style="background-color:rgb(255,255,255);">
        <tr align="center">
            <td>
                <b>Zona</b>
            </td>
            <td>
                <b>Precio</b>
            </td>                
            <td width="70px">
            </td>
            <td width="70px">
            </td>
        </tr>
        <tr align="center">
            <?php
            while ($registroDelivery = $resDelivery->fetch_array(MYSQLI_BOTH)) {
                echo '<tr align="center">
                      <td>' . $registroDelivery['ZONA'] . '</td>
                      <td> $'. $registroDelivery['PRECIO'] . '</td>
                      <td><input type="button" value="Editar" style="width:70px;height:20px" onClick="editar(' . $registroDelivery['ID'] . ')"></td>
                      <td><input type="button" value="Eliminar" style="width:70px;height:20px" onClick="eliminar(' . $registroDelivery['ID'] . ')"></td> </tr>';
            }
            ?>

        </tr>
    </table>
    <form method="POST" action="../controlador/guardar.php" >
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <input type="hidden" name="formulario" value="delivery">
        <table width="30%" align="center" cellspacing="40">
            <table width="30%" align="center" cellspacing="40">
                <tr>
                    <td>
                        <fieldset style="padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;border:2px solid">
                            <legend align="center"><h2>Delivery</h2></legend>
                            <table width="100%">
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Zona</b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="text" name="zonaDelivery" value="<?php echo $zona ?>">
                                    </td>

                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Precio</b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="text" name="precioDelivery" value="<?php echo $precio ?>">
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
            </table>

            <table align="center" width="80%">
                <tr>
                    <td align="center">
                        <input type="button" value="Atras" style="width:150px;height:40px" onClick=" window.location.href = 'configuracion.html'">
                    </td>
                    <td align="center">
                        <input type="button" value="Limpiar" style="width:150px;height:40px" onClick=" window.location.href = 'configuracionDelivery.php'">
                    </td>
                    <td align="center">
                        <input type="submit" value="Guardar" style="width:150px;height:40px">
                    </td>
                </tr>
            </table>    
    </form>
</body>
</html>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    