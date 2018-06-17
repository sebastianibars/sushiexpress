<?php
include ("../conexion/conexion.php");
$conexion = crearConexion();

$minimoPiezas = "select * from minimopiezas";
$resMinimoPiezas = $conexion->query($minimoPiezas);

$minimoPiezas = 5;

while ($registroMinimoPiezas = $resMinimoPiezas->fetch_array(MYSQLI_BOTH)) {
    $minimoPiezas = $registroMinimoPiezas["PIEZAS"];
}
//////////////////////////////////////////////////////////

$tablas = "select * from tabla";

$resTablas = $conexion->query($tablas);

$cantTablas = $resTablas->num_rows;

$cantFilasTotales = ceil($cantTablas / 6);
/////////////////////////////////////////////////////////////

$grupoTablas = "SELECT grupotabla.ID,grupotabla.NOMBRE FROM grupotabla inner join variedadtabla on grupotabla.id = variedadtabla.GRUPOTABLAID group by grupotabla.ID";

$resGrupoTablas = $conexion->query($grupoTablas);

/////////////////////////////////////////////////////////////
$btnVariedadTablas = "select * from BTN_VARIEDAD_TMP_SELECCION";

$resBtnVariedadTablas = $conexion->query($btnVariedadTablas);

$cantBtnVariedadTablas = $resBtnVariedadTablas->num_rows;

$arrayBtnVariedad = array();
while ($registroBtnVariedadTablas = $resBtnVariedadTablas->fetch_array(MYSQLI_BOTH)) {
    $arrayBtnVariedad[] = $registroBtnVariedadTablas["BOTONID"];
}
//////////////////////////////////////////////////////////////

$botonCantidad = "select * from BTN_CANTIDAD_PIEZAS_TMP_SELECCION";

$resBotonCantidad = $conexion->query($botonCantidad);
$botonCantidadId = 0;
$maximoTabla = 0;
$minimoTabla = 0;
$precioTabla = 0;

while ($registroBotonCantidad = $resBotonCantidad->fetch_array(MYSQLI_BOTH)) {
    $botonCantidadId = $registroBotonCantidad["BOTONID"];
}

$maximoMinimoTabla = "SELECT * FROM tabla WHERE id =".$botonCantidadId;
$resMaximoMinimoTabla = $conexion->query($maximoMinimoTabla);
while ($registroMaximoMinimoTabla = $resMaximoMinimoTabla->fetch_array(MYSQLI_BOTH)) {
    $maximoTabla = $registroMaximoMinimoTabla["MAXIMOCANTIDAD"];
    $minimoTabla = $registroMaximoMinimoTabla["MINIMOCANTIDAD"];
    $precioTabla = $registroMaximoMinimoTabla["PRECIO"];
}
/////////////////////////////////////////////////////////////////////////////
$variedadesLista = "SELECT BTN_VARIEDAD_TMP_SELECCION.BOTONID,BTN_VARIEDAD_TMP_SELECCION.CANTIDAD,VARIEDADTABLA.NOMBRE FROM  BTN_VARIEDAD_TMP_SELECCION
INNER JOIN VARIEDADTABLA ON BTN_VARIEDAD_TMP_SELECCION.BOTONID = VARIEDADTABLA.ID";
$resVariedadesLista = $conexion->query($variedadesLista);

while ($cantTablas > 6) {
    $cantTablas = $cantTablas - 6;
}

