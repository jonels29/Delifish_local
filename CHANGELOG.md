NOTAS DE CAMBIO: 

Agregar cambio a tabla de MOD_MENU_CONF. 

	ALTER TABLE  `MOD_MENU_CONF` ADD  `mod_pro` INT NULL DEFAULT NULL AFTER  `mod_stock` ;

Cambio tipo de variable a campos de dicha tabla: 

	ALTER TABLE  `MOD_MENU_CONF` CHANGE  `ID`  `ID` INT( 11 ) NOT NULL AUTO_INCREMENT ,
	CHANGE  `mod_sales`  `mod_sales` INT( 1 ) NOT NULL DEFAULT  '0',
	CHANGE  `mod_fact`  `mod_fact` INT( 1 ) NOT NULL DEFAULT  '0',
	CHANGE  `mod_invt`  `mod_invt` INT( 1 ) NOT NULL DEFAULT  '0',
	CHANGE  `mod_rept`  `mod_rept` INT( 1 ) NOT NULL DEFAULT  '0',
	CHANGE  `mod_stock`  `mod_stock` INT( 1 ) NOT NULL DEFAULT  '0',
	CHANGE  `mod_pro`  `mod_pro` INT( 11 ) NOT NULL DEFAULT  '0';


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
		
	CREATE TABLE `QUO_TEMP_DETAIL` (
		`quo_templateID` INT(10) NOT NULL,
		`itemID` VARCHAR(20) NOT NULL,
		`description` VARCHAR(30) NOT NULL,
		`unit` VARCHAR(10) NOT NULL,
		INDEX `Index 1` (`itemID`)
		);
		
	CREATE TABLE `QUO_HEADER` (
		`quotationID` INT(10) NOT NULL,
		`ID_compania` BIGINT(20) NULL DEFAULT NULL,
		`CustomerID` VARCHAR(50) NULL DEFAULT NULL,
		`ship_address` VARCHAR(30) NULL DEFAULT NULL,
		`taxID` VARCHAR(8) NULL DEFAULT NULL,
		`CustomerPO` VARCHAR(20) NULL DEFAULT NULL,
		`remarks` VARCHAR(30) NULL DEFAULT NULL,
		`status` INT(5) NULL DEFAULT NULL,
		`LAST_CHANGE` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
		INDEX `Index 1` (`quotationID`)
		);  
		
	CREATE TABLE `QUO_DETAIL` (
		`quotationID` INT(10) NOT NULL,
		`itemID` VARCHAR(20) NOT NULL,
		`qty` INT(4) NOT NULL,
		`unit_price` DECIMAL(18,4) NOT NULL,
		`total` DECIMAL(18,4) NOT NULL
		);