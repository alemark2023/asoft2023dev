<table>

    <tr>
        <td>
            Campo 
        </td>
        <td>
            tipdoc
        </td>
        <td>
            seriedoc 
        </td>
        <td>
            numdoc
        </td>
        <td>
            fecha
        </td>
        <td>
            tbase
        </td>
        <td>
            cuenta
        </td>
        <td>
            ctacli
        </td>
        <td>
            codcli
        </td>
        <td>
            nombre
        </td>
        <td>
            direcc
        </td>
        <td>
            ruc
        </td>
        <td>
            moneda
        </td>
        <td>
            tcambio
        </td>

        <td>
            valorvta
        </td>
        <td>
            valorexo
        </td>
        <td>
            valorinaf
        </td>
        <td>
            vgratuitas
        </td>
        <td>
            valorexpor
        </td>
        <td>
            valorigv
        </td>
        <td>
            ctaigv
        </td>
        <td>
            valorisc
        </td>
        <td>
            ctaisc
        </td>
        <td>
            otributo
        </td>
        <td>
            icbper
        </td>
        <td>
            valortotal
        </td>

        <td>
            anulado
        </td>
        <td>
            credito
        </td>
        <td>
            tiporef
        </td>
        <td>
            serieref
        </td>
        <td>
            numeroref
        </td>
        <td>
            fecharef
        </td>
        <td>
            fechavto
        </td>
        <td>
            tr
        </td>
        <td>
            ctacja
        </td>
        <td>
            cheque
        </td>
        <td>
            costo
        </td>
    </tr>



    <tr>
        <td>
            Validaciones
        </td>
        <td>
            01=Factura, 02=RH, 03=Boleta. Validar con tabla 10 sunat
        </td>
        <td>
            Serie del documento
        </td>
        <td>
            Número del comprobante de venta
        </td>
        <td>
            Fecha de emisión del documento emitido
        </td>
        <td>
            1=Venta gravada, 2=Venta exonerada, 3=Venta IVAP, 4=Exportaciones, 5=Venta Exo. con ISC, 6=Venta con percepción
        </td>
        <td>
            Cuenta ventas, debe existir en plan contable
        </td>
        <td>
            Cuenta cliente, debe existir en plan contable
        </td>
        <td>
            Si Cuenta Contable tiene seleccionado Tipo AUXILIAR Anexo, debe existir en la tabla codigo auxiliares
        </td>
        <td>
            Nombre o razón social del cliente
        </td>
        <td>
            opcional
        </td>
        <td>
            D.N.I. o R.U.C. del cliente
        </td>



        <td>
            S=Soles, D=dólares
        </td>
        <td>
            Sólo si campo moneda="D"
        </td>
        <td>
            Sólo si es una operación gravada o tiene otros impuestos como IVAP
        </td>
        <td>
            Valor exonerado se aplica generalmente en zona selva
        </td>
        <td>
            Valor Inafecto solo si es el caso
        </td>
        <td>
            Valor otorgado de forma gratuita
        </td>
        <td>
            Valor de exportación
        </td>
        <td>
            Se aplica en zona gravada
        </td>
        <td>
            Cuenta I.G.V., debe existir en plan contable
        </td>
        <td>
            Valor si la operación tiene I.S.C.
        </td>
        <td>
            Cuenta I.S.C., debe existir en plan contable
        </td>

        <td>
            Valor si la operación tiene otro tributos como IVAP	
        </td>
        <td>
            Valor del Impuesto a las bolsas plasticas (ICBPER).
        </td>
        <td>
            Importe total del comprobante de pago
        </td>
        <td>
            Obligatorio sólo si el documento está anulado y de debe tener una "A"
        </td>
        <td>
            Cuando la venta es al crédito debe tener una "S", en otro caso dejar en blanco
        </td>
        <td>
            Tipo documento que modifica sólo cuando el tipdoc=07 nota de crédito
        </td>
        <td>
            Serie documento que modifica sólo cuando el tipdoc=07 nota de crédito
        </td>
        <td>
            Número documento que modifica sólo cuando el tipdoc=07 nota de crédito
        </td>
        <td>
            Fecha documento que modifica sólo cuando el tipdoc=07 nota de crédito
        </td>
        <td>
            Fecha de vencimiento cuando campo credito="S"
        </td>
        <td>
            x=transaccion de venta
        </td>
        <td>
            Cuenta para la cancelación o caja. Debe existir en plan de cuentas
        </td>
        <td>
            Número de cheque o sustento del pago
        </td>
        <td>
            Código centro del costo
        </td>
    </tr>


    <tr>
        <td>
            Tamaño/Tipo
        </td>
        <td>
            2 caracteres
        </td>
        <td>
            4 caracteres
        </td>
        <td>
            8 caracteres
        </td>
        <td>
            dd/mm/aaaa
        </td>
        <td>
            Numérico 1
        </td>
        <td>
            7 caracteres
        </td>
        <td>
            7 caracteres
        </td>
        <td>
            11 caracteres
        </td>
        <td>
            40 caracteres
        </td>
        <td>
            40 caracteres
        </td>
        <td>
            11 caracteres
        </td>



        <td>
            1 caracter
        </td>
        <td>
            Numérico 7,3
        </td>
        <td>
            Numérico 14,2
        </td>
        <td>
            Numérico 14,2
        </td>
        <td>
            Numérico 14,2
        </td>
        <td>
            Numérico 14,2
        </td>
        <td>
            Numérico 14,2
        </td>
        <td>
            Numérico 14,2
        </td>
        <td>
            7 caracteres
        </td>
        <td>
            Numérico 14,2
        </td>
        <td>
            7 caracteres
        </td>
        <td>
            Numérico 14,2
        </td>

        <td>
            Numérico 14,2	
        </td>
        <td>
            Numérico 14,2
        </td>
        <td>
            1 caracter
        </td>
        <td>
            1 caracter
        </td>
        <td>
            2 caracteres
        </td>
        <td>
            4 caracteres
        </td>
        <td>
            8 caracteres
        </td>
        <td>
            dd/mm/aaaa
        </td>
        <td>
            dd/mm/aaaa
        </td>
        <td>
            2 caracteres
        </td>
        <td>
            7 caracteres
        </td>
        <td>
            15 caracteres
        </td>
        <td>
            10 caracteres
        </td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    @foreach($records as $row)
    <tr>
        <td>
        </td>
        <td>
            {{ $row['document_type_id'] }}
        </td>
        <td>
            {{ $row['series'] }}
        </td>
        <td>
            {{ $row['number'] }}
        </td>
        <td>
            {{ $row['date_of_issue'] }}
        </td>
        <td>
            {{ $row['xxxxxxx'] }}
        </td>
        <td>
            {{ $row['xxxxxxx'] }}
        </td>
        <td>
            {{ $row['xxxxxxx'] }}
        </td>
        <td>
            {{ $row['customer_number'] }}
        </td>
        <td>
            {{ $row['customer_name'] }}
        </td>
        <td>
            {{ $row['customer_address'] }}
        </td>
        <td>
            {{ $row['customer_number'] }}
        </td>
        <td>
            {{ $row['currency_type_description'] }}
        </td>
        <td>
            {{ $row['exchange_rate_sale'] }}
        </td>
        <td>
            {{ $row['total_taxed'] }}
        </td>
        <td>
            {{ $row['total_exonerated'] }}
        </td>
        <td>
            {{ $row['total_unaffected'] }}
        </td>
        <td>
            {{ $row['total_free'] }}
        </td>
        <td>
            {{ $row['total_exportation'] }}
        </td>
        <td>
            {{ $row['total_igv'] }}
        </td>
        <td>
            {{ $row['xxxxxxx'] }}
        </td>
        <td>
            {{ $row['total_isc'] }}
        </td>
        <td>
            {{ $row['xxxxxxx'] }}
        </td>
        <td>
            {{ $row['xxxxxxx'] }}
        </td>
        <td>
            {{ $row['total_plastic_bag_taxes'] }}
        </td>
        <td>
            {{ $row['total'] }}
        </td>
        <td>
            {{ $row['voided_description'] }}
        </td>
        <td>
            {{ $row['payment_condition_description'] }}
        </td>


        @if ($row['reference_document'])
            
            <td>
                {{ $row['reference_document']['document_type_id'] }}
            </td>
            <td>
                {{ $row['reference_document']['series'] }}
            </td>
            <td>
                {{ $row['reference_document']['number'] }}
            </td>
            <td>
                {{ $row['reference_document']['date_of_issue'] }}
            </td>
        @else
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        @endif


        <td>
            {{ $row['date_of_due'] }}
        </td>
        <td>
            {{ $row['xxxxxxx'] }}
        </td>
        <td>
            {{ $row['xxxxxxx'] }}
        </td>
        <td>
            {{ $row['xxxxxxx'] }}
        </td>
        <td>
            {{ $row['xxxxxxx'] }}
        </td>
    </tr>
    @endforeach
</table>
