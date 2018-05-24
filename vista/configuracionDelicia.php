<html>
    <?php
    include ("../conexion/conexion.php");

    $conexion = crearConexion();
    ///////////////////////////////////////////////////////////////////////////////
    $delicias = "select * from delicia";
    $resDelicias = $conexion->query($delicias);

    $idDelicia = "";
    $nombreDelicia = "";
    $precioDelicia = "";
    $mostrar = "";
    $nombreDeliciaCantidadDelicia = "";
    $cantidadCantidadDelicia = "";
    $resCantidadDelicia = "";
    ///////////////////////////////////////////////////////////////////////////////
    $idCantidadDelicia = "";
    $idDeliciaCantidadDelicia = "";
    $precioCantidadDelicia = "";

    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'editar') {
            if (isset($_GET["id"])) {
                $editarDelicia = "select * from delicia where id=" . $_GET["id"];
                $resDelicia = $conexion->query($editarDelicia);
                while ($registroDelicia = $resDelicia->fetch_array(MYSQLI_BOTH)) {
                    $idDelicia = $registroDelicia['ID'];
                    $nombreDelicia = $registroDelicia['NOMBRE'];
                    $precioDelicia = $registroDelicia['PRECIO'];
                    $mostrar = $registroDelicia['MOSTRAR'];
                    $cantidadCantidadDelicia = $registroDelicia['CANTIDAD'];
                }
            }
        } else if (($_GET["accion"]) == 'eliminar') {
            if (isset($_GET["id"])) {
                $eliminarDelicia = "delete from delicia where id=" . $_GET["id"];
                $conexion->query($eliminarDelicia);
                Header("Location: configuracionDelicia.php");
            }
        }
    }
    ?>
    <script type="text/javascript">

        function editar(id) {
            window.location.href = "configuracionDelicia.php?accion=editar&id=" + id;
        }
        function eliminar(id) {
            window.location.href = "configuracionDelicia.php?accion=eliminar&id=" + id;
        }
    </script>

    <body style="background-color:lightblue;">
        <div align="center"><h1>Configuraci&#243;n delicias</h1></div>
        <table border="2" cellspacing="0" align="center" width="70%" style="background-color:rgb(255,255,255);">
            <tr align="center">
                <td>
                    <b>Delicia</b>
                </td>
                <td>
                    <b>Precio</b>
                </td>
                <td>
                    <b>Mostrar</b>
                </td>
                <td style="width:70px;height:20px">
                </td>
                <td style="width:70px;height:20px">
                </td>
            </tr>
            <tr align="center">
                <?php
                while ($registroDelicia = $resDelicias->fetch_array(MYSQLI_BOTH)) {
                    echo '<tr align="center">
                                  <td>' . $registroDelicia['NOMBRE'] . '</td>
                                  <td>$' . $registroDelicia['PRECIO'] . '</td>';
                    if ($registroDelicia['MOSTRAR'] == true) {
                        echo '<td><input type="checkbox" checked style="width:15px;height:15px;" disabled="disabled"></td>';
                    } else {
                        echo '<td><input type="checkbox" style="width:15px;height:15px;" disabled="disabled"></td>';
                    }
                    echo '<td><input type="button" value="Editar" style="width:70px;height:20px" onClick="editar(' . $registroDelicia['ID'] . ')"></td>
                          <td><input type="button" value="Eliminar" style="width:70px;height:20px" onClick="eliminar(' . $registroDelicia['ID'] . ')"></td> </tr>';
                }
                ?>
            </tr>
        </table>
        <form method="POST" action="../controlador/guardar.php" >
            <input type="hidden" name="id" value="<?php echo $idDelicia ?>">
            <input type="hidden" name="formulario" value="delicia">
            <table width="30%" align="center" cellspacing="40">
                <tr>
                    <td>
                        <fieldset style="padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;border: 2px solid">
                            <legend align="center"><h2>Delicias</h2></legend>
                            <table width="100%">
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Delicia</b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="text" name="nombreDelicia" value="<?php echo $nombreDelicia ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Precio</b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <?php
                                        if ($cantidadCantidadDelicia == 0) {
                                            echo '<input type="text" name="precioDelicia" value=' . $precioDelicia . ' >';
                                        } else {
                                            echo '<input type="text" name="precioDelicia" readonly="readonly" value=' . $precioDelicia . ' >';
                                        }
                                        ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Mostrar</b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="checkbox" name="mostrarDelicia"  
                                        <?php
                                        if ($mostrar == true) {
                                            echo 'checked ';
                                        }
                                        ?>
                                               style="width:15px;height:15px;" >
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
                        <input type="button" value="Limpiar" style="width:150px;height:40px" onClick="window.location.href = 'configuracionDelicia.php'">
                    </td>
                    <td align="center">
                        <input type="submit" value="Guardar" style="width:150px;height:40px">
                    </td>
                </tr>
            </table>  
        </form>
        <table cellspacing="20">
            <tr>
                <td></td>
            </tr>
        </table>
        <table width="100%" align="center">
            <tr>
                <td align="center">
                    <input type="button" value="Atras" style="width:150px;height:40px" onClick=" window.location.href = 'configuracion.php'">
                </td>
            </tr>
        </table>
    </body>
</html>
