<?PHP

class ges_notasdecredito extends Controller
{


public function Init(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_notasdecredito.php';
            require APP . 'view/_templates/footer.php';


        }
 
}


public function ges_hist_creditmemo(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_hist_creditmemo.php';
            require APP . 'view/_templates/footer.php';


        }
 
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


$printer = $this->model->Query_value('CREDITNOTE_HEADER','printer','where CreditNoteNumber ="'.$id.'"');

return $printer;
}


//OBTERNER IMPRESORA default por usuer
public function GetUserDefaultPrinter(){

$this->model->verify_session();

$printerid = $this->model->Query_value('SAX_USER','printer','where id ="'.$this->model->active_user_id.'"');

$printer = $this->model->Query_value('INV_PRINT_CONF','SERIAL','where ID ="'.$printerid.'"');


return $printer;
}



public function GetCreditMemo($sort,$limit,$date1,$date2){

$this->model->verify_session();

$clause='';

$clause.= 'where CREDITNOTE_HEADER.ID_compania="'.$this->model->id_compania.'" ';



if($date1!=''){

   if($date2!=''){

      $clause.= 'and  date between "'.$date1.'" and "'.$date2.'" ';           
    }
   
   if($date2==''){ 

     $clause.= 'and date="'.$date1.'"';
   }
     
}



if($this->model->active_user_role=='admin'){

 $query ='SELECT * FROM `CREDITNOTE_HEADER` 
inner JOIN `CREDITNOTE_DETAIL` ON CREDITNOTE_HEADER.CreditNoteNumber = CREDITNOTE_DETAIL.CreditNoteNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = CREDITNOTE_HEADER.user '.$clause.' GROUP BY CREDITNOTE_HEADER.CreditNoteNumber order by CREDITNOTE_HEADER.LAST_CHANGE '.$sort.' limit '.$limit ; }

if($this->model->active_user_role=='user'){

  if($clause!=''){ $clause.= 'and `SAX_USER`.`id`="'.$this->model->active_user_role_id.'"'; } else{ $clause.= ' Where `SAX_USER`.`id`="'.$this->active_user_role_id.'"'; }

$query='SELECT * FROM `CREDITNOTE_HEADER`
inner JOIN `CREDITNOTE_DETAIL` ON CREDITNOTE_HEADER.CreditNoteNumber = CREDITNOTE_DETAIL.CreditNoteNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = CREDITNOTE_HEADER.user '.$clause.' GROUP BY CREDITNOTE_HEADER.CreditNoteNumber  order by CREDITNOTE_HEADER.LAST_CHANGE '.$sort.' limit '.$limit;

}

       
$table.= '<script type="text/javascript">
  jQuery(document).ready(function($)
  { 
   var table = $("#table_report").dataTable({
      
      aLengthMenu: [
         [25, 50, 100, 500 , -1], [25, 50, 100 , 500 , "All"]
        ],
      responsive: false,
      dom: "Blfrtip",
      bSort: true,
      bPaginate: true,
      select:true,

        buttons: [

          {

          extend: "excelHtml5",
          text: "Exportar",
          title: "Reporte_ventas",
           
          exportOptions: {

                columns: ":visible",

                 format: {
 
                    header: function ( data ) {

                      var StrPos = data.indexOf("<div");

                        if (StrPos<=0){
                          
                          var ExpDataHeader = data;

                        }else{
                       
                          var ExpDataHeader = data.substr(0, StrPos); 

                        }
                       
                      return ExpDataHeader;
                      }
                    }
                 
                  }               

          },

          {

          extend:  "colvis",

          text: "Seleccionar",

          columns: ":gt(0)"           

         },

         {

          extend: "colvisGroup",
          text: "Ninguno",
          show: [0],
          hide: [":gt(0)"]

          },

          {

            extend: "colvisGroup",
            text: "Todo",
            show: ["*"]

          }

          ]

   

    });

table.yadcf([
{column_number : 0,
 column_data_type: "html",
 html_data_type: "text" ,
 select_type: "select2",
 select_type_options: { width: "100%" }},
 
{column_number : 1,
 select_type: "select2",
 select_type_options: { width: "100%" }},

{column_number : 2,
 select_type: "select2",
 select_type_options: { width: "100%" }},

{column_number : 3 ,
 select_type: "select2",
 select_type_options: { width: "100%" }},
{column_number : 4,
 select_type: "select2",
 select_type_options: { width: "100%" }},

{column_number : 5,
 select_type: "select2",
 select_type_options: { width: "100%" }},

 {column_number : 6,
 select_type: "select2",
 select_type_options: { width: "100%" }},

 {column_number : 7,
 select_type: "select2",
 select_type_options: { width: "100%" }}
],
{cumulative_filtering: true, 
filter_reset_button_text: false});
   
});

</script>
  <table id="table_report" class="tableReport table table-striped table-bordered" cellspacing="0"  >
    <thead>
      <tr>
        <th>No. Nota </th>
        <th>No. Factura Fiscal </th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Total Venta</th>
        <th>Procesado por:</th>
        <th>Estado PT</th>
        <th>Impresa</th>
      </tr>
    </thead>';



$filter =  $this->model->Query($query);

$URL ='"'.URL.'"';

foreach ($filter as $datos) {

  $filter = json_decode($datos);

$status= '';
$style = '';


  $ID ='"'.$filter->{'CreditNoteNumber'}.'"';

   

$Emitida = $this->model->Query_value('CREDITNOTE_HEADER','EMITIDA','Where CreditNoteNumber="'.$filter->{'CreditNoteNumber'}.'" and  ID_compania="'.$this->model->id_compania.'" ');


$ERROR = $this->model->Query_value('Customer_Credit_Memo_Header_Imp','Error','Where CreditNumber="'.$filter->{'GenCreditNumber'}.'" and  ID_compania="'.$this->model->id_compania.'" ');
$ENV = $this->model->Query_value('Customer_Credit_Memo_Header_Imp','Enviado','Where CreditNumber="'.$filter->{'GenCreditNumber'}.'" and  ID_compania="'.$this->model->id_compania.'" ');
$ERRORPT = $this->model->Query_value('Customer_Credit_Memo_Header_Imp','ErrorPT','Where CreditNumber="'.$filter->{'GenCreditNumber'}.'" and  ID_compania="'.$this->model->id_compania.'" ');

if($filter->{'GenCreditNumber'} ==''){ $apro = 'NO';  $apro_style="style='color:orange; font-style:bold;'";  }

if($filter->{'GenCreditNumber'} != '' ){ $apro = 'SI'; $apro_style="style='color:green; font-style:bold;'"; }



$user = $this->model->Get_User_Info($filter->{'user'}); 

foreach ($user as $value) {
$value = json_decode($value);
$name= $value->{'name'};
$lastname = $value->{'lastname'};
}


 if($ERROR ==1) { 

     $status= "Error : ".$ERRORPT;
     $style="style='color:red; font-style:bold;'"; 


   } else{

    if($ENV==0){

      $style="style='color:orange; font-style:bold;'"; 
      $status='No sincronizado';

       }else{ 

         $status= "Enviado";
         $style="style='color:green; font-style:bold;'";

       }   

    }

$table.= "<tr>

    <td ><a href='#' onclick='javascript: show_creditmemo(".$URL.",".$ID."); ' >".$filter->{'CreditNoteNumber'}."</a></td>
    <td >".$filter->{'GenCreditNumber'}."</td>
    <td class='numb' >".$filter->{'date'}."</td>
    <td >".$filter->{'CustomerName'}.'</td>
    <td class="numb" >'.$this->numberFormatPrecision($filter->{'Net_due'}).'</td>
    <td >'.$name.' '.$lastname.'</td>
    <td '.$style.'>'.$status.'</td>
    <td '.$apro_style.'>'.$apro."</td>
   </tr>";

$apr = '';
}

$table.= '</table>';

echo $table;

}

public function  GetCreditMemoInfo($id){



$this->model->verify_session();

$id_compania= $this->model->id_compania;

$query ="SELECT * FROM `CREDITNOTE_HEADER`
inner JOIN `CREDITNOTE_DETAIL` ON CREDITNOTE_HEADER.CreditNoteNumber = CREDITNOTE_DETAIL.CreditNoteNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = CREDITNOTE_HEADER.user
WHERE CREDITNOTE_HEADER.CreditNoteNumber='".$id."' and CREDITNOTE_HEADER.ID_compania='".$id_compania."'  GROUP BY CREDITNOTE_DETAIL.CreditNoteNumber ";

//inner JOIN Products_Exp ON Products_Exp.ProductID = CREDITNOTE_DETAIL.item_id


$ORDER_detail= $this->model->Query($query);


echo '<br/><br/><fieldset><legend>Detalle de Nota de credito</legend><table class="table table-striped table-bordered" cellspacing="0"  ><tr>';

  foreach ($ORDER_detail as $datos) {
    $ORDER_detail = json_decode($datos);



    echo "<tr><th class='columnHdr'><strong>No. Nota</strong></th><td class='columnHdr InfsalesTd order'>".$ORDER_detail->{'CreditNoteNumber'}."</td></tr>
          <tr><th class='columnHdr'><strong>No. Factura Fiscal</strong></th><td class='columnHdr InfsalesTd order'>".$ORDER_detail->{'InvoiceNumber'}."</td></tr>
          <tr><th class='columnHdr'><strong>Fecha</strong></th><td class='columnHdr InfsalesTd '>".$ORDER_detail->{'date'}."</td></tr>
          <tr><th class='columnHdr'><strong>Cliente</strong></th><td class='columnHdr InfsalesTd'>".$ORDER_detail->{'CustomerName'}."</td></tr>
          <tr><th class='columnHdr' ><strong>Total </strong></th><td class='columnHdr InfsalesTd '>".$this->numberFormatPrecision($ORDER_detail->{'Net_due'})."</td></tr>
          <tr><th class='columnHdr'><strong>Vendedor</strong></th><td class='columnHdr InfsalesTd'>".$ORDER_detail->{'name'}.' '.$ORDER_detail->{'lastname'}."</td></tr>";

}


echo "</tr></table>";



$query ="SELECT * FROM `CREDITNOTE_DETAIL`
WHERE CREDITNOTE_DETAIL.CreditNoteNumber='".$id."' and  CREDITNOTE_DETAIL.ID_compania='".$id_compania."' ORDER BY CREDITNOTE_DETAIL.ItemOrd ASC ;";


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

    $id= "'".$ORDER_detail->{'CreditNoteNumber'}."'";


echo  "<tr>
          <td>".$ORDER->{'Item_id'}."</td>
          <td>".$ORDER->{'Description'}."</td>
          <td class='numb' >".number_format($ORDER->{'Quantity'},4,'.',',')."</td>
          <td class='numb' >".$this->numberFormatPrecision($ORDER->{'Unit_Price'})."</td>

      </tr>";

  }

echo '</tbody></table>



</fieldset>';

/*<div style="float:right;" class="col-md-2">
<a href="'.URL.'index.php?url=ges_ventas/ges_print_salesorder/'.$ORDER_detail->{'CreditNoteNumber'}.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
   <img  class="icon" src="img/Printer.png" />
  <span>Imprimir</span>
</a>
</div>*/
}


