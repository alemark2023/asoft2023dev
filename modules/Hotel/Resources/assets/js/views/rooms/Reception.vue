<template>
  <div>
    <div class="page-header pr-0">
      <h2>
        <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
      </h2>
      <ol class="breadcrumbs">
        <li class="active"><span>VISTA GENERAL RECEPCIÓN</span></li>
      </ol>
    </div>
    <div class="card mb-0">
      <div class="card-header bg-info">
        <h3 class="my-0">Vista general recepción</h3>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-lg-between">
          <div style="max-width: 120px">
            <el-select v-model="hotel_floor_id" placeholder="Piso" :disabled="loading" clearable>
              <el-option
                v-for="f in floors"
                :key="f.id"
                :value="f.id"
                :label="f.description"
              >
              </el-option>
            </el-select>
          </div>
          <el-button-group>
            <el-button
              v-for="st in roomStatus"
              :key="st"
              class="btn btn-sm"
              size="mini"
              :class="onGetColorStatus(st)"
              @click="onFilterByStatus(st)"
              :disabled="loading"
              >{{ st }}</el-button
            >
          </el-button-group>
        </div>
        <hr>
        <div class="row">
          <div class="col-6 col-md-3 mb-4" v-for="ro in items" :key="ro.id">
            <el-card :class="onGetColorStatus(ro.status)">
              <div slot="header" class="d-flex align-items-center justify-content-between">
                <span>{{ ro.name }}</span>
                <!-- <el-button v-if="ro.status !== 'MANTENIMIENTO'" style="float: right; padding: 3px 0" type="text"
                  >
                  <i class="fa fa-arrow-left"></i>
                  </el-button
                > -->
                <el-button v-if="ro.status === 'DISPONIBLE'" style="float: right;" type="primary" @click="onToRent(ro.id)"
                  >
                  <i class="fa fa-arrow-circle-left"></i>
                  </el-button
                >
              </div>
              <div class="d-flex justify-content-center align-items-center" v-if="ro.status === 'DISPONIBLE'">
                <i class="fa fa-bed fa-2x mt-2"></i>
                <span class="h3 ml-3">{{ ro.name }}</span>
              </div>
            </el-card>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    roomStatus: {
      type: Array,
      required: true,
    },
    floors: {
      type: Array,
      required: true,
    },
    rooms: {
      type: Array,
      required: true,
      default: []
    },
  },
  data() {
    return {
      hotel_floor_id: "",
      loading: false,
      items: []
    };
  },
  mounted() {
    this.items = this.rooms;
  },
  watch: {
    hotel_floor_id() {
      this.onFilterByStatus();
    }
  },
  methods: {
    onToRent(roomId) {
      window.location.href = `/hotels/reception/${roomId}/rent`;
    },
    onFilterByStatus(status = '') {
      this.loading = true;
      const params = {
        status,
        hotel_floor_id: this.hotel_floor_id
      }
      this.$http.get('/hotels/reception', { params }).then(response => {
        this.items = response.data.rooms;
      }).finally(() => {
        this.loading = false;
      });
    },
    onGetColorStatus(status) {
      if (status === "DISPONIBLE") {
        return "btn-success";
      } else if (status === "MANTENIMIENTO") {
        return "btn-warning";
      } else if (status === "OCUPADO") {
        return "btn-danger";
      } else if (status === "LIMPIEZA") {
        return "btn-info";
      }
      return "";
    },
  },
};
</script>
