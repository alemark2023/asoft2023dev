## 4.0.5

### docs
2021-03-24 : cambios para ver el google docs viewer<br>
2021-03-10 : docs | changelog<br>


### fixed
2021-03-24 : fixed | https://gitlab.com/carlomagno83/facturadorpro4/-/issues/431 | no listan tareas programadas<br>
2021-03-24 : fixed | margen top en panel admin<br>
2021-03-23 : fixed | i-306 | se ha asociado el documento generado desde multiples guías a sus repectivas guías<br>
2021-03-22 : fixed | t-416 | Requerimiento para Cotizaciones - Referencia<br>
2021-03-19 : fixed | i-327 | error al seleccionar un modulo con hijos arreglado<br>
2021-03-18 : fixed | i-306 | se corrigió el error al crear cpe desde las guías<br>
2021-03-17 : fixed | i-141 | se oculto el boton editar de los cpe<br>
2021-03-17 : fixed | POS | problema con nombres de 50 caracteres<br>
2021-03-17 : fixed | i-141 | error en el campo leyenda al editar un cpe, solucionado<br>
2021-03-16 : fixed | mostrando orden de compra en plantilla full_height<br>
2021-03-15 : fixed | i-392 | se limito la busqueda de productos a los vendedores, ahora solo ven los items de su almacen<br>
2021-03-15 : fixed | i-282 | se modificó la formula para obtener las ganancias<br>
2021-03-12 : fixed | subida de logo en formato jpg<br>
2021-03-10 : fixed | errores varios<br>


### feature
2021-03-24 : feature | i-405 | problema al mostrar el pdf en moviles, arreglado<br>
2021-03-22 : feature | t-338 | Permitir generar comprobante de pago desde cotización a vendedores<br>
2021-03-21 : feature | sidebar espacios visible para submenus<br>
2021-03-21 : feature | header mas uniforme en diseño, cambio de switch, tooltip para todos<br>
2021-03-20 : feature | i-249 | backups independientes por empresas<br>
2021-03-20 : feature | i-327 | se cambió el orden para mostrar de los modulos(en el superadmin y los tenant)<br>
2021-03-19 : feature | i-327 | se agrego una la opción para activar o desactivar las opciones del menu ventas<br>
2021-03-18 : feature | i-261 | se puede cargar plantillas personalizada[*] sin afectacion a las actualizaciones<br>
2021-03-18 : feature | i-380 | telefono y vendedor en nota de venta y guia de remision, plantilla default<br>
2021-03-17 : feature | i-346 | se agrego una ruta más para editar un producto a partir de su id<br>
2021-03-16 : feature | i-306 | se agregó la opción para seleccionar la dirección de llegada dependiendo del cliente seleccionado en las guías de remisión<br>
2021-03-16 : feature | i-365 | reduccion de espacios superiores en formulario de creacion<br>
2021-03-15 : feature | i-369 | el usuario vendedor ya no puede editar el precio de venta de un producto al emitir un cpe<br>
2021-03-15 : feature | i-398 | telefono de cliente en plantilla brand<br>
2021-03-12 : feature | i-306 | se agrego la opción para generar un cpe desde multiples guías de remisión<br>
2021-03-12 : feature | i-306 | se agrego la opción para generar un cpe desde multiples guías de remisi+on<br>
2021-03-11 : feature | i-374 | se eliminó la marca de la descripción del producto cuando se usa la vista lista<br>
2021-03-11 : feature | i-129 | se agrego la plantilla multilples_logos que habilita la opción de imprimir el logo del establecimiento en los cpe<br>


## 4.0.4

### docs
2021-03-04 : docs | Update README.md<br>
2021-03-03 : docs | changelog<br>


### fixed
2021-03-19 : fixed | i-332 | optimización visual del pos
2021-03-10 : fixed | solución al error  logo not found<br>
2021-03-09 : fixed | i-361 | template header_image_full_width<br>
2021-03-08 : fixed | i-220-2 | se eliminó el icono de la frase genear guía<br>
2021-03-03 : fixed | i-363-2 | Se modificó la forma de importar productos, ahora el almacén se selecciona en el popup de importación<br>


