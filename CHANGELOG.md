NOTAS DE CAMBIO: 

CAMBIO EN LA TABLA DE MODULOS

    --
    -- Table structure for table `MOD_MENU_CONF`
    --

    CREATE TABLE IF NOT EXISTS `MOD_MENU_CONF` (
      `ID` int(11) NOT NULL AUTO_INCREMENT,
      `mod_sales` int(11) NOT NULL DEFAULT '0',
      `mod_invo` int(11) NOT NULL DEFAULT '0',
      `mod_fact` int(11) NOT NULL DEFAULT '0',
      `mod_invt` int(11) NOT NULL DEFAULT '0',
      `mod_rept` int(11) NOT NULL DEFAULT '0',
      `mod_stock` int(11) NOT NULL DEFAULT '0',
      `mod_pro` int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (`ID`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;


SE AGREGAN CAMBIOS A LA TABLA DE SAX_USER 

	ALTER TABLE  `SAX_USER` ADD  `pro_addmod` INT NOT NULL AFTER  `stoc_view` ,
	ADD  `tpl_addmod` INT NOT NULL AFTER  `pro_addmod` ;

SE AGREGA CAMPOS A LA TABLA DE sale_tax 

    ALTER TABLE  `sale_tax` ADD  `DEFAULT` INT( 1 ) NOT NULL DEFAULT  '0' AFTER  `rate` ;

cambio de primary key en tabla de Sales Representative

    ALTER TABLE  `Sales_Representative_Exp` ADD PRIMARY KEY (  `SalesRepID` ,  `ID_compania` ) ;
	

Creacion de tablas para modulo de presupuesto

	CREATE TABLE `QUO_TEMP_HEADER` (
		`quo_templateID` INT(10) NOT NULL,
		`quotation_name` VARCHAR(40) NOT NULL,
		`ID_compania` BIGINT(20) NOT NULL,
		`status` INT(5) NOT NULL,
		`LAST_CHANGE` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		INDEX `Index 1` (`quo_templateID`)
		);


SE AGREGAN TABLAS PARA GESTION DE IMPRESION DE FACTURAS




--
-- Table structure for table `INVOICE_GEN_DETAIL`
--

CREATE TABLE `INVOICE_GEN_DETAIL` (
  `SalesOrderNumber` varchar(50) NOT NULL,
  `ItemOrd` int(11) NOT NULL,
  `Despachado` decimal(16,8) NOT NULL,
  `TotalLinea` decimal(16,8) NOT NULL,
  `ID_compania` int(11) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UnitPrice` decimal(16,8) NOT NULL,
  `ItemID` varchar(20) NOT NULL,
  PRIMARY KEY (`SalesOrderNumber`,`ItemOrd`,`ID_compania`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `INVOICE_GEN_HEADER`
--

CREATE TABLE `INVOICE_GEN_HEADER` (
  `InvoiceNumber` varchar(50) DEFAULT NULL,
  `SalesOrderNumber` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `Total` decimal(16,8) NOT NULL,
  `SubTotal` decimal(16,8) NOT NULL,
  `Itbms` decimal(16,8) NOT NULL,
  `ID_compania` int(11) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TipoPago` int(11) NOT NULL DEFAULT '3',
  `TaxID` varchar(8) NOT NULL,
  `NOTAS` varchar(1024)  NULL,
  PRIMARY KEY (`SalesOrderNumber`,`ID_compania`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




ALTER TABLE `SalesOrder_Header_Imp`
	ADD COLUMN `EMITIDA` tinyint(1) NOT NULL;





tabla para terminos de pago

CREATE TABLE `CUST_PAY_TERM` (
  `CustomerID` VARCHAR(50) NULL,
  `DaysToPay` VARCHAR(50) NULL,
  `ID_compania` INT NULL
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;


CAMBIO TABLA DE SALES ORDER DETAIL

  --
  -- Table structure for table `SalesOrder_Detail_Imp`
  --

  CREATE TABLE IF NOT EXISTS `SalesOrder_Detail_Imp` (
    `ID` int(11) NOT NULL AUTO_INCREMENT,
    `ID_compania` bigint(20) NOT NULL,
    `SalesOrderNumber` varchar(20) NOT NULL,
    `ItemOrd` int(11) NOT NULL,
    `Item_id` varchar(20) NOT NULL,
    `Description` varchar(160) NOT NULL,
    `Quantity` decimal(11,5) NOT NULL,
    `Unit_Price` decimal(18,4) NOT NULL,
    `Net_line` decimal(18,4) NOT NULL,
    `Taxable` int(1) NOT NULL,
    `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `REMARK` varchar(200) DEFAULT NULL,
    `PK_CHICO` varchar(50) DEFAULT NULL,
    `PK_GRANDE` varchar(50) DEFAULT NULL,
    PRIMARY KEY (`ID`,`ID_compania`,`SalesOrderNumber`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


cambio por delifish 

ALTER TABLE `SalesOrder_Header_Imp`
  ADD COLUMN `fecha_entrega` VARCHAR(20) NULL AFTER `Emitida`;