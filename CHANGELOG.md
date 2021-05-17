## 4.0.9

### docs
2021-05-05 : docs | changelog<br>


### fixed
2021-05-17 : fixed | #521 | Reporte: Ventas: Ajuste de tiempo maximo para pdf a 1800 segundos (30 minutos)<br>
2021-05-14 : fixed | #517 | Compile JS<br>
2021-05-14 : fixed | #517 | Nota de Ventas: Añadiendo fecha de pago al metodo de pago<br>
2021-05-14 : fixed | #389 | permisos y restricciones de accesos mediante urls<br>
2021-05-13 : fixed | #519 | missing use configuration<br>
2021-05-13 : fixed | #519 #577 | Fix Response<br>
2021-05-13 : fixed | #519 | Compile js<br>
2021-05-13 : fixed | #519 | Finanzas: Balance: Oculta el saldo total basado en la configuracion seller_can_view_balance<br>
2021-05-13 : fixed | #519 | Configuracion: Avanzada: Visual: Permite mostrar opcion de vendedor para ver balance en finanzas<br>
2021-05-13 : fixed | #578 | Contabilidad: Exportar reporte: Reporte de venta: Se amplia nota de debito y credito cuando afecta a un documento, para evaluar su status y, de estar anulada, se ajusta a 0 el total de la misma<br>
2021-05-12 : fixed | #516 #577 | Compile JS<br>
2021-05-12 : fixed | #577 | Item: DocumentFormItem evalua si es vendedor y si puede crear producto.<br>
2021-05-12 : fixed | #577 | Configuracion: Visual: Se añade posibilidad de vendedor para crear producto<br>
2021-05-12 : fixed | #389 | accesos de usuarios a vistas sin permisos<br>
2021-05-12 : fixed | #516 | Admin: Lista de usuarios: Ajuste grafico para orden de comprobantes<br>
2021-05-12 : fixed | #578 | Contabilidad: Exportar Reporte: Ajuste para colocar en 0 los totales cuando sea factura o bolenta, con status Rechazado o Anulado<br>
2021-05-12 : fixed | #578 | Contabilidad: Exportar Reporte: Ajuste para mostrar las notas correctamente.<br>
2021-05-12 : fixed | 582 | errores en filename y selector de series al editar comprobante<br>
2021-05-11 : fixed | #482 | Cash: Reportes de caja: Generacion de datos en el controlador. Ajuste para añadir credito y su calculo en los reportes de caja<br>
2021-05-11 : fixed | #582 | actualizando correlativo al modificar tipo de comprobante tomando el proximo numero a registrar en la serie seleccionada<br>
2021-05-11 : fixed | #576 | Compras: Anular Compra: Ajuste para verificar que exista lots_enabled.<br>
2021-05-10 : fixed | #407 #554 | Compile js<br>
2021-05-10 : fixed | #496 | Administración: Usuarios: Restaurando configuración de correo por cliente<br>
2021-05-10 : fixed | #482 #554 | Revirtiendo Cambios 3a2d670b838dabcddbcd3698d29c1d9bc13e93d0 37a23f9f91feaa47ae8bb695706c531e842d6de5 496e0cb02dd740c0d0cca36b285b5f5b69dfaa8d 8cfda276e32db47e7834ebfcd2e260dcccc09bb5 664d0a047d62c286396ead521c9dd71616b4e828<br>
2021-05-10 : fixed | #550 | altura cuerpo de plantilla<br>
2021-05-10 : fixed | #407 | Compras: Añadir Producto: Codigo de barras: Al encontrar el item, se elimina la busqueda por barras y se ajusta normal.<br>
2021-05-10 : fixed | #563 | Marcas y Categorías: Crear/Editar: Se añade validacion para marca o categoría única<br>
2021-05-07 : fixed | #433 | Reporte Caja: Muestra todos los ingresos a caja. No suma Credito al total de caja<br>
2021-05-07 : fixed | #433 #468 #554 | Compile js<br>
2021-05-07 : fixed | #554 | Documentos por Regularizar: Archivo de migracion<br>
2021-05-07 : fixed | #554 | Documentos por Regularizar: Se añade posiblidad de eliminar documentos con regularize_shipping y response_regularize_shipping, que tambien, tengan un documento con serie y con numero igual. Softdelete a tabla documents<br>
2021-05-07 : fixed | #433 | Reporte: Venta: Documento: Se añade opciones de descarga en columna<br>
2021-05-06 : fixed | #468 | Nota de venta: Se añade fecha de vencimiento a las nota de venta. Se lista Fecha de vencimiento a las nota de ventas<br>
2021-05-06 : fixed | #511 #512 | Finanzas: Cuentas por Cobrar/ Cuentas por pagar: Añadiendo la opcion a todos en proveedores y clientes<br>


