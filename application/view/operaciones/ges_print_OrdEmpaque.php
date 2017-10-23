<script type="text/javascript">
	
$(window).load(function(){

window.print();



});

</script>

<style type="text/css">

table , th, td {
   border: 3px solid black !important;
}

</style>


<?php	

//company
$comp = $this->model->Get_company_Info();

foreach ($comp as $value) {
	$value = json_decode($value);
	$address = $value->{'address'};
	$name = $value->{'company_name'};
	$tel= $value->{'Tel'};
	$fax = $value->{'Fax'};
}



?>

<div  class="page-print col-xs-11">
<div  class="col-xs-12">
<!-- contenido -->

<page size="A4">

<div class=" col-xs-12">

  <div style="float:right;" class="print_button col-md-2">
  <a href="#" onClick="window.print()" class="print_button btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
   <img  class='icon' src="img/Printer.png" />
    <span>Imprimir</span>
  </a>
  </div>

<div id="printable" class="col-xs-12">

<!-- company info  -->
	           <div class="row">
                <div class="invoice_header  tableInvDe col-xs-5">
                 <h2 class="h_invoice_header" ><?php echo $name ; ?></h2>
                 <table width='100%' class="borderTable wrap tableInvoice">
                 	<tr>
                 	  <th><strong><?php echo $address ; ?></strong></th>

                 	</tr>

                 	<tr>
                 	  <th><strong>Tel. :</strong><?php echo $tel ; ?></th>
                
                 	</tr>
                 	<tr>
                 	  <th><strong>Fax :</strong> <?php echo $fax ; ?></th>
                 	 
                 	</tr>
                 </table>                  
                </div>
             
                <div class="col-xs-2"></div>
 
<!-- Order Info  -->   
	          <div class="invoice_header tableInvDe col-xs-5">
                <h2 class="h_invoice_header" >Orden de Pedido: </h2><label><font size="16"><?php echo $saleorder; ?></font></label>
                 <table width='100%' class="borderTable tableInvoice">
                 	<tr>
                 	  <th><strong>No. Orden:</strong> <?php echo $saleorder; ?></th>
                 	 
                 	</tr>
                 	<tr>
                 	  <th><strong>Fecha: </strong> <?php $saledate = strtotime($saledate); echo date('d-m-Y',$saledate); ?></th>
                 	  
                 	</tr>
                 	<tr>
                 	  <th><strong>Enviado por: </strong> - </th>
                 	  
                 	</tr>
                 	<tr>
                 	   <th><strong>Hora: </strong><?php $created = strtotime($this->model->GetLocalTime($created)); echo date('H:i:s',$created);   ?></th>
                       
                 	</tr>


                 </table>
                  
                </div>
               </div>

             
       
                   
                   <table  class='borderTable' width="100%" border='1'>  
                       <thead>
                        <tr>
                           <th>ID Cliente</th>
                           <th>Rep. de ventas</th>
                           <th>Fecha de entrega</th>
                        </tr>
                       </thead>
                       <tbody>
                        <tr>
                           <td width="40%" ><?php echo  $custname; ?></td>
                           <td width="20%" style="text-align: center;" ><?php echo  $salesRep; ?></td>
                           <td width="20%"  style="text-align: center;" ><?php echo  $fecha_entrega; ?></td>
                        </tr>                      
                       </tbody>
                   </table>
                

                <div class="separador col-xs-12"></div>

                <?php if($obser!=''){ ?>
                      
                             <TABLE  class='borderTable' width="100%" border='1' >
                                <TR >
                                    <TH width="100%">Observaciones</TH>
                                </TR>
                                <tbody>
                                <tr>
                                    <td><?php echo  $obser; ?></td>
                                </tr>     

                                </tbody>


                            </TABLE>
                <?php } ?>
                <div class="separador col-xs-12"></div>

                    
                        
                             <TABLE  class='borderTable'  width="100%" border='1' >
                              <thead>
                                <TR >
                                    <TH width="15%">Item</TH>
                                    <TH width="40%">Descripcion</TH>
                                    <TH width="15%">Chico</TH>
                                    <TH width="15%">Grande</TH>
                                    <TH width="15%">Libra/Unidad</TH>
                                </TR>
                           </thead>
                           <tbody>
                           <?php  foreach ($ORDER as  $value) { 

                            $value = json_decode($value);  



                            if ($value->{'Quantity'}>'0'){ $QTY = number_format($value->{'Quantity'},2); }else{  $QTY = ''; }

                            if (strpos($value->{'Description'},'Lote')){ 

                                $Description = trim($value->{'Description'});
                               
                            }else{ 

                                $Description = $value->{'Description'};
                            }

                             
                             //CONVERSION A LIBRA 
                                $libra = '';

                                $libra  = $QTY;

                                $UnitMeasure = get_string_between($value->{'Description'},'(',')');

                                $desc = substr($value->{'Description'}, strpos($value->{'Description'},')')+1);

                               // list($uni , $desc ) = explode(')', $value->{'Description'});

                                if($UnitMeasure == 'KG'){
 
                                   $UNIT_TO_CONVERT = $this->model->Query_value('Customers_Exp','Custom_field5','WHERE 
                                                                                                                    CustomerID="'.$value->{'CustomerID'}.'" and 
                                                                                                                    id_compania="'.$id_compania.'"');

                                   $factor  = $this->model->Query_value('UNIT_MES_CONVRT','FACTOR','WHERE 
                                                                                                        UNIT="L" and
                                                                                                        UNIT_TO_CONVERT="'.$UNIT_TO_CONVERT.'" and
                                                                                                        ID_compania="'.$id_compania.'"');
                                                               
                                   $libra  = number_format($value->{'Quantity'},4) / number_format($factor,4);

                                
                               }

                              // if($UnitMeasure == 'L' || $UnitMeasure == 'LB'){   }
                            //CONVERSION A LIBRA 

                               if($value->{'PK_CHICO'}!='' || $value->{'PK_GRANDE'}!=''){ $libra  = ''; }
                               if($UnitMeasure == 'KG' ){  $UnitMeasure = 'LB'; }
 
                            $table .= '<tr>
                               <td width="15%" style="padding-right:10px; text-align: right;">'.$value->{'Item_id'}.'</td>
                               <td width="40%" ">('.$UnitMeasure.') '.$desc.'</td>
                               <td width="15%" style="text-align: center; padding-right">'.$value->{'PK_CHICO'}.'</td>
                               <td width="15%" style="text-align: center; padding-right">'.$value->{'PK_GRANDE'}.'</td>
                               <td width="15%" style="text-align: center; padding-right">'.number_format($libra,2).'</td>
                               </tr>';

                            }



                            echo $table;
                            ?>
                            </tbody></table>      

                        
                        </div>


</div>

</div>
</page>
</div>
</div>

<?php
//EXTRAE STRING ENTRE DOS CARACTERES
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
?>