public function SetCreditNoteHeader($CustomerID,$Subtotal,$TaxID,$Net_due,$user,$nopo,$pago,$licitacion,$observaciones,$entrega,$ordertax,$fecha_entrega,$noinvoice,$printer){
$this->model->verify_session();

$id_compania = $this->model->id_compania;

$CreditNoteNumber = $this->model->Get_NC_No();


$custinfo = $this->model->get_Cust_info_int($CustomerID);
$custinfo = json_decode($custinfo);

//$created = strtotime($this->model->GetLocalTime($created)); date('H:i:s',$created);  


$date = strtotime($this->model->GetLocalTime(date("Y-m-d")));
$date = date("Y-m-d",$date);

$values = array(
'ID_compania'=>$this->model->id_compania,
'CreditNoteNumber'=> $CreditNoteNumber,
'CustomerID'=>  $this->model->Query_value('Customers_Exp','CustomerID','Where ID="'.$CustomerID.'" AND id_compania="'.$id_compania.'" ;'),
'CustomerName'=>$this->model->Query_value('Customers_Exp','Customer_Bill_Name','Where ID="'.$CustomerID.'" AND id_compania="'.$id_compania.'" ;'),
'Subtotal'=>$Subtotal,
'TaxID'=>$TaxID,
'OrderTax' => $ordertax,
'Net_due'=>$Net_due,
'user'=>$this->model->active_user_id,
'date'=>$date,
'saletax'=>'0',
'CustomerPO' => $nopo,
'tipo_licitacion' => $licitacion,
'entrega' => $entrega,
'termino_pago' => $pago,
'observaciones' => $observaciones,
'ShipToName' => $this->model->Query_value('Customers_Exp','CustomerID','Where ID="'.$CustomerID.'" AND id_compania="'.$id_compania.'" ;').'-'.$custinfo->{'Customer_Bill_Name'},
'ShipToAddressLine1' => $custinfo->{'AddressLine1'},
'ShipToAddressLine2' => $custinfo->{'AddressLine2'},
'ShipToCity' => $custinfo->{'City'},
'ShipToState' => $custinfo->{'State'},
'ShipToZip' => $custinfo->{'Zip'},
'ShipToCountry' => $custinfo->{'Country'},
'fecha_entrega' => $fecha_entrega,
'InvoiceNumber' => $noinvoice,
'printer' => $printer);

$this->model->insert('CREDITNOTE_HEADER',$values);

$this->CheckError();

echo $CreditNoteNumber ;

}


