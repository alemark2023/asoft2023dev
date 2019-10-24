<template >
    <div >
        <header class="page-header pr-0">
            <h2 class="text-sm">POS</h2> 
            <div class="right-wrapper pull-right">
                <h2 class="text-sm">{{user.name}}</h2>
            </div>
        </header>
        <div v-if="!is_payment" class="row col-lg-12 m-0 p-0" v-loading="loading">
            <div class="col-lg-8 col-md-6 px-4 pt-3 hyo" >

                <el-input placeholder="Buscar productos" size="medium"  v-model="input_item" @input="searchItems" autofocus class="m-bottom">                
                    <el-button slot="append" icon="el-icon-plus" @click.prevent="showDialogNewItem = true"></el-button>
                </el-input> 

                <div class="row">
                    <template v-for="(item,index) in items"  >
                        <div class="col-lg-3 col-md-4 col-sm-6" :key="index" @click="clickAddItem(item,index)">
                            <section class="card pointer">
                                <div class="card-body px-2 pt-2">
                                    <p class="font-weight-semibold mb-0">{{item.description}}</p>
                                    <img :src="item.image_url" class="img-thumbail img-custom" /> 
                                    <p class="text-muted font-weight-lighter mb-0"><small>{{item.internal_id}}</small></p>
                                </div>
                                <div class="card-footer text-center bg-primary">
                                    <h5 class="font-weight-semibold text-right text-white">{{item.currency_type_symbol}} {{item.sale_unit_price}}</h5>
                                </div>
                            </section>
                        </div>      
                    </template>

                </div>

            </div>
            <div class="col-lg-4 col-md-6 bg-white m-0 p-0" style="height: calc(100vh - 110px)">
                <div class="h-75 bg-light" style="overflow-y: auto">
                    <div class="row py-3 border-bottom m-0 p-0">
                        <div class="col-8">
                            <el-select v-model="form.customer_id" filterable  placeholder="Cliente" @change="changeCustomer">
                                <el-option   v-for="option in all_customers" :key="option.id" :label="option.description" :value="option.id">
                                </el-option>
                            </el-select>
                        </div>
                        <div class="col-4">
                            <div class="btn-group d-flex" role="group">
                                <a class="btn btn-sm btn-default w-100" @click.prevent="showDialogNewPerson = true"> <i class="fas fa-plus fa-wf"></i> </a>
                                <a class="btn btn-sm btn-default w-100" @click="clickDeleteCustomer"> <i class="fas fa-trash fa-wf"></i> </a>
                            </div>                            
                        </div>
                    </div>
                    <div class="row py-1 border-bottom m-0 p-0">
                        <div class="col-12">
                        <table class="table table-sm table-borderless mb-0">
                            <template v-for="(item,index) in form.items">
                                <tr :key="index">
                                    <td width="30%">
                                        <el-input  v-model="item.item.aux_quantity" :readonly="item.item.calculate_quantity"  class=""  @input="clickAddItem(item,index,true)"></el-input>
                                          <!-- <el-input-number v-model="item.item.aux_quantity" @change="clickAddItem(item,index,true)" :min="1" :max="10"></el-input-number> -->

                                    </td>
                                    <td>
                                        <p class="m-0">{{item.item.description}}</p>
                                        <!-- <p class="text-muted m-b-0"><small>Descuento 2%</small></p> -->
                                    </td>
                                    <td >
                                        <p class="font-weight-semibold m-0 text-center">
                                            {{currency_type.symbol}} 
                                        </p>
                                    </td>
                                    <td width="30%">
                                        <p class="font-weight-semibold m-0 text-center">
                                            <!-- {{currency_type.symbol}} {{item.total}} -->
                                            <el-input v-model="item.total" @input="calculateQuantity(index)" @blur="blurCalculateQuantity(index)" :readonly="!item.item.calculate_quantity" >
                                                <!-- <template slot="prepend" v-if="currency_type.symbol">{{ currency_type.symbol }}</template> -->
                                            </el-input>
                                        </p>
                                    </td>
                                    <td class="text-right">
                                        <a   class="btn btn-sm btn-default"  @click="clickDeleteItem(index)"> <i class="fas fa-trash fa-wf"></i> </a>
                                    </td>
                                </tr>
                            </template>
                            
                        </table>
                    </div>
                    </div>
                </div>
                <div class="h-25 bg-light" style="overflow-y: auto">
                    <div class="row border-top bg-light m-0 p-0 h-50 d-flex align-items-center">
                        <div class="col-6 text-center px-0">
                            <h4 class="font-weight-semibold text-blue m-0">{{currency_type.symbol}} {{ form.total_taxed }}</h4>
                            <h5 class="d-inline-flex m-0">
                                <span class="font-weight-semibold ">Sub Total</span>
                            </h5>
                        </div>
                        <div class="col-6 text-center px-0">
                            <h4 class="font-weight-semibold text-blue m-0">{{currency_type.symbol}} {{form.total_igv}}</h4>
                            <h5 class="d-inline-flex m-0">
                                <span class="font-weight-semibold ">IGV</span>
                            </h5>
                        </div>
                    </div>
                    <div class="row text-white m-0 p-0 h-50 d-flex align-items-center" @click="clickPayment" v-bind:class="[form.total > 0 ? 'bg-info pointer' : 'bg-dark']">
                        <div class="col-6 text-center">
                            <i class="fas fa-chevron-circle-right fa fw h5"></i>
                            <span class="font-weight-semibold h5">PAGO</span>
                        </div>
                        <div class="col-6 text-center">
                            <h5 class="font-weight-semibold h5">{{currency_type.symbol}} {{ form.total }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            
            <person-form :showDialog.sync="showDialogNewPerson"
                        type="customers"
                        :external="true"
                        :document_type_id = form.document_type_id></person-form>

            <item-form :showDialog.sync="showDialogNewItem"
                   :external="true"></item-form>

        </div>
        <template v-else>
            <payment-form   :form="form"            
                            :currency-type-id-active="form.currency_type_id"
                            :currency-type-active="currency_type"
                            :exchange-rate-sale="form.exchange_rate_sale"
                            :customer="customer"></payment-form>
        </template>

    </div>
</template> 
<style>
.c-width{
    width: 80px!important;
    padding: 0!important;
    margin-right: 0!important;
}
.el-select-dropdown { 
    max-width: 80% !important;
    margin-right: 1% !important;
} 
</style>

<script>
 
    import {calculateRowItem} from '../../../helpers/functions'
    import PaymentForm from './partials/payment.vue';
    import PersonForm from '../persons/form.vue'
    import ItemForm from './partials/form.vue'
    import {functions, exchangeRate} from '../../../mixins/functions'

    export default { 
        components: {PaymentForm, PersonForm, ItemForm},
        mixins: [functions, exchangeRate],

        data() {
            return {
                showDialogNewPerson: false,
                showDialogNewItem: false,
                loading: false,
                is_payment: false,//aq
                // is_payment: true,//aq
                resource: 'pos',
                recordId: null,
                input_item: '',
                items:[],
                all_items:[],
                customers:[],
                affectation_igv_types:[],
                all_customers:[],
                establishment: null,
                currency_type: {},
                form_item:{},
                customer:{},
                row:{},
                user:{},
                form:{},
            }
        },
        async created() {
            await this.getTables() 
            this.initForm()
            this.events()
            
        },
        methods: {
            
            calculateQuantity(index) {
                // console.log(this.form.items[index])
                if(this.form.items[index].item.calculate_quantity) {
                    let quantity = _.round((parseFloat(this.form.items[index].total) / parseFloat(this.form.items[index].unit_price)), 4)
                    
                    if(quantity){
                        this.form.items[index].quantity = quantity
                        this.form.items[index].item.aux_quantity = quantity
                    }else{
                        this.form.items[index].quantity = 0
                        this.form.items[index].item.aux_quantity = 0
                    }
                    // this.calculateTotal()
                }
                
                //  this.clickAddItem(this.form.items[index],index, true) 

            },
            blurCalculateQuantity(index){
                this.row = calculateRowItem(this.form.items[index], this.form.currency_type_id, 1);
                this.form.items[index] = this.row 
                this.calculateTotal()
            },
            changeCustomer(){
                let customer = _.find(this.all_customers,{'id':this.form.customer_id})
                this.customer = customer
                this.form.document_type_id = (customer.identity_document_type_id == '1') ? '03':'01'
            },
            async events(){
                await this.$eventHub.$on('cancelSale', () => {                
                    this.is_payment = false
                    this.initForm()
                })
                await this.$eventHub.$on('reloadDataPersons', (customer_id) => {
                    this.reloadDataCustomers(customer_id)
                })

                await this.$eventHub.$on('reloadDataItems', (item_id) => {
                    this.reloadDataItems(item_id)
                })

                await this.$eventHub.$on('saleSuccess', () => {                
                    // this.is_payment = false
                    this.initForm() 
                    this.getTables()
                });
                
 
            },
            initForm() { 

                this.form = {
                    establishment_id: null,
                    document_type_id: '01',
                    series_id: null,
                    prefix: null,
                    number: '#',
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    customer_id: null,
                    currency_type_id: 'PEN',
                    purchase_order: null,
                    exchange_rate_sale: 1,
                    total_prepayment: 0,
                    total_charge: 0,
                    total_discount: 0,
                    total_exportation: 0,
                    total_free: 0,
                    total_taxed: 0,
                    total_unaffected: 0,
                    total_exonerated: 0,
                    total_igv: 0,
                    total_base_isc: 0,
                    total_isc: 0,
                    total_base_other_taxes: 0,
                    total_other_taxes: 0,
                    total_taxes: 0,
                    total_value: 0,
                    total: 0,
                    operation_type_id: '0101',
                    date_of_due: moment().format('YYYY-MM-DD'),
                    items: [],
                    charges: [],
                    discounts: [],
                    attributes: [],
                    guides: [],
                    payments: [],
                    hotel: {},
                    additional_information:null,
                    actions: {
                        format_pdf:'a4',
                    }
                }
                
                this.initFormItem()
                this.changeDateOfIssue()

                 
            },
            initFormItem(){
                
                this.form_item = { 
                    item_id: null,
                    item: {},
                    affectation_igv_type_id: null,
                    affectation_igv_type: {},
                    has_isc: false,
                    system_isc_type_id: null,
                    calculate_quantity:false,
                    percentage_isc: 0,
                    suggested_price: 0,
                    quantity: 1,
                    aux_quantity: 1,
                    unit_price_value: 0,
                    unit_price: 0,
                    charges: [],
                    discounts: [],
                    attributes: [],
                    has_igv: null
                };
            },
            async clickPayment(){

                let flag = 0
                this.form.items.forEach((row) => {
                    if(row.aux_quantity < 0 || row.total < 0|| isNaN(row.total)){
                        flag++
                    }
                })

                if(flag>0) return this.$message.error('Cantidad negativa o incorrecta');
                if(!this.form.customer_id) return this.$message.error('Seleccione un cliente');
                if(!this.form.items[0]) return this.$message.error('Seleccione un producto');
                this.form.establishment_id = this.establishment.id
                this.loading = true
                await this.sleep(800); 
                this.is_payment = true
                this.loading = false

            },
            sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            },
            clickDeleteCustomer(){
                this.form.customer_id = null
            },
            async clickAddItem(item,index, input = false){
                
                this.loading = true
                let exchangeRateSale = this.form.exchange_rate_sale
                let exist_item = _.find(this.form.items,{'item_id':item.item_id})  
                let pos = this.form.items.indexOf(exist_item);
                let response = null

                // console.log(item.calculate_quantity)
 
                  
                if(exist_item){
                    
                    if(input){ 
                        
                        response = await this.getStatusStock(item.item_id, exist_item.item.aux_quantity)                         
                        if(!response.success) {
                            item.item.aux_quantity = item.quantity
                            this.loading = false
                            return this.$message.error(response.message)
                        }

                        exist_item.quantity =  exist_item.item.aux_quantity 

                    }else{ 

                        response = await this.getStatusStock(item.item_id, parseFloat(exist_item.item.aux_quantity) + 1)                         
                        if(!response.success) {
                            this.loading = false
                            return this.$message.error(response.message)
                        }

                        exist_item.quantity ++;
                        exist_item.item.aux_quantity  ++; 
                    }

                    this.row = calculateRowItem(exist_item, this.form.currency_type_id, exchangeRateSale);
                   
                    this.form.items[pos] = this.row 

                }else{

                    response = await this.getStatusStock(item.item_id, 1)                         
                    if(!response.success){
                        this.loading = false
                        return this.$message.error(response.message)
                    }

                    this.form_item.item = item;
                    this.form_item.unit_price_value = this.form_item.item.sale_unit_price;
                    this.form_item.has_igv = this.form_item.item.has_igv;
                    this.form_item.affectation_igv_type_id = this.form_item.item.sale_affectation_igv_type_id;
                    this.form_item.quantity = 1;
                    this.form_item.aux_quantity = 1;

                    let unit_price = (this.form_item.has_igv)?this.form_item.unit_price_value:this.form_item.unit_price_value*1.18;
                    
                    this.form_item.unit_price = unit_price;
                    this.form_item.item.unit_price = unit_price;
                    this.form_item.item.presentation = null;
                    
                    this.form_item.charges = [];
                    this.form_item.discounts = [];
                    this.form_item.attributes = [];
                    this.form_item.affectation_igv_type = _.find(this.affectation_igv_types, {'id': this.form_item.affectation_igv_type_id});

                    // console.log(this.form_item)
                    this.row = calculateRowItem(this.form_item, this.form.currency_type_id, exchangeRateSale);
                    // console.log(this.row)

                    this.form.items.push(this.row)
                    item.aux_quantity = 1

                }

                 console.log('pos', this.row)

                this.$notify({title: '',  message: 'Producto añadido!',   type: 'success',duration:700 });
                
                // console.log(this.row)
                // console.log(this.form.items)
                this.calculateTotal()
                this.loading = false                             

            },   
            async getStatusStock(item_id, quantity){
                let data = {}
                if(!quantity) quantity = 0; 
                await this.$http.get(`/${this.resource}/validate_stock/${item_id}/${quantity}`)
                    .then(response => {  
                        data = response.data
                    })
                return data
            },
            clickDeleteItem(index) {
                
                this.form.items.splice(index, 1)
                this.calculateTotal()
            },
            
            calculateTotal() {
                debugger
                let total_discount = 0
                let total_charge = 0
                let total_exportation = 0
                let total_taxed = 0
                let total_exonerated = 0
                let total_unaffected = 0
                let total_free = 0
                let total_igv = 0
                let total_value = 0
                let total = 0
                this.form.items.forEach((row) => {
                    total_discount += parseFloat(row.total_discount)
                    total_charge += parseFloat(row.total_charge)

                    if (row.affectation_igv_type_id === '10') {
                        total_taxed += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '20') {
                        total_exonerated += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '30') {
                        total_unaffected += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '40') {
                        total_exportation += parseFloat(row.total_value)
                    }
                    if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) < 0) {
                        total_free += parseFloat(row.total_value)
                    }
                    if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) > -1) {
                        total_igv += parseFloat(row.total_igv)
                        total += parseFloat(row.total)
                    }
                    total_value += parseFloat(row.total_value)
                });

                this.form.total_exportation = _.round(total_exportation, 2)
                this.form.total_exonerated = _.round(total_exonerated, 2)
                this.form.total_taxed = (_.round(total_taxed, 2) +  this.form.total_exonerated)
               // this.form.total_exonerated = _.round(total_exonerated, 2)
                this.form.total_unaffected = _.round(total_unaffected, 2)
                this.form.total_free = _.round(total_free, 2)
                this.form.total_igv = _.round(total_igv, 2)
                this.form.total_value = _.round(total_value, 2)
                this.form.total_taxes = _.round(total_igv, 2)
                this.form.total = _.round(total, 2)
            },
            changeDateOfIssue() {
                // this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                //     this.form.exchange_rate_sale = response
                // })
            },
            async getTables(){
                await this.$http.get(`/${this.resource}/tables`)
                    .then(response => { 
                        this.all_items = response.data.items 
                        this.affectation_igv_types = response.data.affectation_igv_types
                        this.all_customers = response.data.customers
                        this.establishment = response.data.establishment
                        this.currency_types = response.data.currency_types
                        this.user = response.data.user
                        this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null
                        this.changeCurrencyType()
                        this.filterItems() 
                        this.changeDateOfIssue()
                    })  

            },
            searchItems() {  
                  
                if (this.input_item.length > 0) {

                    this.loading = true
                    let parameters = `input_item=${this.input_item}`

                    this.$http.get(`/${this.resource}/search_items?${parameters}`)
                            .then(response => { 
                                // console.log(response)
                                this.items = response.data.items
                                this.loading = false
                                if(this.items.length == 0){this.filterItems()}
                            })  
                } else {
                    // this.customers = []
                    this.filterItems()
                }

            }, 
            filterItems() {                 
                this.items = this.all_items                     
            },
            reloadDataCustomers(customer_id) {
                this.$http.get(`/${this.resource}/table/customers`).then((response) => {
                    this.all_customers = response.data
                    this.form.customer_id = customer_id
                    this.changeCustomer()
                })              
            },
            reloadDataItems(item_id) {
                this.$http.get(`/${this.resource}/table/items`).then((response) => {
                    this.all_items = response.data
                    this.filterItems() 
                })
            },
            changeCurrencyType() {
                this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
                // let items = []
                // this.form.items.forEach((row) => {
                //     items.push(calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
                // });
                // this.form.items = items
                // this.calculateTotal()
            },
        }
    }
</script>