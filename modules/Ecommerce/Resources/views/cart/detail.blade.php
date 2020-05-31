@extends('ecommerce::layouts.layout_ecommerce_cart.index')
@section('content')

<div class="row" id="app">
    <div class="col-lg-8">
        <div class="cart-table-container">

            <table class="table table-cart">
                <thead>
                    <tr>
                        <th class="product-col">Producto</th>
                        <th class="price-col">Precio</th>
                        <th class="qty-col">Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in records" class="product-row">
                        <td class="product-col">
                            <figure class="product-image-container">
                                <a href="#" class="product-image">
                                    <img :src=" '/storage/uploads/items/' + row.image" alt="product">
                                </a>
                            </figure>
                            <h2 class="product-title">
                                <a href="#">@{{ row.name }}</a>
                            </h2>
                        </td>
                        <td>@{{ row.currency_type.symbol }} @{{ row.sale_unit_price }}</td>
                        <td>
                            <input class="vertical-quantity form-control input_quantity" :data-product="row.id"
                                type="text">
                        </td>
                        <td>S/ @{{ row.sub_total }}</td>
                        <td>
                            <button type="button" @click="deleteItem(row.id, index)"
                                class="btn btn-outline-danger btn-sm"><i class="icon-cancel"></i></button>
                        </td>
                    </tr>

                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4" class="clearfix">
                            <div class="float-left">
                                <a href="/ecommerce" class="btn btn-outline-secondary">Continuar Comprando</a>
                            </div><!-- End .float-left -->

                            <div class="float-right">
                                <a href="#" @click="clearShoppingCart"
                                    class="btn btn-outline-secondary btn-clear-cart">Limpiar Carrito</a>
                                <!--<a href="#" class="btn btn-outline-secondary btn-update-cart">Update Shopping Cart</a> -->
                            </div><!-- End .float-right -->
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div><!-- End .cart-table-container -->
    </div><!-- End .col-lg-8 -->

    <div class="col-lg-4">
        <div class="cart-summary">
            <h3>Resumen</h3>
            <table class="table table-totals">
                <tbody>

                    <tr v-if="summary.total_exonerated > 0">
                        <td>OP.EXONERADAS</td>
                        <td>S/ @{{ summary.total_exonerated }}</td>
                    </tr>
                    <tr v-if="summary.total_taxed > 0">
                        <td>OP.GRAVADA</td>
                        <td>S/ @{{ summary.total_taxed }}</td>
                    </tr>
                    <tr v-if="summary.total_igv > 0">
                        <td>IGV</td>
                        <td>S/ @{{ summary.total_igv }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Orden Total</td>
                        <td>S/ @{{summary.total}}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="checkout-methods text-center">

                @guest
                <a href="{{route('tenant_ecommerce_login')}}" class="btn btn-block btn-sm btn-primary login-link culqi">Pagar
                    con VISA</a>
                <a href="{{route('tenant_ecommerce_login')}}" class="btn btn-block btn-sm btn-primary login-link">Pagar
                    con EFECTIVO</a>
                <a style="margin-left:15%" href="{{route('tenant_ecommerce_login')}}"
                    class="btn btn-block btn-sm login-link">
                    <img src="{{ asset('porto-ecommerce/assets/images/btn_buynowCC_LG.gif') }}" alt="">
                </a>

                @else
                <button class="btn btn-block btn-sm btn-primary culqi" onclick="execCulqi()"> Pagar con VISA </button>

                <button @click="payment_cash.clicked = !payment_cash.clicked" class="btn btn-block btn-sm btn-primary">
                    Pagar con EFECTIVO </button>
                <div v-show="payment_cash.clicked" style="margin: 3%" class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">S/</span>
                        </div>
                        <input readonly placeholder="0.0" v-model="payment_cash.amount" type="text"
                            onkeypress="return isNumberKey(event)" maxlength="14" class="form-control"
                            aria-label="Amount">
                        <button @click="paymentCash" class="btn btn-success">OK!</button>
                    </div>
                </div>


                @if($information->script_paypal)

                    {!!html_entity_decode($information->script_paypal)!!}

                @endif


                @endguest

            </div><!-- End .checkout-methods -->
        </div><!-- End .cart-summary -->


        <div class="cart-summary">
            <h3>Datos de contacto y envío</h3>

            <form autocomplete="off" action="#">
                <div class="form-group" :class="{'text-danger': errors.telephone}">
                    <label for="email">Teléfono:</label>
                    <input v-model="form_contact.telephone" type="text" autocomplete="off" class="form-control" placeholder="Ingrese número de teléfono" name="teléfono">
                    <small class="form-control-feedback" v-if="errors.telephone" v-text="errors.telephone[0]"></small>
                </div>
                <div class="form-group" :class="{'text-danger': errors.address}">
                    <label for="email">Dirección:</label>
                    <textarea v-model="form_contact.address" class="form-control" placeholder="Ingrese dirección de envío" rows="2" cols="10"></textarea>
                    <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                </div>
            </form>
        </div>
    </div><!-- End .col-lg-4 -->




    <div class="modal fade" id="modal_ask_document" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalCenterTitle">Generar Comprobante Electronico</h2>

                </div>
                <div class="modal-body">
                    <h3>La Transacción de se realizó correctamente.</h3>
                    <h4>¿ Desea generar un comprobante y enviarlo a su email ? </h4> <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="checkDocument('6')">SI, FACTURA</button>
                    <button type="button" class="btn btn-primary" @click="checkDocument('1')">SI, BOLETA
                        ELECTRONICA</button>
                    <button type="button" class="btn btn-secondary" @click="redirectHome" data-dismiss="modal">No,
                        NINGUNA</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_identity_document" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Datos Generales para el Comprobante</h3>
                </div>
                <div class="modal-body">

                    <form>
                        <div class="form-group">
                            <label class="control-label">Tipo Doc. Identidad <span class="text-danger">*</span></label>
                            <select class="form-control" :disabled="formIdentity.identity_document_type_id == '6'"
                                v-model="formIdentity.identity_document_type_id">
                                <option v-for="option in identity_document_types" :value="option.id"
                                    :label="option.description"></option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label class="control-label">Ingrese Número <span> (Se debe validar el numero ingresado)</span> <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" v-model="formIdentity.number"
                                    :maxlength="maxLength" aria-label="Recipient's username"
                                    aria-describedby="button-addon2">
                                <div class="input-group-append">

                                    <button  :disabled="!formIdentity.number" @click.prevent="searchCustomer"
                                        class="btn btn-outline-secondary" type="button" id="button-addon2">

                                        <template v-if="formIdentity.identity_document_type_id === '6'">
                                            <i class="icon-search"></i> <span>SUNAT @{{ text_search }}</span>
                                        </template>
                                        <template v-if="formIdentity.identity_document_type_id === '1'">
                                            <i class="icon-search"></i> <span>RENIEC @{{ text_search }}</span>
                                        </template>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                    <div v-show="response_search.message" class="alert"
                        :class="{'alert-danger' : !response_search.success, 'alert-success': response_search.success}"
                        role="alert">
                        @{{ response_search.message }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCELAR</button>
                    <button type="button" class="btn btn-primary" @click="sendDocument"
                        v-show="formIdentity.validate">ENVIAR</button>
                </div>
            </div>
        </div>
    </div>
</div><!-- End .row -->

<input type="hidden" id="total_amount" data-total="0.0">

@endsection

@push('scripts')
<!-- script src="https://checkout.culqi.com/js/v3"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.31.1/dist/sweetalert2.all.min.js"></script>
<script src="https://momentjs.com/downloads/moment.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script -->


<script type="text/javascript">
    var app_cart = new Vue({
        el: '#app',
        data: {
            form_contact: {
                address:   '',
                telephone:   '',
            },
            payment_cash: {
                amount: '',
                clicked: false
            },
            response_search: {},
            text_search: '',
            loading_search: false,
            identity_document_types: [{
                id: '1',
                description: 'DNI'
            }, {
                id: '6',
                description: 'RUC'
            }],
            formIdentity: {
                identity_document_type_id: '6'
            },
            records: [],
            records_old: [],
            order_generated: {},
            summary: {
                subtotal: '0.0',
                tax: '0.0',
                total: '0.0'
            },
            aux_totals: {},
            form_document: {},
            user: {},
            typeDocumentSelected: '',
            response_order_total:0,
            errors: {},
            exchange_rate_sale: ''
        },
        computed: {
            maxLength: function () {
                if (this.formIdentity.identity_document_type_id === '6') {
                    return 11
                }
                if (this.formIdentity.identity_document_type_id === '1') {
                    return 8
                }
            }
        },
        async mounted() {
          await this.changeExchangeRate(moment().format("YYYY-MM-DD"))

          let exchange_rate_sale = this.exchange_rate_sale
          let contex = this

          $(".input_quantity").change(function (e) {
            let value = parseFloat($(this).val())
            let id = $(this).data('product')
            let row = contex.records.find(x => x.id == id)

            if(row.currency_type_id === 'USD') {
              row.sub_total = ((parseFloat(row.sale_unit_price) * value) * exchange_rate_sale).toFixed(2)
            } else {
              row.sub_total = (parseFloat(row.sale_unit_price) * value).toFixed(2)
            }

            row.cantidad = value
            contex.calculateSummary()
          })

          this.records.forEach(function (item) {
            if(item.currency_type_id === 'USD') {
              item.sub_total = (parseFloat(item.sub_total) * exchange_rate_sale).toFixed(2)
              item.exchange_rate_sale = exchange_rate_sale
            }
            item.sale_unit_price = parseFloat(item.sale_unit_price).toFixed(2)
          })

          this.calculateSummary()
        },
        created() {
            let array = localStorage.getItem('products_cart');
            array = JSON.parse(array)
            if (array) {
                this.records = array.map(function (item) {
                    let obj = item
                    obj.cantidad = 1
                    obj.sub_total = parseFloat(item.sale_unit_price).toFixed(2)
                    obj.exchange_rate_sale = ''
                    return obj
                })
            }
            // console.log(this.records)
            this.initForm();

        },
        methods: {
          async changeExchangeRate(exchange_rate_date){
            var response = await axios.get(`/exchange_rate/ecommence/${exchange_rate_date}`)
            this.exchange_rate_sale = parseFloat(response.data.sale)
          },
            getFormPaymentCash() {
              this.form_document.datos_del_cliente_o_receptor.direccion = this.form_contact.address
              this.form_document.datos_del_cliente_o_receptor.telefono = this.form_contact.telephone
                let precio = Math.round(Number(this.summary.total) * 100).toFixed(2);
                let precio_culqi = Number(this.summary.total)
                return {
                    producto: 'Compras Ecommerce Facturador Pro',
                    precio: precio,
                    precio_culqi: precio_culqi,
                    customer: this.form_document.datos_del_cliente_o_receptor,
                    items: this.records,
                    telephone: this.form_contact.telephone,
                    address: this.form_contact.address
                }
            },
            async paymentCash() {
              // verifica si tiene productos seleccionado
              let product = JSON.parse(localStorage.getItem('products_cart'));

              if (product.length < 1){
                swal({
                    title: "No se han encontrado productos",
                    text: "Por favor seleccione algún producto de la tienda.",
                    type: "error"
                })
                return
              }

                swal({
                    title: "Estamos generando el Pago.",
                    text: `Por favor no cierre esta ventana hasta que el proceso termine.`,
                    focusConfirm: false,
                    onOpen: () => {
                        Swal.showLoading()
                    }
                });

                let url_finally = '{{ route("tenant_ecommerce_payment_cash")}}';
                let response = await axios.post(url_finally, this.getFormPaymentCash(), this.getHeaderConfig()).then(response => {
                if (response.data.success) {
                    this.saveContactDataUser()
                    this.clearShoppingCart()
                    this.response_order_total = response.data.order.total
                    swal({
                        title: "Gracias por su pago!",
                        text: "En breve le enviaremos un correo electronico con los detalles de su compra.",
                        type: "success"
                    }).then((x) => {
                      app_cart.order_generated = order
                        //askedDocument(response.data.order);
                    })
                }
              }).catch(error => {
                swal("Pago No realizado", 'Sucedio algo inesperado.', "error");
                if (error.response.status === 422) {
                  this.errors = error.response.data;
                } else {
                  console.log(error);
                }
              });

            },
            redirectHome() {
                window.location = "{{ route('tenant.ecommerce.index') }}";
            },
            async searchCustomer() {
                this.text_search = 'Buscando...'
                this.response_search = {
                    succes: false,
                    message: ''
                }
                let identity_document_type_name = ''
                if (this.formIdentity.identity_document_type_id === '6') {
                    identity_document_type_name = 'ruc'
                }
                if (this.formIdentity.identity_document_type_id === '1') {
                    identity_document_type_name = 'dni'
                }

                let response = await axios.get(
                    `/services/${identity_document_type_name}/${this.formIdentity.number}`)

                if (response.data.success) {
                    this.response_search.success = response.data.success
                    this.response_search.message = 'Datos Encontrados (Ahora puede enviar su comprobante.)'
                    // let data = response.data.data
                    this.formIdentity.validate = true
                    this.form_document.datos_del_cliente_o_receptor.codigo_tipo_documento_identidad = this
                        .formIdentity.identity_document_type_id
                    this.form_document.datos_del_cliente_o_receptor.numero_documento = this.formIdentity
                        .number
                    /* this.form.name = data.name
                     this.form.trade_name = data.trade_name
                     this.form.address = data.address
                     this.form.department_id = data.department_id
                     this.form.province_id = data.province_id
                     this.form.district_id = data.district_id
                     this.form.phone = data.phone*/
                } else {
                    this.response_search.success = response.data.success
                    this.response_search.message = response.data.message

                    this.form_document.datos_del_cliente_o_receptor.codigo_tipo_documento_identidad = "0"
                    this.form_document.datos_del_cliente_o_receptor.numero_documento = "0"
                }

                this.text_search = ''

            },
            getHeaderConfig() {
                let token = this.user.api_token
                let axiosConfig = {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`
                    }
                };
                return axiosConfig;
            },
            checkDocument(typeDocument) {

                $('#modal_ask_document').modal('hide');

                this.formIdentity.identity_document_type_id = typeDocument

                $('#modal_identity_document').modal('show');
                //this.typeDocumentSelected = typeDocument
                //let total = parseFloat(this.response_order_total)
                // console.log(total, this.response_order_total)

                /*if (typeDocument == '6') {
                    let tipoDocumento = this.user.identity_document_type_id
                    let number = this.user.number
                    // console.log(this.user)

                    // if (!tipoDocumento || !number || number.length !== 11) {
                    $('#modal_identity_document').modal('show');
                    // } else {
                    //     this.form_document.datos_del_cliente_o_receptor.codigo_tipo_documento_identidad =
                    //         tipoDocumento
                    //     this.form_document.datos_del_cliente_o_receptor.numero_documento = number
                    //     this.sendDocument()
                    // }

                } else {

                    if(total > 700){

                        $('#modal_identity_document').modal('show');

                    }else{

                        this.sendDocument()
                    }

                }*/

            },
            finallyProcess(form) {
                let url_finally = '{{ route("tenant_ecommerce_transaction_finally")}}';
                axios.post(url_finally, form, this.getHeaderConfig())
                    .then(response => {
                        console.log(response)
                        console.log('transaccion finalizada correctamente')
                        swal({
                            title: "Gracias por su pago!",
                            text: "La Transacción de su compra se finalizó correctamente. El Comprobante y detalle de su compra se envió a su correo.",
                            type: "success"
                        }).then((x) => {
                            this.redirectHome()
                        })
                    })
                    .catch(error => {
                        console.log(error)
                        console.log('error al finalizar la transaccion')
                    });

            },
            async sendDocument() {

                $('#modal_ask_document').modal('hide');
                $('#modal_identity_document').modal('hide');

                swal({
                    title: "Estamos enviando el Comprobante a su Email",
                    text: `Por favor no cierre esta ventana hasta que el proceso termine.`,
                    focusConfirm: false,
                    onOpen: () => {
                        Swal.showLoading()
                    }
                });
                let doc = await this.getDocument()
               // console.log(doc)
                // return
                await axios.post('/api/documents', doc, this.getHeaderConfig())
                    .then(response => {
                       // console.log('documento generado correctamente')
                        this.finallyProcess(this.getDataFinally(response.data))
                        this.initForm()
                    })
                    .catch(error => {
                      // console.log(error)
                        //console.log('error al generar documento')
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Sucedió un error al generar el comprobante electronico!',
                        }).then((x) => {
                            window.location = "{{ route('tenant.ecommerce.index') }}";
                        })

                    });

            },
            getDataFinally(document) {
                return {
                    document_external_id: document.data.external_id,
                    number_document: document.number,
                    orderId: this.order_generated.id,
                    product: 'Compras Ecommerce Facturador Pro',
                    precio_culqi: this.summary.total,
                    identity_document_type_id: this.formIdentity.identity_document_type_id,
                    number: this.formIdentity.number,

                }
            },
            async getDocument() {
                this.form_document.items = await this.getItemsDocument()
                this.form_document.totales = await this.getTotales()

                if (this.formIdentity.identity_document_type_id === '6') {
                    this.form_document.serie_documento = 'F001'
                    this.form_document.codigo_tipo_documento = '01'
                }
                if (this.formIdentity.identity_document_type_id === '1') {
                    this.form_document.serie_documento = 'B001'
                    this.form_document.codigo_tipo_documento = '03'
                }
                return this.form_document
            },
            async getTotales() {

                let totals = await {
                    "total_exportacion": 0.00,
                    "total_operaciones_gravadas": this.aux_totals.total_taxed,
                    "total_operaciones_inafectas": 0.00,
                    "total_operaciones_exoneradas": this.aux_totals.total_exonerated,
                    "total_operaciones_gratuitas": 0.00,
                    "total_igv": this.aux_totals.total_igv,
                    "total_impuestos": this.aux_totals.total_igv,
                    "total_valor": this.aux_totals.total_value,
                    "total_venta": this.aux_totals.total
                }

                return totals
            },
            async getItemsDocument() {

                let rec = await this.records_old.map((item) => {

                    let sale_unit_price = 0
                    let total_exonerated = 0
                    let total_igv = 0
                    let total_val = 0
                    let total = 0
                    let percentage_igv = 18

                    if (item.sale_affectation_igv_type_id === '10') {

                        unit_value = item.sale_unit_price / (1 + percentage_igv / 100)
                        total_igv = item.cantidad * parseFloat(item.sale_unit_price - unit_value)
                        total = (item.cantidad * item.sale_unit_price)
                        sale_unit_price = parseFloat(item.sale_unit_price)
                        total_val = (unit_value * item.cantidad)

                        return {
                            "codigo_interno": (item.internal_id) ? item.internal_id:"",
                            "descripcion": item.description,
                            "codigo_producto_sunat": "",
                            "unidad_de_medida": item.unit_type_id,
                            "cantidad": item.cantidad,
                            "valor_unitario": unit_value,
                            "codigo_tipo_precio": "01",
                            "precio_unitario": sale_unit_price,
                            "codigo_tipo_afectacion_igv": "10",
                            "total_base_igv": total_val,
                            "porcentaje_igv": percentage_igv,
                            "total_igv": total_igv,
                            "total_impuestos": total_igv,
                            "total_valor_item": total_val,
                            "total_item": total
                        }

                    }

                    if (item.sale_affectation_igv_type_id === '20') {

                        unit_value = parseFloat(item.sale_unit_price)
                        total_igv = 0
                        total = (parseFloat(item.cantidad) * parseFloat(item.sale_unit_price))
                        sale_unit_price = parseFloat(item.sale_unit_price)
                        total_val = (parseFloat(unit_value) * parseFloat(item.cantidad))

                        return {
                            "codigo_interno": (item.internal_id) ? item.internal_id:"",
                            "descripcion": item.description,
                            "codigo_producto_sunat": "",
                            "unidad_de_medida": item.unit_type_id,
                            "cantidad": item.cantidad,
                            "valor_unitario": unit_value,
                            "codigo_tipo_precio": "01",
                            "precio_unitario": sale_unit_price,
                            "codigo_tipo_afectacion_igv": "20",
                            "total_base_igv": total_val,
                            "porcentaje_igv": percentage_igv,
                            "total_igv": 0,
                            "total_impuestos": 0,
                            "total_valor_item": total_val,
                            "total_item": total
                        }

                    }

                })

                return rec
            },
            initForm() {
              this.errors = {}
                this.user = JSON.parse('{!! json_encode( Auth::user() ) !!}')
                if(!this.user){
                    return false
                }

                this.form_document = {
                    "acciones": {
                        "enviar_email": true,
                        "formato_pdf": "a4"
                    },
                    "serie_documento": "",
                    "numero_documento": "#",
                    "fecha_de_emision": moment().format('YYYY-MM-DD'),
                    "hora_de_emision": moment().format('HH:mm:ss'),
                    "codigo_tipo_operacion": "0101",
                    "codigo_tipo_documento": "01",
                    "codigo_tipo_moneda": "PEN",
                    "fecha_de_vencimiento": moment().format('YYYY-MM-DD'),
                    "datos_del_cliente_o_receptor": {
                        "codigo_tipo_documento_identidad": "0",
                        "numero_documento": "0",
                        "apellidos_y_nombres_o_razon_social": this.user.name,
                        "codigo_pais": "PE",
                        "ubigeo": "150101",
                        "direccion": this.user.address,
                        "correo_electronico": this.user.email,
                        "telefono": this.user.telephone
                    },
                    "totales": {},
                    "items": [],
                }


                this.formIdentity = {
                    identity_document_type_id: '6'
                }

                this.form_contact.address =  this.user.address
                this.form_contact.telephone =  this.user.telephone


            },
            deleteItem(id, index) {
                //remove en fronted
                this.records.splice(index, 1)
                //set remove en localstorage
                let array = localStorage.getItem('products_cart');
                array = JSON.parse(array);
                let indexFound = array.findIndex(x => x.id == id)
                array.splice(indexFound, 1);
                localStorage.setItem('products_cart', JSON.stringify(array));

                this.calculateSummary()


            },
            clearShoppingCart() {
              this.errors = {}
                this.records_old = this.records
                this.records = []
                localStorage.setItem('products_cart', JSON.stringify([]))
                // this.calculateSummary()

                this.summary = {
                    subtotal: '0.0',
                    tax: '0.0',
                    total: '0.00',
                    total_taxed: '0.0',
                    total_value: '0.0',
                    total_exonerated: '0.0',
                    total_igv: '0.0'
                }
                this.payment_cash.amount = '0.00'
                location.reload()
            },
            calculateSummary() {

                //let subtotal = 0.00
                let total_taxed = 0
                let total_value = 0
                let total_exonerated = 0
                let total_igv = 0
                let total = 0

                this.records.forEach(function (item) {

                    //subtotal += parseFloat(item.sub_total)

                    let unit_price = item.sub_total
                    let unit_value = unit_price
                    let percentage_igv = 18

                    if (item.sale_affectation_igv_type_id === '10') {
                        unit_value = item.sub_total / (1 + percentage_igv / 100)
                        total_taxed += parseFloat(unit_value)
                        total_igv += parseFloat(unit_price - unit_value)
                    }
                    if (item.sale_affectation_igv_type_id === '20') {
                        total_exonerated += parseFloat(unit_value)
                    }

                    total_value = total_taxed + total_exonerated
                    total += parseFloat(unit_price)
                })

                // console.log(total_taxed, total_exonerated, total_igv)

                this.summary.total_taxed = total_taxed.toFixed(2)
                this.summary.total_exonerated = total_exonerated.toFixed(2)
                this.summary.total_igv = total_igv.toFixed(2)
                this.summary.total_value = total_value.toFixed(2)
                this.summary.total = total.toFixed(2)
                this.aux_totals = this.summary
                // console.log(this.summary)


                $("#total_amount").data('total', this.summary.total);

                this.payment_cash.amount = this.summary.total;

                // let x =
                // console.log(x)

                // let subtotal = 0.00
                // this.records.forEach(function (item) {
                //     //console.log(item)
                //     subtotal += parseFloat(item.sub_total)
                // })

                // this.summary.subtotal = subtotal.toFixed(2)
                // let tax = (subtotal * 0.18)
                // this.summary.tax = tax.toFixed(2)
                // this.summary.total = (subtotal + tax).toFixed(2)
                // $("#total_amount").data('total', this.summary.total);

                // this.payment_cash.amount = this.summary.total
            },
            saveContactDataUser()
            {
                let url_finally = '{{ route("tenant_ecommerce_user_data")}}';
                axios.post(url_finally, this.form_contact, this.getHeaderConfig())
                    .then(response => {
                       console.log(response.data)
                    })
                    .catch(error => {

                    });
            }
        }
    })

</script>

<script>
    Culqi.publicKey = {!! json_encode($configuration->token_public_culqui ) !!};
    if(!Culqi.publicKey)
    {
      $('.culqi').hide()
/*
        swal({
            title: "Culqi configuración",
            text: "El pago con visa aun no esta disponible. Intente con efectivo.",
            type: "error",
            position: 'top-end',
            icon: 'warning',
        })
*/
    }
    Culqi.options({
        installments: true
    });

    async function askedDocument(order) {
        app_cart.order_generated = order
        $('#modal_ask_document').modal('show')
    }

    function execCulqi() {

        let precio = Math.round((Number($("#total_amount").data('total')) * 100).toFixed(2));
        if (precio > 0) {
            Culqi.settings({
                title: "Productos Ecommerce",
                currency: 'PEN',
                description: 'Compras Ecommerce Facturador Pro',
                amount: precio
            });
            Culqi.open();
        }
    }


    function culqi() {
        if (Culqi.token) {

            swal({
                title: "Estamos hablando con su banco",
                text: `Por favor no cierre esta ventana hasta que el proceso termine.`,
                focusConfirm: false,
                onOpen: () => {
                    Swal.showLoading()
                }
            });

            let precio = Math.round((Number($("#total_amount").data('total')).toFixed(2) * 100));
            let precio_culqi = Number($("#total_amount").data('total')).toFixed(2);

            var url = "/culqi";
            var token = Culqi.token.id;
            var email = Culqi.token.email;
            var installments = Culqi.token.metadata.installments;
            var data = {
                producto: 'Compras Ecommerce Facturador Pro',
                precio: precio,
                precio_culqi: precio_culqi,
                token: token,
                email: email,
                installments: installments,
                customer: JSON.stringify(getCustomer()),
                items: JSON.stringify(getItems())
            }

            $.ajax({
              url: "{{route('tenant_ecommerce_culqui')}}",
              method: 'post',
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data: data,
              dataType: 'JSON',
              success: function (data) {
                if (data.success == true) {
                  app_cart.saveContactDataUser();
                  app_cart.clearShoppingCart();
                  swal({
                    title: "Gracias por su pago!",
                    text: "En breve le enviaremos un correo electronico con los detalles de su compra.",
                    type: "success"
                  }).then((x) => {
                    askedDocument(data.order);
                    //window.location = "{{ route('tenant.ecommerce.index') }}";
                  })
                } else {
                  const message = data.message
                  swal("Pago No realizado", message, "error");
                }
              },
              error: function (error_data) {
                swal("Pago No realizado", error_data, "error");
              }
            });

        } else {
            console.log(Culqi.error);
            swal("Pago No realizado", Culqi.error.user_message, "error");
        }
    };

    function getCustomer() {
        let user = JSON.parse('{!! json_encode( Auth::user() ) !!}')
        return {
            "codigo_tipo_documento_identidad": "0",
            "numero_documento": "0",
            "apellidos_y_nombres_o_razon_social": user.name,
            "codigo_pais": "PE",
            "ubigeo": "150101",
            "direccion": app_cart.user.address,
            "correo_electronico": user.email,
            "telefono": app_cart.user.telephone
        }
    }

    function getItems() {
        return app_cart.records
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 &&
            (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

</script>

@endpush
