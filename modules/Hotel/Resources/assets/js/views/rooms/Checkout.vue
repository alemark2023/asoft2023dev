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
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-4 mb-3">
            <ul class="list-group">
              <li class="list-group-item active">Habitación</li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Habitación:</span>
                <strong>{{ this.rent.room.name }}</strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Tipo:</span>
                <strong>{{ this.rent.room.category.description }}</strong>
              </li>
            </ul>
          </div>
          <div class="col-12 col-md-4 mb-3">
            <ul class="list-group">
              <li class="list-group-item active">Cliente</li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Nombres:</span>
                <strong>{{ this.rent.customer.name }}</strong>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span># Documento:</span>
                <strong>{{ this.rent.customer.number }}</strong>
              </li>
            </ul>
          </div>
          <div class="col-12 col-md-4 mb-3">
            <ul class="list-group">
              <li class="list-group-item active">Entrada/Salida</li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Fecha/Hora Entrada:</span>
                <strong
                  >{{ this.rent.input_date | toDate }} -
                  {{ this.rent.input_time | toTime }}</strong
                >
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Fecha/Hora Salida:</span>
                <strong
                  >{{ this.rent.output_date | toDate }} -
                  {{ this.rent.output_time | toTime }}</strong
                >
              </li>
            </ul>
          </div>
          <div class="col-12">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr class="table-info">
                    <th></th>
                    <th colspan="5">Costo del alojamiento</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>#</td>
                    <td>Costo por tarifa</td>
                    <td>Cant. noches</td>
                    <td>Carga por salir tarde</td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>{{ room.item.unit_price | toDecimals }}</td>
                    <td>{{ room.item.quantity }}</td>
                    <td class="text-center">
                      <div class="d-d-inline-block" style="max-width: 120px">
                        <el-input v-model="arrears" type="number"></el-input>
                      </div>
                    </td>
                    <td></td>
                    <td class="text-center">
                      <div class="d-d-inline-block" style="max-width: 120px">
                        <el-input
                          v-model="total"
                          readonly
                          type="number"
                        ></el-input>
                      </div>
                    </td>
                  </tr>
                  <tr class="table-info">
                    <td></td>
                    <td colspan="5">Servicio al cuarto</td>
                  </tr>
                  <tr>
                    <td>#</td>
                    <td>Descripción</td>
                    <td>Precio unitario</td>
                    <td>Cantidad</td>
                    <td>Estado</td>
                    <td>Total</td>
                  </tr>
                  <tr
                    v-for="(it, i) in rent.items"
                    :key="i"
                    v-show="it.type === 'PRO'"
                  >
                    <td>{{ i + 1 }}</td>
                    <td>{{ it.item.item.description }}</td>
                    <td>{{ it.item.input_unit_price_value | toDecimals }}</td>
                    <td>{{ it.item.quantity | toDecimals }}</td>
                    <td>
                      {{ it.payment_status === "PAID" ? "PAGADO" : "DEBE" }}
                    </td>
                    <td>{{ it.item.total | toDecimals }}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td class="text-right" colspan="5">Pagado</td>
                    <td>
                      <h3 class="my-0">
                        <span class="badge badge-pill badge-info">{{
                          totalPaid | toDecimals
                        }}</span>
                      </h3>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-right" colspan="5">Debe</td>
                    <td>
                      <h3 class="my-0">
                        <span class="badge badge-pill badge-danger">{{
                          totalDebt | toDecimals
                        }}</span>
                      </h3>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="col-12 pt-3">
            <el-button
              :loading="loading"
              :disabled="loading"
              type="primary"
              @click="onGoToInvoice"
            >
              <i class="fa fa-save"></i>
              <span class="ml-2">Guardar y Generar Comprobante</span>
            </el-button>
          </div>
        </div>
      </div>
    </div>
    <el-dialog
      title="Operación realizada"
      :visible="showDialogOptions"
      width="30%"
      :close-on-click-modal="false"
      :close-on-press-escape="false"
      :show-close="false"
      append-to-body
    >
      <template v-if="showDialogOptions">
        <h6>Se ha creado el comprobante de la operación</h6>
        <el-link :href="response.links.pdf">Ver el PDF</el-link>
        <div class="text-center">
          <el-button type="primary" @click="onExitPage">Cerrar</el-button>
        </div>
      </template>
    </el-dialog>
  </div>
</template>

<script>
import moment from "moment";
import { calculateRowItem } from "../../../../../../../resources/js/helpers/functions";

