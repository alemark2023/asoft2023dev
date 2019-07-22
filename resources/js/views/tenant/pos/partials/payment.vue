<template >
    <div class="row col-lg-12 m-0 p-0" v-loading="loading_submit">
        <div class="col-lg-4 col-md-6 bg-white m-0 p-0" style="height: calc(100vh - 110px)">
            <div class="h-75 bg-light" style="overflow-y: auto">

                <div class="row pl-3 py-2 border-bottom m-0 p-0 bg-white">
                    <div class="col-12 px-0 py-3">
                        <h4 class="font-weight-semibold m-0">{{customer.description}}</h4>                         
                    </div>
                </div>

                 <template v-for="(item,index) in form.items">
                    <div class="row py-1 border-bottom m-0 p-0" :key="index">
                        <div class="col-2 p-r-0 m-l-2">
                            <h4 class="font-weight-semibold m-0 text-center">{{item.quantity}}</h4>
                             
                        </div>
                        <div class="col-6 px-0">
                            <h4 class="font-weight-semibold m-0 text-center m-b-0">{{item.item.description}}</h4>
                            <!-- <p class="m-b-0">Descripción del producto</p> -->
                            <!-- <p class="text-muted m-b-0"><small>Descuento 2%</small></p> -->
                        </div>
                        <div class="col-4 p-l-0">
                            <!-- <p class="font-weight-semibold m-b-0">{{currencyTypeActive.symbol}} 240.00</p> -->
                            <h4 class="font-weight-semibold m-0 text-center">{{currencyTypeActive.symbol}} {{item.total}}</h4>
                        </div>
                    </div>
                </template>
 

            </div>
            <div class="h-25 bg-info" style="overflow-y: auto">
                <div class="row m-0 p-0 bg-white h-25 d-flex align-items-center">
                    <div class="col-sm-6 py-1">
                        <p class="font-weight-semibold mb-0">SUBTOTAL</p>
                    </div>
                    <div class="col-sm-6 py-1 text-right">
                        <p class="font-weight-semibold mb-0">{{currencyTypeActive.symbol}} {{ form.total_taxed }}</p>
                    </div>
                </div>
                <div class="row m-0 p-0 bg-white h-25 d-flex align-items-center">
                    <div class="col-sm-6 py-1">
                        <p class="font-weight-semibold mb-0">IGV</p>
                    </div>
                    <div class="col-sm-6 py-1 text-right">
                        <p class="font-weight-semibold mb-0">{{currencyTypeActive.symbol}} {{form.total_igv}}</p>
                    </div>
                </div>
                <!-- <div class="row m-0 p-0 bg-white">
                    <div class="col-sm-6 py-1">
                        <p class="font-weight-semibold mb-0">DESCUENTO</p>
                    </div>
                    <div class="col-sm-6 py-1 text-right">
                        <p class="font-weight-semibold mb-0">{{currencyTypeActive.symbol}} 4.00</p>
                    </div>
                </div> -->
                <div class="row m-0 p-0 h-50 d-flex align-items-center">
                    <div class="col-sm-6 py-2">
                        <p class="font-weight-semibold mb-0 text-white">TOTAL</p>
                    </div>
                    <div class="col-sm-6 py-2 text-right">
                        <p class="font-weight-semibold mb-0 text-white">{{currencyTypeActive.symbol}} {{ form.total }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-6 px-4 pt-3 hyo">
            <div class="row d-flex justify-content-center pt-2">
                <div class="col-lg-4 col-md-3">

                    <el-radio-group v-model="form.document_type_id" size="small" class="full" @change="filterSeries">
                        <el-radio-button label="01" >FACTURA  </el-radio-button>
                        <el-radio-button label="03">BOLETA  </el-radio-button>
                    </el-radio-group>
                </div>

                <div class="col-lg-4 col-md-3">

                    <el-select v-model="form.series_id" >
                        <el-option   v-for="option in series" :key="option.id" :label="option.number" :value="option.id">
                        </el-option>
                    </el-select>
                </div>

                <div class="col-lg-8">
                    <div class="card card-default">
                        
                        <div class="card-body text-center">
                                <p class="my-0"><small>Monto a cobrar</small></p>
                                <h1 class="mb-2 mt-0">{{currencyTypeActive.symbol}} {{ form.total }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card card-default">
                        
                        <div class="card-body text-center"> 

                            <div class="row col-lg-12">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label">Ingrese monto</label> 
                                    <el-input v-model="amount" @input="inputAmount">
                                        <template slot="prepend">{{currencyTypeActive.symbol}}</template>
                                    </el-input> 

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group" :class="{'has-danger': difference < 0}">
                                    <label class="control-label" v-text="(difference <0) ? 'Faltante' :'Cambio'"></label> 
                                    <!-- <el-input v-model="difference" :disabled="true">
                                        <template slot="prepend">{{currencyTypeActive.symbol}}</template>
                                    </el-input> -->
                                    <h4 class="control-label font-weight-semibold m-0 text-center m-b-0">{{currencyTypeActive.symbol}} {{difference}}</h4>
                                </div>
                            </div>
                            </div>
 
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="card card-default">
                        <div class="card-body">
                            <!-- <p class="text-center">Método de Pago</p> -->
                            <div class="input-group mb-3">

                                <div class="col-lg-12 m-bottom">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="control-label" >Método de Pago</label> 

                                            <el-select v-model="form_payment.payment_method_type_id" @change="changePaymentMethodType">
                                                    <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                            </el-select>  
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 m-bottom" v-if="has_card">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="control-label" >Tarjeta 
                                            <a class="text-info" @click.prevent="showDialogNewCardBrand = true" href="#">[+ Nueva]</a>
                                            </label>
                                            <el-select v-model="form_payment.card_brand_id">
                                                    <el-option v-for="option in cards_brand" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                            </el-select>  

                                        </div> 

                                    </div>
                                </div>
                                <div class="col-lg-12 m-bottom" >
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="control-label"  >Referencia</label> 
                                            <el-input v-model="form_payment.reference" >
                                            </el-input> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12" v-if="form_payment.payment_method_type_id=='01'">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <button class="btn btn-block btn-secondary" @click="setAmount(10)">{{currencyTypeActive.symbol}}10</button>
                                        </div>
                                        <div class="col-lg-3">
                                            <button class="btn btn-block btn-secondary" @click="setAmount(20)" >{{currencyTypeActive.symbol}}20</button>
                                        </div>
                                        <div class="col-lg-3">
                                            <button class="btn btn-block btn-secondary" @click="setAmount(50)"  >{{currencyTypeActive.symbol}}50</button>
                                        </div>
                                        <div class="col-lg-3">
                                            <button class="btn btn-block btn-secondary"  @click="setAmount(100)" >{{currencyTypeActive.symbol}}100</button>
                                        </div>
                                    </div>
                                </div>
                                 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 mb-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <button class="btn btn-block btn-primary" @click="clickPayment" :disabled="button_payment">PAGAR</button>
                        </div>
                        <div class="col-lg-6">
                            <button class="btn btn-block btn-danger" @click="clickCancel">CANCELAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <options-form   
            :showDialog.sync="showDialogOptions"
            :recordId="documentNewId" 
            :statusDocument="statusDocument" 
            ></options-form>

        <card-brands-form   :showDialog.sync="showDialogNewCardBrand"
                            :external="true"
                            :recordId="null"></card-brands-form>
    </div>
</template> 
<style>
.c-width{
    width: 80px!important;
    padding: 0!important;
    margin-right: 0!important;
} 

</style>

<script>
    import CardBrandsForm from '../../card_brands/form.vue'
  
    import OptionsForm from './options.vue'

    export default { 
        components: {OptionsForm, CardBrandsForm},

        props:['form','customer', 'currencyTypeActive', 'exchangeRateSale'],
        data() {
            return {
                loading_submit: false,
                showDialogOptions:false,
                showDialogNewCardBrand:false,
                documentNewId:null,
                has_card: false,
                resource: 'pos', 
                amount: 0,
                difference: 0,
                button_payment: false,
                input_item: '',
                form_payment:{},
                series:[],
                all_series:[], 
                cards_brand:[],
                cancel:false,
                form_cash_document:{},
                statusDocument:{},
                payment_method_types:[],
            }
        },
        async created() {
            await this.getTables()  
            this.initFormPayment()
            this.inputAmount()
            
            this.$eventHub.$on('reloadDataCardBrands', (card_brand_id) => {
                this.reloadDataCardBrands(card_brand_id)
            })
        }, 
        methods: {
            reloadDataCardBrands(card_brand_id) {
                this.$http.get(`/${this.resource}/table/card_brands`).then((response) => {
                    this.cards_brand = response.data
                    this.form_payment.card_brand_id = card_brand_id
                    this.changePaymentMethodType()
                })
            },
            changePaymentMethodType(){
                let payment_method_type = _.find(this.payment_method_types,{'id':this.form_payment.payment_method_type_id})   
                this.has_card = payment_method_type.has_card             
                this.form_payment.card_brand_id = (payment_method_type.has_card) ? this.form_payment.card_brand_id:null
            },
            setAmount(amount){
                this.amount = parseFloat(this.amount) + parseFloat(amount)
                this.inputAmount()
            },
            inputAmount(){

                this.difference = this.amount - this.form.total

                if(isNaN(this.difference)) {
                    this.button_payment = true
                    this.difference = "-"
                }else if(this.difference >=0){
                    this.button_payment = false
                    this.difference = this.amount - this.form.total
                }else{
                    this.button_payment = true
                } 
                this.difference = _.round(this.difference,2)    
                // this.form_payment.payment = this.amount
            },
            initFormPayment() {
                
                this.difference = -this.form.total
                this.form_payment = {
                    id: null,
                    date_of_payment: moment().format('YYYY-MM-DD'),
                    payment_method_type_id: '01',
                    reference: null,
                    card_brand_id:null,
                    document_id:null,
                    payment: this.form.total,  
                } 

                this.form_cash_document = { 
                    document_id:null, 
                } 
                 
            }, 
            
            filterSeries() {
                this.form.series_id = null
                this.series = _.filter(this.all_series, {'document_type_id': this.form.document_type_id });
                this.form.series_id = (this.series.length > 0)?this.series[0].id:null
            },
            async clickCancel(){                 

                this.loading_submit = true
                await this.sleep(800); 
                this.loading_submit = false
                this.$eventHub.$emit('cancelSale')
            },
            sleep(ms) { 
                return new Promise(resolve => setTimeout(resolve, ms));                
            },
            async clickPayment(){
                if(this.has_card && !this.form_payment.card_brand_id) return this.$message.error('Seleccione una tarjeta');

                this.loading_submit = true
                await this.$http.post(`/documents`, this.form).then(response => {
                    if (response.data.success) {
                        // console.log(response)
                        this.form_payment.document_id = response.data.data.id;
                        this.form_cash_document.document_id = response.data.data.id;
                        this.savePaymentMethod();
                        this.saveCashDocument();
                        this.statusDocument = response.data.data.response
                        this.documentNewId = response.data.data.id;
                        this.showDialogOptions = true;
                        this.initFormPayment() ;
                        this.$eventHub.$emit('saleSuccess');
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    }
                    else {
                        this.$message.error(error.response.data.message);
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            },
            saveCashDocument(){
                this.$http.post(`/cash/cash_document`, this.form_cash_document)
                    .then(response => {
                        if (response.data.success) {
                            // console.log(response)                             
                        } else {
                            this.$message.error(response.data.message);
                        }
                    })
                    .catch(error => { 
                        console.log(error); 
                    })
            },
            savePaymentMethod(){
                this.$http.post(`/document_payments`, this.form_payment)
                    .then(response => {
                        if (response.data.success) {
                            // console.log(response)                             
                        } else {
                            this.$message.error(response.data.message);
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.records[index].errors = error.response.data;
                        } else {
                            console.log(error);
                        }
                    })
            },
            getTables(){
                this.$http.get(`/${this.resource}/payment_tables`)
                    .then(response => { 
                        this.all_series = response.data.series  
                        this.payment_method_types = response.data.payment_method_types  
                        this.cards_brand = response.data.cards_brand  
                        this.filterSeries() 
                    })  

            }, 
        }
    }
</script>
