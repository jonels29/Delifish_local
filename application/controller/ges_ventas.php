<?PHP

class ges_ventas extends Controller
{

//******************************************************************************
//ORDEN DE VENTAS
public function ges_orden_ventas(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_orden_ventas.php';
            require APP . 'view/_templates/footer.php';


        }
          


	
}

//ORDEN DE VENTAS
public function ges_orden_ventas_direct(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
       //    echo '<script> alert("Sistema en mantenimiento por favor espere"); </script>';
            require APP . 'view/operaciones/ges_orden_ventas_direct.php';
            require APP . 'view/_templates/footer.php';


        }
          


  
}



public function ges_hist_salesorder(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_hist_salesorder.php';
            require APP . 'view/_templates/footer.php';


        }
          


	
}

public function ges_hist_sales(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_hist_sales.php';
            require APP . 'view/_templates/footer.php';


        }
          


  
}

//ORDEN DE VENTAS
public function ges_reporte_diario(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
       //    echo '<script> alert("Sistema en mantenimiento por favor espere"); </script>';
            require APP . 'view/operaciones/ges_reporte_diario.php';
            require APP . 'view/_templates/footer.php';


        }
          
 
}


public function ges_print_salesorder($id){

//$tax= $this->model->Query_value('sale_tax','rate','where id="1";');
//$tax_sale = $tax/100;

 
$id = trim(preg_replace('/000+/','',$id));

 $res = $this->model->verify_session();
 $id_compania = $this->model->id_compania;

        if($res=='0'){

        $ORDER = $this->model->Get_order_to_invoice($id);
 
 
            foreach ($ORDER as  $value) {

               $value = json_decode($value);
               $custid = $value->{'Customer_Bill_Name'};
               $custname = $value->{'Customer_Bill_Name'}.'/ Dir:'.$value->{'AddressLine1'}.' '.$value->{'AddressLine2'};
               $saleorder = $value->{'SalesOrderNumber'};

               $salesRep = $value->{'name'}.' '.$value->{'lastname'} ;

               $saledate = $value->{'date'};

               $PO =  $value->{'CustomerPO'};

               $subtotal= number_format($value->{'Subtotal'},4);        
               $tax = number_format($value->{'OrderTax'},4);
               $total=number_format($value->{'Net_due'},4);


               $contact = $value->{'email'}.' / '.$value->{'Phone_Number'};

               $tipo_lic = $value->{'tipo_licitacion'};
               $termino_pago =  $value->{'termino_pago'};
               $obser =  $value->{'observaciones'} ;
               $entrega =   $value->{'entrega'};

               

            }
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_print_salesorder.php';
            require APP . 'view/_templates/footer.php';


        }
          


    
}


public function ges_print_OrdEmpaque($id){


$id = trim(preg_replace('/000+/','',$id));

 $res = $this->model->verify_session();
 $id_compania = $this->model->id_compania;

        if($res=='0'){

        $ORDER = $this->model->Get_order_to_invoice($id);
 
 
            foreach ($ORDER as  $value) {
               $value = json_decode($value);

                $custid = $value->{'Customer_Bill_Name'};

                $razonSocial = $this->model->Query_value('Customers_Exp','Custom_field3','where 
                                                                                        CustomerID="'.$value->{'CustomerID'}.'" and 
                                                                                        id_compania="'.$id_compania.'"');
              
               $custname = $value->{'ShipToName'}.' <br>'.$razonSocial.' <br>'.$value->{'AddressLine1'};
     
               $saleorder = $value->{'SalesOrderNumber'};

               $salesRep = $value->{'name'}.' '.$value->{'lastname'} ;

               $saledate = $value->{'date'};

               $fecha_entrega = $value->{'fecha_entrega'};

               $clause = 'WHERE SalesOrderNumber="'.$id.'" and ID_compania="'.$id_compania.'"';  
               $created  = $this->model->Query_value('SalesOrder_Header_Imp','LAST_CHANGE',$clause);


               $PO =  $value->{'CustomerPO'};

               $contact = $value->{'email'}.' / '.$value->{'Phone_Number'};

               $tipo_lic = $value->{'tipo_licitacion'};
               $termino_pago =  $value->{'termino_pago'};
               $obser =  $value->{'observaciones'} ;
               $entrega =   $value->{'entrega'};
              

            }

            //UPDATE INDICATOR
            $value = array('DispachPrinted' => '1' );

            $this->model->update('SalesOrder_Header_Imp',$value, ' SalesOrderNumber="'.$id.'" and ID_compania="'.$id_compania.'"');
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_print_OrdEmpaque.php';
            require APP . 'view/_templates/footer.php';


        }
          


    
}


//******************************************************************************
//FACTURAS DE VENTAS


public function ges_pro_ventas(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_pro_ventas.php';
            require APP . 'view/_templates/footer.php';

        }

}

