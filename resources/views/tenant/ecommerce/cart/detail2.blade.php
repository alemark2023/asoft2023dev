@extends('tenant.layouts.layout_ecommerce_cart.index')
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
                        <td>S/ @{{ row.sale_unit_price }}</td>
                        <td>
                            <input class="vertical-quantity form-control input_quantity" :data-product="row.id"
                                type="text">
                        </td>
                        <td>@{{ row.sub_total }}</td>
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
                                <a href="/ecommerce" class="btn btn-outline-secondary">Continar Comprando</a>
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

        <!-- <div class="cart-discount">
            <h4>Apply Discount Code</h4>
            <form action="#">
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" placeholder="Enter discount code" required>
                    <div class="input-group-append">
                        <button class="btn btn-sm btn-primary" type="submit">Apply Discount</button>
                    </div>
                </div>
            </form>
        </div> -->
    </div><!-- End .col-lg-8 -->

    <div class="col-lg-4">
        <div class="cart-summary">
            <h3>Resumen</h3>

            {{--<h4>
                <a data-toggle="collapse" href="#total-estimate-section" class="collapsed" role="button"
                    aria-expanded="false" aria-controls="total-estimate-section">Estimate Shipping and Tax</a>
            </h4>

            <div class="collapse" id="total-estimate-section">
                <form action="#">
                    <div class="form-group form-group-sm">
                        <label>Country</label>
                        <div class="select-custom">
                            <select class="form-control form-control-sm">
                                <option value="USA">United States</option>
                                <option value="Turkey">Turkey</option>
                                <option value="China">China</option>
                                <option value="Germany">Germany</option>
                            </select>
                        </div><!-- End .select-custom -->
                    </div><!-- End .form-group -->

                    <div class="form-group form-group-sm">
                        <label>State/Province</label>
                        <div class="select-custom">
                            <select class="form-control form-control-sm">
                                <option value="CA">California</option>
                                <option value="TX">Texas</option>
                            </select>
                        </div><!-- End .select-custom -->
                    </div><!-- End .form-group -->

                    <div class="form-group form-group-sm">
                        <label>Zip/Postal Code</label>
                        <input type="text" class="form-control form-control-sm">
                    </div><!-- End .form-group -->

                    <div class="form-group form-group-custom-control">
                        <label>Flat Way</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="flat-rate">
                            <label class="custom-control-label" for="flat-rate">Fixed $5.00</label>
                        </div><!-- End .custom-checkbox -->
                    </div><!-- End .form-group -->

                    <div class="form-group form-group-custom-control">
                        <label>Best Rate</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="best-rate">
                            <label class="custom-control-label" for="best-rate">Table Rate $15.00</label>
                        </div><!-- End .custom-checkbox -->
                    </div><!-- End .form-group -->
                </form>
            </div> --><!-- End #total-estimate-section -->  --}}

            <table class="table table-totals">
                <tbody>
                    <tr>
                        <td>Subtotal</td>
                        <td>S/ @{{summary.subtotal}}</td>
                    </tr>

                    <tr>
                        <td>IGV</td>
                        <td>S/ @{{summary.tax}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Order Total</td>
                        <td>S/ @{{summary.total}}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="checkout-methods">

                @guest
                <a href="{{route('tenant_ecommerce_login')}}" class="btn btn-block btn-sm btn-primary login-link">Pagar
                    con VISA</a>
                @else
                <button class="btn btn-block btn-sm btn-primary" onclick="execCulqi"> Pagar con VISA </button>

                <form class="btn btn-block btn-sm " action="https://www.paypal.com/cgi-bin/webscr" method="post"
                    target="_blank">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="cristian.ballon@gmail.com">
                    <input type="hidden" name="lc" value="AL">
                    <input type="hidden" name="item_name" value="compras ecommerce">
                    <input type="hidden" name="item_number" value="0001">
                    <input type="hidden" name="button_subtype" value="services">
                    <input type="hidden" name="no_note" value="0">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0"
                        name="submit" alt="PayPal - The safer, easier way to pay online!">
                    <img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1"
                        height="1">
                </form>
                @endguest



            </div><!-- End .checkout-methods -->
        </div><!-- End .cart-summary -->
    </div><!-- End .col-lg-4 -->
</div><!-- End .row -->

<input type="hidden" id="total_amount" data-total="0.0">

@endsection

@push('scripts')
<script src="https://checkout.culqi.com/js/v3"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.31.1/dist/sweetalert2.all.min.js"></script>
<script src="https://momentjs.com/downloads/moment.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>


<script type="text/javascript">
    var app_cart = new Vue({
        el: '#app',
        data: {
            records: [],
            summary: {
                subtotal: '0.0',
                tax: '0.0',
                total: '0.0'
            },
            form_document: {}
        },
        mounted() {

            Culqi.publicKey = 'pk_test_is5j26CmbQPQ6gFX';
            Culqi.options({
                installments: true
            });

            let contex = this
            $(".input_quantity").change(function (e) {
                let value = parseFloat($(this).val())
                let id = $(this).data('product')
                let row = contex.records.find(x => x.id == id)
                row.sub_total = (parseFloat(row.sale_unit_price) * value).toFixed(2)
                row.cantidad = value
                contex.calculateSummary()
            });
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
                    return obj
                })
            }
            this.initForm();
        },
        methods: {
            execCulqi()
            {

            },
            sendDocument(document, order) {
                swal({
                    title: "Estamos enviando el Comprobante a su Email",
                    text: `Por favor no cierre esta ventana hasta que el proceso termine.`,
                    focusConfirm: false,
                    onOpen: () => {
                        Swal.showLoading()
                    }
                });

                ///poner el request de envciar mail


                axios.post(url, contex.updMaster)
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        console.log(err)
                        location.reload();
                    });





                // fin ,logica


                swal({
                    title: "Gracias por su pago!",
                    text: "En breve le enviaremos un correo electronico con los detalles de su compra.",
                    type: "success"
                }).then((x) => {
                    window.location = "{{ route('tenant.ecommerce.index') }}";
                })

            },
            getDocument() {
                this.form_document.items = this.getItemsDocument()
                this.form_document.totales = this.getTotales()
                return this.form_document
            },
            getTotales() {
                return {
                    "total_exportacion": 0.00,
                    "total_operaciones_gravadas": 100.00,
                    "total_operaciones_inafectas": 0.00,
                    "total_operaciones_exoneradas": 0.00,
                    "total_operaciones_gratuitas": 0.00,
                    "total_igv": 18.00,
                    "total_impuestos": 18.00,
                    "total_valor": 100,
                    "total_venta": 118
                }
            },
            getItemsDocument() {

                return this.records.map((item) => {
                    return {
                        "codigo_interno": "0",
                        "descripcion": item.description,
                        "codigo_producto_sunat": "0",
                        "unidad_de_medida": "NIU",
                        "cantidad": item.cantidad,
                        "valor_unitario": 50,
                        "codigo_tipo_precio": "01",
                        "precio_unitario": item.sale_unit_price,
                        "codigo_tipo_afectacion_igv": "10",
                        "total_base_igv": 100.00,
                        "porcentaje_igv": 18,
                        "total_igv": 18,
                        "total_impuestos": 18,
                        "total_valor_item": 100,
                        "total_item": 118
                    }
                })

            },
            initForm() {
                let user = '{!! json_encode( Auth::user() ) !!}'
                this.form_document = {
                    "serie_documento": "F001",
                    "numero_documento": "#",
                    "fecha_de_emision": moment().format('YYYY-MM-DD'),
                    "hora_de_emision": moment().format('HH:mm:ss'),
                    "codigo_tipo_operacion": "0101",
                    "codigo_tipo_documento": "01",
                    "codigo_tipo_moneda": "PEN",
                    "fecha_de_vencimiento": moment().format('YYYY-MM-DD'),
                    "numero_orden_de_compra": "000001",
                    "datos_del_cliente_o_receptor": {
                        "codigo_tipo_documento_identidad": "6",
                        "numero_documento": "10414711225",
                        "apellidos_y_nombres_o_razon_social": user.name,
                        "codigo_pais": "PE",
                        "ubigeo": "150101",
                        "direccion": "Av. 2 de Mayo",
                        "correo_electronico": user.email,
                        "telefono": "427-1148"
                    },
                    "totales": {},
                    "items": [],
                    "informacion_adicional": "Forma de pago:Efectivo|Caja: 1"
                }

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
                this.records = []
                localStorage.setItem('products_cart', JSON.stringify([]))
                this.calculateSummary()
            },
            calculateSummary() {
                let subtotal = 0.00
                this.records.forEach(function (item) {
                    //console.log(item)
                    subtotal += parseFloat(item.sub_total)
                })

                this.summary.subtotal = subtotal.toFixed(2)
                let tax = (subtotal * 0.18)
                this.summary.tax = tax.toFixed(2)
                this.summary.total = (subtotal + tax).toFixed(2)
                $("#total_amount").data('total', this.summary.total);
            }
        }
    })

</script>

<script>
    


    async function askedDocument(order) {
        const {
            value: documento
        } = await Swal.fire({
            title: 'DESEA FACTURA O BOLETA ELECTRÓNICA',
            input: 'select',
            inputOptions: {
                factura: 'FACTURA',
                boleta: 'BOLETA ELECTRÓNICA',
            },
            inputPlaceholder: 'Seleccione un Documento',
            showCancelButton: false,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    resolve()
                })
            }
        })

        app_cart.sendDocument(documento, order)
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

            let precio = Math.round((Number($("#total_amount").data('total')) * 100).toFixed(2));
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
                customer: getCustomer(),
                items: 
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

        let user = '{!! json_encode( Auth::user() ) !!}'

        return {
            "codigo_tipo_documento_identidad": "0",
            "numero_documento": "0",
            "apellidos_y_nombres_o_razon_social": user.name,
            "codigo_pais": "PE",
            "ubigeo": "150101",
            "direccion": "",
            "correo_electronico": user.email,
            "telefono": ""
        }
    }

</script>

@endpush
