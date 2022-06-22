<template>
  <el-dialog :close-on-click-modal="false"
             :title="titleDialog"
             :visible="showDialog"
             top="7vh"
             :append-to-body="true"
             @close="clickClose"
             @open="create">
    <form autocomplete="off"
          @submit.prevent="clickAddItem">
      <div class="form-body">
        <div class="row">
          <div class="col-md-7 col-lg-7 col-xl-7 col-sm-7">
            <div id="custom-select"
                 :class="{'has-danger': errors.item_id}"
                 class="form-group">
              <label class="control-label">
                Producto/Servicio
                <a v-if="can_add_new_product"
                   href="#"
                   @click.prevent="showDialogNewItem = true">
                  [+ Nuevo]
                </a>
              </label>
              <template v-if="!search_item_by_barcode" id="select-append">
                <el-input id="custom-input">
                  <el-select v-model="itemId"
                             id="selectItem"
                             slot="prepend"
                             :disabled="recordItem != null"
                             :loading="loading_search"
                             :remote-method="searchRemoteItems"
                             filterable
                             placeholder="Buscar"
                             popper-class="el-select-items"
                             remote
                             @change="changeItem">
                    <el-tooltip
                      v-for="option in items"
                      :key="option.id"
                      placement="left">
                      <div slot="content"
                           v-html="itemSlotTooltipView(option)"></div>
                      <el-option :label="itemOptionDescriptionView(option)"
                                 :value="option.id"></el-option>
                    </el-tooltip>
                  </el-select>
                  <el-tooltip
                    slot="append"
                    :disabled="recordItem != null"
                    class="item"
                    content="Ver Stock del Producto"
                    effect="dark"
                    placement="bottom">
                    <el-button
                      :disabled="isEditItemNote"
                      @click.prevent="clickWarehouseDetail()">
                      <i class="fa fa-search"></i>
                    </el-button>
                  </el-tooltip>
                </el-input>
              </template>
              <template v-else>
                <el-input id="custom-input">
                  <el-select
                    id="select-width"
                    ref="selectBarcode"
                    slot="prepend"
                    v-model="itemId"
                    :disabled="recordItem != null"
                    :loading="loading_search"
                    :remote-method="searchRemoteItems"
                    remote
                    filterable
                    placeholder="Buscar"
                    popper-class="el-select-items"
                    value-key="id"
                    @change="changeItem">
                    <el-option
                      v-for="option in items"
                      :key="option.id"
                      :label="option.full_description"
                      :value="option.id"></el-option>
                  </el-select>
                  <el-tooltip
                    slot="append"
                    :disabled="recordItem != null"
                    class="item"
                    content="Ver Stock del Producto"
                    effect="dark"
                    placement="bottom">
                    <el-button
                      :disabled="isEditItemNote"
                      @click.prevent="clickWarehouseDetail()">
                      <i class="fa fa-search"></i>
                    </el-button>
                  </el-tooltip>
                </el-input>
              </template>

              <template v-if="!is_client">
                <el-checkbox v-model="search_item_by_barcode"
                             :disabled="recordItem != null">Buscar por código de barras
                </el-checkbox>
                <br>
              </template>
              <el-checkbox v-model="form.has_plastic_bag_taxes"
                           v-if="showDiscounts"
                           :disabled="isEditItemNote">Impuesto a la Bolsa Plástica
              </el-checkbox>
              <small v-if="errors.item_id"
                     class="form-control-feedback"
                     v-text="errors.item_id[0]"></small>
            </div>
          </div>
          <div class="col-md-5">
            <x-input label="Afectación Igv" :error="errors.affectation_igv_type_id">
              <el-select v-model="form.affectation_igv_type_id"
                         :disabled="!change_affectation_igv_type_id"
                         filterable>
                <el-option v-for="option in affectation_igv_types"
                           :key="option.id"
                           :label="option.description"
                           :value="option.id"></el-option>
              </el-select>
              <el-checkbox v-model="change_affectation_igv_type_id"
                           :disabled="recordItem != null">Editar
              </el-checkbox>
            </x-input>
          </div>

          <div class="col-md-4 col-sm-4">
            <x-input label="Cantidad" :error="errors.quantity">
              <el-input v-model="form.quantity"
                        :disabled="form.calculate_quantity"
                        @blur="validateQuantity"
                        @input.native="changeValidateQuantity">
                <el-button slot="prepend"
                           :disabled="form.quantity < 0.01"
                           icon="el-icon-minus"
                           style="padding-right: 5px ;padding-left: 12px"
                           @click="clickDecrease"
                           v-if="!form.calculate_quantity"></el-button>
                <el-button slot="append"
                           icon="el-icon-plus"
                           style="padding-right: 5px ;padding-left: 12px"
                           @click="clickIncrease"
                           v-if="!form.calculate_quantity"></el-button>
              </el-input>
            </x-input>
          </div>

          <div class="col-md-4 col-sm-4">
            <x-input label="Precio Unitario"
                     :tooltip-content="itemLastPrice"
                     :error="errors.unit_price">
              <el-input v-model="form.unit_price"
                        id="form_unit_price"
                        @input="calculateQuantity">
                <template v-if="form.currency_type_symbol"
                          slot="prepend">
                  {{ form.currency_type_symbol }}
                </template>
              </el-input>
            </x-input>
          </div>
          <div class="col-md-4 col-sm-4" v-if="!form.calculate_quantity">
            <x-input label="Total">
              <el-input v-model="form.total"
                        readonly
                        @input="calculateTotal"></el-input>
            </x-input>
          </div>
          <div class="col-md-4 col-sm-4" v-else>
            <x-input label="Total venta producto" :error="errors.total">
              <el-input v-model="form.total"
                        id="form_total"
                        :min="0.01"
                        @input="calculateQuantity">
                <template v-if="form.currency_type_symbol"
                          slot="prepend">
                  {{ form.currency_type_symbol }}
                </template>
              </el-input>
            </x-input>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-12">
            <label class="control-label">Atributo extra (visible en PDF)</label>
          </div>
          <div class="col-md-6">
            <x-input :error="errors.extra_attr_name">
              <el-input v-model="form.extra_attr_name"></el-input>
            </x-input>
          </div>
          <div class="col-md-6">
            <x-input :error="errors.extra_attr_value">
              <el-input v-model="form.extra_attr_value"></el-input>
            </x-input>
          </div>
          <div class="col-md-12 col-sm-12 mt-2" v-if="config.edit_name_product">
            <x-input label="Nombre producto en PDF" :error="errors.name_product_pdf">
              <vue-ckeditor
                v-model="form.name_product_pdf"
                :editors="editors"
                type="classic"></vue-ckeditor>
            </x-input>
          </div>
          <template v-if="!is_client">
