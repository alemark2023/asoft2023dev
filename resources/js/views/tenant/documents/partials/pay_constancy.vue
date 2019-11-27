<template> 
    <el-dialog :title="title" class="text-left" :visible="showDialog" @close="close" :close-on-click-modal="false">
        <!-- <p class="text-center">* Se recomienda resoluciones 700x300.</p> -->
        <div class="text-center">
            <el-upload class="uploader" ref="upload" slot="append" :auto-upload="false" :headers="headers" :data="{'type': 'logo'}" action="/companies/uploads" :show-file-list="false" :before-upload="beforeUpload" :on-success="successUpload" :on-change="preview">
                <img v-if="imageUrl" width="100%" :src="imageUrl" alt="">
                <i v-else class="el-icon-plus uploader-icon"></i>
            </el-upload>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button @click="close">Cancelar</el-button>
            <el-button @click="upload" class="submit" type="primary" :disabled="imageUrl == ''">Guardar</el-button>
        </span>
    </el-dialog> 
</template>

<script>
    export default {
        props: ['url', 'path_logo','showDialog'],
        data() {
            return {
                headers: headers_token,
                dialogVisible: false,
                load: false,
                imageUrl: '',
                title:'Imágen - constancia de pago de detracción'
            }
        },
        computed: {
            src() {
                if (this.path_logo != '') return this.path_logo;
                
                return '/logo/700x300.jpg';
            }
        },
        methods: {
            beforeUpload(file) {
                const isIMG = ((file.type === 'image/jpeg') || (file.type === 'image/png') || (file.type === 'image/jpg'));
                const isLt2M = file.size / 1024 / 1024 < 2;
                
                if (!isIMG) this.$message.error('La imagen no es valida!');
                if (!isLt2M) this.$message.error('La imagen excede los 2MB!');
                
                return isIMG && isLt2M;
            },
            preview(file) {
                this.imageUrl = URL.createObjectURL(file.raw);
            },
            upload() {
                this.$refs.upload.submit();
            },
            successUpload(response, file, fileList) {
                this.imageUrl = URL.createObjectURL(file.raw);
                
                if (response.success) {
                    this.$message.success(response.message);
                    // location.href = this.url;
                    
                    return;
                }
                
                this.$message({message:'Error al subir el archivo', type: 'error'});
                this.imageUrl = '';
            },
            close() {
                this.$emit('update:showDialog', false)
                // this.initForm()
                
            }
        }
    }
</script>

<style lang="scss">
    .uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .uploader .el-upload:hover {
        border-color: #409EFF;
    }
    .uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }
    
    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
</style>
