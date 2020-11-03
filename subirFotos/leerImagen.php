<?php

if (!($conexion=mysql_connect("localhost", "root", ""))) {
    printf("<p> Error de Conexion</p>") ;
 die();
}

if (!mysql_select_db("test", $conexion)) {
    printf("<p> DB no validooo</p>") ; 
     exit() ;
}

$sql2="select * from tabla_fotos where id = 3 ";
    $rtdo=mysql_query($sql2);  
    if(mysql_num_rows($rtdo) != 0){
        while($registro=mysql_fetch_array($rtdo))
        { 
            echo "<img src='data:image/jpeg; base64,".base64_encode($registro["foto"])."'>";
        }
    }



?>