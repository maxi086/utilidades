<?php

// EXTRAIGO EL METODO QUE (ULTIMO /)
$funcion = end(explode('/', $_SERVER['REQUEST_URI']));
include_once('controlador.php');

// SEGUN EL METODO LLAMO A LA FUNCION
switch ($funcion) {
    case 'listarProductosPaginado':
        listarProductosPaginado();
        break;
    case 'guardar':
        guardar();
        break;

    default:
        # code...
        break;
}

/**
 * Litado Paginado de un Producto
 * 
 * @param  $name$_POST["page"] default 1
 * @param  $name$_POST["items"] default 5
 *
 * @return void
 */
function listarProductosPaginado()
{

    $post = $_POST;
    $controlador = new Controlador();
    $filtros['page'] = isset($_POST['page']) ? $_POST['page'] : 1;
    $filtros['items'] = isset($_POST['items']) ? $_POST['items'] : 5;
    $result = array();

    try{
        $listado = $controlador->listarProductosPaginado($filtros);
        if(!empty($listado)){
            $result["listado"] = $listado["listado"];
            $result["paginaActual"] = $filtros['page'];
            $result["paginasTotales"] = $listado["registros"];

        }else{
            $result["listado"] = null;
            $result["paginaActual"] = 1;
            $result["paginasTotales"] = 1;
        }        

    }catch(Exception $e){
        $result["listado"] = null;
        $result["paginaActual"] = 1;
        $result["paginasTotales"] = 1;
    }

    
    echo json_encode($result);
}


/**
 * Guarda una venta y su detalle
 * 
 * @param $_POST["listaTabla1"]  (lista de detalle ventas)
 * @param $id_cliente (por hora default 1)
 * @param $numero (por hora default 2)
 * @param $observacion (nueva venta 2)
 *
 * @return $respuesta["error","mensaje","id_ventas"]
 */
function guardar()
{
    $respuesta['error'] = 0;
    $respuesta['mensaje'] = "";
    $respuesta['id_ventas'] = 0;

    $controlador = new Controlador();


    $listaTabla1P = $_POST["listaTabla1"];
    $listaTabla1 = json_decode($listaTabla1P);

    $respuestaVenta = $controlador->insertarVenta($id_cliente = 1, $numero = 2, $observacion = "nueva venta 2");
    if (!empty($respuestaVenta) && $respuestaVenta["id_insertado"] > 0) {
        $respuesta['id_ventas'] = $respuestaVenta["id_insertado"];

        foreach ($listaTabla1 as $value) {

            $controlador->insertarDetalleVenta($respuestaVenta["id_insertado"], $value->id, $value->orden, 10.23);
        }
    }


    echo json_encode($respuesta);
}
