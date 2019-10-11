<template>
    <el-dialog :title="titleDialog"   :visible="showDialog"  @open="create"  :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
         <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body"> 
                <div class="row">
                    
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.identity_document_type_id}">
                            <label class="control-label">Tipo Doc. Identidad</label>
                            <el-select v-model="hotel.identity_document_type_id" filterable  popper-class="el-select-identity_document_type" >
                                <el-option v-for="option in identity_document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.identity_document_type_id" v-text="errors.identity_document_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.number}">
                            <label class="control-label">Número</label>                             
                                <el-input v-model="hotel.number" :maxlength="maxLength" > 
                                </el-input>
                            <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombres y Apellidos</label>
                            <el-input v-model="hotel.name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.sex}">
                            <label class="control-label">Sexo</label>
                            <el-select v-model="hotel.sex" filterable  > 
                                <el-option v-for="option in sexs" :key="option.id" :value="option.id" :label="option.description"></el-option>

                            </el-select>
                            <small class="form-control-feedback" v-if="errors.sex" v-text="errors.sex[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.age}">
                            <label class="control-label">Edad</label>
                            <el-input v-model="hotel.age"></el-input>
                            <small class="form-control-feedback" v-if="errors.age" v-text="errors.age[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.civil_status}">
                            <label class="control-label">Estado civil</label>
                            <el-select v-model="hotel.civil_status" filterable  >
                                <el-option v-for="option in civil_status" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.civil_status" v-text="errors.civil_status[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.room_number}">
                            <label class="control-label">N° Habitación</label>
                            <el-input v-model="hotel.room_number"></el-input>
                            <small class="form-control-feedback" v-if="errors.room_number" v-text="errors.room_number[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.nacionality}">
                            <label class="control-label">Nacionalidad</label>
                            <el-input v-model="hotel.nacionality"></el-input>
                            <small class="form-control-feedback" v-if="errors.nacionality" v-text="errors.nacionality[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.origin}">
                            <label class="control-label">Procedencia</label>
                            <el-input v-model="hotel.origin"></el-input>
                            <small class="form-control-feedback" v-if="errors.origin" v-text="errors.origin[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.date_entry}">
                            <label class="control-label">Fecha Ingreso</label>
                            <el-date-picker v-model="hotel.date_entry" type="date" value-format="yyyy-MM-dd" :clearable="false" ></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_entry" v-text="errors.date_entry[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.time_entry}">
                            <label class="control-label">Hora Ingreso</label>
                            <el-time-picker
                                v-model="hotel.time_entry" value-format="HH:mm:ss">
                            </el-time-picker>
                            <small class="form-control-feedback" v-if="errors.time_entry" v-text="errors.time_entry[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.date_exit}">
                            <label class="control-label">Fecha Salida</label>
                            <el-date-picker v-model="hotel.date_exit" type="date" value-format="yyyy-MM-dd" :clearable="false" ></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_exit" v-text="errors.date_exit[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.time_exit}">
                            <label class="control-label">Hora Salida</label>
                            <el-time-picker
                                v-model="hotel.time_exit" value-format="HH:mm:ss">
                            </el-time-picker>
                            <small class="form-control-feedback" v-if="errors.time_exit" v-text="errors.time_exit[0]"></small>
                        </div>
                    </div>
                </div>
                
             
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close(true)">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>  
    </el-dialog>
</template> 

<script>

    export default {
        props: ['showDialog', 'hotel'],
        data() {
            return {
                titleDialog: 'Datos personales para reserva de hospedaje',
                loading_submit: false,
                errors: {},
                form: {},
                resource: 'bussiness_turns',
                company: {},
                configuration: {},
                identity_document_types:[],
                sexs:[],
                civil_status:[],
                cards_brand:[],
            }
        },
        computed: {
            maxLength: function () { 
                if (this.hotel.identity_document_type_id === '1') {
                    return 8
                }else{
                    return 12
                }
            }
        },
        async created() {
            
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => { 
                    this.identity_document_types = response.data.identity_document_types  
                    this.sexs = response.data.sexs  
                    this.civil_status = response.data.civil_status  
                })  
        },
        methods: {
            create(){                
                
            },                
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}/validate_hotel`, this.hotel)
                    .then(response => {
                        if (response.data.success) {
                            this.$emit('addDocumentHotel', this.hotel);
                            this.close(false)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },    
            close(flag) {
                if(flag) this.$emit('addDocumentHotel', {});
                this.errors = {}
                this.$emit('update:showDialog', false)
            }, 
             
        }
    }
</script>
