<template>
    <el-dialog :title="titleDialog"   :visible="showDialog"  @open="create"  :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
         
        <div class="form-body">
            <div class="row" >
                <div class="col-lg-12">

                    <table>
                    <thead>
                        <tr width="100%">
                            <th v-if="payments.length>0">Método de pago</th>
                            <th v-if="payments.length>0">Referencia</th>
                            <th v-if="payments.length>0">Monto</th>
                            <th width="15%"><a href="#" @click.prevent="clickAddPayment" class="text-center font-weight-bold text-info">[+ Agregar]</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in payments" :key="index"> 
                            <td>
                                <div class="form-group mb-2 mr-2">
                                    <el-select v-model="row.payment_method_type_id">
                                        <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group mb-2 mr-2"  >
                                    <el-input v-model="row.reference"></el-input>
                                </div>
                            </td>
                            <td>
                                <div class="form-group mb-2 mr-2" >
                                    <el-input v-model="row.payment"></el-input>
                                </div>
                            </td>
                            <td class="series-table-actions text-center"> 
                                <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancel(index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td> 
                            <br>
                        </tr>
                    </tbody> 
                </table> 
                

                </div>
                
            </div>
        </div>
        
        <div class="form-actions text-right pt-2">
            <el-button @click.prevent="close()">Cerrar</el-button>
        </div>
    </el-dialog>
</template> 

<script>
    export default {
        props: ['showDialog', 'payments'],
        data() {
            return {
                titleDialog: 'Pagos',
                loading: false,
                errors: {},
                form: {},
                company: {},
                configuration: {},
                activeName: 'first',
                payment_method_types:[],
                cards_brand:[],

            }
        },
        async created() {
            
            await this.$http.get(`/pos/payment_tables`)
                .then(response => { 
                    this.payment_method_types = response.data.payment_method_types  
                    this.cards_brand = response.data.cards_brand  
                    this.clickAddPayment()
                })  
        },
        methods: {
            create(){
                
                
            },
            clickAddPayment() {
                
                this.payments.push({
                    id: null,
                    document_id: null,
                    sale_note_id: null,
                    date_of_payment:  moment().format('YYYY-MM-DD'),
                    payment_method_type_id: '01',
                    reference: null,
                    payment: 0,
                });

                this.$emit('add', this.payments);
            }, 
                   
            close() {
                this.$emit('update:showDialog', false)
                this.$emit('add', this.payments);
            },
            clickCancel(index) {
                this.payments.splice(index, 1);
                this.$emit('add', this.payments);
            },
        }
    }
</script>