### feature
2021-03-22 : feature | i-416 | Requerimiento para Cotizaciones - Referencia
2021-03-10 : feature | template center_note (solo cambia ticket en nota de venta)<br>
2021-03-09 : feature | i-374 | se agrego la marca del producto a la descripción en la sección POS<br>
2021-03-09 : feature | i-381 | se agregó una opción para subir un logo global, tambien una opción para ocultar el logo global, se ocultó la opción de configurar el login en los clientes<br>
2021-03-08 : feature | i-381 | se agregó una opción para configurar el login de los clientes desde el superadmin<br>
2021-03-05 : feature | feature-dashboard-tenants | Se hicieron algunas modificaciones al dashboard y se resolvió el problema de los totales en el dashboard<br>
2021-03-04 : feature | i-220 | se agregó el botón Generar Guía en la sección Reportes/Ventas/Consolidado de items<br>
2021-03-03 : feature | i-368 | se modificó la plantilla datatime, se agregó la hora de creación del pedido en los tickets<br>
2021-03-03 : feature | i-334 | se agregó la opción para agregar un lote a un producto desde las guías de remisión<br>


## 4.0.3

### fixed
2021-03-03 : fixed | i-361 | ocultando descuento en plantilla header_image_full_width<br>
2021-03-02 : fixed | compilando archivo estaticos<br>
2021-03-01 : fixed | a5 brand sale_note<br>
2021-02-24 : fixed | i-345 | La busqueda por rango de fechas se modifico, se dejó de usar la columna created_at por date_of_issue<br>
2021-02-24 : fixed | fixed-colores-sidebar-superadmin | Se modifico el color del menu del superadmin<br>
2021-02-24 : fixed | i-348 | template brand - error tickets y a5 en notas de venta<br>


### feature
2021-03-02 : feature | i-363 | Se agregó la columna almacén a la importación de productos.<br>
2021-03-01 : feature | i-386 | Se editaron las plantillas default, default, default2, default3, default4, font_sm, legend_amazonia y se le agregaron la hora a los reportes de tamaño ticket<br>
2021-03-01 : feature | i-343 | dashboard tenant sin espacio en disco<br>
2021-03-01 : feature | i-361 | cod por dto en plantilla image_full<br>
2021-02-26 : feature | feature-tramite-documentario | Se agrego la vista de crear expedientes<br>
2021-02-25 : feature | feature-sercofi-2 | se separó los servicios de los productos, ahora los servicios tienen su item en el sidebar, además se preselecciona la unidad de medida zz desde el boton crear<br>
2021-02-25 : feature | header Mi empresa<br>
2021-02-24 : feature | i-350 | se agrego el boton editar en las cotizaciones<br>


## 4.0.2

### fixed
2021-02-24 : fixed | i-348 | template brand - error tickets y a5<br>
2021-02-17 : fixed | header de tenant en diferentes opciones<br>


### feature
2021-02-23 : feature | feature-topbar | se agregó el botón de pedidos al topbar, se arreglo el modo responsivo en el topbar y se corrigió la combinación de colores en el sidabr<br>
2021-02-23 : feature | feature-topbar | Se egregó el boton modo oscuro en el header, se cambiaron los colores de los botones del topbar, se cambió los estilos del sidebar en sus diferentes variantes<br>
2021-02-22 : feature | feature-topbar | Cambios visuales en el sidebar, cambios finales, esperando feedback<br>
2021-02-22 : feature | feature-topbar | Cambios visuales en el sidebar, limpiando el fondo blanco del div nano<br>
2021-02-22 : feature | feature-topbar | Cambios visuales en el sidebar, limpiando el fondo blanco<br>
2021-02-22 : feature | feature-topbar | Cambios visuales en el sidebar<br>
2021-02-22 : feature | feature-topbar | Se agrego el sidebar de colores<br>
2021-02-19 : feature | feature-sercofi | Se separaron los filtros en cuentas por pagar y en cuentas por cobrar se agregó el boton mostrar penalidad por mora<br>
2021-02-19 : feature | sidebar estilo paneles oscuros<br>
2021-02-19 : feature | switcher-top color<br>


## 4.0.1

### docs
chagelod

## 4.0.0

### feature
Despliegue de PRO4
