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
                ref="upload"
                :headers="headers"
                action="/purchases/import"
                :show-file-list="true"
                :auto-upload="false"
                :multiple="false"
                :on-error="errorUpload"
                :limit="1"
                :on-success="successUpload"
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
     // var convert = require("xml-js");
      var xml = `<?xml version="1.0" encoding="utf-8" standalone="no"?>
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
  <ext:UBLExtensions>
    <ext:UBLExtension>
      <ext:ExtensionContent><ds:Signature Id="signatureFACTURALOPERU">
  <ds:SignedInfo><ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
    <ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
  <ds:Reference URI=""><ds:Transforms><ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/></ds:Transforms><ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><ds:DigestValue>sdZB7kaA9rjIucYaUjdzZRd+xAM=</ds:DigestValue></ds:Reference></ds:SignedInfo><ds:SignatureValue>C98igZEFthVRFQ51p4Q3PICz/8rcjtENBRA2mN+axxFPKK5K5B2USBqxg+aSYzNUqTijQi4arpWwVSjKlcAq2ssmKRt0peDKnHwWFthGZGZe71+dffHeu2d1hQsxNFKKuhPL0+d/liR0hulvgDTL4kWXK9qEdLX8O1ZqlWsSXWAV59H7ykk5DFPPe8j4w20PU91HfD2CQ4Sb3mQmgACGif6AtofzfNhoim7w+BNCIq9JcgxmIyAm0DjWdYbvtThJULQVlmWZuL2d5vPb8FQdmr0tHsKNtiRSquNL8q5NZdO3xMASpzoYyqbOhiQpAIxOLXyYnIL0FApHvkbDgF70uA==</ds:SignatureValue>
<ds:KeyInfo><ds:X509Data><ds:X509Certificate>MIIE8TCCA9mgAwIBAgICV+MwDQYJKoZIhvcNAQEFBQAwggENMRswGQYKCZImiZPyLGQBGRYLTExBTUEuUEUgU0ExCzAJBgNVBAYTAlBFMQ0wCwYDVQQIDARMSU1BMQ0wCwYDVQQHDARMSU1BMRgwFgYDVQQKDA9UVSBFTVBSRVNBIFMuQS4xRTBDBgNVBAsMPEROSSA5OTk5OTk5IFJVQyAyMDMwMTk0NzY0MSAtIENFUlRJRklDQURPIFBBUkEgREVNT1NUUkFDScOTTjFEMEIGA1UEAww7Tk9NQlJFIFJFUFJFU0VOVEFOVEUgTEVHQUwgLSBDRVJUSUZJQ0FETyBQQVJBIERFTU9TVFJBQ0nDk04xHDAaBgkqhkiG9w0BCQEWDWRlbW9AbGxhbWEucGUwHhcNMTcwNDI4MTk0ODQ5WhcNMTkwNDI4MTk0ODQ5WjCCAQ0xGzAZBgoJkiaJk/IsZAEZFgtMTEFNQS5QRSBTQTELMAkGA1UEBhMCUEUxDTALBgNVBAgMBExJTUExDTALBgNVBAcMBExJTUExGDAWBgNVBAoMD1RVIEVNUFJFU0EgUy5BLjFFMEMGA1UECww8RE5JIDk5OTk5OTkgUlVDIDIwMzAxOTQ3NjQxIC0gQ0VSVElGSUNBRE8gUEFSQSBERU1PU1RSQUNJw5NOMUQwQgYDVQQDDDtOT01CUkUgUkVQUkVTRU5UQU5URSBMRUdBTCAtIENFUlRJRklDQURPIFBBUkEgREVNT1NUUkFDScOTTjEcMBoGCSqGSIb3DQEJARYNZGVtb0BsbGFtYS5wZTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAN6ouL3S0BSd7A22puw50y+Hs3zt1DIgYyTZD7JwYIBP1H1QL7B8JcbyhWhyfdJbyIwuuaXdoZMEGgha977x+nW9cFSg1SxhtvJ73l0nb30tY3J1OQ+uLntAW30iDBjTMxSENi4bKC0mcwd3RUArDOwRrs66rKurvCoQnMG623uLC+RXc/S8o9Enr4HorimhfPuqmJ79hCze+Z6TW5YNV7kxL2IUcC6zZUemR6NbwM6kFRF1YBdoTWlhfOYvujdhdubFiixIcBp+OJ6GUGnErQxzflhkatST1s58CB4JkwdOm9mZQuXof9kZ2AGFFfklGuDOFiIKGp2sya9sV2ZeFwUCAwEAAaNXMFUwHQYDVR0OBBYEFNAD20nBqnjVv9sRdjM0vgtIlVhsMB8GA1UdIwQYMBaAFNAD20nBqnjVv9sRdjM0vgtIlVhsMBMGA1UdJQQMMAoGCCsGAQUFBwMBMA0GCSqGSIb3DQEBBQUAA4IBAQDZmHB0lSA0N13DrloUmDdhEkOCgsRyrpjYSvrQnRaQu0dVLjvEm6SJIb+O/+k+WWY5v17c+kNZfOU6BcKQPF9UX+4PRqGzuWbtagqF8OuNo/QQoOHK40TkDobhig0EdOvo2dSHZAQPIWXFIAKFA2Inp9lLJYxlZsqvJaOOjnHFBbyIewcI0EZvlw69BiXxNKjIoz+mMYJbWrSl+FBMqUw+ZXYzFfc+vZlRCNb23nishm4RrFZoBIz1BD/9ZJ/z7Ggtelbxw4xtTOs15P5vbS4b5mBUlUL/SVkG463oorzeaCqbmuQcB3Iry1HK5qATAprPXYCmiPYYWwisglqkIfTT</ds:X509Certificate></ds:X509Data></ds:KeyInfo></ds:Signature></ext:ExtensionContent>
    </ext:UBLExtension>
  </ext:UBLExtensions>
  <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
  <cbc:CustomizationID>2.0</cbc:CustomizationID>
  <cbc:ID>F001-17</cbc:ID>
  <cbc:IssueDate>2019-11-30</cbc:IssueDate>
  <cbc:IssueTime>13:09:11</cbc:IssueTime>
  <cbc:DueDate>2019-11-30</cbc:DueDate>
  <cbc:InvoiceTypeCode listID="0101">01</cbc:InvoiceTypeCode>
  <cbc:Note languageLocaleID="1000"><![CDATA[Dos mil ochocientos  con 00/100 ]]></cbc:Note>
  <cbc:DocumentCurrencyCode>PEN</cbc:DocumentCurrencyCode>
  <cac:Signature>
    <cbc:ID>signatureFACTURALOPERU</cbc:ID>
    <cbc:Note>FACTURALO</cbc:Note>
    <cac:SignatoryParty>
      <cac:PartyIdentification>
        <cbc:ID>20110768151</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name><![CDATA[UNIVERSIDAD PERUANA CAYETANO HEREDIA]]></cbc:Name>
      </cac:PartyName>
    </cac:SignatoryParty>
    <cac:DigitalSignatureAttachment>
      <cac:ExternalReference>
        <cbc:URI>#signatureFACTURALOPERU</cbc:URI>
      </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
  </cac:Signature>
  <cac:AccountingSupplierParty>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="6">20110768151</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyName>
        <cbc:Name><![CDATA[UNIVERSIDAD PERUANA CAYETANO HEREDIA]]></cbc:Name>
      </cac:PartyName>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName><![CDATA[UNIVERSIDAD PERUANA CAYETANO HEREDIA]]></cbc:RegistrationName>
        <cac:RegistrationAddress>
          <cbc:ID>150101</cbc:ID>
          <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
          <cbc:CityName>Lima</cbc:CityName>
          <cbc:CountrySubentity>LIMA</cbc:CountrySubentity>
          <cbc:District>Lima</cbc:District>
          <cac:Country>
            <cbc:IdentificationCode>PE</cbc:IdentificationCode>
          </cac:Country>
        </cac:RegistrationAddress>
      </cac:PartyLegalEntity>
      <cac:Contact>
        <cbc:Telephone>-</cbc:Telephone>
        <cbc:ElectronicMail>cayetano@gmail.com</cbc:ElectronicMail>
      </cac:Contact>
    </cac:Party>
  </cac:AccountingSupplierParty>
  <cac:AccountingCustomerParty>
    <cac:Party>
      <cac:PartyIdentification>
        <cbc:ID schemeID="6">20218409041</cbc:ID>
      </cac:PartyIdentification>
      <cac:PartyLegalEntity>
        <cbc:RegistrationName><![CDATA[INMOBILIARIA E INVERS. SAN FERNANDO S.A.]]></cbc:RegistrationName>
        <cac:RegistrationAddress>
          <cbc:ID>150125</cbc:ID>
          <cac:AddressLine>
            <cbc:Line><![CDATA[KM. 31 FND.SANTA INES PARCELA 10234(ALT. OV. PTE PIEDRA(LADRILLERA LARK))]]></cbc:Line>
          </cac:AddressLine>
          <cac:Country>
            <cbc:IdentificationCode>PE</cbc:IdentificationCode>
          </cac:Country>
        </cac:RegistrationAddress>
      </cac:PartyLegalEntity>
      <cac:Contact>
        <cbc:Telephone>3434234</cbc:Telephone>
        <cbc:ElectronicMail>cris@mail.com</cbc:ElectronicMail>
      </cac:Contact>
    </cac:Party>
  </cac:AccountingCustomerParty>
  <cac:TaxTotal>
    <cbc:TaxAmount currencyID="PEN">427.12</cbc:TaxAmount>
    <cac:TaxSubtotal>
      <cbc:TaxableAmount currencyID="PEN">2372.88</cbc:TaxableAmount>
      <cbc:TaxAmount currencyID="PEN">427.12</cbc:TaxAmount>
      <cac:TaxCategory>
        <cac:TaxScheme>
          <cbc:ID>1000</cbc:ID>
          <cbc:Name>IGV</cbc:Name>
          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
        </cac:TaxScheme>
      </cac:TaxCategory>
    </cac:TaxSubtotal>
  </cac:TaxTotal>
  <cac:LegalMonetaryTotal>
    <cbc:LineExtensionAmount currencyID="PEN">2372.88</cbc:LineExtensionAmount>
    <cbc:PayableAmount currencyID="PEN">2800.00</cbc:PayableAmount>
  </cac:LegalMonetaryTotal>
  <cac:InvoiceLine>
    <cbc:ID>1</cbc:ID>
    <cbc:InvoicedQuantity unitCode="NIU">14.0000</cbc:InvoicedQuantity>
    <cbc:LineExtensionAmount currencyID="PEN">2372.88</cbc:LineExtensionAmount>
    <cac:PricingReference>
      <cac:AlternativeConditionPrice>
        <cbc:PriceAmount currencyID="PEN">200.000000</cbc:PriceAmount>
        <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
      </cac:AlternativeConditionPrice>
    </cac:PricingReference>
    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="PEN">427.12</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxableAmount currencyID="PEN">2372.88</cbc:TaxableAmount>
        <cbc:TaxAmount currencyID="PEN">427.12</cbc:TaxAmount>
        <cac:TaxCategory>
          <cbc:Percent>18.00</cbc:Percent>
          <cbc:TaxExemptionReasonCode>10</cbc:TaxExemptionReasonCode>
          <cac:TaxScheme>
            <cbc:ID>1000</cbc:ID>
            <cbc:Name>IGV</cbc:Name>
            <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>
    </cac:TaxTotal>
    <cac:Item>
      <cbc:Description><![CDATA[polera]]></cbc:Description>
      <cac:SellersItemIdentification>
        <cbc:ID>234234</cbc:ID>
      </cac:SellersItemIdentification>
      <cac:CommodityClassification>
        <cbc:ItemClassificationCode>24234</cbc:ItemClassificationCode>
      </cac:CommodityClassification>
    </cac:Item>
    <cac:Price>
      <cbc:PriceAmount currencyID="PEN">169.491525</cbc:PriceAmount>
    </cac:Price>
  </cac:InvoiceLine>
</Invoice>
`;
       var XmlNode = new DOMParser().parseFromString(xml, 'text/xml');
       let resul  = this.xmlToJson(XmlNode)
       console.log( resul.Invoice['cac:AccountingCustomerParty']['cac:Party'] );
    }
  }
};
</script>
