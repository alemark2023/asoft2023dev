<?php

return [
    'signature_note' => env('SIGNATURE_NOTE_OSE', 'FACTURALO'),
    'signature_uri' => env('SIGNATURE_URI_OSE', 'signatureFACTURALO'),
    'api_service_url' => env('API_SERVICE_URL'),
    'api_service_token' => env('API_SERVICE_TOKEN', false),
    'sunat_alternate_server' => env('SUNAT_ALTERNATE_SERVER', false),
    /* al estar activo show_all_items_at_invoice se muestra todos los productos de todos los almacenes al momento de
    crear la factura, por default esta en true para mostrarse los productos de todos los establecimientos
    #432*/
    'show_all_items_at_invoice' => env('SHOW_ALL_ITEMS_AT_INVOICE', true),
    /* al estar activo show_all_items_with_out_stock se muestra todos los productos aunque no tengan stock al momento
    de crear la factura, por default esta en true para mostrarse los productos que no tienen stock
    #432*/
    'show_all_items_with_out_stock' => env('SHOW_ALL_ITEMS_WITH_OUT_STOCK', true),

];