export default {
  props: {
    rent: {
      type: Object,
      required: true,
    },
    customer: {
      type: Object,
      required: true,
    },
    token: {
      type: String,
      required: true,
    },
    room: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      showDialogOptions: false,
      title: "",
      arrears: 0,
      total: 0,
      loading: false,
      totalPaid: 0,
      totalDebt: 0,
      response: {},
    };
  },
  mounted() {
    this.title = `Checkout: Habitación ${this.rent.room.name}`;
    this.total = this.room.item.total;
    this.onCalculateTotals();
  },
  watch: {
    arrears(value) {
      if (isNaN(value)) {
        return;
      }
      if (value >= 0) {
        const total = parseFloat(this.room.item.total) + parseFloat(value);
        this.total = total;
        this.onCalculateTotals();
      }
    },
  },
  methods: {
    onExitPage() {
      window.location.href = "/hotels/reception";
    },
    onCalculateNewPriceRoom() {
      const old = this.rent.items
        .filter((i) => i.type === "HAB")
        .reduce((i) => i);

      const roomItem = { ...old };
      const oldItem = roomItem.item;
      delete roomItem.item;
      oldItem.quantity = 1;
      const newTotal = parseFloat(oldItem.total) + parseFloat(this.arrears);
      oldItem.input_unit_price_value = parseFloat(newTotal);
      oldItem.item.unit_price = parseFloat(newTotal);
      oldItem.unit_value = parseFloat(newTotal);
      const newItem = calculateRowItem(oldItem, "PEN", 3);
      roomItem.item = newItem;
      const items = this.rent.items.map((i) => {
        if (i.type === "HAB") {
          return roomItem;
        }
        return i;
      });
      return items;
    },
    onGoToInvoice() {
      const config = {
        headers: { Authorization: `Bearer ${this.token}` },
      };
      const date = moment();
      const itemsHard = this.onCalculateNewPriceRoom();
      const items = itemsHard.map((i) => {
        const description =
          i.type === "HAB"
            ? `${i.item.item.description} x ${this.room.item.quantity} noche(s)`
            : i.item.item.description;
        return {
          codigo_interno: i.item.item.internal_id || "",
          descripcion: description,
          codigo_producto_sunat: "",
          unidad_de_medida: i.item.item.unit_type_id,
          cantidad: i.item.quantity,
          valor_unitario: i.item.unit_price,
          codigo_tipo_precio: i.item.price_type_id,
          precio_unitario: i.item.unit_price,
          codigo_tipo_afectacion_igv: i.item.affectation_igv_type_id,
          total_base_igv: i.item.total_value,
          porcentaje_igv: 18,
          total_igv: 18,
          total_impuestos: i.item.total_igv,
          total_valor_item: i.item.total_value,
          total_item: i.item.total,
        };
      });
      const totalOpeGravada = itemsHard
        .map((i) => i.item.total_value)
        .reduce((a, b) => a + b, 0);
      const totalVenta = itemsHard
        .map((i) => i.item.total)
        .reduce((a, b) => a + b, 0);
      const tipoDoc =
        this.customer.identity_document_type_id == "1" ? "03" : "01";
      const serie =
        this.customer.identity_document_type_id == "1" ? "B001" : "F001";
      const payload = {
        serie_documento: serie,
        numero_documento: "#",
        fecha_de_emision: date.format("YYYY-MM-DD"),
        hora_de_emision: date.format("HH:mm:ss"),
        codigo_tipo_operacion: "0101",
        codigo_tipo_documento: tipoDoc,
        codigo_tipo_moneda: "PEN",
        fecha_de_vencimiento: date.format("YYYY-MM-DD"),
        numero_orden_de_compra: "",
        datos_del_cliente_o_receptor: {
          codigo_tipo_documento_identidad: this.customer
            .identity_document_type_id,
          numero_documento: this.customer.number,
          apellidos_y_nombres_o_razon_social: this.customer.name,
          codigo_pais: this.customer.country_id,
          ubigeo: this.customer.district_id || "",
          direccion: this.customer.address || "",
          correo_electronico: this.customer.email || "",
          telefono: this.customer.telephone || "",
        },
        totales: {
          total_exportacion: 0.0,
          total_operaciones_gravadas: totalOpeGravada,
          total_operaciones_inafectas: 0.0,
          total_operaciones_exoneradas: 0.0,
          total_operaciones_gratuitas: 0.0,
          total_igv: 18.0,
          total_impuestos: 18.0,
          total_valor: totalOpeGravada,
          total_venta: totalVenta,
        },
        items,
        informacion_adicional: "Forma de pago: Efectivo|Caja: 1",
      };
      this.loading = true;
      this.$http
        .post("/api/documents", payload, config)
        .then((response) => {
          const payloadFinalizedRent = {
            arrears: this.arrears,
          };
          this.loading = true;
          this.$http
            .post(
              `/hotels/reception/${this.rent.id}/rent/finalized`,
              payloadFinalizedRent
            )
            .then(() => {
              this.response = response.data;
              this.showDialogOptions = true;
            })
            .finally(() => (this.loading = false));
        })
        .finally(() => (this.loading = false));
    },
    onCalculateTotals() {
      this.totalPaid = this.rent.items
        .map((i) => {
          if (i.payment_status === "PAID") {
            return i.item.total;
          }
          return 0;
        })
        .reduce((a, b) => a + b, 0);
      const totalDebt = this.rent.items
        .map((i) => {
          if (i.payment_status === "DEBT") {
            return i.item.total;
          }
          return 0;
        })
        .reduce((a, b) => a + b, 0);
      this.totalDebt = totalDebt + parseFloat(this.arrears);
    },
    onGotoBack() {
      window.location.href = "/hotels/reception";
    },
  },
};
</script>
