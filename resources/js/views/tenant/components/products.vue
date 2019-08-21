<template>
    <div class="box m-custom" >
        <input name="item_selected" type="hidden" id="item_selected" ref="item_selected">
        <div class="box-body no-padding m-custom" >
            
            <div class="col-md-8 m-custom" >
                <div>
                    <span>Producto:</span>
                    <el-select @change="setProduct" v-model="product" filterable  clearable placeholder="Productos" class="m-custom">
                        <el-option v-for="item in data_products" :key="item.id" :label="item.description" :value="item.id" class="m-custom"></el-option>
                    </el-select>
                </div>
            </div>
            
        </div>
    </div>
</template>
<style>
.m-custom{
    margin-left:-6px !important
}
</style>
<script>
    export default {
        props: {            
            'data_products': {
                required : true
            },
            'item_selected':{
                required : false
            }
        },
        data() {
            return {
              product: null
            }
        },
        created() {

            this.product = (this.item_selected) ? parseInt(this.item_selected) : null
          
        },
        methods:{
            setProduct()
            {
                this.$refs.item_selected.value = this.product
            }
        }
    }
</script>
