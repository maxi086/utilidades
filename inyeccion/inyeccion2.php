<?php


$conexion = mysqli_connect("localhost","root","");
$variable = $_GET["q"];


if(mysqli_connect_errno()){

    echo "fallo la conexion";
}

mysqli_select_db($conexion,"test") or die ("No se encuentra BD");

mysqli_set_charset($conexion,"utf-8");


$sql2="select * from productos where id_producto = ?";

$resultado = mysqli_prepare($conexion,$sql2);

$ok = mysqli_stmt_bind_param($resultado,'i',$variable);

$ok = mysqli_stmt_execute($resultado);

if($ok==false){
    echo "Error";
}else{
    $ok = mysqli_stmt_bind_result($resultado,$id_producto,$descripcion,$monto);
    

    while(mysqli_stmt_fetch($resultado)){
        echo $id_producto.' - '.$descripcion.' - '.$monto;
    } 


    mysqli_stmt_close($resultado);
}




?>