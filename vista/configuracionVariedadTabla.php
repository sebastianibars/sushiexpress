<html>
    <?php
    include ("../conexion/conexion.php");

    $conexion = crearConexion();

    $grupoVariedadTablas = "select * from grupotabla";

    $resGrupoVariedadTablas = $conexion->query($grupoVariedadTablas);

    $idGrupoVariedad = "";
    $nombreGrupoVariedad = "";
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $variedadTablas = "SELECT var.ID,var.NOMBRE,grupo.NOMBRE NOMBREGRUPOTABLA, var.MOSTRAR FROM variedadtabla var
                        inner join grupotabla grupo on grupo.id = var.GRUPOTABLAID";

    $resVariedadTablas = $conexion->query($variedadTablas);

    $idVariedad = "";
    $nombreVariedad = "";
    $grupoTabla = "";
    $mostrar = "";
    //////////////////////////////////////////////////////////////////////////////////////////////
    $grupoVariedadTablasSelect = "select * from grupotabla";

    $resGrupoVariedadTablasSelect = $conexion->query($grupoVariedadTablasSelect);

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'editarGrupoVariedad') {
            if (isset($_GET["id"])) {
                $editarGrupoVariedadTabla = "select * from grupotabla where id=" . $_GET["id"];
                $resGrupoVariedadTabla = $conexion->query($editarGrupoVariedadTabla);
                while ($registroGrupoVariedadTabla = $resGrupoVariedadTabla->fetch_array(MYSQLI_BOTH)) {
                    $idGrupoVariedad = $registroGrupoVariedadTabla['ID'];
                    $nombreGrupoVariedad = $registroGrupoVariedadTabla['NOMBRE'];
                }
            }
        } else if (($_GET["accion"]) == 'eliminarGrupoVariedad') {
            if (isset($_GET["id"])) {
                $eliminarGrupoVariedadTabla = "delete from grupotabla where id=" . $_GET["id"];
                $conexion->query($eliminarGrupoVariedadTabla);
                Header("Location: configuracionVariedadTabla.php");
            }
        } else if (($_GET["accion"]) == 'editarVariedad') {
            if (isset($_GET["id"])) {
                $editarVariedadTabla = "select * from variedadtabla where id=" . $_GET["id"];
                $resVariedadTabla = $conexion->query($editarVariedadTabla);

                while ($registroVariedadTabla = $resVariedadTabla->fetch_array(MYSQLI_BOTH)) {
                    $idVariedad = $registroVariedadTabla['ID'];
                    $nombreVariedad = $registroVariedadTabla['NOMBRE'];
                    $grupoTabla = $registroVariedadTabla['GRUPOTABLAID'];
                    $mostrar = $registroVariedadTabla['MOSTRAR'];
                }
            }
        }else if (($_GET["accion"]) == 'eliminarVariedad') {
            if (isset($_GET["id"])) {
                $eliminarVariedadTabla = "delete from variedadtabla where id=" . $_GET["id"];
                $conexion->query($eliminarVariedadTabla);
                Header("Location: configuracionVariedadTabla.php");
            }
        }
    }
    ?>
    <script type="text/javascript">

        function editarGrupo(id) {
            window.location.href = "configuracionVariedadTabla.php?accion=editarGrupoVariedad&id=" + id;
        }
        function eliminarGrupo(id) {
            window.location.href = "configuracionVariedadTabla.php?accion=eliminarGrupoVariedad&id=" + id;
        }

        function editarVariedad(id) {
            window.location.href = "configuracionVariedadTabla.php?accion=editarVariedad&id=" + id;
        }
        function eliminarVariedad(id) {
            window.location.href = "configuracionVariedadTabla.php?accion=eliminarVariedad&id=" + id;
        }
    
    </script>

    <body style="background-color:lightblue;">
        <div align="center"><h1>Configuraci&#243;n variedades tablas</h1></div>
        <table width="100%">
            <tr>
                <td width="40%" valign="top">
                    <table border="2" cellspacing="0" align="center"  width="95%" style="background-color:rgb(255,255,255);">
                        <tr align="center">
                            <td>
                                <b>Grupo variedad</b>
                            </td>
                            <td style="width:70px;height:20px">
                            </td>
                            <td style="width:70px;height:20px">
                            </td>
                        </tr>
                        <tr align="center">
                            <?php
                            while ($registroGrupoVariedadTablas = $resGrupoVariedadTablas->fetch_array(MYSQLI_BOTH)) {
                                echo '<tr align="center">
                                <td>' . $registroGrupoVariedadTablas['NOMBRE'] . '</td>
                                <td><input type="button" value="Editar" style="width:70px;height:20px" onClick="editarGrupo(' . $registroGrupoVariedadTablas['ID'] . ')"></td>
                                <td><input type="button" value="Eliminar" style="width:70px;height:20px" onClick="eliminarGrupo(' . $registroGrupoVariedadTablas['ID'] . ')"></td> </tr>';
                            }
                            ?>
                        </tr>
                    </table>
                    <form method="POST" action="../controlador/guardar.php">
                        <table width="100%" align="center" cellspacing="40" >
                            <input type="hidden" name="idGrupoVariedad" value="<?php echo $idGrupoVariedad ?>">
                            <input type="hidden" name="formulario" value="grupoVariedad">
                            <tr>
                                <td>
                                    <fieldset style="padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;border: 2px solid">
                                        <legend align="center"><h2>Grupo variedad</h2></legend>
                                        <table width="100%" >                                        
                                            <tr>
                                                <td align="right" width="50%">
                                                    <b>Grupo variedad</b>
                                                </td>
                                                <td align="left"  width="50%">
                                                    <input type="text" name="nombreGrupoVariedad" value="<?php echo $nombreGrupoVariedad ?>">
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
                                    <input type="button" value="Limpiar" style="width:150px;height:40px" onClick=" window.location.href = 'configuracionVariedadTabla.php'">
                                </td>
                                <td align="center">
                                    <input type="submit" value="Guardar" style="width:150px;height:40px">
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
                <td width="60%" valign="top">
                    <table border="2" cellspacing="0" align="center" valign="top" width="100%" style="background-color:rgb(255,255,255);">
                        <tr align="center">
                            <td>
                                <b>Variedad</b>
                            </td>
                            <td>
                                <b>Grupo variedad</b>
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
                            while ($registroVariedadTabla = $resVariedadTablas->fetch_array(MYSQLI_BOTH)) {
                                echo '<tr align="center">
                                     <td>' . $registroVariedadTabla['NOMBRE'] . '</td>
                                     <td>' . $registroVariedadTabla['NOMBREGRUPOTABLA'] . '</td>';
                                     if ($registroVariedadTabla['MOSTRAR'] == true) {
                                        echo '<td><input type="checkbox" checked style="width:15px;height:15px;" disabled="disabled"></td>';
                                     } else {
                                        echo '<td><input type="checkbox" style="width:15px;height:15px;" disabled="disabled"></td>';
                                     }
                                     echo '<td><input type="button" value="Editar" style="width:70px;height:20px" onClick="editarVariedad(' . $registroVariedadTabla['ID'] . ')"></td>
                                     <td><input type="button" value="Eliminar" style="width:70px;height:20px" onClick="eliminarVariedad(' . $registroVariedadTabla['ID'] . ')"></td> </tr>';
                            }
                            ?>
                        </tr>
                    </table>
                    <form method="POST" action="../controlador/guardar.php" >
                        <table width="70%" align="center" cellspacing="40">
                            <tr>
                                <td>
                                    <fieldset style="padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;border: 2px solid">
                                        <legend align="center"><h2>Variedad</h2></legend>
                                        <table width="100%">
                                            <input type="hidden" name="idVariedad" value="<?php echo $idVariedad ?>">
                                            <input type="hidden" name="formulario" value="variedad">
                                            <tr>
                                                <td align="right" width="50%">
                                                    <b>Variedad</b>
                                                </td>
                                                <td align="left"  width="50%">
                                                    <input type="text" name="nombreVariedad" value="<?php echo $nombreVariedad ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" width="50%">
                                                    <b>Grupo variedad</b>
                                                </td>
                                                <td align="left" width="50%" >
                                                    <select name="selectGrupoVariedad" style="width:120px;height:25px">
                                                        <?php
                                                        while ($registroVariedadTablaSelect = $resGrupoVariedadTablasSelect->fetch_array(MYSQLI_BOTH)) {
                                                            if ($grupoTabla == $registroVariedadTablaSelect['ID']) {
                                                                echo '<option selected value=' . $registroVariedadTablaSelect['ID'] . '>' . $registroVariedadTablaSelect['NOMBRE'] . ' </option> ';
                                                            } else {
                                                                echo '<option value=' . $registroVariedadTablaSelect['ID'] . '>' . $registroVariedadTablaSelect['NOMBRE'] . ' </option> ';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" width="50%">
                                                    <b>Mostrar</b>
                                                </td>
                                                <td align="left"  width="50%">
                                                   <input type="checkbox" name="mostrarVariedad"  
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
                            <tr>
                                <td align="center">
                                    <input type="button" value="Limpiar" style="width:150px;height:40px" onClick=" window.location.href = 'configuracionVariedadTabla.php'">
                                </td>
                                <td align="center">
                                    <input type="submit" value="Guardar" style="width:150px;height:40px">
                                </td>
                            </tr>
                            </tr>
                        </table>  
                    </form>    
                </td>
            </tr>
        </table>
        <table cellspacing="20" >
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
