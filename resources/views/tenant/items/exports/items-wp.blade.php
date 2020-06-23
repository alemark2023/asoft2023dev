<html>
@if(!empty($records))

        <span>#</span>,
        <span>Código interno</span>,
        <span>Nombre</span>,
        <span>Nombre alternativo</span>,
        <span>Descripción</span>,
        <span>Unidad de medida</span>,
        <span>Posee IGV</span>,
        <span>Categoría</span>,
        <span>Marca</span>,
        <span>Precio</span>,
        <span>Fecha de vencimiento</span>

        @foreach($records as $key => $value)
        <br>
            {{$loop->iteration}},
            {{$value->internal_id}},
            {{$value->name}},
            {{$value->second_name }},
            {{$value->description }},
            {{$value->unit_type_id }},
            {{$value->has_igv }},
            {{$value->category_id }},
            {{$value->brand_id }},
            {{$value->sale_unit_price }},
            {{$value->date_of_due }}
        @endforeach
@endif

</html>
