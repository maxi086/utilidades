<?php
include_once("conexionMysqli.php");
class Controlador
{

    /**
     * Lista todos  los productos en forma paginada
     *
     * @param [array] $filtros["items","page"] (cantidad de elementos a mostrar y pagina actual)
     * @return void
     */
    public function  listarProductosPaginado($filtros)
    {

        $items = isset($filtros['items']) ? $filtros['items'] : 50;
        $page = isset($filtros['page']) ? $filtros['page'] : 1;

        $listado =  array();
        $conexion = new Conexion();

        try {
            $sqlLimit = " ";
            $arrayParam = array();
            $typesParam = "";

            //CONSULTA PARA PAGINAR
            if (isset($filtros['page']) and is_numeric($filtros['page']) and $page = $filtros['page']) {
                $paginaInicial = (($page - 1) * $items);
                array_push($arrayParam, $paginaInicial);
                array_push($arrayParam, $items);
                $typesParam .= "ii";
                $sqlLimit = " LIMIT ? , ? ";
            } else {
                array_push($arrayParam, $items);
                $typesParam .= "i";
                $sqlLimit = " LIMIT ? ";
            }

            // ARMO LA QUERY
            $query = "SELECT p.*,p.id_producto as id 
            FROM productos p
            " . $sqlLimit . "";

            $respuesta = $conexion->selectSqlToArray($query, $typesParam, $arrayParam);
            if ($respuesta["codigo"] == 0) {
                $listado["listado"] = $respuesta["resultado"];

                $queryCount = "SELECT count(*) as totalItems 
                               FROM productos p";

                $respuestaContador = $conexion->selectSqlToArray($queryCount, "", array());
                if ($respuestaContador["codigo"] == 0) {
                    $paginasTotales = $respuestaContador["resultado"][0]["totalItems"] / $items;
                    if ($paginasTotales > round($paginasTotales, 0, PHP_ROUND_HALF_ODD))
                        $paginasTotales = round($paginasTotales, 0, PHP_ROUND_HALF_ODD) + 1;
                    else
                        $paginasTotales = round($paginasTotales, 0, PHP_ROUND_HALF_ODD);
                    $listado["registros"] = $paginasTotales;
                }
            }
        } catch (Exception $e) {
            return null;
        }

        return $listado;
    }


    /**
     * Insertar venta
     *
     * @param [int] $id_cliente
     * @param [int] $numero
     * @param [String] $observacion
     * @return void
     */
    public function insertarVenta($id_cliente, $numero, $observacion)
    {
        $conexion = new Conexion();
        $fecha = date('Y-m-d');
        $parametros = array($id_cliente, $numero, $fecha, $observacion);
        $typesParametros = "iiss";
        $query = " insert into ventas (id_cliente,numero,fecha,observacion) values (?,?,?,?) ";

        $respuesta = $conexion->insertSimple($query, $typesParametros, $parametros);

        return $respuesta;
    }

   /**
    * Insertar un detalle de una venta
    *
    * @param [int] $id_venta
    * @param [int] $id_producto
    * @param [int] $cantidad
    * @param [decimal 10,2] $monto_total
    * @return void
    */
    public function insertarDetalleVenta($id_venta, $id_producto, $cantidad, $monto_total)
    {
        $conexion = new Conexion();

        $parametros = array($id_venta, $id_producto, $cantidad, $monto_total);
        $typesParametros = "iiid";
        $query = " insert into ventas_detalle (id_venta,id_producto,cantidad,monto_total) values (?,?,?,?) ";

        $respuesta = $conexion->insertSimple($query, $typesParametros, $parametros);

        return $respuesta;
    }
}
