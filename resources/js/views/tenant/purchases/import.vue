<template>
  <el-dialog
    :title="titleDialog"
    :visible="showDialog"
    @close="close"
    @open="create"
    class="dialog-import"
  >
    <form autocomplete="off" @submit.prevent="submit">
      <div class="form-body">
        <div class="row">
          <div class="col-md-12 mt-4">
            <div class="form-group text-center" :class="{'has-danger': errors.file}">
              <el-upload
              action="''"
                ref="upload"
                :show-file-list="true"
                :auto-upload="false"
                :multiple="false"
                :on-change="handleChange"
                :limit="1"
              >
                <el-button slot="trigger" type="primary">Seleccione un archivo (xml)</el-button>
              </el-upload>
              <small class="form-control-feedback" v-if="errors.file" v-text="errors.file[0]"></small>
            </div>
          </div>
        </div>
      </div>
      <div class="form-actions text-right mt-4">
        <el-button @click.prevent="close()">Cancelar</el-button>
      </div>
    </form>

    <el-button @click="demo">Procesar</el-button>
  </el-dialog>
</template>

<script>
export default {
  props: ["showDialog"],
  data() {
    return {
      loading_submit: false,
      headers: headers_token,
      titleDialog: null,
      resource: "items",
      errors: {},
      form: {}
    };
  },
  created() {
    this.initForm();
  },
  methods: {
      handleChange(file)
      {
        const reader = new FileReader();
        reader.onload = e => console.log(e.target.result);

        reader.readAsText(file);

          console.log(file)
      },
    initForm() {
      this.errors = {};
      this.form = {
        file: null
      };
    },
    create() {
      this.titleDialog = "Importar Factura Compra";
    },
    async submit() {
      this.loading_submit = true;
      await this.$refs.upload.submit();
      this.loading_submit = false;
    },
    close() {
      this.$emit("update:showDialog", false);
      this.initForm();
    },
    successUpload(response, file, fileList) {
      if (response.success) {
        alert("asd");
        //this.$message.success(response.message)
        //this.$eventHub.$emit('reloadData')
        //this.$refs.upload.clearFiles()
        //this.close()
      } else {
        this.$message({ message: response.message, type: "error" });
      }
    },
    errorUpload(response) {
      console.log(response);
    },
    xmlToJson(xml) {
      // Create the return object
      var obj = {};

      if (xml.nodeType == 1) {
        // element
        // do attributes
        if (xml.attributes.length > 0) {
          obj["@attributes"] = {};
          for (var j = 0; j < xml.attributes.length; j++) {
            var attribute = xml.attributes.item(j);
            obj["@attributes"][attribute.nodeName] = attribute.nodeValue;
          }
        }
      } else if (xml.nodeType == 3) {
        // text
        obj = xml.nodeValue;
      }

      // do children
      // If all text nodes inside, get concatenated text from them.
      var textNodes = [].slice.call(xml.childNodes).filter(function(node) {
        return node.nodeType === 3;
      });
      if (xml.hasChildNodes() && xml.childNodes.length === textNodes.length) {
        obj = [].slice.call(xml.childNodes).reduce(function(text, node) {
          return text + node.nodeValue;
        }, "");
      } else if (xml.hasChildNodes()) {
        for (var i = 0; i < xml.childNodes.length; i++) {
          var item = xml.childNodes.item(i);
          var nodeName = item.nodeName;
          if (typeof obj[nodeName] == "undefined") {
            obj[nodeName] = this.xmlToJson(item);
          } else {
            if (typeof obj[nodeName].push == "undefined") {
              var old = obj[nodeName];
              obj[nodeName] = [];
              obj[nodeName].push(old);
            }
            obj[nodeName].push(this.xmlToJson(item));
          }
        }
      }
      return obj;
    },
    demo() {



        parseXMLToJSON();
        return false


    }
  }
};
</script>
