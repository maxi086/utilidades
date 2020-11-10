<?php

// DESCOMENTAR PARA APARENTAR QUE OBTENGO DATOS DE UNA BASE
//$bdSecciones[0] = array("id_seccion"=>1,"titulo"=>"Seccion 1","texto"=>"Texto1");
//$bdSecciones[1] = array("id_seccion"=>2,"titulo"=>"Seccion 2","texto"=>"Texto2");



?>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>PANELES</title>
  <!-- Bootstrap -->
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->

</head>

<body>
  <script src="../bootstrap/jquery-3.2.1.js"></script>
  <script src="../bootstrap/jquery-ui-1.10.4.custom.min.js"></script>
  <script src="../bootstrap/js/bootstrap.min.js"></script>
  <div class="container">

  <fieldset class="border p-2">
        <legend  class="w-auto">ORDEN DE LAS SECCIONES</legend>
            <div class="row">

                <div class="col col-sm-6">
                
                <div class="scrumboard row">
                    <div class="column flex" id="contenedor">

                    <?php
                    $inputListadoSecciones = "";
                    // SI NO TRAIGO NADA DE LA BASE DE DATOS PRECARGO UNOS POR DEFAULT
                    if(!empty($bdSecciones)){
                        
                        foreach ($bdSecciones as  $value) {
                            $inputListadoSecciones .= $value["id_seccion"]."-";
                            if($value["id_seccion"] == "secciones_panelUno" || $value["id_seccion"] == "secciones_panelDos" 
                                || $value["id_seccion"] == "secciones_panelTres" ){

                                    echo '<div class="panel panel-default portlet" id="'.$value["id_seccion"].'">
                                            <div class="panel-heading portlet-header">'.$value["titulo"].'</div>
                                            <div class="panel-body portlet-content"></div>
                                        </div>
                                    ';

                            }else{
                                echo '<div class="panel panel-info portlet" id="'.$value["id_seccion"].'">
                                            <div class="panel-heading portlet-header">Seccion
                                            <a class="btn btn-danger" onclick="quitar(this)">quitar</a>
                                            </div>
                                            <div class="panel-body portlet-content">
                                            <input type="text" class="form-control " placeholder="encabezado" name="'.$value["id_seccion"].'_encabezado" value="'.$value["titulo"].'" />
                                            <textarea class="form-control " placeholder="contenido" name="'.$value["id_seccion"].'_contenido" >'.$value["texto"].'</textarea>
                                            
                                            </div>
                                            
                                        </div>';

                            }

                        }

                    }else{ // SINO PRECARGO LOS QUE OBTENGO DE LA BASE DE DATOS
                    $inputListadoSecciones = "secciones_panelUno-secciones_panelDos-secciones_panelTres";
                    echo '<div class="panel panel-default portlet" id="secciones_panelUno">
                                <div class="panel-heading portlet-header">SECCION UNO</div>
                                <div class="panel-body portlet-content"></div>
                            </div>
                            <div class="panel panel-default portlet" id="secciones_panelDos">
                                <div class="panel-heading portlet-header">SECCION DOS</div>
                                <div class="panel-body portlet-content"></div>
                            </div>
                            <div class="panel panel-default portlet" id="secciones_panelTres">
                                <div class="panel-heading portlet-header">SECCION TRES</div>
                                <div class="panel-body portlet-content"></div>
                            </div>';


                    }
                    
                    ?>

                    </div>
                </diV>
                <div class="col col-sm-1">
                </div>
                </div>
                <div class="col col-sm-3">
                <input class="form-control input-block"type="text" id="inputPaneles" name="secciones_listado"  <?php echo 'value="'.$inputListadoSecciones.'"'; ?> />
                    <a  class="btn btn-info" onclick="agregar()">Agregar Panel</a>
                </div>
            </div>
        <br>
        <br>
        <br>
        <br>


        </fieldset>


  </body>
  <script>

function actualizarOrden(){
  contador = 0;
  inputPanel = document.getElementById("inputPaneles");
  inputPanel.value="";
   $('#contenedor').find('.portlet').each(function(){
      var innerDivId = $(this).attr('id');
      console.log(innerDivId);
      inputPanel.value += innerDivId+"-";
      contador ++;
  });
  return contador;

}



function agregar(){
    cantElementos = actualizarOrden();
    cantElementos ++;

    eDiv1 = document.createElement('div');
    eDiv1.setAttribute("id",'secciones_panel'+cantElementos);
    eDiv1.setAttribute("name",'secciones_panel'+cantElementos);
    eDiv1.setAttribute("class",'panel panel-info portlet');

    eDiv1.innerHTML = `
            <div class="panel-heading portlet-header">Seccion
               <a class="btn btn-danger" onclick="quitar(this)">quitar</a>
            </div>
            <div class="panel-body portlet-content">
              <input type="text" class="form-control " placeholder="encabezado" name="secciones_panel`+cantElementos+`_encabezado" />
              <textarea class="form-control " placeholder="contenido" name="secciones_panel`+cantElementos+`_contenido"></textarea>
            </div>   
      `;
      document.getElementById("contenedor").appendChild(eDiv1);
      actualizarOrden();
      
}


function quitar(e){
    //console.log(e.parentNode.parentNode);
    e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
    actualizarOrden();
}

$(function() {
    $( ".column" ).sortable({
      connectWith: ".column",
      handle: ".portlet-header",
      cancel: ".portlet-toggle",
      placeholder: "portlet-placeholder ui-corner-all",
      change: function(){actualizarOrden()},
      update: function(){actualizarOrden()}
    })
 
    $( ".portlet" )
      .addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
      .find( ".portlet-header" )
        .addClass( "ui-widget-header ui-corner-all" )
       
 
    $( ".portlet-toggle" ).click(function() {
      var icon = $( this );
      icon.toggleClass( "ui-icon-minusthick ui-icon-plusthick" );
      icon.closest( ".portlet" ).find( ".portlet-content" ).toggle();
      actualizarOrden();
      
    });
  });
</script>

</html>