public function GetPayTerm($ID){

  $this->model->verify_session();
  $id_compania = $this->model->id_compania;

  $CustID = $this->model->Query_value('Customers_Exp','CustomerID','WHERE ID = "'.$ID.'" AND ID_compania="'.$id_compania.'"'); 


  $DaysToPay = $this->model->Query_value('CUST_PAY_TERM','DaysToPay','WHERE CustomerID = "'.$CustID.'" AND ID_compania="'.$id_compania.'"'); 

  echo $DaysToPay;
}

public function CheckError(){


  $CHK_ERROR =  $this->model->read_db_error();
  

  if ($CHK_ERROR!=''){ 

   die("<script>$(window).load(  
        function(){   
          MSG_ERROR('".$CHK_ERROR."',0);
         }
       );</script>"); 

  }

}


public function SetCreditNoteDetail($CreditNoteNumber){

$this->model->verify_session();

$id_compania= $this->model->id_compania;
$id_user_active= $this->model->active_user_id ;

$data = json_decode($_GET['Data']);

foreach ($data as $key => $value) {

if($value){

list($desc,$remarks,$UnitMeasure,$itemid,$unit_price,$qty,$Price,$chi,$gra ) = explode('@', $value );

$no_cover_qty = $qty;
$no_cover_uni = $UnitMeasure;
$no_cover_pri = $unit_price;

$custid  = $this->model->Query_value('CREDITNOTE_HEADER','CustomerID','WHERE CreditNoteNumber="'.$CreditNoteNumber.'" and ID_compania="'.$id_compania.'"');
$UNIT_TO_CONVERT = $this->model->Query_value('Customers_Exp','Custom_field5','WHERE CustomerID="'.$custid.'" and id_compania="'.$id_compania.'"');
$factor  = $this->model->Query_value('UNIT_MES_CONVRT','FACTOR','WHERE UNIT="'.$UnitMeasure.'" and UNIT_TO_CONVERT="'.$UNIT_TO_CONVERT.'" and ID_compania="'.$id_compania.'"');


  if($factor!=''){
   
    $unit_price =   $unit_price / $factor;
    $qty = $qty * $factor;
    $Price =  $unit_price * $qty;

    $UnitMeasure     = $this->model->Query_value('UNIT_MES_CONVRT','UNIT_NAME','WHERE UNIT="'.$UnitMeasure.'" AND UNIT_TO_CONVERT="'.$UNIT_TO_CONVERT.'" and ID_compania="'.$id_compania.'"');


      //EN CASO QUE NO SE HAGA CONVERSION DE UNIDDES ESCRIBE EN LA TABLA DE SALES ORDER DETAIL SIN INDICAR EL ITEMID. 
      $values1 = array(
          'ItemOrd' => $key ,
          'ID_compania'=>$id_compania,
          'CreditNoteNumber'=>$CreditNoteNumber,
          'Item_id'=> $itemid,
          'Description'=> '('.$UnitMeasure.') '.$desc.' '.$remarks,
          'REMARK'=>$remarks,
          'Quantity'=>$qty,
          'Unit_Price'=>$unit_price,
          'Net_line'=>$Price,
          'PK_CHICO'=>$chi,
          'PK_GRANDE'=>$gra,
          'Taxable'=>$this->model->Query_value('Products_Exp','TaxType','Where ProductID="'.$itemid.'" and ID_compania="'.$id_compania.'";') );

      $this->model->insert('CREDITNOTE_DETAIL',$values1); //set item line
      $this->CheckError();

      $factor = '';
   }else{

      //EN CASO QUE NO SE HAGA CONVERSION DE UNIDDES ESCRIBE EN LA TABLA DE SALES ORDER DETAIL INDICANDO EL ITEMID. 
      $values1 = array(
          'ItemOrd' => $key ,
          'ID_compania'=>$id_compania,
          'CreditNoteNumber'=>$CreditNoteNumber,
          'Item_id'=>$itemid,
          'Description'=> '('.$UnitMeasure.') '.$desc.' '.$remarks,
          'REMARK'=>$remarks,
          'Quantity'=>$qty,
          'Unit_Price'=>$unit_price,
          'Net_line'=>$Price,
           'PK_CHICO'=>$chi,
          'PK_GRANDE'=>$gra,
          'Taxable'=>$this->model->Query_value('Products_Exp','TaxType','Where ProductID="'.$itemid.'" and ID_compania="'.$id_compania.'";') );



      $this->model->insert('CREDITNOTE_DETAIL',$values1); //set item line
      $this->CheckError();

   }


 }
}
echo '1';

}




