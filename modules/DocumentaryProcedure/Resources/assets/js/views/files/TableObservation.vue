<template>
    <div
        v-if="
        file !== null &&
        file.observations !== null &&
        file.observations.length > 0
        "
        class="table-responsive">
        <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Etapa</th>
                    <th>Observacion</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,index) in file.observations" :key="item.id">
                    <td>{{ index + 1 }}</td>
                    <td>
                        {{ item.documentary_office.name }}
                    </td>
                    <td>
                        {{ item.observation }}
                    </td>
                    <td>
                        {{ item.created_at }}
                    </td>
                </tr>
                </tbody>
            </table>
    </div>
</template>

<script>
import {mapState} from "vuex";

export default {
    name: 'table-observation-historical',
    props: [],
    computed: {
        ...mapState([
            'file',
        ]),
    },
    data() {
        return {
            borrando: false,
        };
    },
    mounted() {

    },
    methods: {},
};
</script>