public function ges_pro_hist_ventas(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_pro_hist_ventas.php';
            require APP . 'view/_templates/footer.php';


        }
          


  
}


public function ges_print_sales($id){

$tax= $this->model->Query_value('sale_tax','rate','where id="1";');
$tax_sale = $tax/100;

 
$id = trim(preg_replace('/000+/','',$id));

 $res = $this->model->verify_session();

        if($res=='0'){

        $ORDER = $this->model->Get_sales_to_invoice($id);
 
 
            foreach ($ORDER as  $value) {
               $value = json_decode($value);
              
               $custid = $value->{'CustomerID'};
               
               $custname = $value->{'Customer_Bill_Name'};

               $saleorder = $value->{'InvoiceNumber'};

               $salesRep = $value->{'name'}.' '.$value->{'lastname'} ;

               $saledate = $value->{'date'};

               $subtotal= number_format($value->{'Subtotal'},2);

               $tax = $value->{'saletax'};

               $tax_sale = $tax/100;

               $tax =  number_format(($subtotal * $tax_sale),2);

              

               $total=number_format($value->{'Net_due'},2);

               $contact = $value->{'email'}.' / '.$value->{'Phone_Number'};
               

            }
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_print_sales.php';
            require APP . 'view/_templates/footer.php';


        }
          


    
}

//******************************************************************************
//SALIDA DE INVENTARIO POR AJUSTES

public function ges_sal_merc(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_sal_merc.php';
            require APP . 'view/_templates/footer.php';


        }
           
}

public function ges_hist_sal_merc(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_hist_sal_merc.php';
            require APP . 'view/_templates/footer.php';


        }
          


  
}

public function  ges_print_SalMerc($id){

 
$id = trim(preg_replace('/000+/','',$id));

 $res = $this->model->verify_session();

        if($res=='0'){

        $ORDER = $this->model->Get_sal_merc_to_invoice($id);
 
        

 
            foreach ($ORDER as  $value) {

              $value = json_decode($value);

             $name = $this->model->Query_value('SAX_USER','name','Where ID="'.$value->{'USER'}.'"');
             $lastname =  $this->model->Query_value('SAX_USER','lastname','Where ID="'.$value->{'USER'}.'"');

             $Job= $value->{'JobID'};      
             $fase= $value->{'JobPhaseID'};
             $ccost= $value->{'JobCostCodeID'};
              
              $ref = $value->{'Reference'};

              $rep = $name.' '.$lastname;

              $date = $value->{'Date'};

              $desc = $value->{'ReasonToAdjust'};

              $accnt =  $value->{'Account'};


            }
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_print_SalMerc.php';
            require APP . 'view/_templates/footer.php';


        }
          
}


public function GetPayTerm($ID){

  $this->model->verify_session();
  $id_compania = $this->model->id_compania;

  $CustID = $this->model->Query_value('Customers_Exp','CustomerID','WHERE ID = "'.$ID.'" AND ID_compania="'.$id_compania.'"'); 


  $DaysToPay = $this->model->Query_value('CUST_PAY_TERM','DaysToPay','WHERE CustomerID = "'.$CustID.'" AND ID_compania="'.$id_compania.'"'); 

  echo $DaysToPay;
}





