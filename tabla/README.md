# TABLA DINAMICA

- conexionMysqli.php : Contiene Las Funciones generales para ejecutar querys de select e insert en la DB
- controladorInterface.php: Hace de interface para las llamadas ajax al controlador

- Tabla dinamica: trae todos los productos por ajax y paginados
- La paginacion se hace backend cada cambio de pagina llama por ajax al backend para traer la info
- Tiene la opcion de select All
- Se guardan temporalmente en un input un json con los datos a guardar
- Al Guardar se crea la venta y se guardan todos los detalles (un insert por cada elemento)
- Usa Prepared Statements  para insert y select 

![](https://github.com/maxi086/utilidades/blob/master/tabla/imagenesRM/Captura1.JPG)