public function CreateCreditMemoFile($id){

  $this->model->verify_session();
  $id_compania = $this->model->id_compania;

  $this->CreateHeaderFile($id);
  $this->CreateDetailFile($id);


    //UPDATE
    $columns = array( 'EMITIDA' => 1);
    $this->model->update('CREDITNOTE_HEADER' ,$columns, ' ID_compania="'.$id_compania.'"  and CreditNoteNumber="'.$id.'"');


  
}



//CREO ARCHIVOS FACTI
private function CreateHeaderFile($id){

   $this->model->verify_session();
   $id_compania = $this->model->id_compania;

    $Cust = 'SELECT
                InvoiceNumber,
                TaxID,
                Net_due,
                observaciones,
                CustomerID,
                ShipToName,
                ShipToAddressLine1,
                ShipToAddressLine2,
                ShipToCity, 
                ShipToZip, 
                ShipToCountry
                FROM CREDITNOTE_HEADER
                where 
                ID_compania = "'.$id_compania.'" 
                AND CreditNoteNumber = "'.$id.'"';   

     $Cust = $this->model->Query($Cust);
     $Cust = json_decode($Cust[0]);

     $CustName    = $Cust->{'ShipToName'};
     $CustAddress = $Cust->{'ShipToAddressLine1'}.' '.$Cust->{'ShipToAddressLine2'}.' '.$Cust->{'ShipToCountry'};
     $RUC = $this->model->Query_value('Customers_Exp','custom_field1','where CustomerID="'.$Cust->{'CustomerID'}.'" and ID_compania = "'.$id_compania.'"');
     $DV  = $this->model->Query_value('Customers_Exp','custom_field2','where CustomerID="'.$Cust->{'CustomerID'}.'" and ID_compania = "'.$id_compania.'"');
    
  
     $Total  = $this->numberFormatPrecision($Cust->{'Net_due'});
     $NOTAS  = $Cust->{'observaciones'}.'-'.$Cust->{'termino_pago'}.'-'.$Cust->{'ShipToAddressLine1'};
     $Motivo = $Cust->{'observaciones'};
     $Tax    =  $this->model->Query_value('sale_tax','rate','where taxid="'.$Cust->{'TaxID'}.'"');

     $date = strtotime($this->model->GetLocalTime($Cust->{'LAST_CHANGE'})); 
     $dHORA = date('H:i:s',$date); 
     $dfecha = date('d-m-Y',$date);

     $ID = $id; 

     list($serprint,$NoInvoice) = explode('-', $Cust->{'InvoiceNumber'});

     if($NoInvoice!=''){

       $id = $NoInvoice;

     }else{
      
       $serprint  = '';
       $NoInvoice = '';

     }
    



    $DATA = '1|'.            //TIPO NOTA DE CREDITO
            'NCTI'.$ID.'|'.  //NO DOC
            $CustName.'|'.   //NONMBRE CLIENTE
            $RUC.'|'.        //RUC
            $CustAddress.'|'.//Direccion
            $Total.'|'.      //Total Neto
            $Tax.'|'.        //Alicuota
            $Motivo.'|'.     //NOTA MOTIVO
            $dfecha.'|'.     //FECHA DEVOLUCIONj
            $serprint.'|'.   //Serial de la impresora fiscal
            $NoInvoice.'|'.  //No. de factura fiscal
            $id.'|'.         //Referencia
            $DV;             //DV
                     

     $filename = 'CREDITMEMO/IN/NCTI'.$ID.'.txt';

     file_put_contents($filename , $DATA);

      //new block
      $PRINTER = $this->GetPrinterSeleccted($ID);

      $DIR = "FISCAL/".$PRINTER."/IN/";

      if (!file_exists($DIR)) {

          mkdir($DIR, 0777, true);

      }

      $filename = $DIR.'NCTI'.$ID.'.txt';

      file_put_contents($filename , $DATA);



}

