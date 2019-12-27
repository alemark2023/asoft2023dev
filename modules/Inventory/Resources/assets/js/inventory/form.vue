
<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.item_id}">
                            <label class="control-label">Producto</label>
                            <el-select v-model="form.item_id" filterable>
                                <el-option v-for="option in items" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.item_id" v-text="errors.item_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.quantity}">
                            <label class="control-label">Cantidad</label>
                            <el-input v-model="form.quantity"></el-input>
                            <small class="form-control-feedback" v-if="errors.quantity" v-text="errors.quantity[0]"></small>

                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.warehouse_id}">
                            <label class="control-label">Almacén</label>
                            <el-select v-model="form.warehouse_id" filterable>
                                <el-option v-for="option in warehouses" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.warehouse_id" v-text="errors.warehouse_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4" v-if="type == 'input'">
                        <div class="form-group" :class="{'has-danger': errors.lot_code}">
                            <label class="control-label">
                                Código lote
                            </label>
                            <el-input v-model="form.lot_code" >
                                <el-button slot="append" icon="el-icon-edit-outline"  @click.prevent="clickLotcode"></el-button>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.lot_code" v-text="errors.lot_code[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.inventory_transaction_id}">
                            <label class="control-label">Motivo traslado</label>
                            <el-select v-model="form.inventory_transaction_id" filterable>
                                <el-option v-for="option in inventory_transactions" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.inventory_transaction_id" v-text="errors.inventory_transaction_id[0]"></small>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Aceptar</el-button>
            </div>
        </form>
        
        <lots-form
            :showDialog.sync="showDialogLots"
            :stock="form.quantity"
            :lots="form.lots"
            @addRowLot="addRowLot">
        </lots-form>
    </el-dialog>

</template>

<script>
    import LotsForm from '../../../../../../resources/js/views/tenant/items/partials/lots.vue'

    export default {
        components: {LotsForm},
        props: ['showDialog', 'recordId','type'],
        data() {
            return {
                loading_submit: false,
                showDialogLots:false,
                titleDialog: null,
                resource: 'inventory',
                errors: {},
                form: {},
                items: [],
                warehouses: [],
                inventory_transactions: [],
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            addRowLot(lots){
                this.form.lots = lots
            },
            clickLotcode(){ 
                this.showDialogLots = true
            },
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    item_id: null,
                    warehouse_id: null,
                    inventory_transaction_id: null,
                    quantity: null,
                    type: this.type,
                    lot_code:null,
                    lots:[]

                }
            },
            async create() {

                this.titleDialog = (this.type == 'input') ? 'Ingreso de producto al almacén' : 'Salida de producto del almacén'
 
                await this.$http.get(`/${this.resource}/tables/transaction/${this.type}`)
                    .then(response => {
                        this.items = response.data.items
                        this.warehouses = response.data.warehouses
                        this.inventory_transactions = response.data.inventory_transactions
                    })

            },
            submit() {

                // if(this.form.quantity<0)
                //     return this.$message.error('No puede ingresar cantidad negativa')

                if(this.form.lots.length>0){
                    if(!this.form.lot_code){
                        return this.$message.error('El campo código de lote es requerido');
                    }
                }

                // this.loading_submit = true
                this.form.type = this.type
                // console.log(this.form)
                this.$http.post(`/${this.resource}/transaction`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                            // console.log(error.response.data)
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>