### feature
2021-05-17 : feature | #592 | ticket formato 58mm para notas de venta<br>
2021-05-13 : feature | #589 | mejoras visuales de tablas y datos del dashboard<br>
2021-05-07 : feature | #550 | plantilla con bordes redondeados -rounded-<br>
2021-05-06 : feature | - | Tarea Programada: Añadiendo bat para tareas programadas en windows<br>


## 4.0.8

### docs
2021-04-21 : docs | changelog<br>


### fixed
2021-05-05 : fixed | #565 | mostrando logs al ejecutar una tarea programada<br>
2021-05-05 : fixed | #513 | Compile js<br>
2021-05-05 : fixed | #565 | mostrando logs al ejecutar una tarea programada<br>
2021-05-05 : fixed | #482 | CashControllerRevision: Separando Tipo de documenos de Metodos de pago<br>
2021-05-04 : fixed | #496 #407 | Compile JS<br>
2021-05-04 : fixed | #496 | Administración: Usuarios: Notificacion para correo como informacion adiciona en el manual. Cambia la configuracion si todos los campos estan llenos. Contraseña queda excluida de la actualizacion<br>
2021-05-04 : fixed | #544 | Cotizacion: Nueva cotizacion: Añadido Marca al PDF default A5.<br>
2021-05-04 : fixed | #544 | Cotizacion: Nueva cotizacion: Añadido Marca al PDF default.<br>
2021-05-04 : fixed | #566 | plantilla model3<br>
2021-05-04 : fixed | #540 | llamados en mobilecontroller<br>
2021-05-04 : fixed | #407 | Compras: Añadir Producto: Codigo de barras: Se añade la funcionabilidad de buscar por codigo de barras.<br>
2021-05-03 : fixed | #545 | valor booleano desde resources<br>
2021-05-03 : fixed | #496 | Administración: Usuarios: Añade configuracion de correo por clientes.<br>
2021-05-01 : fixed | #141 | se reparo el error al convertir dolares a soles y viceversa, al editar comprobantes<br>
2021-04-30 : fixed | #503 | Pos: Busqueda: Se ajusta para que la paginacion funcione correctamente por input_item<br>
2021-04-30 : fixed | #555 | consulta de productos mediante busqueda en guias de remision<br>
2021-04-30 : fixed | #503 | Pos: Busqueda: Se ajusta para que la paginacion funcione correctamente por input_item<br>
2021-04-30 : fixed | #539 | Admin: Usuarios: Modulos: Permite que el los nodos hijos se seleccionen cuando el padre es seleccionado, y quiten la seleccion cuando el caso contrario suceda<br>
2021-04-30 : fixed | #498 | Reporte: Venta: GENERAL DE PRODUCTOS: Muestra Ventas Anuladas en los registros cuando no deberia.<br>
2021-04-30 : fixed | #555 | vista guia tarda mucho en realizar consultas, se elimina repeticion de queries de localidades<br>
2021-04-29 : fixed | #539 | Compile JS<br>
2021-04-29 : fixed | cambio de plantilla solo cuando se tenga un establecimiento seleccionado<br>
2021-04-29 : fixed | #539 | Usuarios: Permisos: Ajuste para seleccionar un elemento y sus hijos. Ajusta el mismo status de check a los hijos<br>
2021-04-29 : fixed | #556 | Pos: Añade item al buscar por Codigo de barras<br>
2021-04-29 : fixed | #482 | CashControllerRevision: Nomenclatura correcta.<br>
2021-04-29 : fixed | #513 | Dashboard: Grafico Nota de venta - Comprobante: Total Pagado por Total Cobrado / Total por Pagar por Pendiente de cobro<br>
2021-04-28 : fixed | #482 | Removiendo PaymentMethodType del template<br>
2021-04-28 : fixed | #482 | compile js<br>
2021-04-28 : fixed | #425 | Compile JS<br>
2021-04-28 : fixed | #503 | Compile JS<br>
2021-04-28 : fixed | #503 | Pos: Filtrado de items: Ajuste para evaluar si description es null, sino se toma name o internal_id. Se mantiene el filtrado input_item<br>
2021-04-27 : fixed | #482 | Pos: Caja chica Pos: Reporte PDF: Se remueven metodos de pago a credito de facturacion, para que en el reporte puedan salir correctamente listados y de el monto correcto en el ingreso a caja<br>
2021-04-26 : fixed | #329 | Compile JS<br>
2021-04-26 : fixed | #541 | Nota de venta: Creacion con producto con lotes: Validar que exista presentation -> quantity_unit en el array<br>
2021-04-26 : fixed | #466 | Contabilidad: Exportar Reporte: Ajuste para validar que documento exista cuando document_type_id es 07 o 08<br>
2021-04-26 : fixed | #425 | REPORTES: CONSISTENCIA DE DOCUMENTOS POR RANGO DE FECHAS: Al front, se limita a 30 dias la seleccion final de consulta.<br>
2021-04-26 : fixed | #425 | REPORTES: CONSISTENCIA DE DOCUMENTOS POR RANGO DE FECHAS: Se elimina la validacion getIsClient, se inicia con 1 mes pero el cliente puede consultar todo el rango de fechas( documents created_at min hasta documents created_at max)<br>
2021-04-26 : fixed | #530 | Facturas: (invoice) Ocultar cuentas inhabilitadas y que tengan "Mostrar cuenta en los reportes de comprobantes" desactivado para el tema Top Ruc<br>
2021-04-26 : fixed | #329 | Pedidos: Ajuste para establecer seller_id desde Pedidos al generar el comprobante<br>
2021-04-23 : fixed | #329 | Compile js<br>
2021-04-23 : fixed | #529 | Compra: Nueva compra desde Orden de Compra: update_price si es verdadero accede a la actualizaion de precios.<br>
2021-04-23 : fixed | #529 | Compra: Nueva compra desde Orden de Compra: Error Undefined index: update_price al crear Nueva Compra<br>
2021-04-22 : fixed | #329 | Nota de ventas: Ajuste para establecer seller_id desde nota de ventas al generar el comprobante<br>
2021-04-22 : fixed | #451 #479 | Compile JS<br>
2021-04-22 : fixed | #451 | Item: Marcas: Ajuste para la url de brands. No guarda desde el modulo de compra<br>
2021-04-22 : fixed | #451 | Item: Categorias: Ajuste para la url de categorias. No guarda desde el modulo de compra<br>
2021-04-22 : fixed | #479 | Nota de ventas: Generar Comprobante: Ajuste para que v-modal no se sobreponga en el modal de impresion<br>
2021-04-22 : fixed | #432 | Compile JS<br>
2021-04-22 : fixed | #399<br>
2021-04-21 : fixed | #408 | Actualizar precio de compra en formulario de PRODUCTO en base al precio de COMPRA del formulario de COMPRAs<br>
2021-04-21 : fixed | i-399 | PROBLEMA NO COINCIDE STOCK DE CATALOGO/PRODUCTOS CON REPORTE KARDEX<br>
2021-04-21 : fixed | #515 | titulos y listas en modo oscudo<br>
2021-04-21 : fixed | #432 | Se revierte la configuracion para stock 0 y solo items por almacen de usuario. En vez de eso, se mostrará el almacen del item<br>
2021-04-21 : fixed | #481 | error lots_enable (no existe key en json)<br>
2021-04-21 : fixed | #497 | Log Diario de laravel en .env.example<br>
2021-04-21 : fixed | #497 | Compile JS<br>
2021-04-21 : fixed | #497 | Ajuste para validar user_id como creador de sales note<br>
2021-04-20 : fixed | #497 | Reporte general de productos: El modelo sale_notes no permite registrar el vendor. Se ajusta para que nota de venta no muestre vendedor.<br>


