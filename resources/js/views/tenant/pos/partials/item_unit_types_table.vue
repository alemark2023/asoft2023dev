<template>
    <el-dialog   :title="titleDialog" :visible="showDialog" :close-on-click-modal="true" @close="close" @open="create" append-to-body top="7vh" width="30%">
        <Keypress key-event="keyup" :key-code="87" @success="handleUp87" />
        <Keypress key-event="keyup" :key-code="83" @success="handleDown83" />
        <Keypress key-event="keyup" :key-code="68" @success="handleSetPrice68" />

        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">     
 
                        <el-table
                            ref="tableItemUnitTypes"
                            :data="itemUnitTypes"
                            highlight-current-row
                            @current-change="handleCurrentChange"
                            style="width: 100%"
                        >  
                            <el-table-column label="Precio">
                                <template slot-scope="{ row }">
                                    {{getDescription(row)}}
                                </template>
                            </el-table-column>
                        </el-table>
                    </div> 
                </div>   
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cerrar</el-button>
            </div>
        </form>
    </el-dialog>
</template> 
<script>

    import Keypress from "vue-keypress";

    export default {
        components: { Keypress },
        props: ['showDialog', 'itemUnitTypes'],
        data() {
            return {
                currentIndex: 0,
                selected_price: null,  
                titleDialog: 'Seleccionar precio',
                currentRow:null
            }
        },
        async created() {
             
        },
        methods: {   
            handleSetPrice68(){

                if(!this.currentRow){
                    return this.$message.warning('No ha seleccionado un precio')
                }

                this.selectedPrice()
            },
            handleDown83() {
                
                this.currentIndex += 1;

                if (this.itemUnitTypes[this.currentIndex]) {
                    this.setCurrent(this.itemUnitTypes[this.currentIndex]);
                } else {
                    this.currentIndex = 0;
                    this.setCurrent(this.itemUnitTypes[0]);
                }

            },
            handleUp87() { 

                if (this.currentIndex == 0) {
                    return;
                }
                this.currentIndex -= 1;
                this.setCurrent(this.itemUnitTypes[this.currentIndex])

            },
            handleCurrentChange(val) {
                this.currentRow = val;
            }, 
            setCurrent(row) {
                // console.log(row, "setCurrent")
                this.$refs.tableItemUnitTypes.setCurrentRow(row);
            },
            async selectedPrice(){

                let unit_type = await _.find(this.itemUnitTypes, {id : this.currentRow.id})

                if(unit_type){
                    this.$eventHub.$emit("selectedItemUnitTypeTable", (unit_type))
                    this.close()
                }else{
                    this.$message.warning('No ha seleccionado un precio')
                }

            },
            getDescription(option){
                 
                let price = 0

                switch (option.price_default) {
                    case 1:
                        price = option.price1
                        break;
                    case 2:
                        price = option.price2
                        break;
                    case 3:
                        price = option.price3
                        break; 
                }

                return `Precio: ${price} - Unidad: ${option.unit_type_id}`
            },  
            create() {
                
                this.$nextTick(() => {
                    this.initTableRow()
                }); 

            },  
            initTableRow() {

                if(this.itemUnitTypes.length > 0){
                    this.currentIndex = 0;
                    this.setCurrent(this.itemUnitTypes[0]);
                }
            },
            close() {
                this.currentRow = null
                this.$emit('update:showDialog', false)
            }, 
        }
    }
</script>