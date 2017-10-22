<script type="text/javascript">
	
$(window).load(function(){

window.print();



});



</script>

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
                 <table class="tableInvoice">
                    <tr>
                      <th><strong><?php echo $address ; ?></strong></th>
                    </tr>

                    <tr>
                      <th><strong>Tel. :</strong><?php echo $tel ; ?></th>
                      <th></th>
                    </tr>
                    <tr>
                      <th><strong>Fax :</strong> <?php echo $fax ; ?></th>
                      <th></th>
                    </tr>


                 </table>
                   
                </div>
             
                <div class="col-xs-2"></div>
 
<!-- Order Info  -->   
	           
                <div class="invoice_header tableInvDe col-xs-5">

                <h2 class="h_invoice_header" >Factura</h2>
                 <table class="tableInvoice">
                    
                    <tr>
                      <th><strong>No. Factura:</strong> <?php echo $saleorder; ?></th>
                      <th></th>
                    </tr>
                    <tr>
                      <th><strong>Fecha:</strong> <?php echo $saledate; ?></th>
                      <th></th>
                    </tr>
                    <tr>
                      <th><strong>Enviado por: </strong> - </th>
                      <th></th>
                    </tr>
                    <tr>
                      <th></th>
                      <th></th>
                    </tr>


                 </table>
                  
                </div>
               </div>

	 <!-- to  -->
	           <div class="row">
                <div class="col-xs-5">
                    <div class="panelB panel-default">
                        <div class="panel-heading">
                           <strong> Para </strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="invoice-div invoice-div4  panel-body">
                           <?php echo $custid; ?>
                        </div>
                        
                    </div>
                  
                </div>
               
                <div class="col-xs-2"></div>
      <!--ship  to  -->
	           
                <div class="col-xs-5">
                    <div class="panelB panel-default">
                        <div class="panel-heading">
                             <strong> Entrega a </strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="invoice-div invoice-div4  panel-body">
                            <?php echo  $custname; ?>
                        </div>
                           
                            <!-- <a href="#" class="btn btn-default btn-block">View All Alerts</a>-->
                       
                        
                    </div>
                  
                </div>
               </div>
               <div class="row">

                <div class="col-xs-12">
                    <div class="panelB noMarginB  panel-default">
                        <div class="panel-heading">
                        
                             <TABLE   width="100%" >
                                <TR >
                                    <TH width="33%">ID Cliente</TH>
                                    <TH width="33%">No. OC</TH>
                                    <TH width="33%">Rep. de ventas</TH>
                                </TR>
                            </TABLE>
                       
                        </div>

                        <!-- /.panel-heading -->
                        <div class="invoice-div panel-body">
       

                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php echo  $custid; ?></div></div>
                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body">-</div></div>
                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php echo  $salesRep; ?></div></div>
					   



                        </div>
                        
                    </div>
                  
                </div>
                <div class="col-xs-12">
                    <div class="panelB  panel-default">
                        <div class="panel-heading">
                        
                             <TABLE   width="100%" >
                                <TR >
                                    <TH width="33%">Contacto</TH>
                                    <TH width="33%">Tipo de Licitaciones</TH>
                                    <TH width="33%">Terminos de pago</TH>
                                </TR>
                            </TABLE>
                       
                        </div>

                        <!-- /.panel-heading -->
                        <div class="invoice-div panel-body">
       

                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php echo  $contact; ?></div></div>
                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"></div></div>
                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"></div></div>

                        </div>
                        
                    </div>
                  
                </div>




               </div>

               <div class="separador col-xs-12"></div>

                
                    <div class="panelB noMarginB  panel-default">
                        <div class="panel-heading">
                        
                             <TABLE   width="100%" >
                                <TR >
                                    <TH width="100%">Observaciones</TH>
                                    
                                </TR>
                            </TABLE>
                       
                        </div>

                        <!-- /.panel-heading -->
                        <div class="invoice-div panel-body">
       

                        <div class="col-xs-12 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php echo  $desc; ?></div></div>
                    

                        </div>
                        
                    </div>
                  
                <div class="separador col-xs-12"></div>


                <div class="row">

                <div class="col-xs-12">
                    <div class="panelB noMarginB  panel-default">
                        <div class="panel-heading">
                            <TABLE   width="100%" >
                                <TR >
                                    <TH width="15%">Cantidad</TH>
                                    <TH width="55%">Descripcion</TH>
                                    <TH width="15%">Precio Unit.</TH>
                                    <TH width="15%">Monto</TH>
                                </TR>
                            </TABLE>
                        </div>
     

                        <!-- /.panel-heading -->
                        <div class="invoice-div panel-body">
  <table width="100%" >

<?php  foreach ($ORDER as  $value) { 

$value = json_decode($value);  



if ($value->{'Quantity'}>'0'){ $QTY = number_format($value->{'Quantity'},0); }else{  $QTY = ''; }

if (strpos($value->{'Description'},'Lote')){ 

    $uniP = '';  
    $netdue = ''; 
    $Description = trim($value->{'Description'});
   
}else{ 
    $uniP = number_format($value->{'Unit_Price'},2); 
    $netdue = number_format($value->{'Net_line'},2); 
    $Description = '<br>'.$value->{'Item_id'}.'<br>'.$value->{'Description'};
}



$table .= '<tr>
   <td width="15%" style="padding-right:10px; text-align: right;">'.$QTY.'</td>
   <td width="55%" ">'.$Description.'</td>
   <td width="15%" style="text-align: right; padding-right">'.$uniP.'</td>
   <td width="15%" style="text-align: right; padding-right">'.$netdue.'</td>
   </tr>';

}



echo $table;
?>
</table>     



                        </div>
                        
                    </div>
                  
                </div>
                </div>
                <div class="row">

                <div class="col-xs-12">
                    <div class="panelB noMarginB  panel-default">
                       

                        <!-- /.panel-heading -->
                        <div class="invoice-div panel-body">
       
                        <div class="col-xs-4"></div>
                        <div class="col-xs-6 panelB noMarginB panel-default"><div class="invoice-div3  panel-body">Sub Total</div></div>
                        <div class="col-xs-2 panelB noMarginB panel-default"><div class="invoice-div3  panel-body"><?php echo  $subtotal;?></div></div>
                        <div class="col-xs-4"></div>
                        <div class="col-xs-6 panelB noMarginB panel-default"><div class="invoice-div3  panel-body">ITBMS</div></div>
                        <div class="col-xs-2 panelB noMarginB panel-default"><div class="invoice-div3  panel-body"><?php echo  $tax;?></div></div>
                        <div class="col-xs-4"></div>
                        <div class="col-xs-6 panelB noMarginB panel-default panel-heading"><div class="invoice-div3  panel-body">TOTAL </div></div>
                        <div class="col-xs-2 panelB noMarginB panel-default panel-heading"><div class="invoice-div3  panel-body"><?php echo  $total;?></div></div>



                        </div>
                        
                    </div>
                  
                </div>
                </div>
                

</div>

</div>
</page>
</div>
</div>
