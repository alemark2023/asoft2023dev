/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_of_payment` date NOT NULL,
  `date_of_payment_real` date DEFAULT NULL,
  `reference_id` int(10) unsigned DEFAULT NULL,
  `payment_method_type_id` int(10) unsigned DEFAULT NULL,
  `has_card` tinyint(1) NOT NULL DEFAULT '0',
  `card_brand_id` int(10) unsigned DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment` decimal(12,2) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `reference_payment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bank_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bank_id` int(10) unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cci` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `bank_accounts_bank_id_foreign` (`bank_id`),
  KEY `bank_accounts_currency_type_id_foreign` (`currency_type_id`),
  CONSTRAINT `bank_accounts_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`),
  CONSTRAINT `bank_accounts_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `banks` VALUES (1,'BANCO SCOTIABANK',NULL,NULL,1),(2,'BANCO DE CREDITO DEL PERU',NULL,NULL,1),(3,'BANCO DE COMERCIO',NULL,NULL,1),(4,'BANCO PICHINCHA',NULL,NULL,1),(5,'BBVA CONTINENTAL',NULL,NULL,1),(6,'INTERBANK',NULL,NULL,1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `billing_cycles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_time_start` datetime NOT NULL,
  `renew` tinyint(1) NOT NULL DEFAULT '0',
  `quantity_documents` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `business_turns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `business_turns` VALUES (1,'hotel','Hoteles',0,'2020-01-07 22:12:52','2020-01-07 22:12:52'),(2,'transport','Empresa de transporte de pasajeros',0,'2020-01-07 22:12:52','2020-01-07 22:12:52'),(3,'restaurant','Restaurantes',0,'2020-01-07 22:12:52','2020-01-07 22:12:52'),(4,'tap','Grifos',0,'2020-01-07 22:12:52','2020-01-07 22:12:52');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card_brands` (
  `id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  KEY `card_brands_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `card_brands` VALUES ('01','Visa',1),('02','Mastercard',1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `date_opening` date NOT NULL,
  `time_opening` time NOT NULL,
  `date_closed` date DEFAULT NULL,
  `time_closed` time DEFAULT NULL,
  `beginning_balance` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `final_balance` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `income` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `reference_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cash_user_id_foreign` (`user_id`),
  CONSTRAINT `cash_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cash_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cash_id` int(10) unsigned NOT NULL,
  `document_id` int(10) unsigned DEFAULT NULL,
  `sale_note_id` int(10) unsigned DEFAULT NULL,
  `expense_payment_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cash_documents_cash_id_foreign` (`cash_id`),
  KEY `cash_documents_document_id_foreign` (`document_id`),
  KEY `cash_documents_sale_note_id_foreign` (`sale_note_id`),
  KEY `cash_documents_expense_payment_id_foreign` (`expense_payment_id`),
  CONSTRAINT `cash_documents_cash_id_foreign` FOREIGN KEY (`cash_id`) REFERENCES `cash` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cash_documents_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cash_documents_expense_payment_id_foreign` FOREIGN KEY (`expense_payment_id`) REFERENCES `expense_payments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cash_documents_sale_note_id_foreign` FOREIGN KEY (`sale_note_id`) REFERENCES `sale_notes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_affectation_igv_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `exportation` tinyint(1) DEFAULT NULL,
  `free` tinyint(1) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_affectation_igv_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_affectation_igv_types` VALUES ('10',1,0,0,'Gravado - Operación Onerosa'),('11',1,0,1,'Gravado – Retiro por premio'),('12',1,0,1,'Gravado – Retiro por donación'),('13',1,0,1,'Gravado – Retiro'),('14',1,0,1,'Gravado – Retiro por publicidad'),('15',1,0,1,'Gravado – Bonificaciones'),('16',1,0,1,'Gravado – Retiro por entrega a trabajadores'),('17',0,0,1,'Gravado – IVAP'),('20',1,0,0,'Exonerado - Operación Onerosa'),('21',1,0,1,'Exonerado – Transferencia Gratuita'),('30',1,0,0,'Inafecto - Operación Onerosa'),('31',1,0,1,'Inafecto – Retiro por Bonificación'),('32',1,0,1,'Inafecto – Retiro'),('33',1,0,1,'Inafecto – Retiro por Muestras Médicas'),('34',1,0,1,'Inafecto - Retiro por Convenio Colectivo'),('35',1,0,1,'Inafecto – Retiro por premio'),('36',1,0,1,'Inafecto - Retiro por publicidad'),('37',1,0,1,'Inafecto - Transferencia gratuita'),('40',1,1,0,'Exportación de bienes o servicios');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_attribute_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_attribute_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_attribute_types` VALUES ('3001',1,'Detracciones: Recursos Hidrobiológicos-Matrícula de la embarcación'),('3002',1,'Detracciones: Recursos Hidrobiológicos-Nombre de la embarcación'),('3003',1,'Detracciones: Recursos Hidrobiológicos-Tipo de especie vendida'),('3004',1,'Detracciones: Recursos Hidrobiológicos-Lugar de descarga'),('3005',1,'Detracciones: Recursos Hidrobiológicos-Fecha de descarga'),('3006',1,'Detracciones: Recursos Hidrobiológicos-Cantidad de especie vendida'),('3050',1,'Transportre Terreste - Número de asiento'),('3051',1,'Transporte Terrestre - Información de manifiesto de pasajeros'),('3052',1,'Transporte Terrestre - Número de documento de identidad del pasajero'),('3053',1,'Transporte Terrestre - Tipo de documento de identidad del pasajero'),('3054',1,'Transporte Terrestre - Nombres y apellidos del pasajero'),('3055',1,'Transporte Terrestre - Ciudad o lugar de destino - Ubigeo'),('3056',1,'Transporte Terrestre - Ciudad o lugar de destino - Dirección detallada'),('3057',1,'Transporte Terrestre - Ciudad o lugar de origen - Ubigeo'),('3058',1,'Transporte Terrestre - Ciudad o lugar de origen - Dirección detallada'),('3059',1,'Transporte Terrestre - Fecha de inicio programado'),('3060',1,'Transporte Terrestre - Hora de inicio programado'),('4000',1,'Beneficio Hospedajes-Paquete turístico: Código de país de emisión del pasaporte'),('4001',1,'Beneficio Hospedajes: Código de país de residencia del sujeto no domiciliado'),('4002',1,'Beneficio Hospedajes: Fecha de ingreso al país'),('4003',1,'Beneficio Hospedajes: Fecha de Ingreso al Establecimiento'),('4004',1,'Beneficio Hospedajes: Fecha de Salida del Establecimiento'),('4005',1,'Beneficio Hospedajes: Número de Días de Permanencia'),('4006',1,'Beneficio Hospedajes: Fecha de Consumo'),('4007',1,'Beneficio Hospedajes-Paquete turístico: Nombres y apellidos del huesped'),('4008',1,'Beneficio Hospedajes-Paquete turístico: Tipo de documento de identidad del huesped'),('4009',1,'Beneficio Hospedajes-Paquete turístico: Número de documento de identidad del huesped'),('4030',1,'Carta Porte Aéreo:  Lugar de origen - Código de ubigeo'),('4031',1,'Carta Porte Aéreo:  Lugar de origen - Dirección detallada'),('4032',1,'Carta Porte Aéreo:  Lugar de destino - Código de ubigeo'),('4033',1,'Carta Porte Aéreo:  Lugar de destino - Dirección detallada'),('4040',1,'BVME transporte ferroviario: Pasajero - Apellidos y Nombres'),('4041',1,'BVME transporte ferroviario: Pasajero - Tipo de documento de identidad'),('4042',1,'BVME transporte ferroviario: Servicio transporte: Ciudad o lugar de origen - Código de ubigeo'),('4043',1,'BVME transporte ferroviario: Servicio transporte: Ciudad o lugar de origen - Dirección detallada'),('4044',1,'BVME transporte ferroviario: Servicio transporte: Ciudad o lugar de destino - Código de ubigeo'),('4045',1,'BVME transporte ferroviario: Servicio transporte: Ciudad o lugar de destino - Dirección detallada'),('4046',1,'BVME transporte ferroviario: Servicio transporte:Número de asiento'),('4047',1,'BVME transporte ferroviario: Servicio transporte: Hora programada de inicio de viaje'),('4048',1,'BVME transporte ferroviario: Servicio transporte: Fecha programada de inicio de viaje'),('4049',1,'BVME transporte ferroviario: Pasajero - Número de documento de identidad'),('4060',1,'Regalía Petrolera: Decreto Supremo de aprobación del contrato'),('4061',1,'Regalía Petrolera: Area de contrato (Lote)'),('4062',1,'Regalía Petrolera: Periodo de pago - Fecha de inicio'),('4063',1,'Regalía Petrolera: Periodo de pago - Fecha de fin'),('4064',1,'Regalía Petrolera: Fecha de Pago'),('5000',1,'Proveedores Estado: Número de Expediente'),('5001',1,'Proveedores Estado: Código de Unidad Ejecutora'),('5002',1,'Proveedores Estado: N° de Proceso de Selección'),('5003',1,'Proveedores Estado: N° de Contrato'),('5010',1,'Numero de Placa'),('5011',1,'Categoria'),('5012',1,'Marca'),('5013',1,'Modelo'),('5014',1,'Color'),('5015',1,'Motor'),('5016',1,'Combustible'),('5017',1,'Form. Rodante'),('5018',1,'VIN'),('5019',1,'Serie/Chasis'),('5020',1,'Año fabricacion'),('5021',1,'Año modelo'),('5022',1,'Version'),('5023',1,'Ejes'),('5024',1,'Asientos'),('5025',1,'Pasajeros'),('5026',1,'Ruedas'),('5027',1,'Carroceria'),('5028',1,'Potencia'),('5029',1,'Cilindros'),('5030',1,'Ciliindrada'),('5031',1,'Peso Bruto'),('5032',1,'Peso Neto'),('5033',1,'Carga Util'),('5034',1,'Longitud'),('5035',1,'Altura'),('5036',1,'Ancho'),('6000',1,'Comercialización de Oro:  Código Unico Concesión Minera'),('6001',1,'Comercialización de Oro:  N° declaración compromiso'),('6002',1,'Comercialización de Oro:  N° Reg. Especial .Comerci. Oro'),('6003',1,'Comercialización de Oro:  N° Resolución que autoriza Planta de Beneficio'),('6004',1,'Comercialización de Oro: Ley Mineral (% concent. oro)'),('7000',1,'Gastos Art. 37 Renta:  Número de Placa'),('7001',1,'Créditos Hipotecarios: Tipo de préstamo'),('7002',1,'Créditos Hipotecarios: Indicador de Primera Vivienda'),('7003',1,'Créditos Hipotecarios: Partida Registral'),('7004',1,'Créditos Hipotecarios: Número de contrato'),('7005',1,'Créditos Hipotecarios: Fecha de otorgamiento del crédito'),('7006',1,'Créditos Hipotecarios: Dirección del predio - Código de ubigeo'),('7007',1,'Créditos Hipotecarios: Dirección del predio - Dirección completa'),('7008',1,'Créditos Hipotecarios: Dirección del predio - Urbanización'),('7009',1,'Créditos Hipotecarios: Dirección del predio - Provincia'),('7010',1,'Créditos Hipotecarios: Dirección del predio - Distrito'),('7011',1,'Créditos Hipotecarios: Dirección del predio - Departamento'),('7020',1,'Partida Arancelaria');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_charge_discount_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `base` tinyint(1) NOT NULL,
  `level` enum('item','global') COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('discount','charge') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_charge_discount_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_charge_discount_types` VALUES ('00',1,1,'item','discount','Descuentos que afectan la base imponible del IGV/IVAP'),('01',1,0,'item','discount','Descuentos que no afectan la base imponible del IGV/IVAP'),('02',1,1,'global','discount','Descuentos globales que afectan la base imponible del IGV/IVAP'),('03',1,0,'global','discount','Descuentos globales que no afectan la base imponible del IGV/IVAP'),('04',0,1,'global','discount','Descuentos globales por anticipos gravados que afectan la base imponible del IGV/IVAP'),('05',0,0,'global','discount','Descuentos globales por anticipos exonerados'),('06',0,0,'global','discount','Descuentos globales por anticipos inafectos'),('45',0,1,'global','charge','FISE'),('46',1,0,'global','charge','Recargo al consumo y/o propinas'),('47',1,1,'item','charge','Cargos que afectan la base imponible del IGV/IVAP'),('48',1,0,'item','charge','Cargos que no afectan la base imponible del IGV/IVAP'),('49',1,1,'global','charge','Cargos globales que afectan la base imponible del IGV/IVAP'),('50',1,0,'global','charge','Cargos globales que no afectan la base imponible del IGV/IVAP'),('51',0,1,'global','charge','Percepción venta interna'),('52',0,1,'global','charge','Percepción a la adquisición de combustible'),('53',0,1,'global','charge','Percepción realizada al agente de percepción con tasa especial');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_currency_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_currency_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_currency_types` VALUES ('PEN',1,'S/','Soles'),('USD',1,'$','Dólares Americanos');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_detraction_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage` decimal(6,2) NOT NULL,
  `operation_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_detraction_types_operation_type_id_foreign` (`operation_type_id`),
  KEY `cat_detraction_types_id_index` (`id`),
  CONSTRAINT `cat_detraction_types_operation_type_id_foreign` FOREIGN KEY (`operation_type_id`) REFERENCES `cat_operation_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_detraction_types` VALUES ('001',1,'Azúcar y melaza de caña',10.00,'1001'),('003',1,'Alcohol etílico',10.00,'1001'),('005',1,'Maíz amarillo duro',4.00,'1001'),('008',1,'Madera',4.00,'1001'),('016',1,'Aceite de pescado',10.00,'1001'),('019',1,'Arrendamiento de bienes',10.00,'1001'),('020',1,'Mantenimiento y reparación de bienes muebles',12.00,'1001'),('022',1,'Otros servicios empresariales',12.00,'1001'),('023',1,'Leche',4.00,'1001'),('025',1,'Fabricación de bienes por encargo',10.00,'1001');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_document_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `short` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_document_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_document_types` VALUES ('01',1,'FT','FACTURA ELECTRÓNICA'),('03',1,'BV','BOLETA DE VENTA ELECTRÓNICA'),('07',1,'NC','NOTA DE CRÉDITO'),('08',1,'ND','NOTA DE DÉBITO'),('09',1,NULL,'GUIA DE REMISIÓN REMITENTE'),('20',1,NULL,'COMPROBANTE DE RETENCIÓN ELECTRÓNICA'),('31',1,NULL,'Guía de remisión transportista'),('40',1,NULL,'COMPROBANTE DE PERCEPCIÓN ELECTRÓNICA'),('71',0,NULL,'Guia de remisión remitente complementaria'),('72',0,NULL,'Guia de remisión transportista complementaria'),('GU75',1,NULL,'GUÍA'),('NE76',1,NULL,'NOTA DE ENTRADA'),('80',1,NULL,'NOTA DE VENTA');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_identity_document_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_identity_document_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_identity_document_types` VALUES ('0',1,'Doc.trib.no.dom.sin.ruc'),('1',1,'DNI'),('4',1,'CE'),('6',1,'RUC'),('7',1,'Pasaporte'),('A',0,'Ced. Diplomática de identidad'),('B',0,'Documento identidad país residencia-no.d'),('C',0,'Tax Identification Number - TIN – Doc Trib PP.NN'),('D',0,'Identification Number - IN – Doc Trib PP. JJ'),('E',0,'TAM- Tarjeta Andina de Migración');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_legend_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_legend_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_legend_types` VALUES ('1000',1,'Monto en Letras'),('1002',1,'TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE'),('2000',1,'COMPROBANTE DE PERCEPCIÓN'),('2001',1,'BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA'),('2002',1,'SERVICIOS PRESTADOS EN LA AMAZONÍA  REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA'),('2003',1,'CONTRATOS DE CONSTRUCCIÓN EJECUTADOS  EN LA AMAZONÍA REGIÓN SELVA'),('2004',1,'Agencia de Viaje - Paquete turístico'),('2005',1,'Venta realizada por emisor itinerante'),('2006',1,'Operación sujeta a detracción'),('2007',1,'Operación sujeta al IVAP'),('2008',1,'VENTA EXONERADA DEL IGV-ISC-IPM. PROHIBIDA LA VENTA FUERA DE LA ZONA COMERCIAL DE TACNA'),('2009',1,'PRIMERA VENTA DE MERCANCÍA IDENTIFICABLE ENTRE USUARIOS DE LA ZONA COMERCIAL'),('2010',1,'Restitucion Simplificado de Derechos Arancelarios');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_note_credit_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_note_credit_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_note_credit_types` VALUES ('01',1,'Anulación de la operación'),('02',1,'Anulación por error en el RUC'),('03',1,'Corrección por error en la descripción'),('04',1,'Descuento global'),('05',1,'Descuento por ítem'),('06',1,'Devolución total'),('07',1,'Devolución por ítem'),('08',1,'Bonificación'),('09',1,'Disminución en el valor'),('10',1,'Otros Conceptos'),('11',1,'Ajustes de operaciones de exportación'),('12',1,'Ajustes afectos al IVAP');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_note_debit_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_note_debit_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_note_debit_types` VALUES ('01',1,'Intereses por mora'),('02',1,'Aumento en el valor'),('03',1,'Penalidades/ otros conceptos'),('10',1,'Ajustes de operaciones de exportación'),('11',1,'Ajustes afectos al IVAP');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_operation_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `exportation` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_operation_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_operation_types` VALUES ('0101',1,0,'Venta interna'),('0112',0,0,'Venta Interna - Sustenta Gastos Deducibles Persona Natural'),('0113',0,0,'Venta Interna - NRUS'),('0200',1,1,'Exportación de Bienes'),('0201',0,1,'Exportación de Servicios – Prestación servicios realizados íntegramente en el país'),('0202',0,1,'Exportación de Servicios – Prestación de servicios de hospedaje No Domiciliado'),('0203',0,1,'Exportación de Servicios – Transporte de navieras'),('0204',0,1,'Exportación de Servicios – Servicios a naves y aeronaves de bandera extranjera'),('0205',0,1,'Exportación de Servicios - Servicios que conformen un Paquete Turístico'),('0206',0,1,'Exportación de Servicios – Servicios complementarios al transporte de carga'),('0207',0,1,'Exportación de Servicios – Suministro de energía eléctrica a favor de sujetos domiciliados en ZED'),('0208',0,1,'Exportación de Servicios – Prestación servicios realizados parcialmente en el extranjero'),('0301',0,0,'Operaciones con Carta de porte aéreo (emitidas en el ámbito nacional)'),('0302',0,0,'Operaciones de Transporte ferroviario de pasajeros'),('0303',0,0,'Operaciones de Pago de regalía petrolera'),('0401',0,0,'Ventas no domiciliados que no califican como exportación'),('1001',1,0,'Operación Sujeta a Detracción'),('1002',0,0,'Operación Sujeta a Detracción- Recursos Hidrobiológicos'),('1003',0,0,'Operación Sujeta a Detracción- Servicios de Transporte Pasajeros'),('1004',0,0,'Operación Sujeta a Detracción- Servicios de Transporte Carga'),('2001',0,0,'Operación Sujeta a Percepción');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_other_tax_concept_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_other_tax_concept_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_other_tax_concept_types` VALUES ('1000',1,'Total valor de venta - operaciones exportadas'),('1001',1,'Total valor de venta - operaciones gravadas'),('1002',1,'Total valor de venta - operaciones inafectas'),('1003',1,'Total valor de venta - operaciones exoneradas'),('1004',1,'Total valor de venta – Operaciones gratuitas'),('1005',1,'Sub total de venta'),('2001',1,'Percepciones'),('2002',1,'Retenciones'),('2003',1,'Detracciones'),('2004',1,'Bonificaciones'),('2005',1,'Total descuentos'),('3001',1,'FISE (Ley 29852) Fondo Inclusión Social Energético');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_payment_method_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_payment_method_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_payment_method_types` VALUES ('001',1,'Depósito en cuenta'),('002',1,'Giro'),('003',1,'Transferencia de fondos'),('004',1,'Orden de pago'),('005',1,'Tarjeta de débito'),('006',1,'Tarjeta de crédito emitida en el país por una empresa del sistema financiero'),('007',1,'Cheques con la cláusula de \"NO NEGOCIABLE\", \"INTRANSFERIBLES\", \"NO A LA ORDEN\" u otra equivalente, a que se refiere el inciso g) del artículo 5° de la ley'),('008',1,'Efectivo, por operaciones en las que no existe obligación de utilizar medio de pago'),('009',1,'Efectivo, en los demás casos'),('010',1,'Medios de pago usados en comercio exterior'),('011',1,'Documentos emitidos por las EDPYMES y las cooperativas de ahorro y crédito no autorizadas a captar depósitos del público'),('012',1,'Tarjeta de crédito emitida en el país o en el exterior por una empresa no perteneciente al sistema financiero, cuyo objeto principal sea la emisión y administración de tarjetas de crédito'),('013',1,'Tarjetas de crédito emitidas en el exterior por empresas bancarias o financieras no domiciliadas'),('101',1,'Transferencias – Comercio exterior'),('102',1,'Cheques bancarios - Comercio exterior'),('103',1,'Orden de pago simple - Comercio exterior'),('104',1,'Orden de pago documentario - Comercio exterior'),('105',1,'Remesa simple - Comercio exterior'),('106',1,'Remesa documentaria - Comercio exterior'),('107',1,'Carta de crédito simple - Comercio exterior'),('108',1,'Carta de crédito documentario - Comercio exterior'),('999',1,'Otros medios de pago');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_perception_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `percentage` decimal(10,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_perception_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_perception_types` VALUES ('01',1,2.00,'Percepción Venta Interna'),('02',1,1.00,'Percepción a la adquisición de combustible'),('03',1,0.50,'Percepción realizada al agente de percepción con tasa especial');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_price_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_price_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_price_types` VALUES ('01',1,'Precio unitario (incluye el IGV)'),('02',1,'Valor referencial unitario en operaciones no onerosas');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_related_documents_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_related_documents_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_related_documents_types` VALUES ('01',1,'Numeración DAM'),('02',1,'Número de orden de entrega'),('03',1,'Número SCOP'),('04',1,'Número de manifiesto de carga'),('05',1,'Número de constancia de detracción'),('06',1,'Otros');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_related_tax_document_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_related_tax_document_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_related_tax_document_types` VALUES ('01',1,'Factura – emitida para corregir error en el RUC'),('02',1,'Factura – emitida por anticipos'),('03',1,'Boleta de Venta – emitida por anticipos'),('04',1,'Ticket de Salida - ENAPU'),('05',1,'Código SCOP'),('99',1,'Otros');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_retention_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `percentage` decimal(10,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_retention_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_retention_types` VALUES ('01',1,3.00,'Tasa 3%'),('02',1,6.00,'Tasa 6%');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_summary_status_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_summary_status_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_summary_status_types` VALUES ('1',1,'Adicionar'),('2',1,'Modificar'),('3',1,'Anulado');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_system_isc_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_system_isc_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_system_isc_types` VALUES ('01',1,'Sistema al valor'),('02',1,'Aplicación del Monto Fijo'),('03',1,'Sistema de Precios de Venta al Público');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_transfer_reason_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_transfer_reason_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_transfer_reason_types` VALUES ('01',1,'Venta'),('02',1,'Compra'),('04',1,'Traslado entre establecimientos de la misma empresa'),('08',1,'Importación'),('09',1,'Exportación'),('13',1,'Otros'),('14',1,'Venta sujeta a confirmación del comprador'),('18',1,'Traslado emisor itinerante CP'),('19',1,'Traslado a zona primaria');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_transport_mode_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_transport_mode_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_transport_mode_types` VALUES ('01',1,'Transporte público'),('02',1,'Transporte privado');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_unit_types` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `cat_unit_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `cat_unit_types` VALUES ('ZZ',1,NULL,'Servicio'),('BX',1,NULL,'Caja'),('GLL',1,NULL,'Galones'),('GRM',1,NULL,'Gramos'),('KGM',1,NULL,'Kilos'),('LTR',1,NULL,'Litros'),('MTR',1,NULL,'Metros'),('FOT',1,NULL,'Pies'),('INH',1,NULL,'Pulgadas'),('NIU',1,NULL,'Unidades'),('YRD',1,NULL,'Yardas'),('HUR',1,NULL,'Hora');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charge_padrones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identity_document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `soap_send_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '01',
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soap_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `soap_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `soap_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_due` date DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detraction_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_store` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operation_amazonia` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `companies_identity_document_type_id_foreign` (`identity_document_type_id`),
  KEY `companies_soap_type_id_foreign` (`soap_type_id`),
  CONSTRAINT `companies_identity_document_type_id_foreign` FOREIGN KEY (`identity_document_type_id`) REFERENCES `cat_identity_document_types` (`id`),
  CONSTRAINT `companies_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `companies` VALUES (1,'6','20345345345','test','test','01','01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subtotal_pen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_pen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `igv_pen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal_usd` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_usd` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `igv_usd` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `company_accounts` VALUES (1,'70111','12121','40111','70111','12122','40111');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuration_ecommerce` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `information_contact_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `information_contact_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `information_contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `information_contact_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `script_paypal` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_youtube` text COLLATE utf8mb4_unicode_ci,
  `link_twitter` text COLLATE utf8mb4_unicode_ci,
  `link_facebook` text COLLATE utf8mb4_unicode_ci,
  `tag_support` text COLLATE utf8mb4_unicode_ci,
  `tag_dollar` text COLLATE utf8mb4_unicode_ci,
  `tag_shipping` text COLLATE utf8mb4_unicode_ci,
  `token_public_culqui` text COLLATE utf8mb4_unicode_ci,
  `token_private_culqui` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `configuration_ecommerce` VALUES (1,'Admin','admin@mail.com','01 505-5555',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configurations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `send_auto` tinyint(1) NOT NULL,
  `formats` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `cron` tinyint(1) NOT NULL DEFAULT '1',
  `stock` tinyint(1) NOT NULL DEFAULT '1',
  `sunat_alternate_server` tinyint(1) NOT NULL DEFAULT '0',
  `limit_documents` bigint(20) NOT NULL DEFAULT '0',
  `limit_users` bigint(20) NOT NULL DEFAULT '10',
  `locked_emission` tinyint(1) NOT NULL DEFAULT '0',
  `plan` json DEFAULT NULL,
  `enable_whatsapp` tinyint(1) NOT NULL DEFAULT '1',
  `phone_whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visual` json DEFAULT NULL,
  `decimal_quantity` tinyint(4) NOT NULL DEFAULT '2',
  `locked_users` tinyint(1) NOT NULL DEFAULT '0',
  `date_time_start` datetime DEFAULT NULL,
  `quantity_documents` int(11) NOT NULL,
  `locked_tenant` tinyint(1) NOT NULL DEFAULT '0',
  `compact_sidebar` tinyint(1) NOT NULL DEFAULT '0',
  `amount_plastic_bag_taxes` decimal(6,2) NOT NULL DEFAULT '0.10',
  `config_system_env` tinyint(1) NOT NULL DEFAULT '1',
  `colums_grid_item` tinyint(4) DEFAULT '4',
  `options_pos` tinyint(1) NOT NULL DEFAULT '1',
  `edit_name_product` tinyint(1) NOT NULL DEFAULT '1',
  `restrict_receipt_date` tinyint(1) NOT NULL DEFAULT '1',
  `affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '10',
  `include_igv` tinyint(1) DEFAULT NULL,
  `product_only_location` tinyint(1) DEFAULT NULL,
  `terms_condition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cotizaction_finance` tinyint(1) NOT NULL DEFAULT '1',
  `legend_footer` tinyint(1) NOT NULL DEFAULT '0',
  `header_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `configurations` VALUES (1,1,'default',1,1,0,1,1,0,'{\"id\": 2, \"name\": \"uno\", \"locked\": 0, \"pricing\": 10, \"created_at\": \"2020-01-07 17:07:39\", \"updated_at\": \"2020-01-07 17:07:39\", \"limit_users\": 1, \"plan_documents\": {}, \"limit_documents\": 1}',1,NULL,'{\"bg\": \"light\", \"header\": \"light\", \"sidebars\": \"light\"}',2,0,'2020-01-07 17:14:12',0,0,0,0.10,1,4,1,1,1,'10',NULL,NULL,NULL,1,0,NULL,NULL,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_value` decimal(16,6) NOT NULL,
  `affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_base_igv` decimal(12,2) NOT NULL,
  `percentage_igv` decimal(12,2) NOT NULL,
  `total_igv` decimal(12,2) NOT NULL,
  `system_isc_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL,
  `price_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(16,6) NOT NULL,
  `total_value` decimal(12,2) NOT NULL,
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `attributes` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `charges` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contract_items_contract_id_foreign` (`contract_id`),
  KEY `contract_items_item_id_foreign` (`item_id`),
  KEY `contract_items_affectation_igv_type_id_foreign` (`affectation_igv_type_id`),
  KEY `contract_items_system_isc_type_id_foreign` (`system_isc_type_id`),
  KEY `contract_items_price_type_id_foreign` (`price_type_id`),
  CONSTRAINT `contract_items_affectation_igv_type_id_foreign` FOREIGN KEY (`affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `contract_items_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contract_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `contract_items_price_type_id_foreign` FOREIGN KEY (`price_type_id`) REFERENCES `cat_price_types` (`id`),
  CONSTRAINT `contract_items_system_isc_type_id_foreign` FOREIGN KEY (`system_isc_type_id`) REFERENCES `cat_system_isc_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contract_id` int(10) unsigned NOT NULL,
  `date_of_payment` date NOT NULL,
  `payment_method_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_card` tinyint(1) NOT NULL DEFAULT '0',
  `card_brand_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change` decimal(12,2) DEFAULT NULL,
  `payment` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contract_payments_contract_id_foreign` (`contract_id`),
  KEY `contract_payments_card_brand_id_foreign` (`card_brand_id`),
  KEY `contract_payments_payment_method_type_id_foreign` (`payment_method_type_id`),
  CONSTRAINT `contract_payments_card_brand_id_foreign` FOREIGN KEY (`card_brand_id`) REFERENCES `card_brands` (`id`),
  CONSTRAINT `contract_payments_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contract_payments_payment_method_type_id_foreign` FOREIGN KEY (`payment_method_type_id`) REFERENCES `payment_method_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contracts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `establishment` json NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `date_of_due` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `customer` json NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `payment_method_type_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate_sale` decimal(13,3) NOT NULL,
  `total_prepayment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exportation` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_free` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxed` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_unaffected` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exonerated` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_igv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `charges` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `prepayments` json DEFAULT NULL,
  `guides` json DEFAULT NULL,
  `related` json DEFAULT NULL,
  `perception` json DEFAULT NULL,
  `detraction` json DEFAULT NULL,
  `legends` json DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms_condition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `quotation_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contracts_user_id_foreign` (`user_id`),
  KEY `contracts_establishment_id_foreign` (`establishment_id`),
  KEY `contracts_customer_id_foreign` (`customer_id`),
  KEY `contracts_soap_type_id_foreign` (`soap_type_id`),
  KEY `contracts_state_type_id_foreign` (`state_type_id`),
  KEY `contracts_currency_type_id_foreign` (`currency_type_id`),
  KEY `contracts_payment_method_type_id_foreign` (`payment_method_type_id`),
  KEY `contracts_quotation_id_foreign` (`quotation_id`),
  CONSTRAINT `contracts_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `contracts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `contracts_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `contracts_payment_method_type_id_foreign` FOREIGN KEY (`payment_method_type_id`) REFERENCES `payment_method_types` (`id`),
  CONSTRAINT `contracts_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`),
  CONSTRAINT `contracts_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `contracts_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `contracts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  KEY `countries_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `countries` VALUES ('AX','AALAND ISLANDS',1),('AF','AFGHANISTAN',1),('AL','ALBANIA',1),('DZ','ALGERIA',1),('AS','AMERICAN SAMOA',1),('AD','ANDORRA',1),('AO','ANGOLA',1),('AI','ANGUILLA',1),('AQ','ANTARCTICA',1),('AG','ANTIGUA AND BARBUDA',1),('AR','ARGENTINA',1),('AM','ARMENIA',1),('AW','ARUBA',1),('AU','AUSTRALIA',1),('AT','AUSTRIA',1),('AZ','AZERBAIJAN',1),('BS','BAHAMAS',1),('BH','BAHRAIN',1),('BD','BANGLADESH',1),('BB','BARBADOS',1),('BY','BELARUS',1),('BE','BELGIUM',1),('BZ','BELIZE',1),('BJ','BENIN',1),('BM','BERMUDA',1),('BT','BHUTAN',1),('BO','BOLIVIA',1),('BA','BOSNIA AND HERZEGOWINA',1),('BW','BOTSWANA',1),('BV','BOUVET ISLAND',1),('BR','BRAZIL',1),('IO','BRITISH INDIAN OCEAN TERRITORY',1),('BN','BRUNEI DARUSSALAM',1),('BG','BULGARIA',1),('BF','BURKINA FASO',1),('BI','BURUNDI',1),('KH','CAMBODIA',1),('CM','CAMEROON',1),('CA','CANADA',1),('CV','CAPE VERDE',1),('KY','CAYMAN ISLANDS',1),('CF','CENTRAL AFRICAN REPUBLIC',1),('TD','CHAD',1),('CL','CHILE',1),('CN','CHINA',1),('CX','CHRISTMAS ISLAND',1),('CC','COCOS (KEELING) ISLANDS',1),('CO','COLOMBIA',1),('KM','COMOROS',1),('CD','CONGO, Democratic Republic of (was Zaire)',1),('CG','CONGO, Republic of',1),('CK','COOK ISLANDS',1),('CR','COSTA RICA',1),('CI','COTE D`IVOIRE',1),('HR','CROATIA (local name: Hrvatska)',1),('CU','CUBA',1),('CY','CYPRUS',1),('CZ','CZECH REPUBLIC',1),('DK','DENMARK',1),('DJ','DJIBOUTI',1),('DM','DOMINICA',1),('DO','DOMINICAN REPUBLIC',1),('EC','ECUADOR',1),('EG','EGYPT',1),('SV','EL SALVADOR',1),('GQ','EQUATORIAL GUINEA',1),('ER','ERITREA',1),('EE','ESTONIA',1),('ET','ETHIOPIA',1),('FK','FALKLAND ISLANDS (MALVINAS)',1),('FO','FAROE ISLANDS',1),('FJ','FIJI',1),('FI','FINLAND',1),('FR','FRANCE',1),('GF','FRENCH GUIANA',1),('PF','FRENCH POLYNESIA',1),('TF','FRENCH SOUTHERN TERRITORIES',1),('GA','GABON',1),('GM','GAMBIA',1),('GE','GEORGIA',1),('DE','GERMANY',1),('GH','GHANA',1),('GI','GIBRALTAR',1),('GR','GREECE',1),('GL','GREENLAND',1),('GD','GRENADA',1),('GP','GUADELOUPE',1),('GU','GUAM',1),('GT','GUATEMALA',1),('GN','GUINEA',1),('GW','GUINEA-BISSAU',1),('GY','GUYANA',1),('HT','HAITI',1),('HM','HEARD AND MC DONALD ISLANDS',1),('HN','HONDURAS',1),('HK','HONG KONG',1),('HU','HUNGARY',1),('IS','ICELAND',1),('IN','INDIA',1),('ID','INDONESIA',1),('IR','IRAN (ISLAMIC REPUBLIC OF)',1),('IQ','IRAQ',1),('IE','IRELAND',1),('IL','ISRAEL',1),('IT','ITALY',1),('JM','JAMAICA',1),('JP','JAPAN',1),('JO','JORDAN',1),('KZ','KAZAKHSTAN',1),('KE','KENYA',1),('KI','KIRIBATI',1),('KP','KOREA, DEMOCRATIC PEOPLE`S REPUBLIC OF',1),('KR','KOREA, REPUBLIC OF',1),('KW','KUWAIT',1),('KG','KYRGYZSTAN',1),('LA','LAO PEOPLE`S DEMOCRATIC REPUBLIC',1),('LV','LATVIA',1),('LB','LEBANON',1),('LS','LESOTHO',1),('LR','LIBERIA',1),('LY','LIBYAN ARAB JAMAHIRIYA',1),('LI','LIECHTENSTEIN',1),('LT','LITHUANIA',1),('LU','LUXEMBOURG',1),('MO','MACAU',1),('MK','MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF',1),('MG','MADAGASCAR',1),('MW','MALAWI',1),('MY','MALAYSIA',1),('MV','MALDIVES',1),('ML','MALI',1),('MT','MALTA',1),('MH','MARSHALL ISLANDS',1),('MQ','MARTINIQUE',1),('MR','MAURITANIA',1),('MU','MAURITIUS',1),('YT','MAYOTTE',1),('MX','MEXICO',1),('FM','MICRONESIA, FEDERATED STATES OF',1),('MD','MOLDOVA, REPUBLIC OF',1),('MC','MONACO',1),('MN','MONGOLIA',1),('MS','MONTSERRAT',1),('MA','MOROCCO',1),('MZ','MOZAMBIQUE',1),('MM','MYANMAR',1),('NA','NAMIBIA',1),('NR','NAURU',1),('NP','NEPAL',1),('NL','NETHERLANDS',1),('AN','NETHERLANDS ANTILLES',1),('NC','NEW CALEDONIA',1),('NZ','NEW ZEALAND',1),('NI','NICARAGUA',1),('NE','NIGER',1),('NG','NIGERIA',1),('NU','NIUE',1),('NF','NORFOLK ISLAND',1),('MP','NORTHERN MARIANA ISLANDS',1),('NO','NORWAY',1),('OM','OMAN',1),('PK','PAKISTAN',1),('PW','PALAU',1),('PS','PALESTINIAN TERRITORY, Occupied',1),('PA','PANAMA',1),('PG','PAPUA NEW GUINEA',1),('PY','PARAGUAY',1),('PE','PERU',1),('PH','PHILIPPINES',1),('PN','PITCAIRN',1),('PL','POLAND',1),('PT','PORTUGAL',1),('PR','PUERTO RICO',1),('QA','QATAR',1),('RE','REUNION',1),('RO','ROMANIA',1),('RU','RUSSIAN FEDERATION',1),('RW','RWANDA',1),('SH','SAINT HELENA',1),('KN','SAINT KITTS AND NEVIS',1),('LC','SAINT LUCIA',1),('PM','SAINT PIERRE AND MIQUELON',1),('VC','SAINT VINCENT AND THE GRENADINES',1),('WS','SAMOA',1),('SM','SAN MARINO',1),('ST','SAO TOME AND PRINCIPE',1),('SA','SAUDI ARABIA',1),('SN','SENEGAL',1),('CS','SERBIA AND MONTENEGRO',1),('SC','SEYCHELLES',1),('SL','SIERRA LEONE',1),('SG','SINGAPORE',1),('SK','SLOVAKIA',1),('SI','SLOVENIA',1),('SB','SOLOMON ISLANDS',1),('SO','SOMALIA',1),('ZA','SOUTH AFRICA',1),('GS','SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',1),('ES','SPAIN',1),('LK','SRI LANKA',1),('SD','SUDAN',1),('SR','SURINAME',1),('SJ','SVALBARD AND JAN MAYEN ISLANDS',1),('SZ','SWAZILAND',1),('SE','SWEDEN',1),('CH','SWITZERLAND',1),('SY','SYRIAN ARAB REPUBLIC',1),('TW','TAIWAN',1),('TJ','TAJIKISTAN',1),('TZ','TANZANIA, UNITED REPUBLIC OF',1),('TH','THAILAND',1),('TL','TIMOR-LESTE',1),('TG','TOGO',1),('TK','TOKELAU',1),('TO','TONGA',1),('TT','TRINIDAD AND TOBAGO',1),('TN','TUNISIA',1),('TR','TURKEY',1),('TM','TURKMENISTAN',1),('TC','TURKS AND CAICOS ISLANDS',1),('TV','TUVALU',1),('UG','UGANDA',1),('UA','UKRAINE',1),('AE','UNITED ARAB EMIRATES',1),('GB','UNITED KINGDOM',1),('US','UNITED STATES',1),('UM','UNITED STATES MINOR OUTLYING ISLANDS',1),('UY','URUGUAY',1),('UZ','UZBEKISTAN',1),('VU','VANUATU',1),('VA','VATICAN CITY STATE (HOLY SEE)',1),('VE','VENEZUELA',1),('VN','VIET NAM',1),('VG','VIRGIN ISLANDS (BRITISH)',1),('VI','VIRGIN ISLANDS (U.S.)',1),('WF','WALLIS AND FUTUNA ISLANDS',1),('EH','WESTERN SAHARA',1),('YE','YEMEN',1),('ZM','ZAMBIA',1),('ZW','ZIMBABWE',1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  KEY `departments_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `departments` VALUES ('01','AMAZONAS',1),('02','ÁNCASH',1),('03','APURIMAC',1),('04','AREQUIPA',1),('05','AYACUCHO',1),('06','CAJAMARCA',1),('07','CALLAO',1),('08','CUSCO',1),('09','HUANCAVELICA',1),('10','HUÁNUCO',1),('11','ICA',1),('12','JUNÍN',1),('13','LA LIBERTAD',1),('14','LAMBAYEQUE',1),('15','LIMA',1),('16','LORETO',1),('17','MADRE DE DIOS',1),('18','MOQUEGUA',1),('19','PASCO',1),('20','PIURA',1),('21','PUNO',1),('22','SAN MARTIN',1),('23','TACNA',1),('24','TUMBES',1),('25','UCAYALI',1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dispatch_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dispatch_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dispatch_items_dispatch_id_foreign` (`dispatch_id`),
  KEY `dispatch_items_item_id_foreign` (`item_id`),
  CONSTRAINT `dispatch_items_dispatch_id_foreign` FOREIGN KEY (`dispatch_id`) REFERENCES `dispatches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dispatch_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dispatchers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identity_document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dispatchers_identity_document_type_id_foreign` (`identity_document_type_id`),
  KEY `dispatchers_number_index` (`number`),
  KEY `dispatchers_name_index` (`name`),
  CONSTRAINT `dispatchers_identity_document_type_id_foreign` FOREIGN KEY (`identity_document_type_id`) REFERENCES `cat_identity_document_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dispatches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `establishment` json NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubl_version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_document_id` int(10) unsigned DEFAULT NULL,
  `reference_quotation_id` int(10) unsigned DEFAULT NULL,
  `reference_order_form_id` int(10) unsigned DEFAULT NULL,
  `reference_order_note_id` int(10) unsigned DEFAULT NULL,
  `series` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int(11) NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `customer` json NOT NULL,
  `observations` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `transport_mode_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transfer_reason_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transfer_reason_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_shipping` date NOT NULL,
  `transshipment_indicator` tinyint(1) NOT NULL,
  `port_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_weight` decimal(10,2) NOT NULL,
  `packages_number` int(11) NOT NULL,
  `container_number` int(11) DEFAULT NULL,
  `origin` json NOT NULL,
  `delivery` json NOT NULL,
  `dispatcher` json DEFAULT NULL,
  `driver` json DEFAULT NULL,
  `license_plate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legends` json DEFAULT NULL,
  `optional` json DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_xml` tinyint(1) NOT NULL DEFAULT '0',
  `has_pdf` tinyint(1) NOT NULL DEFAULT '0',
  `has_cdr` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dispatches_user_id_foreign` (`user_id`),
  KEY `dispatches_establishment_id_foreign` (`establishment_id`),
  KEY `dispatches_soap_type_id_foreign` (`soap_type_id`),
  KEY `dispatches_state_type_id_foreign` (`state_type_id`),
  KEY `dispatches_document_type_id_foreign` (`document_type_id`),
  KEY `dispatches_customer_id_foreign` (`customer_id`),
  KEY `dispatches_unit_type_id_foreign` (`unit_type_id`),
  KEY `dispatches_transport_mode_type_id_foreign` (`transport_mode_type_id`),
  KEY `dispatches_transfer_reason_type_id_foreign` (`transfer_reason_type_id`),
  CONSTRAINT `dispatches_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `dispatches_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `cat_document_types` (`id`),
  CONSTRAINT `dispatches_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `dispatches_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `dispatches_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `dispatches_transfer_reason_type_id_foreign` FOREIGN KEY (`transfer_reason_type_id`) REFERENCES `cat_transfer_reason_types` (`id`),
  CONSTRAINT `dispatches_transport_mode_type_id_foreign` FOREIGN KEY (`transport_mode_type_id`) REFERENCES `cat_transport_mode_types` (`id`),
  CONSTRAINT `dispatches_unit_type_id_foreign` FOREIGN KEY (`unit_type_id`) REFERENCES `cat_unit_types` (`id`),
  CONSTRAINT `dispatches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `districts` (
  `id` char(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  KEY `districts_province_id_foreign` (`province_id`),
  KEY `districts_id_index` (`id`),
  CONSTRAINT `districts_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `districts` VALUES ('010101','0101','Chachapoyas',1),('010102','0101','Asunción',1),('010103','0101','Balsas',1),('010104','0101','Cheto',1),('010105','0101','Chiliquin',1),('010106','0101','Chuquibamba',1),('010107','0101','Granada',1),('010108','0101','Huancas',1),('010109','0101','La Jalca',1),('010110','0101','Leimebamba',1),('010111','0101','Levanto',1),('010112','0101','Magdalena',1),('010113','0101','Mariscal Castilla',1),('010114','0101','Molinopampa',1),('010115','0101','Montevideo',1),('010116','0101','Olleros',1),('010117','0101','Quinjalca',1),('010118','0101','San Francisco de Daguas',1),('010119','0101','San Isidro de Maino',1),('010120','0101','Soloco',1),('010121','0101','Sonche',1),('010201','0102','Bagua',1),('010202','0102','Aramango',1),('010203','0102','Copallin',1),('010204','0102','El Parco',1),('010205','0102','Imaza',1),('010206','0102','La Peca',1),('010301','0103','Jumbilla',1),('010302','0103','Chisquilla',1),('010303','0103','Churuja',1),('010304','0103','Corosha',1),('010305','0103','Cuispes',1),('010306','0103','Florida',1),('010307','0103','Jazan',1),('010308','0103','Recta',1),('010309','0103','San Carlos',1),('010310','0103','Shipasbamba',1),('010311','0103','Valera',1),('010312','0103','Yambrasbamba',1),('010401','0104','Nieva',1),('010402','0104','El Cenepa',1),('010403','0104','Río Santiago',1),('010501','0105','Lamud',1),('010502','0105','Camporredondo',1),('010503','0105','Cocabamba',1),('010504','0105','Colcamar',1),('010505','0105','Conila',1),('010506','0105','Inguilpata',1),('010507','0105','Longuita',1),('010508','0105','Lonya Chico',1),('010509','0105','Luya',1),('010510','0105','Luya Viejo',1),('010511','0105','María',1),('010512','0105','Ocalli',1),('010513','0105','Ocumal',1),('010514','0105','Pisuquia',1),('010515','0105','Providencia',1),('010516','0105','San Cristóbal',1),('010517','0105','San Francisco de Yeso',1),('010518','0105','San Jerónimo',1),('010519','0105','San Juan de Lopecancha',1),('010520','0105','Santa Catalina',1),('010521','0105','Santo Tomas',1),('010522','0105','Tingo',1),('010523','0105','Trita',1),('010601','0106','San Nicolás',1),('010602','0106','Chirimoto',1),('010603','0106','Cochamal',1),('010604','0106','Huambo',1),('010605','0106','Limabamba',1),('010606','0106','Longar',1),('010607','0106','Mariscal Benavides',1),('010608','0106','Milpuc',1),('010609','0106','Omia',1),('010610','0106','Santa Rosa',1),('010611','0106','Totora',1),('010612','0106','Vista Alegre',1),('010701','0107','Bagua Grande',1),('010702','0107','Cajaruro',1),('010703','0107','Cumba',1),('010704','0107','El Milagro',1),('010705','0107','Jamalca',1),('010706','0107','Lonya Grande',1),('010707','0107','Yamon',1),('020101','0201','Huaraz',1),('020102','0201','Cochabamba',1),('020103','0201','Colcabamba',1),('020104','0201','Huanchay',1),('020105','0201','Independencia',1),('020106','0201','Jangas',1),('020107','0201','La Libertad',1),('020108','0201','Olleros',1),('020109','0201','Pampas Grande',1),('020110','0201','Pariacoto',1),('020111','0201','Pira',1),('020112','0201','Tarica',1),('020201','0202','Aija',1),('020202','0202','Coris',1),('020203','0202','Huacllan',1),('020204','0202','La Merced',1),('020205','0202','Succha',1),('020301','0203','Llamellin',1),('020302','0203','Aczo',1),('020303','0203','Chaccho',1),('020304','0203','Chingas',1),('020305','0203','Mirgas',1),('020306','0203','San Juan de Rontoy',1),('020401','0204','Chacas',1),('020402','0204','Acochaca',1),('020501','0205','Chiquian',1),('020502','0205','Abelardo Pardo Lezameta',1),('020503','0205','Antonio Raymondi',1),('020504','0205','Aquia',1),('020505','0205','Cajacay',1),('020506','0205','Canis',1),('020507','0205','Colquioc',1),('020508','0205','Huallanca',1),('020509','0205','Huasta',1),('020510','0205','Huayllacayan',1),('020511','0205','La Primavera',1),('020512','0205','Mangas',1),('020513','0205','Pacllon',1),('020514','0205','San Miguel de Corpanqui',1),('020515','0205','Ticllos',1),('020601','0206','Carhuaz',1),('020602','0206','Acopampa',1),('020603','0206','Amashca',1),('020604','0206','Anta',1),('020605','0206','Ataquero',1),('020606','0206','Marcara',1),('020607','0206','Pariahuanca',1),('020608','0206','San Miguel de Aco',1),('020609','0206','Shilla',1),('020610','0206','Tinco',1),('020611','0206','Yungar',1),('020701','0207','San Luis',1),('020702','0207','San Nicolás',1),('020703','0207','Yauya',1),('020801','0208','Casma',1),('020802','0208','Buena Vista Alta',1),('020803','0208','Comandante Noel',1),('020804','0208','Yautan',1),('020901','0209','Corongo',1),('020902','0209','Aco',1),('020903','0209','Bambas',1),('020904','0209','Cusca',1),('020905','0209','La Pampa',1),('020906','0209','Yanac',1),('020907','0209','Yupan',1),('021001','0210','Huari',1),('021002','0210','Anra',1),('021003','0210','Cajay',1),('021004','0210','Chavin de Huantar',1),('021005','0210','Huacachi',1),('021006','0210','Huacchis',1),('021007','0210','Huachis',1),('021008','0210','Huantar',1),('021009','0210','Masin',1),('021010','0210','Paucas',1),('021011','0210','Ponto',1),('021012','0210','Rahuapampa',1),('021013','0210','Rapayan',1),('021014','0210','San Marcos',1),('021015','0210','San Pedro de Chana',1),('021016','0210','Uco',1),('021101','0211','Huarmey',1),('021102','0211','Cochapeti',1),('021103','0211','Culebras',1),('021104','0211','Huayan',1),('021105','0211','Malvas',1),('021201','0212','Caraz',1),('021202','0212','Huallanca',1),('021203','0212','Huata',1),('021204','0212','Huaylas',1),('021205','0212','Mato',1),('021206','0212','Pamparomas',1),('021207','0212','Pueblo Libre',1),('021208','0212','Santa Cruz',1),('021209','0212','Santo Toribio',1),('021210','0212','Yuracmarca',1),('021301','0213','Piscobamba',1),('021302','0213','Casca',1),('021303','0213','Eleazar Guzmán Barron',1),('021304','0213','Fidel Olivas Escudero',1),('021305','0213','Llama',1),('021306','0213','Llumpa',1),('021307','0213','Lucma',1),('021308','0213','Musga',1),('021401','0214','Ocros',1),('021402','0214','Acas',1),('021403','0214','Cajamarquilla',1),('021404','0214','Carhuapampa',1),('021405','0214','Cochas',1),('021406','0214','Congas',1),('021407','0214','Llipa',1),('021408','0214','San Cristóbal de Rajan',1),('021409','0214','San Pedro',1),('021410','0214','Santiago de Chilcas',1),('021501','0215','Cabana',1),('021502','0215','Bolognesi',1),('021503','0215','Conchucos',1),('021504','0215','Huacaschuque',1),('021505','0215','Huandoval',1),('021506','0215','Lacabamba',1),('021507','0215','Llapo',1),('021508','0215','Pallasca',1),('021509','0215','Pampas',1),('021510','0215','Santa Rosa',1),('021511','0215','Tauca',1),('021601','0216','Pomabamba',1),('021602','0216','Huayllan',1),('021603','0216','Parobamba',1),('021604','0216','Quinuabamba',1),('021701','0217','Recuay',1),('021702','0217','Catac',1),('021703','0217','Cotaparaco',1),('021704','0217','Huayllapampa',1),('021705','0217','Llacllin',1),('021706','0217','Marca',1),('021707','0217','Pampas Chico',1),('021708','0217','Pararin',1),('021709','0217','Tapacocha',1),('021710','0217','Ticapampa',1),('021801','0218','Chimbote',1),('021802','0218','Cáceres del Perú',1),('021803','0218','Coishco',1),('021804','0218','Macate',1),('021805','0218','Moro',1),('021806','0218','Nepeña',1),('021807','0218','Samanco',1),('021808','0218','Santa',1),('021809','0218','Nuevo Chimbote',1),('021901','0219','Sihuas',1),('021902','0219','Acobamba',1),('021903','0219','Alfonso Ugarte',1),('021904','0219','Cashapampa',1),('021905','0219','Chingalpo',1),('021906','0219','Huayllabamba',1),('021907','0219','Quiches',1),('021908','0219','Ragash',1),('021909','0219','San Juan',1),('021910','0219','Sicsibamba',1),('022001','0220','Yungay',1),('022002','0220','Cascapara',1),('022003','0220','Mancos',1),('022004','0220','Matacoto',1),('022005','0220','Quillo',1),('022006','0220','Ranrahirca',1),('022007','0220','Shupluy',1),('022008','0220','Yanama',1),('030101','0301','Abancay',1),('030102','0301','Chacoche',1),('030103','0301','Circa',1),('030104','0301','Curahuasi',1),('030105','0301','Huanipaca',1),('030106','0301','Lambrama',1),('030107','0301','Pichirhua',1),('030108','0301','San Pedro de Cachora',1),('030109','0301','Tamburco',1),('030201','0302','Andahuaylas',1),('030202','0302','Andarapa',1),('030203','0302','Chiara',1),('030204','0302','Huancarama',1),('030205','0302','Huancaray',1),('030206','0302','Huayana',1),('030207','0302','Kishuara',1),('030208','0302','Pacobamba',1),('030209','0302','Pacucha',1),('030210','0302','Pampachiri',1),('030211','0302','Pomacocha',1),('030212','0302','San Antonio de Cachi',1),('030213','0302','San Jerónimo',1),('030214','0302','San Miguel de Chaccrampa',1),('030215','0302','Santa María de Chicmo',1),('030216','0302','Talavera',1),('030217','0302','Tumay Huaraca',1),('030218','0302','Turpo',1),('030219','0302','Kaquiabamba',1),('030220','0302','José María Arguedas',1),('030301','0303','Antabamba',1),('030302','0303','El Oro',1),('030303','0303','Huaquirca',1),('030304','0303','Juan Espinoza Medrano',1),('030305','0303','Oropesa',1),('030306','0303','Pachaconas',1),('030307','0303','Sabaino',1),('030401','0304','Chalhuanca',1),('030402','0304','Capaya',1),('030403','0304','Caraybamba',1),('030404','0304','Chapimarca',1),('030405','0304','Colcabamba',1),('030406','0304','Cotaruse',1),('030407','0304','Ihuayllo',1),('030408','0304','Justo Apu Sahuaraura',1),('030409','0304','Lucre',1),('030410','0304','Pocohuanca',1),('030411','0304','San Juan de Chacña',1),('030412','0304','Sañayca',1),('030413','0304','Soraya',1),('030414','0304','Tapairihua',1),('030415','0304','Tintay',1),('030416','0304','Toraya',1),('030417','0304','Yanaca',1),('030501','0305','Tambobamba',1),('030502','0305','Cotabambas',1),('030503','0305','Coyllurqui',1),('030504','0305','Haquira',1),('030505','0305','Mara',1),('030506','0305','Challhuahuacho',1),('030601','0306','Chincheros',1),('030602','0306','Anco_Huallo',1),('030603','0306','Cocharcas',1),('030604','0306','Huaccana',1),('030605','0306','Ocobamba',1),('030606','0306','Ongoy',1),('030607','0306','Uranmarca',1),('030608','0306','Ranracancha',1),('030609','0306','Rocchacc',1),('030610','0306','El Porvenir',1),('030701','0307','Chuquibambilla',1),('030702','0307','Curpahuasi',1),('030703','0307','Gamarra',1),('030704','0307','Huayllati',1),('030705','0307','Mamara',1),('030706','0307','Micaela Bastidas',1),('030707','0307','Pataypampa',1),('030708','0307','Progreso',1),('030709','0307','San Antonio',1),('030710','0307','Santa Rosa',1),('030711','0307','Turpay',1),('030712','0307','Vilcabamba',1),('030713','0307','Virundo',1),('030714','0307','Curasco',1),('040101','0401','Arequipa',1),('040102','0401','Alto Selva Alegre',1),('040103','0401','Cayma',1),('040104','0401','Cerro Colorado',1),('040105','0401','Characato',1),('040106','0401','Chiguata',1),('040107','0401','Jacobo Hunter',1),('040108','0401','La Joya',1),('040109','0401','Mariano Melgar',1),('040110','0401','Miraflores',1),('040111','0401','Mollebaya',1),('040112','0401','Paucarpata',1),('040113','0401','Pocsi',1),('040114','0401','Polobaya',1),('040115','0401','Quequeña',1),('040116','0401','Sabandia',1),('040117','0401','Sachaca',1),('040118','0401','San Juan de Siguas',1),('040119','0401','San Juan de Tarucani',1),('040120','0401','Santa Isabel de Siguas',1),('040121','0401','Santa Rita de Siguas',1),('040122','0401','Socabaya',1),('040123','0401','Tiabaya',1),('040124','0401','Uchumayo',1),('040125','0401','Vitor',1),('040126','0401','Yanahuara',1),('040127','0401','Yarabamba',1),('040128','0401','Yura',1),('040129','0401','José Luis Bustamante Y Rivero',1),('040201','0402','Camaná',1),('040202','0402','José María Quimper',1),('040203','0402','Mariano Nicolás Valcárcel',1),('040204','0402','Mariscal Cáceres',1),('040205','0402','Nicolás de Pierola',1),('040206','0402','Ocoña',1),('040207','0402','Quilca',1),('040208','0402','Samuel Pastor',1),('040301','0403','Caravelí',1),('040302','0403','Acarí',1),('040303','0403','Atico',1),('040304','0403','Atiquipa',1),('040305','0403','Bella Unión',1),('040306','0403','Cahuacho',1),('040307','0403','Chala',1),('040308','0403','Chaparra',1),('040309','0403','Huanuhuanu',1),('040310','0403','Jaqui',1),('040311','0403','Lomas',1),('040312','0403','Quicacha',1),('040313','0403','Yauca',1),('040401','0404','Aplao',1),('040402','0404','Andagua',1),('040403','0404','Ayo',1),('040404','0404','Chachas',1),('040405','0404','Chilcaymarca',1),('040406','0404','Choco',1),('040407','0404','Huancarqui',1),('040408','0404','Machaguay',1),('040409','0404','Orcopampa',1),('040410','0404','Pampacolca',1),('040411','0404','Tipan',1),('040412','0404','Uñon',1),('040413','0404','Uraca',1),('040414','0404','Viraco',1),('040501','0405','Chivay',1),('040502','0405','Achoma',1),('040503','0405','Cabanaconde',1),('040504','0405','Callalli',1),('040505','0405','Caylloma',1),('040506','0405','Coporaque',1),('040507','0405','Huambo',1),('040508','0405','Huanca',1),('040509','0405','Ichupampa',1),('040510','0405','Lari',1),('040511','0405','Lluta',1),('040512','0405','Maca',1),('040513','0405','Madrigal',1),('040514','0405','San Antonio de Chuca',1),('040515','0405','Sibayo',1),('040516','0405','Tapay',1),('040517','0405','Tisco',1),('040518','0405','Tuti',1),('040519','0405','Yanque',1),('040520','0405','Majes',1),('040601','0406','Chuquibamba',1),('040602','0406','Andaray',1),('040603','0406','Cayarani',1),('040604','0406','Chichas',1),('040605','0406','Iray',1),('040606','0406','Río Grande',1),('040607','0406','Salamanca',1),('040608','0406','Yanaquihua',1),('040701','0407','Mollendo',1),('040702','0407','Cocachacra',1),('040703','0407','Dean Valdivia',1),('040704','0407','Islay',1),('040705','0407','Mejia',1),('040706','0407','Punta de Bombón',1),('040801','0408','Cotahuasi',1),('040802','0408','Alca',1),('040803','0408','Charcana',1),('040804','0408','Huaynacotas',1),('040805','0408','Pampamarca',1),('040806','0408','Puyca',1),('040807','0408','Quechualla',1),('040808','0408','Sayla',1),('040809','0408','Tauria',1),('040810','0408','Tomepampa',1),('040811','0408','Toro',1),('050101','0501','Ayacucho',1),('050102','0501','Acocro',1),('050103','0501','Acos Vinchos',1),('050104','0501','Carmen Alto',1),('050105','0501','Chiara',1),('050106','0501','Ocros',1),('050107','0501','Pacaycasa',1),('050108','0501','Quinua',1),('050109','0501','San José de Ticllas',1),('050110','0501','San Juan Bautista',1),('050111','0501','Santiago de Pischa',1),('050112','0501','Socos',1),('050113','0501','Tambillo',1),('050114','0501','Vinchos',1),('050115','0501','Jesús Nazareno',1),('050116','0501','Andrés Avelino Cáceres Dorregaray',1),('050201','0502','Cangallo',1),('050202','0502','Chuschi',1),('050203','0502','Los Morochucos',1),('050204','0502','María Parado de Bellido',1),('050205','0502','Paras',1),('050206','0502','Totos',1),('050301','0503','Sancos',1),('050302','0503','Carapo',1),('050303','0503','Sacsamarca',1),('050304','0503','Santiago de Lucanamarca',1),('050401','0504','Huanta',1),('050402','0504','Ayahuanco',1),('050403','0504','Huamanguilla',1),('050404','0504','Iguain',1),('050405','0504','Luricocha',1),('050406','0504','Santillana',1),('050407','0504','Sivia',1),('050408','0504','Llochegua',1),('050409','0504','Canayre',1),('050410','0504','Uchuraccay',1),('050411','0504','Pucacolpa',1),('050412','0504','Chaca',1),('050501','0505','San Miguel',1),('050502','0505','Anco',1),('050503','0505','Ayna',1),('050504','0505','Chilcas',1),('050505','0505','Chungui',1),('050506','0505','Luis Carranza',1),('050507','0505','Santa Rosa',1),('050508','0505','Tambo',1),('050509','0505','Samugari',1),('050510','0505','Anchihuay',1),('050601','0506','Puquio',1),('050602','0506','Aucara',1),('050603','0506','Cabana',1),('050604','0506','Carmen Salcedo',1),('050605','0506','Chaviña',1),('050606','0506','Chipao',1),('050607','0506','Huac-Huas',1),('050608','0506','Laramate',1),('050609','0506','Leoncio Prado',1),('050610','0506','Llauta',1),('050611','0506','Lucanas',1),('050612','0506','Ocaña',1),('050613','0506','Otoca',1),('050614','0506','Saisa',1),('050615','0506','San Cristóbal',1),('050616','0506','San Juan',1),('050617','0506','San Pedro',1),('050618','0506','San Pedro de Palco',1),('050619','0506','Sancos',1),('050620','0506','Santa Ana de Huaycahuacho',1),('050621','0506','Santa Lucia',1),('050701','0507','Coracora',1),('050702','0507','Chumpi',1),('050703','0507','Coronel Castañeda',1),('050704','0507','Pacapausa',1),('050705','0507','Pullo',1),('050706','0507','Puyusca',1),('050707','0507','San Francisco de Ravacayco',1),('050708','0507','Upahuacho',1),('050801','0508','Pausa',1),('050802','0508','Colta',1),('050803','0508','Corculla',1),('050804','0508','Lampa',1),('050805','0508','Marcabamba',1),('050806','0508','Oyolo',1),('050807','0508','Pararca',1),('050808','0508','San Javier de Alpabamba',1),('050809','0508','San José de Ushua',1),('050810','0508','Sara Sara',1),('050901','0509','Querobamba',1),('050902','0509','Belén',1),('050903','0509','Chalcos',1),('050904','0509','Chilcayoc',1),('050905','0509','Huacaña',1),('050906','0509','Morcolla',1),('050907','0509','Paico',1),('050908','0509','San Pedro de Larcay',1),('050909','0509','San Salvador de Quije',1),('050910','0509','Santiago de Paucaray',1),('050911','0509','Soras',1),('051001','0510','Huancapi',1),('051002','0510','Alcamenca',1),('051003','0510','Apongo',1),('051004','0510','Asquipata',1),('051005','0510','Canaria',1),('051006','0510','Cayara',1),('051007','0510','Colca',1),('051008','0510','Huamanquiquia',1),('051009','0510','Huancaraylla',1),('051010','0510','Huaya',1),('051011','0510','Sarhua',1),('051012','0510','Vilcanchos',1),('051101','0511','Vilcas Huaman',1),('051102','0511','Accomarca',1),('051103','0511','Carhuanca',1),('051104','0511','Concepción',1),('051105','0511','Huambalpa',1),('051106','0511','Independencia',1),('051107','0511','Saurama',1),('051108','0511','Vischongo',1),('060101','0601','Cajamarca',1),('060102','0601','Asunción',1),('060103','0601','Chetilla',1),('060104','0601','Cospan',1),('060105','0601','Encañada',1),('060106','0601','Jesús',1),('060107','0601','Llacanora',1),('060108','0601','Los Baños del Inca',1),('060109','0601','Magdalena',1),('060110','0601','Matara',1),('060111','0601','Namora',1),('060112','0601','San Juan',1),('060201','0602','Cajabamba',1),('060202','0602','Cachachi',1),('060203','0602','Condebamba',1),('060204','0602','Sitacocha',1),('060301','0603','Celendín',1),('060302','0603','Chumuch',1),('060303','0603','Cortegana',1),('060304','0603','Huasmin',1),('060305','0603','Jorge Chávez',1),('060306','0603','José Gálvez',1),('060307','0603','Miguel Iglesias',1),('060308','0603','Oxamarca',1),('060309','0603','Sorochuco',1),('060310','0603','Sucre',1),('060311','0603','Utco',1),('060312','0603','La Libertad de Pallan',1),('060401','0604','Chota',1),('060402','0604','Anguia',1),('060403','0604','Chadin',1),('060404','0604','Chiguirip',1),('060405','0604','Chimban',1),('060406','0604','Choropampa',1),('060407','0604','Cochabamba',1),('060408','0604','Conchan',1),('060409','0604','Huambos',1),('060410','0604','Lajas',1),('060411','0604','Llama',1),('060412','0604','Miracosta',1),('060413','0604','Paccha',1),('060414','0604','Pion',1),('060415','0604','Querocoto',1),('060416','0604','San Juan de Licupis',1),('060417','0604','Tacabamba',1),('060418','0604','Tocmoche',1),('060419','0604','Chalamarca',1),('060501','0605','Contumaza',1),('060502','0605','Chilete',1),('060503','0605','Cupisnique',1),('060504','0605','Guzmango',1),('060505','0605','San Benito',1),('060506','0605','Santa Cruz de Toledo',1),('060507','0605','Tantarica',1),('060508','0605','Yonan',1),('060601','0606','Cutervo',1),('060602','0606','Callayuc',1),('060603','0606','Choros',1),('060604','0606','Cujillo',1),('060605','0606','La Ramada',1),('060606','0606','Pimpingos',1),('060607','0606','Querocotillo',1),('060608','0606','San Andrés de Cutervo',1),('060609','0606','San Juan de Cutervo',1),('060610','0606','San Luis de Lucma',1),('060611','0606','Santa Cruz',1),('060612','0606','Santo Domingo de la Capilla',1),('060613','0606','Santo Tomas',1),('060614','0606','Socota',1),('060615','0606','Toribio Casanova',1),('060701','0607','Bambamarca',1),('060702','0607','Chugur',1),('060703','0607','Hualgayoc',1),('060801','0608','Jaén',1),('060802','0608','Bellavista',1),('060803','0608','Chontali',1),('060804','0608','Colasay',1),('060805','0608','Huabal',1),('060806','0608','Las Pirias',1),('060807','0608','Pomahuaca',1),('060808','0608','Pucara',1),('060809','0608','Sallique',1),('060810','0608','San Felipe',1),('060811','0608','San José del Alto',1),('060812','0608','Santa Rosa',1),('060901','0609','San Ignacio',1),('060902','0609','Chirinos',1),('060903','0609','Huarango',1),('060904','0609','La Coipa',1),('060905','0609','Namballe',1),('060906','0609','San José de Lourdes',1),('060907','0609','Tabaconas',1),('061001','0610','Pedro Gálvez',1),('061002','0610','Chancay',1),('061003','0610','Eduardo Villanueva',1),('061004','0610','Gregorio Pita',1),('061005','0610','Ichocan',1),('061006','0610','José Manuel Quiroz',1),('061007','0610','José Sabogal',1),('061101','0611','San Miguel',1),('061102','0611','Bolívar',1),('061103','0611','Calquis',1),('061104','0611','Catilluc',1),('061105','0611','El Prado',1),('061106','0611','La Florida',1),('061107','0611','Llapa',1),('061108','0611','Nanchoc',1),('061109','0611','Niepos',1),('061110','0611','San Gregorio',1),('061111','0611','San Silvestre de Cochan',1),('061112','0611','Tongod',1),('061113','0611','Unión Agua Blanca',1),('061201','0612','San Pablo',1),('061202','0612','San Bernardino',1),('061203','0612','San Luis',1),('061204','0612','Tumbaden',1),('061301','0613','Santa Cruz',1),('061302','0613','Andabamba',1),('061303','0613','Catache',1),('061304','0613','Chancaybaños',1),('061305','0613','La Esperanza',1),('061306','0613','Ninabamba',1),('061307','0613','Pulan',1),('061308','0613','Saucepampa',1),('061309','0613','Sexi',1),('061310','0613','Uticyacu',1),('061311','0613','Yauyucan',1),('070101','0701','Callao',1),('070102','0701','Bellavista',1),('070103','0701','Carmen de la Legua Reynoso',1),('070104','0701','La Perla',1),('070105','0701','La Punta',1),('070106','0701','Ventanilla',1),('070107','0701','Mi Perú',1),('080101','0801','Cusco',1),('080102','0801','Ccorca',1),('080103','0801','Poroy',1),('080104','0801','San Jerónimo',1),('080105','0801','San Sebastian',1),('080106','0801','Santiago',1),('080107','0801','Saylla',1),('080108','0801','Wanchaq',1),('080201','0802','Acomayo',1),('080202','0802','Acopia',1),('080203','0802','Acos',1),('080204','0802','Mosoc Llacta',1),('080205','0802','Pomacanchi',1),('080206','0802','Rondocan',1),('080207','0802','Sangarara',1),('080301','0803','Anta',1),('080302','0803','Ancahuasi',1),('080303','0803','Cachimayo',1),('080304','0803','Chinchaypujio',1),('080305','0803','Huarocondo',1),('080306','0803','Limatambo',1),('080307','0803','Mollepata',1),('080308','0803','Pucyura',1),('080309','0803','Zurite',1),('080401','0804','Calca',1),('080402','0804','Coya',1),('080403','0804','Lamay',1),('080404','0804','Lares',1),('080405','0804','Pisac',1),('080406','0804','San Salvador',1),('080407','0804','Taray',1),('080408','0804','Yanatile',1),('080501','0805','Yanaoca',1),('080502','0805','Checca',1),('080503','0805','Kunturkanki',1),('080504','0805','Langui',1),('080505','0805','Layo',1),('080506','0805','Pampamarca',1),('080507','0805','Quehue',1),('080508','0805','Tupac Amaru',1),('080601','0806','Sicuani',1),('080602','0806','Checacupe',1),('080603','0806','Combapata',1),('080604','0806','Marangani',1),('080605','0806','Pitumarca',1),('080606','0806','San Pablo',1),('080607','0806','San Pedro',1),('080608','0806','Tinta',1),('080701','0807','Santo Tomas',1),('080702','0807','Capacmarca',1),('080703','0807','Chamaca',1),('080704','0807','Colquemarca',1),('080705','0807','Livitaca',1),('080706','0807','Llusco',1),('080707','0807','Quiñota',1),('080708','0807','Velille',1),('080801','0808','Espinar',1),('080802','0808','Condoroma',1),('080803','0808','Coporaque',1),('080804','0808','Ocoruro',1),('080805','0808','Pallpata',1),('080806','0808','Pichigua',1),('080807','0808','Suyckutambo',1),('080808','0808','Alto Pichigua',1),('080901','0809','Santa Ana',1),('080902','0809','Echarate',1),('080903','0809','Huayopata',1),('080904','0809','Maranura',1),('080905','0809','Ocobamba',1),('080906','0809','Quellouno',1),('080907','0809','Kimbiri',1),('080908','0809','Santa Teresa',1),('080909','0809','Vilcabamba',1),('080910','0809','Pichari',1),('080911','0809','Inkawasi',1),('080912','0809','Villa Virgen',1),('080913','0809','Villa Kintiarina',1),('081001','0810','Paruro',1),('081002','0810','Accha',1),('081003','0810','Ccapi',1),('081004','0810','Colcha',1),('081005','0810','Huanoquite',1),('081006','0810','Omacha',1),('081007','0810','Paccaritambo',1),('081008','0810','Pillpinto',1),('081009','0810','Yaurisque',1),('081101','0811','Paucartambo',1),('081102','0811','Caicay',1),('081103','0811','Challabamba',1),('081104','0811','Colquepata',1),('081105','0811','Huancarani',1),('081106','0811','Kosñipata',1),('081201','0812','Urcos',1),('081202','0812','Andahuaylillas',1),('081203','0812','Camanti',1),('081204','0812','Ccarhuayo',1),('081205','0812','Ccatca',1),('081206','0812','Cusipata',1),('081207','0812','Huaro',1),('081208','0812','Lucre',1),('081209','0812','Marcapata',1),('081210','0812','Ocongate',1),('081211','0812','Oropesa',1),('081212','0812','Quiquijana',1),('081301','0813','Urubamba',1),('081302','0813','Chinchero',1),('081303','0813','Huayllabamba',1),('081304','0813','Machupicchu',1),('081305','0813','Maras',1),('081306','0813','Ollantaytambo',1),('081307','0813','Yucay',1),('090101','0901','Huancavelica',1),('090102','0901','Acobambilla',1),('090103','0901','Acoria',1),('090104','0901','Conayca',1),('090105','0901','Cuenca',1),('090106','0901','Huachocolpa',1),('090107','0901','Huayllahuara',1),('090108','0901','Izcuchaca',1),('090109','0901','Laria',1),('090110','0901','Manta',1),('090111','0901','Mariscal Cáceres',1),('090112','0901','Moya',1),('090113','0901','Nuevo Occoro',1),('090114','0901','Palca',1),('090115','0901','Pilchaca',1),('090116','0901','Vilca',1),('090117','0901','Yauli',1),('090118','0901','Ascensión',1),('090119','0901','Huando',1),('090201','0902','Acobamba',1),('090202','0902','Andabamba',1),('090203','0902','Anta',1),('090204','0902','Caja',1),('090205','0902','Marcas',1),('090206','0902','Paucara',1),('090207','0902','Pomacocha',1),('090208','0902','Rosario',1),('090301','0903','Lircay',1),('090302','0903','Anchonga',1),('090303','0903','Callanmarca',1),('090304','0903','Ccochaccasa',1),('090305','0903','Chincho',1),('090306','0903','Congalla',1),('090307','0903','Huanca-Huanca',1),('090308','0903','Huayllay Grande',1),('090309','0903','Julcamarca',1),('090310','0903','San Antonio de Antaparco',1),('090311','0903','Santo Tomas de Pata',1),('090312','0903','Secclla',1),('090401','0904','Castrovirreyna',1),('090402','0904','Arma',1),('090403','0904','Aurahua',1),('090404','0904','Capillas',1),('090405','0904','Chupamarca',1),('090406','0904','Cocas',1),('090407','0904','Huachos',1),('090408','0904','Huamatambo',1),('090409','0904','Mollepampa',1),('090410','0904','San Juan',1),('090411','0904','Santa Ana',1),('090412','0904','Tantara',1),('090413','0904','Ticrapo',1),('090501','0905','Churcampa',1),('090502','0905','Anco',1),('090503','0905','Chinchihuasi',1),('090504','0905','El Carmen',1),('090505','0905','La Merced',1),('090506','0905','Locroja',1),('090507','0905','Paucarbamba',1),('090508','0905','San Miguel de Mayocc',1),('090509','0905','San Pedro de Coris',1),('090510','0905','Pachamarca',1),('090511','0905','Cosme',1),('090601','0906','Huaytara',1),('090602','0906','Ayavi',1),('090603','0906','Córdova',1),('090604','0906','Huayacundo Arma',1),('090605','0906','Laramarca',1),('090606','0906','Ocoyo',1),('090607','0906','Pilpichaca',1),('090608','0906','Querco',1),('090609','0906','Quito-Arma',1),('090610','0906','San Antonio de Cusicancha',1),('090611','0906','San Francisco de Sangayaico',1),('090612','0906','San Isidro',1),('090613','0906','Santiago de Chocorvos',1),('090614','0906','Santiago de Quirahuara',1),('090615','0906','Santo Domingo de Capillas',1),('090616','0906','Tambo',1),('090701','0907','Pampas',1),('090702','0907','Acostambo',1),('090703','0907','Acraquia',1),('090704','0907','Ahuaycha',1),('090705','0907','Colcabamba',1),('090706','0907','Daniel Hernández',1),('090707','0907','Huachocolpa',1),('090709','0907','Huaribamba',1),('090710','0907','Ñahuimpuquio',1),('090711','0907','Pazos',1),('090713','0907','Quishuar',1),('090714','0907','Salcabamba',1),('090715','0907','Salcahuasi',1),('090716','0907','San Marcos de Rocchac',1),('090717','0907','Surcubamba',1),('090718','0907','Tintay Puncu',1),('090719','0907','Quichuas',1),('090720','0907','Andaymarca',1),('090721','0907','Roble',1),('090722','0907','Pichos',1),('100101','1001','Huanuco',1),('100102','1001','Amarilis',1),('100103','1001','Chinchao',1),('100104','1001','Churubamba',1),('100105','1001','Margos',1),('100106','1001','Quisqui (Kichki)',1),('100107','1001','San Francisco de Cayran',1),('100108','1001','San Pedro de Chaulan',1),('100109','1001','Santa María del Valle',1),('100110','1001','Yarumayo',1),('100111','1001','Pillco Marca',1),('100112','1001','Yacus',1),('100113','1001','San Pablo de Pillao',1),('100201','1002','Ambo',1),('100202','1002','Cayna',1),('100203','1002','Colpas',1),('100204','1002','Conchamarca',1),('100205','1002','Huacar',1),('100206','1002','San Francisco',1),('100207','1002','San Rafael',1),('100208','1002','Tomay Kichwa',1),('100301','1003','La Unión',1),('100307','1003','Chuquis',1),('100311','1003','Marías',1),('100313','1003','Pachas',1),('100316','1003','Quivilla',1),('100317','1003','Ripan',1),('100321','1003','Shunqui',1),('100322','1003','Sillapata',1),('100323','1003','Yanas',1),('100401','1004','Huacaybamba',1),('100402','1004','Canchabamba',1),('100403','1004','Cochabamba',1),('100404','1004','Pinra',1),('100501','1005','Llata',1),('100502','1005','Arancay',1),('100503','1005','Chavín de Pariarca',1),('100504','1005','Jacas Grande',1),('100505','1005','Jircan',1),('100506','1005','Miraflores',1),('100507','1005','Monzón',1),('100508','1005','Punchao',1),('100509','1005','Puños',1),('100510','1005','Singa',1),('100511','1005','Tantamayo',1),('100601','1006','Rupa-Rupa',1),('100602','1006','Daniel Alomía Robles',1),('100603','1006','Hermílio Valdizan',1),('100604','1006','José Crespo y Castillo',1),('100605','1006','Luyando',1),('100606','1006','Mariano Damaso Beraun',1),('100607','1006','Pucayacu',1),('100608','1006','Castillo Grande',1),('100701','1007','Huacrachuco',1),('100702','1007','Cholon',1),('100703','1007','San Buenaventura',1),('100704','1007','La Morada',1),('100705','1007','Santa Rosa de Alto Yanajanca',1),('100801','1008','Panao',1),('100802','1008','Chaglla',1),('100803','1008','Molino',1),('100804','1008','Umari',1),('100901','1009','Puerto Inca',1),('100902','1009','Codo del Pozuzo',1),('100903','1009','Honoria',1),('100904','1009','Tournavista',1),('100905','1009','Yuyapichis',1),('101001','1010','Jesús',1),('101002','1010','Baños',1),('101003','1010','Jivia',1),('101004','1010','Queropalca',1),('101005','1010','Rondos',1),('101006','1010','San Francisco de Asís',1),('101007','1010','San Miguel de Cauri',1),('101101','1011','Chavinillo',1),('101102','1011','Cahuac',1),('101103','1011','Chacabamba',1),('101104','1011','Aparicio Pomares',1),('101105','1011','Jacas Chico',1),('101106','1011','Obas',1),('101107','1011','Pampamarca',1),('101108','1011','Choras',1),('110101','1101','Ica',1),('110102','1101','La Tinguiña',1),('110103','1101','Los Aquijes',1),('110104','1101','Ocucaje',1),('110105','1101','Pachacutec',1),('110106','1101','Parcona',1),('110107','1101','Pueblo Nuevo',1),('110108','1101','Salas',1),('110109','1101','San José de Los Molinos',1),('110110','1101','San Juan Bautista',1),('110111','1101','Santiago',1),('110112','1101','Subtanjalla',1),('110113','1101','Tate',1),('110114','1101','Yauca del Rosario',1),('110201','1102','Chincha Alta',1),('110202','1102','Alto Laran',1),('110203','1102','Chavin',1),('110204','1102','Chincha Baja',1),('110205','1102','El Carmen',1),('110206','1102','Grocio Prado',1),('110207','1102','Pueblo Nuevo',1),('110208','1102','San Juan de Yanac',1),('110209','1102','San Pedro de Huacarpana',1),('110210','1102','Sunampe',1),('110211','1102','Tambo de Mora',1),('110301','1103','Nasca',1),('110302','1103','Changuillo',1),('110303','1103','El Ingenio',1),('110304','1103','Marcona',1),('110305','1103','Vista Alegre',1),('110401','1104','Palpa',1),('110402','1104','Llipata',1),('110403','1104','Río Grande',1),('110404','1104','Santa Cruz',1),('110405','1104','Tibillo',1),('110501','1105','Pisco',1),('110502','1105','Huancano',1),('110503','1105','Humay',1),('110504','1105','Independencia',1),('110505','1105','Paracas',1),('110506','1105','San Andrés',1),('110507','1105','San Clemente',1),('110508','1105','Tupac Amaru Inca',1),('120101','1201','Huancayo',1),('120104','1201','Carhuacallanga',1),('120105','1201','Chacapampa',1),('120106','1201','Chicche',1),('120107','1201','Chilca',1),('120108','1201','Chongos Alto',1),('120111','1201','Chupuro',1),('120112','1201','Colca',1),('120113','1201','Cullhuas',1),('120114','1201','El Tambo',1),('120116','1201','Huacrapuquio',1),('120117','1201','Hualhuas',1),('120119','1201','Huancan',1),('120120','1201','Huasicancha',1),('120121','1201','Huayucachi',1),('120122','1201','Ingenio',1),('120124','1201','Pariahuanca',1),('120125','1201','Pilcomayo',1),('120126','1201','Pucara',1),('120127','1201','Quichuay',1),('120128','1201','Quilcas',1),('120129','1201','San Agustín',1),('120130','1201','San Jerónimo de Tunan',1),('120132','1201','Saño',1),('120133','1201','Sapallanga',1),('120134','1201','Sicaya',1),('120135','1201','Santo Domingo de Acobamba',1),('120136','1201','Viques',1),('120201','1202','Concepción',1),('120202','1202','Aco',1),('120203','1202','Andamarca',1),('120204','1202','Chambara',1),('120205','1202','Cochas',1),('120206','1202','Comas',1),('120207','1202','Heroínas Toledo',1),('120208','1202','Manzanares',1),('120209','1202','Mariscal Castilla',1),('120210','1202','Matahuasi',1),('120211','1202','Mito',1),('120212','1202','Nueve de Julio',1),('120213','1202','Orcotuna',1),('120214','1202','San José de Quero',1),('120215','1202','Santa Rosa de Ocopa',1),('120301','1203','Chanchamayo',1),('120302','1203','Perene',1),('120303','1203','Pichanaqui',1),('120304','1203','San Luis de Shuaro',1),('120305','1203','San Ramón',1),('120306','1203','Vitoc',1),('120401','1204','Jauja',1),('120402','1204','Acolla',1),('120403','1204','Apata',1),('120404','1204','Ataura',1),('120405','1204','Canchayllo',1),('120406','1204','Curicaca',1),('120407','1204','El Mantaro',1),('120408','1204','Huamali',1),('120409','1204','Huaripampa',1),('120410','1204','Huertas',1),('120411','1204','Janjaillo',1),('120412','1204','Julcán',1),('120413','1204','Leonor Ordóñez',1),('120414','1204','Llocllapampa',1),('120415','1204','Marco',1),('120416','1204','Masma',1),('120417','1204','Masma Chicche',1),('120418','1204','Molinos',1),('120419','1204','Monobamba',1),('120420','1204','Muqui',1),('120421','1204','Muquiyauyo',1),('120422','1204','Paca',1),('120423','1204','Paccha',1),('120424','1204','Pancan',1),('120425','1204','Parco',1),('120426','1204','Pomacancha',1),('120427','1204','Ricran',1),('120428','1204','San Lorenzo',1),('120429','1204','San Pedro de Chunan',1),('120430','1204','Sausa',1),('120431','1204','Sincos',1),('120432','1204','Tunan Marca',1),('120433','1204','Yauli',1),('120434','1204','Yauyos',1),('120501','1205','Junin',1),('120502','1205','Carhuamayo',1),('120503','1205','Ondores',1),('120504','1205','Ulcumayo',1),('120601','1206','Satipo',1),('120602','1206','Coviriali',1),('120603','1206','Llaylla',1),('120604','1206','Mazamari',1),('120605','1206','Pampa Hermosa',1),('120606','1206','Pangoa',1),('120607','1206','Río Negro',1),('120608','1206','Río Tambo',1),('120609','1206','Vizcatan del Ene',1),('120701','1207','Tarma',1),('120702','1207','Acobamba',1),('120703','1207','Huaricolca',1),('120704','1207','Huasahuasi',1),('120705','1207','La Unión',1),('120706','1207','Palca',1),('120707','1207','Palcamayo',1),('120708','1207','San Pedro de Cajas',1),('120709','1207','Tapo',1),('120801','1208','La Oroya',1),('120802','1208','Chacapalpa',1),('120803','1208','Huay-Huay',1),('120804','1208','Marcapomacocha',1),('120805','1208','Morococha',1),('120806','1208','Paccha',1),('120807','1208','Santa Bárbara de Carhuacayan',1),('120808','1208','Santa Rosa de Sacco',1),('120809','1208','Suitucancha',1),('120810','1208','Yauli',1),('120901','1209','Chupaca',1),('120902','1209','Ahuac',1),('120903','1209','Chongos Bajo',1),('120904','1209','Huachac',1),('120905','1209','Huamancaca Chico',1),('120906','1209','San Juan de Iscos',1),('120907','1209','San Juan de Jarpa',1),('120908','1209','Tres de Diciembre',1),('120909','1209','Yanacancha',1),('130101','1301','Trujillo',1),('130102','1301','El Porvenir',1),('130103','1301','Florencia de Mora',1),('130104','1301','Huanchaco',1),('130105','1301','La Esperanza',1),('130106','1301','Laredo',1),('130107','1301','Moche',1),('130108','1301','Poroto',1),('130109','1301','Salaverry',1),('130110','1301','Simbal',1),('130111','1301','Victor Larco Herrera',1),('130201','1302','Ascope',1),('130202','1302','Chicama',1),('130203','1302','Chocope',1),('130204','1302','Magdalena de Cao',1),('130205','1302','Paijan',1),('130206','1302','Rázuri',1),('130207','1302','Santiago de Cao',1),('130208','1302','Casa Grande',1),('130301','1303','Bolívar',1),('130302','1303','Bambamarca',1),('130303','1303','Condormarca',1),('130304','1303','Longotea',1),('130305','1303','Uchumarca',1),('130306','1303','Ucuncha',1),('130401','1304','Chepen',1),('130402','1304','Pacanga',1),('130403','1304','Pueblo Nuevo',1),('130501','1305','Julcan',1),('130502','1305','Calamarca',1),('130503','1305','Carabamba',1),('130504','1305','Huaso',1),('130601','1306','Otuzco',1),('130602','1306','Agallpampa',1),('130604','1306','Charat',1),('130605','1306','Huaranchal',1),('130606','1306','La Cuesta',1),('130608','1306','Mache',1),('130610','1306','Paranday',1),('130611','1306','Salpo',1),('130613','1306','Sinsicap',1),('130614','1306','Usquil',1),('130701','1307','San Pedro de Lloc',1),('130702','1307','Guadalupe',1),('130703','1307','Jequetepeque',1),('130704','1307','Pacasmayo',1),('130705','1307','San José',1),('130801','1308','Tayabamba',1),('130802','1308','Buldibuyo',1),('130803','1308','Chillia',1),('130804','1308','Huancaspata',1),('130805','1308','Huaylillas',1),('130806','1308','Huayo',1),('130807','1308','Ongon',1),('130808','1308','Parcoy',1),('130809','1308','Pataz',1),('130810','1308','Pias',1),('130811','1308','Santiago de Challas',1),('130812','1308','Taurija',1),('130813','1308','Urpay',1),('130901','1309','Huamachuco',1),('130902','1309','Chugay',1),('130903','1309','Cochorco',1),('130904','1309','Curgos',1),('130905','1309','Marcabal',1),('130906','1309','Sanagoran',1),('130907','1309','Sarin',1),('130908','1309','Sartimbamba',1),('131001','1310','Santiago de Chuco',1),('131002','1310','Angasmarca',1),('131003','1310','Cachicadan',1),('131004','1310','Mollebamba',1),('131005','1310','Mollepata',1),('131006','1310','Quiruvilca',1),('131007','1310','Santa Cruz de Chuca',1),('131008','1310','Sitabamba',1),('131101','1311','Cascas',1),('131102','1311','Lucma',1),('131103','1311','Marmot',1),('131104','1311','Sayapullo',1),('131201','1312','Viru',1),('131202','1312','Chao',1),('131203','1312','Guadalupito',1),('140101','1401','Chiclayo',1),('140102','1401','Chongoyape',1),('140103','1401','Eten',1),('140104','1401','Eten Puerto',1),('140105','1401','José Leonardo Ortiz',1),('140106','1401','La Victoria',1),('140107','1401','Lagunas',1),('140108','1401','Monsefu',1),('140109','1401','Nueva Arica',1),('140110','1401','Oyotun',1),('140111','1401','Picsi',1),('140112','1401','Pimentel',1),('140113','1401','Reque',1),('140114','1401','Santa Rosa',1),('140115','1401','Saña',1),('140116','1401','Cayalti',1),('140117','1401','Patapo',1),('140118','1401','Pomalca',1),('140119','1401','Pucala',1),('140120','1401','Tuman',1),('140201','1402','Ferreñafe',1),('140202','1402','Cañaris',1),('140203','1402','Incahuasi',1),('140204','1402','Manuel Antonio Mesones Muro',1),('140205','1402','Pitipo',1),('140206','1402','Pueblo Nuevo',1),('140301','1403','Lambayeque',1),('140302','1403','Chochope',1),('140303','1403','Illimo',1),('140304','1403','Jayanca',1),('140305','1403','Mochumi',1),('140306','1403','Morrope',1),('140307','1403','Motupe',1),('140308','1403','Olmos',1),('140309','1403','Pacora',1),('140310','1403','Salas',1),('140311','1403','San José',1),('140312','1403','Tucume',1),('150101','1501','Lima',1),('150102','1501','Ancón',1),('150103','1501','Ate',1),('150104','1501','Barranco',1),('150105','1501','Breña',1),('150106','1501','Carabayllo',1),('150107','1501','Chaclacayo',1),('150108','1501','Chorrillos',1),('150109','1501','Cieneguilla',1),('150110','1501','Comas',1),('150111','1501','El Agustino',1),('150112','1501','Independencia',1),('150113','1501','Jesús María',1),('150114','1501','La Molina',1),('150115','1501','La Victoria',1),('150116','1501','Lince',1),('150117','1501','Los Olivos',1),('150118','1501','Lurigancho',1),('150119','1501','Lurin',1),('150120','1501','Magdalena del Mar',1),('150121','1501','Pueblo Libre',1),('150122','1501','Miraflores',1),('150123','1501','Pachacamac',1),('150124','1501','Pucusana',1),('150125','1501','Puente Piedra',1),('150126','1501','Punta Hermosa',1),('150127','1501','Punta Negra',1),('150128','1501','Rímac',1),('150129','1501','San Bartolo',1),('150130','1501','San Borja',1),('150131','1501','San Isidro',1),('150132','1501','San Juan de Lurigancho',1),('150133','1501','San Juan de Miraflores',1),('150134','1501','San Luis',1),('150135','1501','San Martín de Porres',1),('150136','1501','San Miguel',1),('150137','1501','Santa Anita',1),('150138','1501','Santa María del Mar',1),('150139','1501','Santa Rosa',1),('150140','1501','Santiago de Surco',1),('150141','1501','Surquillo',1),('150142','1501','Villa El Salvador',1),('150143','1501','Villa María del Triunfo',1),('150201','1502','Barranca',1),('150202','1502','Paramonga',1),('150203','1502','Pativilca',1),('150204','1502','Supe',1),('150205','1502','Supe Puerto',1),('150301','1503','Cajatambo',1),('150302','1503','Copa',1),('150303','1503','Gorgor',1),('150304','1503','Huancapon',1),('150305','1503','Manas',1),('150401','1504','Canta',1),('150402','1504','Arahuay',1),('150403','1504','Huamantanga',1),('150404','1504','Huaros',1),('150405','1504','Lachaqui',1),('150406','1504','San Buenaventura',1),('150407','1504','Santa Rosa de Quives',1),('150501','1505','San Vicente de Cañete',1),('150502','1505','Asia',1),('150503','1505','Calango',1),('150504','1505','Cerro Azul',1),('150505','1505','Chilca',1),('150506','1505','Coayllo',1),('150507','1505','Imperial',1),('150508','1505','Lunahuana',1),('150509','1505','Mala',1),('150510','1505','Nuevo Imperial',1),('150511','1505','Pacaran',1),('150512','1505','Quilmana',1),('150513','1505','San Antonio',1),('150514','1505','San Luis',1),('150515','1505','Santa Cruz de Flores',1),('150516','1505','Zúñiga',1),('150601','1506','Huaral',1),('150602','1506','Atavillos Alto',1),('150603','1506','Atavillos Bajo',1),('150604','1506','Aucallama',1),('150605','1506','Chancay',1),('150606','1506','Ihuari',1),('150607','1506','Lampian',1),('150608','1506','Pacaraos',1),('150609','1506','San Miguel de Acos',1),('150610','1506','Santa Cruz de Andamarca',1),('150611','1506','Sumbilca',1),('150612','1506','Veintisiete de Noviembre',1),('150701','1507','Matucana',1),('150702','1507','Antioquia',1),('150703','1507','Callahuanca',1),('150704','1507','Carampoma',1),('150705','1507','Chicla',1),('150706','1507','Cuenca',1),('150707','1507','Huachupampa',1),('150708','1507','Huanza',1),('150709','1507','Huarochiri',1),('150710','1507','Lahuaytambo',1),('150711','1507','Langa',1),('150712','1507','Laraos',1),('150713','1507','Mariatana',1),('150714','1507','Ricardo Palma',1),('150715','1507','San Andrés de Tupicocha',1),('150716','1507','San Antonio',1),('150717','1507','San Bartolomé',1),('150718','1507','San Damian',1),('150719','1507','San Juan de Iris',1),('150720','1507','San Juan de Tantaranche',1),('150721','1507','San Lorenzo de Quinti',1),('150722','1507','San Mateo',1),('150723','1507','San Mateo de Otao',1),('150724','1507','San Pedro de Casta',1),('150725','1507','San Pedro de Huancayre',1),('150726','1507','Sangallaya',1),('150727','1507','Santa Cruz de Cocachacra',1),('150728','1507','Santa Eulalia',1),('150729','1507','Santiago de Anchucaya',1),('150730','1507','Santiago de Tuna',1),('150731','1507','Santo Domingo de Los Olleros',1),('150732','1507','Surco',1),('150801','1508','Huacho',1),('150802','1508','Ambar',1),('150803','1508','Caleta de Carquin',1),('150804','1508','Checras',1),('150805','1508','Hualmay',1),('150806','1508','Huaura',1),('150807','1508','Leoncio Prado',1),('150808','1508','Paccho',1),('150809','1508','Santa Leonor',1),('150810','1508','Santa María',1),('150811','1508','Sayan',1),('150812','1508','Vegueta',1),('150901','1509','Oyon',1),('150902','1509','Andajes',1),('150903','1509','Caujul',1),('150904','1509','Cochamarca',1),('150905','1509','Navan',1),('150906','1509','Pachangara',1),('151001','1510','Yauyos',1),('151002','1510','Alis',1),('151003','1510','Allauca',1),('151004','1510','Ayaviri',1),('151005','1510','Azángaro',1),('151006','1510','Cacra',1),('151007','1510','Carania',1),('151008','1510','Catahuasi',1),('151009','1510','Chocos',1),('151010','1510','Cochas',1),('151011','1510','Colonia',1),('151012','1510','Hongos',1),('151013','1510','Huampara',1),('151014','1510','Huancaya',1),('151015','1510','Huangascar',1),('151016','1510','Huantan',1),('151017','1510','Huañec',1),('151018','1510','Laraos',1),('151019','1510','Lincha',1),('151020','1510','Madean',1),('151021','1510','Miraflores',1),('151022','1510','Omas',1),('151023','1510','Putinza',1),('151024','1510','Quinches',1),('151025','1510','Quinocay',1),('151026','1510','San Joaquín',1),('151027','1510','San Pedro de Pilas',1),('151028','1510','Tanta',1),('151029','1510','Tauripampa',1),('151030','1510','Tomas',1),('151031','1510','Tupe',1),('151032','1510','Viñac',1),('151033','1510','Vitis',1),('160101','1601','Iquitos',1),('160102','1601','Alto Nanay',1),('160103','1601','Fernando Lores',1),('160104','1601','Indiana',1),('160105','1601','Las Amazonas',1),('160106','1601','Mazan',1),('160107','1601','Napo',1),('160108','1601','Punchana',1),('160110','1601','Torres Causana',1),('160112','1601','Belén',1),('160113','1601','San Juan Bautista',1),('160201','1602','Yurimaguas',1),('160202','1602','Balsapuerto',1),('160205','1602','Jeberos',1),('160206','1602','Lagunas',1),('160210','1602','Santa Cruz',1),('160211','1602','Teniente Cesar López Rojas',1),('160301','1603','Nauta',1),('160302','1603','Parinari',1),('160303','1603','Tigre',1),('160304','1603','Trompeteros',1),('160305','1603','Urarinas',1),('160401','1604','Ramón Castilla',1),('160402','1604','Pebas',1),('160403','1604','Yavari',1),('160404','1604','San Pablo',1),('160501','1605','Requena',1),('160502','1605','Alto Tapiche',1),('160503','1605','Capelo',1),('160504','1605','Emilio San Martín',1),('160505','1605','Maquia',1),('160506','1605','Puinahua',1),('160507','1605','Saquena',1),('160508','1605','Soplin',1),('160509','1605','Tapiche',1),('160510','1605','Jenaro Herrera',1),('160511','1605','Yaquerana',1),('160601','1606','Contamana',1),('160602','1606','Inahuaya',1),('160603','1606','Padre Márquez',1),('160604','1606','Pampa Hermosa',1),('160605','1606','Sarayacu',1),('160606','1606','Vargas Guerra',1),('160701','1607','Barranca',1),('160702','1607','Cahuapanas',1),('160703','1607','Manseriche',1),('160704','1607','Morona',1),('160705','1607','Pastaza',1),('160706','1607','Andoas',1),('160801','1608','Putumayo',1),('160802','1608','Rosa Panduro',1),('160803','1608','Teniente Manuel Clavero',1),('160804','1608','Yaguas',1),('170101','1701','Tambopata',1),('170102','1701','Inambari',1),('170103','1701','Las Piedras',1),('170104','1701','Laberinto',1),('170201','1702','Manu',1),('170202','1702','Fitzcarrald',1),('170203','1702','Madre de Dios',1),('170204','1702','Huepetuhe',1),('170301','1703','Iñapari',1),('170302','1703','Iberia',1),('170303','1703','Tahuamanu',1),('180101','1801','Moquegua',1),('180102','1801','Carumas',1),('180103','1801','Cuchumbaya',1),('180104','1801','Samegua',1),('180105','1801','San Cristóbal',1),('180106','1801','Torata',1),('180201','1802','Omate',1),('180202','1802','Chojata',1),('180203','1802','Coalaque',1),('180204','1802','Ichuña',1),('180205','1802','La Capilla',1),('180206','1802','Lloque',1),('180207','1802','Matalaque',1),('180208','1802','Puquina',1),('180209','1802','Quinistaquillas',1),('180210','1802','Ubinas',1),('180211','1802','Yunga',1),('180301','1803','Ilo',1),('180302','1803','El Algarrobal',1),('180303','1803','Pacocha',1),('190101','1901','Chaupimarca',1),('190102','1901','Huachon',1),('190103','1901','Huariaca',1),('190104','1901','Huayllay',1),('190105','1901','Ninacaca',1),('190106','1901','Pallanchacra',1),('190107','1901','Paucartambo',1),('190108','1901','San Francisco de Asís de Yarusyacan',1),('190109','1901','Simon Bolívar',1),('190110','1901','Ticlacayan',1),('190111','1901','Tinyahuarco',1),('190112','1901','Vicco',1),('190113','1901','Yanacancha',1),('190201','1902','Yanahuanca',1),('190202','1902','Chacayan',1),('190203','1902','Goyllarisquizga',1),('190204','1902','Paucar',1),('190205','1902','San Pedro de Pillao',1),('190206','1902','Santa Ana de Tusi',1),('190207','1902','Tapuc',1),('190208','1902','Vilcabamba',1),('190301','1903','Oxapampa',1),('190302','1903','Chontabamba',1),('190303','1903','Huancabamba',1),('190304','1903','Palcazu',1),('190305','1903','Pozuzo',1),('190306','1903','Puerto Bermúdez',1),('190307','1903','Villa Rica',1),('190308','1903','Constitución',1),('200101','2001','Piura',1),('200104','2001','Castilla',1),('200105','2001','Catacaos',1),('200107','2001','Cura Mori',1),('200108','2001','El Tallan',1),('200109','2001','La Arena',1),('200110','2001','La Unión',1),('200111','2001','Las Lomas',1),('200114','2001','Tambo Grande',1),('200115','2001','Veintiseis de Octubre',1),('200201','2002','Ayabaca',1),('200202','2002','Frias',1),('200203','2002','Jilili',1),('200204','2002','Lagunas',1),('200205','2002','Montero',1),('200206','2002','Pacaipampa',1),('200207','2002','Paimas',1),('200208','2002','Sapillica',1),('200209','2002','Sicchez',1),('200210','2002','Suyo',1),('200301','2003','Huancabamba',1),('200302','2003','Canchaque',1),('200303','2003','El Carmen de la Frontera',1),('200304','2003','Huarmaca',1),('200305','2003','Lalaquiz',1),('200306','2003','San Miguel de El Faique',1),('200307','2003','Sondor',1),('200308','2003','Sondorillo',1),('200401','2004','Chulucanas',1),('200402','2004','Buenos Aires',1),('200403','2004','Chalaco',1),('200404','2004','La Matanza',1),('200405','2004','Morropon',1),('200406','2004','Salitral',1),('200407','2004','San Juan de Bigote',1),('200408','2004','Santa Catalina de Mossa',1),('200409','2004','Santo Domingo',1),('200410','2004','YAMANGO',1),('200501','2005','PAITA',1),('200502','2005','AMOTAPE',1),('200503','2005','ARENAL',1),('200504','2005','COLAN',1),('200505','2005','LA HUACA',1),('200506','2005','Tamarindo',1),('200507','2005','Vichayal',1),('200601','2006','SULLANA',1),('200602','2006','Bellavista',1),('200603','2006','Ignacio Escudero',1),('200604','2006','Lancones',1),('200605','2006','Marcavelica',1),('200606','2006','Miguel Checa',1),('200607','2006','Querecotillo',1),('200608','2006','Salitral',1),('200701','2007','PARIÑAS',1),('200702','2007','EL ALTO',1),('200703','2007','LA BREA',1),('200704','2007','LOBITOS',1),('200705','2007','Los Organos',1),('200706','2007','MANCORA',1),('200801','2008','SECHURA',1),('200802','2008','Bellavista de la Unión',1),('200803','2008','BERNAL',1),('200804','2008','Cristo Nos Valga',1),('200805','2008','Vice',1),('200806','2008','Rinconada Llicuar',1),('210101','2101','Puno',1),('210102','2101','Acora',1),('210103','2101','Amantani',1),('210104','2101','Atuncolla',1),('210105','2101','Capachica',1),('210106','2101','Chucuito',1),('210107','2101','Coata',1),('210108','2101','Huata',1),('210109','2101','Mañazo',1),('210110','2101','Paucarcolla',1),('210111','2101','Pichacani',1),('210112','2101','Plateria',1),('210113','2101','San Antonio',1),('210114','2101','Tiquillaca',1),('210115','2101','Vilque',1),('210201','2102','Azángaro',1),('210202','2102','Achaya',1),('210203','2102','Arapa',1),('210204','2102','Asillo',1),('210205','2102','Caminaca',1),('210206','2102','Chupa',1),('210207','2102','José Domingo Choquehuanca',1),('210208','2102','Muñani',1),('210209','2102','Potoni',1),('210210','2102','Saman',1),('210211','2102','San Anton',1),('210212','2102','San José',1),('210213','2102','San Juan de Salinas',1),('210214','2102','Santiago de Pupuja',1),('210215','2102','Tirapata',1),('210301','2103','Macusani',1),('210302','2103','Ajoyani',1),('210303','2103','Ayapata',1),('210304','2103','Coasa',1),('210305','2103','Corani',1),('210306','2103','Crucero',1),('210307','2103','Ituata',1),('210308','2103','Ollachea',1),('210309','2103','San Gaban',1),('210310','2103','Usicayos',1),('210401','2104','Juli',1),('210402','2104','Desaguadero',1),('210403','2104','Huacullani',1),('210404','2104','Kelluyo',1),('210405','2104','Pisacoma',1),('210406','2104','Pomata',1),('210407','2104','Zepita',1),('210501','2105','Ilave',1),('210502','2105','Capazo',1),('210503','2105','Pilcuyo',1),('210504','2105','Santa Rosa',1),('210505','2105','Conduriri',1),('210601','2106','Huancane',1),('210602','2106','Cojata',1),('210603','2106','Huatasani',1),('210604','2106','Inchupalla',1),('210605','2106','Pusi',1),('210606','2106','Rosaspata',1),('210607','2106','Taraco',1),('210608','2106','Vilque Chico',1),('210701','2107','Lampa',1),('210702','2107','Cabanilla',1),('210703','2107','Calapuja',1),('210704','2107','Nicasio',1),('210705','2107','Ocuviri',1),('210706','2107','Palca',1),('210707','2107','Paratia',1),('210708','2107','Pucara',1),('210709','2107','Santa Lucia',1),('210710','2107','Vilavila',1),('210801','2108','Ayaviri',1),('210802','2108','Antauta',1),('210803','2108','Cupi',1),('210804','2108','Llalli',1),('210805','2108','Macari',1),('210806','2108','Nuñoa',1),('210807','2108','Orurillo',1),('210808','2108','Santa Rosa',1),('210809','2108','Umachiri',1),('210901','2109','Moho',1),('210902','2109','Conima',1),('210903','2109','Huayrapata',1),('210904','2109','Tilali',1),('211001','2110','Putina',1),('211002','2110','Ananea',1),('211003','2110','Pedro Vilca Apaza',1),('211004','2110','Quilcapuncu',1),('211005','2110','Sina',1),('211101','2111','Juliaca',1),('211102','2111','Cabana',1),('211103','2111','Cabanillas',1),('211104','2111','Caracoto',1),('211201','2112','Sandia',1),('211202','2112','Cuyocuyo',1),('211203','2112','Limbani',1),('211204','2112','Patambuco',1),('211205','2112','Phara',1),('211206','2112','Quiaca',1),('211207','2112','San Juan del Oro',1),('211208','2112','Yanahuaya',1),('211209','2112','Alto Inambari',1),('211210','2112','San Pedro de Putina Punco',1),('211301','2113','Yunguyo',1),('211302','2113','Anapia',1),('211303','2113','Copani',1),('211304','2113','Cuturapi',1),('211305','2113','Ollaraya',1),('211306','2113','Tinicachi',1),('211307','2113','Unicachi',1),('220101','2201','Moyobamba',1),('220102','2201','Calzada',1),('220103','2201','Habana',1),('220104','2201','Jepelacio',1),('220105','2201','Soritor',1),('220106','2201','Yantalo',1),('220201','2202','Bellavista',1),('220202','2202','Alto Biavo',1),('220203','2202','Bajo Biavo',1),('220204','2202','Huallaga',1),('220205','2202','San Pablo',1),('220206','2202','San Rafael',1),('220301','2203','San José de Sisa',1),('220302','2203','Agua Blanca',1),('220303','2203','San Martín',1),('220304','2203','Santa Rosa',1),('220305','2203','Shatoja',1),('220401','2204','Saposoa',1),('220402','2204','Alto Saposoa',1),('220403','2204','El Eslabón',1),('220404','2204','Piscoyacu',1),('220405','2204','Sacanche',1),('220406','2204','Tingo de Saposoa',1),('220501','2205','Lamas',1),('220502','2205','Alonso de Alvarado',1),('220503','2205','Barranquita',1),('220504','2205','Caynarachi',1),('220505','2205','Cuñumbuqui',1),('220506','2205','Pinto Recodo',1),('220507','2205','Rumisapa',1),('220508','2205','San Roque de Cumbaza',1),('220509','2205','Shanao',1),('220510','2205','Tabalosos',1),('220511','2205','Zapatero',1),('220601','2206','Juanjuí',1),('220602','2206','Campanilla',1),('220603','2206','Huicungo',1),('220604','2206','Pachiza',1),('220605','2206','Pajarillo',1),('220701','2207','Picota',1),('220702','2207','Buenos Aires',1),('220703','2207','Caspisapa',1),('220704','2207','Pilluana',1),('220705','2207','Pucacaca',1),('220706','2207','San Cristóbal',1),('220707','2207','San Hilarión',1),('220708','2207','Shamboyacu',1),('220709','2207','Tingo de Ponasa',1),('220710','2207','Tres Unidos',1),('220801','2208','Rioja',1),('220802','2208','Awajun',1),('220803','2208','Elías Soplin Vargas',1),('220804','2208','Nueva Cajamarca',1),('220805','2208','Pardo Miguel',1),('220806','2208','Posic',1),('220807','2208','San Fernando',1),('220808','2208','Yorongos',1),('220809','2208','Yuracyacu',1),('220901','2209','Tarapoto',1),('220902','2209','Alberto Leveau',1),('220903','2209','Cacatachi',1),('220904','2209','Chazuta',1),('220905','2209','Chipurana',1),('220906','2209','El Porvenir',1),('220907','2209','Huimbayoc',1),('220908','2209','Juan Guerra',1),('220909','2209','La Banda de Shilcayo',1),('220910','2209','Morales',1),('220911','2209','Papaplaya',1),('220912','2209','San Antonio',1),('220913','2209','Sauce',1),('220914','2209','Shapaja',1),('221001','2210','Tocache',1),('221002','2210','Nuevo Progreso',1),('221003','2210','Polvora',1),('221004','2210','Shunte',1),('221005','2210','Uchiza',1),('230101','2301','Tacna',1),('230102','2301','Alto de la Alianza',1),('230103','2301','Calana',1),('230104','2301','Ciudad Nueva',1),('230105','2301','Inclan',1),('230106','2301','Pachia',1),('230107','2301','Palca',1),('230108','2301','Pocollay',1),('230109','2301','Sama',1),('230110','2301','Coronel Gregorio Albarracín Lanchipa',1),('230111','2301','La Yarada los Palos',1),('230201','2302','Candarave',1),('230202','2302','Cairani',1),('230203','2302','Camilaca',1),('230204','2302','Curibaya',1),('230205','2302','Huanuara',1),('230206','2302','Quilahuani',1),('230301','2303','Locumba',1),('230302','2303','Ilabaya',1),('230303','2303','Ite',1),('230401','2304','Tarata',1),('230402','2304','Héroes Albarracín',1),('230403','2304','Estique',1),('230404','2304','Estique-Pampa',1),('230405','2304','Sitajara',1),('230406','2304','Susapaya',1),('230407','2304','Tarucachi',1),('230408','2304','Ticaco',1),('240101','2401','Tumbes',1),('240102','2401','Corrales',1),('240103','2401','La Cruz',1),('240104','2401','Pampas de Hospital',1),('240105','2401','San Jacinto',1),('240106','2401','San Juan de la Virgen',1),('240201','2402','Zorritos',1),('240202','2402','Casitas',1),('240203','2402','Canoas de Punta Sal',1),('240301','2403','Zarumilla',1),('240302','2403','Aguas Verdes',1),('240303','2403','Matapalo',1),('240304','2403','Papayal',1),('250101','2501','Calleria',1),('250102','2501','Campoverde',1),('250103','2501','Iparia',1),('250104','2501','Masisea',1),('250105','2501','Yarinacocha',1),('250106','2501','Nueva Requena',1),('250107','2501','Manantay',1),('250201','2502','Raymondi',1),('250202','2502','Sepahua',1),('250203','2502','Tahuania',1),('250204','2502','Yurua',1),('250301','2503','Padre Abad',1),('250302','2503','Irazola',1),('250303','2503','Curimana',1),('250304','2503','Neshuya',1),('250305','2503','Alexander Von Humboldt',1),('250401','2504','Purus',1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document_hotels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` int(10) unsigned NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity_document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` enum('M','F') COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int(11) NOT NULL,
  `civil_status` enum('S','C','V','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacionality` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_number` int(11) NOT NULL,
  `date_entry` date NOT NULL,
  `time_entry` time NOT NULL,
  `date_exit` date NOT NULL,
  `time_exit` time NOT NULL,
  `ocupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guests` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_hotels_document_id_foreign` (`document_id`),
  KEY `document_hotels_identity_document_type_id_foreign` (`identity_document_type_id`),
  CONSTRAINT `document_hotels_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_hotels_identity_document_type_id_foreign` FOREIGN KEY (`identity_document_type_id`) REFERENCES `cat_identity_document_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_value` decimal(16,6) NOT NULL,
  `affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_base_igv` decimal(12,2) NOT NULL,
  `percentage_igv` decimal(12,2) NOT NULL,
  `total_igv` decimal(12,2) NOT NULL,
  `system_isc_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_plastic_bag_taxes` decimal(6,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL,
  `price_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(16,6) NOT NULL,
  `total_value` decimal(12,2) NOT NULL,
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `attributes` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `charges` json DEFAULT NULL,
  `additional_information` text COLLATE utf8mb4_unicode_ci,
  `warehouse_id` int(10) unsigned DEFAULT NULL,
  `name_product_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `document_items_document_id_foreign` (`document_id`),
  KEY `document_items_item_id_foreign` (`item_id`),
  KEY `document_items_affectation_igv_type_id_foreign` (`affectation_igv_type_id`),
  KEY `document_items_system_isc_type_id_foreign` (`system_isc_type_id`),
  KEY `document_items_price_type_id_foreign` (`price_type_id`),
  KEY `document_items_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `document_items_affectation_igv_type_id_foreign` FOREIGN KEY (`affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `document_items_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `document_items_price_type_id_foreign` FOREIGN KEY (`price_type_id`) REFERENCES `cat_price_types` (`id`),
  CONSTRAINT `document_items_system_isc_type_id_foreign` FOREIGN KEY (`system_isc_type_id`) REFERENCES `cat_system_isc_types` (`id`),
  CONSTRAINT `document_items_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` int(10) unsigned NOT NULL,
  `date_of_payment` date NOT NULL,
  `payment_method_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_card` tinyint(1) NOT NULL DEFAULT '0',
  `card_brand_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change` decimal(12,2) DEFAULT NULL,
  `payment` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `document_payments_document_id_foreign` (`document_id`),
  KEY `document_payments_card_brand_id_foreign` (`card_brand_id`),
  KEY `document_payments_payment_method_type_id_foreign` (`payment_method_type_id`),
  KEY `document_payments_date_of_payment_index` (`date_of_payment`),
  CONSTRAINT `document_payments_card_brand_id_foreign` FOREIGN KEY (`card_brand_id`) REFERENCES `card_brands` (`id`),
  CONSTRAINT `document_payments_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_payments_payment_method_type_id_foreign` FOREIGN KEY (`payment_method_type_id`) REFERENCES `payment_method_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document_transports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` int(10) unsigned NOT NULL,
  `seat_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passenger_manifest` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity_document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_identity_document` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passenger_fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin_district_id` json NOT NULL,
  `origin_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destinatation_district_id` json NOT NULL,
  `destinatation_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_transports_document_id_foreign` (`document_id`),
  KEY `document_transports_identity_document_type_id_foreign` (`identity_document_type_id`),
  CONSTRAINT `document_transports_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_transports_identity_document_type_id_foreign` FOREIGN KEY (`identity_document_type_id`) REFERENCES `cat_identity_document_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `establishment` json NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubl_version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `series` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int(11) NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `customer` json NOT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_order` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_id` int(10) unsigned DEFAULT NULL,
  `sale_note_id` int(10) unsigned DEFAULT NULL,
  `order_note_id` int(10) unsigned DEFAULT NULL,
  `exchange_rate_sale` decimal(13,3) NOT NULL,
  `total_prepayment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exportation` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_free` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxed` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_unaffected` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exonerated` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_igv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_plastic_bag_taxes` decimal(6,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `has_prepayment` tinyint(1) NOT NULL DEFAULT '0',
  `affectation_type_prepayment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `was_deducted_prepayment` tinyint(1) NOT NULL DEFAULT '0',
  `charges` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `prepayments` json DEFAULT NULL,
  `guides` json DEFAULT NULL,
  `related` json DEFAULT NULL,
  `perception` json DEFAULT NULL,
  `detraction` json DEFAULT NULL,
  `legends` json DEFAULT NULL,
  `additional_information` text COLLATE utf8mb4_unicode_ci,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr` longtext COLLATE utf8mb4_unicode_ci COMMENT ' ',
  `has_xml` tinyint(1) NOT NULL DEFAULT '0',
  `has_pdf` tinyint(1) NOT NULL DEFAULT '0',
  `has_cdr` tinyint(1) NOT NULL DEFAULT '0',
  `data_json` json DEFAULT NULL,
  `send_server` tinyint(1) NOT NULL DEFAULT '1',
  `success_shipping_status` tinyint(1) NOT NULL DEFAULT '0',
  `shipping_status` json DEFAULT NULL,
  `success_sunat_shipping_status` tinyint(1) NOT NULL DEFAULT '0',
  `sunat_shipping_status` json DEFAULT NULL,
  `query_status` json DEFAULT NULL,
  `success_query_status` tinyint(1) NOT NULL DEFAULT '0',
  `total_canceled` tinyint(1) NOT NULL DEFAULT '0',
  `soap_shipping_response` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_user_id_foreign` (`user_id`),
  KEY `documents_establishment_id_foreign` (`establishment_id`),
  KEY `documents_customer_id_foreign` (`customer_id`),
  KEY `documents_soap_type_id_foreign` (`soap_type_id`),
  KEY `documents_state_type_id_foreign` (`state_type_id`),
  KEY `documents_group_id_foreign` (`group_id`),
  KEY `documents_document_type_id_foreign` (`document_type_id`),
  KEY `documents_currency_type_id_foreign` (`currency_type_id`),
  KEY `documents_quotation_id_foreign` (`quotation_id`),
  KEY `documents_external_id_index` (`external_id`),
  KEY `documents_sale_note_id_foreign` (`sale_note_id`),
  KEY `documents_series_index` (`series`),
  KEY `documents_number_index` (`number`),
  KEY `documents_date_of_issue_index` (`date_of_issue`),
  KEY `documents_order_note_id_foreign` (`order_note_id`),
  CONSTRAINT `documents_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `documents_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `documents_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `cat_document_types` (`id`),
  CONSTRAINT `documents_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `documents_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `documents_order_note_id_foreign` FOREIGN KEY (`order_note_id`) REFERENCES `order_notes` (`id`),
  CONSTRAINT `documents_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`),
  CONSTRAINT `documents_sale_note_id_foreign` FOREIGN KEY (`sale_note_id`) REFERENCES `sale_notes` (`id`),
  CONSTRAINT `documents_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `documents_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drivers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identity_document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `drivers_identity_document_type_id_foreign` (`identity_document_type_id`),
  KEY `drivers_number_index` (`number`),
  KEY `drivers_name_index` (`name`),
  CONSTRAINT `drivers_identity_document_type_id_foreign` FOREIGN KEY (`identity_document_type_id`) REFERENCES `cat_identity_document_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `establishments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district_id` char(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aditional_information` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trade_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `establishments_country_id_foreign` (`country_id`),
  KEY `establishments_department_id_foreign` (`department_id`),
  KEY `establishments_province_id_foreign` (`province_id`),
  KEY `establishments_district_id_foreign` (`district_id`),
  CONSTRAINT `establishments_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `establishments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `establishments_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`),
  CONSTRAINT `establishments_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `establishments` VALUES (1,'Oficina Principal','PE','15','1501','150101','-','demo@gmail.com','-','0000',NULL,NULL,NULL,NULL,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exchange_rates` (
  `date` date NOT NULL,
  `sale_original` decimal(13,3) NOT NULL,
  `purchase_original` decimal(13,3) NOT NULL,
  `purchase` decimal(13,3) NOT NULL,
  `sale` decimal(13,3) NOT NULL,
  `date_original` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `expense_id` int(10) unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `expense_items_expense_id_foreign` (`expense_id`),
  CONSTRAINT `expense_items_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_method_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_card` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `expense_method_types` VALUES (1,'Caja chica',0),(2,'Tarjeta de crédito',1),(3,'Tarjeta de débito',1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `expense_id` int(10) unsigned NOT NULL,
  `date_of_payment` date NOT NULL,
  `expense_method_type_id` int(10) unsigned NOT NULL,
  `has_card` tinyint(1) NOT NULL DEFAULT '0',
  `card_brand_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `expense_payments_expense_id_foreign` (`expense_id`),
  KEY `expense_payments_card_brand_id_foreign` (`card_brand_id`),
  KEY `expense_payments_expense_method_type_id_foreign` (`expense_method_type_id`),
  KEY `expense_payments_date_of_payment_index` (`date_of_payment`),
  CONSTRAINT `expense_payments_card_brand_id_foreign` FOREIGN KEY (`card_brand_id`) REFERENCES `card_brands` (`id`),
  CONSTRAINT `expense_payments_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expense_payments_expense_method_type_id_foreign` FOREIGN KEY (`expense_method_type_id`) REFERENCES `expense_method_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_reasons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `expense_reasons` VALUES (1,'Varios'),(2,'Representación de la organización'),(3,'Trabajo de campo');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `expense_types` VALUES (1,'PLANILLA','2020-01-07 22:11:33','2020-01-07 22:11:33'),(2,'RECIBO POR HONORARIO','2020-01-07 22:11:33','2020-01-07 22:11:33'),(3,'SERVICIO PÚBLICO','2020-01-07 22:11:33','2020-01-07 22:11:33'),(4,'OTROS','2020-01-07 22:11:33','2020-01-07 22:11:33');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expense_type_id` int(10) unsigned NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `supplier_id` int(10) unsigned NOT NULL,
  `expense_reason_id` int(10) unsigned NOT NULL DEFAULT '1',
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` int(11) NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `supplier` json NOT NULL,
  `exchange_rate_sale` decimal(13,3) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_user_id_foreign` (`user_id`),
  KEY `expenses_establishment_id_foreign` (`establishment_id`),
  KEY `expenses_supplier_id_foreign` (`supplier_id`),
  KEY `expenses_expense_type_id_foreign` (`expense_type_id`),
  KEY `expenses_currency_type_id_foreign` (`currency_type_id`),
  KEY `expenses_expense_reason_id_foreign` (`expense_reason_id`),
  KEY `expenses_soap_type_id_foreign` (`soap_type_id`),
  KEY `expenses_state_type_id_foreign` (`state_type_id`),
  CONSTRAINT `expenses_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `expenses_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `expenses_expense_reason_id_foreign` FOREIGN KEY (`expense_reason_id`) REFERENCES `expense_reasons` (`id`),
  CONSTRAINT `expenses_expense_type_id_foreign` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_types` (`id`),
  CONSTRAINT `expenses_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `expenses_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `expenses_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fixed_asset_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `internal_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_unit_price` decimal(16,6) NOT NULL,
  `purchase_affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fixed_asset_items_item_type_id_foreign` (`item_type_id`),
  KEY `fixed_asset_items_unit_type_id_foreign` (`unit_type_id`),
  KEY `fixed_asset_items_currency_type_id_foreign` (`currency_type_id`),
  KEY `fixed_asset_items_purchase_affectation_igv_type_id_foreign` (`purchase_affectation_igv_type_id`),
  CONSTRAINT `fixed_asset_items_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `fixed_asset_items_item_type_id_foreign` FOREIGN KEY (`item_type_id`) REFERENCES `item_types` (`id`),
  CONSTRAINT `fixed_asset_items_purchase_affectation_igv_type_id_foreign` FOREIGN KEY (`purchase_affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `fixed_asset_items_unit_type_id_foreign` FOREIGN KEY (`unit_type_id`) REFERENCES `cat_unit_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fixed_asset_purchase_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fixed_asset_purchase_id` int(10) unsigned NOT NULL,
  `fixed_asset_item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_value` decimal(16,6) NOT NULL,
  `affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_base_igv` decimal(12,2) NOT NULL,
  `percentage_igv` decimal(12,2) NOT NULL,
  `total_igv` decimal(12,2) NOT NULL,
  `system_isc_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL,
  `price_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(16,6) NOT NULL,
  `total_value` decimal(12,2) NOT NULL,
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `attributes` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `charges` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fixed_asset_purchase_items_fixed_asset_purchase_id_foreign` (`fixed_asset_purchase_id`),
  KEY `fixed_asset_purchase_items_fixed_asset_item_id_foreign` (`fixed_asset_item_id`),
  KEY `fixed_asset_purchase_items_affectation_igv_type_id_foreign` (`affectation_igv_type_id`),
  KEY `fixed_asset_purchase_items_system_isc_type_id_foreign` (`system_isc_type_id`),
  KEY `fixed_asset_purchase_items_price_type_id_foreign` (`price_type_id`),
  CONSTRAINT `fixed_asset_purchase_items_affectation_igv_type_id_foreign` FOREIGN KEY (`affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `fixed_asset_purchase_items_fixed_asset_item_id_foreign` FOREIGN KEY (`fixed_asset_item_id`) REFERENCES `fixed_asset_items` (`id`),
  CONSTRAINT `fixed_asset_purchase_items_fixed_asset_purchase_id_foreign` FOREIGN KEY (`fixed_asset_purchase_id`) REFERENCES `fixed_asset_purchases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fixed_asset_purchase_items_price_type_id_foreign` FOREIGN KEY (`price_type_id`) REFERENCES `cat_price_types` (`id`),
  CONSTRAINT `fixed_asset_purchase_items_system_isc_type_id_foreign` FOREIGN KEY (`system_isc_type_id`) REFERENCES `cat_system_isc_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fixed_asset_purchases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `series` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int(11) NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_due` date DEFAULT NULL,
  `time_of_issue` time NOT NULL,
  `supplier_id` int(10) unsigned NOT NULL,
  `supplier` json NOT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate_sale` decimal(13,3) NOT NULL,
  `total_prepayment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exportation` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_free` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxed` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_unaffected` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exonerated` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_igv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `perception_date` date DEFAULT NULL,
  `perception_number` int(11) DEFAULT NULL,
  `total_perception` decimal(12,2) DEFAULT NULL,
  `charges` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `prepayments` json DEFAULT NULL,
  `guides` json DEFAULT NULL,
  `related` json DEFAULT NULL,
  `perception` json DEFAULT NULL,
  `detraction` json DEFAULT NULL,
  `legends` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fixed_asset_purchases_user_id_foreign` (`user_id`),
  KEY `fixed_asset_purchases_establishment_id_foreign` (`establishment_id`),
  KEY `fixed_asset_purchases_supplier_id_foreign` (`supplier_id`),
  KEY `fixed_asset_purchases_customer_id_foreign` (`customer_id`),
  KEY `fixed_asset_purchases_soap_type_id_foreign` (`soap_type_id`),
  KEY `fixed_asset_purchases_state_type_id_foreign` (`state_type_id`),
  KEY `fixed_asset_purchases_group_id_foreign` (`group_id`),
  KEY `fixed_asset_purchases_document_type_id_foreign` (`document_type_id`),
  KEY `fixed_asset_purchases_currency_type_id_foreign` (`currency_type_id`),
  CONSTRAINT `fixed_asset_purchases_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `fixed_asset_purchases_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `fixed_asset_purchases_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `cat_document_types` (`id`),
  CONSTRAINT `fixed_asset_purchases_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `fixed_asset_purchases_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `fixed_asset_purchases_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `fixed_asset_purchases_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `fixed_asset_purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `fixed_asset_purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `format_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `formats` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `global_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination_id` int(11) NOT NULL,
  `destination_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_id` int(11) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `destination_index` (`destination_id`,`destination_type`),
  KEY `payment_index` (`payment_id`,`payment_type`),
  KEY `global_payments_soap_type_id_foreign` (`soap_type_id`),
  CONSTRAINT `global_payments_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `groups_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `groups` VALUES ('01','Facturas'),('02','Boletas');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `income` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `income_type_id` int(10) unsigned NOT NULL,
  `income_reason_id` int(10) unsigned NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `customer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int(11) NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `exchange_rate_sale` decimal(13,3) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `income_user_id_foreign` (`user_id`),
  KEY `income_establishment_id_foreign` (`establishment_id`),
  KEY `income_income_type_id_foreign` (`income_type_id`),
  KEY `income_income_reason_id_foreign` (`income_reason_id`),
  KEY `income_state_type_id_foreign` (`state_type_id`),
  KEY `income_soap_type_id_foreign` (`soap_type_id`),
  KEY `income_currency_type_id_foreign` (`currency_type_id`),
  KEY `income_number_index` (`number`),
  KEY `income_date_of_issue_index` (`date_of_issue`),
  CONSTRAINT `income_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `income_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `income_income_reason_id_foreign` FOREIGN KEY (`income_reason_id`) REFERENCES `income_reasons` (`id`),
  CONSTRAINT `income_income_type_id_foreign` FOREIGN KEY (`income_type_id`) REFERENCES `income_types` (`id`),
  CONSTRAINT `income_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `income_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `income_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `income_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `income_id` int(10) unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `income_items_income_id_foreign` (`income_id`),
  CONSTRAINT `income_items_income_id_foreign` FOREIGN KEY (`income_id`) REFERENCES `income` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `income_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `income_id` int(10) unsigned NOT NULL,
  `date_of_payment` date NOT NULL,
  `payment_method_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_card` tinyint(1) NOT NULL DEFAULT '0',
  `card_brand_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change` decimal(12,2) DEFAULT NULL,
  `payment` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `income_payments_income_id_foreign` (`income_id`),
  KEY `income_payments_card_brand_id_foreign` (`card_brand_id`),
  KEY `income_payments_payment_method_type_id_foreign` (`payment_method_type_id`),
  CONSTRAINT `income_payments_card_brand_id_foreign` FOREIGN KEY (`card_brand_id`) REFERENCES `card_brands` (`id`),
  CONSTRAINT `income_payments_income_id_foreign` FOREIGN KEY (`income_id`) REFERENCES `income` (`id`) ON DELETE CASCADE,
  CONSTRAINT `income_payments_payment_method_type_id_foreign` FOREIGN KEY (`payment_method_type_id`) REFERENCES `payment_method_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `income_reasons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `income_reasons` VALUES (1,'Varios');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `income_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `income_types` VALUES (1,'INGRESOS FINANCIEROS','2020-04-06 14:49:06','2020-04-06 14:49:06'),(2,'PRESTAMOS','2020-04-06 14:49:06','2020-04-06 14:49:06'),(3,'OTROS','2020-04-06 14:49:06','2020-04-06 14:49:06');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('1','2','3') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `warehouse_id` int(10) unsigned NOT NULL,
  `warehouse_destination_id` int(10) unsigned DEFAULT NULL,
  `inventory_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `lot_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inventories_transfer_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventories_item_id_foreign` (`item_id`),
  KEY `inventories_warehouse_id_foreign` (`warehouse_id`),
  KEY `inventories_inventory_transaction_id_foreign` (`inventory_transaction_id`),
  KEY `inventories_inventories_transfer_id_foreign` (`inventories_transfer_id`),
  CONSTRAINT `inventories_inventories_transfer_id_foreign` FOREIGN KEY (`inventories_transfer_id`) REFERENCES `inventories_transfer` (`id`),
  CONSTRAINT `inventories_inventory_transaction_id_foreign` FOREIGN KEY (`inventory_transaction_id`) REFERENCES `inventory_transactions` (`id`),
  CONSTRAINT `inventories_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventories_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventories_transfer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` int(10) unsigned NOT NULL,
  `warehouse_destination_id` int(10) unsigned NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventories_transfer_warehouse_id_foreign` (`warehouse_id`),
  KEY `inventories_transfer_warehouse_destination_id_foreign` (`warehouse_destination_id`),
  CONSTRAINT `inventories_transfer_warehouse_destination_id_foreign` FOREIGN KEY (`warehouse_destination_id`) REFERENCES `warehouses` (`id`),
  CONSTRAINT `inventories_transfer_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_configurations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stock_control` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `inventory_configurations` VALUES (1,0,NULL,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_kardex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_of_issue` date NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `inventory_kardexable_id` int(10) unsigned NOT NULL,
  `inventory_kardexable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int(10) unsigned NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_kardex_item_id_foreign` (`item_id`),
  KEY `inventory_kardex_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `inventory_kardex_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_kardex_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_transactions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('input','output') COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `inventory_transactions_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `inventory_transactions` VALUES ('02','Compra nacional','input'),('03','Consignación recibida','input'),('05','Devolución recibida','input'),('16','Inventario inicial','input'),('18','Entrada de importación','input'),('19','Ingreso de producción','input'),('20','Entrada por devolución de producción','input'),('21','Entrada por transferencia entre almacenes','input'),('22','Entrada por identificación erronea','input'),('24','Entrada por devolución del cliente','input'),('26','Entrada para servicio de producción','input'),('29','Entrada de bienes en prestamo','input'),('31','Entrada de bienes en custodia','input'),('50','Ingreso temporal','input'),('52','Ingreso por transformación','input'),('54','Ingreso de producción','input'),('55','Entrada de importación','input'),('57','Entrada por conversión de medida','input'),('91','Ingreso por transformación','input'),('93','Ingreso temporal','input'),('96','Entrada por conversión de medida','input'),('99','Otros','input'),('01','Venta nacional','output'),('04','Consignación entregada','output'),('06','Devolución entregada','output'),('07','Bonificación','output'),('08','Premio','output'),('09','Donación','output'),('10','Salida a producción','output'),('11','Salida por transferencia entre almacenes','output'),('12','Retiro','output'),('13','Mermas','output'),('14','Desmedros','output'),('15','Destrucción','output'),('17','Exportación','output'),('23','Salida por identificación erronea','output'),('25','Salida por devolución al proveedor','output'),('27','Salida por servicio de producción','output'),('28','Ajuste por diferencia de inventario','output'),('30','Salida de bienes en prestamo','output'),('32','Salida de bienes en custodia','output'),('33','Muestras médicas','output'),('34','Publicidad','output'),('35','Gastos de representación','output'),('36','Retiro para entrega a trabajadores','output'),('37','Retiro por convenio colectivo','output'),('38','Retiro por sustitución de bien siniestrado','output'),('51','Salida temporal','output'),('53','Salida para servicios terceros','output'),('56','Salida por conversión de medida','output');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` int(10) unsigned NOT NULL,
  `operation_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_due` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_document_id_foreign` (`document_id`),
  KEY `invoices_operation_type_id_foreign` (`operation_type_id`),
  CONSTRAINT `invoices_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_operation_type_id_foreign` FOREIGN KEY (`operation_type_id`) REFERENCES `cat_operation_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_images_item_id_foreign` (`item_id`),
  CONSTRAINT `item_images_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_lots` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `series` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `warehouse_id` int(10) unsigned DEFAULT NULL,
  `item_loteable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_loteable_id` int(10) unsigned NOT NULL,
  `has_sale` tinyint(1) NOT NULL DEFAULT '0',
  `state` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_lots_item_id_foreign` (`item_id`),
  KEY `item_lots_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `item_lots_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `item_lots_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_lots_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_of_due` date NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_lots_group_item_id_foreign` (`item_id`),
  CONSTRAINT `item_lots_group_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_sets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `individual_item_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_sets_item_id_foreign` (`item_id`),
  KEY `item_sets_individual_item_id_foreign` (`individual_item_id`),
  CONSTRAINT `item_sets_individual_item_id_foreign` FOREIGN KEY (`individual_item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `item_sets_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_tags_item_id_foreign` (`item_id`),
  KEY `item_tags_tag_id_foreign` (`tag_id`),
  CONSTRAINT `item_tags_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `item_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_types` (
  `id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `item_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `item_types` VALUES ('01','Producto'),('02','Servicio');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_unit_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `unit_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_unit` decimal(12,4) NOT NULL,
  `price1` decimal(12,4) NOT NULL,
  `price2` decimal(12,4) NOT NULL,
  `price3` decimal(12,4) NOT NULL,
  `price_default` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `item_unit_types_unit_type_id_foreign` (`unit_type_id`),
  KEY `item_unit_types_item_id_foreign` (`item_id`),
  CONSTRAINT `item_unit_types_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `item_unit_types_unit_type_id_foreign` FOREIGN KEY (`unit_type_id`) REFERENCES `cat_unit_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_warehouse` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `warehouse_id` int(10) unsigned NOT NULL,
  `stock` decimal(12,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_warehouse_item_id_foreign` (`item_id`),
  KEY `item_warehouse_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `item_warehouse_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `item_warehouse_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `technical_specifications` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `internal_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_due` date DEFAULT NULL,
  `account_id` int(10) unsigned DEFAULT NULL,
  `item_code_gs1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_unit_price` decimal(16,6) NOT NULL,
  `purchase_has_igv` tinyint(1) NOT NULL DEFAULT '1',
  `has_igv` tinyint(1) NOT NULL DEFAULT '1',
  `purchase_unit_price` decimal(16,6) NOT NULL DEFAULT '0.000000',
  `has_isc` tinyint(1) NOT NULL DEFAULT '0',
  `commission_amount` decimal(8,2) DEFAULT NULL,
  `line` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_plastic_bag_taxes` decimal(6,2) NOT NULL DEFAULT '0.10',
  `system_isc_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `percentage_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `suggested_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sale_affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `calculate_quantity` tinyint(1) NOT NULL DEFAULT '0',
  `sale_unit_price_set` decimal(16,6) DEFAULT NULL,
  `is_set` tinyint(1) NOT NULL DEFAULT '0',
  `category_id` int(10) unsigned DEFAULT NULL,
  `brand_id` int(10) unsigned DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'imagen-no-disponible.jpg',
  `image_medium` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'imagen-no-disponible.jpg',
  `image_small` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'imagen-no-disponible.jpg',
  `stock` decimal(16,4) NOT NULL DEFAULT '0.0000',
  `stock_min` decimal(12,2) NOT NULL DEFAULT '0.00',
  `lot_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lots_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `series_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `percentage_of_profit` decimal(12,2) NOT NULL DEFAULT '0.00',
  `has_perception` tinyint(1) NOT NULL DEFAULT '0',
  `percentage_perception` decimal(12,2) DEFAULT NULL,
  `attributes` json DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `warehouse_id` int(10) unsigned DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `apply_store` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `items_item_type_id_foreign` (`item_type_id`),
  KEY `items_unit_type_id_foreign` (`unit_type_id`),
  KEY `items_currency_type_id_foreign` (`currency_type_id`),
  KEY `items_system_isc_type_id_foreign` (`system_isc_type_id`),
  KEY `items_sale_affectation_igv_type_id_foreign` (`sale_affectation_igv_type_id`),
  KEY `items_purchase_affectation_igv_type_id_foreign` (`purchase_affectation_igv_type_id`),
  KEY `items_warehouse_id_foreign` (`warehouse_id`),
  KEY `items_account_id_foreign` (`account_id`),
  KEY `items_brand_id_foreign` (`brand_id`),
  KEY `items_category_id_foreign` (`category_id`),
  CONSTRAINT `items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  CONSTRAINT `items_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `items_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `items_item_type_id_foreign` FOREIGN KEY (`item_type_id`) REFERENCES `item_types` (`id`),
  CONSTRAINT `items_purchase_affectation_igv_type_id_foreign` FOREIGN KEY (`purchase_affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `items_sale_affectation_igv_type_id_foreign` FOREIGN KEY (`sale_affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `items_system_isc_type_id_foreign` FOREIGN KEY (`system_isc_type_id`) REFERENCES `cat_system_isc_types` (`id`),
  CONSTRAINT `items_unit_type_id_foreign` FOREIGN KEY (`unit_type_id`) REFERENCES `cat_unit_types` (`id`),
  CONSTRAINT `items_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_rating` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `value` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_rating_user_id_foreign` (`user_id`),
  KEY `items_rating_item_id_foreign` (`item_id`),
  CONSTRAINT `items_rating_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `items_rating_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kardex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_of_issue` date NOT NULL,
  `type` enum('sale','purchase') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `document_id` int(10) unsigned DEFAULT NULL,
  `purchase_id` int(10) unsigned DEFAULT NULL,
  `sale_note_id` int(10) unsigned DEFAULT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kardex_purchase_id_foreign` (`purchase_id`),
  KEY `kardex_document_id_foreign` (`document_id`),
  KEY `kardex_item_id_foreign` (`item_id`),
  KEY `kardex_sale_note_id_foreign` (`sale_note_id`),
  CONSTRAINT `kardex_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kardex_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `kardex_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kardex_sale_note_id_foreign` FOREIGN KEY (`sale_note_id`) REFERENCES `sale_notes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=333 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `migrations` VALUES (1,'2018_00_00_000000_tenant_catalogs_table',1),(2,'2018_01_00_000000_tenant_system_table',1),(3,'2018_01_01_000000_tenant_users_table',1),(4,'2018_01_01_000001_tenant_password_resets_table',1),(5,'2018_01_01_000004_tenant_modules_table',1),(6,'2018_01_01_000006_tenant_module_user_table',1),(7,'2018_01_01_000013_tenant_location_table',1),(8,'2018_05_16_000800_tenant_companies_table',1),(9,'2018_05_16_000810_tenant_establishments_table',1),(10,'2018_05_16_000900_tenant_configurations_table',1),(11,'2018_05_17_000002_tenant_series_table',1),(12,'2018_05_17_000101_tenant_persons_table',1),(13,'2018_06_17_000001_tenant_items_table',1),(14,'2018_06_17_000002_tenant_documents_table',1),(15,'2018_06_17_000005_tenant_document_items_table',1),(16,'2018_06_19_000020_tenant_invoices_table',1),(17,'2018_06_19_000021_tenant_notes_table',1),(18,'2018_06_21_000002_tenant_summaries_table',1),(19,'2018_06_21_000003_tenant_summary_documents_table',1),(20,'2018_06_21_000004_tenant_voided_table',1),(21,'2018_06_21_000005_tenant_voided_documents_table',1),(22,'2018_06_22_000022_tenant_retentions_table',1),(23,'2018_06_22_000023_tenant_retention_documents_table',1),(24,'2018_06_22_000024_tenant_perceptions_table',1),(25,'2018_06_22_000026_tenant_perception_details_table',1),(26,'2018_07_22_000024_tenant_dispatches_table',1),(27,'2018_07_22_000030_tenant_dispatch_items_table',1),(28,'2019_02_12_000002_tenant_purchases_table',1),(29,'2019_02_12_000005_tenant_purchase_items_table',1),(30,'2019_02_12_000007_tenant_kardex_table',1),(31,'2019_02_13_150334_tenant_add_cront_to_configurations',1),(32,'2019_02_13_175903_tenant_change_type_column_quantity_to_document_items',1),(33,'2019_02_13_190940_tenant_add_information_to_documents',1),(34,'2019_02_14_100645_tenant_add_establishment_id_to_users',1),(35,'2019_02_19_150123_tenant_change_columns_to_exchange_rates',1),(36,'2019_02_25_074400_tenant_add_data_json_to_documents',1),(37,'2019_02_26_084922_tenant_change_columns_offline_to_documents',1),(38,'2019_02_27_093803_tenant_add_send_online_to_documents',1),(39,'2019_02_27_150015_create_tasks_table',1),(40,'2019_02_28_100503_tenant_add_calculate_quantity_to_items',1),(41,'2019_02_28_154355_tenant_delete_unique_class_to_tasks',1),(42,'2019_02_28_215128_tenant_change_decimal_column_quantity_to_document_items',1),(43,'2019_03_01_100028_tenant_change_decimal_column_stock_to_items',1),(44,'2019_03_01_100550_tenant_change_type_column_quantity_to_purchase_items',1),(45,'2019_03_01_100938_tenant_change_type_column_quantity_to_kardex',1),(46,'2019_03_01_163938_tenant_add_locked_to_users',1),(47,'2019_03_16_095539_tenant_quotations_table',1),(48,'2019_03_16_095620_tenant_quotation_items_table',1),(49,'2019_03_19_155345_tenant_sale_notes_table',1),(50,'2019_03_19_155546_tenant_sale_note_items_table',1),(51,'2019_03_20_152101_tenant_add_sale_note_id_to_kardex',1),(52,'2019_03_22_095723_tenant_change_nullable_colum_type_to_kardex',1),(53,'2019_03_23_114011_tenant_warehouses_table',1),(54,'2019_03_23_134515_add_warehouse_id_to_items',1),(55,'2019_03_23_154011_tenant_item_warehouse_table',1),(56,'2019_03_25_120709_tenant_inventories_table',1),(57,'2019_03_26_120709_tenant_inventory_kardex_table',1),(58,'2019_03_27_104823_tenant_add_record_to_warehouses',1),(59,'2019_03_28_102106_tenant_add_quotation_id_to_documents',1),(60,'2019_03_28_112106_tenant_add_foreign_establishment_id_to_warehouses',1),(61,'2019_03_29_100403_tenant_add_foreign_to_item_warehouse',1),(62,'2019_03_29_100413_tenant_add_foreign_to_inventories',1),(63,'2019_03_29_100433_tenant_add_foreign_to_inventory_kardex',1),(64,'2019_03_29_100503_tenant_add_has_igv_to_items',1),(65,'2019_04_24_151702_tenant_add_stock_to_configurations',1),(66,'2019_04_26_105302_tenant_add_contingency_to_series',1),(67,'2019_04_29_111659_tenant_inventory_configurations_table',1),(68,'2019_04_29_164935_tenant_add_record_to_inventory_configurations',1),(69,'2019_04_30_140509_tenant_add_type_to_users',1),(70,'2019_05_06_174801_tenant_change_column_type_to_users',1),(71,'2019_05_07_160954_tenant_item_unit_types_table',1),(72,'2019_05_10_172128_tenant_add_price_default_to_item_unit_types',1),(73,'2019_05_13_145524_tenant_fix_error_to_inventories',1),(74,'2019_05_14_091046_tenant_description_to_item_unit_types',1),(75,'2019_05_27_185903_tenant_change_type_column_qr_to_documents',1),(76,'2019_05_28_172128_tenant_add_percentage_of_profit_to_items',1),(77,'2019_06_12_000005_tenant_document_payments_table',1),(78,'2019_06_12_000015_tenant_sale_note_payments_table',1),(79,'2019_06_12_172128_tenant_add_total_canceled_to_sale_notes',1),(80,'2019_06_13_100503_tenant_change_unit_price_to_items',1),(81,'2019_06_14_100503_tenant_person_address_table',1),(82,'2019_06_24_122116_tenant_add_changed_to_sale_notes',1),(83,'2019_07_09_141248_tenant_expense_types_table',1),(84,'2019_07_09_141408_tenant_expenses_table',1),(85,'2019_07_09_141508_tenant_expense_items_table',1),(86,'2019_07_09_172826_tenant_add_perception_agent_to_persons',1),(87,'2019_07_10_092347_tenant_add_percentage_perception_to_items',1),(88,'2019_07_10_103811_tenant_add_columns_perceptions_to_purchases',1),(89,'2019_07_10_120610_tenant_add_columns_purchases_to_payment_method_types',1),(90,'2019_07_10_123325_tenant_purchase_payments_table',1),(91,'2019_07_10_140636_tenant_add_date_of_due_to_purchases',1),(92,'2019_07_10_151332_tenant_add_warehouse_id_to_purchase_items',1),(93,'2019_07_12_181618_tenant_add_columns_aditional_to_establishments',1),(94,'2019_07_19_163617_tenant_add_columns_system_to_configurations',1),(95,'2019_07_22_094601_tenant_cash_table',1),(96,'2019_07_22_094658_tenant_cash_documents_table',1),(97,'2019_07_22_094725_tenant_add_image_to_items',1),(98,'2019_07_22_094803_tenant_modify_document_id_to_cash_documents',1),(99,'2019_07_22_102243_tenant_accounts_table',1),(100,'2019_07_22_103459_tenant_add_account_id_to_items',1),(101,'2019_07_23_175808_tenant_modify_decimals_to_item_unit_types',1),(102,'2019_07_24_162847_tenant_add_data_module_for_pos',1),(103,'2019_07_25_144505_tenant_add_ose_to_companies',1),(104,'2019_07_27_181623_tenant_add_name_name2_to_items',1),(105,'2019_07_31_165537_tenant_add_subtotal_account_to_configurations',1),(106,'2019_08_01_002801_tenant_add_status_to_item',1),(107,'2019_08_01_005553_tenant_add_status_to_persons',1),(108,'2019_08_01_011908_tenant_add_description_to_quotations',1),(109,'2019_08_01_095140_tenant_add_status_to_bank_accounts',1),(110,'2019_08_01_101234_tenant_add_active_to_banks',1),(111,'2019_08_01_102419_tenant_add_active_to_card_brands',1),(112,'2019_08_01_105836_tenant_delete_subtotal_account_to_configurations',1),(113,'2019_08_01_110045_tenant_company_accounts_table',1),(114,'2019_08_03_162431_tenant_add_data_modules_for_dashboard',1),(115,'2019_08_05_130830_tenant_add_index_external_id_to_documents',1),(116,'2019_08_12_125016_tenant_up_unit_price_to_items',1),(117,'2019_08_13_082230_tenant_add_column_limit_user_to_configurations',1),(118,'2019_08_16_153648_tenant_add_more_decimal_column_stock_to_items',1),(119,'2019_08_16_161756_tenant_add_total_plastic_bag_taxes_to_documents',1),(120,'2019_08_16_161824_tenant_add_total_plastic_bag_taxes_to_document_items',1),(121,'2019_08_16_161854_tenant_add_amount_plastic_bag_taxes_to_items',1),(122,'2019_08_19_112540_tenant_add_quotation_id_to_sale_notes',1),(123,'2019_08_19_115344_tenant_add_sale_note_id_to_documents',1),(124,'2019_08_19_124610_tenant_add_state_condition_to_persons',1),(125,'2019_08_20_121326_tenant_add_indexes_to_documents',1),(126,'2019_08_20_144511_tenant_add_indexes_to_summaries',1),(127,'2019_08_20_151037_tenant_add_indexes_to_persons',1),(128,'2019_08_21_145954_tenant_modify_name_name2_to_items',1),(129,'2019_08_23_115358_tenant_add_data_accounnting_inventory_to_modules',1),(130,'2019_08_23_160411_tenant_modify_description_to_items',1),(131,'2019_09_03_153427_tenant_change_nullable_column_affected_document_id_to_notes',1),(132,'2019_09_03_153656_tenant_add_data_affected_document_to_notes',1),(133,'2019_09_09_153206_tenant_add_sale_note_id_to_cash_documents',1),(134,'2019_09_09_174848_tenant_modify_columns_to_perceptions',1),(135,'2019_09_09_174916_tenant_modify_columns_to_perception_details',1),(136,'2019_09_10_102854_tenant_modify_series_id_to_perceptions',1),(137,'2019_09_11_131559_tenant_add_apply_store_to_items',1),(138,'2019_09_11_154949_tenant_expense_method_types',1),(139,'2019_09_11_155535_tenant_expense_payments',1),(140,'2019_09_11_174858_tenant_expense_reasons',1),(141,'2019_09_11_174929_tenant_add_expense_reason_id_to_expenses',1),(142,'2019_09_13_112026_tenant_add_column_image_medium_and_image_small_to_items',1),(143,'2019_09_15_233528_tenant_create_table_tag',1),(144,'2019_09_15_233537_tenant_create_table_item_tag',1),(145,'2019_09_16_121938_tenant_add_date_of_due_to_items',1),(146,'2019_09_16_133219_tenant_add_data_ecommerce_to_modules',1),(147,'2019_09_16_160453_tenant_add_timestamps_to_tag',1),(148,'2019_09_16_161726_tenant_add_status_to_tags',1),(149,'2019_09_17_131050_tenant_add_type_client_to_users',1),(150,'2019_09_17_202003_tenant_promotions_table',1),(151,'2019_09_18_152416_tenant_add_percentage_perception_to_persons',1),(152,'2019_09_18_160838_tenant_add_has_perception_to_items',1),(153,'2019_09_30_151349_tenant_add_has_prepayment_to_documents',1),(154,'2019_09_30_160541_tenant_inventory_transactions_table',1),(155,'2019_09_30_160919_tenant_change_columns_to_inventories',1),(156,'2019_10_04_092658_tenant_business_turns_table',1),(157,'2019_10_04_094841_tenant_document_hotels_table',1),(158,'2019_10_09_101229_tenant_add_locked_tenant_to_configurations',1),(159,'2019_10_10_155554_tenant_add_quantity_documents_date_time_start_to_configurations',1),(160,'2019_10_11_095050_tenant_billing_cycles_table',1),(161,'2019_10_11_153948_tenant_add_locked_users_to_configurations',1),(162,'2019_10_14_101501_tenant_add_column_set_to_items',1),(163,'2019_10_14_102317_tenant_item_sets_table',1),(164,'2019_10_14_235308_tenant_orders_table',1),(165,'2019_10_16_001052_tenant_add_identity_and_number_to_users',1),(166,'2019_10_17_100307_tenant_add_data_to_document_types',1),(167,'2019_10_18_150004_tenant_categories_table',1),(168,'2019_10_18_150414_tenant_brands_table',1),(169,'2019_10_18_150604_tenant_brand_category_to_items',1),(170,'2019_10_18_194622_tenant_add_soft_delete_to_purchases',1),(171,'2019_10_20_190039_tenant_add_plan_to_configurations',1),(172,'2019_10_20_195730_tenant_add_cuenta_to_modules',1),(173,'2019_10_20_200958_tenant_account_payments_table',1),(174,'2019_10_22_173407_tenant_add_was_deducted_prepayment_to_documents',1),(175,'2019_10_24_103947_tenant_add_sunat_alternate_server_to_configurations',1),(176,'2019_10_24_183250_tenant_add_document_id_to_orders',1),(177,'2019_10_24_210806_tenant_items_rating_table',1),(178,'2019_10_26_213130_tenant_add_logo_store_to_companies',1),(179,'2019_10_28_202116_tenant_add_reference_number_to_cash',1),(180,'2019_11_05_113236_create_padrones_table',1),(181,'2019_11_05_113320_create_charge_padrones_table',1),(182,'2019_11_06_095251_tenant_add_success_shipping_status_to_documents',1),(183,'2019_11_06_102422_tenant_add_success_sunat_shipping_status_to_documents',1),(184,'2019_11_06_110606_tenant_add_success_query_status_to_documents',1),(185,'2019_11_06_113035_tenant_offline_configurations_table',1),(186,'2019_11_11_102124_tenant_series_configurations_table',1),(187,'2019_11_12_223340_tenant_add_reference_document_id_to_dispatches',1),(188,'2019_11_13_124821_tenant_add_document_type_id_to_series_configurations',1),(189,'2019_11_18_154307_tenant_create_congiguration_ecommerce_table',1),(190,'2019_11_19_113132_tenant_cat_detraction_types_table',1),(191,'2019_11_20_175549_tenant_add_inventory_kardex_id_to_sale_note_items',1),(192,'2019_11_20_221547_tenant_add_address_to_configuration_ecommerce',1),(193,'2019_11_25_213648_tenant_add_social_to_configuration_ecommerce',1),(194,'2019_11_29_093342_tenant_add_detraction_account_to_companies',1),(195,'2019_12_02_105910_tenant_add_purchase_expense_to_cash_documents',1),(196,'2019_12_02_111743_tenant_change_expense_reason_id_to_expenses',1),(197,'2019_12_02_111837_tenant_add_soap_type_id_to_expenses',1),(198,'2019_12_02_152128_tenant_add_date_of_due_to_quotations',1),(199,'2019_12_02_161856_tenant_add_data_to_payment_method_types',1),(200,'2019_12_02_163726_tenant_add_payment_method_type_id_to_quotations',1),(201,'2019_12_05_104120_tenant_fixed_columns_to_cash_documents',1),(202,'2019_12_06_114132_tenant_add_shipping_address_to_quotations',1),(203,'2019_12_06_120917_tenant_add_commission_amount_to_items',1),(204,'2019_12_11_111224_tenant_purchase_quotations_table',1),(205,'2019_12_11_112209_tenant_purchase_quotation_items_table',1),(206,'2019_12_11_122830_tenant_add_reference_quotation_id_to_dispatches',1),(207,'2019_12_11_174726_tenant_purchase_orders_table',1),(208,'2019_12_11_175353_tenant_purchase_order_items_table',1),(209,'2019_12_16_103759_tenant_add_decimal_quantity_to_configurations',1),(210,'2019_12_16_181022_tenant_add_prefix_to_purchase_orders',1),(211,'2019_12_17_101130_tenant_add_purchase_order_id_to_purchases',1),(212,'2019_12_19_102946_tenant_item_lots_table',1),(213,'2019_12_19_105644_tenant_add_lot_code_to_items',1),(214,'2019_12_19_141604_tenant_add_operation_amazonia_to_companies',1),(215,'2019_12_20_123931_tenant_module_levels_table',1),(216,'2019_12_20_123945_tenant_module_level_user_table',1),(217,'2019_12_23_144236_tenant_add_apply_concurrency_to_sale_notes',1),(218,'2019_12_23_171335_tenant_add_columns_periods_to_sale_notes',1),(219,'2019_12_24_114350_tenant_add_columns_lots_to_item_lots',1),(220,'2019_12_24_123601_tenant_add_lot_code_to_purchase_items',1),(221,'2019_12_27_111848_tenant_add_lot_code_to_inventories',1),(222,'2019_12_30_095201_tenant_add_lots_enabled_to_items',1),(223,'2020_01_03_111747_tenant_add_amount_plastic_bag_taxes_to_configurations',1),(224,'2020_01_09_094728_tenant_change_decimal_column_quantity_to_dispatch_items',2),(225,'2020_01_10_095143_tenant_add_cci_to_bank_accounts',2),(226,'2020_01_15_095621_tenant_add_change_decimal_exchange_rate_sale_tables',2),(227,'2020_01_15_100032_tenant_add_data_to_cat_document_types',3),(228,'2020_01_15_144606_tenant_add_series_number_to_sale_notes',3),(229,'2020_01_15_172447_tenant_add_paid_to_sale_notes',3),(230,'2020_01_15_181229_tenant_add_plate_to_sale_notes',3),(231,'2020_01_16_101424_tenant_add_detail_to_inventories',3),(232,'2020_01_16_121313_tenant_add_state_to_item_lots',3),(233,'2020_01_17_095233_tenant_document_transports_table',4),(234,'2020_01_17_115328_tenant_add_plate_number_to_documents',4),(235,'2020_01_17_175217_tenant_add_state_type_to_expenses',4),(236,'2020_01_16_103741_tenant_change_decimals_unit_price_tables',5),(237,'2019_10_15_122633_tenant_person_types_table',6),(238,'2019_10_15_123201_tenant_add_person_types_to_persons',6),(239,'2020_01_08_102051_tenant_add_total_canceled_to_documents',6),(240,'2020_01_09_102017_tenant_change_type_decimal_column_quantity_to_dispatch_items',6),(241,'2020_01_09_153023_tenant_add_compact_sidebar_to_configurations',6),(242,'2020_01_10_121518_tenant_add_warehouse_id_to_document_items',6),(243,'2020_01_21_153921_tenant_inventories_transfer_table',6),(244,'2020_01_21_155245_tenant_add_inventories_transfer_id_to_inventories',6),(245,'2020_01_23_120700_tenant_drop_compact_sidebar_to_configurations',6),(246,'2020_01_23_120830_tenant_re_create_compact_sidebar_to_configurations',6),(247,'2020_01_23_123235_tenant_add_columns_to_document_hotels',6),(248,'2020_01_27_150915_tenant_change_column_number_to_sale_notes',7),(249,'2020_01_28_135422_tenant_add_tag_to_configuration_ecommerce',8),(250,'2020_01_27_121553_tenant_add_commission_type_to_items',9),(251,'2020_01_31_122444_tenant_add_config_system_to_configurations',10),(252,'2020_02_05_124542_tenant_add_guests_to_document_hotels',11),(253,'2020_02_07_164026_tenant_create_person_addresses',11),(254,'2020_02_10_111943_tenant_add_affectation_type_prepayment_to_documents',11),(255,'2020_02_11_210535_tenant_add_active_to_items',12),(256,'2020_02_12_203736_tenant_add_contact_to_users',13),(257,'2020_02_17_141658_tenant_create_format_templates_table',14),(258,'2020_02_17_194731_tenant_add_formats_to_configurations',15),(259,'2020_02_17_202910_tenant_item_images_table',16),(260,'2020_02_20_224501_tenant_add_colums_grid_items_to_configurations',17),(261,'2020_02_21_172411_tenant_add_soap_shipping_response_to_documents',18),(262,'2020_02_25_103837_tenant_add_options_pos_to_configurations',19),(263,'2020_02_25_154338_tenant_add_change_to_document_payments',20),(264,'2020_02_27_113111_tenant_add_change_to_sale_note_payments',20),(265,'2020_03_03_172821_tenant_add_additional_information_to_document_items',21),(266,'2020_03_16_162652_tenant_add_enabled_to_persons',22),(267,'2020_03_11_151338_tenant_add_edit_name_product_purchase',23),(268,'2020_03_13_110238_add_column_name_product_pdf',23),(269,'2020_03_13_134951_add_column_name_product_pdf_update',23),(270,'2020_03_13_134955_add_column_name_product_pdf_update',24),(271,'2020_03_13_134955_add_column_name_product_pdf_change',25),(272,'2020_03_20_030559_add_columna_restrict_receipt_date',26),(273,'2020_03_06_101730_tenant_global_payments_table',27),(274,'2020_03_10_165850_tenant_add_data_module_for_finance',27),(275,'2020_03_16_152939_tenant_add_indexes_to_payments_tables_for_finances',27),(276,'2020_02_24_213558_tenant_date_of_due_column_to_purchase_items',28),(277,'2020_02_26_201604_tenant_item_lots_group_table',28),(278,'2020_02_26_203030_tenant_add_series_enabled_to_items',28),(279,'2020_02_17_115050_tenant_add_delivery_date_to_quotations',29),(280,'2020_02_19_102018_tenant_order_notes_table',29),(281,'2020_02_19_121619_tenant_order_note_items_table',29),(282,'2020_02_19_150814_tenant_add_order_note_id_to_documents',29),(283,'2020_02_19_150828_tenant_add_order_note_id_to_sale_notes',29),(284,'2020_02_19_160926_tenant_add_reference_order_note_id_to_dispatches',29),(285,'2020_03_20_174637_tenant_add_affectation_igv_type_id_to_configurations',29),(286,'2020_03_27_141008_tenant_add_column_visual_to_configurations',30),(287,'2020_03_25_173128_tenant_sale_opportunities_table',31),(288,'2020_03_25_173442_tenant_sale_opportunity_items_table',31),(289,'2020_03_26_121642_tenant_add_sale_opportunity_id_to_quotations',31),(290,'2020_03_26_170415_tenant_sale_opportunity_files_table',31),(291,'2020_03_27_111133_tenant_quotation_payments_table',31),(292,'2020_03_27_123343_tenant_add_changed_to_quotations',31),(293,'2020_03_27_143825_tenant_add_account_number_to_quotations',31),(294,'2020_03_27_150024_tenant_add_terms_condition_to_quotations',31),(295,'2020_03_30_112859_tenant_payment_files_table',32),(297,'2020_03_31_141008_tenant_add_column_show_ws_to_configurations',33),(298,'2020_03_31_111522_tenant_add_customer_id_to_purchases',34),(299,'2020_03_31_122445_tenant_fixed_asset_items_table',34),(300,'2020_03_31_151057_tenant_fixed_asset_purchases_table',34),(301,'2020_03_31_151323_tenant_fixed_asset_purchase_items_table',34),(302,'2020_04_01_150413_tenant_add_terms_condition_to_configurations',35),(303,'2020_04_02_124023_tenant_add_sale_opportunity_id_to_purchase_orders',35),(304,'2020_04_02_151534_tenant_add_total_canceled_to_purchases',35),(305,'2020_04_03_143703_tenant_insert_internal_to_soap_types',35),(306,'2020_04_02_154134_tenant_contracts_table',36),(307,'2020_04_02_154147_tenant_contract_items_table',36),(308,'2020_04_02_154244_tenant_contract_payments_table',36),(309,'2020_04_03_111019_tenant_income_types_table',36),(310,'2020_04_03_111139_tenant_income_reasons_table',36),(311,'2020_04_03_111209_tenant_income_table',36),(312,'2020_04_03_111703_tenant_income_items_table',36),(313,'2020_04_03_124629_tenant_income_payments_table',36),(314,'2020_05_07_123152_tenant_technical_services_table',37),(315,'2020_05_07_164323_tenant_user_commissions_table',37),(316,'2020_05_12_131218_add_product_to_configurations',38),(317,'2020_05_06_205001_create_status_orders_table',39),(318,'2020_05_06_210451_tenant_add_status_orders_to_orders',39),(319,'2020_05_12_204311_tenant_add_cotizaction_finance_configurations',39),(320,'2020_05_19_162014_tenant_add_contact_to_persons',40),(321,'2020_05_25_140825_tenant_add_column_plate_number_to_sales_note',41),(322,'2020_05_30_132013_tenant_add_certificate_due_to_companies',42),(323,'2020_06_05_111820_tenant_add_line_to_items',43),(324,'2020_06_10_102549_tenant_add_columns_technical_specifications_items',44),(325,'2020_06_11_093739_tenant_add_columns_header_image_legend_footer_to_configurations',44),(326,'2020_06_11_152155_tenant_add_purchase_orders',44),(327,'2020_06_23_122822_tenant_drivers_table',45),(328,'2020_06_23_122938_tenant_dispatchers_table',45),(329,'2020_06_23_123012_tenant_order_forms_table',45),(330,'2020_06_23_123126_tenant_order_form_items_table',45),(331,'2020_06_24_173951_tenant_add_reference_order_form_id_to_dispatches',45),(332,'2020_07_03_124017_tenant_add_purchase_has_igv_to_items',45);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_level_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_level_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `module_level_user` VALUES (1,1,1),(2,2,1),(3,3,1),(4,4,1),(5,5,1),(6,6,1),(7,7,1),(8,8,1),(9,9,1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_levels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `module_levels_module_id_foreign` (`module_id`),
  CONSTRAINT `module_levels_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `module_levels` VALUES (1,'new_document','Nuevo comprobante',1,NULL,NULL),(2,'list_document','L. Comprobantes',1,NULL,NULL),(3,'document_not_sent','Doc. No enviados',1,NULL,NULL),(4,'document_contingengy','Doc. Contingencia',1,NULL,NULL),(5,'catalogs','Catálogos',1,NULL,NULL),(6,'summary_voided','Resúmenes y Anulaciones',1,NULL,NULL),(7,'quotations','Cotizaciones',1,NULL,NULL),(8,'sale_notes','Notas de Venta',1,NULL,NULL),(9,'incentives','Incentivos',1,NULL,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `module_user` VALUES (1,2,1),(2,5,1),(3,9,1),(4,11,1),(5,7,1),(6,3,1),(7,10,1),(8,8,1),(9,6,1),(10,4,1),(11,1,1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `modules` VALUES (1,'documents','Ventas',NULL,NULL),(2,'purchases','Compras',NULL,NULL),(3,'advanced','Documentos Avanzados',NULL,NULL),(4,'reports','Reportes',NULL,NULL),(5,'configuration','Configuration',NULL,NULL),(6,'pos','Punto de venta (POS)',NULL,NULL),(7,'dashboard','Dashboard',NULL,NULL),(8,'inventory','Inventario',NULL,NULL),(9,'accounting','Contabilidad',NULL,NULL),(10,'ecommerce','Ecommerce',NULL,NULL),(11,'cuenta','Cuenta',NULL,NULL),(12,'finance','Finanzas',NULL,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` int(10) unsigned NOT NULL,
  `note_type` enum('credit','debit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `note_credit_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note_debit_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `affected_document_id` int(10) unsigned DEFAULT NULL,
  `data_affected_document` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notes_document_id_foreign` (`document_id`),
  KEY `notes_note_credit_type_id_foreign` (`note_credit_type_id`),
  KEY `notes_note_debit_type_id_foreign` (`note_debit_type_id`),
  KEY `notes_affected_document_id_foreign` (`affected_document_id`),
  CONSTRAINT `notes_affected_document_id_foreign` FOREIGN KEY (`affected_document_id`) REFERENCES `documents` (`id`),
  CONSTRAINT `notes_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notes_note_credit_type_id_foreign` FOREIGN KEY (`note_credit_type_id`) REFERENCES `cat_note_credit_types` (`id`),
  CONSTRAINT `notes_note_debit_type_id_foreign` FOREIGN KEY (`note_debit_type_id`) REFERENCES `cat_note_debit_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offline_configurations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_client` tinyint(1) NOT NULL DEFAULT '0',
  `token_server` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_server` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `offline_configurations` VALUES (1,0,NULL,NULL,NULL,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_form_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_form_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_form_items_order_form_id_foreign` (`order_form_id`),
  KEY `order_form_items_item_id_foreign` (`item_id`),
  CONSTRAINT `order_form_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `order_form_items_order_form_id_foreign` FOREIGN KEY (`order_form_id`) REFERENCES `order_forms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `establishment` json NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `customer` json NOT NULL,
  `observations` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `transport_mode_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transfer_reason_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transfer_reason_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_shipping` date NOT NULL,
  `transshipment_indicator` tinyint(1) NOT NULL,
  `port_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_weight` decimal(10,2) NOT NULL,
  `packages_number` int(11) NOT NULL,
  `container_number` int(11) DEFAULT NULL,
  `origin` json NOT NULL,
  `delivery` json NOT NULL,
  `dispatcher_id` int(10) unsigned NOT NULL,
  `driver_id` int(10) unsigned NOT NULL,
  `license_plates` json DEFAULT NULL,
  `legends` json DEFAULT NULL,
  `optional` json DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_forms_dispatcher_id_foreign` (`dispatcher_id`),
  KEY `order_forms_driver_id_foreign` (`driver_id`),
  KEY `order_forms_user_id_foreign` (`user_id`),
  KEY `order_forms_establishment_id_foreign` (`establishment_id`),
  KEY `order_forms_soap_type_id_foreign` (`soap_type_id`),
  KEY `order_forms_state_type_id_foreign` (`state_type_id`),
  KEY `order_forms_customer_id_foreign` (`customer_id`),
  KEY `order_forms_unit_type_id_foreign` (`unit_type_id`),
  KEY `order_forms_transport_mode_type_id_foreign` (`transport_mode_type_id`),
  KEY `order_forms_transfer_reason_type_id_foreign` (`transfer_reason_type_id`),
  KEY `order_forms_date_of_issue_index` (`date_of_issue`),
  CONSTRAINT `order_forms_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `order_forms_dispatcher_id_foreign` FOREIGN KEY (`dispatcher_id`) REFERENCES `dispatchers` (`id`),
  CONSTRAINT `order_forms_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  CONSTRAINT `order_forms_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `order_forms_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `order_forms_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `order_forms_transfer_reason_type_id_foreign` FOREIGN KEY (`transfer_reason_type_id`) REFERENCES `cat_transfer_reason_types` (`id`),
  CONSTRAINT `order_forms_transport_mode_type_id_foreign` FOREIGN KEY (`transport_mode_type_id`) REFERENCES `cat_transport_mode_types` (`id`),
  CONSTRAINT `order_forms_unit_type_id_foreign` FOREIGN KEY (`unit_type_id`) REFERENCES `cat_unit_types` (`id`),
  CONSTRAINT `order_forms_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_note_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_note_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_value` decimal(16,6) NOT NULL,
  `affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_base_igv` decimal(12,2) NOT NULL,
  `percentage_igv` decimal(12,2) NOT NULL,
  `total_igv` decimal(12,2) NOT NULL,
  `system_isc_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL,
  `price_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(16,6) NOT NULL,
  `total_value` decimal(12,2) NOT NULL,
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `attributes` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `charges` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_note_items_order_note_id_foreign` (`order_note_id`),
  KEY `order_note_items_item_id_foreign` (`item_id`),
  KEY `order_note_items_affectation_igv_type_id_foreign` (`affectation_igv_type_id`),
  KEY `order_note_items_system_isc_type_id_foreign` (`system_isc_type_id`),
  KEY `order_note_items_price_type_id_foreign` (`price_type_id`),
  CONSTRAINT `order_note_items_affectation_igv_type_id_foreign` FOREIGN KEY (`affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `order_note_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `order_note_items_order_note_id_foreign` FOREIGN KEY (`order_note_id`) REFERENCES `order_notes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_note_items_price_type_id_foreign` FOREIGN KEY (`price_type_id`) REFERENCES `cat_price_types` (`id`),
  CONSTRAINT `order_note_items_system_isc_type_id_foreign` FOREIGN KEY (`system_isc_type_id`) REFERENCES `cat_system_isc_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `establishment` json NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` char(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `date_of_due` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `customer` json NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method_type_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exchange_rate_sale` decimal(13,3) NOT NULL,
  `total_prepayment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exportation` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_free` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxed` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_unaffected` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exonerated` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_igv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `charges` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `prepayments` json DEFAULT NULL,
  `guides` json DEFAULT NULL,
  `related` json DEFAULT NULL,
  `perception` json DEFAULT NULL,
  `detraction` json DEFAULT NULL,
  `legends` json DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_notes_user_id_foreign` (`user_id`),
  KEY `order_notes_establishment_id_foreign` (`establishment_id`),
  KEY `order_notes_customer_id_foreign` (`customer_id`),
  KEY `order_notes_soap_type_id_foreign` (`soap_type_id`),
  KEY `order_notes_state_type_id_foreign` (`state_type_id`),
  KEY `order_notes_currency_type_id_foreign` (`currency_type_id`),
  KEY `order_notes_payment_method_type_id_foreign` (`payment_method_type_id`),
  CONSTRAINT `order_notes_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `order_notes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `order_notes_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `order_notes_payment_method_type_id_foreign` FOREIGN KEY (`payment_method_type_id`) REFERENCES `payment_method_types` (`id`),
  CONSTRAINT `order_notes_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `order_notes_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `order_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer` json NOT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `items` json NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `reference_payment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_external_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_order_id` tinyint(3) unsigned DEFAULT NULL,
  `purchase` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_status_order_id_foreign` (`status_order_id`),
  CONSTRAINT `orders_status_order_id_foreign` FOREIGN KEY (`status_order_id`) REFERENCES `status_orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `padrones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ruc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_razon_social` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_contribuyente` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condicion_domicilio` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubigeo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_via` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_via` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_zona` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_zona` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interior` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lote` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departamento` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manzana` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kilometro` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `padrones_ruc_index` (`ruc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_id` int(11) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_index` (`payment_id`,`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_method_types` (
  `id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_card` tinyint(1) NOT NULL DEFAULT '0',
  `charge` decimal(12,2) DEFAULT NULL,
  `number_days` int(11) DEFAULT NULL,
  KEY `payment_method_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `payment_method_types` VALUES ('01','Efectivo',0,NULL,NULL),('02','Tarjeta de crédito',1,NULL,NULL),('03','Tarjeta de débito',1,NULL,NULL),('04','Transferencia',0,NULL,NULL),('05','Factura a 30 días',0,NULL,30),('06','Tarjeta crédito visa',1,3.68,NULL),('07','Contado contraentrega',0,NULL,NULL),('08','A 30 días',0,NULL,30),('09','Crédito',1,NULL,NULL),('10','Contado',0,NULL,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perception_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `perception_id` int(10) unsigned NOT NULL,
  `document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_perception` date NOT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_document` decimal(10,2) NOT NULL,
  `total_perception` decimal(10,2) NOT NULL,
  `payments` json NOT NULL,
  `series` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` json NOT NULL,
  `total_to_pay` decimal(10,2) NOT NULL,
  `total_payment` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `perception_details_perception_id_foreign` (`perception_id`),
  KEY `perception_documents_document_type_id_foreign` (`document_type_id`),
  KEY `perception_documents_currency_type_id_foreign` (`currency_type_id`),
  CONSTRAINT `perception_details_perception_id_foreign` FOREIGN KEY (`perception_id`) REFERENCES `perceptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `perception_documents_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `perception_documents_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `cat_document_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perceptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `establishment` json NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubl_version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `series` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int(11) NOT NULL,
  `date_of_issue` date NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `customer` json NOT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `perception_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observations` text COLLATE utf8mb4_unicode_ci,
  `total_perception` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `optional` json DEFAULT NULL,
  `legends` json DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_of_issue` time NOT NULL,
  `hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_xml` tinyint(1) NOT NULL DEFAULT '0',
  `has_pdf` tinyint(1) NOT NULL DEFAULT '0',
  `has_cdr` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `perceptions_user_id_foreign` (`user_id`),
  KEY `perceptions_establishment_id_foreign` (`establishment_id`),
  KEY `perceptions_soap_type_id_foreign` (`soap_type_id`),
  KEY `perceptions_state_type_id_foreign` (`state_type_id`),
  KEY `perceptions_document_type_id_foreign` (`document_type_id`),
  KEY `perceptions_currency_type_id_foreign` (`currency_type_id`),
  KEY `perceptions_perception_type_id_foreign` (`perception_type_id`),
  KEY `perceptions_customer_id_foreign` (`customer_id`),
  KEY `perceptions_number_index` (`number`),
  KEY `perceptions_series_index` (`series`),
  CONSTRAINT `perceptions_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `perceptions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `perceptions_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `cat_document_types` (`id`),
  CONSTRAINT `perceptions_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `perceptions_perception_type_id_foreign` FOREIGN KEY (`perception_type_id`) REFERENCES `cat_perception_types` (`id`),
  CONSTRAINT `perceptions_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `perceptions_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `perceptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `department_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district_id` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `person_address_person_id_foreign` (`person_id`),
  KEY `person_address_department_id_foreign` (`department_id`),
  KEY `person_address_province_id_foreign` (`province_id`),
  KEY `person_address_district_id_foreign` (`district_id`),
  CONSTRAINT `person_address_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `person_address_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`),
  CONSTRAINT `person_address_person_id_foreign` FOREIGN KEY (`person_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `person_address_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `country_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district_id` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `person_addresses_person_id_foreign` (`person_id`),
  KEY `person_addresses_country_id_foreign` (`country_id`),
  KEY `person_addresses_department_id_foreign` (`department_id`),
  KEY `person_addresses_province_id_foreign` (`province_id`),
  KEY `person_addresses_district_id_foreign` (`district_id`),
  CONSTRAINT `person_addresses_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `person_addresses_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `person_addresses_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`),
  CONSTRAINT `person_addresses_person_id_foreign` FOREIGN KEY (`person_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `person_addresses_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `person_types` VALUES (1,'Interno','2020-01-27 16:40:41','2020-01-27 16:40:41'),(2,'Distribuidor','2020-01-27 16:40:41','2020-01-27 16:40:41');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('customers','suppliers') COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity_document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` char(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district_id` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perception_agent` tinyint(1) NOT NULL DEFAULT '0',
  `person_type_id` int(10) unsigned DEFAULT NULL,
  `contact` json DEFAULT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `percentage_perception` decimal(12,2) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `persons_identity_document_type_id_foreign` (`identity_document_type_id`),
  KEY `persons_country_id_foreign` (`country_id`),
  KEY `persons_department_id_foreign` (`department_id`),
  KEY `persons_province_id_foreign` (`province_id`),
  KEY `persons_district_id_foreign` (`district_id`),
  KEY `persons_name_index` (`name`),
  KEY `persons_number_index` (`number`),
  KEY `persons_type_index` (`type`),
  KEY `persons_person_type_id_foreign` (`person_type_id`),
  KEY `persons_enabled_index` (`enabled`),
  CONSTRAINT `persons_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `persons_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `persons_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`),
  CONSTRAINT `persons_identity_document_type_id_foreign` FOREIGN KEY (`identity_document_type_id`) REFERENCES `cat_identity_document_types` (`id`),
  CONSTRAINT `persons_person_type_id_foreign` FOREIGN KEY (`person_type_id`) REFERENCES `person_types` (`id`),
  CONSTRAINT `persons_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `promotions_item_id_foreign` (`item_id`),
  CONSTRAINT `promotions_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provinces` (
  `id` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  KEY `provinces_department_id_foreign` (`department_id`),
  KEY `provinces_id_index` (`id`),
  CONSTRAINT `provinces_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `provinces` VALUES ('0101','01','Chachapoyas',1),('0102','01','Bagua',1),('0103','01','Bongará',1),('0104','01','Condorcanqui',1),('0105','01','Luya',1),('0106','01','Rodríguez de Mendoza',1),('0107','01','Utcubamba',1),('0201','02','Huaraz',1),('0202','02','Aija',1),('0203','02','Antonio Raymondi',1),('0204','02','Asunción',1),('0205','02','Bolognesi',1),('0206','02','Carhuaz',1),('0207','02','Carlos Fermín Fitzcarrald',1),('0208','02','Casma',1),('0209','02','Corongo',1),('0210','02','Huari',1),('0211','02','Huarmey',1),('0212','02','Huaylas',1),('0213','02','Mariscal Luzuriaga',1),('0214','02','Ocros',1),('0215','02','Pallasca',1),('0216','02','Pomabamba',1),('0217','02','Recuay',1),('0218','02','Santa',1),('0219','02','Sihuas',1),('0220','02','Yungay',1),('0301','03','Abancay',1),('0302','03','Andahuaylas',1),('0303','03','Antabamba',1),('0304','03','Aymaraes',1),('0305','03','Cotabambas',1),('0306','03','Chincheros',1),('0307','03','Grau',1),('0401','04','Arequipa',1),('0402','04','Camaná',1),('0403','04','Caravelí',1),('0404','04','Castilla',1),('0405','04','Caylloma',1),('0406','04','Condesuyos',1),('0407','04','Islay',1),('0408','04','La Uniòn',1),('0501','05','Huamanga',1),('0502','05','Cangallo',1),('0503','05','Huanca Sancos',1),('0504','05','Huanta',1),('0505','05','La Mar',1),('0506','05','Lucanas',1),('0507','05','Parinacochas',1),('0508','05','Pàucar del Sara Sara',1),('0509','05','Sucre',1),('0510','05','Víctor Fajardo',1),('0511','05','Vilcas Huamán',1),('0601','06','Cajamarca',1),('0602','06','Cajabamba',1),('0603','06','Celendín',1),('0604','06','Chota',1),('0605','06','Contumazá',1),('0606','06','Cutervo',1),('0607','06','Hualgayoc',1),('0608','06','Jaén',1),('0609','06','San Ignacio',1),('0610','06','San Marcos',1),('0611','06','San Miguel',1),('0612','06','San Pablo',1),('0613','06','Santa Cruz',1),('0701','07','Prov. Const. del Callao',1),('0801','08','Cusco',1),('0802','08','Acomayo',1),('0803','08','Anta',1),('0804','08','Calca',1),('0805','08','Canas',1),('0806','08','Canchis',1),('0807','08','Chumbivilcas',1),('0808','08','Espinar',1),('0809','08','La Convención',1),('0810','08','Paruro',1),('0811','08','Paucartambo',1),('0812','08','Quispicanchi',1),('0813','08','Urubamba',1),('0901','09','Huancavelica',1),('0902','09','Acobamba',1),('0903','09','Angaraes',1),('0904','09','Castrovirreyna',1),('0905','09','Churcampa',1),('0906','09','Huaytará',1),('0907','09','Tayacaja',1),('1001','10','Huánuco',1),('1002','10','Ambo',1),('1003','10','Dos de Mayo',1),('1004','10','Huacaybamba',1),('1005','10','Huamalíes',1),('1006','10','Leoncio Prado',1),('1007','10','Marañón',1),('1008','10','Pachitea',1),('1009','10','Puerto Inca',1),('1010','10','Lauricocha',1),('1011','10','Yarowilca',1),('1101','11','Ica',1),('1102','11','Chincha',1),('1103','11','Nasca',1),('1104','11','Palpa',1),('1105','11','Pisco',1),('1201','12','Huancayo',1),('1202','12','Concepción',1),('1203','12','Chanchamayo',1),('1204','12','Jauja',1),('1205','12','Junín',1),('1206','12','Satipo',1),('1207','12','Tarma',1),('1208','12','Yauli',1),('1209','12','Chupaca',1),('1301','13','Trujillo',1),('1302','13','Ascope',1),('1303','13','Bolívar',1),('1304','13','Chepén',1),('1305','13','Julcán',1),('1306','13','Otuzco',1),('1307','13','Pacasmayo',1),('1308','13','Pataz',1),('1309','13','Sánchez Carrión',1),('1310','13','Santiago de Chuco',1),('1311','13','Gran Chimú',1),('1312','13','Virú',1),('1401','14','Chiclayo',1),('1402','14','Ferreñafe',1),('1403','14','Lambayeque',1),('1501','15','Lima',1),('1502','15','Barranca',1),('1503','15','Cajatambo',1),('1504','15','Canta',1),('1505','15','Cañete',1),('1506','15','Huaral',1),('1507','15','Huarochirí',1),('1508','15','Huaura',1),('1509','15','Oyón',1),('1510','15','Yauyos',1),('1601','16','Maynas',1),('1602','16','Alto Amazonas',1),('1603','16','Loreto',1),('1604','16','Mariscal Ramón Castilla',1),('1605','16','Requena',1),('1606','16','Ucayali',1),('1607','16','Datem del Marañón',1),('1608','16','Putumayo',1),('1701','17','Tambopata',1),('1702','17','Manu',1),('1703','17','Tahuamanu',1),('1801','18','Mariscal Nieto',1),('1802','18','General Sánchez Cerro',1),('1803','18','Ilo',1),('1901','19','Pasco',1),('1902','19','Daniel Alcides Carrión',1),('1903','19','Oxapampa',1),('2001','20','Piura',1),('2002','20','Ayabaca',1),('2003','20','Huancabamba',1),('2004','20','Morropón',1),('2005','20','Paita',1),('2006','20','Sullana',1),('2007','20','Talara',1),('2008','20','Sechura',1),('2101','21','Puno',1),('2102','21','Azángaro',1),('2103','21','Carabaya',1),('2104','21','Chucuito',1),('2105','21','El Collao',1),('2106','21','Huancané',1),('2107','21','Lampa',1),('2108','21','Melgar',1),('2109','21','Moho',1),('2110','21','San Antonio de Putina',1),('2111','21','San Román',1),('2112','21','Sandia',1),('2113','21','Yunguyo',1),('2201','22','Moyobamba',1),('2202','22','Bellavista',1),('2203','22','El Dorado',1),('2204','22','Huallaga',1),('2205','22','Lamas',1),('2206','22','Mariscal Cáceres',1),('2207','22','Picota',1),('2208','22','Rioja',1),('2209','22','San Martín',1),('2210','22','Tocache',1),('2301','23','Tacna',1),('2302','23','Candarave',1),('2303','23','Jorge Basadre',1),('2304','23','Tarata',1),('2401','24','Tumbes',1),('2402','24','Contralmirante Villar',1),('2403','24','Zarumilla',1),('2501','25','Coronel Portillo',1),('2502','25','Atalaya',1),('2503','25','Padre Abad',1),('2504','25','Purús',1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `lot_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_value` decimal(16,6) NOT NULL,
  `date_of_due` date DEFAULT NULL,
  `affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_base_igv` decimal(12,2) NOT NULL,
  `percentage_igv` decimal(12,2) NOT NULL,
  `total_igv` decimal(12,2) NOT NULL,
  `system_isc_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL,
  `price_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(16,6) NOT NULL,
  `total_value` decimal(12,2) NOT NULL,
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `attributes` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `charges` json DEFAULT NULL,
  `warehouse_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  KEY `purchase_items_item_id_foreign` (`item_id`),
  KEY `purchase_items_affectation_igv_type_id_foreign` (`affectation_igv_type_id`),
  KEY `purchase_items_system_isc_type_id_foreign` (`system_isc_type_id`),
  KEY `purchase_items_price_type_id_foreign` (`price_type_id`),
  KEY `purchase_items_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `purchase_items_affectation_igv_type_id_foreign` FOREIGN KEY (`affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `purchase_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `purchase_items_price_type_id_foreign` FOREIGN KEY (`price_type_id`) REFERENCES `cat_price_types` (`id`),
  CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_items_system_isc_type_id_foreign` FOREIGN KEY (`system_isc_type_id`) REFERENCES `cat_system_isc_types` (`id`),
  CONSTRAINT `purchase_items_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_value` decimal(16,6) NOT NULL,
  `affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_base_igv` decimal(12,2) NOT NULL,
  `percentage_igv` decimal(12,2) NOT NULL,
  `total_igv` decimal(12,2) NOT NULL,
  `system_isc_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL,
  `price_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(16,6) NOT NULL,
  `total_value` decimal(12,2) NOT NULL,
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `attributes` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `charges` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchase_order_items_item_id_foreign` (`item_id`),
  KEY `purchase_order_items_affectation_igv_type_id_foreign` (`affectation_igv_type_id`),
  KEY `purchase_order_items_system_isc_type_id_foreign` (`system_isc_type_id`),
  KEY `purchase_order_items_price_type_id_foreign` (`price_type_id`),
  CONSTRAINT `purchase_order_items_affectation_igv_type_id_foreign` FOREIGN KEY (`affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `purchase_order_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `purchase_order_items_price_type_id_foreign` FOREIGN KEY (`price_type_id`) REFERENCES `cat_price_types` (`id`),
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_order_items_system_isc_type_id_foreign` FOREIGN KEY (`system_isc_type_id`) REFERENCES `cat_system_isc_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OC',
  `date_of_issue` date NOT NULL,
  `date_of_due` date DEFAULT NULL,
  `time_of_issue` time NOT NULL,
  `supplier_id` int(10) unsigned NOT NULL,
  `supplier` json NOT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate_sale` decimal(13,3) NOT NULL,
  `total_prepayment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exportation` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_free` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxed` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_unaffected` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exonerated` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_igv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upload_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_quotation_id` int(10) unsigned DEFAULT NULL,
  `sale_opportunity_id` int(10) unsigned DEFAULT NULL,
  `payment_method_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_orders_purchase_quotation_id_foreign` (`purchase_quotation_id`),
  KEY `purchase_orders_user_id_foreign` (`user_id`),
  KEY `purchase_orders_establishment_id_foreign` (`establishment_id`),
  KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_orders_soap_type_id_foreign` (`soap_type_id`),
  KEY `purchase_orders_state_type_id_foreign` (`state_type_id`),
  KEY `purchase_orders_currency_type_id_foreign` (`currency_type_id`),
  KEY `purchase_orders_payment_method_type_id_foreign` (`payment_method_type_id`),
  KEY `purchase_orders_sale_opportunity_id_foreign` (`sale_opportunity_id`),
  CONSTRAINT `purchase_orders_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `purchase_orders_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `purchase_orders_payment_method_type_id_foreign` FOREIGN KEY (`payment_method_type_id`) REFERENCES `payment_method_types` (`id`),
  CONSTRAINT `purchase_orders_purchase_quotation_id_foreign` FOREIGN KEY (`purchase_quotation_id`) REFERENCES `purchase_quotations` (`id`),
  CONSTRAINT `purchase_orders_sale_opportunity_id_foreign` FOREIGN KEY (`sale_opportunity_id`) REFERENCES `sale_opportunities` (`id`),
  CONSTRAINT `purchase_orders_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `purchase_orders_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `purchase_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) unsigned NOT NULL,
  `date_of_payment` date NOT NULL,
  `payment_method_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_card` tinyint(1) NOT NULL DEFAULT '0',
  `card_brand_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_payments_purchase_id_foreign` (`purchase_id`),
  KEY `purchase_payments_card_brand_id_foreign` (`card_brand_id`),
  KEY `purchase_payments_payment_method_type_id_foreign` (`payment_method_type_id`),
  KEY `purchase_payments_date_of_payment_index` (`date_of_payment`),
  CONSTRAINT `purchase_payments_card_brand_id_foreign` FOREIGN KEY (`card_brand_id`) REFERENCES `card_brands` (`id`),
  CONSTRAINT `purchase_payments_payment_method_type_id_foreign` FOREIGN KEY (`payment_method_type_id`) REFERENCES `payment_method_types` (`id`),
  CONSTRAINT `purchase_payments_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_quotation_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_quotation_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_quotation_items_purchase_quotation_id_foreign` (`purchase_quotation_id`),
  KEY `purchase_quotation_items_item_id_foreign` (`item_id`),
  CONSTRAINT `purchase_quotation_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `purchase_quotation_items_purchase_quotation_id_foreign` FOREIGN KEY (`purchase_quotation_id`) REFERENCES `purchase_quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_quotations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `establishment` json NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `suppliers` json NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_quotations_user_id_foreign` (`user_id`),
  KEY `purchase_quotations_establishment_id_foreign` (`establishment_id`),
  KEY `purchase_quotations_soap_type_id_foreign` (`soap_type_id`),
  KEY `purchase_quotations_state_type_id_foreign` (`state_type_id`),
  CONSTRAINT `purchase_quotations_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `purchase_quotations_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `purchase_quotations_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `purchase_quotations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `series` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int(11) NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_due` date DEFAULT NULL,
  `time_of_issue` time NOT NULL,
  `supplier_id` int(10) unsigned NOT NULL,
  `supplier` json NOT NULL,
  `purchase_order_id` int(10) unsigned DEFAULT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate_sale` decimal(13,3) NOT NULL,
  `total_prepayment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exportation` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_free` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxed` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_unaffected` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exonerated` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_igv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `total_canceled` tinyint(1) NOT NULL DEFAULT '0',
  `customer_id` int(10) unsigned DEFAULT NULL,
  `perception_date` date DEFAULT NULL,
  `perception_number` int(11) DEFAULT NULL,
  `total_perception` decimal(12,2) DEFAULT NULL,
  `charges` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `prepayments` json DEFAULT NULL,
  `guides` json DEFAULT NULL,
  `related` json DEFAULT NULL,
  `perception` json DEFAULT NULL,
  `detraction` json DEFAULT NULL,
  `legends` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchases_user_id_foreign` (`user_id`),
  KEY `purchases_establishment_id_foreign` (`establishment_id`),
  KEY `purchases_supplier_id_foreign` (`supplier_id`),
  KEY `purchases_soap_type_id_foreign` (`soap_type_id`),
  KEY `purchases_state_type_id_foreign` (`state_type_id`),
  KEY `purchases_group_id_foreign` (`group_id`),
  KEY `purchases_document_type_id_foreign` (`document_type_id`),
  KEY `purchases_currency_type_id_foreign` (`currency_type_id`),
  KEY `purchases_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchases_customer_id_foreign` (`customer_id`),
  CONSTRAINT `purchases_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `purchases_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `purchases_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `cat_document_types` (`id`),
  CONSTRAINT `purchases_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `purchases_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `purchases_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`),
  CONSTRAINT `purchases_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `purchases_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotation_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_value` decimal(16,6) NOT NULL,
  `affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_base_igv` decimal(12,2) NOT NULL,
  `percentage_igv` decimal(12,2) NOT NULL,
  `total_igv` decimal(12,2) NOT NULL,
  `system_isc_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL,
  `price_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(16,6) NOT NULL,
  `total_value` decimal(12,2) NOT NULL,
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `attributes` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `charges` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotation_items_quotation_id_foreign` (`quotation_id`),
  KEY `quotation_items_item_id_foreign` (`item_id`),
  KEY `quotation_items_affectation_igv_type_id_foreign` (`affectation_igv_type_id`),
  KEY `quotation_items_system_isc_type_id_foreign` (`system_isc_type_id`),
  KEY `quotation_items_price_type_id_foreign` (`price_type_id`),
  CONSTRAINT `quotation_items_affectation_igv_type_id_foreign` FOREIGN KEY (`affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `quotation_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `quotation_items_price_type_id_foreign` FOREIGN KEY (`price_type_id`) REFERENCES `cat_price_types` (`id`),
  CONSTRAINT `quotation_items_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotation_items_system_isc_type_id_foreign` FOREIGN KEY (`system_isc_type_id`) REFERENCES `cat_system_isc_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotation_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` int(10) unsigned NOT NULL,
  `date_of_payment` date NOT NULL,
  `payment_method_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_card` tinyint(1) NOT NULL DEFAULT '0',
  `card_brand_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change` decimal(12,2) DEFAULT NULL,
  `payment` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `quotation_payments_quotation_id_foreign` (`quotation_id`),
  KEY `quotation_payments_card_brand_id_foreign` (`card_brand_id`),
  KEY `quotation_payments_payment_method_type_id_foreign` (`payment_method_type_id`),
  CONSTRAINT `quotation_payments_card_brand_id_foreign` FOREIGN KEY (`card_brand_id`) REFERENCES `card_brands` (`id`),
  CONSTRAINT `quotation_payments_payment_method_type_id_foreign` FOREIGN KEY (`payment_method_type_id`) REFERENCES `payment_method_types` (`id`),
  CONSTRAINT `quotation_payments_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `establishment` json NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` char(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `date_of_due` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `customer` json NOT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method_type_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exchange_rate_sale` decimal(13,3) NOT NULL,
  `total_prepayment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exportation` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_free` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxed` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_unaffected` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exonerated` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_igv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `charges` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `prepayments` json DEFAULT NULL,
  `guides` json DEFAULT NULL,
  `related` json DEFAULT NULL,
  `perception` json DEFAULT NULL,
  `detraction` json DEFAULT NULL,
  `legends` json DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `terms_condition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed` tinyint(1) NOT NULL DEFAULT '0',
  `sale_opportunity_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotations_user_id_foreign` (`user_id`),
  KEY `quotations_establishment_id_foreign` (`establishment_id`),
  KEY `quotations_customer_id_foreign` (`customer_id`),
  KEY `quotations_soap_type_id_foreign` (`soap_type_id`),
  KEY `quotations_state_type_id_foreign` (`state_type_id`),
  KEY `quotations_currency_type_id_foreign` (`currency_type_id`),
  KEY `quotations_payment_method_type_id_foreign` (`payment_method_type_id`),
  KEY `quotations_sale_opportunity_id_foreign` (`sale_opportunity_id`),
  CONSTRAINT `quotations_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `quotations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `quotations_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `quotations_payment_method_type_id_foreign` FOREIGN KEY (`payment_method_type_id`) REFERENCES `payment_method_types` (`id`),
  CONSTRAINT `quotations_sale_opportunity_id_foreign` FOREIGN KEY (`sale_opportunity_id`) REFERENCES `sale_opportunities` (`id`),
  CONSTRAINT `quotations_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `quotations_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `quotations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `retention_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `retention_id` int(10) unsigned NOT NULL,
  `document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `series` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue` date NOT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_document` decimal(10,2) NOT NULL,
  `payments` json NOT NULL,
  `exchange_rate` json NOT NULL,
  `date_of_retention` date NOT NULL,
  `total_retention` decimal(10,2) NOT NULL,
  `total_to_pay` decimal(10,2) NOT NULL,
  `total_payment` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `retention_documents_retention_id_foreign` (`retention_id`),
  KEY `retention_documents_document_type_id_foreign` (`document_type_id`),
  KEY `retention_documents_currency_type_id_foreign` (`currency_type_id`),
  CONSTRAINT `retention_documents_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `retention_documents_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `cat_document_types` (`id`),
  CONSTRAINT `retention_documents_retention_id_foreign` FOREIGN KEY (`retention_id`) REFERENCES `retentions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `retentions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `establishment` json NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubl_version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `series` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` int(11) NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `supplier_id` int(10) unsigned NOT NULL,
  `supplier` json NOT NULL,
  `retention_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observations` text COLLATE utf8mb4_unicode_ci,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_retention` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `legends` json DEFAULT NULL,
  `optional` json DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_xml` tinyint(1) NOT NULL DEFAULT '0',
  `has_pdf` tinyint(1) NOT NULL DEFAULT '0',
  `has_cdr` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `retentions_user_id_foreign` (`user_id`),
  KEY `retentions_establishment_id_foreign` (`establishment_id`),
  KEY `retentions_soap_type_id_foreign` (`soap_type_id`),
  KEY `retentions_state_type_id_foreign` (`state_type_id`),
  KEY `retentions_document_type_id_foreign` (`document_type_id`),
  KEY `retentions_supplier_id_foreign` (`supplier_id`),
  KEY `retentions_retention_type_id_foreign` (`retention_type_id`),
  KEY `retentions_currency_type_id_foreign` (`currency_type_id`),
  CONSTRAINT `retentions_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `retentions_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `cat_document_types` (`id`),
  CONSTRAINT `retentions_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `retentions_retention_type_id_foreign` FOREIGN KEY (`retention_type_id`) REFERENCES `cat_retention_types` (`id`),
  CONSTRAINT `retentions_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `retentions_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `retentions_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `retentions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_note_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sale_note_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_value` decimal(16,6) NOT NULL,
  `affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_base_igv` decimal(12,2) NOT NULL,
  `percentage_igv` decimal(12,2) NOT NULL,
  `total_igv` decimal(12,2) NOT NULL,
  `system_isc_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `percentage_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL,
  `price_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(16,6) NOT NULL,
  `total_value` decimal(12,2) NOT NULL,
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `attributes` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `charges` json DEFAULT NULL,
  `inventory_kardex_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_note_items_sale_note_id_foreign` (`sale_note_id`),
  KEY `sale_note_items_item_id_foreign` (`item_id`),
  KEY `sale_note_items_affectation_igv_type_id_foreign` (`affectation_igv_type_id`),
  KEY `sale_note_items_system_isc_type_id_foreign` (`system_isc_type_id`),
  KEY `sale_note_items_price_type_id_foreign` (`price_type_id`),
  KEY `sale_note_items_inventory_kardex_id_foreign` (`inventory_kardex_id`),
  CONSTRAINT `sale_note_items_affectation_igv_type_id_foreign` FOREIGN KEY (`affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `sale_note_items_inventory_kardex_id_foreign` FOREIGN KEY (`inventory_kardex_id`) REFERENCES `inventory_kardex` (`id`),
  CONSTRAINT `sale_note_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `sale_note_items_price_type_id_foreign` FOREIGN KEY (`price_type_id`) REFERENCES `cat_price_types` (`id`),
  CONSTRAINT `sale_note_items_sale_note_id_foreign` FOREIGN KEY (`sale_note_id`) REFERENCES `sale_notes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_note_items_system_isc_type_id_foreign` FOREIGN KEY (`system_isc_type_id`) REFERENCES `cat_system_isc_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_note_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sale_note_id` int(10) unsigned NOT NULL,
  `date_of_payment` date NOT NULL,
  `payment_method_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_card` tinyint(1) NOT NULL DEFAULT '0',
  `card_brand_id` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change` decimal(12,2) DEFAULT NULL,
  `payment` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_note_payments_sale_note_id_foreign` (`sale_note_id`),
  KEY `sale_note_payments_card_brand_id_foreign` (`card_brand_id`),
  KEY `sale_note_payments_payment_method_type_id_foreign` (`payment_method_type_id`),
  KEY `sale_note_payments_date_of_payment_index` (`date_of_payment`),
  CONSTRAINT `sale_note_payments_card_brand_id_foreign` FOREIGN KEY (`card_brand_id`) REFERENCES `card_brands` (`id`),
  CONSTRAINT `sale_note_payments_payment_method_type_id_foreign` FOREIGN KEY (`payment_method_type_id`) REFERENCES `payment_method_types` (`id`),
  CONSTRAINT `sale_note_payments_sale_note_id_foreign` FOREIGN KEY (`sale_note_id`) REFERENCES `sale_notes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `establishment` json NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `series` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `customer` json NOT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate_sale` decimal(13,3) NOT NULL,
  `apply_concurrency` tinyint(1) NOT NULL DEFAULT '0',
  `enabled_concurrency` tinyint(1) NOT NULL DEFAULT '0',
  `automatic_date_of_issue` date DEFAULT NULL,
  `quantity_period` int(11) DEFAULT NULL,
  `type_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_prepayment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exportation` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_free` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxed` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_unaffected` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exonerated` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_igv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_isc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_base_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_other_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `charges` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `prepayments` json DEFAULT NULL,
  `guides` json DEFAULT NULL,
  `related` json DEFAULT NULL,
  `perception` json DEFAULT NULL,
  `detraction` json DEFAULT NULL,
  `legends` json DEFAULT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_id` int(10) unsigned DEFAULT NULL,
  `order_note_id` int(10) unsigned DEFAULT NULL,
  `total_canceled` tinyint(1) NOT NULL DEFAULT '0',
  `changed` tinyint(1) NOT NULL DEFAULT '0',
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `license_plate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_notes_user_id_foreign` (`user_id`),
  KEY `sale_notes_establishment_id_foreign` (`establishment_id`),
  KEY `sale_notes_customer_id_foreign` (`customer_id`),
  KEY `sale_notes_soap_type_id_foreign` (`soap_type_id`),
  KEY `sale_notes_state_type_id_foreign` (`state_type_id`),
  KEY `sale_notes_currency_type_id_foreign` (`currency_type_id`),
  KEY `sale_notes_quotation_id_foreign` (`quotation_id`),
  KEY `sale_notes_apply_concurrency_index` (`apply_concurrency`),
  KEY `sale_notes_type_period_index` (`type_period`),
  KEY `sale_notes_quantity_period_index` (`quantity_period`),
  KEY `sale_notes_automatic_date_of_issue_index` (`automatic_date_of_issue`),
  KEY `sale_notes_enabled_concurrency_index` (`enabled_concurrency`),
  KEY `sale_notes_order_note_id_foreign` (`order_note_id`),
  CONSTRAINT `sale_notes_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `sale_notes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `sale_notes_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `sale_notes_order_note_id_foreign` FOREIGN KEY (`order_note_id`) REFERENCES `order_notes` (`id`),
  CONSTRAINT `sale_notes_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`),
  CONSTRAINT `sale_notes_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `sale_notes_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `sale_notes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_opportunities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `establishment_id` int(10) unsigned NOT NULL,
  `establishment` json NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `customer` json NOT NULL,
  `currency_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate_sale` decimal(13,3) NOT NULL,
  `total_exportation` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_free` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxed` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_unaffected` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_exonerated` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_igv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_taxes` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci,
  `observation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_opportunities_user_id_foreign` (`user_id`),
  KEY `sale_opportunities_establishment_id_foreign` (`establishment_id`),
  KEY `sale_opportunities_customer_id_foreign` (`customer_id`),
  KEY `sale_opportunities_soap_type_id_foreign` (`soap_type_id`),
  KEY `sale_opportunities_state_type_id_foreign` (`state_type_id`),
  KEY `sale_opportunities_currency_type_id_foreign` (`currency_type_id`),
  CONSTRAINT `sale_opportunities_currency_type_id_foreign` FOREIGN KEY (`currency_type_id`) REFERENCES `cat_currency_types` (`id`),
  CONSTRAINT `sale_opportunities_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `sale_opportunities_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`),
  CONSTRAINT `sale_opportunities_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `sale_opportunities_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `sale_opportunities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_opportunity_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sale_opportunity_id` int(10) unsigned NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_opportunity_files_sale_opportunity_id_foreign` (`sale_opportunity_id`),
  CONSTRAINT `sale_opportunity_files_sale_opportunity_id_foreign` FOREIGN KEY (`sale_opportunity_id`) REFERENCES `sale_opportunities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_opportunity_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sale_opportunity_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item` json NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_value` decimal(16,6) NOT NULL,
  `affectation_igv_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_base_igv` decimal(12,2) NOT NULL,
  `percentage_igv` decimal(12,2) NOT NULL,
  `total_igv` decimal(12,2) NOT NULL,
  `total_taxes` decimal(12,2) NOT NULL,
  `price_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` decimal(16,6) NOT NULL,
  `total_value` decimal(12,2) NOT NULL,
  `total_charge` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `attributes` json DEFAULT NULL,
  `discounts` json DEFAULT NULL,
  `charges` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_opportunity_items_sale_opportunity_id_foreign` (`sale_opportunity_id`),
  KEY `sale_opportunity_items_item_id_foreign` (`item_id`),
  KEY `sale_opportunity_items_affectation_igv_type_id_foreign` (`affectation_igv_type_id`),
  KEY `sale_opportunity_items_price_type_id_foreign` (`price_type_id`),
  CONSTRAINT `sale_opportunity_items_affectation_igv_type_id_foreign` FOREIGN KEY (`affectation_igv_type_id`) REFERENCES `cat_affectation_igv_types` (`id`),
  CONSTRAINT `sale_opportunity_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `sale_opportunity_items_price_type_id_foreign` FOREIGN KEY (`price_type_id`) REFERENCES `cat_price_types` (`id`),
  CONSTRAINT `sale_opportunity_items_sale_opportunity_id_foreign` FOREIGN KEY (`sale_opportunity_id`) REFERENCES `sale_opportunities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `series` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `establishment_id` int(10) unsigned NOT NULL,
  `document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contingency` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `series_establishment_id_foreign` (`establishment_id`),
  KEY `series_document_type_id_foreign` (`document_type_id`),
  CONSTRAINT `series_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `cat_document_types` (`id`),
  CONSTRAINT `series_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `series` VALUES (1,1,'01','F001',0,NULL,NULL),(2,1,'03','B001',0,NULL,NULL),(3,1,'07','FC01',0,NULL,NULL),(4,1,'07','BC01',0,NULL,NULL),(5,1,'08','FD01',0,NULL,NULL),(6,1,'08','BD01',0,NULL,NULL),(7,1,'20','R001',0,NULL,NULL),(8,1,'09','T001',0,NULL,NULL),(9,1,'40','P001',0,NULL,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `series_configurations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `series_id` int(10) unsigned NOT NULL,
  `document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `series` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `series_configurations_series_id_foreign` (`series_id`),
  KEY `series_configurations_series_index` (`series`),
  KEY `series_configurations_number_index` (`number`),
  KEY `series_configurations_document_type_id_foreign` (`document_type_id`),
  CONSTRAINT `series_configurations_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `cat_document_types` (`id`),
  CONSTRAINT `series_configurations_series_id_foreign` FOREIGN KEY (`series_id`) REFERENCES `series` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `soap_types` (
  `id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `soap_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `soap_types` VALUES ('01','Demo'),('02','Producción'),('03','Interno');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `state_types` (
  `id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `state_types_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `state_types` VALUES ('01','Registrado'),('03','Enviado'),('05','Aceptado'),('07','Observado'),('09','Rechazado'),('11','Anulado'),('13','Por anular');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status_orders` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `status_orders` VALUES (1,'Pago sin verificar','2020-05-13 22:42:23',NULL),(2,'Pago verificado','2020-05-13 22:42:23',NULL),(3,'Despachado','2020-05-13 22:42:23',NULL),(4,'Confirmado por el cliente','2020-05-13 22:42:23',NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `summaries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary_status_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubl_version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_reference` date NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticket` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_ticket` tinyint(1) NOT NULL DEFAULT '0',
  `has_cdr` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `summaries_user_id_foreign` (`user_id`),
  KEY `summaries_soap_type_id_foreign` (`soap_type_id`),
  KEY `summaries_state_type_id_foreign` (`state_type_id`),
  KEY `summaries_summary_status_type_id_foreign` (`summary_status_type_id`),
  KEY `summaries_date_of_issue_index` (`date_of_issue`),
  CONSTRAINT `summaries_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `summaries_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `summaries_summary_status_type_id_foreign` FOREIGN KEY (`summary_status_type_id`) REFERENCES `cat_summary_status_types` (`id`),
  CONSTRAINT `summaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `summary_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `summary_id` int(10) unsigned NOT NULL,
  `document_id` int(10) unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `summary_documents_summary_id_foreign` (`summary_id`),
  KEY `summary_documents_document_id_foreign` (`document_id`),
  CONSTRAINT `summary_documents_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`),
  CONSTRAINT `summary_documents_summary_id_foreign` FOREIGN KEY (`summary_id`) REFERENCES `summaries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `execution_time` time NOT NULL,
  `output` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technical_services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `customer` json NOT NULL,
  `cellphone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue` date NOT NULL,
  `time_of_issue` time NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `prepayment` decimal(12,2) NOT NULL DEFAULT '0.00',
  `activities` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `technical_services_user_id_foreign` (`user_id`),
  KEY `technical_services_soap_type_id_foreign` (`soap_type_id`),
  KEY `technical_services_customer_id_foreign` (`customer_id`),
  KEY `technical_services_date_of_issue_index` (`date_of_issue`),
  KEY `technical_services_serial_number_index` (`serial_number`),
  CONSTRAINT `technical_services_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `persons` (`id`),
  CONSTRAINT `technical_services_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `technical_services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_commissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_commissions_user_id_foreign` (`user_id`),
  CONSTRAINT `user_commissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `establishment_id` int(10) unsigned DEFAULT NULL,
  `type` enum('admin','seller','integrator','client') COLLATE utf8mb4_unicode_ci NOT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  `identity_document_type_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_api_token_unique` (`api_token`),
  KEY `users_establishment_id_foreign` (`establishment_id`),
  CONSTRAINT `users_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `users` VALUES (1,'Administrador','demo@gmail.com',NULL,'$2y$10$pmRN8nQcBZp8qT0UtZqrveONoi3xAzejbgliYdPQM8mW/TnBuD9Le','M7yIQBGTlYp6j9zUhqKaMWoskoDwcc3vWrSQs2eHxDvP0QmHph',1,'admin',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voided` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `external_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `soap_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_type_id` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubl_version` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_issue` date NOT NULL,
  `date_of_reference` date NOT NULL,
  `identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticket` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_ticket` tinyint(1) NOT NULL DEFAULT '0',
  `has_cdr` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `voided_user_id_foreign` (`user_id`),
  KEY `voided_soap_type_id_foreign` (`soap_type_id`),
  KEY `voided_state_type_id_foreign` (`state_type_id`),
  CONSTRAINT `voided_soap_type_id_foreign` FOREIGN KEY (`soap_type_id`) REFERENCES `soap_types` (`id`),
  CONSTRAINT `voided_state_type_id_foreign` FOREIGN KEY (`state_type_id`) REFERENCES `state_types` (`id`),
  CONSTRAINT `voided_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voided_documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `voided_id` int(10) unsigned NOT NULL,
  `document_id` int(10) unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `voided_documents_voided_id_foreign` (`voided_id`),
  KEY `voided_documents_document_id_foreign` (`document_id`),
  CONSTRAINT `voided_documents_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`),
  CONSTRAINT `voided_documents_voided_id_foreign` FOREIGN KEY (`voided_id`) REFERENCES `voided` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warehouses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `establishment_id` int(10) unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warehouses_establishment_id_foreign` (`establishment_id`),
  CONSTRAINT `warehouses_establishment_id_foreign` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
