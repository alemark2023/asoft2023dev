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

                <div class="col-md-3">
                    <template  v-if="form.name" >
                        <div :class="{'has-danger': errors.name}"
                            class="form-group">
                        <label class="control-label">Nombre</label>
                        <el-input v-model="form.name">{{form.name}}</el-input>
                        <small v-if="errors.name"
                                class="form-control-feedback"
                                v-text="errors.name[0]"></small>
                    </div>
                    </template>
                    <template v-else>
                        <div :class="{'has-danger': errors.name}"
                            class="form-group">
                        <label class="control-label">Nombre</label>
                        <el-input v-model="form.name"></el-input>
                        <small v-if="errors.name"
                                class="form-control-feedback"
                                v-text="errors.name[0]"></small>
                    </div>
                    </template>
                    
                </div>

                <div
                        class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                            <tr>
                                <th class="text-center">Unidad</th>
                                <th class="text-center">Descripción</th>
                                <th class="text-center">
                                    Factor
                                    <el-tooltip class="item"
                                                content="Cantidad de unidades"
                                                effect="dark"
                                                placement="top">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </th>
                                <th class="text-center">Precio 1</th>
                                <th class="text-center">Precio 2</th>
                                <th class="text-center">Precio 3</th>
                                <th class="text-center">Precio 4</th>
                                <th class="text-center">P. Defecto</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in form.item_unit_types"
                                :key="index">
                                <template v-if="row.id">
                                    <td class="text-center">{{ row.unit_type_id }}</td>
                                    <td class="text-center">{{ row.description }}</td>
                                    <td class="text-center">{{ row.quantity_unit }}</td>
                                    <td class="text-center">
                                        <el-input v-model="row.price1"></el-input>
                                    </td>
                                    <td class="text-center">
                                        <el-input v-model="row.price2"></el-input>
                                    </td>
                                    <td class="text-center">
                                        <el-input v-model="row.price3"></el-input>
                                    </td>
                                    <td class="text-center">
                                        <el-input v-model="row.price4"></el-input>
                                    </td>
                                    <td class="text-center">Precio {{ row.price_default }}</td>
                                    <td class="series-table-actions text-right">
                                        <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                type="button"
                                                @click.prevent="clickDelete(row.id)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </template>
                                <template v-else>
                                    <td>
                                        <div class="form-group">
                                            <el-select v-model="row.unit_type_id"
                                                        dusk="item_unit_type.unit_type_id">
                                                <el-option v-for="option in unit_types"
                                                            :key="option.id"
                                                            :label="option.description"
                                                            :value="option.id"></el-option>
                                            </el-select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <el-input v-model="row.description"></el-input>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <el-input v-model="row.quantity_unit"></el-input>
                                            <!-- <small class="form-control-feedback" v-if="errors.quantity_unit" v-text="errors.quantity_unit[0]"></small> -->
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <el-input v-model="row.price1"></el-input>
                                            <!-- <small class="form-control-feedback" v-if="errors.stock_min" v-text="errors.stock_min[0]"></small> -->
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <el-input v-model="row.price2"></el-input>
                                            <!-- <small class="form-control-feedback" v-if="errors.stock_min" v-text="errors.stock_min[0]"></small> -->
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <el-input v-model="row.price3"></el-input>
                                            <!-- <small class="form-control-feedback" v-if="errors.stock_min" v-text="errors.stock_min[0]"></small> -->
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <el-input v-model="row.price4"></el-input>
                                            <!-- <small class="form-control-feedback" v-if="errors.stock_min" v-text="errors.stock_min[0]"></small> -->
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <el-select v-model="row.price_default">
                                                <el-option :key="1"
                                                            :value="1"
                                                            label="Precio 1"></el-option>
                                                <el-option :key="2"
                                                            :value="2"
                                                            label="Precio 2"></el-option>
                                                <el-option :key="3"
                                                            :value="3"
                                                            label="Precio 3"></el-option>
                                                <el-option :key="3"
                                                            :value="3"
                                                            label="Precio 4"></el-option>
                                            </el-select>
                                        </div>
                                    </td>
                                    <td class="series-table-actions text-right">
                                        <!-- <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickSubmit(index)">
                                            <i class="fa fa-check"></i>
                                        </button> -->
                                        <button class="btn waves-effect waves-light btn-xs btn-danger"
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
                item_unit_types: {
                    id: null,
                    unit_type_id: null,
                    quantity_unit: 0,
                    price1: 0,
                    price2: 0,
                    price3: 0,
                    price4: 0,
                    price_default: 2,

                },
                unit_types: [],
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
                    name: null,
                    item_unit_types: [],
                }
            },
            clickAddRow() {
                this.form.item_unit_types.push({
                    id: null,
                    description: null,
                    unit_type_id: 'NIU',
                    quantity_unit: 0,
                    price1: 0,
                    price2: 0,
                    price3: 0,
                    price4: 0,
                    price_default: 2
                })

                console.log(this.form.item_unit_types)
            },
            clickCancel(index) {
                this.form.item_unit_types.splice(index, 1)
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
                        console.log(response.data)
                        this.form.item_unit_types = [];
                        if(response.data.length > 0){
                            response.data.forEach(price => {
                                this.form.item_unit_types.push(price)
                            });
                            this.form.name= response.data[0].name
                            console.log(response.data[0].name)
                        }else{
                            this.form.item_unit_types.push(response.data)
                            this.form.name= response.data.name
                        }
                        
                    })
            }
        },
            async create() {

                this.titleDialog = (this.recordId)? 'Editar Listado de Precios':'Nuevo Listado de Precios'
                if (this.recordId) {
                    await this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                            console.log(response.data)
                            
                            if(response.data.length > 0){
                                response.data.forEach(price => {
                                    this.form.item_unit_types.push(price)
                                });
                                this.form.name= response.data[0].name
                                console.log(response.data[0].name)
                            }else{
                                this.form.item_unit_types.push(response.data)
                                this.form.name= response.data.name
                            }
                            
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