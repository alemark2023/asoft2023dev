# Plantilla PDF personalizada

---

- [First Section](#section-1)

<a name="section-1"></a>
## First Section

Write something cool.. 🦊

podrán subir N cantidad de carpetas al servidor con nombre de personalizada\[\*\] por ejemplo:

* personalizada_1
* personalizada_cliente1
* personalizadaA

la ruta a subir la caperta es: app/CoreFacturalo/Templates/pdf/\[nombre de carpeta\]
a su vez deben subir la imagen a: public/templates/pdf/\[nombre de carpeta\]/image.png

Es importante que cada plantilla que suban cuente con la siguiente estructura
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/94fc5dd5f1ca589bfa59372bcebcec5d/image.png)
donde los archivos mas importantes son/estan:

* carpeta partials
* style.css
* el resto, que son archivos dependientes de cada tipo de documento no es 100% obligatorio, pudiendo tener solamente por ejemplo; invoice_a4 o invoice_a5, manteniendo los formatos y los datos consultados