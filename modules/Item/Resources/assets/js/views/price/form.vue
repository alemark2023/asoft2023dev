<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="row">
                <div 
                        class="col-md-12">
                    <h5 class="separator-title mt-0">
                        Listado de precios
                        <el-tooltip class="item"
                                    content="Aplica para realizar compra/venta en presentacion de diferentes precios y/o cantidades"
                                    effect="dark"
                                    placement="top">
                            <i class="fa fa-info-circle"></i>
                        </el-tooltip>
                    </h5>
                </div>
                <div
                        class="col-md-12">
                    <div class="table-responsive" style="overflow-x:auto;">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                               
                                <template v-for="(row, index) in form.prices" >
                                    <th style="width: 80px;" width="13%" :key="index">precio {{index+1}}</th>
                                </template>
                                
                                <th style="width: 80px;" width="13%" class="text-center">P. Defecto</th>
                                <th style="width: 80px;" width="13%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <template>
                                    
                                </template>
                                <template>
                                    <template v-for="(row, index) in form.prices" >
                                        <td v-if="index<1" width="13%" :key="index">
                                            <div style="width: 80px;" class="form-group">
                                                <el-input v-model="row.price"></el-input>
                                            </div>
                                        </td>
                                        <td v-else width="13%"  :key="index">
                                            <div style="width: 80px;" class="d-flex w-100" >
                                                <span class="pr-1">%</span>
                                                <div class="form-group">
                                                    <el-input v-model="row.price"></el-input>
                                                    <!-- <small class="form-control-feedback" v-if="errors.stock_min" v-text="errors.stock_min[0]"></small> -->
                                                </div>
                                            </div>
                                            
                                        </td>
                                    </template>
                                    <td width="13%">
                                        <div style="width: 80px;">
                                            <el-select v-model="form.price_default">
                                                <template  v-for="(row, index) in form.prices" >
                                                    <el-option :key="index+1"
                                                            :value="index+1"
                                                            :label="`precio  ${index+1}`"></el-option>
                                                </template>
                                            </el-select>
                                        </div>
                                    </td>
                                    <td width="13%" class="series-table-actions text-right">
                                        <!-- <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickSubmit(index)">
                                            <i class="fa fa-check"></i>
                                        </button> -->
                                        <button style="width: 80px;" class="btn waves-effect waves-light btn-xs btn-danger"
                                                type="button"
                                                @click.prevent="clickCancel(index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </template>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        <div class="col">
                    <a class="control-label font-weight-bold text-info"
                        href="#"
                        @click="clickAddRow"> [ + Agregar]</a>
                </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>
 
<script>
 

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'price', 
                errors: {}, 
                form: {},
                unit_types: [],
                price:[]
            }
        },
        async created() {
           await this.initForm();

           await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                let data = response.data;
                this.unit_types = data.unit_types
                // this.config = data.configuration
                /* this.$store.commit('setConfiguration', data.configuration); */


                /* this.loadConfiguration() */
            })

            this.$eventHub.$on('reloadTables', () => {
                this.reloadTables()
            })
        },
        methods: {
            initForm() { 
                this.errors = {} 

                this.form = {
                    id: null,
                    description: null,
                    unit_type_id: 'NIU',
                    quantity_unit: 0,
                    price_default: 2,
                    prices: [{price: 0},
                        {price: 0},
                        {price: 0},
                        {price: 0
                    }],
                }
            },
            async clickAddRow() {
                this.form.prices.push({
                    price:0
                });

            },
            clickCancel(index) {
                this.form.prices.splice(index, 1)
            },
            clickDelete(id) {

            this.$http.delete(`/${this.resource}/list/${id}`)
                .then(res => {
                    if (res.data.success) {
                        this.loadRecord()
                        this.$message.success('Se eliminó correctamente el registro')
                    }
                })
                .catch(error => {
                    if (error.response.status === 500) {
                        this.$message.error('Error al intentar eliminar');
                    } else {
                        console.log(error.response.data.message)
                    }
                })

        },
        async loadRecord() {
            if (this.recordId) {
                await this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                        this.form.prices=[]
                        /* console.log(response.data) */
                        response.data.forEach(value => {
                            
                            this.form.prices.push({price:value.price})
                        });
                        console.log(this.form.prices);
                        this.form.description= response.data[0].name_price.description
                        this.form.unit_type_id= response.data[0].name_price.unit_type_id
                        this.form.quantity_unit= response.data[0].name_price.quantity_unit
                        this.form.price_default= response.data[0].name_price.price_default
                        
                    })
            }
        },
            async create() {

                this.titleDialog = (this.recordId)? 'Editar Listado de Precios':'Nuevo Listado de Precios'
                if (this.recordId) {
                    this.form.id=this.recordId
                    await this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                            this.form.prices=[]
                            /* console.log(response.data) */
                                response.data.forEach(value => {
                            
                                    this.form.prices.push({price:value.price})
                                });
                                console.log(this.form.prices);
                                this.form.description= response.data[0].name_price.description
                                this.form.unit_type_id= response.data[0].name_price.unit_type_id
                                this.form.quantity_unit= response.data[0].name_price.quantity_unit
                                this.form.price_default= response.data[0].name_price.price_default
                            
                            
                        })
                }
            },
            async submit() {   
 

                this.loading_submit = true  
                await this.$http.post(`${this.resource}`, this.form)
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
                        } else {
                            console.log(error.response)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
                    
            },  
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            }
        }
    }
</script>