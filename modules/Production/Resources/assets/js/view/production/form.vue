<template>
  <div class="card mb-0 pt-2 pt-md-0">
    <div class="card-header bg-info">
      <h3 class="my-0">Producción</h3>
    </div>
    <div class="tab-content">
      <form autocomplete="off" @submit.prevent="submit">
        <div class="form-body">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group" :class="{'has-danger': errors.item_id}">
                <label class="control-label">Producto</label>
                <el-select
                  v-model="form.item_id"
                  filterable
                  remote
                  :remote-method="searchRemoteItems"
                  :loading="loading_search"
                >
                  <el-option
                    v-for="option in items"
                    :key="option.id"
                    :value="option.id"
                    :label="option.description"
                  ></el-option>
                </el-select>
                <small
                  class="form-control-feedback"
                  v-if="errors.item_id"
                  v-text="errors.item_id[0]"
                ></small>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group" :class="{'has-danger': errors.quantity}">
                <label class="control-label">Cantidad</label>
                <el-input-number
                  v-model="form.quantity"
                  :min="0"
                  :controls="false"
                  :precision="precision"
                ></el-input-number>
                <small
                  class="form-control-feedback"
                  v-if="errors.quantity"
                  v-text="errors.quantity[0]"
                ></small>
              </div>
            </div>
            <div class="col-md-8">
              <div
                class="form-group"
                :class="{'has-danger': errors.warehouse_id}"
              >
                <label class="control-label">Almacén</label>
                <el-select v-model="form.warehouse_id" filterable>
                  <el-option
                    v-for="option in warehouses"
                    :key="option.id"
                    :value="option.id"
                    :label="option.description"
                  ></el-option>
                </el-select>
                <small
                  class="form-control-feedback"
                  v-if="errors.warehouse_id"
                  v-text="errors.warehouse_id[0]"
                ></small>
              </div>
            </div>

            <div class="col-md-8">
              <br />
              <div
                class="form-group"
                :class="{'has-danger': errors.inventory_transaction_id}"
              >
                <label class="control-label">Motivo traslado</label>
                <input
                  type="text"
                  class="form-control"
                  readonly
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
      </form>
    </div>
  </div>
</template>

<script>


export default {

    computed: {

    },
    data() {
        return {
            resource: 'production',
            loading_submit: false,
            errors: {},
            form: {
                items:[]
            },
            loading_search: false,
            warehouses: [],
            precision:2,
            items:[]
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
            this.form =  {
                item_id: null,
                warehouse_id: null,
                quantity: 0
            }
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

            if(this.form.quantity < 1) {
                return this.$message.error('La cantidad debe ser mayor a 0');
            }

            this.loading_submit = true

             await this.$http.post(`/${this.resource}`, this.form)
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
                .then(() => {
                    this.loading_submit = false
                })
         }


    }
}
</script>
