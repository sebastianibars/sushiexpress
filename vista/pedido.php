<html>
    <?php
    include ("../conexion/conexion.php");

    $conexion = crearConexion();
    $consultarRetiraPedidoGuardar="select * from BTN_RETIRO_PEDIDO";
    $resConsultarRetiraPedidoGuardar =  $conexion->query($consultarRetiraPedidoGuardar); 
    $cantFilas = $resConsultarRetiraPedidoGuardar->num_rows;
    if($cantFilas==0){
        $existePedidoRetiro = 0;
    }else{
        $existePedidoRetiro = 1;
    }
    
    $consultarRetiraPedido="select * from BTN_RETIRO_PEDIDO";
    $resRetiraPedido =  $conexion->query($consultarRetiraPedido); 
    $cantFilas = $resRetiraPedido->num_rows;
    if($cantFilas==0){
        $guardarRetiroPedido="insert into BTN_RETIRO_PEDIDO (BOTONID) values (2)"; 
        $conexion->query($guardarRetiroPedido); 
    }
    
    if (isset($_GET["accion"])) {
        if (($_GET["accion"]) == 'guardarPedidoFinal') {
            
            $tablas ="SELECT btn_cantidad_piezas_tmp.ID CANTIDADPIEZASIDRELACION, tabla.ID,tabla.CANTIDADPIEZAS,tabla.PRECIO FROM btn_cantidad_piezas_tmp
                      inner join tabla on tabla.id = btn_cantidad_piezas_tmp.BOTONID";
            $resTablas =  $conexion->query($tablas);   
    /////////////////////////////////////////////////////////////////////////////////////
            $delicias ="SELECT delicia.ID,delicia.NOMBRE,btn_delicia_tmp.CANTIDAD,btn_delicia_tmp.PRECIO FROM btn_delicia_tmp
                        inner join delicia on delicia.id = btn_delicia_tmp.BOTONID";
            $resDelicias =  $conexion->query($delicias);
    ////////////////////////////////////////////////////////////////////////////////////////////
            $bebidas ="SELECT bebida.ID,bebida.NOMBRE,btn_bebida_tmp.CANTIDAD,btn_bebida_tmp.PRECIO FROM btn_bebida_tmp
                       inner join bebida on bebida.id = btn_bebida_tmp.BOTONID";
            $resBebidas =  $conexion->query($bebidas);
    //////////////////////////////////////////////////////////////////////////////////////////
            $adicional="SELECT adicional.ID,adicional.NOMBRE,btn_adicional_tmp.CANTIDAD,btn_adicional_tmp.PRECIO FROM btn_adicional_tmp
                        inner join adicional on adicional.id = btn_adicional_tmp.BOTONID";
            $resAdicional =  $conexion->query($adicional);
    ///////////////////////////////////////////////////////////////////////////////////////////
            $pedidosYa="SELECT pedidosya.ID PEDIDOSYAID,pedidosya.NUMEROPROMOCION,pedidosya.PEDIDOPROMOCIONID,pedidopromocion.NOMBRE,btn_pedidosya_tmp.CANTIDAD,btn_pedidosya_tmp.PRECIO  FROM btn_pedidosya_tmp
                        inner join pedidosya on pedidosya.PEDIDOPROMOCIONID = btn_pedidosya_tmp.BOTONID
                        inner join pedidopromocion on pedidopromocion.ID = pedidosya.PEDIDOPROMOCIONID";
            $resPedidosYa =  $conexion->query($pedidosYa);
    /////////////////////////////////////////////////////////////////////////////////////////////////
            $promocion="SELECT promociones.ID PROMOCIONID,promociones.NUMEROPROMOCION,promociones.PEDIDOPROMOCIONID,pedidopromocion.NOMBRE,btn_promociones_tmp.CANTIDAD,btn_promociones_tmp.PRECIO  FROM btn_promociones_tmp
                        inner join promociones on promociones.PEDIDOPROMOCIONID = btn_promociones_tmp.BOTONID
                        inner join pedidopromocion on pedidopromocion.ID = promociones.PEDIDOPROMOCIONID";
            $resPromocion =  $conexion->query($promocion);  
    ///////////////////////////////////////////////////////////////////////////////////////////////// 
            $agrupate="SELECT agrupate.ID AGRUPATEID,agrupate.NUMEROPROMOCION,agrupate.PEDIDOPROMOCIONID,pedidopromocion.NOMBRE,btn_agrupate_tmp.CANTIDAD,btn_agrupate_tmp.PRECIO FROM btn_agrupate_tmp
                        inner join agrupate on agrupate.PEDIDOPROMOCIONID = btn_agrupate_tmp.BOTONID
                        inner join pedidopromocion on pedidopromocion.ID = agrupate.PEDIDOPROMOCIONID";
            $resAgrupate =  $conexion->query($agrupate);
    /////////////////////////////////////////////////////////////////////////////////////////////////
            $retiroPedido="SELECT BOTONID FROM btn_retiro_pedido";
            $resRetiroPedido =  $conexion->query($retiroPedido);
    /////////////////////////////////////////////////////////////////////////////////////////////////
            $agupateCodigo="SELECT CODIGO FROM AGRUPATE_CODIGO_TMP";
            $resAgupateCodigo =  $conexion->query($agupateCodigo);
            
            $consultaPrimerPedidoFinal = "SELECT ID FROM PEDIDOFINAL";
            $consultarPrimerPedidoFinal =  $conexion->query($consultaPrimerPedidoFinal); 
            $cantPedidoFinal = $consultarPrimerPedidoFinal->num_rows;

            if($cantPedidoFinal==0){
                $insertarPrimerPedidoFinal = "INSERT INTO PEDIDOFINAL (ID) VALUES (1)";
                $conexion->query($insertarPrimerPedidoFinal); 
            }else{
                $consultaPedidoFinal = "SELECT MAX(ID)+1 ID FROM PEDIDOFINAL";
                $consultarPedidoFinal =  $conexion->query($consultaPedidoFinal);
                while ($registroConsultarPedidoFinal = $consultarPedidoFinal->fetch_array(MYSQLI_BOTH)) {
                    $idPedidoFinal=$registroConsultarPedidoFinal["ID"];
                }
                $insertarPedidoFinal = "INSERT INTO PEDIDOFINAL (ID) VALUES ('.$idPedidoFinal.')";
                $conexion->query($insertarPedidoFinal); 
            }
            $consultaPedidoFinal = "SELECT MAX(ID) ID FROM PEDIDOFINAL";
            $consultarPedidoFinal =  $conexion->query($consultaPedidoFinal); 
            while ($registroConsultarPedidoFinal = $consultarPedidoFinal->fetch_array(MYSQLI_BOTH)) {
                $idPedidoFinal=$registroConsultarPedidoFinal["ID"];
            }
            
            $total=0;
            while ($registroTablas = $resTablas->fetch_array(MYSQLI_BOTH)) {
                $cantidadPiezasIdRelacion = $registroTablas["CANTIDADPIEZASIDRELACION"];
                $idCantidadPiezas=$registroTablas["ID"];
                $cantPiezas=$registroTablas["CANTIDADPIEZAS"];
                $precioTabla=$registroTablas["PRECIO"];
                $total=$total+$precioTabla;

                $consultaGuardar="insert into PEDIDOFINAL_CANTPIEZAS(CANTIDADPIEZASIDRELACION,PEDIDOFINALID,CANTIDADPIEZASID,CANTIDADPIEZAS,PRECIO) 
                                  values ($cantidadPiezasIdRelacion,$idPedidoFinal,$idCantidadPiezas,$cantPiezas,$precioTabla)";
                $conexion->query($consultaGuardar); 

                $variedadTabla ="SELECT variedadtabla.ID,variedadtabla.NOMBRE,variedadtabla.GRUPOTABLAID,btn_variedad_tmp.CANTIDAD, btn_variedad_tmp.TABLAID FROM btn_variedad_tmp
                                inner join variedadtabla on variedadtabla.ID = btn_variedad_tmp.BOTONID
                                where TABLAID = ".$idCantidadPiezas." AND btn_variedad_tmp.ID = ".$cantidadPiezasIdRelacion;
                $resVariedadTabla = $conexion->query($variedadTabla);
                
                while ($registroVariedadTabla = $resVariedadTabla->fetch_array(MYSQLI_BOTH)) {
                    $nombreVariedad= $registroVariedadTabla["NOMBRE"];
                    $cantidadVariedad=$registroVariedadTabla["CANTIDAD"];
                    $idVariedad =$registroVariedadTabla["ID"];
                    $grupoTablaIdVariedad=$registroVariedadTabla["GRUPOTABLAID"];
                    $tablaIdVariedad=$registroVariedadTabla["TABLAID"];

                    $consultaGuardar="insert into PEDIDOFINAL_VARIEDAD (CANTIDADPIEZASIDRELACION,PEDIDOFINALID,VARIEDADID,NOMBREVARIEDAD,GRUPOTABLA,CANTIDAD,TABLAID)
                                      values ($cantidadPiezasIdRelacion,$idPedidoFinal,$idVariedad,'$nombreVariedad',$grupoTablaIdVariedad,$cantidadVariedad,$tablaIdVariedad)";
                    $conexion->query($consultaGuardar); 
                }
            }
            while ($registroDelicias = $resDelicias->fetch_array(MYSQLI_BOTH)) {
                $idDelicia=$registroDelicias["ID"];
                $deliciaNombre=$registroDelicias["NOMBRE"];
                $deliciaCantidad=$registroDelicias["CANTIDAD"];
                $deliciaPrecio=$registroDelicias["PRECIO"];
                $total=$total+$deliciaPrecio;

                $consultaGuardar="insert into PEDIDOFINAL_DELICIA (PEDIDOFINALID,DELICIAID,NOMBREDELICIA,PRECIO,CANTIDAD)
                                  values ($idPedidoFinal,$idDelicia,'$deliciaNombre',$deliciaPrecio,$deliciaCantidad)";
                $conexion->query($consultaGuardar); 
            }
            while ($registroBebidas = $resBebidas->fetch_array(MYSQLI_BOTH)) {
                $idBebida=$registroBebidas["ID"];
                $nombreBebidas=$registroBebidas["NOMBRE"];
                $cantidadBebidas=$registroBebidas["CANTIDAD"];
                $precioBebidas=$registroBebidas["PRECIO"];
                $total=$total+$precioBebidas;

                $consultaGuardar="insert into PEDIDOFINAL_BEBIDA (PEDIDOFINALID,BEBIDAID,NOMBREBEBIDA,PRECIO,CANTIDAD)
                                  values ($idPedidoFinal,$idBebida,'$nombreBebidas',$precioBebidas,$cantidadBebidas)";
                $conexion->query($consultaGuardar); 
            } 
            while ($registroAdicional = $resAdicional->fetch_array(MYSQLI_BOTH)) {
                $idAdicional=$registroAdicional["ID"];
                $nombreAdicional=$registroAdicional["NOMBRE"];
                $cantidadAdicional=$registroAdicional["CANTIDAD"];
                $precioAdicional=$registroAdicional["PRECIO"];
                $total=$total+$precioAdicional;

                $consultaGuardar="insert into PEDIDOFINAL_ADICIONAL (PEDIDOFINALID,ADICIONALID,NOMBREADICIONAL,PRECIO,CANTIDAD)
                                  values ($idPedidoFinal,$idAdicional,'$nombreAdicional',$precioAdicional,$cantidadAdicional)";
                $conexion->query($consultaGuardar);       
            } 
            while ($registroPedidosYa = $resPedidosYa->fetch_array(MYSQLI_BOTH)) {
                $idPedidosYa=$registroPedidosYa["PEDIDOSYAID"];
                $numeroPromocionPedidosYa=$registroPedidosYa["NUMEROPROMOCION"];
                $pedidosPromocionIdPedidosYa=$registroPedidosYa["PEDIDOPROMOCIONID"];
                $nombrePromocionPedidosYa=$registroPedidosYa["NOMBRE"];
                $cantidadPromocionPedidosYa=$registroPedidosYa["CANTIDAD"];
                $precioPromocionPedidosYa=$registroPedidosYa["PRECIO"];
                $total=$total+$precioPromocionPedidosYa;

                $consultaGuardar="insert into PEDIDOFINAL_PEDIDOSYA (PEDIDOFINALID,NUMEROPROMOCION,PEDIDOPROMOCIONID,NOMBREPROMOCION,PRECIO,CANTIDAD)
                                  values ($idPedidoFinal,$numeroPromocionPedidosYa,$pedidosPromocionIdPedidosYa,'$nombrePromocionPedidosYa',$precioPromocionPedidosYa,$cantidadPromocionPedidosYa)";
                $conexion->query($consultaGuardar); 
            } 
            while ($registroAgrupate = $resAgrupate->fetch_array(MYSQLI_BOTH)) {
                $idAgrupate=$registroAgrupate["AGRUPATEID"];
                $numeroPromocionAgrupate=$registroAgrupate["NUMEROPROMOCION"];
                $pedidosPromocionIdAgrupate=$registroAgrupate["PEDIDOPROMOCIONID"];
                $nombrePromocionAgrupate=$registroAgrupate["NOMBRE"];
                $cantidadPromocionAgrupate=$registroAgrupate["CANTIDAD"];
                $precioPromocionAgrupate=$registroAgrupate["PRECIO"];
                $total=$total+$precioPromocionAgrupate;

                $consultaGuardar="insert into PEDIDOFINAL_AGRUPATE (PEDIDOFINALID,NUMEROPROMOCION,PEDIDOPROMOCIONID,NOMBREPROMOCION,PRECIO,CANTIDAD)
                                  values ($idPedidoFinal,$numeroPromocionAgrupate,$pedidosPromocionIdAgrupate,'$nombrePromocionAgrupate',$precioPromocionAgrupate,$cantidadPromocionAgrupate)";
                $conexion->query($consultaGuardar); 
            } 
            while ($registroPromocion = $resPromocion->fetch_array(MYSQLI_BOTH)) {
                $idPromocion=$registroPromocion["PROMOCIONID"];
                $numeroPromocionPromocion=$registroPromocion["NUMEROPROMOCION"];
                $pedidosPromocionIdPromocion=$registroPromocion["PEDIDOPROMOCIONID"];
                $nombrePromocionPedido=$registroPromocion["NOMBRE"];
                $cantidadPromocion=$registroPromocion["CANTIDAD"];
                $precioPromocion=$registroPromocion["PRECIO"];
                $total=$total+$precioPromocion;

                $consultaGuardar="insert into PEDIDOFINAL_PROMOCION (PEDIDOFINALID,NUMEROPROMOCION,PEDIDOPROMOCIONID,NOMBREPROMOCION,PRECIO,CANTIDAD)
                                  values ($idPedidoFinal,$numeroPromocionPromocion,$pedidosPromocionIdPromocion,'$nombrePromocionPedido',$precioPromocion,$cantidadPromocion)";
                $conexion->query($consultaGuardar); 
            } 
             while ($registroRetiroPedido = $resRetiroPedido->fetch_array(MYSQLI_BOTH)) {
                $retiroPedido=$registroRetiroPedido["BOTONID"];
                $consultaGuardar="update pedidofinal set RETIRO = $retiroPedido where id = $idPedidoFinal";
                $conexion->query($consultaGuardar); 
            } 
            
            $consultaDemora="SELECT DEMORAPEDIDO FROM CONFIGURACIONTIEMPOS";
            $resConsultaDemora =  $conexion->query($consultaDemora); 
            while ($registroConsultaDemora = $resConsultaDemora->fetch_array(MYSQLI_BOTH)) {
                $tiempoDemora=$registroConsultaDemora["DEMORAPEDIDO"];
            }
            
            while ($registroAgupateCodigo = $resAgupateCodigo->fetch_array(MYSQLI_BOTH)) {
                $codigoAgrupate=$registroAgupateCodigo["CODIGO"];
                
                $consultaGuardar="insert into AGRUPATE_CODIGO (PEDIDOFINALID,CODIGO) values ($idPedidoFinal,$codigoAgrupate)";
                $conexion->query($consultaGuardar); 
            }        

            $diaEntrega=$_GET["dia"];
            $mesEntrega=$_GET["mes"];
            $añoEntrega=$_GET["año"];
            $horaEntrega=$_GET["hora"];
            $minutosEntrega=$_GET["minutos"];
            $totalDescuento=$_GET["total"];

            $completarPedidoFinal="update pedidofinal set FECHAPEDIDO=NOW(),FECHAENTREGA=STR_TO_DATE('$añoEntrega-$mesEntrega-$diaEntrega $horaEntrega:$minutosEntrega:00','%Y-%m-%d %k:%i:00'),PRECIOSUBTOTAL=$total, PRECIOTOTAL=$totalDescuento where id=$idPedidoFinal";
            $conexion->query($completarPedidoFinal); 
            
            $borrarTablas="delete from pedidofinal_usuario_1";
            $conexion->query($borrarTablas); 
            
            $consultaGuardar="insert into PEDIDOFINAL_USUARIO_1 (PEDIDOFINALID) values ($idPedidoFinal)";
            $conexion->query($consultaGuardar); 
                                   
            $borrarTablas="delete from btn_cantidad_piezas_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_delicia_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_bebida_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_adicional_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_pedidosya_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_promociones_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_variedad_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_agrupate_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_retiro_pedido";
            $conexion->query($borrarTablas);
            $borrarTablas="delete from tmp_fecha_entrega";
            $conexion->query($borrarTablas);
            $borrarTablas="delete from agrupate_codigo_tmp";
            $conexion->query($borrarTablas);
            
           
            if($retiroPedido==1){
                Header("Location: finalPedidoRetira.php");
            }else if($retiroPedido==2){
                Header("Location: finalPedidoDelivery.php");        
            }else if($retiroPedido==3){
                Header("Location: finalPedidoEspera.php");
            }

        }
        if (($_GET["accion"]) == 'guardarRetiroPedido') {
            if (isset($_GET["botonId"])) {
                $botonId=$_GET["botonId"];
                $consultarRetiraPedido="select * from BTN_RETIRO_PEDIDO where BOTONID=$botonId";
                $resRetiraPedido =  $conexion->query($consultarRetiraPedido); 
                $cantFilas = $resRetiraPedido->num_rows;
                if($cantFilas==0){
                    $borrarRetiroPedido="delete from BTN_RETIRO_PEDIDO";
                    $conexion->query($borrarRetiroPedido); 
                    $guardarRetiroPedido="insert into BTN_RETIRO_PEDIDO (BOTONID) values ($botonId)"; 
                    $conexion->query($guardarRetiroPedido); 
                    Header("Location: pedido.php"); 
                }
          
            }
            
        }
         if (($_GET["accion"]) == 'eliminarTabla') {
            $id = ($_GET["id"]);
            $borrarTablas="delete from btn_cantidad_piezas_tmp where id = $id";
            $conexion->query($borrarTablas);
            $borrarTablas="delete from btn_variedad_tmp where id = $id";
            $conexion->query($borrarTablas); 
            Header("Location: pedido.php"); 
         }         
         if (($_GET["accion"]) == 'eliminarDelicia') {
            $id = ($_GET["id"]);
            $borrarTablas="delete from btn_delicia_tmp where botonid = $id";
            $conexion->query($borrarTablas);
            Header("Location: pedido.php"); 
         }
         if (($_GET["accion"]) == 'eliminarBebida') {
            $id = ($_GET["id"]);
            $borrarTablas="delete from btn_bebida_tmp where botonid = $id";
            $conexion->query($borrarTablas);
            Header("Location: pedido.php"); 
         }
         if (($_GET["accion"]) == 'eliminarAdicional') {
            $id = ($_GET["id"]);
            $borrarTablas="delete from btn_adicional_tmp where botonid = $id";
            $conexion->query($borrarTablas);
            Header("Location: pedido.php"); 
         }
         if (($_GET["accion"]) == 'eliminarPedidosYa') {
            $id = ($_GET["id"]);
            $borrarTablas="delete from btn_pedidosya_tmp where botonid = $id";
            $conexion->query($borrarTablas);
            Header("Location: pedido.php"); 
         }
         if (($_GET["accion"]) == 'eliminarAgrupate') {
            $id = ($_GET["id"]);
            $borrarTablas="delete from btn_agrupate_tmp where botonid = $id";
            $conexion->query($borrarTablas);
            $borrarTablas="delete from AGRUPATE_CODIGO_TMP";
            $conexion->query($borrarTablas);            
            
            Header("Location: pedido.php"); 
         }
         if (($_GET["accion"]) == 'eliminarPromocion') {
            $id = ($_GET["id"]);
            $borrarTablas="delete from btn_promociones_tmp where botonid = $id";
            $conexion->query($borrarTablas);
            Header("Location: pedido.php"); 
         }
         if (($_GET["accion"]) == 'eliminarPedido') {
            $borrarTablas="delete from btn_cantidad_piezas_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_delicia_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_bebida_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_adicional_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_pedidosya_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_promociones_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_variedad_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from btn_agrupate_tmp";
            $conexion->query($borrarTablas); 
            $borrarTablas="delete from agrupate_codigo_tmp";
            $conexion->query($borrarTablas);
            Header("Location: pedido.php"); 
         }
                  
    }else{
        
        $consultaBtnCantSeleccion="SELECT BOTONID FROM BTN_CANTIDAD_PIEZAS_TMP_SELECCION";
        $resConsultaBtnCantSeleccion =  $conexion->query($consultaBtnCantSeleccion);         
        while ($registroConsultaBtnCantSeleccion = $resConsultaBtnCantSeleccion->fetch_array(MYSQLI_BOTH)) {
             $botonIdCantGuardar=$registroConsultaBtnCantSeleccion["BOTONID"];
             
            $cantBtnCantPiezas ="SELECT IF(MAX(ID) is null,1,MAX(ID)+1)ID FROM BTN_CANTIDAD_PIEZAS_TMP";
            $resCantBtnCantPiezas =  $conexion->query($cantBtnCantPiezas);         
            while ($registroCantBtnCantPiezas = $resCantBtnCantPiezas->fetch_array(MYSQLI_BOTH)) {
                $btnCantId=$registroCantBtnCantPiezas["ID"];
            }
            
            $guardarBtnCantidad="insert into BTN_CANTIDAD_PIEZAS_TMP (ID,BOTONID) values ($btnCantId,$botonIdCantGuardar)";
            $conexion->query($guardarBtnCantidad);
         }
        
        $consultaBtnSeleccion="SELECT BOTONID,CANTIDAD,TABLAID FROM BTN_VARIEDAD_TMP_SELECCION";
        $resConsultaBtnSeleccion =  $conexion->query($consultaBtnSeleccion); 
        while ($registroConsultaBtnSeleccion = $resConsultaBtnSeleccion->fetch_array(MYSQLI_BOTH)) {
            $botonIdGuardar=$registroConsultaBtnSeleccion["BOTONID"];
            $cantidadGuardar=$registroConsultaBtnSeleccion["CANTIDAD"];
            $tablaIdGuardar=$registroConsultaBtnSeleccion["TABLAID"];
            
            $guardarVariedad="insert into btn_variedad_tmp (ID,BOTONID,CANTIDAD,TABLAID) 
                               values ($btnCantId,$botonIdGuardar,$cantidadGuardar,$tablaIdGuardar)";
            $conexion->query($guardarVariedad);
             
        }     
        
        $borrarTablaTemporal="delete from BTN_VARIEDAD_TMP_SELECCION";
        $conexion->query($borrarTablaTemporal);
        $borrarTablaTemporal="delete from BTN_CANTIDAD_PIEZAS_TMP_SELECCION";
        $conexion->query($borrarTablaTemporal);
        
        
        $tablas ="SELECT btn_cantidad_piezas_tmp.ID BTNCANTIDADID,tabla.ID,tabla.CANTIDADPIEZAS,tabla.PRECIO FROM btn_cantidad_piezas_tmp
                  inner join tabla on tabla.id = btn_cantidad_piezas_tmp.BOTONID";
        $resTablas =  $conexion->query($tablas);    
        /////////////////////////////////////////////////////////////////////////////////////
        $delicias ="SELECT btn_delicia_tmp.BOTONID,delicia.NOMBRE,btn_delicia_tmp.CANTIDAD,btn_delicia_tmp.PRECIO FROM btn_delicia_tmp
                    inner join delicia on delicia.id = btn_delicia_tmp.BOTONID";
        $resDelicias =  $conexion->query($delicias);
        ////////////////////////////////////////////////////////////////////////////////////////////
        $bebidas ="SELECT btn_bebida_tmp.BOTONID,bebida.NOMBRE,btn_bebida_tmp.CANTIDAD,btn_bebida_tmp.PRECIO FROM btn_bebida_tmp
                   inner join bebida on bebida.id = btn_bebida_tmp.BOTONID";
        $resBebidas =  $conexion->query($bebidas);
        //////////////////////////////////////////////////////////////////////////////////////////
        $adicional="SELECT btn_adicional_tmp.BOTONID,adicional.NOMBRE,btn_adicional_tmp.CANTIDAD,btn_adicional_tmp.PRECIO FROM btn_adicional_tmp
                    inner join adicional on adicional.id = btn_adicional_tmp.BOTONID";
        $resAdicional =  $conexion->query($adicional);
        ///////////////////////////////////////////////////////////////////////////////////////////
        $pedidosYa="SELECT btn_pedidosya_tmp.BOTONID,pedidosya.NUMEROPROMOCION,btn_pedidosya_tmp.CANTIDAD,btn_pedidosya_tmp.PRECIO  FROM btn_pedidosya_tmp
                    inner join pedidosya on pedidosya.PEDIDOPROMOCIONID = btn_pedidosya_tmp.BOTONID";
        $resPedidosYa =  $conexion->query($pedidosYa);
        /////////////////////////////////////////////////////////////////////////////////////////////////
        $promocion="SELECT btn_promociones_tmp.BOTONID,promociones.NUMEROPROMOCION, btn_promociones_tmp.CANTIDAD, btn_promociones_tmp.PRECIO FROM btn_promociones_tmp
                    inner join promociones on promociones.PEDIDOPROMOCIONID = btn_promociones_tmp.BOTONID";
        $resPromocion =  $conexion->query($promocion); 
        /////////////////////////////////////////////////////////////////////////////////////////////////
        $agrupate="SELECT btn_agrupate_tmp.BOTONID,agrupate.NUMEROPROMOCION,btn_agrupate_tmp.CANTIDAD,btn_agrupate_tmp.PRECIO  FROM btn_agrupate_tmp
                    inner join agrupate on agrupate.PEDIDOPROMOCIONID = btn_agrupate_tmp.BOTONID";
        $resAgrupate =  $conexion->query($agrupate);
        /////////////////////////////////////////////////////////////////////////////////////////////////
        $btnReiraLocal="SELECT BOTONID FROM BTN_RETIRO_PEDIDO";
        $resBtnReiraLocal =  $conexion->query($btnReiraLocal); 
        /////////////////////////////////////////////////////////////////////////////////////////////////
        $consultarFechaActual="select * from TMP_FECHA_ENTREGA";
        $resConsultarFechaActual =  $conexion->query($consultarFechaActual); 
        $cantFilas = $resConsultarFechaActual->num_rows;
        if($cantFilas==0){
            $guardarFechaActual="insert into TMP_FECHA_ENTREGA (FECHAENTREGA) values (NOW())";
            $conexion->query($guardarFechaActual);     
        }
                
        $consultaTiempoDemora="SELECT DEMORAPEDIDO FROM CONFIGURACIONTIEMPOS";
        $resTiempoDemora =  $conexion->query($consultaTiempoDemora); 
        while ($registroTiempoDemora = $resTiempoDemora->fetch_array(MYSQLI_BOTH)) {
            $tiempoDemora=$registroTiempoDemora["DEMORAPEDIDO"];
        }

        $tiempoDemoraHora=0;
        $tiempoDemoraMinuto=0;
        while($tiempoDemora>0){
            if($tiempoDemora>=60){
                $tiempoDemoraHora=$tiempoDemoraHora+1;
                $tiempoDemora=$tiempoDemora-60;
            }
            if($tiempoDemora<60){
                $tiempoDemoraMinuto=$tiempoDemora;
                $tiempoDemora=0;
            }   
        }

        $consultaFechaEntrega ="SELECT ADDTIME(FECHAENTREGA, '$tiempoDemoraHora:$tiempoDemoraMinuto:00') FECHAENTREGA FROM TMP_FECHA_ENTREGA";
        $resFechaEntrega =  $conexion->query($consultaFechaEntrega); 
        while ($registroFechaEntrega = $resFechaEntrega->fetch_array(MYSQLI_BOTH)) {
            $fechaEntrega=$registroFechaEntrega["FECHAENTREGA"];
        }          
    }

    ?>

    <div align="center"><h1>Preparar pedidos</h1></div>
    <script>
              
    function updateTime() {
         var fecha = new Date();
         var dia = fecha.getDate();
         var mes = fecha.getMonth()+1;
         var año = fecha.getFullYear();
         var horas = fecha.getHours();
         var minutos = fecha.getMinutes();
         if(minutos<10){
             minutos='0'+minutos;
         }
         if(horas<10){
             horas='0'+horas;
         }
         if(mes<10){
             mes='0'+mes;
         }
         if(dia<10){
             dia='0'+dia;
         }
         document.getElementById('contenedorDia').innerHTML = '' + dia+'/'+mes+'/'+año;
         document.getElementById('contenedorHora').innerHTML = ''+horas + ':' + minutos;
         setTimeout('updateTime()', 1000);
     }

     function retiraPedido(retiraId){
         window.location.href = "pedido.php?accion=guardarRetiroPedido&botonId=" + retiraId;
     }

     function guardarPedidoFinal(){
         var existePedidoRetiro = <?php echo $existePedidoRetiro; ?>;
         if(existePedidoRetiro == 1){
             var diaEntrega=document.getElementById("diaEntrega").value;
             var mesEntrega=document.getElementById("mesEntrega").value;
             var añoEntrega=document.getElementById("añoEntrega").value;
             var horaEntrega=document.getElementById("horaEntrega").value;
             var minutoEntrega=document.getElementById("minutoEntrega").value;
             var descuento = '';
             if(document.getElementById("valueDescuento") != null){
                 descuento = document.getElementById("valueDescuento").value;
             }
             var tipoDescuento = document.getElementById("tipoDescuentoId").value;
             var subTotal = document.getElementById("totalId").value;
             var total;
             
             if(descuento != ''){
                if(tipoDescuento ==1){
                    total = parseFloat(subTotal) - parseFloat(descuento);
                    if(total < 0){
                        total=0;
                    }            
                }
        
                if(tipoDescuento==2){
                    if(descuento<10){
                        descuento = '0.0'+descuento;   
                    }else if(numero < 100){
                        descuento = '0.'+descuento;      
                    }else{
                        descuento = '1';
                    }
                    total = parseFloat(subTotal) - (parseFloat(subTotal)*parseFloat(descuento));
                }
            }else{
                total = subTotal;
            }
    
            window.location.href = "pedido.php?accion=guardarPedidoFinal&dia="+diaEntrega+"&mes="+mesEntrega+"&año="+añoEntrega+"&hora="+horaEntrega+"&minutos="+minutoEntrega+"&total="+total;
         }else{
            var mimodal = crearModalTexto("DEBE SELECCIONAR TIPO DE RETIRO DEL PEDIDO");
            mostrarModal(mimodal);
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
    
    function eliminarTabla(tablaId){
        window.location.href = "pedido.php?accion=eliminarTabla&id=" + tablaId;
    }
    
    function eliminarDelicia(deliciaId){
        window.location.href = "pedido.php?accion=eliminarDelicia&id=" + deliciaId;
    }
    
    function eliminarBebida(bebidaId){
        window.location.href = "pedido.php?accion=eliminarBebida&id=" + bebidaId;
    }

    function eliminarAdicional(adicionalId){
        window.location.href = "pedido.php?accion=eliminarAdicional&id=" + adicionalId;
    }
    
    function eliminarPedidosYa(pedidosYaId){
        window.location.href = "pedido.php?accion=eliminarPedidosYa&id=" + pedidosYaId;
    }
    
    function eliminarAgrupate(agrupateId){
        window.location.href = "pedido.php?accion=eliminarAgrupate&id=" + agrupateId;
    }
    
    function eliminarPromocion(promocionId){
        window.location.href = "pedido.php?accion=eliminarPromocion&id=" + promocionId;
    }
    
    function borrarAtras(){
        window.location.href = "inicio.php?accion=borrarDatos";
    }
    
    function eliminarPedido(){
        window.location.href = "pedido.php?accion=eliminarPedido";
    }
    
    function cargarTotal(idtotal){
        var idtotalSelect = idtotal.split("_");
        var id = idtotalSelect[0];
        var total = idtotalSelect[1];
        var div = '';
        if(id==2 || id==3){
            var simbolo ='$';
            var tipoDescuento='1';
            if(id==2){
                simbolo='%';
                tipoDescuento='2';
            }
            div = '<input type="hidden" id="totalId" value="'+total+'"/><input type="hidden" id="tipoDescuentoId" value="'+id+'"/><table align="center" valign="center"><tr ><td align="right"><b>subtotal:</b></td><td align="center"><b>$'+total+'</b></td></tr><tr><td align="right"><b>-</b></td><td align="center" style="border-bottom: 2px solid;width:100px;"><b>'+simbolo+'</b><input type="text" size="2px" onkeyup="calcularDescuento('+tipoDescuento+','+total+')" id="valueDescuento"></td></tr><tr style="font-size: 23px;"><td align="right"><b>Total:</b></td><td align="center"><div id="divSumaTotal"><b>$'+total+'</b></div></td></tr></table>';
        }else if(id==1){
            div = '<input type="hidden" id="totalId" value="'+total+'"/><input type="hidden" id="tipoDescuentoId" value="'+id+'"/><table align="center" valign="center"><tr style="font-size: 23px;"><td align="right"><b>Total:</b></td><td align="center"><b>$'+total+'</b></td></tr></table>';
        }
       
	document.getElementById("divTotal").innerHTML  = div;
    }
    
    function calcularDescuento(tipoDescuento,subTotal){
        var numero = '';
        var total;
        if(document.getElementById("valueDescuento").value != ""){
            numero = document.getElementById("valueDescuento").value;    
        }
        
        if(numero != ''){
            if(tipoDescuento ==1){
                total = parseFloat(subTotal) - parseFloat(numero);
                if(total < 0){
                    total=0;
                }            
            }
        
            if(tipoDescuento==2){
                if(numero<10){
                    numero = '0.0'+numero;   
                }else if(numero < 100){
                    numero = '0.'+numero;      
                }else{
                    numero = '1';
                }
                total = parseFloat(subTotal) - (parseFloat(subTotal)*parseFloat(numero));
            }
        }else{
            total = subTotal;
        }
        var div = '<b>$'+total+'</b>';
        document.getElementById("divSumaTotal").innerHTML  = div;
    }
    </script>
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
    <body onload="javascript:updateTime()" style="background-color:lightblue;">
        <table width="90%" align="center" height="100px" >
            <tr valign="top">
                <td align="center" width=33%">
                    <table>
                        <tr align="center">
                            <td style=font-size:18>
                                <b>FECHA</b>
                            </td>
                        </tr>
                        <tr align="center">
                            <td>
                                <b><div id="contenedorDia" style=font-size:18></div></b>
                            </td>
                        </tr>
                       <tr align="center">
                            <td>
                                <b><div id="contenedorHora" style=font-size:18></div></b>
                            </td>
                        </tr>
                    </table>
                </td>
                <td align="center" width="20%">
                               
                </td>
                <td align="center" width="33%">
                    <table>
                        <tr align="center">
                            <td style=font-size:18>
                                <b>ENTREGA</b>
                            </td>
                        </tr>
                        <tr align="center">
                            <td>
                                <?php
                                $diaEntrega= date_format(date_create($fechaEntrega), 'd');
                                $mesEntrega= date_format(date_create($fechaEntrega), 'm');
                                $añoEntrega= date_format(date_create($fechaEntrega), 'Y');
                                $horaEntrega=date_format(date_create($fechaEntrega), 'H');
                                $minutosEntrega=date_format(date_create($fechaEntrega), 'i');
                                echo '<input type="text" id="diaEntrega" value="'.$diaEntrega.'" size="1px"><b>/</b> 
                                      <input type="text" id="mesEntrega" value="'.$mesEntrega.'" size="1px"><b>/</b>
                                      <input type="text" id="añoEntrega" value="'.$añoEntrega.'" size="1px"> 
                                    </td>
                                </tr>
                                <tr align="center">
                                    <td>
                                        <input type="text" id="horaEntrega" value="'.$horaEntrega.'" size="1px"><b>:</b>
                                        <input type="text" id="minutoEntrega" value="'.$minutosEntrega.'" size="1px"> 
                                    </td>';
                                ?>
                        </tr>
                    </table>
                </td>        
            </tr>
        </table>	
        <table>
            <tr>
                <td width="10%" valign="top">
                    <table cellspacing="10px">
                        <tr>
                            <td>
                                <input type="button" value="Tablas" style="width:120px;height:40px" onClick=" window.location.href = 'tablas.php'">
                            </td>
                        </tr>		
                        <tr>
                            <td>
                                <input type="button" value="Delicias" style="width:120px;height:40px" onClick=" window.location.href = 'delicias.php'">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="button" value="Bebidas" style="width:120px;height:40px" onClick=" window.location.href = 'bebidas.php'">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="button" value="Adicionales" style="width:120px;height:40px" onClick=" window.location.href = 'adicionales.php'">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="button" value="Agrupate" style="width:120px;height:40px" onClick=" window.location.href = 'agrupate.php'">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="button" value="Pedidos ya" style="width:120px;height:40px" onClick=" window.location.href = 'pedidosYa.php'">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="button" value="Promociones" style="width:120px;height:40px" onClick=" window.location.href = 'promociones.php'">
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="75%" valign="top" >
                    <table cellspacing="0" width="100%" border="2" style="background-color:rgb(255,255,255);">
                        <tr >
                            <td align="center">
                                <b>Pedido</b>
                            </td>
                            <td align="center">
                                <b>Variedad</b>
                            </td>
                            <td align="center">
                                <b>Cantidad</b>
                            </td>
                            <td align="center">
                                <b>Precio</b>
                            </td>
                            <td>
                                
                            </td>
                        </tr>
                    <?php
                    $total=0;
                        while ($registroTablas = $resTablas->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr align="center"><td>'.$registroTablas["CANTIDADPIEZAS"].' PIEZAS</td>';  
                            $variedadTabla ="SELECT btn_variedad_tmp.ID,variedadtabla.NOMBRE,btn_variedad_tmp.CANTIDAD 
                                            FROM btn_variedad_tmp
                                            inner join variedadtabla on variedadtabla.ID = btn_variedad_tmp.BOTONID
                                            where btn_variedad_tmp.ID =".$registroTablas["BTNCANTIDADID"];
                            $resVariedadTabla = $conexion->query($variedadTabla);
                            echo '</td><td>';
                            $i=0;
                            while ($registroVariedadTabla = $resVariedadTabla->fetch_array(MYSQLI_BOTH)) {
                                $idTablaEliminar = $registroVariedadTabla["ID"];
                                $i++;
                                if($i!=1){
                                    echo '<br>';
                                }
                                echo $registroVariedadTabla["NOMBRE"];
                            }
                            echo '</td><td>';
                            $j=0;
                            $resVariedadTabla = $conexion->query($variedadTabla);
                            while ($registroVariedadTabla = $resVariedadTabla->fetch_array(MYSQLI_BOTH)) {
                                $j++;
                                if($j!=1){
                                    echo '<br>';
                                }
                                if ($registroVariedadTabla["CANTIDAD"]==0){
                                    echo '-';
                                }else{
                                    echo $registroVariedadTabla["CANTIDAD"];
                                }
                            }
                            echo '</td><td>$'.$registroTablas["PRECIO"].'</td>';
                            echo '<td align="center" style="width:60px;">';
                                echo '<input type="button" value="Eiminar" style="width:60px;height:25px;" onClick="eliminarTabla('.$idTablaEliminar.')">';
                            echo '</td></tr>'; 
                            $total=$total+$registroTablas["PRECIO"];
                        }
                        while ($registroDelicias = $resDelicias->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr align="center"><td>'.$registroDelicias["NOMBRE"].'</td>
                                    <td>-</td>
                                    <td>'.$registroDelicias["CANTIDAD"].'</td>
                                    <td>$'.$registroDelicias["PRECIO"].'</td>
                                    <td align="center" style="width:60px;">
                                       <input type="button" value="Eiminar" style="width:60px;height:25px;" onClick="eliminarDelicia('.$registroDelicias["BOTONID"].')">
                                    </td>
                                 </tr>';
                                $total=$total+$registroDelicias["PRECIO"];
                        }
                        while ($registroBebidas = $resBebidas->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr align="center"><td>'.$registroBebidas["NOMBRE"].'</td>
                                 <td>-</td>
                                 <td>'.$registroBebidas["CANTIDAD"].'</td>
                                 <td>$'.$registroBebidas["PRECIO"].'</td>
                                 <td align="center" style="width:60px;">
                                    <input type="button" value="Eiminar" style="width:60px;height:25px;" onClick="eliminarBebida('.$registroBebidas["BOTONID"].')">
                                 </td>
                                 </tr>';
                                $total=$total+$registroBebidas["PRECIO"];
                        } 
                        while ($registroAdicional = $resAdicional->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr align="center"><td>'.$registroAdicional["NOMBRE"].'</td>
                                 <td>-</td>
                                 <td>'.$registroAdicional["CANTIDAD"].'</td>
                                 <td>$'.$registroAdicional["PRECIO"].'</td>
                                 <td align="center" style="width:60px;">
                                    <input type="button" value="Eiminar" style="width:60px;height:25px;" onClick="eliminarAdicional('.$registroAdicional["BOTONID"].')">
                                 </td>
                                 </tr>';
                                $total=$total+$registroAdicional["PRECIO"];
                        } 
                        while ($registroPedidosYa = $resPedidosYa->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr align="center"><td>PEDIDOS YA - Promoción N°'.$registroPedidosYa["NUMEROPROMOCION"].'</td>
                                 <td>-</td>
                                 <td>'.$registroPedidosYa["CANTIDAD"].'</td>
                                 <td>$'.$registroPedidosYa["PRECIO"].'</td>
                                 <td align="center" style="width:60px;">
                                    <input type="button" value="Eiminar" style="width:60px;height:25px;" onClick="eliminarPedidosYa('.$registroPedidosYa["BOTONID"].')">
                                 </td>
                                 </tr>';    
                                $total=$total+$registroPedidosYa["PRECIO"];
                        } 
                        while ($registroAgrupate = $resAgrupate->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr align="center"><td>AGRUPATE - Promoción N°'.$registroAgrupate["NUMEROPROMOCION"].'</td>
                                 <td>-</td>
                                 <td>'.$registroAgrupate["CANTIDAD"].'</td>
                                 <td>$'.$registroAgrupate["PRECIO"].'</td>
                                 <td align="center" style="width:60px;">
                                    <input type="button" value="Eiminar" style="width:60px;height:25px;" onClick="eliminarAgrupate('.$registroAgrupate["BOTONID"].')">
                                 </td>
                                 </tr>';     
                                $total=$total+$registroAgrupate["PRECIO"];
                        }
                        while ($registroPromocion = $resPromocion->fetch_array(MYSQLI_BOTH)) {
                            echo '<tr align="center"><td>PROMOCIONES - Promoción N°'.$registroPromocion["NUMEROPROMOCION"].'</td>
                                 <td>-</td>
                                 <td>'.$registroPromocion["CANTIDAD"].'</td>
                                 <td>$'.$registroPromocion["PRECIO"].'</td>
                                 <td align="center" style="width:60px;">
                                    <input type="button" value="Eiminar" style="width:60px;height:25px;" onClick="eliminarPromocion('.$registroPromocion["BOTONID"].')">
                                 </td>
                                 </tr>';     
                                $total=$total+$registroPromocion["PRECIO"];
                        } 
                        echo '</table>
                            <table width="66%">
                                <tr>
                                <td align="center" >
                            <select onchange="cargarTotal(this.value)">
                                <option value="1_'.$total.'">Sin descuento</option>
                                <option value="2_'.$total.'">Descuento porcentaje</option>
                                <option value="3_'.$total.'">Descuento valor</option>
                            </select>
                            </td>
                            <td align="center">
                        <input type="hidden" id="totalId" value="'.$total.'"/>
                        <input type="hidden" id="tipoDescuentoId" value="1"/>
                        <div id="divTotal">
                            <table align="center" valign="center">                                 
                                <tr style="font-size: 23px;">
                                    <td align="right">
                                        <b>Total:</b>
                                    </td>
                                    <td align="center">
                                        <b>$'.$total.'</b>
                                    </td>
                                </tr>                                
                            </table>
                         </div>
                         </td>
                         </tr>
                         </table>';
                    ?>

                 
                </td>	
                <td  width="15%" valign="top">
                    <table cellspacing="20">
                        <?php
                        $botonId="";
                            while ($registroBtnReiraLocal = $resBtnReiraLocal->fetch_array(MYSQLI_BOTH)) {
                                $botonId=$registroBtnReiraLocal["BOTONID"];
                            }
                            echo '<tr>
                                    <td>
                                        <input type="button" value="Retira local"'; 
                                        if($botonId==1){
                                            echo 'style="width:120px;height:40px;background-color: GRAY;color: WHITE;"';
                                        }else{
                                            echo 'style="width:120px;height:40px"';
                                        }
                                        echo 'onClick="retiraPedido(1)">
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                        <input type="button" value="Delivery"'; 
                                        if($botonId==2){
                                            echo 'style="width:120px;height:40px;background-color: GRAY;color: WHITE;"';
                                        }else{
                                            echo 'style="width:120px;height:40px"';
                                        }
                                        echo 'onClick="retiraPedido(2)">
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                        <input type="button" value="Espera"'; 
                                        if($botonId==3){
                                            echo 'style="width:120px;height:40px;background-color: GRAY;color: WHITE;"';
                                        }else{
                                            echo 'style="width:120px;height:40px"';
                                        }
                                        echo 'onClick="retiraPedido(3)">
                                    </td>
                                  </tr>'
                               ?>
                    </table>
                    <table cellspacing="50">
                        <tr>
                            <td>
                            </td>
                        </tr>
                    </table>

                </td>

            </tr>
        </table>
        <table cellspacing="20" align="center" width="80%" >
            <tr align="center">
                <td>
                    <input type="button" value="Atras" style="width:120px;height:40px" onClick="borrarAtras()">
                </td>
                <td>
                    <input type="button" value="Eliminar pedido" style="width:120px;height:40px" onclick="eliminarPedido()">
                </td>
                <td>
                    <input type="button" value="Siguiente" style="width:120px;height:40px" onClick="guardarPedidoFinal()">
                </td>             
            </tr>
        </table>
    </body>
</html>