<!--            <div v-if="has_list_prices"-->
<!--                 class="col-md-12">-->
<!--              <div class="table-responsive"-->
<!--                   style="margin:3px">-->
<!--                <h5 class="separator-title">-->
<!--                  Lista de Precios-->
<!--                  <el-tooltip class="item"-->
<!--                              content="Aplica para realizar compra/venta en presentacion de diferentes precios y/o cantidades"-->
<!--                              effect="dark"-->
<!--                              placement="top">-->
<!--                    <i class="fa fa-info-circle"></i>-->
<!--                  </el-tooltip>-->
<!--                </h5>-->
<!--                <table class="table">-->
<!--                  <thead>-->
<!--                  <tr>-->
<!--                    <th class="text-center">Unidad</th>-->
<!--                    <th class="text-center">Descripción</th>-->
<!--                    <th class="text-center">Factor</th>-->
<!--                    <th class="text-center">Precio 1</th>-->
<!--                    <th class="text-center">Precio 2</th>-->
<!--                    <th class="text-center">Precio 3</th>-->
<!--                    <th class="text-center">Precio Default</th>-->
<!--                    <th></th>-->
<!--                  </tr>-->
<!--                  </thead>-->
<!--                  <tbody>-->
<!--                  <tr v-for="(row, index) in form.item_unit_types"-->
<!--                      :key="index">-->
<!--                    <td class="text-center">{{ row.unit_type_id }}</td>-->
<!--                    <td class="text-center">{{ row.description }}</td>-->
<!--                    <td class="text-center">{{ row.quantity_unit }}</td>-->
<!--                    <td class="text-center">{{ row.price1 }}</td>-->
<!--                    <td class="text-center">{{ row.price2 }}</td>-->
<!--                    <td class="text-center">{{ row.price3 }}</td>-->
<!--                    <td class="text-center">Precio {{ row.price_default }}</td>-->
<!--                    <td class="series-table-actions text-right">-->
<!--                      <button class="btn waves-effect waves-light btn-xs btn-success"-->
<!--                              type="button"-->
<!--                              @click.prevent="selectedPrice(row)">-->
<!--                        <i class="el-icon-check"></i>-->
<!--                      </button>-->
<!--                    </td>-->
<!--                  </tr>-->
<!--                  </tbody>-->
<!--                </table>-->
<!--              </div>-->
<!--            </div>-->

            <div class="col-md-12 mt-2">
              <el-collapse v-model="activePanel">
                <el-collapse-item name="1" title="+ Agregar Descuentos/Cargos/Atributos especiales">
                  <div v-if="discount_types.length > 0">
                    <label class="control-label">Descuentos
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
                      <tr v-for="(row, index) in form.discounts" :key="index">
                        <td>
                          <el-select v-model="row.discount_type_id">
                            <el-option v-for="option in discount_types"
                                       :key="option.id"
                                       :label="option.description"
                                       :value="option.id"></el-option>
                          </el-select>
                        </td>
                        <td>
                          <el-input v-model="row.description"></el-input>
                        </td>
                        <td>
                          <div style="display: flex">
                            <el-button type="primary" @click="row.is_amount = !row.is_amount" style="margin-right: 4px">
                              <template v-if="row.is_amount">
                                {{ form.currency_type_symbol }}
                              </template>
                              <template v-else>
                                %
                              </template>
                            </el-button>
                            <el-input v-model="row.amount">
                            </el-input>
                          </div>
                        </td>
                        <td>
                          <el-button type="danger" @click="clickRemoveDiscount(index)">X</el-button>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
