<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="close" top="7vh" :close-on-click-modal="false">
        <form autocomplete="off" @submit.prevent="clickAddItem">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7 col-lg-7 col-xl-7 col-sm-7">
                        <div class="form-group" id="custom-select" :class="{'has-danger': errors.item_id}">
                            <label class="control-label">
                                Producto/Servicio
                                <a href="#" @click.prevent="showDialogNewItem = true">[+ Nuevo]</a>
                            </label>

                            <template v-if="!search_item_by_barcode" id="select-append">
                                <el-input id="custom-input">
                                    <el-select :disabled="recordItem != null"
                                            v-model="form.item_id" @change="changeItem"
                                            filterable
                                            placeholder="Buscar"
                                            popper-class="el-select-items"
                                            dusk="item_id"
                                            @visible-change="focusTotalItem"
                                            slot="prepend"
                                            id="select-width">
                                        <el-option v-for="option in items"  :key="option.id" :value="option.id" :label="option.full_description"></el-option>
                                    </el-select>
                                    <el-tooltip slot="append" class="item" effect="dark" content="Ver Stock del Producto" placement="bottom" :disabled="recordItem != null">
                                        <el-button  @click.prevent="clickWarehouseDetail()"><i class="fa fa-search"></i></el-button>
                                    </el-tooltip>
                                </el-input>
                            </template>
                            <template v-else>
                                <el-input id="custom-input">
                                    <el-select :disabled="recordItem != null" v-model="form.item_id"
                                            @change="changeItem"
                                            filterable
                                            placeholder="Buscar"
                                            :filter-method="filterMethod"
                                            popper-class="el-select-items"
                                            dusk="item_id"
                                            @visible-change="focusTotalItem"
                                            slot="prepend"
                                            id="select-width">
                                        <el-option v-for="option in items"  :key="option.id" :value="option.id" :label="option.full_description"></el-option>
                                    </el-select>
                                    <el-tooltip slot="append" class="item" effect="dark" content="Ver Stock del Producto" placement="bottom" :disabled="recordItem != null">
                                        <el-button  @click.prevent="clickWarehouseDetail()"><i class="fa fa-search"></i></el-button>
                                    </el-tooltip>
                                </el-input>
                            </template>

                            <template v-if="!is_client">
                                <el-checkbox  v-model="search_item_by_barcode" :disabled="recordItem != null" >Buscar por código de barras</el-checkbox><br>
                            </template>
                            <el-checkbox v-model="form.has_plastic_bag_taxes" :disabled="isEditItemNote" >Impuesto a la Bolsa Plástica</el-checkbox>
                            <small class="form-control-feedback" v-if="errors.item_id" v-text="errors.item_id[0]"></small>
                        </div>
                    </div>
                    <!-- <div class="col-md-1 col-lg-1 col-xl-1 col-sm-1" >
                        <div class="form-group">
                            <label class="control-label">Stock</label><br>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickWarehouseDetail()"><i class="fa fa-search"></i></button>
                        </div>
                    </div> -->
                    <div class="col-md-5">
                        <div class="form-group" :class="{'has-danger': errors.affectation_igv_type_id}">
                            <label class="control-label">Afectación Igv</label>
                            <el-select v-model="form.affectation_igv_type_id" :disabled="!change_affectation_igv_type_id" filterable>
                                <el-option v-for="option in affectation_igv_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <el-checkbox :disabled="recordItem != null" v-model="change_affectation_igv_type_id">Editar</el-checkbox>
                            <small class="form-control-feedback" v-if="errors.affectation_igv_type_id" v-text="errors.affectation_igv_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group" :class="{'has-danger': errors.quantity}">
                            <label class="control-label">Cantidad</label>
                            <el-input-number v-model="form.quantity" :min="0.01" :disabled="form.item.calculate_quantity"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.quantity" v-text="errors.quantity[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_price_value}">
                            <label class="control-label">Precio Unitario</label>
                            <el-input v-model="form.unit_price_value" @input="calculateQuantity" :readonly="typeUser === 'seller'">
                                <template slot="prepend" v-if="form.item.currency_type_symbol">{{ form.item.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.unit_price_value" v-text="errors.unit_price[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6" v-show="form.item.calculate_quantity">
                        <div class="form-group"  :class="{'has-danger': errors.total_item}">
                            <label class="control-label">Total venta producto</label>
                            <el-input v-model="total_item" @input="calculateQuantity" :min="0.01" ref="total_item">
                                <template slot="prepend" v-if="form.item.currency_type_symbol">{{ form.item.currency_type_symbol }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.total_item" v-text="errors.total_item[0]"></small>
                        </div>
                    </div>
                    <template v-if="!is_client">

                        <div class="col-md-12"  v-if="form.item_unit_types.length > 0">
                            <div style="margin:3px" class="table-responsive">
                                <h3>Lista de Precios</h3>
                                <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Unidad</th>
                                    <th class="text-center">Descripción</th>
                                    <th class="text-center">Factor</th>
                                    <th class="text-center">Precio 1</th>
                                    <th class="text-center">Precio 2</th>
                                    <th class="text-center">Precio 3</th>
                                    <th class="text-center">Precio Default</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in form.item_unit_types">

                                        <td class="text-center">{{row.unit_type_id}}</td>
                                        <td class="text-center">{{row.description}}</td>
                                        <td class="text-center">{{row.quantity_unit}}</td>
                                        <td class="text-center">{{row.price1}}</td>
                                        <td class="text-center">{{row.price2}}</td>
                                        <td class="text-center">{{row.price3}}</td>
                                        <td class="text-center">Precio {{row.price_default}}</td>
                                        <td class="series-table-actions text-right">
                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="selectedPrice(row)">
                                                <i class="el-icon-check"></i>
                                            </button>
                                        </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <!--<div class="col-md-3 center-el-checkbox">-->
                            <!--<div class="form-group" :class="{'has-danger': errors.has_igv}">-->
                                <!--<el-checkbox v-model="form.has_igv">Incluye Igv</el-checkbox><br>-->
                                <!--<small class="form-control-feedback" v-if="errors.has_igv" v-text="errors.has_igv[0]"></small>-->
                            <!--</div>-->
                        <!--</div>-->
                        <!-- <div class="col-md-4 center-el-checkbox">
                            <div class="form-group" :class="{'has-danger': errors.has_igv}">
                                <el-checkbox v-model="form.has_plastic_bag_taxes">Impuesto a la Bolsa Plástica</el-checkbox><br>
                            </div>
                        </div>  -->
                        <!--<div class="col-md-6" v-show="has_list_prices">
                            <div class="form-group" :class="{'has-danger': errors.item_unit_type_id}">
                                <label class="control-label">Presentación</label>
                                <el-select v-model="form.item_unit_type_id" filterable @change="changePresentation">
                                    <el-option v-for="option in item_unit_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <el-radio-group v-if="form.item_unit_type_id" v-model="item_unit_type.price_default" @change="changePresentation">
                                    <el-radio :label="1">Precio 1</el-radio>
                                    <el-radio :label="2">Precio 2</el-radio>
                                    <el-radio :label="3">Precio 3</el-radio>
                                </el-radio-group>
                                <small class="form-control-feedback" v-if="errors.item_unit_type_id" v-text="errors.item_unit_type_id[0]"></small>
                            </div>
                        </div>-->
                        <!-- <div class="col-md-12 mt-2" v-if="form.item.warehouses">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Ubicación</th>
                                    <th class="text-right">Stock</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="row in form.item.warehouses">
                                    <th>{{ row.warehouse_description }}</th>
                                    <th class="text-right">{{ row.stock }}</th>
                                </tr>
                                </tbody>
                            </table>
                        </div> -->
                        <div class="col-md-12 mt-2">
                            <el-collapse  v-model="activePanel">
                                <!-- <el-collapse-item :disabled="recordItem != null" title="Información adicional atributos UBL 2.1" name="1"> -->
                                <el-collapse-item :disabled="recordItem != null"  name="1">
                                    <template slot="title">
                                        <i style="color:green;" class="fa fa-plus"></i> &nbsp; Agregar Descuentos/Cargos/Atributos especiales<i class="header-icon el-icon-information"></i>
                                    </template>
                                    <!--<div>-->
                                        <!--<div class="row">-->
                                            <div v-if="discount_types.length > 0">
                                                <label class="control-label">
                                                    Descuentos
                                                    <a href="#" @click.prevent="clickAddDiscount">[+ Agregar]</a>
                                                </label>
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Tipo</th>
                                                        <th>Descripción</th>
                                                        <th>Porcentaje</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(row, index) in form.discounts">
                                                        <td>
                                                            <el-select v-model="row.discount_type_id" @change="changeDiscountType(index)">
                                                                <el-option v-for="option in discount_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                                            </el-select>
                                                        </td>
                                                        <td>
                                                            <el-input v-model="row.description"></el-input>
                                                        </td>
                                                        <td>
                                                            <el-checkbox v-model="row.is_amount">Ingresar monto fijo</el-checkbox><br>
                                                            <el-input v-model="row.percentage"></el-input>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger" @click.prevent="clickRemoveDiscount(index)">x</button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div v-if="charge_types.length > 0">
                                                <label class="control-label">
                                                    Cargos
                                                    <a href="#" @click.prevent="clickAddCharge">[+ Agregar]</a>
                                                </label>
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Tipo</th>
                                                        <th>Descripción</th>
                                                        <th>Porcentaje</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(row, index) in form.charges">
                                                        <td>
                                                            <el-select v-model="row.charge_type_id" @change="changeChargeType(index)">
                                                                <el-option v-for="option in charge_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                                            </el-select>
                                                        </td>
                                                        <td>
                                                            <el-input v-model="row.description"></el-input>
                                                        </td>
                                                        <td>
                                                            <el-input v-model="row.percentage"></el-input>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger" @click.prevent="clickRemoveCharge(index)">x</button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div v-if="attribute_types.length > 0">
                                                <label class="control-label">
                                                    Atributos
                                                    <a href="#" @click.prevent="clickAddAttribute">[+ Agregar]</a>
                                                </label>
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Tipo</th>
                                                        <th>Descripción</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(row, index) in form.attributes">
                                                        <td>
                                                            <el-select v-model="row.attribute_type_id" filterable @change="changeAttributeType(index)">
                                                                <el-option v-for="option in attribute_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                                            </el-select>
                                                        </td>
                                                        <td>
                                                            <el-input v-model="row.value"></el-input>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger" @click.prevent="clickRemoveAttribute(index)">x</button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <!--</div>-->
                                    <!--</div>-->
                                </el-collapse-item>
                            </el-collapse>
                            <!--<section class="card mb-2 card-transparent card-collapsed" id="card-section">-->
                                    <!--<header class="card-header hoverable bg-light border-top rounded-0 py-1" data-card-toggle style="cursor: pointer;" id="card-click">-->
                                        <!--<div class="card-actions" style="margin-top: -12px;">-->
                                            <!--<a href="#" class="card-action card-action-toggle text-info" data-card-toggle=""></a>-->

                                        <!--</div>-->

                                        <!--<p class="pl-1">Información adicional atributos UBL 2.1</p>-->
                                    <!--</header>-->
                                    <!--<div class="card-body px-0" style="display: none;">-->
                                        <!--<div class="col-md-12 px-0" v-if="discount_types.length > 0">-->
                                            <!--<label class="control-label">-->
                                                <!--Descuentos-->
                                                <!--<a href="#" @click.prevent="clickAddDiscount">[+ Agregar]</a>-->
                                            <!--</label>-->
                                            <!--<table class="table">-->
                                                <!--<thead>-->
                                                <!--<tr>-->
                                                    <!--<th>Tipo</th>-->
                                                    <!--<th>Descripción</th>-->
                                                    <!--<th>Porcentaje</th>-->
                                                    <!--<th></th>-->
                                                <!--</tr>-->
                                                <!--</thead>-->
                                                <!--<tbody>-->
                                                <!--<tr v-for="(row, index) in form.discounts">-->
                                                    <!--<td>-->
                                                        <!--<el-select v-model="row.discount_type_id" @change="changeDiscountType(index)">-->
                                                            <!--<el-option v-for="option in discount_types" :key="option.id" :value="option.id" :label="option.description"></el-option>-->
                                                        <!--</el-select>-->
                                                    <!--</td>-->
                                                    <!--<td>-->
                                                        <!--<el-input v-model="row.description"></el-input>-->
                                                    <!--</td>-->
                                                    <!--<td>-->
                                                        <!--<el-input v-model="row.percentage"></el-input>-->
                                                    <!--</td>-->
                                                    <!--<td>-->
                                                        <!--<button type="button" class="btn btn-danger" @click.prevent="clickRemoveDiscount(index)">x</button>-->
                                                    <!--</td>-->
                                                <!--</tr>-->
                                                <!--</tbody>-->
                                            <!--</table>-->
                                        <!--</div>-->
                                        <!--<div class="col-md-12 px-0" v-if="charge_types.length > 0">-->
                                            <!--<label class="control-label">-->
                                                <!--Cargos-->
                                                <!--<a href="#" @click.prevent="clickAddCharge">[+ Agregar]</a>-->
                                            <!--</label>-->
                                            <!--<table class="table">-->
                                                <!--<thead>-->
                                                <!--<tr>-->
                                                    <!--<th>Tipo</th>-->
                                                    <!--<th>Descripción</th>-->
                                                    <!--<th>Porcentaje</th>-->
                                                    <!--<th></th>-->
                                                <!--</tr>-->
                                                <!--</thead>-->
                                                <!--<tbody>-->
                                                <!--<tr v-for="(row, index) in form.charges">-->
                                                    <!--<td>-->
                                                        <!--<el-select v-model="row.charge_type_id" @change="changeChargeType(index)">-->
                                                            <!--<el-option v-for="option in charge_types" :key="option.id" :value="option.id" :label="option.description"></el-option>-->
                                                        <!--</el-select>-->
                                                    <!--</td>-->
                                                    <!--<td>-->
                                                        <!--<el-input v-model="row.description"></el-input>-->
                                                    <!--</td>-->
                                                    <!--<td>-->
                                                        <!--<el-input v-model="row.percentage"></el-input>-->
                                                    <!--</td>-->
                                                    <!--<td>-->
                                                        <!--<button type="button" class="btn btn-danger" @click.prevent="clickRemoveCharge(index)">x</button>-->
                                                    <!--</td>-->
                                                <!--</tr>-->
                                                <!--</tbody>-->
                                            <!--</table>-->
                                        <!--</div>-->
                                        <!--<div class="col-md-12 px-0" v-if="attribute_types.length > 0">-->
                                            <!--<label class="control-label">-->
                                                <!--Atributos-->
                                                <!--<a href="#" @click.prevent="clickAddAttribute">[+ Agregar]</a>-->
                                            <!--</label>-->
                                            <!--<table class="table">-->
                                                <!--<thead>-->
                                                <!--<tr>-->
                                                    <!--<th>Tipo</th>-->
                                                    <!--<th>Descripción</th>-->
                                                    <!--<th></th>-->
                                                <!--</tr>-->
                                                <!--</thead>-->
                                                <!--<tbody>-->
                                                <!--<tr v-for="(row, index) in form.attributes">-->
                                                    <!--<td>-->
                                                        <!--<el-select v-model="row.attribute_type_id" filterable @change="changeAttributeType(index)">-->
                                                            <!--<el-option v-for="option in attribute_types" :key="option.id" :value="option.id" :label="option.description"></el-option>-->
                                                        <!--</el-select>-->
                                                    <!--</td>-->
                                                    <!--<td>-->
                                                        <!--<el-input v-model="row.value"></el-input>-->
                                                    <!--</td>-->
                                                    <!--<td>-->
                                                        <!--<button type="button" class="btn btn-danger" @click.prevent="clickRemoveAttribute(index)">x</button>-->
                                                    <!--</td>-->
                                                <!--</tr>-->
                                                <!--</tbody>-->
                                            <!--</table>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                <!--</section>-->
                        </div>
                    </template>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button class="add" type="primary" native-type="submit" v-if="form.item_id">{{titleAction}}</el-button>
            </div>
        </form>
        <item-form :showDialog.sync="showDialogNewItem"
                   :external="true"></item-form>


        <warehouses-detail
                :showDialog.sync="showWarehousesDetail"
                :warehouses="warehousesDetail">
            </warehouses-detail>
    </el-dialog>
</template>
<style>
.el-select-dropdown {
    max-width: 80% !important;
    margin-right: 5% !important;
}
</style>

<script>

    import ItemForm from '../../items/form.vue'
    import {calculateRowItem} from '../../../../helpers/functions'
    import WarehousesDetail from '../../items/partials/warehouses.vue'

    export default {
        props: ['recordItem','showDialog', 'operationTypeId', 'currencyTypeIdActive', 'exchangeRateSale', 'typeUser', 'isEditItemNote'],
        components: {ItemForm, WarehousesDetail},
        data() {
            return {
                titleAction: '',
                is_client:false,
                titleDialog: '',
                resource: 'documents',
                showDialogNewItem: false,
                has_list_prices: false,
                errors: {},
                form: {},
                items: [],
                operation_types: [],
                all_affectation_igv_types: [],
                affectation_igv_types: [],
                system_isc_types: [],
                discount_types: [],
                charge_types: [],
                attribute_types: [],
                use_price: 1,
                change_affectation_igv_type_id: false,
                activePanel: 0,
                total_item: 0,
                item_unit_types: [],
                showWarehousesDetail: false,
                warehousesDetail:[],
                showListStock:false,
                search_item_by_barcode:false,
                //item_unit_type: {}
            }
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/item/tables`).then(response => {
               // console.log('tablas new edit')
                this.items = response.data.items
                this.operation_types = response.data.operation_types
                this.all_affectation_igv_types = response.data.affectation_igv_types
                this.system_isc_types = response.data.system_isc_types
                this.discount_types = response.data.discount_types
                this.charge_types = response.data.charge_types
                this.attribute_types = response.data.attribute_types
                this.is_client = response.data.is_client;
                // this.filterItems()

            })

            this.$eventHub.$on('reloadDataItems', (item_id) => {
                this.reloadDataItems(item_id)
            })
        },
        methods: {
            filterMethod(query){

                let item = _.find(this.items, {'internal_id': query});

                if(item){
                    this.form.item_id = item.id
                    this.changeItem()
                }
                // console.log(item)
            },
            clickWarehouseDetail(){

                let item = _.find(this.items, {'id': this.form.item_id});

                this.warehousesDetail = item.warehouses
                this.showWarehousesDetail = true
            },
            filterItems(){
                this.items = this.items.filter(item => item.warehouses.length >0)
            },
            initForm() {
                this.errors = {};

                this.form = {
                   // category_id: [1],
                   // edit: false,
                    item_id: null,
                    item: {},
                    affectation_igv_type_id: null,
                    affectation_igv_type: {},
                    has_isc: false,
                    system_isc_type_id: null,
                    percentage_isc: 0,
                    suggested_price: 0,
                    quantity: 1,
                    unit_price_value: 0,
                    input_unit_price_value: 0,
                    unit_price: 0,
                    charges: [],
                    discounts: [],
                    attributes: [],
                    has_igv: null,
                    item_unit_types: [],
                    has_plastic_bag_taxes:false
                };

                this.activePanel = 0;
                this.total_item = 0;
                this.item_unit_type = {};
                this.has_list_prices = false;
            },
            // initializeFields() {
            //     this.form.affectation_igv_type_id = this.affectation_igv_types[0].id
            // },
            async create() {

                this.titleDialog = (this.recordItem) ? ' Editar Producto o Servicio' : ' Agregar Producto o Servicio';
                this.titleAction = (this.recordItem) ? ' Editar' : ' Agregar';
                let operation_type = await _.find(this.operation_types, {id: this.operationTypeId})
                this.affectation_igv_types = await _.filter(this.all_affectation_igv_types, {exportation: operation_type.exportation})


                if (this.recordItem) {
                    // console.log(this.recordItem)
                    this.form.item_id = await this.recordItem.item_id
                    await this.changeItem()
                    this.form.quantity = this.recordItem.quantity
                    this.form.unit_price_value = this.recordItem.input_unit_price_value
                    this.form.has_plastic_bag_taxes = (this.recordItem.total_plastic_bag_taxes > 0) ? true : false

                    if(this.isEditItemNote){
                        this.form.item.currency_type_id = this.currencyTypeIdActive
                        this.form.item.currency_type_symbol = (this.currencyTypeIdActive == 'PEN') ? 'S/':'$'
                    }


                    this.calculateQuantity()
                }

            },
            clickAddDiscount() {
                this.form.discounts.push({
                    discount_type_id: null,
                    discount_type: null,
                    description: null,
                    percentage: 0,
                    factor: 0,
                    amount: 0,
                    base: 0,
                    is_amount: false
                })
            },
            clickRemoveDiscount(index) {
                this.form.discounts.splice(index, 1)
            },
            changeDiscountType(index) {
                let discount_type_id = this.form.discounts[index].discount_type_id
                this.form.discounts[index].discount_type = _.find(this.discount_types, {id: discount_type_id})
            },
            clickAddCharge() {
                this.form.charges.push({
                    charge_type_id: null,
                    charge_type: null,
                    description: null,
                    percentage: 0,
                    factor: 0,
                    amount: 0,
                    base: 0
                })
            },
            clickRemoveCharge(index) {
                this.form.charges.splice(index, 1)
            },
            changeChargeType(index) {
                let charge_type_id = this.form.charges[index].charge_type_id
                this.form.charges[index].charge_type = _.find(this.charge_types, {id: charge_type_id})
            },
            clickAddAttribute() {
                this.form.attributes.push({
                    attribute_type_id: null,
                    description: null,
                    value: null,
                    start_date: null,
                    end_date: null,
                    duration: null,
                })
            },
            clickRemoveAttribute(index) {
                this.form.attributes.splice(index, 1)
            },
            changeAttributeType(index) {
                let attribute_type_id = this.form.attributes[index].attribute_type_id
                let attribute_type = _.find(this.attribute_types, {id: attribute_type_id})
                this.form.attributes[index].description = attribute_type.description
            },
            close() {
                this.initForm()
                this.$emit('update:showDialog', false)
            },
            changeItem() {

                this.form.item = _.find(this.items, {'id': this.form.item_id});
                this.form.item_unit_types = _.find(this.items, {'id': this.form.item_id}).item_unit_types
                this.form.unit_price_value = this.form.item.sale_unit_price;

                this.form.has_igv = this.form.item.has_igv;
                this.form.affectation_igv_type_id = this.form.item.sale_affectation_igv_type_id;
                this.form.quantity = 1;
                this.cleanTotalItem();
                this.showListStock = true

                //this.item_unit_types = this.form.item.item_unit_types;
                //(this.item_unit_types.length > 0) ? this.has_list_prices = true : this.has_list_prices = false;
            },
            focusTotalItem(change) {
                if(!change && this.form.item.calculate_quantity) {
                    this.$refs.total_item.$el.getElementsByTagName('input')[0].focus()
                    this.total_item = this.form.unit_price_value
                }
            },
            calculateQuantity() {
                // debugger
                if(this.form.item.calculate_quantity) {
                    //console.log('entro')
                    this.form.quantity = _.round((this.total_item / this.form.unit_price_value), 4)
                }
            },
            cleanTotalItem(){
                this.total_item = null
            },
            clickAddItem() {

                if (this.validateTotalItem().total_item) return;

                let unit_price = (this.form.has_igv)?this.form.unit_price_value:this.form.unit_price_value*1.18;

                this.form.input_unit_price_value = this.form.unit_price_value;

                this.form.unit_price = unit_price;
                this.form.item.unit_price = unit_price;
                this.form.item.presentation = this.item_unit_type;
                this.form.affectation_igv_type = _.find(this.affectation_igv_types, {'id': this.form.affectation_igv_type_id});
                // console.log
                this.row = calculateRowItem(this.form, this.currencyTypeIdActive, this.exchangeRateSale);
               // this.row.edit = false;
                this.initForm();
                //this.initializeFields()

                if (this.recordItem)
                {
                    this.row.indexi = this.recordItem.indexi
                }

                this.$emit('add', this.row);


                if (this.recordItem)
                {
                    this.close()
                }
            },
            validateTotalItem(){

                this.errors = {}

                if(this.form.item.calculate_quantity){
                    if(this.total_item < 0.01)
                        this.$set(this.errors, 'total_item', ['total venta item debe ser mayor a 0.01']);
                }

                return this.errors
            },
            reloadDataItems(item_id) {
                this.$http.get(`/${this.resource}/table/items`).then((response) => {
                    this.items = response.data
                    this.form.item_id = item_id
                    this.changeItem()
                    // this.filterItems()
                })
            },
            changePresentation() {
                let price = 0;

                this.item_unit_type = _.find(this.form.item.item_unit_types, {'id': this.form.item_unit_type_id});

                switch (this.item_unit_type.price_default) {
                    case 1: price = this.item_unit_type.price1
                        break;
                    case 2: price = this.item_unit_type.price2
                        break;
                    case 3: price = this.item_unit_type.price3
                        break;
                }

                this.form.unit_price_value = price;
                this.form.item.unit_type_id = this.item_unit_type.unit_type_id;
            },
            selectedPrice(row)
            {


                let valor = 0
                switch(row.price_default)
                {
                    case 1:
                        valor = row.price1
                        break
                    case 2:
                         valor = row.price2
                        break
                    case 3:
                         valor = row.price3
                        break

                }
                this.form.item_unit_type_id = row.id
                this.item_unit_type = row

                this.form.unit_price_value = valor
                this.form.item.unit_type_id = row.unit_type_id
                this.calculateQuantity()
            },
        }
    }

</script>