public function Get_SalesOrders($sort,$limit,$date1,$date2){


$this->model->verify_session();

$clause='';

$clause.= 'where SalesOrder_Header_Imp.ID_compania="'.$this->model->id_compania.'" ';



if($date1!=''){

   if($date2!=''){

      $clause.= 'and  date between "'.$date1.'" and "'.$date2.'" ';           
    }
   
   if($date2==''){ 

     $clause.= 'and date="'.$date1.'"';
   }
     
}



if($this->model->active_user_role=='admin'){

$query ='SELECT * FROM `SalesOrder_Header_Imp` 
inner JOIN `SalesOrder_Detail_Imp` ON SalesOrder_Header_Imp.SalesOrderNumber = SalesOrder_Detail_Imp.SalesOrderNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = SalesOrder_Header_Imp.user '.$clause.' GROUP BY SalesOrder_Header_Imp.SalesOrderNumber order by SalesOrder_Header_Imp.LAST_CHANGE '.$sort.' limit '.$limit ; }

if($this->model->active_user_role=='user'){

  if($clause!=''){ $clause.= 'and `SAX_USER`.`id`="'.$this->model->active_user_role_id.'"'; } else{ $clause.= ' Where `SAX_USER`.`id`="'.$this->active_user_role_id.'"'; }

$query='SELECT * FROM `SalesOrder_Header_Imp`
inner JOIN `SalesOrder_Detail_Imp` ON SalesOrder_Header_Imp.SalesOrderNumber = SalesOrder_Detail_Imp.SalesOrderNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = SalesOrder_Header_Imp.user '.$clause.' GROUP BY SalesOrder_Header_Imp.SalesOrderNumber  order by SalesOrder_Header_Imp.LAST_CHANGE '.$sort.' limit '.$limit;

}


       
$table.= '<script type="text/javascript">
 jQuery(document).ready(function($)

  {

   var table = $("#table_report").dataTable({

       aLengthMenu: [
         [25, 50, 100, 500 , -1], [25, 50, 100 , 500 , "All"]
        ],
      responsive: true,

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
 select_type_options: { width: "100%" }

},
{column_number : 1,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 2,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 4,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 5,
 select_type: "select2",
 select_type_options: { width: "100%" }},

{column_number : 6,
 select_type: "select2",
 select_type_options: { width: "100%" }}
],
{cumulative_filtering: true, 
filter_reset_button_text: false});

      
  });
</script>
  <table id="table_report" class="tableReport table table-striped table-bordered">
    <thead>
      <tr>
        <th>No. Orden</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Total Venta</th>
        <th>Procesado por:</th>
        <th>Estado PT</th>
        <th>Factura Emitida</th>
        <th>Cerrar</th>
      </tr>
    </thead>';



$filter =  $this->model->Query($query);

$URL ='"'.URL.'"';

foreach ($filter as $datos) {

  $filter = json_decode($datos);


  $ID ='"'.$filter->{'SalesOrderNumber'}.'"';

   if($filter->{'Error'}==1) { 

     $status= "Error : ".$filter->{'ErrorPT'}. '  Cancelado';
     $style="style='color:red; font-style:bold;'"; 


   } else{

    if($filter->{'Enviado'}==0){

      $style="style='color:orange; font-style:bold;'"; 
      $status='No sincronizado';

       }else{ 

         $status= "Enviado";
         $style="style='color:green; font-style:bold;'";

       }   

    }


$Emitida = $this->model->Query_value('SalesOrder_Header_Imp','EMITIDA','Where SalesOrderNumber="'.$filter->{'SalesOrderNumber'}.'" and  ID_compania="'.$this->model->id_compania.'" ');


//if($aprobacion==''){ $apro = 'En espera de envio'; $apro_style="style='color:orange; font-style:bold;'"; }

if($Emitida=='0' || $Emitida==''){ $apro = 'NO';  $apro_style="style='color:orange; font-style:bold;'";  }

if($Emitida=='1' ){ $apro = 'SI'; $apro_style="style='color:green; font-style:bold;'"; }



$user = $this->model->Get_User_Info($filter->{'user'}); 

foreach ($user as $value) {
$value = json_decode($value);
$name= $value->{'name'};
$lastname = $value->{'lastname'};
}


if($filter->{'Error'}!=1){$apr = $apro;}

$close_sales_ck = $this->model->Query_value('SAX_USER', 'closeSO','where id="'.$this->model->active_user_id.'";');

$table.= "<tr>

    <td ><a href='#' onclick='javascript: show_sales(".$URL.",".$ID."); ' >".$filter->{'SalesOrderNumber'}."</a></td>
    <td class='numb' >".$filter->{'date'}."</td>
    <td >".$filter->{'CustomerID'}.'-'.$filter->{'CustomerName'}.'</td>
    <td class="numb" >'.$this->numberFormatPrecision($filter->{'Net_due'}).'</td>
    <td >'.$name.' '.$lastname.'</td>
    <td '.$style.'>'.$status.'</td>
    <td '.$apro_style.'>'.$apro."</td>";

 if($close_sales_ck == 1 && $apro != 'SI'){

 $table .= "<td ><a href='#' onclick='javascript: closeSo(".$URL.",".$ID."); ' >Cerrar orden</a></td>";

 } else{

 $table .= "<td ></td>";

 }

 $table .= "</tr>";


$apr = '';
}

$table.= '</table>';



echo $table;

}