<!--                  <div v-if="charge_types.length > 0">-->
<!--                    <label class="control-label">Cargos-->
<!--                      <a href="#" @click.prevent="clickAddCharge">[+ Agregar]</a>-->
<!--                    </label>-->
<!--                    <table class="table">-->
<!--                      <thead>-->
<!--                      <tr>-->
<!--                        <th>Tipo</th>-->
<!--                        <th>Descripción</th>-->
<!--                        <th>Porcentaje</th>-->
<!--                        <th></th>-->
<!--                      </tr>-->
<!--                      </thead>-->
<!--                      <tbody>-->
<!--                      <tr v-for="(row, index) in form.charges"-->
<!--                          :key="index">-->
<!--                        <td>-->
<!--                          <el-select v-model="row.charge_type_id"-->
<!--                                     @change="changeChargeType(index)">-->
<!--                            <el-option v-for="option in charge_types"-->
<!--                                       :key="option.id"-->
<!--                                       :label="option.description"-->
<!--                                       :value="option.id"></el-option>-->
<!--                          </el-select>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                          <el-input v-model="row.description"></el-input>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                          <el-input v-model="row.percentage"></el-input>-->
<!--                        </td>-->
<!--                        <td>-->
<!--                          <button class="btn btn-danger"-->
<!--                                  type="button"-->
<!--                                  @click.prevent="clickRemoveCharge(index)">x-->
<!--                          </button>-->
<!--                        </td>-->
<!--                      </tr>-->
<!--                      </tbody>-->
<!--                    </table>-->
<!--                  </div>-->
                  <div v-if="attribute_types.length > 0">
                    <label class="control-label">Atributos
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
                      <tr v-for="(row, index) in form.attributes"
                          :key="index">
                        <td>
                          <el-select v-model="row.attribute_type_id"
                                     filterable
                                     @change="changeAttributeType(index)">
                            <el-option
                              v-for="option in attribute_types"
                              :key="option.id"
                              :label="option.description"
                              :value="option.id"></el-option>
                          </el-select>
                        </td>
                        <td>
                          <el-input v-model="row.value"
                                    @input="inputAttribute(index)"></el-input>
                        </td>
                        <td>
                          <button class="btn btn-danger"
                                  type="button"
                                  @click.prevent="clickRemoveAttribute(index)">x
                          </button>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </el-collapse-item>
              </el-collapse>
            </div>
          </template>
        </div>
      </div>
      <!-- @todo: Mejorar evitando duplicar codigo -->
      <!-- Mostrar en cel -->

      <div class="row hidden-md-up form-actions text-center">
        <div class="col-12">
          &nbsp;
        </div>
        <div class="col-6">
          <el-button class="form-control"
                     @click.prevent="clickClose()">Cerrar
          </el-button>
        </div>
        <div class="col-6">
          <el-button v-if="form.item_id"
                     class="add form-control btn btn-primary"
                     native-type="submit"
                     type="primary">
            {{ titleAction }}
          </el-button>
        </div>
      </div>
      <!-- @todo: Mejorar evitando duplicar codigo -->
      <!-- Mostrar en cel -->
      <!-- @todo: Mejorar evitando duplicar codigo -->
      <!-- Ocultar en cel -->

      <div class="form-actions text-right pt-2  hidden-sm-down">
        <el-button @click.prevent="clickClose()">Cerrar</el-button>
        <el-button v-if="form.item_id"
                   class="add"
                   native-type="submit"
                   type="primary">
          {{ titleAction }}
        </el-button>
      </div>
    </form>
    <item-form :external="true"
               :showDialog.sync="showDialogNewItem"></item-form>