### feature
2021-05-04 : feature | #434 | organizando anulaciones por orden de fecha de emision<br>
2021-05-03 : feature | #540 | mobileContrlller y ruta para subir imagenes desde apk personalizadas por terceros<br>
2021-04-29 : feature | #545 | creado ticket 58mm para pedidos<br>
2021-04-29 : feature | habilitando ticket de 58mm desde configuracion avanzada<br>
2021-04-28 : feature | #545 | rediseño de vista configuracion avanzada<br>
2021-04-27 : feature | #450 | compile js<br>
2021-04-27 : feature | #450 | modulo de compras funcionando con formatos por establecimientos<br>
2021-04-27 : feature | #450 | modulo de ventas funcionando con formatos por establecimientos<br>
2021-04-27 : feature | #450 | plantilla de establecimiento funcionando en cotizaciones (recorriendo todos los documentos para que tomen esta configuracion)<br>
2021-04-27 : feature | #450 | plantilla de establecimiento funcionando en oportunidades de venta (recorriendo todos los documentos para que tomen esta configuracion)<br>
2021-04-27 : feature | #450 | plantilla de establecimiento funcionando en notas de venta (recorriendo todos los documentos para que tomen esta configuracion)<br>
2021-04-23 : feature | #450 | guardando plantilla por establecimiento, funcionando solo en documents (guias notas y demas en proceso)<br>
2021-04-22 : feature | #450 | campo en establecimiento para formato pdf, consulta en vista del formato actual por establecimiento (en proceso)<br>


