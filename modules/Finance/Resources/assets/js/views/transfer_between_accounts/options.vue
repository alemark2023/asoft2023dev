<template>
    <el-dialog
        :show-close="false"
        :title="titleDialog"
        :visible="showDialogTransfer"
        width="30%"
        @open="create">

        <div class="col-12 row">

            <!-- Bancos -->
            <div class="col-4">
                <label class="control-label">
                    Origen
                    <el-tooltip
                        class="item"
                        content="Selecciona la cuenta bancaria o caja de origen"
                        effect="dark"
                        placement="top-start">
                        <i class="fa fa-info-circle"></i>
                    </el-tooltip>
                </label>
                <el-select v-model="transfer_amount.from"
                           clearable
                           filterable
                >
                    <!--  @change="getBankAccounts" -->
                    <el-option v-for="option in banks"
                               :key="option.id"
                               :disabled="option.id === transfer_amount.to"
                               :label="option.description"
                               :value="option.id">

                    </el-option>
                    <el-option v-for="option in cashs"
                               :key="option.id"
                               :disabled="option.id === transfer_amount.to"
                               :label="option.description"
                               :value="option.id">

                    </el-option>


                </el-select>
            </div>
            <!-- Cash -->
            <div class="col-4">
                <label class="control-label">
                    Destino
                    <el-tooltip
                        class="item"
                        content="Selecciona la cuenta bancaria o caja de destino"
                        effect="dark"
                        placement="top-start">
                        <i class="fa fa-info-circle"></i>
                    </el-tooltip>
                </label>
                <el-select v-model="transfer_amount.to"
                           clearable
                           filterable
                >
                    <el-option v-for="option in banks"
                               :key="option.id"
                               :disabled="option.id == transfer_amount.from"
                               :label="option.description"
                               :value="option.id">

                    </el-option>
                    <el-option v-for="option in cashs"
                               :key="option.id"
                               :disabled="option.id == transfer_amount.from"
                               :label="option.description"
                               :value="option.id">

                    </el-option>
                </el-select>
            </div>

            <div class="col-4">
                <label class="control-label">
                    Cantidad
                    <el-tooltip
                        class="item"
                        content="Ingresa el monto a transferir"
                        effect="dark"
                        placement="top-start">
                        <i class="fa fa-info-circle"></i>
                    </el-tooltip>
                </label>

                <el-input-number
                    v-model="transfer_amount.amount_transform"
                    :min="0"
                >

                </el-input-number>
            </div>
            <div class="col-4">
                &nbsp;
            </div>
            <div class="col-4">
                <label class="control-label">
                    &nbsp;
                </label>
                <el-button
                    class="submit"
                    icon="el-icon-search"
                    type="primary"
                    @click.prevent="clickClose">
                    Cerrar
                </el-button>
            </div>
            <div class="col-4">
                <label class="control-label">
                    &nbsp;
                </label>
                <el-button
                    class="submit"
                    icon="el-icon-search"
                    type="primary"
                    @click.prevent="transferAmount">
                    Transferir
                </el-button>
            </div>


        </div>
    </el-dialog>
</template>

<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    props: [
        'showDialogTransfer',
    ],
    computed: {
        ...mapState([
            'config',
        ]),
    },
    data() {
        return {
            titleDialog: "Transferencia entre cuentas",
            loading: false,
            resource: 'finances/balance',
            banks: [],
            cashs: [],
            transfer_amount: {
                amount_transform: null,
                from: null,
                to: null,
            },
        }
    },
    created() {
        this.loadConfiguration();
        this.getBankAccounts();
        this.getCashAccounts();
        this.clearForm()
    },
    methods: {

        ...mapActions([
            'loadConfiguration',
        ]),

        clearForm() {
            this.transfer_amount.amount_transform = null;
            this.transfer_amount.from = null;
            this.transfer_amount.to = null;

        },

        create() {
            this.getBankAccounts();
            this.getCashAccounts();
        },
        getBankAccounts() {
            this.$http.post(`/${this.resource}/bank_accounts`, {}).then((resource) => {
                let data = resource.data;
                console.error(data)
                this.banks = data.banks
            })
        },

        getCashAccounts() {
            this.$http.post(`/${this.resource}/cash`, {}).then((resource) => {
                let data = resource.data;
                console.error(data)
                this.cashs = data.cash
            })
        },
        transferAmount() {

            this.$http.post(`/${this.resource}/transfer`, {data: this.transfer_amount}).then((resource) => {
                let data = resource.data;
                console.error(data)
                this.cashs = data.cash
            })

        },
        clickFinalize() {
            location.href = `/${this.resource}`
        },
        clickNewDocument() {
            this.clickClose()
        },
        clickClose() {
            this.$emit('update:showDialogTransfer', false)
            this.clearForm()
        },
    }
}
</script>