public function get_salesorder_info($id){

$this->model->verify_session();

$id_compania= $this->model->id_compania;

$query ="SELECT * FROM `SalesOrder_Header_Imp`
inner JOIN `SalesOrder_Detail_Imp` ON SalesOrder_Header_Imp.SalesOrderNumber = SalesOrder_Detail_Imp.SalesOrderNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = SalesOrder_Header_Imp.user
WHERE SalesOrder_Header_Imp.SalesOrderNumber='".$id."' and SalesOrder_Header_Imp.ID_compania='".$id_compania."'  GROUP BY SalesOrder_Detail_Imp.SalesOrderNumber ";

//inner JOIN Products_Exp ON Products_Exp.ProductID = SalesOrder_Detail_Imp.item_id


$ORDER_detail= $this->model->Query($query);


echo '<br/><br/><fieldset><legend>Detalle de Orden de venta/Pedido</legend><table class="table table-striped table-bordered" cellspacing="0"  ><tr>';

  foreach ($ORDER_detail as $datos) {
    $ORDER_detail = json_decode($datos);


      if($ORDER_detail->{'Error'}=='1') { 

       $status= "Error : ".$ORDER_detail->{'ErrorPT'}. 'Se ha cancelado la Orden';
       $style="style='color:red;'"; 


     } else{

        if($ORDER_detail->{'Enviado'}!="1"){

          $style="style='color:orange;'"; 
          $status='Por Procesar'; }else{ 

            $status= "Sincronizado el: ".$ORDER_detail->{'Export_date'};
            $style="style='color:green;'";

           }   

        }

$aprobacion = $this->model->Query_value('SalesOrder_Header_Exp','Close_SO','Where SalesOrderNumber="'.$ORDER_detail->{'SalesOrderNumber'}.'" and  ID_compania="'.$this->model->id_compania.'" ');

    //if($aprobacion==''){ $apro = 'En espera de envio'; $apro_style="style='color:orange; font-style:bold;'"; }
    //if($aprobacion=='0' || $aprobacion==''){ $apro = 'En espera de aprobacion';  $apro_style="style='color:orange; font-style:bold;'";  }
    //if($aprobacion=='1' ){ $apro = 'Aprobado'; $apro_style="style='color:green; font-style:bold;'"; }

    echo "<tr  ><th class='columnHdr'><strong>No. Orden</strong></th><td class='columnHdr InfsalesTd order'>".$ORDER_detail->{'SalesOrderNumber'}."</td></tr>
          <tr ><th class='columnHdr'><strong>Fecha</strong></th><td class='columnHdr InfsalesTd '>".$ORDER_detail->{'date'}."</td></tr>
          <tr ' ><th class='columnHdr'><strong>Cliente</strong></th><td class='columnHdr InfsalesTd'>".$ORDER_detail->{'CustomerName'}."</td></tr>
          <tr ><th class='columnHdr' ><strong>Total venta</strong></th><td class='columnHdr InfsalesTd '>".$this->numberFormatPrecision($ORDER_detail->{'Net_due'})."</td></tr>
          <tr><th class='columnHdr'><strong>Vendedor</strong></th><td class='columnHdr InfsalesTd'>".$ORDER_detail->{'name'}.' '.$ORDER_detail->{'lastname'}."</td></tr>
          <tr ><th class='columnHdr'><strong>Estado</strong></th><td '.$style.' class='columnHdr InfsalesTd'>".$status."</td></tr>";

}


if($ORDER_detail->{'Error'}=='1') { 
$apro ='';

 }

echo "</tr></table>";



$query ="SELECT * FROM `SalesOrder_Detail_Imp`
WHERE SalesOrder_Detail_Imp.SalesOrderNumber='".$id."' and  SalesOrder_Detail_Imp.ID_compania='".$id_compania."' ORDER BY SalesOrder_Detail_Imp.ItemOrd ASC ;";


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

    $id= "'".$ORDER_detail->{'SalesOrderNumber'}."'";


echo  "<tr>
          <td>".$ORDER->{'Item_id'}."</td>
          <td>".$ORDER->{'Description'}."</td>
          <td class='numb' >".number_format($ORDER->{'Quantity'},4,'.',',')."</td>
          <td class='numb' >".$this->numberFormatPrecision($ORDER->{'Unit_Price'})."</td>

      </tr>";

  }

