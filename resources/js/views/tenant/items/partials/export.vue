<template>
	<el-dialog :title="Exportar Productos" :visible="showDialog" @close="close" @open="create" class="dialog-import">
		<div class="form-body">
			<div class="row">
				<div class="col-12">
					<template v-if="form.period === 'month' || form.period === 'between_months'">
                        <div class="col-md-3">
                            <label class="control-label">Mes de</label>
                            <el-date-picker v-model="form.month_start" type="month"
                                            @change="changeDisabledMonths"
                                            value-format="yyyy-MM" format="MM/yyyy" :clearable="false"></el-date-picker>
                        </div>
                    </template>
				</div>
			</div>
			<div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Procesar</el-button>
            </div>
        </div>
	</el-dialog>
</template>

<script>
	export default {
        props: ['showDialog'],
        data() {
            return {
                loading_submit: false,
                headers: headers_token,
                resource: 'items',
                errors: {},
                form: {},
                pickerOptionsMonths: {
                    disabledDate: (time) => {
                        time = moment(time).format('YYYY-MM')
                        return this.form.month_start > time
                    }
                },
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    month_start: moment().format('YYYY-MM'),
                }
            },
        }
    }
</script>
