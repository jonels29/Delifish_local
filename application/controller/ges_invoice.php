<?php 
//******************************************************************************
//Gestion de facturacion a modulo fiscal

class ges_invoice extends Controller
{

/*INIT LOAD VIEWS*/
public function Init(){


     $res = $this->model->verify_session();

            if($res=='0'){

                // load views
                require APP . 'view/_templates/header.php';
                require APP . 'view/_templates/panel.php';
                require APP . 'view/operaciones/ges_invoice.php';
                require APP . 'view/_templates/footer.php';

            }
              
      
    }

public function GenInvoice($id){


     $res = $this->model->verify_session();

            if($res=='0'){

                // load views
                require APP . 'view/_templates/header.php';
                require APP . 'view/_templates/panel.php';
                require APP . 'view/operaciones/ges_gen_invoice.php';
                require APP . 'view/_templates/footer.php';

            }
              
      
    }

/*END LOAD VIEWS*/


//LISTA DE SO ASOCIADAS EL CLIENTE
public function GetSoToAdd($ref){

$this->model->verify_session();
$list = '';

$custID = $this->model->Query_value('SalesOrder_Header_Imp','CustomerID','where SalesOrderNumber="'.$ref.'" and ID_compania="'.$this->model->id_compania.'";');


$QUERY = 'SELECT SalesOrderNumber 
            FROM SalesOrder_Header_Imp 
          WHERE  CustomerID="'.$custID.'" AND 
                 ID_compania="'.$this->model->id_compania.'" AND
                 SalesOrderNumber <> "'.$ref.'" AND 
                 EMITIDA <> "1"; ';

$SO = $this->model->Query($QUERY);

if($SO){
  $list .= '<option value=""  selected></option>';

  foreach ($SO as $key => $value) {
    $value = json_decode($value);

    $list .= '<option value="'.$value->{'SalesOrderNumber'}.'" >'.$value->{'SalesOrderNumber'}.'</option>';

  }

}else{

 $list = '<option value=""  selected disabled>No hay otras ordenes abiertas.</option>';

}



echo   $list;

}


public function SetSOEmit($ref,$refFusion){

$this->model->verify_session();

$columns = array( 'SalesOrderNumber' => $ref, 
                  'EMITIDA' => '1',
                  'FUSION'  => $refFusion);

$this->model->update('SalesOrder_Header_Imp' ,$columns, ' ID_compania="'.$this->model->id_compania.'"  and 
                                                             SalesOrderNumber="'.$ref.'"');


echo 1;

}

//LISTA DE IMPRESORA FISCAL 
public function getPrinterList(){

  return $this->model->Query('SELECT * FROM INV_PRINT_CONF');

}

//INFO DE IMPRESORA FISCAL POR ID
public function getPrinterById($id){
  
  $RES = '';

  $printer = $this->model->Query('SELECT * FROM INV_PRINT_CONF where ID ="'.$id.'"');


  if($printer){
    $printer = json_decode($printer[0]);
    $RES = $printer->{'SERIAL'};
  }else{

    $RES = 'FISCAL';
  }


return $RES;
}

//OBTERNER IMPRESORA SELECCIONADA PARA IMPRESION
public function GetPrinterSeleccted($id){


$printer = $this->model->Query_value('INVOICE_GEN_HEADER','printer','where SalesOrderNumber ="'.$id.'"');

return $printer;
}

//OBTERNER IMPRESORA default por usuer
public function GetUserDefaultPrinter(){

$this->model->verify_session();

$printerid = $this->model->Query_value('SAX_USER','printer','where id ="'.$this->model->active_user_id.'"');
$printer = $this->model->Query_value('INV_PRINT_CONF','SERIAL','where ID ="'.$printerid.'"');
return $printer;
}


/*Devuelve pedidos no facturados*/
public function GetOrdrToInvoice(){

    $sql = 'SELECT * 
              FROM SalesOrder_Header_Imp
              WHERE EMITIDA = "0" 
                AND ID_compania = "'.$this->model->id_compania.'"
              ORDER BY LAST_CHANGE desc ';

    $INVOICES = $this->model->Query($sql);

    return $INVOICES;
}

/*Devuelve pedidos no facturados por ID*/
public function GetOrdrHeaderById($id){

    $sql = 'SELECT * 
              FROM SalesOrder_Header_Imp
              WHERE EMITIDA = "0" 
                AND ID_compania = "'.$this->model->id_compania.'"
                AND SalesOrderNumber = "'.$id.'"';

    $ORDER = $this->model->Query($sql);

    return $ORDER;
}



/*Devuelve detalle de pedidos*/
public function GetOrdrDetail($id){

    $this->model->verify_session();

    $id_compania = $this->model->id_compania;

    $query ="SELECT * 
               FROM `SalesOrder_Detail_Imp`
              WHERE SalesOrder_Detail_Imp.SalesOrderNumber='".$id."' 
               and  SalesOrder_Detail_Imp.ID_compania='".$id_compania."' 
           ORDER BY SalesOrder_Detail_Imp.ItemOrd ASC ;";


    $ORDER= $this->model->Query($query);

    echo '<table id="example-12" class="table table-striped table-bordered" cellspacing="0"  >
          <thead>
            <tr>
              <th>Codigo</th>
              <th>Descripcion</th>
              <th>Cantidad</th>
              <th>Precio Unit.</th>
            </tr>
          </thead><tbody>';


    foreach ($ORDER as $datos) {

        $ORDER = json_decode($datos);

    echo  "<tr>
              <td>".$ORDER->{'Item_id'}."</td>
              <td>".$ORDER->{'Description'}."</td>
              <td class='numb' >".number_format($ORDER->{'Quantity'},4,'.',',')."</td>
              <td class='numb' >".$this->numberFormatPrecision($ORDER->{'Unit_Price'})."</td>

          </tr>";

      }

    echo '</tbody></table>

    <div style="float:right;" class="col-md-2">
    <a href="'.URL.'index.php?url=ges_ventas/ges_print_OrdEmpaque/'.$id.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
       <img  class="icon" src="img/Printer.png" />
      <span>Imprimir</span>
    </a>
    </div>

    <div style="float:right;" class="col-md-2">
    <a href="'.URL.'index.php?url=ges_invoice/GenInvoice/'.$id.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
       <img  class="icon" src="img/invoice.png" />
      <span>A Factura</span>
    </a>
    </div>

    </fieldset>';
}


/*Devuelve detalle de pedidos para facturar*/
public function GetOrdrDetail2Fusion($id){

 $this->model->verify_session();
 $id_compania = $this->model->id_compania;
 
 $table = '';

    $query ="SELECT * 
               FROM `SalesOrder_Detail_Imp`
              WHERE SalesOrder_Detail_Imp.SalesOrderNumber='".$id."' 
               and  SalesOrder_Detail_Imp.ID_compania='".$id_compania."' 
           ORDER BY SalesOrder_Detail_Imp.ItemOrd ASC ;";

    $detail= $this->model->Query($query);

    $table .= '<table id="DetailFusion" class="display table table-striped table-condensed table-bordered " cellspacing="0">
              <thead>
                <tr >
                  <th width="1%" class="text-center" ></th>
                  <th width="5%" >Item ID</th>
                  <th width="15%" class="text-center">Descripcion</th>
                  <th width="5%" class="text-center"> Gravable</th>
                  <th width="5%" class="text-center" >Ordenado</th>
                  <th width="5%" class="text-center" >Precio Unit.</th>                
                </tr>
              </thead>
              <tbody id="table_req" > ';
              $i = 1;
              foreach ($detail as $key => $value) {

                $ORDER = json_decode($value);

                $PRICE = $this->numberFormatPrecision($ORDER->{'Unit_Price'});

        $table .=  "<tr>
                    <td class='numb' ><input type='checkbox' id='check".$i."'/></td> 
                    <td>".$ORDER->{'Item_id'}."[".$ORDER->{'ID'}."]</td>
                    <td>".$ORDER->{'Description'}."</td>
                    <td>".$ORDER->{'Taxable'}."</td>
                    <td class='numb' id='qty".$i."' >".number_format($ORDER->{'Quantity'},5,'.',',')."</td>
                    <td class='numb' id='unitprice".$i."' >".$PRICE."</td>
                </tr>";
                  $i += 1;
            }
                
          $table .= '</tbody></table>';

 
echo $table;
}

/*Devuelve detalle de pedidos para facturar*/
public function GetOrdrDetail2Invoice($id){

 $this->model->verify_session();

    $id_compania = $this->model->id_compania;

    $query ="SELECT * 
               FROM `SalesOrder_Detail_Imp`
              WHERE SalesOrder_Detail_Imp.SalesOrderNumber='".$id."' 
               and  SalesOrder_Detail_Imp.ID_compania='".$id_compania."' 
           ORDER BY SalesOrder_Detail_Imp.ItemOrd ASC ;";

    $ORDER= $this->model->Query($query);
 
return $ORDER;
}


/*Innserta informacion de cabecera facturacion de pedidos/Ord. ventas*/
public function SetInvoiceHeader($id,$subtotal,$itbms,$total,$taxid,$notas,$printer){

  $this->model->verify_session();
  $id_compania = $this->model->id_compania;

  if($id){

   $columns = array( 'SalesOrderNumber' => $id,
                      'date' => date("Y-m-d H:i:s"),
                      'Total' => $total,
                      'Subtotal' => $subtotal,
                      'Itbms' => $itbms,
                      'TaxID' => $taxid,
                      'NOTAS' => $notas,
                      'ID_compania' => $id_compania,
                      'printer' => $printer );

    $this->model->insert('INVOICE_GEN_HEADER',$columns);


}

echo  $id;
}


/*Innserta informacion de detalle facturacion de pedidos/Ord. ventas*/
public function SetInvoiceDetail($id){

$this->model->verify_session();
$id_compania = $this->model->id_compania;

 
$RegLine = json_decode($_GET['Data']);

foreach ($RegLine as $key => $value) {

sleep(3);
  
if($value){ 
  
list($ItemDesc,$ItemID,$pos,$desp,$tot,$Unit_Price) = explode('@', $value);

   //REGISTROS POR FUSION   
   $AciRef = $this->get_string_between($ItemID,'(',')');
   $AciRef = trim($AciRef);

   if($AciRef !=''){

     list($Item,) = explode('[', $ItemID);
     $Item = trim($Item);


     $ID = $this->get_string_between($ItemID,'[',']');

     //UPDATE
     $columns1 = array( 'SalesOrderNumber'     => $id,
                                'ItemOrd'      => $pos,
                                'Description'  => $ItemDesc,
                                'INVOICED'     => '1' );



     $this->model->update('SalesOrder_Detail_Imp' ,$columns1, ' ID="'.$ID.'" ;');


     $ItemID = $Item;

   }else{

     list($Item,) = explode('[', $ItemID);
     $Item = trim($Item);

     //UPDATE position order
     $ID = $this->get_string_between($ItemID,'[',']');
     $this->model->update('SalesOrder_Detail_Imp' ,array( 'ItemOrd'  => $pos, 'Description'  => $ItemDesc , 'INVOICED'  => '1'  ), 'ID="'.$ID.'" ;');
     
     $ItemID = $Item;

   }

   $this->CheckError();
   

   $columns2 = array( 'SalesOrderNumber' => $id,
                      'ItemOrd'     => $pos,
                      'ItemID'      => $ItemID,
                      'Despachado'  => $desp,
                      'TotalLinea'  => $tot,
                      'UnitPrice'   => $Unit_Price,
                      'ID_compania' => $id_compania );
   

    $this->model->insert('INVOICE_GEN_DETAIL',$columns2);
    $this->CheckError();

  }


}


$result = 1;

echo $result;
}


//OBTENGO NUMERO DE REGISTROS POR ORDEN 
public function GetRegNum($id){

  $this->model->verify_session();
  $id_compania = $this->model->id_compania;

  $regnum = $this->model->Query_value('SalesOrder_Detail_Imp', 
                                                   'count(*)', 
                                       'where ID_compania = "'.$id_compania.'" 
                                        and SalesOrderNumber = "'.$id.'"');
return $regnum;
}

//CREO ARCHIVOS PARA FACTURACION FISCAL
public function GenInvoiceFiles($id){

  $this->model->verify_session();
  $id_compania = $this->model->id_compania;

 
  $this->CreateHeaderFile($id);
  $this->CreateDetailFile($id);

   //UPDATE
   $columns = array( 'EMITIDA' => 1);
   $this->model->update('SalesOrder_Header_Imp' ,$columns, ' ID_compania="'.$id_compania.'"  and SalesOrderNumber="'.$id.'"');
 



}

//CREO ARCHIVOS FACTI
private function CreateHeaderFile($id){

   $this->model->verify_session();
   $id_compania = $this->model->id_compania;

    $Cust = 'SELECT 
                CustomerID,
                ShipToName,
                ShipToAddressLine1,
                ShipToAddressLine2,
                ShipToCity, 
                ShipToZip, 
                ShipToCountry
                FROM SalesOrder_Header_Imp
                where 
                ID_compania = "'.$id_compania.'" 
                AND SalesOrderNumber = "'.$id.'"';   

     $Cust = $this->model->Query($Cust);
     $Cust = json_decode($Cust[0]);

     $CustName    = $Cust->{'ShipToName'};
     $CustAddress = $Cust->{'ShipToAddressLine1'}.' '.$Cust->{'ShipToAddressLine2'}.' '.$Cust->{'ShipToCountry'};
     $RUC = $this->model->Query_value('Customers_Exp','custom_field1','where CustomerID="'.$Cust->{'CustomerID'}.'" and ID_compania = "'.$id_compania.'"');
     $DV  = $this->model->Query_value('Customers_Exp','custom_field2','where CustomerID="'.$Cust->{'CustomerID'}.'" ID_compania = "'.$id_compania.'"');
    

    

     $Fact = 'SELECT 
                    Total,
                    TipoPago,
                    NOTAS
                    FROM INVOICE_GEN_HEADER
                    where 
                    ID_compania = "'.$id_compania.'" 
                    AND SalesOrderNumber = "'.$id.'"';   

      $Fact = $this->model->Query($Fact);
      $Fact = json_decode($Fact[0]);

      $Total  = $this->numberFormatPrecision($Fact->{'Total'});
      $TyPago = $Fact->{'TipoPago'};
      $NOTAS  = $Fact->{'NOTAS'};

        switch ($TyPago) {
           case '1':      
            $EFECTIVO = $Total;
            break;

          case '2':
            $CHEQUE = $Total;
            break;

          case '3':
            $OTROS = $Total;
            break;
          
          default:
            $OTROS = $Total;
            break;
        }

   $DATA = 'FACTI'.$id.'|'.  //NO DOC
            $CustName.'|'.   //NONMBRE CLIENTE
            $RUC.'|'.        //RUC
            $CustAddress.'|'.//Direccion
            '|'.             //Total Descuento
            $Total.'|'.      //Total pagos
            $Total.'|'.      //Total final
            '0.00|'.         //Recargos
            '0.00|'.         //% recargos
            $EFECTIVO.'|'.   //Efectivo
            $CHEQUE.'|'.     //Cheque
            '0.00|'.         //TDC
            '0.00|'.         //TDD
            '0.00|'.         //Nota de credito
            $OTROS.'|'.      //Otra forma de pago
            $DV.'|'.         //DV
            $NOTAS .'-'.$CustAddress;    //NOTAS
                     

    $filename = 'INVOICE/IN/FACTI'.$id.'.txt';
    file_put_contents($filename , $DATA);



//NEW BLOQUE
$PRINTER = $this->GetPrinterSeleccted($id);

$DIR = "FISCAL/".$PRINTER."/IN/";

if (!file_exists($DIR)) {

    mkdir($DIR, 0777, true);

}

$filename = $DIR."FACTI".$id.'.txt';

file_put_contents($filename , $DATA);


}

//CREO ARCHIVOS FACMV
private function CreateDetailFile($id){

   $this->model->verify_session();
   $id_compania = $this->model->id_compania;
    
   $DATA = '';

   $RegLine = 'SELECT 
                     ItemOrd,
                     ItemID,
                     Despachado,
                     TotalLinea,
                     UnitPrice
               FROM  INVOICE_GEN_DETAIL
              WHERE  ID_compania = "'.$id_compania.'" 
                     AND SalesOrderNumber = "'.$id.'"';

    $RegLine = $this->model->Query($RegLine);

    foreach ($RegLine as  $value) {

    $value = json_decode($value);

    $PosItem = $value->{'ItemOrd'};
    $ItemID  = $value->{'ItemID'};
    $QTY     = $value->{'Despachado'};
    $PRICE   = $value->{'UnitPrice'};
    $TaxType = $this->model->Query_value('Products_Exp','TaxType','Where ID_compania = "'.$id_compania.'" and ProductID = "'.$ItemID.'"');
    $Desc    = $this->model->Query_value('Products_Exp','Description','Where ID_compania = "'.$id_compania.'" and ProductID = "'.$ItemID.'"');
    $DesFact = $this->model->Query_value('SalesOrder_Detail_Imp','Description','Where ID_compania = "'.$id_compania.'" and SalesOrderNumber = "'.$id.'" and  Item_id = "'.$ItemID.'" and ItemOrd="'.$PosItem.'"');
    
    $Unit = $this->get_string_between($DesFact,'(',')');

    if( $TaxType == '1'){

      $TaxID = $this->model->Query_value('INVOICE_GEN_HEADER','TaxID','Where ID_compania = "'.$id_compania.'" and SalesOrderNumber = "'.$id.'"');
      $ITBMS =  $TaxID;
      
    }else{
      
      $ITBMS = '0.00';

    }


   $DATA .= 'FACMV'.$id.'|'. //NO DOC
             $ItemID.'|'.    //Codigo
             $DesFact.'|'.   //Nombre Articulo
             $Unit.'|'.      //Unidad de venta
             $QTY.'|'.       //Cantidad
             $this->numberFormatPrecision($PRICE).'|'.     //Precio neto unitario
             $ITBMS."\n";    //Alicuota ITBMS
    }


$filename = 'INVOICE/IN/FACMV'.$id.'.txt';
file_put_contents($filename , $DATA);


//NUEVO BLOQUE
$PRINTER = $this->GetPrinterSeleccted($id);

$DIR = "FISCAL/".$PRINTER."/IN/";

if (!file_exists($DIR)) {

    mkdir($DIR, 0777, true);

}

$filename = $DIR."FACMV".$id.'.txt';

file_put_contents($filename , $DATA);

      
}






//Ver config de dropbox
public function GetDropboxConfig(){

require_once "dropbox-sdk/Dropbox/autoload.php";

$accessToken = file_get_contents('dropbox-sdk/atoken.txt');

$dbxClient = new Dropbox\Client($accessToken, "PHP-Example/1.0");
$accountInfo = $dbxClient->getAccountInfo();

  foreach ($accountInfo as $key => $value) {

    echo '<strong>'.$key.'</strong> : '.$value."</br>";
   
  }

}



public function ReadInvoiceFile($id_compania){


  $logText = '';
  $bdres = '';



  $SQL = 'SELECT SalesOrderNumber , printer
            FROM 
            INVOICE_GEN_HEADER
            Where 
            ID_compania = "'.$id_compania.'" 
            AND InvoiceNumber IS NULL ';

  $res = $this->model->Query($SQL);

  foreach ($res as $key => $value) {
   
      $value = json_decode($value);

      $ID = $value->{'SalesOrderNumber'};
      $folder = $value->{'printer'};

        //NUEVO BLOQUE
      $PRINTER = $this->GetPrinterSeleccted($ID);

        $DIR = "FISCAL/".$PRINTER."/OUT/";
        $filename = $DIR.'OUT_FACTI'.$ID.'.TXT';

        if (file_exists($filename)) {
  
         $InvNum  = $this->InsertSalesInfo($id_compania,trim($ID));
          
          if( $InvNum!='-'){

             $logText .= $this->model->GetLocalTime(date('Y-m-d H:i:s')).' InvoiceNumber : '.$InvNum.' -  SalesOrderNumber:'.$ID.' File: '.$filename."<br>\n";
          
          }

        }
        

  }

  file_put_contents('webhook_log.txt',  $logText, FILE_APPEND);

}


//OBTENGO NUMERO DE SERIAL Y FACTURA
public function GetInvoiceNumber($ID){


  $PRINTER = $this->GetPrinterSeleccted($ID);
  $DIR = "FISCAL/".$PRINTER."/OUT/";

  $filename = $DIR.'OUT_FACTI'.$ID.'.TXT';
  $line = file_get_contents($filename);

  list(,,,,,,$FACTNO,$conse) = explode(chr(9), $line);


return $FACTNO.'-'.$conse;
}



//inserto informacion de SO en Sales par acontabilizacion en PT
public function InsertSalesInfo($id_compania,$ID){

//$this->model->verify_session();
//$id_compania = $this->model->id_compania;


    $SalesOrder = $this->model->Query('SELECT 
                                          *
                                        FROM  SalesOrder_Header_Imp 
                                       WHERE  ID_compania = "'.$id_compania.'" 
                                         AND SalesOrderNumber = "'.$ID.'"');

    foreach ($SalesOrder as $key => $value) {
      
     $value = json_decode($value);

     $CustomerID = $value->{'CustomerID'};
     $CustomerName = $value->{'CustomerName'};
    }


    $InvoiceInfo = $this->model->Query('SELECT 
                                          date,
                                          SubTotal,
                                          TaxID,
                                          Total
                                        FROM  INVOICE_GEN_HEADER 
                                       WHERE  ID_compania = "'.$id_compania.'" 
                                         AND SalesOrderNumber = "'.$ID.'"');

    foreach ($InvoiceInfo as $key => $value) {
      
     $value = json_decode($value);

     $Subtotal = $value->{'SubTotal'};
     $TaxID    = $value->{'TaxID'};
     $Total    = $value->{'Total'};
     $InvDate =  $value->{'date'};

    }


    $InvoiceNumber = $this->GetInvoiceNumber($ID);


    if ($InvoiceNumber != '-'){ 

    //SET HEADER
   $values = array(
    'ID_compania'=>$id_compania,  
    'InvoiceNumber'=>$InvoiceNumber,
    'CustomerID'  => $CustomerID,
    'CustomerName'=> $CustomerName,
    'Subtotal'=> $Subtotal,
    'TaxID'=>    $this->model->Query_value('sale_tax','taxid','Where rate="'.$TaxID.'";'),
    'Net_due'=>  $Total,
    'user'=>'00',
    'date'=>$InvDate,
    'saletax'=> $TaxID
    );


   $this->model->insert('Sales_Header_Imp',$values);




    $SalesOrderDetail = $this->model->Query('SELECT 
                                                *
                                              FROM  SalesOrder_Detail_Imp 
                                             WHERE  ID_compania = "'.$id_compania.'" 
                                               AND  SalesOrderNumber = "'.$ID.'" 
                                               AND  INVOICED = "1"');

    foreach ($SalesOrderDetail as $key => $sales) {
      
          $sales = json_decode($sales);


          $InvoiceDetail = $this->model->Query('SELECT 
                                                Despachado,
                                                TotalLinea,
                                                UnitPrice
                                              FROM  INVOICE_GEN_DETAIL 
                                             WHERE  ID_compania = "'.$id_compania.'" 
                                               AND SalesOrderNumber = "'.$ID.'"
                                               AND ItemOrd = "'.$sales->{'ItemOrd'}.'"');

          foreach ($InvoiceDetail as $key => $value) {
            
           $value = json_decode($value);

           $qty = $value->{'Despachado'};
           $unitPrice    = $value->{'UnitPrice'};
           $Total    = $value->{'TotalLinea'};

          }


          $Unit = $this->get_string_between($sales->{'Description'},'(',')');

              //para sales order viejas que los id vienen vacio
              if($sales->{'Item_id'}==''){

                 list($itemid) = explode('(', $sales->{'Description'});
                
                 $itemid = trim($itemid); 
               
              }else{

                $itemid = $sales->{'Item_id'};
              }

            if($Unit=='KG'){
              
              $Description =    trim($sales->{'Description'});

              //INVENTORYADJUNT SI SE FACTURO EN KILOS
              $factor  = $this->model->Query_value('UNIT_MES_CONVRT','FACTOR','WHERE UNIT="L" and UNIT_TO_CONVERT="K" and ID_compania="'.$id_compania.'"');
              $no_cover_qty =  $qty / $factor;
              $no_cover_qty = (-1)*$no_cover_qty;

              $LastUnitCost = $this->model->Query_value('Products_Exp','LastUnitCost','Where ProductID="'.$itemid.'" and id_compania="'.$id_compania.'";');
              $UnitCost = $no_cover_qty *$LastUnitCost;

               $valuesInvAd = array(
                'ItemID' => $itemid,
                'ID_compania' => $id_compania,
                'Reference' => $ID,
                'ReasonToAdjust' => $InvoiceNumber,
                'Account' =>  $this->model->Query_value('CTA_GL_CONF','GLACCT','where ID_compania="'.$id_compania.'";'),
                'Quantity' => $no_cover_qty ,
                'USER' => '00',
                'UnitCost' => $UnitCost,
                'Date' => $InvDate,
                'location_id' => $this->model->Query_value('status_location','id','where lote="'.$itemid.'0000" and id_product="'.$itemid.'" and route="1" and ID_compania="'.$id_compania.'"')
                );

               $this->model->insert('InventoryAdjust_Imp',$valuesInvAd);
               
               $desItem = $itemid;
               $Description =    $desItem.'-'.trim($sales->{'Description'});
               $itemid = '';

            }else{

               $Description =    trim($sales->{'Description'});

            }
      
          //SET DETAIL
          $values1 = array(
                'ID_compania'=>$id_compania,
                'invoiceNumber'=>$InvoiceNumber,
                'Item_id'=>$itemid,
                'Description'=> $Description,
                'Quantity'=>$qty,
                'Unit_Price'=>$unitPrice,
                'Net_line'=>$Total,
                'Taxable'=>$sales->{'Taxable'});

           $this->model->insert('Sales_Detail_Imp',$values1); //set item line
       }

    //Inserto numero de factura generada por maquina fiscal
    $this->model->Query('UPDATE INVOICE_GEN_HEADER SET InvoiceNumber="'.$InvoiceNumber.'" WHERE SalesOrderNumber="'.$ID.'" and ID_compania="'.$id_compania.'"');

 }

return $InvoiceNumber;
}





//EXTRAE STRING ENTRE DOS CARACTERES
private function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}


//PRECISION 2 DECIMALES SIN REDONDEO 
private function numberFormatPrecision($number, $precision = 2, $separator = '.')
{
    $numberParts = explode($separator, $number);
    $response = $numberParts[0];
    if(count($numberParts)>1){
        $response .= $separator;
        $response .= substr($numberParts[1], 0, $precision);
    }
    return $response;
}


public function CheckError(){


  $CHK_ERROR =  $this->model->read_db_error();
  

  if ($CHK_ERROR!=''){ 

   die("<script>$(window).load(  
        function(){   
          $('#ErrorModal').modal('show');
          $('#ErrorMsg').html('".$CHK_ERROR."');
         }
       );</script>"); 

  }

}

}//fin clase
?>