if (isset($_GET["accion"])) {
    if (($_GET["accion"]) == 'guardarCantidadTabla') {
        if (isset($_GET["id"])) {
            $borrarDatosTablaTemporal = "delete from BTN_CANTIDAD_PIEZAS_TMP_SELECCION";
            $conexion->query($borrarDatosTablaTemporal);
            $idBoton = $_GET["id"];

            $guardarDatosTablaTemporal = "insert into BTN_CANTIDAD_PIEZAS_TMP_SELECCION (botonid) value ($idBoton)";
            $conexion->query($guardarDatosTablaTemporal);

            $borrarDatosTablaTemporalVariedad = "delete from BTN_VARIEDAD_TMP_SELECCION";
            $conexion->query($borrarDatosTablaTemporalVariedad);

            Header("Location: tablas.php");
        }
    } else if (($_GET["accion"]) == 'guardarVariedadTabla') {
        if (isset($_GET["id"])) {
            $idBoton = $_GET["id"];
            $cantTabla=$_GET["cantTablas"];
            $cantBtnVariedad = "select * from BTN_VARIEDAD_TMP_SELECCION where BOTONID =". $idBoton;
            $resCantBtnVariedad = $conexion->query($cantBtnVariedad);
            $resultCantBtnVariedad = $resCantBtnVariedad->num_rows;

            if ($resultCantBtnVariedad > 0) {
                $borrarDatosTablaTemporal = "delete from BTN_VARIEDAD_TMP_SELECCION where BOTONID =". $idBoton;
                $conexion->query($borrarDatosTablaTemporal);
                
            } else {
                $guardarDatosTablaTemporal = "insert into BTN_VARIEDAD_TMP_SELECCION (BOTONID,CANTIDAD,TABLAID) value ($idBoton,0,$cantTabla)";
                $conexion->query($guardarDatosTablaTemporal);
            }
            Header("Location: tablas.php");
        }
    }else if (($_GET["accion"]) == 'guardarCantidadSelect') {
        if (isset($_GET["id"])) {
            $idVariedad = $_GET["id"];
            $cantidadVariedad=$_GET["cantidad"];
            $cantVariedad = "update BTN_VARIEDAD_TMP_SELECCION set CANTIDAD = $cantidadVariedad where BOTONID=$idVariedad";
            echo $cantVariedad;
            $conexion->query($cantVariedad);
           
            Header("Location: tablas.php");
        }
    }else if (($_GET["accion"]) == 'borrarSeleccion') {
            $borrarTablas="DELETE FROM btn_cantidad_piezas_tmp_seleccion";
            $conexion->query($borrarTablas); 
            $borrarTablas="DELETE FROM btn_variedad_tmp_seleccion";
            $conexion->query($borrarTablas); 
           
            Header("Location: tablas.php");
    }
}
?>
<script type="text/javascript">
    

    function guardarCantidadTabla(id) {
        window.location.href = "tablas.php?accion=guardarCantidadTabla&id=" + id;
    }

    function gurdarCantidades(id) {
        var maximoCantidad = <?php echo $maximoTabla ?>;
        var precio = <?php echo $precioTabla ?>;
        var cantBtnVariedad =<?php echo $cantBtnVariedadTablas ?>;
        var cantTablas =<?php echo $botonCantidadId ?>;

        var cantMaxima = false;
        if (cantTablas == 0) {
            var mimodal = crearModalTexto("NO HAY CANTIDAD SELECCIONADA");
            mostrarModal(mimodal);
        } else {
            var arrayBtnVariedad = <?php echo json_encode($arrayBtnVariedad) ?>;
            if (arrayBtnVariedad.length > 0) {
                for (var i = 0; i < arrayBtnVariedad.length; i++)
                {
                    if (arrayBtnVariedad[i] == id) {
                        cantMaxima = true;
                        break;
                    }
                }
                if (!cantMaxima) {
                    if ((cantBtnVariedad + 1) > maximoCantidad) {
                        var mimodal = crearModalTexto("NO SE PUEDEN SELECCIONAR MAS VARIEDADES");
                        mostrarModal(mimodal);
                    } else {
                        window.location.href = "tablas.php?accion=guardarVariedadTabla&id=" + id+"&cantTablas="+cantTablas;
                    }
                } else {
                    window.location.href = "tablas.php?accion=guardarVariedadTabla&id=" + id+"&cantTablas="+cantTablas;
                }
            } else {
                window.location.href = "tablas.php?accion=guardarVariedadTabla&id=" + id+"&cantTablas="+cantTablas;
            }
        }
    }
    
    function guardarCantidadSelect(idCantidad){
        var idCantidadArray = idCantidad.split("_");
        var id=idCantidadArray[0];
        var cantidad =idCantidadArray[1];
        window.location.href = "tablas.php?accion=guardarCantidadSelect&id=" + id+"&cantidad="+cantidad;
    }
    
    function borrarSeleccion(){
        window.location.href = "tablas.php?accion=borrarSeleccion";
    }
    
    function validarMinimoPiezas(){
        var minimoCantidad = <?php echo $minimoTabla ?>;
        var cantBtnVariedad = <?php echo $cantBtnVariedadTablas ?>;
        var arrayBtnVariedad = <?php echo json_encode($arrayBtnVariedad) ?>;
        var contarCantidad = true;
        
        for (var i = 0; i < arrayBtnVariedad.length; i++){
            if(arrayBtnVariedad[i] == 1 || arrayBtnVariedad[i] == 2 || arrayBtnVariedad[i] == 3)
            contarCantidad = false;
        }

        if((cantBtnVariedad < minimoCantidad) && contarCantidad){
             var mimodal = crearModalTexto("SE DEBEN ELEGIR "+minimoCantidad+" O MAS VARIEDADES");
             mostrarModal(mimodal);
        }else{
            window.location.href = "pedido.php";
        }
    }
    
    function crearModalTexto(msj) {
        var f = document.createElement("div");
        var m = document.createElement("div");
        var t = document.createTextNode(msj);
        f.appendChild(m);
        m.appendChild(t);
        f.className = "contenedor";
        m.className = "modal";
        var cerrar = document.createElement("div");
        var x = document.createTextNode("X");
        cerrar.appendChild(x);
        cerrar.className = "cerrar";
        cerrar.addEventListener("click", function () {
            f.style.visibility = "hidden";
        });
        m.appendChild(cerrar);
        document.body.appendChild(f);
        return f;
    }

    function mostrarModal(obj) {
        obj.style.visibility = "visible";
    }