echo '</tbody></table><div style="float:right;" class="col-md-2">
<a href="'.URL.'index.php?url=ges_ventas/ges_print_salesorder/'.$ORDER_detail->{'SalesOrderNumber'}.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
   <img  class="icon" src="img/Printer.png" />
  <span>Imprimir</span>
</a>
</div></fieldset>';


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
          MSG_ERROR('".$CHK_ERROR."',0);
         }
       );</script>"); 

  }

}




///////////////////////////////SALESS!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
public function Get_Sales($sort,$limit,$date1,$date2){


$this->model->verify_session();


$clause='';

$clause.= 'where Sales_Header_Imp.ID_compania="'.$this->model->id_compania.'" ';



if($date1!=''){

   if($date2!=''){

      $clause.= ' and date between "'.$date1.'%" and "'.$date2.'%" ';           
    }
   
   if($date2==''){ 

     $clause.= ' and date like "'.$date1.'%"';
   }
     
}


//if($this->model->active_user_role=='admin'){

$query ='SELECT * FROM `Sales_Header_Imp` 
inner JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = Sales_Detail_Imp.InvoiceNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = Sales_Header_Imp.user '.$clause.' GROUP BY Sales_Header_Imp.InvoiceNumber order by LAST_CHANGE '.$sort.' limit '.$limit ; 
//}

/*if($this->active_user_role=='user'){

  if($clause!=''){ $clause.= 'and `SAX_USER`.`id`="'.$this->active_user_role_id.'"'; } else{ $clause.= ' Where `SAX_USER`.`id`="'.$this->active_user_role_id.'"'; }

$query='SELECT * FROM `Sales_Header_Imp`
inner JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = SalesOrder_Detail_Imp.InvoiceNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = Sales_Header_Imp.user '.$clause.' GROUP BY SalesOrder_Header_Imp.InvoiceNumber  order by LAST_CHANGE '.$sort.' limit '.$limit;

}
*/
       
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
 select_type_options: { width: "100%" }

},
{column_number : 1,
 column_data_type: "html",
 html_data_type: "text" ,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 2,
 column_data_type: "html",
 html_data_type: "text" ,
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
 select_type_options: { width: "100%" }}
],
{cumulative_filtering: true, 
filter_reset_button_text: false});
   
      
  });
  </script>
  <table id="table_report" class="display  table table-condensed table-striped table-bordered"  >
    <thead>
      <tr>
        <th>No. Factura fiscal</th>
        <th>No. Orden/Pedido</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Tipo Cliente</th>
        <th>Termino de Pago</th>
        <th width="5%">Total Venta</th>
        <th>Procesado por:</th>
        <th>Estado PT</th>
      </tr>
    </thead>';



$filter =  $this->model->Query($query);

$URL ="'".URL."'";