## 4.0.7

### docs
2021-04-12 : docs | changelog<br>


### fixed
2021-04-21 : fixed | complie js, padding top nota de credito/debito<br>
2021-04-21 : fixed | condicion para editar precio en nota de credito<br>
2021-04-20 : fixed | #480 | Compile JS<br>
2021-04-20 : fixed | #480 | Lista de pedidos en tienda virtual: Se determina que description es el nombre del producto (desde creacion del item) por lo tanto, se cambia la etiqueta de la propiedad<br>
2021-04-19 : fixed | #432 | Filtro de productos, seleccion de solo item.<br>
2021-04-19 : fixed | #501 | Reporte de Ventas: Ajuste para alinear totales en descarga (pdf y excel)<br>
2021-04-18 : fixed | #436 | Editar precio en perfil vendedor - pos<br>
2021-04-18 : fixed | #367 | Pago de servicio tecnico en caja<br>
2021-04-16 : fixed | #425 | REPORTES: CONSISTENCIA DE DOCUMENTOS POR RANGO DE FECHAS: Se ajsuta la validacion para seleccionar por rango de fechas.<br>
2021-04-15 : fixed | #309 | TIENDA VIRTUAL: Al seleccionar la categoria, se mostrará resaltado y enviará correctamente a la categoria<br>
2021-04-15 : fixed | #480 | Tienda virtual: Pedidos: Añadiendo descripcion a la tabla de pedidos (lupa)<br>
2021-04-15 : fixed | #495 | NV desde POS: Se ajusta id en false, type_period y quantity_period se valida que existan para que no falle la creacion<br>
2021-04-15 : fixed | #449 | Api: Guia de Remision: Ejemplo para crear items al momento de enviar la guia: removiendo codigo_tipo_item<br>
2021-04-15 : fixed | #449 | Api: Guia de Remision: Ejemplo para crear items al momento de enviar la guia.<br>
2021-04-15 : fixed | #486 | POS: Nota de venta: Ajuste para mensaje por whatsapp<br>
2021-04-14 : fixed | #463 | visualizacion categorias en pos<br>
2021-04-14 : fixed | #491 | menu header comprobantes no enviados<br>
2021-04-14 : fixed | #485 | Compile JS<br>
2021-04-14 : fixed | #485 | Ingreso de Producto en inventario: Convirtiendo cantidad en numero para comparacion<br>
2021-04-14 : fixed | #485 | Ingreso de Producto en inventario: Convirtiendo cantidad en numero para comparacion<br>
2021-04-13 : fixed | #433 | Compile js<br>
2021-04-12 : fixed | #462 | datos visuales en letras blanco con modo oscuro<br>
2021-04-08 : fixed | #433 | Filtro para buscar por numero de guia en Reportes -> Ventas -> Documentos<br>
2021-04-08 : fixed | #433 | Filtro para buscar por numero de guia en Ventas -> Listado de Comprobantes<br>
2021-04-07 : fixed | #449 | Api: Guia de Remision: Ejemplo para crear items al momento de enviar la guia<br>