</script>

<html>
    <style>
        .contenedor {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0px;
            left: 0px;
            background-color: rgba(0,0,0,0.5);
            visibility: hidden;
        }
        .modal {
            background-color: white;
            border-radius: 4px;
            padding: 25px;
            margin: 10% auto;
            width: 30%;
            height: auto;
        }
        .cerrar {
            width: auto;
            height: auto;
            cursor: pointer;
            color: rgba(255,0,0,0.5);
            padding: 7px;
            float: right;
            font-family: "Calibri";
            font-weight: bold;
        }
        .cerrar:hover{
            color: rgba(255,0,0,1);
        }
    </style>
    <h1 align="center">Tablas</h1>
    <body style="background-color:lightblue;">
        <table width="80%" align="center"  cellspacing="10">

            <?php
            $cantFilas = 1;
            $nombreTabla = "";
            $precioTabla = "";
            while ($registroTablas = $resTablas->fetch_array(MYSQLI_BOTH)) {
                if ($cantFilasTotales != 1) {
                    if ($cantFilas == 1) {
                        echo '<tr align="center" >';
                    }
                    echo '<td align="center" >
                         <input type="button" value="' . $registroTablas["CANTIDADPIEZAS"] . ' piezas" style="width:140px;height:30px';
                    if ($botonCantidadId == $registroTablas["ID"]) {
                        $nombreTabla = $registroTablas["CANTIDADPIEZAS"];
                        $precioTabla = $registroTablas["PRECIO"];
                        echo ';background-color: GRAY;color: WHITE;';
                    }
                    echo '" onClick="guardarCantidadTabla(' . $registroTablas['ID'] . ')">
                         </td>';
                    if ($cantFilas == 6) {
                        echo '</tr>';
                        $cantFilasTotales = $cantFilasTotales - 1;
                        $cantFilas = 1;
                    } else {
                        $cantFilas = $cantFilas + 1;
                    }
                } else {
                    if ($cantFilas == 1) {
                        echo '</table><table width="80%" align="center"><tr align="center">';
                    }
                    echo '<td align="center">
                         <input type="button" value="' . $registroTablas["CANTIDADPIEZAS"] . ' piezas" style="width:140px;height:30px';
                    if ($botonCantidadId == $registroTablas["ID"]) {
                        $nombreTabla = $registroTablas["CANTIDADPIEZAS"];
                        $precioTabla = $registroTablas["PRECIO"];
                        echo ';background-color: GRAY;color: WHITE;';
                    }
                    echo '" onClick="guardarCantidadTabla(' . $registroTablas['ID'] . ')">
                         </td>';
                    if ($cantFilas == 6) {
                        echo '</tr>
                  </table>';
                        $cantFilas = 1;
                    } else {
                        $cantFilas = $cantFilas + 1;
                    }
                    $cantFilasTotales = $cantFilasTotales - 1;
                }
            }
            ?>
        </table>	
        <h2 align="center">Variedades</h2>
        <table width="100%">
            <tr>				
                <td  valign="top" width="30%">
                    <div align="center">
                        <?php
                        echo '<h3>';
                        if ($nombreTabla != "") {
                            echo 'Tabla de ' . $nombreTabla . ' piezas<br>Total $' . $precioTabla . '</h3>';
                        }
                        ?>
                    </div>
                    <table cellspacing="0" width="100%" border="2" style="background-color:rgb(255,255,255);" >
                        <tr >
                            <td align="center">
                                <b>Variedad</b>
                            </td>
                            <td align="center" style="width:20px">
                                <b>Cantidad</b>
                            </td>
                        </tr>
                        <?php
                        while ($registroVariedadesLista = $resVariedadesLista->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr valign="top">
                                    <td align="center">' . $registroVariedadesLista["NOMBRE"] . '
                                        <td><select name="cantidadVariedad"style="width:50px;height:18px" onchange="guardarCantidadSelect(this.value)">
                                        <option';
                            if ($registroVariedadesLista["CANTIDAD"] == "") {
                                echo ' selected';
                            }
                            echo ' value="'.$registroVariedadesLista["BOTONID"].'_0">-</option>
                                    <option';
                            if ($registroVariedadesLista["CANTIDAD"] == $minimoPiezas) {
                                echo ' selected';                              
                            }
                            echo ' value="'.$registroVariedadesLista["BOTONID"].'_'.$minimoPiezas.'">'.$minimoPiezas.'</option>
                                        <option';
                            if ($registroVariedadesLista["CANTIDAD"] == ($minimoPiezas*2)) {
                                echo ' selected';
                            }
                            echo ' value="'.$registroVariedadesLista["BOTONID"].'_'.($minimoPiezas*2).'">'.($minimoPiezas*2).'</option>
                                        <option';
                            if ($registroVariedadesLista["CANTIDAD"] == ($minimoPiezas*3)) {
                                echo ' selected';
                            }
                            echo ' value="'.$registroVariedadesLista["BOTONID"].'_'.($minimoPiezas*3).'">'.($minimoPiezas*3).'</option>
                                    </select>
                                </td>
                                </tr>';
                        }
                        ?>                     


            </tr>
        </table>
    </td>	