//CREO ARCHIVOS FACMV
private function CreateDetailFile($id){

   $this->model->verify_session();
   $id_compania = $this->model->id_compania;
    
   $DATA = '';

   $RegLine = 'SELECT 
                     ItemOrd,
                     Item_id,
                     Quantity,
                     Net_line,
                     Unit_Price,
                     Description
               FROM  CREDITNOTE_DETAIL
               WHERE ID_compania = "'.$id_compania.'" 
                     AND CreditNoteNumber = "'.$id.'"';

    $RegLine = $this->model->Query($RegLine);

    foreach ($RegLine as  $value) {

    $value = json_decode($value);

    $PosItem = $value->{'ItemOrd'};
    $ItemID  = $value->{'Item_id'}; 
    $QTY     = $value->{'Quantity'};
    $PRICE   = $value->{'Unit_Price'};
    $TaxType = $this->model->Query_value('Products_Exp','TaxType','Where ID_compania = "'.$id_compania.'" and ProductID = "'.$ItemID.'"');
    $DesFact = $value->{'Description'};
    
    $Unit = $this->get_string_between($DesFact,'(',')');

    if( $TaxType == '1'){

      $TaxID = $this->model->Query_value('CREDITNOTE_DETAIL','TaxID','Where ID_compania = "'.$id_compania.'" and CreditNoteNumber = "'.$id.'"');
      $ITBMS =  $TaxID;
      
    }else{
      
      $ITBMS = '0.00';

    }


   $DATA .= 'NCMV'.$id.'|'. //NO DOC
             $ItemID.'|'.    //Codigo
             $DesFact.'|'.   //Nombre Articulo
             $Unit.'|'.      //Unidad de venta
             $QTY.'|'.       //Cantidad
             $this->numberFormatPrecision($PRICE).'|'.     //Precio neto unitario
             $ITBMS."\n";    //Alicuota ITBMS
    }

     //VIEJO
     $filename = 'CREDITMEMO/IN/NCMV'.$id.'.txt';
     file_put_contents($filename , $DATA);


      //new block
      $PRINTER = $this->GetPrinterSeleccted($id);

      $DIR = "FISCAL/".$PRINTER."/IN/";

      if (!file_exists($DIR)) {

          mkdir($DIR, 0777, true);

      }

      $filename = $DIR.'NCMV'.$id.'.txt';

      file_put_contents($filename , $DATA);

  
}




