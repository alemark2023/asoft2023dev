<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Actividades del sistema - Accesos</span></li>
            </ol>
            <div class="right-wrapper pull-right">
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Actividades del sistema - Accesos</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha y Hora</th>
                        <th>Usuario</th>
                        <th class="text-center">T. Transacción Autenticación</th>
                        <th>Ip</th>
                        <th>Dispositivo</th>
                        <th>Tipo dispositivo</th>
                        <th>Plataforma</th>
                        <th>Navegador</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.date_time }}</td>
                        <td>{{ row.user_name }}</td>
                        <td>{{ row.auth_transaction_type_description }}</td>
                        <td>{{ row.ip }}</td>
                        <td>{{ row.device_name }}</td>
                        <td>{{ row.device_type_description }}</td>
                        <td>{{ row.platform_description }}</td>
                        <td>{{ row.browser_description }}</td>
                    </tr>
                </data-table>
            </div>

        </div>
    </div>
</template>

<script>

    import DataTable from '../../../components/DataTable.vue'

    export default {
        components: { DataTable },
        data() {
            return {
                showDialog: false,
                showDialogOptions: false,
                open_cash: true,
                resource: 'system-activity-logs/access',
                recordId: null,
                cash:null,
            }
        },
        async created() {

        },
        methods: {
        }
    }
</script>
