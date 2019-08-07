<template>
    <div class="box">
        <input name="item_selected" type="hidden" id="item_selected" ref="item_selected">
        <div class="box-body no-padding">
            
            <div class="col-md-8">
                <div>
                    <span>Producto:</span>
                    <el-select @change="setProduct" v-model="product" filterable  clearable placeholder="Productos">
                        <el-option v-for="item in data_products" :key="item.id" :label="item.description" :value="item.id"></el-option>
                    </el-select>
                </div>
            </div>
            
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            
            'data_products': {
                required: true
            }
        },
        data() {
            return {
              product: null
            }
        },
        created() {
          
        },
        methods:{
            setProduct()
            {
                this.$refs.item_selected.value = this.product
            }
        }
    }
</script>