### feature
2021-04-14 : feature | i-367 | EMITIR FACTURA DESDE UN SERVICIO DE SOPORTE TÉCNICO<br>

## 4.0.6

### docs
2021-03-24 : docs | changelog<br>


### fixed
2021-04-12 : fixed | #477 | Editar Producto: Fecha de vencimiento en formato Y-m-d H:i:s<br>
2021-04-12 : fixed | #477 | Editar Item: Fecha de vencimiento en formato Y-m-d H:i:s<br>
2021-04-12 : fixed | #477 | Editar Item: Ajuste para guardar la fecha de vencimiento como fecha y no como array<br>
2021-04-12 : fixed | #476 | Ajuste de validacion de configuracion ( #432 ) en el modelo y aplicado a la busqueda<br>
2021-04-09 : fixed | #390 | Packs: Edicion/Nuevo pack, muestra el total y se puede modificar la cantidad de los items que lo componen<br>
2021-04-09 : fixed | #432 | mejoras en la lista de productos, evita duplicados por stock si no se tiene la configuracion habilitada<br>
2021-04-08 : fixed | #475 | GUIA DE REMISION - Cambio de nomenclatura por migracion<br>
2021-04-07 : fixed | #441 | Nota de venta: Ajuste para añadir formas de pago Credito y Contado. Similar a Cotizacion<br>
2021-04-07 : fixed | #441 | Cotizacion: Ajuste de diseño en los campos de fecha y total.<br>
2021-04-07 : fixed | #441 | Cotizacion: Modal al 50%, Se muestra la moneda (currency_type_id) y el total<br>
2021-04-07 : fixed | #441 | Cotizacion: Al momento de generar el comprobante, se selecciona credito o contado. Default Contado.<br>
2021-04-07 : fixed | #432 | Ajuste de texto en el sidebar<br>
2021-04-07 : fixed | #432 | Ajuste de estilo de codigo.<br>
2021-04-06 : fixed | #435 | Ajuste para guardar la fecha en formato Y-m-d y no un array<br>
2021-04-06 : fixed | #393 | error de texto azanvado x avanzado<br>
2021-04-05 : fixed | #414 #301 #277 | Añadido filtro por marcas y categorias como opcional. Ajuste para ordenar por nombre de item<br>
2021-04-03 : fixed | i-418 | Detalle del Item al Generar FACTURA de una Cotización no se Copia<br>
2021-04-03 : fixed | carga de items en movimiento de inventario<br>
2021-04-03 : fixed | carga de items en movimiento de inventario<br>
2021-04-03 : fixed | carga de items en movimiento de inventario<br>
2021-04-03 : fixed | carga de items en movimiento de inventario<br>
2021-04-03 : fixed | i-395 | error en la busqueda al momento de ingresar en el modulo de movimiento<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-03-31 : fixed | i-1 | Removiendo "ELECTRÓNICA" de "GUIA DE REMISIÓN REMITENTE ELECTRÓNICA"<br>
2021-03-31 : fixed | #448 | stock 0 para servicios<br>
2021-03-31 : fixed | #458 | boton permite duplicados<br>
2021-03-30 : fixed | campos giro grifo activo form de venta<br>
2021-03-30 : fixed | campos de informacion adicional en formulario de venta<br>
2021-03-30 : fixed | observaciones en formulario de venta<br>
2021-03-30 : fixed | t-373 | Corregir edición items en cotizaciones luego de ser generadas, así como en ventas<br>
2021-03-29 : fixed | direcciones de cliente en form de venta<br>
2021-03-29 : fixed | direcciones de cliente en form de venta<br>
2021-03-29 : fixed | direcciones de cliente en formulario de venta<br>
2021-03-26 : fixed | #387 | solución al error en reporte de caja, montos a credito no aparecian<br>
2021-03-25 : fixed | js<br>
2021-03-25 : fixed | compilado<br>
2021-03-25 : fixed | #420 | cierre inesperado del popup de pagos en notas de venta, solucionado<br>
2021-03-25 : fixed | #327 | bug de permisos, usuarios secundarios pueden entrar a modulos no asignados, reparado}<br>
2021-03-25 : fixed | #233 | se eliminó la duplicidad del nombre del vendedor en los pdfs de cotizaciones<br>
2021-03-24 : fixed | #327 | se ocultaron los modules y componentes no otorgados a la empresa al momento de editar un usuario secundario<br>
2021-03-24 : fixed | #306 | se corrigieron los detalles del buscador de clientes en el popup de generar cpe desde multiples guías y se agrego el número del comprobante en la plantilla blank<br>


