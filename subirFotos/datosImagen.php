<?php

if (!($conexion=mysql_connect("localhost", "root", ""))) {
    printf("<p> Error de Conexion</p>") ;
 die();
}

if (!mysql_select_db("test", $conexion)) {
    printf("<p> DB no validooo</p>") ; 
     exit() ;
}

mysql_query( 'SET NAMES utf8' );

$nombre_archivo  = $_FILES['archivo']['name'];
$tipo_archivo  = $_FILES['archivo']['type'];
$tam_archivo  = $_FILES['archivo']['size'];


if($tam_archivo < 1000000){

    $carpeta_destino =  "uploads/";//$_SERVER['DOCUMENT_ROOT'].'uploads';
    move_uploaded_file($_FILES['archivo']['tmp_name'],$carpeta_destino.$nombre_archivo);


    $archivo_objetivo=fopen($carpeta_destino.$nombre_archivo,"r");

    $contenido = fread($archivo_objetivo,$tam_archivo);

    $contenido = addslashes($contenido);

    fclose($archivo_objetivo);

    $sql = "INSERT INTO tabla_fotos (detalle,foto) VALUES ('nueva foto','$contenido')";


    $sqlE=mysql_query($sql);
    $my_errorCampos = mysql_error();

    echo $my_errorCampos; 

 

}

?>