public function ReadInvoiceFile($id_compania){


  $logText = '';
  $bdres = '';


  $SQL = 'SELECT CreditNoteNumber,printer
            FROM 
            CREDITNOTE_HEADER
            Where 
            ID_compania = "'.$id_compania.'" 
            AND GenCreditNumber IS NULL';

  $res = $this->model->Query($SQL);

  foreach ($res as $key => $value) {
   
      $value = json_decode($value);

      $ID = $value->{'CreditNoteNumber'};
      $folder = $value->{'printer'};
       

        //NUEVO BLOQUE
        $PRINTER = $this->GetPrinterSeleccted($ID);

        $DIR = "FISCAL/".$PRINTER."/OUT/";
        $filename = $DIR.'OUT_NCTI'.$ID.'.TXT';

        if (file_exists($filename)) {
  
          $InvNum  = $this->InsertCreditMemoInfo($id_compania, trim($ID));
          if( $InvNum!='-'){
            $logText .= date('Y-m-d H:i:s').' InvoiceNumber : '.$InvNum.' -  CreditMemoNumber:'.$ID.' File: '.$filename."<br>\n";
          }

        }


  }

  file_put_contents('webhook_log.txt',  $logText, FILE_APPEND);

}


//OBTENGO NUMERO DE SERIAL Y FACTURA
public function GetNCNumber($ID){

  $PRINTER = $this->GetPrinterSeleccted($ID);
  $DIR = "FISCAL/".$PRINTER."/OUT/";

  $filename = $DIR.'OUT_NCTI'.$ID.'.TXT';
  $line = file_get_contents($filename);

  list(,,,,,,$NCNO,$conse) = explode(chr(9), $line);
  
  //$NCNO = substr($NCNO, 6);


  return $NCNO.'-'.$conse;
}