### feature
2021-04-11 : feature | #233 | se agrego el cuadro para cambiar la fecha de pago cuando se genera un comprobante a partir de una cotización<br>
2021-04-08 : feature | cambio de url para consulta interna la sunat<br>
2021-04-07 : feature | #439 | se agregó el modod cargando en las opciones de los cpe<br>
2021-04-06 : feature | detalles dashboard<br>
2021-04-06 : feature | #443 | Ajuste para nota de venta, Copia de default y ajuste para orden visual de las cuentas<br>
2021-04-06 : feature | #443 | Plantilla PDF basada en default3_new para añadir las cuentas bancarias al final de la factura a4<br>
2021-04-05 : feature | #390 | poder editar la cantidad del producto luego de creado<br>
2021-04-05 : feature | i-436 | Agregó configuración: Permitir editar precio unitario a vendedores<br>
2021-04-05 : feature | i-367 | REQUERIMIENTO PARA PODER EMITIR FACTURA DESDE UN SERVICIO DE SOPORTE TÉCNICO<br>
2021-04-05 : feature | #313 | se agregó la columna cantidad a los cpe generados desde nota de venta<br>
2021-04-05 : feature | #401 | mostrar el precio de venta de producto al editar el precio en las compras<br>
2021-04-02 : feature | i-358 | REPORTE DE PRODUCTOS SIN STOCK Y CON STOCK MÍNIMO<br>
2021-04-01 : feature | #435 | Añadiendo modelo de factura con fecha de vencimiento por producto. Se quita Serie para fecha de vencimiento<br>
2021-04-01 : feature | #285 | módulos y componentes controlados desde el superadmin<br>
2021-03-31 : feature | #313 | generar un cpe desde múltiples notas de ventas. listo<br>
2021-03-31 : feature | #401 | opción de editar el precio de venta de un producto desde las compras fue aadido<br>
2021-03-31 : feature | #423 | template pedido sin mostrar igv<br>
2021-03-29 : feature | #391 | se agregaron los campos tipos de usuario y vendedor y categoría de los productos en el reporte general del productos<br>
2021-03-29 : feature | rediseño posiciones en formulario de venta<br>
2021-03-25 : feature | diseño formulario venta<br>
2021-03-25 : feature | condicion de pago<br>
2021-03-25 : feature | arreglos de diseño sidebar, colores e iconos<br>
2021-03-25 : feature | json retorna url de imagen a apk<br>


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
