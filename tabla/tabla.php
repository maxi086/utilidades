<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Tabla</title>
</head>

<body>
    <div class="container">
        <form id="form">
            <input type="text" class="form-control" name="listaTabla1" id="listaTabla1" value=''>

            <a class="btn btn-success form-control" onclick="buscarRegistros(pagina = 1)"> Buscar</a>
            <button class="btn btn-info form-control" id="btnGuardar" onclick="guardarTodo(event)">GUARDAR</button><span id="loading"></span>
            <div class="row">
                <div class="col-sm-9">
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="tabla1SelectAll">Marcar Todos:&nbsp;&nbsp;</label>
                        <input type="checkbox" id="tabla1SelectAll" onchange="selectTabla1All(this,'.chktabla1')">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col col-sm-12">
                    <div class="table-responsive ">
                        <table class="table table-hover table-striped" id="tabla1">

                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm col-sm-5">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination" id="paginacion1">

                        </ul>
                    </nav>
                </div>
            </div>
        </form>
    </div><!-- contenedor -->

    <script src="jquery-3.2.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="jquery-3.2.1.js"></script>

</body>
<script>
    /** 

*/
    function buscarRegistros(paginaSeleccionada) {
        table = document.getElementById('tabla1');
        dataAjax = {
            page: paginaSeleccionada,
            items: 5
        }

        $.ajax({
            url: 'controladorInterface.php/listarProductosPaginado',
            type: 'post',
            data: dataAjax,
            cache: false,
            success: function(r) {
                console.log(r);
                retorno = JSON.parse(r);
                //console.log(r);
                listado = retorno["listado"];
                console.log(retorno);
                armarTabla1(table, listado);
                armarPaginacion(retorno['paginaActual'], retorno['paginasTotales'], 10, "paginacion1", "buscarRegistros");
                // console.log(r);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert("Status: " + textStatus);
                alert("Error: " + errorThrown);
            }
        });

    }

    function armarTabla1(table, datos) {
           //INICIALIZO EL ARRAY GUARDADO EN EL INPUT
           if (document.getElementById("listaTabla1").value != "")
             arrayValores = JSON.parse(document.getElementById("listaTabla1").value);
            else
             arrayValores = new Array();


        tablaRegistros = new Array();
        i = 1;
        datos.forEach(element => {
            registro = new Array();
            registro[0] = '' + element['id'] + ' [...] ';
            registro[1] = '' + element['descripcion'];
            registro[2] = '' + element['monto'];

        // PRIMERO FERIFICO SI ESTA CARGADA EN EL INPUTHIDDEN 
        if (typeof arrayValores.find(objeto => objeto.id == element['id']) !== 'undefined'){
            var objetoEncontrado = arrayValores.find(objeto => objeto.id == element['id']);
                registro[4] = '<input type="checkbox" class="chktabla1" onchange="chkTabla1Change(this)" name="panelTabla1_' + element['id'] + '" checked > ';
                registro[5] = '<input type="text" onchange="ordenArrayChange(this,\'listaTabla1\')" class="form-control input-sm"  style="width:48" name="tablaOrden_' + element['id'] + '"  value="' + objetoEncontrado.orden+ '" > ';
        }else{ // SI NO ESTA EN E INPUT HIDDEN VERIFICO EL CHECK LO QUE VENIA DE LA BASE DE DATOS
            if (!isNaN(parseInt(element['cv_cargado']))) {
                //registro[4] = '<input type="checkbox" class="chktabla1" onchange="chkTabla1Change(this)" name="panelTabla1_' + element['id'] + '" checked > ';
                //registro[5] = '<input type="text" onchange="ordenArrayChange(this,\'listaTabla1\')" class="form-control input-sm"  style="width:48" name="tablaOrden_' + element['id'] + '"  value="' + element['id'] + '" > ';
                registro[4] = '<input type="checkbox" class="chktabla1" onchange="chkTabla1Change(this)" name="panelTabla1_' + element['id'] + '"  > ';
                registro[5] = '<input type="text" onchange="ordenArrayChange(this,\'listaTabla1\')" class="form-control input-sm "  style="width:48" name="tabla1Orden_' + element['id'] + '"   > ';
            } else {
                registro[4] = '<input type="checkbox" class="chktabla1" onchange="chkTabla1Change(this)" name="panelTabla1_' + element['id'] + '"  > ';
                registro[5] = '<input type="text" onchange="ordenArrayChange(this,\'listaTabla1\')" class="form-control input-sm "  style="width:48" name="tabla1Orden_' + element['id'] + '"   > ';
            }
        }
            tablaRegistros[i] = registro;
            i++;
        });

        tablaTitulos = ["id_producto", "descripcion", "monto", "seleccionar", "Cantidad"];

        armarTabla(table, tablaRegistros, tablaTitulos);
    }

    function armarTabla(table, tablaRegistros, tablaTitulos) {
        //console.log(tablaRegistros);
        //LIMPIO LA TABLA SI SE ENCUENTRA CARGADA
        bodys = table.getElementsByTagName("tbody")[0];
        if (typeof(bodys) != 'undefined' && bodys != null)
            table.removeChild(bodys);
        var tbody = document.createElement("tbody");

        //CARGO LOS ENCABEZADOS
        var rowth = document.createElement("tr");
        tablaTitulos.forEach(titulo => {

            cellElement = document.createElement("th");
            cellElement.innerHTML = titulo;
            rowth.appendChild(cellElement);

        });
        tbody.appendChild(rowth);

        //CARGO LOS REGISTROS
        tablaRegistros.forEach(element => {
            var row = document.createElement("tr");

            element.forEach(registro => {
                var cellElement = document.createElement("td");
                cellElement.innerHTML = registro;
                row.appendChild(cellElement);
            });

            tbody.appendChild(row);

        });

        //VINCULO EL TBODY A LA TABLA
        table.appendChild(tbody);
        //console.log(table);

    }


    function armarPaginacion(actual, pagTotal, maxPag, idPaginacion, onClickFunction) {
        actual = parseInt(actual);
        pagTotal = parseInt(pagTotal);
        maxPag = parseInt(maxPag);

        idPaginacion = document.getElementById(idPaginacion);
        paginas = "";
        primera = actual - parseInt(maxPag / 2);
        ultima = actual + parseInt(maxPag / 2);

        if (primera <= 0)
            primera = 1;

        if (ultima >= pagTotal)
            ultima = pagTotal


        paginas += `<li class="page-item">
                        <a class="page-link" onclick="` + onClickFunction + `(` + 1 + `)" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                        </li>`;
        i_aux = 0;
        for (i = primera; i <= ultima; i++) {
            i_aux = i;
            if (i == actual)
                paginas += '<li class="page-item active"><a class="page-link" onclick="' + onClickFunction + '(' + i + ')">' + i + '</a></li>';
            else
                paginas += '<li class="page-item ""><a class="page-link" onclick="' + onClickFunction + '(' + i + ')">' + i + '</a></li>';
        }
        if (i_aux != 0 && pagTotal > (i_aux + 1)) {
            anteultima = pagTotal - 1;
            paginas += '<li class="page-item ""><a class="page-link" ">...</a></li>';
            paginas += '<li class="page-item ""><a class="page-link" onclick="' + onClickFunction + '(' + anteultima + ')">' + anteultima + '</a></li>';
        }
        paginas += `   <li class="page-item">
                        <a class="page-link" onclick="` + onClickFunction + `(` + pagTotal + `)" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                      </li>`;

        idPaginacion.innerHTML = paginas;


    }


    function selectTabla1All(evento, clase) {
        console.log("ENTRE")
        if (evento.checked) {
            //console.log("CHEKED")
            // Iterate each checkbox
            $(clase).each(function() {
                this.checked = true;
                chkTabla1Change(this)
            });
        } else { //console.log("NO CHEKED")
            $(clase).each(function() {
                this.checked = false;
                chkTabla1Change(this)
            });
        }

    }


    function chkTabla1Change(event) {
        id = event.name.split("_");
        checked = event.checked;
        id = id[1];
        inputdelArray = "listaTabla1";
        inputOrden = "tabla1Orden_" + id;

        chkChange(id, inputdelArray, inputOrden, checked);

    }


    function chkChange(id, inputdelArray, inputOrden, checked) {


        //INICIALIZO EL ARRAY GUARDADO EN EL INPUT
        if (document.getElementById(inputdelArray).value != "")
            arrayValores = JSON.parse(document.getElementById(inputdelArray).value);
        else
            arrayValores = new Array();

        //OBTENGO EL VALOR DEL ONRDEN
        ordenInput = document.getElementsByName(inputOrden)[0];
        orden = ordenInput.value;

        //SI NO ESTA CARGADO EL ORDEN BUSCO EL MAXIMO +1 DEL ARRAY O LO PONGO EN 1
        maximoOrden = Math.max.apply(Math, arrayValores.map(o => o.orden));
        if (orden == "" || isNaN(maximoOrden) || maximoOrden == "Nan" || arrayValores.length == 0) {
            if (maximoOrden == 0 || isNaN(maximoOrden) || maximoOrden == "Nan" || arrayValores.length == 0)
                orden = 1;
            else
                orden = maximoOrden + 1;
        } else {
            orden = parseInt(orden);

        }

        //CREO EL OBJETO A INSERTAR EN EL ARRAY
        var obj = {};
        obj["id"] = id;
        obj["orden"] = orden;

        //console.log(obj);
        //SI LO TILDE Y NO EXISTE LO AGREGO 
        if (arrayValores.find(objeto => objeto.id === id) === undefined && checked) {
            arrayValores.push(obj);
            ordenInput.value = orden;
        }

        // SI LO DESTILDE Y NO EXISTE LO REMUEVO 
        if (typeof arrayValores.find(objeto => objeto.id === id) !== 'undefined' && !checked) {
            arrayValores = arrayValores.filter(function(objeto) {
                return objeto.id !== id;
            });
            ordenInput.value = "";
        }

        // CODIFICO EL ARRAY Y LO GUARDO EN EL INPUT
        arrayValores = JSON.stringify(arrayValores);
        document.getElementById(inputdelArray).value = arrayValores;
    }

    // FUNCION QUE ACTUALIZA EL CAMBIO DEL ORDEN 
    // recibe el input y el nombre del input que almacena el array
    function ordenArrayChange(e, inputdelArray) {
        id = e.name.split("_");
        id = id[1];

        if (document.getElementById(inputdelArray).value != "") {
            arrayValores = JSON.parse(document.getElementById(inputdelArray).value);

            var foundIndex = arrayValores.findIndex(objeto => objeto.id == id);
            arrayValores[foundIndex].orden = e.value;

            arrayValores = JSON.stringify(arrayValores);
            document.getElementById(inputdelArray).value = arrayValores;
        }


    }


    function guardarTodo(event) {
        var B = document.getElementById('loading');
        document.getElementById('btnGuardar').disabled = true;
        B.innerHTML = "<img src='images/loading.gif' alg='Loading...'>";
        event.preventDefault();

        $.ajax({
            url: 'controladorInterface.php/guardar',
            type: 'post',
            data: $('form').serialize(),
            cache: false,
            success: function(r) {
                console.log(r);
                try {
                    respuesta = JSON.parse(r);
                } catch (e) {
                    console.log("Excepcion Error");
                    B.innerHTML = "";
                    document.getElementById('btnGuardar').disabled = false;
                }

                if (respuesta['error'] == 0) {
                    console.log("Se Guardo con exito");
                } else {
                    console.log("Error al Guardar");
                }
                B.innerHTML = "";
                document.getElementById('btnGuardar').disabled = false;
                console.log(respuesta);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                B.innerHTML = "";
                document.getElementById('btnGuardar').disabled = false;
                console.log("Error al Guardar");
                alert("Status: " + textStatus);
                alert("Error: " + errorThrown);
            }
        });


    }
</script>

</html>

<?php
/*
include_once("controlador.php");

$controlador = new Controlador();
$r = $controlador->listarVentas(null);
var_dump($r);
*/
?>