foreach ($filter as $datos) {

  $filter = json_decode($datos);


  $ID ="'".$filter->{'InvoiceNumber'}."'";

   if($filter->{'Error'}==1) { 

     $status= "Error : ".$filter->{'ErrorPT'}. 'Cancelado';
     $style="style='color:red; font-style:bold;'"; 


   } else{

    if($filter->{'Enviado'}==0){

      $style="style='color:orange; font-style:bold;'"; 
      $status='No sincronizado';

       }else{ 

         $status= "Enviado";
         $style="style='color:green; font-style:bold;'";

       }   

    }



$user = $this->model->Get_User_Info($filter->{'user'}); 

foreach ($user as $value) {
$value = json_decode($value);
$name= $value->{'name'};
$lastname = $value->{'lastname'};
}

 $OrdPedi = $this->model->Query_value('INVOICE_GEN_HEADER','SalesOrderNumber','WHERE InvoiceNumber="'.$filter->{'InvoiceNumber'}.'"');
 $OrdPediID ="'".$OrdPedi."'"; 

 $NOTA = $this->model->Query_value('INVOICE_GEN_HEADER','NOTAS','WHERE InvoiceNumber="'.$filter->{'InvoiceNumber'}.'"');

 list($nota,$typago) = explode('-',$NOTA);

$table.= '<tr>
    <td ><a href="#"  onclick="javascript: show_invoice('.$URL.','.$ID.');"  ><strong>'.$filter->{'InvoiceNumber'}.'</strong></a></td>
    <td ><a href="#"  onclick="javascript: show_sales('.$URL.','.$OrdPediID.');"  ><strong>'.$OrdPedi."</strong></a></td>
    <td class='numb' >".$filter->{'date'}."</td>
    <td >".$filter->{'CustomerName'}.'</td>
    <td ></td>
    <td >'.$typago.'</td>
    <td width="15%" class="numb" >'.number_format($filter->{'Net_due'},2,'.',',').'</td>
    <td >'.$name.' '.$lastname.'</td>
    <td '.$style.'>'.$status."</td>
   </tr>";

}

$table.= '</table>';

echo $table;

}



public function get_sales_info($id){


$this->model->verify_session();


$query ="SELECT * FROM `Sales_Header_Imp`
inner JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = Sales_Detail_Imp.InvoiceNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = Sales_Header_Imp.user
WHERE Sales_Header_Imp.InvoiceNumber='".$id."' GROUP BY Sales_Detail_Imp.InvoiceNumber";

//inner JOIN Products_Exp ON Products_Exp.ProductID = Sales_Detail_Imp.item_id

$ORDER_detail= $this->model->Query($query);


echo '<br/><br/><fieldset><legend>Detalle de factura</legend><table class="table table-striped table-bordered" cellspacing="0"  ><tr>';

  foreach ($ORDER_detail as $datos) {
    $ORDER_detail = json_decode($datos);
   
    $OrdPedi = $this->model->Query_value('INVOICE_GEN_HEADER','SalesOrderNumber','WHERE InvoiceNumber="'.$ORDER_detail->{'InvoiceNumber'}.'"');

    echo "<tr><th class='columnHdr'><strong>No. Factura fiscal</strong></th><td class='columnHdr InfsalesTd order'>".$ORDER_detail->{'InvoiceNumber'}."</td></tr>
          <tr><th class='columnHdr'><strong>No. Orden/Pedido</strong></th><td class='columnHdr InfsalesTd order'>".$OrdPedi."</td></tr>
          <tr><th class='columnHdr'><strong>Fecha</strong></th><td class='columnHdr InfsalesTd'>".$ORDER_detail->{'date'}."</td></tr>
          <tr><th class='columnHdr'><strong>Cliente</strong></th><td class='columnHdr InfsalesTd'>".$ORDER_detail->{'CustomerName'}."</td></tr>
          <tr><th class='columnHdr'><strong>Total venta</strong></th><td class='columnHdr InfsalesTd'>".number_format($ORDER_detail->{'Net_due'},2,'.',',')."</td></tr>
          <tr><th class='columnHdr'><strong>Procesado por: </strong></th><td class='columnHdr InfsalesTd'>".$ORDER_detail->{'name'}.' '.$ORDER_detail->{'lastname'}."</td></tr>";

}




echo "</tr></table>";

$query ="SELECT * FROM `Sales_Header_Imp`
inner JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = Sales_Detail_Imp.InvoiceNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = Sales_Header_Imp.user
WHERE Sales_Header_Imp.InvoiceNumber='".$id."';";


$ORDER= $this->model->Query($query);

echo '<table id="example-12" class="table table-striped table-bordered" cellspacing="0"  >
      <thead>
        <tr>
          <th>Codigo</th>
          <th>Descripcion</th>
          <th>Cantidad</th>
          <th>Precio Unit.</th>
          <th>Total linea</th>
          <th>Estado Sinc.</th>
        </tr>
      </thead><tbody>';


foreach ($ORDER as $datos) {

    $ORDER = json_decode($datos);

    $id= "'".$ORDER_detail->{'InvoiceNumber'}."'";

  if($ORDER->{'Error'}=='1') { 

   $status= "Error : ".$ORDER->{'ErrorPT'}. 'Se ha cancelado la Orden';
   $style="style='color:red;'"; 


 } else{

    if($ORDER->{'Enviado'}!="1"){

      $style="style='color:orange;'"; 
      $status='Por Procesar'; }else{ 

        $status= "Sincronizado el: ".$ORDER->{'Export_date'};
         $style="style='color:green;'";

       }   

    }

$net_line = number_format($ORDER->{'Quantity'},2,'.',',') * number_format($ORDER->{'Unit_Price'},2,'.',',');

$PRICE = $this->numberFormatPrecision($ORDER->{'Unit_Price'});
$QTY =  $this->numberFormatPrecision($ORDER->{'Quantity'});
$NET_LINE =  $PRICE * $QTY;

$NET_LINE = $this->numberFormatPrecision($NET_LINE);

echo  "<tr>
          <td>".$ORDER->{'Item_id'}."</td>
          <td>".$ORDER->{'Description'}."</td>
          <td class='numb' >".$QTY."</td>
          <td class='numb' >".$PRICE ."</td>
          <td class='numb' >".$NET_LINE.'</td>
          <td '.$style.' >'.$status.'</td>
      </tr>';

  }

echo '</tbody></table></fieldset>';


}


