<template>
  <div>
    <div class="page-header pr-0">
      <h2>
        <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
      </h2>
      <ol class="breadcrumbs">
        <li class="active"><span>REGISTRO DE HABITACIONES</span></li>
      </ol>
      <div class="right-wrapper pull-right">
        <div class="btn-group flex-wrap">
          <button
            type="button"
            class="btn btn-custom btn-sm mt-2 mr-2"
            @click="onCreate"
          >
            <i class="fa fa-plus-circle"></i> Nuevo
          </button>
        </div>
      </div>
    </div>
    <div class="card mb-0">
      <div class="card-header bg-info">
        <h3 class="my-0">Listado de habitaciones</h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th></th>
                <th>Habitación</th>
                <th>Categoría</th>
                <th>Piso</th>
                <th>Tarifas</th>
                <th>Estado</th>
                <th>Visible</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in items" :key="item.id" :class="{ 'table-info': !item.active }">
                <td class="text-right">{{ item.id }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.category.description }}</td>
                <td>{{ item.floor.description }}</td>
                <td>
                  <el-button>
                    <i class="fa fa-money-bill-alt"></i>
                  </el-button>
                </td>
                <td>{{ item.status }}</td>
                <td class="text-center">
                  <span v-if="item.active">Si</span>
                  <span v-else>No</span>
                </td>
                <td class="text-center">
                  <el-button type="success" @click="onEdit(item)">
                    <i class="fa fa-edit"></i>
                  </el-button>
                  <el-button type="danger" @click="onDelete(item)">
                    <i class="fa fa-trash"></i>
                  </el-button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <ModalAddEdit
      :visible.sync="openModalAddEdit"
      @onAddItem="onAddItem"
      @onUpdateItem="onUpdateItem"
      :room="room"
      :categories="categories"
      :floors="floors"
    ></ModalAddEdit>
  </div>
</template>

<script>
import ModalAddEdit from "./AddEdit";

export default {
  props: {
    rooms: {
      type: Array,
      required: true,
    },
    categories: {
      type: Array,
      required: true,
    },
    floors: {
      type: Array,
      required: true,
    },
  },
  components: {
    ModalAddEdit,
  },
  data() {
    return {
      items: [],
      room: null,
      openModalAddEdit: false,
      loading: false,
    };
  },
  mounted() {
    this.items = this.rooms;
  },
  methods: {
    onDelete(item) {
      this.$confirm(
        `¿estás seguro de eliminar al elemento ${item.name}?`,
        "Atención",
        {
          confirmButtonText: "Si, continuar",
          cancelButtonText: "No, cerrar",
          type: "warning",
        }
      )
        .then(() => {
          this.$http
            .delete(`/hotels/rooms/${item.id}/delete`)
            .then((response) => {
              this.$message({
                type: "success",
                message: response.data.message,
              });
              this.items = this.items.filter((i) => i.id !== item.id);
            })
            .catch((error) => {
              this.axiosError(error);
            });
        })
        .catch();
    },
    onEdit(item) {
      this.room = { ...item };
      this.openModalAddEdit = true;
    },
    onUpdateItem(data) {
      this.items = this.items.map((i) => {
        if (i.id === data.id) {
          return data;
        }
        return i;
      });
    },
    onAddItem(data) {
      this.items.unshift(data);
    },
    onCreate() {
      this.room = null;
      this.openModalAddEdit = true;
    },
  },
};
</script>