<td  valign="top" width="70%">
    <table align="center">
        <tr valign="top">
            <?php
            while ($registroGrupoTablas = $resGrupoTablas->fetch_array(MYSQLI_BOTH)) {
                echo '<td><fieldset style="padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;border: 2px solid">
                    <legend align="center"><b>' . $registroGrupoTablas["NOMBRE"] . '</b></legend>
                    <table cellspacing="5px" width="10%" >';
                $variedadTablas = "select * from variedadtabla where grupotablaid =" . $registroGrupoTablas["ID"];
                $resVariedadTablas = $conexion->query($variedadTablas);
                while ($registroVariedadTablas = $resVariedadTablas->fetch_array(MYSQLI_BOTH)) {
                    echo '<tr valign="top">
                <td>
                  <input type="button" value="' . $registroVariedadTablas["NOMBRE"] . '" style="width:140px;height:30px';

                    $dibujarBtnVariedadTabla = "select * from BTN_VARIEDAD_TMP_SELECCION";
                    $resDibujarBtnVariedadTabla = $conexion->query($dibujarBtnVariedadTabla);
                    while ($registroDibujarBtnVariedadTabla = $resDibujarBtnVariedadTabla->fetch_array(MYSQLI_BOTH)) {
                        if ($registroDibujarBtnVariedadTabla["BOTONID"] == $registroVariedadTablas["ID"]) {
                            echo ';background-color: GRAY;color: WHITE;';
                        }
                    }
                    echo '" onClick="gurdarCantidades(' . $registroVariedadTablas["ID"] . ')">
         </td>
         </tr>';
                    echo '<td>';
                }
                echo '</tr>
          </table>
          </td>';
            }
            ?>
        </tr>
    </table>
</td>    
</tr>
</table>
<table width="100%" cellspacing="40">
    <tr align="center">
        <td>
            <input type="button" value="Atras" style="width:120px;height:40px" onClick="validarMinimoPiezas()">
        </td>
        <td>
            <input type="button" value="Borrar Todo" style="width:120px;height:40px" onClick="borrarSeleccion()" />
        </td>
    </tr>
</table>
</body>
</html>
