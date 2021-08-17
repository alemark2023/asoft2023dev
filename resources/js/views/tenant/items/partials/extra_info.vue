<template>
    <div class="row">
        <div class="col-md-8">
            <div :class="{'has-danger': errors.name}"
                 class="form-group">
                <label class="control-label">Colores</label>
                <template v-if="colors.length > 0">
                    <el-select v-model="form.colors"
                               :multiple="true"

                    >
                        <el-option v-for="option in colors"
                                   :key="option.id"
                                   :label="option.id + ' - ' +option.name"
                                   :value="option.id"></el-option>
                    </el-select>
                    <small v-if="errors.name"
                           class="form-control-feedback"
                           v-text="errors.name[0]"></small>
                </template>
                <template
                    v-else>
                    No hay colores
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";


export default {
    props: [
        'form'
    ],
    computed: {
        ...mapState([
            'colors',
            'config',
        ]),
    },
    data() {
        return {
            errors: {}
        }
    },
    created() {
        this.loadConfiguration();
    },
    mounted() {
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        close() {
            this.$emit('update:form', this.form)
        },
    }
}
</script>