<!--    <warehouses-detail-->
<!--      :isUpdateWarehouseId="isUpdateWarehouseId"-->
<!--      :showDialog.sync="showWarehousesDetail"-->
<!--      :warehouses="warehousesDetail">-->
<!--    </warehouses-detail>-->
  </el-dialog>
</template>
<style>
.el-select-dropdown {
  margin-right: 5% !important;
  max-width: 80% !important;
}
</style>

<script>

import itemForm from '../../items/form.vue'

import {calculateRowItemOther} from '../../../../helpers/functions'
import WarehousesDetail from './warehouses.vue'

import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import VueCkeditor from 'vue-ckeditor5'
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import {ItemOptionDescription, ItemSlotTooltip} from "../../../../helpers/modal_item";
import XInput from "../../../../components/XInput";
import { uuid } from 'vue-uuid';

export default {
  name: 'QuotationItem',
  props: [
    'recordItem',
    'showDialog',
    'currencyTypeIdActive',
    'exchangeRateSale',
    'typeUser',
    'configuration',
    'displayDiscount',
    'customerId'
  ],
  components: {
    XInput,
    itemForm,
    WarehousesDetail,
    'vue-ckeditor': VueCkeditor.component
  },
  data() {
    return {
      config: {},
      showDiscounts: true,
      extra_temp: undefined,
      operationTypeId: null,
      isEditItemNote: false,
      can_add_new_product: false,
      loading_search: false,
      titleAction: '',
      is_client: false,
      titleDialog: 'Agregar Producto o Servicio',
      resource: 'quotations',
      showDialogNewItem: false,
      has_list_prices: false,
      errors: {},
      form: {},
      all_items: [],
      items: [],
      operation_types: [],
      all_affectation_igv_types: [],
      aux_items: [],
      affectation_igv_types: [],
      system_isc_types: [],
      discount_types: [],
      charge_types: [],
      attribute_types: [],
      use_price: 1,
      change_affectation_igv_type_id: false,
      activePanel: 0,
      item_unit_types: [],
      item_unit_type: {},
      showWarehousesDetail: false,
      warehousesDetail: [],
      showListStock: false,
      search_item_by_barcode: false,
      // isUpdateWarehouseId: null,
      showDialogLots: false,
      showDialogSelectLots: false,
      lots: [],
      editors: {
        classic: ClassicEditor
      },
      readonly_total: 0,
      itemLastPrice: null,
      itemId: null,
      //item_unit_type: {}
    }
  },
  created() {
    this.initForm();
  },
  mounted() {
    this.getTables()
    this.$eventHub.$on('reloadDataItems', (item_id) => {
      this.reloadDataItems(item_id)
    })

    this.$eventHub.$on('selectWarehouseId', (warehouse_id) => {
      this.form.warehouse_id = warehouse_id
    })
    this.canCreateProduct();
  },
  computed: {
    edit_unit_price() {
      if (this.typeUser === 'admin') {
        return true
      }
      if (this.typeUser === 'seller') {
        return this.config.allow_edit_unit_price_to_seller;
      }
      return false;
    },
  },
  methods: {
    initForm() {
      this.itemId = null;
      this.items = [];
      this.errors = {};
      this.form = {};
    },
    async create() {
      this.initForm();
      this.titleDialog = (this.recordItem) ? ' Editar Producto o Servicio' : ' Agregar Producto o Servicio';
      this.titleAction = (this.recordItem) ? ' Editar' : ' Agregar';
      if (this.operation_types !== undefined) {
        let operation_type = await _.find(this.operation_types, {id: this.operationTypeId})
        if (operation_type !== undefined) {
          this.affectation_igv_types = await _.filter(this.all_affectation_igv_types, {exportation: operation_type.exportation})
        }
      }
      if (this.recordItem) {
        this.itemId = this.recordItem.item_id;
        this.items = [];
        await this.$http.get(`/store/search_item/${this.itemId}`)
          .then(response => {
            this.items = response.data;
          })
        // this.form = _.find(this.items, {'id': this.itemId});
        // this.form = this.items[0];
        this.form = Object.assign({}, this.items[0], this.recordItem);
        console.log(this.form);
        this.calculateTotal();
        // await this.reloadDataItems(this.recordItem.item_id)
        // this.form.item_id = await this.recordItem.item_id
        // await this.changeItem()
        // this.form.quantity = this.recordItem.quantity
        // this.form.unit_price = this.recordItem.unit_price
        // this.form.unit_price_value = this.recordItem.input_unit_price_value
        // this.form.unit_price_value = this.recordItem.input_unit_price_value
        // if (this.recordItem.item.has_igv == false) {
        //     this.form.unit_price = this.recordItem.total_base_igv
        // }

        // this.setHasIgvUpdate()
        // this.form.has_plastic_bag_taxes = (this.recordItem.total_plastic_bag_taxes > 0) ? true : false
        // this.form.warehouse_id = this.recordItem.warehouse_id
        //
        // if (this.recordItem.item.change_free_affectation_igv) {
        //
        //   this.form.affectation_igv_type_id = '15'
        //   this.form.item.change_free_affectation_igv = true
        //
        // } else {
        //   if (this.recordItem.item.original_affectation_igv_type_id) {
        //     this.form.affectation_igv_type_id = this.recordItem.item.original_affectation_igv_type_id
        //   }
        // }
        // this.calculateQuantity()
      } else {
        // this.isUpdateWarehouseId = null
        await this.searchRemoteItems('');
        this.$nextTick(_ => {
          this.focusSelectItem();
        })
      }


    },
    itemSlotTooltipView(item) {
      let label = 'Precio: ' + item.unit_price_label;
      if (item.warehouse_name) {
        label += '<br>Almacén: ' + item.warehouse_name
      }
      if (item.brand_name) {
        label += '<br>Marca: ' + item.brand_name
      }
      if (item.stock) {
        label += '<br>Stock: ' + item.stock
      }
      return label;
    },
    itemOptionDescriptionView(item) {
      let label = item.name;
      if (item.internal_id) {
        label = item.internal_id + ' - ' + label;
      }
      if (item.brand_name) {
        label += ' - ' + item.brand_name;
      }

      return label;
    },
    getTables() {
      this.$http.get(`/store/get_item_tables`).then(response => {
        let data = response.data;
        this.config = data.config
        this.operation_types = data.operation_types;
        this.all_affectation_igv_types = data.affectation_igv_types;
        this.affectation_igv_types = data.affectation_igv_types;
        this.system_isc_types = data.system_isc_types;
        this.discount_types = data.discount_types;
        this.charge_types = data.charge_types;
        this.attribute_types = data.attribute_types;
        this.is_client = data.is_client;
      })
    },
    canCreateProduct() {
      if (this.typeUser === 'admin') {
        this.can_add_new_product = true
      } else if (this.typeUser === 'seller') {
        if (this.config !== undefined && this.config.seller_can_create_product !== undefined) {
          this.can_add_new_product = this.config.seller_can_create_product;
        }
      }
      return this.can_add_new_product;
    },
    validateQuantity() {
      if (!this.form.quantity) {
        this.setMinQuantity()
      }
      if (isNaN(Number(this.form.quantity))) {
        this.setMinQuantity()
      }
      if (typeof parseFloat(this.form.quantity) !== 'number') {
        this.setMinQuantity()
      }
      if (this.form.quantity <= this.getMinQuantity()) {
        this.setMinQuantity()
      }
      this.calculateTotal()
    },
    changeValidateQuantity() {
      this.calculateTotal()
    },
    getMinQuantity() {
      return 0.01
    },
    setMinQuantity() {
      this.form.quantity = this.getMinQuantity()
    },
    clickDecrease() {
      this.form.quantity = parseInt(this.form.quantity) - 1;
      if (this.form.quantity <= this.getMinQuantity()) {
        this.setMinQuantity()
        return
      }
      this.calculateTotal()
    },
    clickIncrease() {
      this.form.quantity = parseInt(this.form.quantity + 1)
      this.calculateTotal()
    },
    async searchRemoteItems(input) {
      // if (input.length > 2) {
        this.loading_search = true
        await this.$http.post(`/store/search_items`, {
          'search': input,
          'search_by_barcode': this.search_item_by_barcode
        })
          .then(response => {
            this.items = response.data;
            this.enabledSearchItemsBarcode()
          })
        this.loading_search = false;
      // } else {
      //   this.filterItems()
      // }
    },
    filterItems() {
      this.items = this.all_items
    },
    enabledSearchItemsBarcode() {
      if (this.search_item_by_barcode) {
        this.$refs.selectBarcode.$data.selectedLabel = '';
        if (this.items.length == 1) {
          this.form.item_id = this.items[0].id;
          this.$refs.selectBarcode.blur();
          this.changeItem();
        }
      }
    },
    filterMethod(query) {
      let item = _.find(this.items, {'internal_id': query});
      if (item) {
        this.form.item_id = item.id
        this.changeItem()
      }
    },
    clickWarehouseDetail() {
      if (!this.form.item_id) {
        return this.$message.error('Seleccione un item');
      }
      let item = _.find(this.items, {'id': this.form.item_id});
      this.warehousesDetail = item.warehouses
      this.showWarehousesDetail = true
    },
    setHasIgvUpdate() {
      if (this.recordItem.item) {
        this.form.has_igv = this.recordItem.item.has_igv
        if (this.form.item) this.form.item.has_igv = this.recordItem.item.has_igv
      }
    },
    clickAddDiscount() {
      this.form.discounts.push({
        discount_type_id: null,
        // discount_type: null,
        description: null,
        // percentage: 0,
        // factor: 0,
        // base: 0,
        is_amount: false,
        amount: 0,
      })
    },
    clickRemoveDiscount(index) {
      this.form.discounts.splice(index, 1)
    },
    // changeDiscountType(index) {
    //   let discount_type_id = this.form.discounts[index].discount_type_id
    //   this.form.discounts[index].discount_type = _.find(this.discount_types, {id: discount_type_id})
    // },
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
      this.inputAttribute(index)
    },
    inputAttribute(index) {

      let value = this.form.attributes[index].value
      let hotelAttributes = ['4003', '4004']

      this.form.attributes[index].start_date = (hotelAttributes.includes(this.form.attributes[index].attribute_type_id)) ? value : null

    },
    async changeItem() {
      this.form = _.find(this.items, {'id': this.itemId});
      this.form = Object.assign({}, this.form, {
        'index': uuid.v1(),
        'item_id': this.itemId,
        'quantity': 1,
        'discounts': [],
        'charges': [],
        'attributes': [],
        'extra_attr_name': 'Tiempo de entrega',
        'extra_attr_value': '',
        'total': 0
      });

      this.calculateTotal();

      this.$nextTick(_ => {
        if (this.form.calculate_quantity) {
          const form_total = document.getElementById('form_total');
          form_total.focus();
          form_total.select();
        } else {
          const form_unit_price = document.getElementById('form_unit_price');
          form_unit_price.focus();
          form_unit_price.select();
        }
      });
    },
    calculateQuantity() {
      if (this.form.calculate_quantity) {
        this.form.quantity = _.round((this.form.total / this.form.unit_price), 4)
      } else {
        this.calculateTotal();
      }
    },
    calculateTotal() {
      this.form.total = _.round((this.form.quantity * this.form.unit_price), 2)
    },
    async clickAddItem() {
      this.$emit('success', this.form);

      if(this.recordItem) {
        this.clickClose();
      } else {
        this.initForm();
        await this.searchRemoteItems('');
        this.focusSelectItem();
      }
      // this.close();
    },
    async reloadDataItems(item_id) {
      let params = {};
      if (this.item_search_extra_parameters !== undefined) {
        if (this.item_search_extra_parameters.only_service !== undefined) {
          params.only_service = 1;
        }
      }
      if (!item_id) {
        await this.$http.get(`/${this.resource}/table/items`, {params}).then((response) => {
          this.items = response.data
          this.form.item_id = item_id
          // if(item_id) this.changeItem()
          // this.filterItems()
        })
      } else {
        await this.$http.get(`/${this.resource}/search/item/${item_id}`).then((response) => {
          this.items = response.data.items
          this.form.item_id = item_id
          this.changeItem()
        })
      }
    },
    changePresentation() {
      let price = 0;
      this.item_unit_type = _.find(this.form.item.item_unit_types, {'id': this.form.item_unit_type_id});
      switch (this.item_unit_type.price_default) {
        case 1:
          price = this.item_unit_type.price1
          break;
        case 2:
          price = this.item_unit_type.price2
          break;
        case 3:
          price = this.item_unit_type.price3
          break;
      }
      this.form.unit_price = price;
      this.form.unit_price_value = price;
      this.form.item.unit_type_id = this.item_unit_type.unit_type_id;
    },
    selectedPrice(row) {
      let valor = 0
      switch (row.price_default) {
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
      this.form.unit_price = valor
      this.form.unit_price_value = valor
      this.form.item.unit_type_id = row.unit_type_id
      this.calculateQuantity()
      this.getTables()
    },
    addRowLotGroup(id) {
      this.form.IdLoteSelected = id
    },
    clickLotGroup() {
      this.showDialogLots = true
    },
    focusSelectItem() {
      document.getElementById('selectItem').focus();
    },
    async getLastPriceItem() {
      this.itemLastPrice = null
      if (this.config.show_last_price_sale) {
        if (this.customerId && this.form.item_id) {
          const params = {
            'type_document': 'QUOTATION',
            'customer_id': this.customerId,
            'item_id': this.form.item_id
          }
          await this.$http.get(`/items/last-sale`, {params}).then((response) => {
            if (response.data.unit_price) {
              this.itemLastPrice = `Último precio de venta: ${response.data.unit_price}`
            }

          })
        }
      }
    },
    clickClose() {
      this.$emit('update:showDialog', false)
    },
  }
}

</script>
