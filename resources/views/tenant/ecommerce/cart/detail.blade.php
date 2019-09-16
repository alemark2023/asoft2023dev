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
                    </tr>

                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4" class="clearfix">
                            <div class="float-left">
                                <a href="/ecommerce" class="btn btn-outline-secondary">Continue Shopping</a>
                            </div><!-- End .float-left -->

                            <div class="float-right">
                                <a href="#" @click="clearShoppingCart" class="btn btn-outline-secondary btn-clear-cart">Clear Shopping Cart</a>
                                <!--<a href="#" class="btn btn-outline-secondary btn-update-cart">Update Shopping Cart</a> -->
                            </div><!-- End .float-right -->
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div><!-- End .cart-table-container -->

        <div class="cart-discount">
            <h4>Apply Discount Code</h4>
            <form action="#">
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm" placeholder="Enter discount code" required>
                    <div class="input-group-append">
                        <button class="btn btn-sm btn-primary" type="submit">Apply Discount</button>
                    </div>
                </div><!-- End .input-group -->
            </form>
        </div><!-- End .cart-discount -->
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
                <a  href="{{route('tenant_pay_cart')}}"   class="btn btn-block btn-sm btn-primary">Ir a Pagar</a>
                <!--<a href="#" class="btn btn-link btn-block">Check Out with Multiple Addresses</a>-->
            </div><!-- End .checkout-methods -->
        </div><!-- End .cart-summary -->
    </div><!-- End .col-lg-4 -->
</div><!-- End .row -->


@endsection

@push('scripts')
<script src="{{ asset('porto-ecommerce/assets/js/vue.js') }}"></script>
<script type="text/javascript">
    function check(event) {
        console.log(event)
    }
    var app = new Vue({
        el: '#app',
        data: {
            records: [],
            message: 'Hello Vue!',
            summary: { subtotal: '0.0', tax:'0.0', total:'0.0' }
        },
        mounted() {
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
                    obj.sub_total = parseFloat( item.sale_unit_price ).toFixed(2)
                    return obj

                })
            }

        },
        methods: {
            clearShoppingCart()
            {
                this.records = []
                localStorage.setItem('products_cart', JSON.stringify([]))
            },
            calculateSummary()
            {
                let subtotal = 0.00
                this.records.forEach(function(item){
                    //console.log(item)
                    subtotal += parseFloat( item.sub_total )
                })

                this.summary.subtotal = subtotal.toFixed(2)
                let tax = (subtotal * 0.18)
                this.summary.tax = tax.toFixed(2)
                this.summary.total =  (subtotal + tax).toFixed(2)


                
            }
        }
    })

</script>
@endpush
