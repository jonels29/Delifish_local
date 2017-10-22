SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `daoutqw3_sage_sa` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `daoutqw3_sage_sa`;

DROP TABLE IF EXISTS `almacenes`;
CREATE TABLE IF NOT EXISTS `almacenes` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `ID_compania` bigint(20) DEFAULT NULL,
  `onoff` varchar(45) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `almacenes` (`id`, `name`, `ID_compania`, `onoff`) VALUES
(1, 'Default', NULL, '1');

DROP TABLE IF EXISTS `CompanySession`;
CREATE TABLE IF NOT EXISTS `CompanySession` (
  `ID_compania` bigint(20) NOT NULL,
  `CompanyNameSage50` varchar(50) NOT NULL,
  `isConnected` tinyint(1) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `CompanySession` (`ID_compania`, `CompanyNameSage50`, `isConnected`, `LAST_CHANGE`) VALUES
(9, 'Test', 0, '2017-04-10 16:54:10');

DROP TABLE IF EXISTS `company_info`;
CREATE TABLE IF NOT EXISTS `company_info` (
  `id` int(11) NOT NULL,
  `company_name` varchar(45) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `id_company_sage` int(11) DEFAULT NULL,
  `Tel` varchar(45) DEFAULT NULL,
  `Fax` varchar(45) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `company_info` (`id`, `company_name`, `address`, `id_company_sage`, `Tel`, `Fax`, `email`) VALUES
(1, 'Prueba Sage', 'Ciudad de Panama, Panama', 1, '507-398-0408', '', 'info@daoutel.net');

DROP TABLE IF EXISTS `CONF_SMTP`;
CREATE TABLE IF NOT EXISTS `CONF_SMTP` (
  `ID` int(11) NOT NULL,
  `HOSTNAME` varchar(150) NOT NULL,
  `PORT` int(3) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(50) NOT NULL,
  `Auth` varchar(5) NOT NULL DEFAULT 'true',
  `SMTPSecure` varchar(10) NOT NULL DEFAULT 'false',
  `SMTPDebug` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `CONF_SMTP` (`ID`, `HOSTNAME`, `PORT`, `USERNAME`, `PASSWORD`, `Auth`, `SMTPSecure`, `SMTPDebug`) VALUES
(1, 'mail.daoutel.net', 587, 'info@daoutel.net', 'vito1603', 'true', 'false', 0);

DROP TABLE IF EXISTS `CON_HEADER`;
CREATE TABLE IF NOT EXISTS `CON_HEADER` (
  `date` date NOT NULL,
  `idReg` int(11) NOT NULL,
  `idJob` varchar(40) NOT NULL,
  `idPha` varchar(40) NOT NULL,
  `idCost` varchar(40) NOT NULL,
  `lastChange` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nota` varchar(255) DEFAULT NULL,
  `refReg` varchar(7) NOT NULL,
  `ID_compania` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `CON_REG_TRAS`;
CREATE TABLE IF NOT EXISTS `CON_REG_TRAS` (
  `ID` int(11) NOT NULL,
  `idReg` int(11) NOT NULL,
  `idRegTras` int(11) NOT NULL,
  `ID_compania` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `CTA_GL_CONF`;
CREATE TABLE IF NOT EXISTS `CTA_GL_CONF` (
  `ID` int(11) NOT NULL,
  `CTA_CXP` varchar(8) NOT NULL,
  `CTA_PUR` varchar(8) NOT NULL,
  `CTA_TAX` varchar(8) NOT NULL,
  `ID_compania` int(11) NOT NULL,
  `GLACCT` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Customers_Exp`;
CREATE TABLE IF NOT EXISTS `Customers_Exp` (
  `ID` int(11) NOT NULL,
  `CustomerID` varchar(20) NOT NULL,
  `Customer_Bill_Name` varchar(39) NOT NULL,
  `PriceLevel` int(11) NOT NULL,
  `Phone_Number` varchar(20) NOT NULL,
  `Contact` varchar(45) NOT NULL,
  `Balance` decimal(10,2) NOT NULL,
  `CreditLimit` decimal(10,2) NOT NULL,
  `Email` varchar(64) NOT NULL,
  `AddressLine1` varchar(30) NOT NULL,
  `AddressLine2` varchar(30) NOT NULL,
  `City` varchar(20) NOT NULL,
  `State` varchar(2) NOT NULL,
  `Zip` varchar(12) NOT NULL,
  `Country` varchar(15) NOT NULL,
  `IsActive` bit(1) NOT NULL,
  `SalesRepID` varchar(20) NOT NULL,
  `SalesRepName` varchar(56) NOT NULL,
  `id_compania` int(11) NOT NULL,
  `custom_field1` varchar(40) NOT NULL,
  `custom_field2` varchar(40) NOT NULL,
  `Custom_field3` varchar(40) NOT NULL,
  `Custom_field4` varchar(40) NOT NULL,
  `Custom_field5` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Customers_Imp`;
CREATE TABLE IF NOT EXISTS `Customers_Imp` (
  `ID_compania` bigint(20) DEFAULT NULL,
  `CustomerID` varchar(20) DEFAULT NULL,
  `Customer_Bill_Name` varchar(39) DEFAULT NULL,
  `AddressLine1` varchar(30) DEFAULT NULL,
  `AddressLine2` varchar(30) DEFAULT NULL,
  `City` varchar(20) DEFAULT NULL,
  `State` varchar(2) DEFAULT NULL,
  `Zip` varchar(12) DEFAULT NULL,
  `Country` varchar(15) DEFAULT NULL,
  `Telephone1` varchar(20) DEFAULT NULL,
  `Email` varchar(64) DEFAULT NULL,
  `Custom_field1` varchar(40) DEFAULT NULL,
  `Custom_field2` varchar(40) DEFAULT NULL,
  `Custom_field3` varchar(40) DEFAULT NULL,
  `Custom_field4` varchar(40) DEFAULT NULL,
  `Custom_field5` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `FAC_DET_CONF`;
CREATE TABLE IF NOT EXISTS `FAC_DET_CONF` (
  `ID` int(11) NOT NULL,
  `DIV_LINE` int(11) NOT NULL,
  `NO_LINES` int(4) NOT NULL,
  `ID_compania` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `Sales_Representative_Exp`;
CREATE TABLE IF NOT EXISTS `Sales_Representative_Exp` (
  `SalesRepID`  varchar(20) NOT NULL,
  `SalesRep_Name` varchar(55) DEFAULT NULL,
  `ID_compania` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `InventoryAdjust_Imp`;
CREATE TABLE IF NOT EXISTS `InventoryAdjust_Imp` (
  `ItemID` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `JobPhaseID` varchar(20) DEFAULT NULL,
  `JobCostCodeID` varchar(20) DEFAULT NULL,
  `ID_compania` bigint(20) NOT NULL,
  `UniqueInvAdjID` bigint(20) NOT NULL,
  `Reference` varchar(20) NOT NULL,
  `Export_date` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `ReasonToAdjust` varchar(35) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `Account` varchar(15) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `UnitCost` decimal(16,4) DEFAULT NULL,
  `Quantity` decimal(11,4) DEFAULT NULL,
  `ErrorPT` varchar(1024) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `Error` tinyint(1) NOT NULL DEFAULT '0',
  `Enviado` tinyint(1) DEFAULT '0',
  `LAST_CHANGE` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Date` date DEFAULT NULL,
  `USER` int(11) DEFAULT NULL,
  `JobID` varchar(50) DEFAULT NULL,
  `Job_id_int` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TRIGGER IF EXISTS `Inventory_On_Error`;
DELIMITER $$
CREATE TRIGGER `Inventory_On_Error` AFTER UPDATE ON `InventoryAdjust_Imp`
 FOR EACH ROW BEGIN
DECLARE  ORDER_Error varchar(150);
DECLARE  ORDER_Number varchar(150);
DECLARE  QTY_Pennding  int;
DECLARE  QTY_current int;
DECLARE  NEW_QTY  int;
DECLARE  Location int;
DECLARE  No_Items  int;

SET  ORDER_Error = (SELECT Error
FROM  
InventoryAdjust_Imp ORDER BY LAST_CHANGE desc limit 1);

IF (ORDER_Error > 0)

THEN

SET  ORDER_Number = (SELECT Reference
FROM  
InventoryAdjust_Imp  ORDER BY LAST_CHANGE desc limit 1);

SET Location = (SELECT location_id
FROM  
InventoryAdjust_Imp  ORDER BY LAST_CHANGE desc limit 1);


SET QTY_Pennding = (SELECT Quantity
FROM  
InventoryAdjust_Imp  ORDER BY LAST_CHANGE desc limit 1);


SET QTY_current = (SELECT qty
FROM  status_location where id=Location);



SET NEW_QTY = ( -1 )* QTY_Pennding + QTY_current;

UPDATE status_location SET qty=NEW_QTY where id=Location;


END IF;
END
$$
DELIMITER ;

DROP TABLE IF EXISTS `Jobs_Exp`;
CREATE TABLE IF NOT EXISTS `Jobs_Exp` (
  `ID_compania` bigint(20) NOT NULL,
  `JobID` varchar(20) NOT NULL,
  `Description` varchar(30) NOT NULL,
  `JobPhases` tinyint(4) NOT NULL,
  `ID` int(11) NOT NULL,
  `IsActive` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Job_Cost_Codes_Exp`;
CREATE TABLE IF NOT EXISTS `Job_Cost_Codes_Exp` (
  `ID_compania` bigint(20) NOT NULL,
  `CostCodeID` varchar(20) NOT NULL,
  `Description` varchar(30) DEFAULT NULL,
  `IsActive` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Job_Phases_Exp`;
CREATE TABLE IF NOT EXISTS `Job_Phases_Exp` (
  `ID_compania` bigint(20) NOT NULL,
  `PhaseID` varchar(20) NOT NULL,
  `Description` varchar(30) NOT NULL,
  `IsActive` int(11) NOT NULL,
  `JobCostCodes` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `MOD_MENU_CONF`;
CREATE TABLE IF NOT EXISTS `MOD_MENU_CONF` (
  `ID` int(11) NOT NULL,
  `mod_sales` varchar(7) NOT NULL,
  `mod_fact` varchar(7) NOT NULL,
  `mod_invt` varchar(7) NOT NULL,
  `mod_rept` varchar(7) NOT NULL,
  `mod_stock` varchar(7) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `MOD_MENU_CONF` (`ID`, `mod_sales`, `mod_fact`, `mod_invt`, `mod_rept`, `mod_stock`) VALUES
(58, '1', '1', '1', '1', '1');

DROP TABLE IF EXISTS `PO_FACT_LOG`;
CREATE TABLE IF NOT EXISTS `PO_FACT_LOG` (
  `ID` int(11) NOT NULL,
  `NO_PO` varchar(20) NOT NULL,
  `REF_FACT` varchar(20) NOT NULL,
  `ITEM_ID` varchar(40) NOT NULL,
  `ITEM_QTY` int(11) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ID_compania` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `PRI_LIST_ID`;
CREATE TABLE IF NOT EXISTS `PRI_LIST_ID` (
  `ID` int(11) NOT NULL,
  `IDPRICE` varchar(50) NOT NULL,
  `DESCRIPTION` varchar(100) DEFAULT NULL,
  `ID_compania` int(11) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `PRI_LIST_ITEM`;
CREATE TABLE IF NOT EXISTS `PRI_LIST_ITEM` (
  `IDPRICE` varchar(50) NOT NULL,
  `IDITEM` varchar(50) NOT NULL,
  `DESCRIPTION` varchar(50) NOT NULL,
  `PRICE` decimal(18,4) NOT NULL,
  `UNIT` varchar(5) NOT NULL,
  `ID_compania` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Products_Exp`;
CREATE TABLE IF NOT EXISTS `Products_Exp` (
  `ID` int(11) NOT NULL,
  `ProductID` varchar(45) NOT NULL,
  `Description` varchar(160) DEFAULT NULL,
  `QtyOnHand` decimal(18,5) DEFAULT NULL,
  `Price1` decimal(18,4) DEFAULT NULL,
  `Price2` decimal(18,4) DEFAULT NULL,
  `Price3` decimal(18,4) DEFAULT NULL,
  `Price4` decimal(18,4) DEFAULT NULL,
  `Price5` decimal(18,4) DEFAULT NULL,
  `Price6` decimal(18,4) DEFAULT NULL,
  `Price7` decimal(18,4) DEFAULT NULL,
  `Price8` decimal(18,4) DEFAULT NULL,
  `Price9` decimal(18,4) DEFAULT NULL,
  `Price10` decimal(18,4) DEFAULT NULL,
  `TaxType` int(11) DEFAULT NULL,
  `UnitMeasure` varchar(20) DEFAULT NULL,
  `IsActive` bit(1) DEFAULT NULL,
  `ItemClass` bigint(8) DEFAULT NULL,
  `id_compania` int(11) NOT NULL DEFAULT '0',
  `status` varchar(1024) DEFAULT NULL,
  `link_foto` varchar(255) DEFAULT NULL,
  `id_location` varchar(45) NOT NULL DEFAULT '1',
  `Custom_field1` varchar(40) DEFAULT NULL,
  `Custom_field2` varchar(40) DEFAULT NULL,
  `Custom_field3` varchar(40) DEFAULT NULL,
  `Custom_field4` varchar(40) DEFAULT NULL,
  `Custom_field5` varchar(40) DEFAULT NULL,
  `UPC_SKU` varchar(20) DEFAULT NULL,
  `GL_Sales_Acct` varchar(15) DEFAULT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `LastUnitCost` decimal(16,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TRIGGER IF EXISTS `Products_Exp_after_update`;
DELIMITER $$
CREATE TRIGGER `Products_Exp_after_update` AFTER UPDATE ON `Products_Exp`
 FOR EACH ROW BEGIN

/*ubico el id del item que esta siendo actualizado*/
SET  @ITEMID = (SELECT ProductID
FROM  
Products_Exp ORDER BY LAST_CHANGE desc limit 1);

SET  @IDcomp = (SELECT id_compania
FROM  
Products_Exp ORDER BY LAST_CHANGE desc limit 1);


SET @LOTEr  =  CONCAT(@ITEMID,"0000");

SET @NEW_QTY = NEW.QtyOnHand;

SET @OLD_QTY = OLD.QtyOnHand;


/*Esto solo ocurre si en PT agrego nuevos items, o la factura de compra afecta en positivo la tabla de productos*/
IF @NEW_QTY > @OLD_QTY 
THEN  


/*Guardo la variable de cantidad de aumento*/ 
SET @NOW_QTY = @NEW_QTY - @OLD_QTY ;


/* Actualizo cantidad en la ubicacion por default con la nueva cantidad*/
SET @LOC_QTY = (SELECT qty FROM status_location WHERE lote = @LOTEr and ID_compania = @IDcomp  AND route = '1' and stock = '1' limit 1);

SET @UPDATE_LOC_QTY = @NOW_QTY + @LOC_QTY;

UPDATE status_location SET qty = @UPDATE_LOC_QTY WHERE lote = @LOTEr and ID_compania = @IDcomp AND route = '1' and stock = '1';



END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `Products_Exp_AFTER_INSERT`;
DELIMITER $$
CREATE TRIGGER `products_exp_AFTER_INSERT` AFTER INSERT ON `Products_Exp`
 FOR EACH ROW BEGIN

DECLARE  ITEMID varchar(50);
DECLARE  LOTE varchar(50);
DECLARE  LOTE_QTY varchar(50);
DECLARE  Reg_KEY varchar(50);
DECLARE  IDComp INT;

SET  ITEMID = (SELECT ProductID
FROM  
Products_Exp ORDER BY ID desc limit 1);

SET  IDComp = (SELECT id_compania
FROM  
Products_Exp ORDER BY ID desc limit 1);


SET  LOTE_QTY = (SELECT QtyOnHand
FROM  
Products_Exp ORDER BY ID desc limit 1);

SET LOTE = CONCAT(ITEMID,'0000');


SET Reg_KEY = (SELECT UUID_SHORT());



INSERT INTO Prod_Lotes (REG_KEY, ProductID, no_lote,lote_qty, ID_compania) 
values (Reg_KEY, ITEMID , LOTE , '',IDComp);

INSERT INTO status_location (lote,id_product,stock,qty,route, ID_compania) 
values (LOTE, ITEMID , '1' , LOTE_QTY, '1', IDComp);

END
$$
DELIMITER ;

DROP TABLE IF EXISTS `Products_Imp`;
CREATE TABLE IF NOT EXISTS `Products_Imp` (
  `ID_compania` bigint(20) NOT NULL,
  `ProductID` varchar(20) NOT NULL,
  `Description` varchar(160) DEFAULT NULL,
  `Price1` decimal(16,4) DEFAULT NULL,
  `Price2` decimal(16,4) DEFAULT NULL,
  `Price3` decimal(16,4) DEFAULT NULL,
  `Price4` decimal(16,4) DEFAULT NULL,
  `Price5` decimal(16,4) DEFAULT NULL,
  `Price6` decimal(16,4) DEFAULT NULL,
  `Price7` decimal(16,4) DEFAULT NULL,
  `Price8` decimal(16,4) DEFAULT NULL,
  `Price9` decimal(16,4) DEFAULT NULL,
  `Price10` decimal(16,4) DEFAULT NULL,
  `TaxType` bigint(20) DEFAULT NULL,
  `Custom_field1` varchar(40) DEFAULT NULL,
  `Custom_field2` varchar(40) DEFAULT NULL,
  `Custom_field3` varchar(40) DEFAULT NULL,
  `Custom_field4` varchar(40) DEFAULT NULL,
  `Custom_field5` varchar(40) DEFAULT NULL,
  `UnitMeasure` varchar(20) DEFAULT NULL,
  `GL_Sales_Acct` varchar(15) DEFAULT NULL,
  `Export_date` datetime DEFAULT NULL,
  `Enviado` tinyint(1) DEFAULT NULL,
  `Error` tinyint(1) DEFAULT NULL,
  `ErrorPT` varchar(1024) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Prod_Lotes`;
CREATE TABLE IF NOT EXISTS `Prod_Lotes` (
  `ID` int(11) NOT NULL,
  `ProductID` varchar(50) NOT NULL,
  `no_lote` varchar(45) NOT NULL,
  `lote_qty` decimal(18,4) NOT NULL,
  `fecha_ven` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `onoff` tinyint(1) NOT NULL DEFAULT '1',
  `REG_KEY` varchar(255) NOT NULL,
  `ID_compania` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Purchase_Detail_Exp`;
CREATE TABLE IF NOT EXISTS `Purchase_Detail_Exp` (
  `ID_compania` bigint(20) NOT NULL,
  `PurchaseID` bigint(20) NOT NULL,
  `RowIndex` int(11) NOT NULL,
  `Item_id` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Description` varchar(160) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `GL_AccountID` varchar(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Quantity` decimal(11,4) NOT NULL,
  `Unit_Price` decimal(16,4) NOT NULL,
  `NetLine` decimal(16,2) NOT NULL,
  `JobID` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `JobPhaseID` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `JobCostCodeID` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Purchase_Detail_Imp`;
CREATE TABLE IF NOT EXISTS `Purchase_Detail_Imp` (
  `ID` int(11) NOT NULL,
  `ID_compania` bigint(20) NOT NULL,
  `TransactionID` bigint(20) NOT NULL,
  `Item_id` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Description` varchar(160) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `GL_Acct` varchar(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Quantity` decimal(11,4) NOT NULL,
  `Unit_Price` decimal(16,4) NOT NULL,
  `Net_line` decimal(16,2) NOT NULL,
  `JobID` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `JobPhaseID` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `JobCostCodeID` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Purchase_Header_Exp`;
CREATE TABLE IF NOT EXISTS `Purchase_Header_Exp` (
  `ID_compania` bigint(20) NOT NULL,
  `PurchaseID` bigint(20) NOT NULL,
  `PurchaseNumber` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `VendorID` varchar(20) NOT NULL,
  `DropShip` varchar(20) NOT NULL,
  `DropShipCustomerID` varchar(20) NOT NULL,
  `DropShipCustomerName` varchar(20) NOT NULL,
  `VendorName` varchar(150) NOT NULL,
  `Date` datetime NOT NULL,
  `AccountID` varchar(20) NOT NULL,
  `CustomerSO` varchar(20) NOT NULL,
  `Total` decimal(18,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Purchase_Header_Imp`;
CREATE TABLE IF NOT EXISTS `Purchase_Header_Imp` (
  `TransactionID` bigint(20) NOT NULL,
  `ID_compania` bigint(20) NOT NULL,
  `PurchaseNumber` varchar(20) NOT NULL,
  `VendorID` varchar(20) NOT NULL,
  `VendorName` varchar(39) NOT NULL,
  `AP_Account` varchar(15) NOT NULL,
  `Date` date NOT NULL,
  `Subtotal` decimal(16,2) NOT NULL,
  `Net_due` decimal(16,2) NOT NULL,
  `Export_date` varchar(20) NOT NULL,
  `Enviado` tinyint(4) NOT NULL,
  `Error` tinyint(4) NOT NULL,
  `ErrorPT` varchar(1024) NOT NULL,
  `nota` varchar(1024) DEFAULT NULL,
  `USER` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Tabla de cabecera de transacciones de compra para importar a Sage50';

DROP TABLE IF EXISTS `PurOrdr_Detail_Exp`;
CREATE TABLE IF NOT EXISTS `PurOrdr_Detail_Exp` (
  `ID_compania` bigint(20) NOT NULL,
  `TransactionID` bigint(20) NOT NULL,
  `RowIndex` int(11) NOT NULL,
  `Item_id` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Description` varchar(255) NOT NULL,
  `GL_AccountID` varchar(15) NOT NULL,
  `Quantity` decimal(11,4) NOT NULL,
  `Unit_Price` decimal(16,4) NOT NULL,
  `NetLine` decimal(16,2) NOT NULL,
  `QuantityReceived` decimal(16,4) NOT NULL,
  `JobID` varchar(20) NOT NULL,
  `JobPhaseID` varchar(20) NOT NULL,
  `JobCostCodeID` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `PurOrdr_Header_Exp`;
CREATE TABLE IF NOT EXISTS `PurOrdr_Header_Exp` (
  `ID_compania` bigint(20) NOT NULL,
  `TransactionID` bigint(20) NOT NULL,
  `PurchaseOrderNumber` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `VendorID` varchar(20) NOT NULL,
  `VendorName` varchar(150) NOT NULL,
  `Date` datetime NOT NULL,
  `AccountID` varchar(20) NOT NULL,
  `CustomerSO` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Total` decimal(18,4) NOT NULL,
  `PO_Closed` tinyint(1) NOT NULL,
  `DropShip` tinyint(1) NOT NULL,
  `DropShipCustomerID` varchar(20) NOT NULL,
  `DropShipCustomerName` varchar(39) NOT NULL,
  `WorkflowAssignee` varchar(39) NOT NULL,
  `WorkflowNote` varchar(2000) NOT NULL,
  `WorkflowStatusName` varchar(32) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `reg_traslado`;
CREATE TABLE IF NOT EXISTS `reg_traslado` (
  `id` int(11) NOT NULL,
  `ID_compania` int(11) NOT NULL,
  `id_almacen_ini` varchar(45) DEFAULT NULL,
  `route_ini` varchar(45) DEFAULT NULL,
  `id_almacen_des` varchar(45) DEFAULT NULL,
  `route_des` varchar(45) DEFAULT NULL,
  `LAST_CHANGE` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_user` int(11) DEFAULT NULL,
  `ProductID` varchar(45) DEFAULT NULL,
  `lote` varchar(45) DEFAULT NULL,
  `qty` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `REQ_DETAIL`;
CREATE TABLE IF NOT EXISTS `REQ_DETAIL` (
  `ProductID` varchar(45) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `DESCRIPCION` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `CANTIDAD` decimal(18,2) NOT NULL,
  `UNIDAD` varchar(4) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `JOB` varchar(50) NOT NULL,
  `PHASE` varchar(50) NOT NULL,
  `CCOST` varchar(50) NOT NULL,
  `NO_REQ` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `ITEM_UNIQUE_NO` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `ID_compania` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `REQ_HEADER`;
CREATE TABLE IF NOT EXISTS `REQ_HEADER` (
  `ID` int(11) NOT NULL,
  `NO_REQ` varchar(25) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `ID_compania` int(11) NOT NULL,
  `NOTA` varchar(1024) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `USER` int(11) NOT NULL,
  `DATE` varchar(10) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `st_closed` int(11) DEFAULT '0',
  `desc_closed` varchar(255) DEFAULT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `REQ_QUOTA`;
CREATE TABLE IF NOT EXISTS `REQ_QUOTA` (
  `ID` int(11) NOT NULL,
  `NO_REQ` varchar(20) NOT NULL,
  `USER` int(11) NOT NULL,
  `ID_compania` int(11) NOT NULL,
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `REQ_RECEPT`;
CREATE TABLE IF NOT EXISTS `REQ_RECEPT` (
  `ID` int(11) NOT NULL,
  `NO_REQ` varchar(20) NOT NULL,
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ITEM` varchar(20) NOT NULL,
  `QTY` decimal(18,2) NOT NULL,
  `USER` int(11) NOT NULL,
  `ID_compania` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `SalesOrder_Detail_Exp`;
CREATE TABLE IF NOT EXISTS `SalesOrder_Detail_Exp` (
  `ID` int(11) NOT NULL,
  `ID_compania` bigint(20) NOT NULL,
  `TransactionID` bigint(20) NOT NULL,
  `RowIndex` int(2) NOT NULL,
  `Item_id` varchar(20) NOT NULL,
  `Description` varchar(160) NOT NULL,
  `GL_AccountID` varchar(15) NOT NULL,
  `Quantity` decimal(11,4) NOT NULL,
  `QuantityShipped` decimal(16,4) NOT NULL,
  `Unit_Price` decimal(16,4) NOT NULL,
  `NetLine` decimal(16,2) NOT NULL,
  `JobID` varchar(20) NOT NULL,
  `JobPhaseID` varchar(20) NOT NULL,
  `JobCostCodeID` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `SalesOrder_Detail_Imp`;
CREATE TABLE IF NOT EXISTS `SalesOrder_Detail_Imp` (
  `ID` int(11) NOT NULL,
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
  `REMARK` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `SalesOrder_Header_Exp`;
CREATE TABLE IF NOT EXISTS `SalesOrder_Header_Exp` (
  `ID_compania` bigint(20) NOT NULL,
  `TransactionID` bigint(20) NOT NULL,
  `SalesOrderNumber` varchar(20) NOT NULL,
  `CustomerID` varchar(20) NOT NULL,
  `CustomerName` varchar(39) NOT NULL,
  `Date` datetime NOT NULL,
  `Close_SO` tinyint(1) NOT NULL,
  `CustomerPO` varchar(20) NOT NULL,
  `Total` decimal(18,4) NOT NULL,
  `TaxID` varchar(8) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
DROP TRIGGER IF EXISTS `Closed_SalesOrder`;
DELIMITER $$
CREATE TRIGGER `Closed_SalesOrder` AFTER UPDATE ON `SalesOrder_Header_Exp`
 FOR EACH ROW BEGIN

DECLARE  SO_ID varchar(50);
DECLARE  CLOSED_ID INT ;


SET  SO_ID = (SELECT SalesOrderNumber
FROM  
SalesOrder_Header_Exp ORDER BY LAST_CHANGE desc limit 1);

SET  CLOSED_ID = (SELECT Close_SO
FROM  
SalesOrder_Header_Exp ORDER BY LAST_CHANGE desc limit 1);


IF (CLOSED_ID > 0)
THEN

UPDATE sale_pendding set status_pendding='0' where SaleOrderId=SO_ID;

END IF;


END
$$
DELIMITER ;

DROP TABLE IF EXISTS `SalesOrder_Header_Imp`;
CREATE TABLE IF NOT EXISTS `SalesOrder_Header_Imp` (
  `ID_compania` bigint(20) NOT NULL,
  `SalesOrderNumber` varchar(20) NOT NULL,
  `CustomerID` varchar(50) NOT NULL,
  `CustomerPO` varchar(20) DEFAULT NULL,
  `CustomerName` varchar(39) NOT NULL,
  `Subtotal` decimal(18,4) NOT NULL,
  `TaxID` varchar(8) NOT NULL,
  `OrderTax` decimal(18,4) NOT NULL,
  `Net_due` decimal(18,4) NOT NULL,
  `Export_date` varchar(50) DEFAULT NULL,
  `Enviado` tinyint(1) NOT NULL DEFAULT '0',
  `Error` tinyint(1) DEFAULT '0',
  `ErrorPT` varchar(1024) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  `user` varchar(45) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `saletax` int(11) NOT NULL,
  `tipo_licitacion` varchar(30) DEFAULT NULL,
  `entrega` varchar(30) DEFAULT NULL,
  `termino_pago` varchar(30) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `ShipToName` varchar(39) DEFAULT NULL,
  `ShipToAddressLine1` varchar(30) DEFAULT NULL,
  `ShipToAddressLine2` varchar(30) DEFAULT NULL,
  `ShipToCity` varchar(20) DEFAULT NULL,
  `ShipToState` varchar(2) DEFAULT NULL,
  `ShipToZip` varchar(12) DEFAULT NULL,
  `ShipToCountry` varchar(15) DEFAULT NULL,
  `SalesRepID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TRIGGER IF EXISTS `SalesOrder_OnError`;
DELIMITER $$
CREATE TRIGGER `SalesOrder_OnError` AFTER UPDATE ON `SalesOrder_Header_Imp`
 FOR EACH ROW BEGIN
DECLARE  ORDER_Error varchar(150);
DECLARE  ORDER_Number varchar(150);
DECLARE  QTY_Pennding  int;
DECLARE  QTY_current int;
DECLARE  NEW_QTY  int;
DECLARE  Location int;
DECLARE  No_Items  int;

SET  ORDER_Error = (SELECT Error
FROM  
SalesOrder_Header_Imp ORDER BY LAST_CHANGE desc limit 1);

IF (ORDER_Error > 0)
THEN

SET  ORDER_Number = (SELECT SalesOrderNumber
FROM  
SalesOrder_Header_Imp  ORDER BY LAST_CHANGE desc limit 1);


SET  No_Items = (SELECT COUNT(*)
FROM  
sale_pendding where SaleOrderid=ORDER_Number );


WHILE No_Items > 0 DO

SET  QTY_Pennding = (SELECT qty
FROM sale_pendding where SaleOrderId=ORDER_Number order by ID limit 1);

SET Location = (SELECT status_location_id
FROM  sale_pendding where SaleOrderId=ORDER_Number  order by ID limit 1);

SET QTY_current = (SELECT qty
FROM  status_location where id=Location);

SET NEW_QTY =  QTY_Pennding + QTY_current;

UPDATE status_location SET qty=NEW_QTY where id=Location;

DELETE FROM sale_pendding where SaleOrderId=ORDER_Number order by ID limit 1;

SET No_Items = No_Items - 1;
END WHILE;

END IF;
END
$$
DELIMITER ;

DROP TABLE IF EXISTS `Sales_Detail_Imp`;
CREATE TABLE IF NOT EXISTS `Sales_Detail_Imp` (
  `ID_compania` bigint(20) NOT NULL DEFAULT '0',
  `InvoiceNumber` varchar(20) NOT NULL,
  `Item_id` varchar(50) NOT NULL,
  `Description` varchar(50) NOT NULL,
  `Quantity` decimal(18,4) NOT NULL,
  `Unit_Price` decimal(18,4) NOT NULL,
  `Net_line` decimal(18,4) NOT NULL,
  `Taxable` tinyint(1) DEFAULT NULL,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Sales_Header_Imp`;
CREATE TABLE IF NOT EXISTS `Sales_Header_Imp` (
  `ID_compania` bigint(20) NOT NULL,
  `ID` int(11) NOT NULL,
  `InvoiceNumber` varchar(20) NOT NULL DEFAULT '',
  `CustomerID` varchar(50) NOT NULL,
  `CustomerName` varchar(50) NOT NULL,
  `Subtotal` decimal(18,4) NOT NULL,
  `TaxID` varchar(20) NOT NULL,
  `Net_due` decimal(18,4) NOT NULL,
  `Export_date` text NOT NULL,
  `Enviado` tinyint(1) DEFAULT '0',
  `Error` tinyint(1) DEFAULT '0',
  `ErrorPT` varchar(20) NOT NULL,
  `LAST_CHANGE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` int(11) NOT NULL,
  `date` varchar(10) NOT NULL,
  `saletax` int(11) NOT NULL,
  `OrderTax` decimal(18,4) NOT NULL,
  `SalesRepID` varchar(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TRIGGER IF EXISTS `Sales_OnError`;
DELIMITER $$
CREATE TRIGGER `Sales_OnError` AFTER UPDATE ON `Sales_Header_Imp`
 FOR EACH ROW begin
DECLARE  ORDER_Error varchar(150);
DECLARE  ORDER_Number varchar(150);
DECLARE  QTY_Pennding  int;
DECLARE  QTY_current int;
DECLARE  NEW_QTY  int;
DECLARE  Location int;
DECLARE  No_Items  int;

SET  ORDER_Error = (SELECT Error
FROM  
Sales_Header_Imp ORDER BY LAST_CHANGE desc limit 1);

IF (ORDER_Error > 0)
THEN

SET  ORDER_Number = (SELECT InvoiceNumber
FROM  
Sales_Header_Imp  ORDER BY LAST_CHANGE desc limit 1);


SET  No_Items = (SELECT COUNT(*)
FROM  
sale_fact_pendding where SaleOrderid=ORDER_Number );


WHILE No_Items > 0 DO

SET  QTY_Pennding = (SELECT qty
FROM sale_fact_pendding where SaleOrderId=ORDER_Number order by ID limit 1);

SET Location = (SELECT status_location_id
FROM  sale_fact_pendding where SaleOrderId=ORDER_Number  order by ID limit 1);

SET QTY_current = (SELECT qty
FROM  status_location where id=Location);

SET NEW_QTY =  QTY_Pennding + QTY_current;

UPDATE status_location SET qty=NEW_QTY where id=Location;

DELETE FROM sale_fact_pendding where SaleOrderId=ORDER_Number order by ID limit 1;

SET No_Items = No_Items - 1;
END WHILE;

END IF;
END
$$
DELIMITER ;

DROP TABLE IF EXISTS `sale_fact_pendding`;
CREATE TABLE IF NOT EXISTS `sale_fact_pendding` (
  `SaleOrderId` int(11) NOT NULL,
  `no_lote` varchar(45) DEFAULT NULL,
  `ProductID` varchar(45) DEFAULT NULL,
  `qty` varchar(45) DEFAULT NULL,
  `status_pendding` tinyint(4) DEFAULT NULL,
  `status_location_id` int(11) DEFAULT NULL,
  `last_change` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sale_pendding`;
CREATE TABLE IF NOT EXISTS `sale_pendding` (
  `SaleOrderId` varchar(50) NOT NULL,
  `no_lote` varchar(45) DEFAULT NULL,
  `ProductID` varchar(45) DEFAULT NULL,
  `qty` varchar(45) DEFAULT NULL,
  `status_pendding` tinyint(4) DEFAULT NULL,
  `status_location_id` int(11) DEFAULT NULL,
  `last_change` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ID` int(11) NOT NULL,
  `ID_compania` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sale_tax`;
CREATE TABLE IF NOT EXISTS `sale_tax` (
  `id` int(11) NOT NULL,
  `taxid` varchar(10) DEFAULT '1',
  `rate` int(11) DEFAULT '7'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sale_tax` (`id`, `taxid`, `rate`) VALUES
(3, 'ITBMS15%', 15),
(6, 'ITBMS', 7),
(7, 'EXENTO', 0);

DROP TABLE IF EXISTS `sax_company_sage`;
CREATE TABLE IF NOT EXISTS `sax_company_sage` (
  `ID` int(11) NOT NULL,
  `id_compania` int(11) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `onoff` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin2;

INSERT INTO `sax_company_sage` (`ID`, `id_compania`, `reference`, `onoff`) VALUES
(1, 1, 'Default', 1);

DROP TABLE IF EXISTS `SAX_USER`;
CREATE TABLE IF NOT EXISTS `SAX_USER` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `onoff` int(11) NOT NULL DEFAULT '1',
  `role` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `notif_oc` int(11) NOT NULL DEFAULT '0',
  `notif_fc` int(11) NOT NULL DEFAULT '0',
  `role_purc` int(11) NOT NULL,
  `role_fiel` int(11) NOT NULL DEFAULT '0',
  `mod_price` int(11) NOT NULL DEFAULT '0',
  `inv_view` int(11) NOT NULL DEFAULT '0',
  `rep_view` int(11) NOT NULL DEFAULT '0',
  `stoc_view` int(11) NOT NULL DEFAULT '0',
  `photo` VARCHAR(1) NULL DEFAULT NULL ,
  `last_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `SAX_USER` (`id`, `name`, `lastname`, `email`, `pass`, `onoff`, `role`, `notif_oc`, `notif_fc`, `role_purc`, `role_fiel`, `mod_price`, `last_login`) VALUES
(25, 'Reinaldo', 'Daou R', 'reinaldo.daou@gmail.com', '07298c6cae93ca9d5c0992818497fa87', 1, 'admin', 1, 1, 1, 1, 0, '2017-06-30 09:12:25'),
(28, 'user', 'user', 'user@daoutel.net', 'user', 0, 'user', 0, 0, 0, 0, 0, '2016-04-19 23:42:14'),
(29, 'Luis', 'Armuelles', 'larmuelles@apconconsulting.com', '3520a706a72f4efddd48d1a4081828bc', 1, 'admin', 0, 1, 1, 0, 0, '2017-06-30 04:45:19');

DROP TABLE IF EXISTS `status_location`;
CREATE TABLE IF NOT EXISTS `status_location` (
  `id` bigint(20) NOT NULL,
  `lote` varchar(45) NOT NULL,
  `id_product` varchar(45) DEFAULT NULL,
  `stock` varchar(45) DEFAULT NULL,
  `onoff` int(11) NOT NULL DEFAULT '1',
  `qty` decimal(18,4) DEFAULT NULL,
  `route` varchar(45) NOT NULL,
  `last_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ID_compania` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ubicaciones`;
CREATE TABLE IF NOT EXISTS `ubicaciones` (
  `id` int(11) NOT NULL,
  `etiqueta` varchar(45) NOT NULL,
  `id_almacen` varchar(45) NOT NULL,
  `onoff` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ubicaciones` (`id`, `etiqueta`, `id_almacen`, `onoff`) VALUES
(1, 'A1M0C0F0', '1', 1);

DROP TABLE IF EXISTS `UNIT_MES_CONVRT`;
CREATE TABLE IF NOT EXISTS `UNIT_MES_CONVRT` (
  `ID` int(11) NOT NULL,
  `UNIT` varchar(2) NOT NULL,
  `UNIT_TO_CONVERT` varchar(1) NOT NULL,
  `UNIT_NAME` varchar(3) NOT NULL,
  `FACTOR` decimal(18,6) NOT NULL,
  `ID_compania` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `UNIT_MES_CONVRT` (`ID`, `UNIT`, `UNIT_TO_CONVERT`, `UNIT_NAME`, `FACTOR`, `ID_compania`) VALUES
(1, 'LB', 'K', 'KG', '0.453592', 3);

DROP TABLE IF EXISTS `Vendors_Exp`;
CREATE TABLE IF NOT EXISTS `Vendors_Exp` (
  `ID_compania` int(11) NOT NULL,
  `VendorID` varchar(20) NOT NULL,
  `Name` varchar(39) DEFAULT NULL,
  `Contact` varchar(20) DEFAULT NULL,
  `Phone_Number1` varchar(20) DEFAULT NULL,
  `Email` varchar(64) DEFAULT NULL,
  `AddressLine1` varchar(30) DEFAULT NULL,
  `AddressLine2` varchar(30) DEFAULT NULL,
  `City` varchar(20) DEFAULT NULL,
  `State` varchar(2) DEFAULT NULL,
  `Zip` varchar(12) DEFAULT NULL,
  `Country` varchar(15) DEFAULT NULL,
  `IsActive` float DEFAULT NULL,
  `Custom_field1` varchar(40) DEFAULT NULL,
  `Custom_field2` varchar(40) DEFAULT NULL,
  `Custom_field3` varchar(40) DEFAULT NULL,
  `Custom_field4` varchar(40) DEFAULT NULL,
  `Custom_field5` varchar(40) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


ALTER TABLE `almacenes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `CompanySession`
  ADD PRIMARY KEY (`ID_compania`,`CompanyNameSage50`);

ALTER TABLE `company_info`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `CONF_SMTP`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `CON_HEADER`
  ADD PRIMARY KEY (`idReg`);

ALTER TABLE `CON_REG_TRAS`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `CTA_GL_CONF`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `Customers_Exp`
  ADD PRIMARY KEY (`ID`,`CustomerID`,`id_compania`);

ALTER TABLE `FAC_DET_CONF`
  ADD PRIMARY KEY (`ID`,`ID_compania`);

ALTER TABLE `InventoryAdjust_Imp`
  ADD PRIMARY KEY (`UniqueInvAdjID`,`ID_compania`);

ALTER TABLE `Jobs_Exp`
  ADD PRIMARY KEY (`ID_compania`,`JobID`,`ID`),
  ADD UNIQUE KEY `ID` (`ID`);

ALTER TABLE `Job_Cost_Codes_Exp`
  ADD PRIMARY KEY (`ID_compania`,`CostCodeID`);

ALTER TABLE `Job_Phases_Exp`
  ADD PRIMARY KEY (`ID_compania`,`PhaseID`);

ALTER TABLE `MOD_MENU_CONF`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `PO_FACT_LOG`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `PRI_LIST_ID`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Index 1` (`IDPRICE`);

ALTER TABLE `PRI_LIST_ITEM`
  ADD PRIMARY KEY (`IDPRICE`,`IDITEM`),
  ADD KEY `Index 1` (`IDPRICE`);

ALTER TABLE `Products_Exp`
  ADD PRIMARY KEY (`ID`,`id_compania`,`ProductID`);

ALTER TABLE `Prod_Lotes`
  ADD PRIMARY KEY (`ID`,`ProductID`,`no_lote`),
  ADD UNIQUE KEY `REG_KEY` (`REG_KEY`),
  ADD UNIQUE KEY `REG_KEY_2` (`REG_KEY`),
  ADD KEY `no_lote` (`no_lote`),
  ADD KEY `no_lote_UNIQUE` (`no_lote`);

ALTER TABLE `Purchase_Detail_Exp`
  ADD PRIMARY KEY (`ID_compania`,`PurchaseID`,`RowIndex`);

ALTER TABLE `Purchase_Detail_Imp`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `Purchase_Header_Exp`
  ADD PRIMARY KEY (`ID_compania`,`PurchaseID`,`PurchaseNumber`,`VendorID`);

ALTER TABLE `Purchase_Header_Imp`
  ADD PRIMARY KEY (`TransactionID`),
  ADD UNIQUE KEY `ID_compania_PurchaseNumber_VendorID` (`ID_compania`,`PurchaseNumber`,`VendorID`);

ALTER TABLE `PurOrdr_Detail_Exp`
  ADD PRIMARY KEY (`ID_compania`,`RowIndex`,`TransactionID`);

ALTER TABLE `PurOrdr_Header_Exp`
  ADD PRIMARY KEY (`TransactionID`);

ALTER TABLE `reg_traslado`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `REQ_DETAIL`
  ADD PRIMARY KEY (`NO_REQ`,`ITEM_UNIQUE_NO`),
  ADD KEY `REQ_NO` (`NO_REQ`);

ALTER TABLE `REQ_HEADER`
  ADD PRIMARY KEY (`ID`,`ID_compania`),
  ADD KEY `NO_REQ` (`NO_REQ`);

ALTER TABLE `REQ_QUOTA`
  ADD PRIMARY KEY (`ID`,`NO_REQ`,`ID_compania`);

ALTER TABLE `REQ_RECEPT`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `SalesOrder_Detail_Exp`
  ADD PRIMARY KEY (`ID`,`ID_compania`,`TransactionID`);

ALTER TABLE `SalesOrder_Detail_Imp`
  ADD PRIMARY KEY (`ID`,`ID_compania`,`SalesOrderNumber`);

ALTER TABLE `SalesOrder_Header_Exp`
  ADD PRIMARY KEY (`ID_compania`,`TransactionID`,`SalesOrderNumber`);

ALTER TABLE `SalesOrder_Header_Imp`
  ADD PRIMARY KEY (`ID`,`ID_compania`,`SalesOrderNumber`);

ALTER TABLE `Sales_Detail_Imp`
  ADD PRIMARY KEY (`ID`,`InvoiceNumber`,`ID_compania`),
  ADD KEY `ID_compania` (`ID_compania`);

ALTER TABLE `Sales_Header_Imp`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `InvoiceNumber` (`InvoiceNumber`),
  ADD UNIQUE KEY `ID_compania` (`ID_compania`,`InvoiceNumber`);

ALTER TABLE `sale_fact_pendding`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `no_lote` (`no_lote`);

ALTER TABLE `sale_pendding`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `no_lote` (`no_lote`);

ALTER TABLE `sale_tax`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sax_company_sage`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `status_location`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`id`,`etiqueta`);

ALTER TABLE `UNIT_MES_CONVRT`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `Vendors_Exp`
  ADD PRIMARY KEY (`ID_compania`,`VendorID`);


ALTER TABLE `almacenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `company_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `CONF_SMTP`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `CON_HEADER`
  MODIFY `idReg` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `CON_REG_TRAS`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `CTA_GL_CONF`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Customers_Exp`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `FAC_DET_CONF`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `InventoryAdjust_Imp`
  MODIFY `UniqueInvAdjID` bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Jobs_Exp`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `MOD_MENU_CONF`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `PO_FACT_LOG`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `PRI_LIST_ID`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Products_Exp`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Prod_Lotes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Purchase_Detail_Imp`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Purchase_Header_Imp`
  MODIFY `TransactionID` bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `reg_traslado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `REQ_HEADER`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `REQ_QUOTA`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `REQ_RECEPT`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `SalesOrder_Detail_Exp`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `SalesOrder_Detail_Imp`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `SalesOrder_Header_Imp`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Sales_Detail_Imp`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `Sales_Header_Imp`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `sale_fact_pendding`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `sale_pendding`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `sale_tax`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `sax_company_sage`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `status_location`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `ubicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `UNIT_MES_CONVRT`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE  `Sales_Representative_Exp` 
   ADD PRIMARY KEY (  `SalesRepID` ,  `ID_compania` ) ;
