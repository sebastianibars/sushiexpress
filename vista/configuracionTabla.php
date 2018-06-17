<?php
    include ("../conexion/conexion.php");

    $conexion = crearConexion();

    $tablas = "select * from tabla";

    $resTablas = $conexion->query($tablas);

    $id = "";
    $piezas = "";
    $maximo = "";
    $minimo = "";
    $precio = "";
    $mostrar = "";
///////////////////////////////////////////////////////
    $consultaMinimoPiezas = "select * from minimopiezas";
    $resPiezas = $conexion->query($consultaMinimoPiezas);
    $minimoPiezas="";
    while ($regitroPiezas = $resPiezas->fetch_array(MYSQLI_BOTH)) {
        $minimoPiezas = $regitroPiezas['PIEZAS'];
    }

    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'editar') {
            if (isset($_GET["id"])) {
                $editarTabla = "select * from tabla where id=" . $_GET["id"];
                $resTabla = $conexion->query($editarTabla);
                while ($registroTabla = $resTabla->fetch_array(MYSQLI_BOTH)) {
                    $id = $registroTabla['ID'];
                    $maximo = $registroTabla['MAXIMOCANTIDAD'];
                    $minimo = $registroTabla['MINIMOCANTIDAD'];
                    $precio = $registroTabla['PRECIO'];
                    $piezas = $registroTabla['CANTIDADPIEZAS'];
                    $mostrar = $registroTabla['MOSTRAR'];
                }
            }
        } else if (($_GET["accion"]) == 'eliminar') {
            if (isset($_GET["id"])) {
                $eliminarTabla = "delete from tabla where id=" . $_GET["id"];
                $conexion->query($eliminarTabla);
                Header("Location: configuracionTabla.php");
            }
        }
    }
    ?>
<html>
    <script type="text/javascript">

        function editar(id) {
            window.location.href = "configuracionTabla.php?accion=editar&id=" + id;
        }

        function eliminar(id) {
            window.location.href = "configuracionTabla.php?accion=eliminar&id=" + id;
        }

    </script>

    <div align="center"><h1>Configuraci&#243;n tablas</h1></div>
    <body style="background-color:lightblue;">
        <table border="2" cellspacing="0" align="center" width="70%" style="background-color:rgb(255,255,255);">
            <tr align="center">
                <td width="20%">
                    <b>Piezas</b>
                </td>
                <td width="20%">
                    <b>Maximo</b>
                </td>
                <td width="20%">
                    <b>Minimo</b>
                </td>
                <td width="20%">
                    <b>Precio</b>
                </td>
                <td width="20%">
                    <b>Mostrar</b>
                </td>
                <td width="70px">
                </td>
                <td width="70px">
                </td>
            </tr>
            <?php
            while ($registroTablas = $resTablas->fetch_array(MYSQLI_BOTH)) {

                echo '<tr align="center">
                      <td>' . $registroTablas['CANTIDADPIEZAS'] . '</td>
                      <td>' . $registroTablas['MAXIMOCANTIDAD'] . '</td>
                      <td>' . $registroTablas['MINIMOCANTIDAD'] . '</td>
                      <td>$' . $registroTablas['PRECIO'] . '</td>';
                if ($registroTablas['MOSTRAR'] == true) {
                    echo '<td><input type="checkbox" checked style="width:15px;height:15px;" disabled="disabled"></td>';
                } else {
                    echo '<td><input type="checkbox" style="width:15px;height:15px;" disabled="disabled"></td>';
                }
                echo '<td><input type="button" value="Editar" style="width:70px;height:20px" onClick="editar(' . $registroTablas['ID'] . ')"></td>
                      <td><input type="button" value="Eliminar" style="width:70px;height:20px" onClick="eliminar(' . $registroTablas['ID'] . ')"></td> </tr>';
            }
            ?>
        </table>
        <form method="POST" action="../controlador/guardar.php">
            <table width="500px" align="center" cellspacing="40">
                <input type="hidden" name="idTabla" value="<?php echo $id ?>">
                <input type="hidden" name="formulario" value="tabla">
                <tr>
                    <td>
                        <fieldset style="padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;border: 2px solid">
                            <legend align="center"><h2>Tabla</h2></legend>
                            <table width="100%">
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Piezas</b>
                                    </td>
                                    <td align="left" width="50%">
                                        <input type="text" name="piezasTabla" value="<?php echo $piezas ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Maximo</b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="text" name="maximoTabla" value="<?php echo $maximo ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Minimo</b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="text" name="minimoTabla" value="<?php echo $minimo ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Precio</b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="text" name="precioTabla" value="<?php echo $precio ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" width="50%">
                                        <b>Mostrar</b>
                                    </td>
                                    <td align="left"  width="50%">
                                        <input type="checkbox" name="mostrarTabla" 
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
                        <input type="button" value="Limpiar" style="width:150px;height:40px" onClick=" window.location.href = 'configuracionTabla.php'">
                    </td>
                    <td align="center">
                        <input type="submit" value="Guardar" style="width:150px;height:40px">
                    </td>
                </tr>
            </table>
        </form>    
        <table width="60%" align="center">
            <tr >
                <td  width="50%">
                    <form method="POST" action="../controlador/guardar.php">
                    <table align="center" cellspacing="20px"> 
                        <input type="hidden" name="formulario" value="cantidadPiezas">
                        <tr >
                            <td>
                                Minimo de piezas por variedad 
                            </td>
                            <td>
                                <input type="text" size="1" name="minimoPiezas"  value="<?php echo $minimoPiezas;?>">
                            </td>
                        </tr>
                    </table>
                        
                </td>
            </tr>
        </table>
        <table align="center" width="80%">
            <tr>
                <td align="center">
                    <input type="button" value="Atras" style="width:150px;height:40px" onClick=" window.location.href = 'configuracion.php  '">
                </td>
                <td align="center">
                    <input type="submit" value="Guardar" style="width:150px;height:40px">
                </td>
            </tr>
            </form>
        </table>
    </body>
</html>