public function CloseSelesOrder($id){

$this->model->verify_session();

  $table = 'SalesOrder_Header_Imp';

  $clause = ' WHERE SalesOrderNumber = "'.$id.'" AND ID_compania =  "'.$this->model->id_compania.'"';

    $this->model->delete($table,$clause);

    $this->CheckError();

  $table = 'SalesOrder_Detail_Imp';

  $clause = ' WHERE SalesOrderNumber = "'.$id.'" AND ID_compania =  "'.$this->model->id_compania.'"';


    $this->model->delete($table,$clause);

    $this->CheckError();

echo 1;
}


public function GetDaylySales($date1,$date2,$pinter){


$this->model->verify_session();


$clause='';

$clause.= 'where Sales_Header_Imp.ID_compania="'.$this->model->id_compania.'" and Sales_Header_Imp.InvoiceNumber <> ""';



if($date1!=''){

  
   if($date2!=''){

      $clause.= ' and Sales_Header_Imp.date between "'.$date1.'%" and "'.$date2.'%" ';           
    }
   
   if($date2==''){ 

     $clause.= ' and Sales_Header_Imp.date like "'.$date1.'%"';
   }
     
}else{

   $clause.= ' and date like "'.date('Y-m-d').'%"';

}



if($pinter!=''){ 

   $clause.= ' and Sales_Header_Imp.InvoiceNumber like "'.$pinter.'-%" ';

}



$query ='SELECT * FROM `Sales_Header_Imp` 
inner JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = Sales_Detail_Imp.InvoiceNumber
'.$clause.' 
GROUP BY Sales_Header_Imp.InvoiceNumber order by LAST_CHANGE'; 

       
$table.= '<script type="text/javascript">
 jQuery(document).ready(function($)

  {

   var table = $("#table_report").dataTable({

      paging: false,
      responsive: false,
      dom: "Blfrtip",
      bSort: true,
      bPaginate: true,
      select:true,

        buttons: [

          {

          extend: "excelHtml5",
          text: "Exportar",
          title: "Reporte_Diario_ventas",
           
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
 column_data_type: "html",
 html_data_type: "text" ,
 select_type: "select2",
 select_type_options: { width: "100%" }},
{column_number : 3 ,
 select_type: "select2",
 select_type_options: { width: "100%" }},
{column_number : 4,
 select_type: "select2",
 select_type_options: { width: "100%" }}
],
{cumulative_filtering: true, 
filter_reset_button_text: false});
      
  });
</script>
  <table id="table_report" class="display  table table-condensed table-striped table-bordered"  >
    <thead>
      <tr>
        <th>DOCUMENTO FISCAL</th>
        <th>PEDIDO</th>        
        <th>CLIENTE</th>
        <th>FECHA</th>
        <th>NOMBRE</th>
        <th>CREDITO</th>
        <th>CONTADO</th>
        <th>DEVOLUCION</th>
        <th>ITBMS</th>
      </tr>
    </thead>';



$filter =  $this->model->Query($query);


foreach ($filter as $datos) {

 $filter = json_decode($datos);



 $OrdPedi = $this->model->Query_value('INVOICE_GEN_HEADER','SalesOrderNumber','WHERE InvoiceNumber="'.$filter->{'InvoiceNumber'}.'"');
 $OrdPediID ="'".$OrdPedi."'"; 

 $NOTA = $this->model->Query_value('INVOICE_GEN_HEADER','NOTAS','WHERE InvoiceNumber="'.$filter->{'InvoiceNumber'}.'"');

 list($nota,$typago) = explode('-',$NOTA);



$contado = '0.00';
$credito = '0.00';
$devolucion = '0.00';

  if(trim($typago)=='CONTADO'){

     $contado = number_format($filter->{'Net_due'},2,'.',',');

  }else{
   
    if(trim($typago)==''){

     $credito = number_format( $filter->{'Net_due'},2,'.',',');

    }else{
     $credito= number_format( $filter->{'Net_due'},2,'.',',');
    }

  }




  if ($value->{'OrderTax'}!=''){

  $tax = number_format( $filter->{'OrderTax'},2,'.',',');

  }else{

  $tax = '0.00';

  }



$table.= '<tr>
    <td ><strong>'.$filter->{'InvoiceNumber'}.'</strong></td>
    <td ><strong>'.$OrdPedi."</strong></td>
    <td class='numb' >".$filter->{'CustomerID'}."</td>
    <td class='numb' >".date('d-m-Y',strtotime($filter->{'date'}))."</td>
    <td >".$filter->{'CustomerName'}.'</td>
    <td class="numb">'.$credito.'</td>
    <td class="numb">'.$contado.'</td>
    <td class="numb">'.$devolucion.'</td>
    <td class="numb">'.$tax."</td>
   </tr>";

}



$clause='';

$clause.= 'where Customer_Credit_Memo_Header_Imp.ID_compania="'.$this->model->id_compania.'" and Customer_Credit_Memo_Header_Imp.CreditNumber <> ""';



if($date1!=''){

  
   if($date2!=''){

      $clause.= ' and Customer_Credit_Memo_Header_Imp.Date between "'.$date1.'%" and "'.$date2.'%" ';           
    }
   
   if($date2==''){ 

     $clause.= ' and Customer_Credit_Memo_Header_Imp.Date like "'.$date1.'%"';
   }
     
}else{

   $clause.= ' and Customer_Credit_Memo_Header_Imp.Date like "'.date('Y-m-d').'%"';

}


if($pinter!=''){ 

   $clause.= ' and Customer_Credit_Memo_Header_Imp.CreditNumber  like "'.$pinter.'-%" ';

}


$query ='SELECT * FROM `Customer_Credit_Memo_Header_Imp` 
inner JOIN `Customer_Credit_Memo_Detail_Imp` ON Customer_Credit_Memo_Detail_Imp.TransactionID = Customer_Credit_Memo_Header_Imp.TransactionID
'.$clause.' 
GROUP BY Customer_Credit_Memo_Header_Imp.CreditNumber order by Customer_Credit_Memo_Header_Imp.TransactionID'; 


$filter =  $this->model->Query($query);

foreach ($filter as $datos) {

 $filter = json_decode($datos);

$table.= '<tr>
    <td ><strong>'.$filter->{'CreditNumber'}.'</strong></td>
    <td ><strong>'.$filter->{'CreditNoteNumber'}."</strong></td>
    <td class='numb' >".$filter->{'CustomerID'}."</td>
    <td class='numb' >".date('d-m-Y',strtotime($filter->{'Date'}))."</td>
    <td >".$filter->{'CustomerName'}.'</td>
    <td class="numb">0.00</td>
    <td class="numb">0.00</td>
    <td class="numb">'.$filter->{'Net_Credit_due'}."</td>
    <td class='numb'>0.00</td>
   </tr>";



}


$table.= '</table> ';



echo $table;

}


//LISTA DE IMPRESORA FISCAL 
public function getPrinterList(){

  return $this->model->Query('SELECT * FROM INV_PRINT_CONF');

}



}

?>