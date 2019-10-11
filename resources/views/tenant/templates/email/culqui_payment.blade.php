<html xmlns="http://www.w3.org/1999/xhtml">

<body
    style="margin: 0; padding: 20px; font-family: Montserrat, Arial, sans serif; font-size: 12px;font-weight:400;word-break: break-word;color:#555;line-height: 18px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">

    </table>
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="max-width:500px; margin:40px auto;border-collapse: collapse;border-radius: 2px;overflow: hidden;">

        <tbody>
            <tr bgcolor="#846add" height="100px">
                <td align="center"
                    style="font-family: Montserrat, Arial, sans serif; color: #fff;text-transform: uppercase;font-size: 20px;justify-content: center;align-items: center;letter-spacing: 4px;font-weight: 600;">
                    Constancia de pago
                </td>
            </tr>

            <tr bgcolor="#f9f9f9">
                <td style="padding:40px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">

                        <tbody>
                            <tr>
                                <td
                                    style="font-family: Montserrat, Arial, sans serif; margin:0; color:#846add; font-size:20px; font-weight:400;">
                                    Hola {{ $document->client }}
                                </td>
                            </tr>

                            <tr>
                                <td
                                    style="font-family: Montserrat, Arial, sans serif; margin:5px 0 0; font-size: 12px; font-weight:400;word-break: break-word;color:#333;line-height: 22p; position: relative; top: 10px;">
                                    Gracias por su pago realizado, aqui le detallamos sus pedido:</td>

                            </tr>
                            <tr height="30"></tr>

                            <tr>
                                <td
                                    style="margin: 40px 0;line-height: 22px; font-family: &#39;Montserrat&#39;, Arial, sans serif; font-size: 12px;font-weight:100; word-break: break-word; color:#333;">

                                    Detalle de la compra: <b> {{ $document->product }} </b><br>
                                    Monto: <b>S/ {{ $document->total }}</b><br>

                                    <br><br>

                                    <span style="color:#846add;">Información de contacto:</span><br>
                                    <span>Personal: Martha <br>
                                        Email: m@buhol.a <br>
                                        Fono: <b>967 746 377‬</b>
                                    </span>

                                </td>
                            </tr>


                            <tr height="50"></tr>

                            <tr>
                                <td
                                    style="margin:40px 0; line-height: 22px; font-family: Montserrat, Arial, sans serif; font-size: 12px; font-weight:400; word-break: break-word; color:#333; padding-top: 10px; border-top: 1px solid #e2e2e2;">
                                    Cualquier duda o consulta puede responder a este correo.
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </td>
            </tr>

        </tbody>
    </table>

</body>

</html>
