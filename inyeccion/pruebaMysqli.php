<?php

include("conexionMysqli.php");

$conexion = new Conexion();

$query = "select *  from productos where id_producto = ?";

$parametros = array("973","ssss","ddd");
$tipos = "sss";
$respuesta = $conexion->selectSqlToArray($query,"s",array("2 or 1=1"));


var_dump($respuesta);





?>