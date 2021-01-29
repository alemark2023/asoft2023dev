<template>
  <div>
    <div class="page-header pr-0">
      <h2>
        <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
      </h2>
      <ol class="breadcrumbs">
        <li class="active"><span>RECEPCIÓN</span></li>
      </ol>
      <div class="right-wrapper pull-right">
        <div class="btn-group flex-wrap">
          <button
            type="button"
            class="btn btn-custom btn-sm mt-2 mr-2"
            @click="onGotoBack"
          >
            <i class="fa fa-arrow-left"></i> Atras
          </button>
        </div>
      </div>
    </div>
    <div class="card mb-0">
      <div class="card-header bg-info">
        <h3 class="my-0">{{ title }}</h3>
      </div>
    </div>
    <document-form-item
      :showDialog.sync="showDialogAddItem"
      :recordItem="recordItem"
      :isEditItemNote="false"
      :operation-type-id="form.operation_type_id"
      :currency-type-id-active="form.currency_type_id"
      :exchange-rate-sale="form.exchange_rate_sale"
      :typeUser="typeUser"
      :configuration="configuration"
      :editNameProduct="configuration.edit_name_product"
      @add="onAddItem"
    ></document-form-item>
  </div>
</template>

<script>
import DocumentFormItem from "../../../../../../../resources/js/views/tenant/documents/partials/item.vue";

export default {
  components: {
    DocumentFormItem,
  },
  props: {
    rent: {
      type: Object,
      required: true,
    },
    configuration: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      title: "",
      showDialogAddItem: false,
      recordItem: null,
      form: {},
      typeUser: "admin",
    };
  },
  created() {
    this.title = `Habitación ${this.rent.room.name} - Agregar productos`;
  },
  methods: {
    onAddItem() {},
    onGotoBack() {},
  },
};
</script>
