<?php

if (!($conexion=mysql_connect("localhost", "root", ""))) {

    printf("<p> Error de Conexion</p>") ;
 die();

exit() ;

}

if (!mysql_select_db("test", $conexion)) {

    printf("<p> DB no validooo</p>") ;
 
     exit() ;

}
mysql_query( 'SET NAMES utf8' );
$variable = $_GET["q"];
$variable= mysql_real_escape_string($variable);
$variable= addslashes($variable);

$sql2="select * from productos where id_producto = $variable ";
var_dump($sql2);
    $rtdo=mysql_query($sql2);  
    if(mysql_num_rows($rtdo) != 0){
        while($registro=mysql_fetch_array($rtdo))
        { 
            var_dump($registro["descripcion"]);
        }
    }



?>