//inserto informacion de SO en Sales par acontabilizacion en PT
public function InsertCreditMemoInfo($id_compania,$ID){


    $CreditMemo = $this->model->Query('SELECT 
                                          *
                                        FROM  CREDITNOTE_HEADER 
                                       WHERE  ID_compania = "'.$id_compania.'" 
                                         AND  CreditNoteNumber = "'.$ID.'"');

    foreach ($CreditMemo as $key => $value) {
      
     $value = json_decode($value);

     $CustomerID = $value->{'CustomerID'};
     $CustomerName = $value->{'CustomerName'};
     $Subtotal = $value->{'Subtotal'};
     $TaxID = $value->{'TaxID'};
     $NCDate  = $value->{'date'};
     $Total = $value->{'Net_due'};

    }

    $NCNumber = $this->GetNCNumber($ID);

    if ( $NCNumber!='-'){


  
    $AR_Account = $this->model->Query_value('CTA_GL_CONF','CTA_CXC','where ID_compania="'.$id_compania.'";');

    //SET HEADER
   $values = array(
    'CreditNoteNumber'=>$ID,  
    'ID_compania'=>$id_compania,  
    'CreditNumber'=>$NCNumber,
    'CustomerID'  => $CustomerID,
    'CustomerName'=> $CustomerName,
    'Subtotal'=> $Subtotal,
    'TaxID'=>  $TaxID,
    'Net_Credit_due'=>  $Total,
    'AR_Account'=>  $AR_Account,
    'user'=>'00',
    'date'=>$NCDate
    );

   $this->model->insert('Customer_Credit_Memo_Header_Imp',$values);

   $this->CheckError();


    $NCDetail = $this->model->Query('SELECT 
                                                *
                                              FROM  CREDITNOTE_DETAIL 
                                             WHERE  ID_compania = "'.$id_compania.'" 
                                               AND  CreditNoteNumber = "'.$ID.'"');

    foreach ($NCDetail as $key => $dato) {
      
          $dato = json_decode($dato);

          $Unit = $this->get_string_between($dato->{'Description'},'(',')');
          $itemid = $dato->{'Item_id'};
          $GlAcct = null;
          $qty = $dato->{'Quantity'};
          $unitPrice = $dato->{'Unit_Price'};
          $Total = $dato->{'Net_line'};
          $TXID =  $this->model->Query_value('Customer_Credit_Memo_Header_Imp','TransactionID','where CreditNumber="'.$NCNumber.'" and ID_compania="'.$id_compania.'"');
  
          if($Unit=='KG'){
              
              $Description =  trim($dato->{'Description'});

              //INVENTORYADJUNT SI SE FACTURO EN KILOS
              $factor  = $this->model->Query_value('UNIT_MES_CONVRT','FACTOR','WHERE UNIT="L" and UNIT_TO_CONVERT="K" and ID_compania="'.$id_compania.'"');
              $no_cover_qty =  $qty / $factor;
              //$no_cover_qty = (-1)*$no_cover_qty;

              $LastUnitCost = $this->model->Query_value('Products_Exp','LastUnitCost','Where ProductID="'.$itemid.'" and id_compania="'.$id_compania.'";');
              $UnitCost = $no_cover_qty * $LastUnitCost;

               $valuesInvAd = array(
                'ItemID' => $itemid,
                'ID_compania' => $id_compania,
                'Reference' => $NCNumber,
                'ReasonToAdjust' => $NCNumber,
                'Account' =>  $this->model->Query_value('CTA_GL_CONF','GLACCT','where ID_compania="'.$id_compania.'";'),
                'Quantity' => $no_cover_qty ,
                'USER' => '00',
                'UnitCost' => $UnitCost,
                'Date' => $NCDate,
                'location_id' => $this->model->Query_value('status_location','id','where lote="'.$itemid.'0000" and id_product="'.$itemid.'" and route="1" and ID_compania="'.$id_compania.'"')
                );

               $this->model->insert('InventoryAdjust_Imp',$valuesInvAd);
               
               $desItem = $itemid;
               $Description =    $desItem.'-'.trim($dato->{'Description'});
               $itemid = '';

            }else{

               $Description =    trim($dato->{'Description'});

            }
      

          if($itemid == ''){ 

            $GlAcct = $this->model->Query_value('CTA_GL_CONF','CTA_DEV','where ID_compania="'.$id_compania.'";'); 

          }else{ 

            $GlAcct = ''  ;
           }


          //SET DETAIL
          $values1 = array(
                'ID_compania'=>$id_compania,
                'TransactionID'=> $TXID,
                'Item_id'=>$itemid,
                'Description'=> $Description,
                'Quantity'=>$qty,
                'GL_Acct' => $GlAcct,
                'Unit_Price'=>$unitPrice,
                'Net_line'=>$Total,
                'Tax_Type'=>$dato->{'Taxable'});

           $this->model->insert('Customer_Credit_Memo_Detail_Imp',$values1); //set item line
          
           $this->CheckError();
       }

    //Inserto numero de NC generada por maquina fiscal
    $this->model->Query('UPDATE CREDITNOTE_HEADER SET GenCreditNumber="'.$NCNumber.'" WHERE CreditNoteNumber="'.$ID.'" and ID_compania="'.$id_compania.'"');

 $this->CheckError();
}

return $NCNumber;
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


public function GetFactNo(){

$this->model->verify_session();
$id_compania = $this->model->id_compania;

$sql = 'SELECT InvoiceNumber, SalesOrderNumber 
          FROM INVOICE_GEN_HEADER 
         WHERE InvoiceNumber is not NULL 
           AND ID_compania="'.$id_compania.'"';

$RES = $this->model->Query($sql);

return $RES;


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

}//aqui termina la clase