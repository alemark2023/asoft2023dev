<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Producto Fabricado</h3>
        </div>
        <div class="tab-content">
            <form autocomplete="off"
                  @submit.prevent="submit">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.item_id}"
                                 class="form-group">
                                <label class="control-label">Producto </label>
                                <el-select
                                    v-model="form.item_id"
                                    :loading="loading_search"
                                    :remote-method="searchRemoteItems"
                                    filterable
                                    remote
                                    @change="changeItem"
                                >
                                    <el-option
                                        v-for="option in items"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.item_id"
                                    class="form-control-feedback"
                                    v-text="errors.item_id[0]"
                                ></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div
                                :class="{'has-danger': errors.warehouse_id}"
                                class="form-group"
                            >
                                <label class="control-label">Almacén</label>
                                <el-select v-model="form.warehouse_id"
                                           filterable>
                                    <el-option
                                        v-for="option in warehouses"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.warehouse_id"
                                    class="form-control-feedback"
                                    v-text="errors.warehouse_id[0]"
                                ></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.quantity}"
                                 class="form-group">
                                <label class="control-label">Cantidad</label>
                                <el-input-number
                                    v-model="form.quantity"
                                    :controls="false"
                                    :min="0"
                                    :precision="precision"
                                ></el-input-number>
                                <small
                                    v-if="errors.quantity"
                                    class="form-control-feedback"
                                    v-text="errors.quantity[0]"
                                ></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div
                                :class="{'has-danger': errors.inventory_transaction_id}"
                                class="form-group"
                            >
                                <label class="control-label">Motivo traslado</label>
                                <input
                                    class="form-control"
                                    readonly
                                    type="text"
                                    value="Ingreso de producción"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button
                        :loading="loading_submit"
                        native-type="submit"
                        type="primary"
                    >Guardar
                    </el-button>
                </div>
                <div class="col-12 col-md-12 mt-3"  v-if="supplies.length > 0">
                    <h3 class="my-0">Lista de materiales</h3>

                    <div class="col-md-12 mt-3 table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th class="text-center">Almacen</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="row in supplies">
                                <th>{{ row.individual_item.description }}</th>
                                <th>{{ row.quantity }}</th>
                                <th>

                                    <el-select v-model="row.warehouse_id"
                                               filterable>
                                        <el-option
                                            v-for="option in warehouses"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                </th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>



            </form>
        </div>
    </div>
</template>

<script>


export default {

    computed: {},
    data() {
        return {
            resource: 'production',
            loading_submit: false,
            errors: {},
            supplies : {},
            form: {
                items: []
            },
            loading_search: false,
            warehouses: [],
            precision: 2,
            items: []
        }
    },
    created() {

        this.initForm()
        this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                let data = response.data
                this.warehouses = data.warehouses
                this.items = data.items
            })
            .then(() => {

            })
    },
    methods: {
        initForm() {
            this.form = {
                item_id: null,
                warehouse_id: null,
                quantity: 0
            }
            this.supplies = {};

        },
        async searchRemoteItems(search) {
            this.loading_search = true;
            this.items = [];
            await this.$http.post(`/${this.resource}/search_items`, {'search': search})
                .then(response => {
                    this.items = response.data.items
                })
            this.loading_search = false;
        },
        async submit() {

            if (this.form.quantity < 1) {
                return this.$message.error('La cantidad debe ser mayor a 0');
            }

            this.loading_submit = true

            this.form.supplies  =this.supplies
            await this.$http.post(`/${this.resource}/create`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        this.initForm()
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        console.log(error)
                    }
                })
                .finally(() => {
                    this.loading_submit = false
                })
        },
        changeItem() {
            let item =  _.find(this.items, {'id': this.form.item_id})
            this.supplies = item.supplies

        },


    }
}
</script>
