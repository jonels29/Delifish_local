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
                 	  <th><strong>Voice :</strong><?php echo $tel ; ?></th>
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

                <h2 class="h_invoice_header" >Salida de Mercancia</h2>
                 <table class="tableInvoice">
                 	
                 	<tr>
                 	  <th><strong>Referencia:</strong> <?php echo $ref; ?></th>
                 	  <th></th>
                 	</tr>
                 	<tr>
                 	  <th><strong>Fecha:</strong> <?php echo $date; ?></th>
                 	  <th></th>
                 	</tr>
                 	<tr>
                 	  <th><strong>Responsable: </strong> <?php echo $rep; ?></th>
                 	  <th></th>
                 	</tr>
                 	<tr>
                 	  <th></th>
                 	  <th></th>
                 	</tr>


                 </table>
                  
                </div>
               </div>

	 <!-- to  
	           <div class="row">
                <div class="col-xs-5">
                    <div class="panelB panel-default">
                        <div class="panel-heading">
                            <strong> TO </strong>
                        </div>
                      
                        <div class="invoice-div invoice-div4  panel-body">
                           <?php echo $custid; ?>
                        </div>
                        
                    </div>
                  
                </div>
               
                <div class="col-xs-2"></div>-->
      <!--ship  to 
	           
                <div class="col-xs-5">
                    <div class="panelB panel-default">
                        <div class="panel-heading">
                            <strong> SHIP TO</strong>
                        </div>
                        
                        <div class="invoice-div invoice-div4  panel-body">
                            <?php echo  $custname; ?>
                        </div>
                           
                           
                       
                        
                    </div>
                  
                </div> 
               </div>-->
               <div class="row">

                <div class="col-xs-12">
                    <div class="panelB noMarginB  panel-default">
                        <div class="panel-heading">
                        
                             <TABLE   width="100%" >
                             	<TR >
                             		<TH width="25%">Proyecto</TH>
                             		<TH width="25%">Fase</TH>
                             		<TH width="25%">Centro de costo</TH>
                                    <TH width="25%">Cuenta contra partida</TH>
                             	</TR>
                            </TABLE>
                       
                        </div>

                        <!-- /.panel-heading -->
                        <div class="invoice-div panel-body">
       

                        <div class="col-xs-3 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php echo  $Job; ?></div></div>
                        <div class="col-xs-3 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php echo  $fase; ?></div></div>
                        <div class="col-xs-3 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php echo  $ccost; ?></div></div>
                        <div class="col-xs-3 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php echo  $accnt; ?></div></div>


                        </div>
                        
                    </div>
                  
                </div>
   
                <div class="separador col-xs-12"></div>

                <div class="col-xs-12">
                    <div class="panelB noMarginB  panel-default">
                        <div class="panel-heading">
                        
                             <TABLE   width="100%" >
                                <TR >
                                    <TH width="100%">Descripcion</TH>
                                    
                                </TR>
                            </TABLE>
                       
                        </div>

                        <!-- /.panel-heading -->
                        <div class="invoice-div panel-body">
       

                        <div class="col-xs-12 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php echo  $desc; ?></div></div>
                    

                        </div>
                        
                    </div>
                  
                </div>



               </div>

               <div class="separador col-xs-12"></div>

                <div class="row">

                <div class="col-xs-12">
                    <div class="panelB noMarginB  panel-default">
                        <div class="panel-heading">
                        
                             <TABLE   width="100%" >
                             	<TR >
                             		<TH width="20%">Cantidad</TH>
                                    
                             		<TH width="25%">Producto</TH>

                             		<TH width="50%">Descripcion</TH>
                             	</TR>
                            </TABLE>
                       
                        </div>
     

                        <!-- /.panel-heading -->
                        <div class="invoice-div panel-body">
       

                        <div class="col-xs-2 panelB noMarginB panel-default"><div class="invoice-div2  panel-body"><?php  foreach ($ORDER as  $value) { $value = json_decode($value);  

                            $qty = $value->{'Quantity'}*(-1);

                        	if ($qty >'0'){ ECHO number_format($qty ,0).'<br><br>'; }else{ ECHO '-<br><br>'; }


                         } ?></div></div>

                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div2  panel-body"><?php  foreach ($ORDER as  $value) { $value = json_decode($value);  ECHO $value->{'ItemID'}.'<br><br>';

                        
             
                         } ?></div></div>



                        <div class="col-xs-6 panelB noMarginB panel-default"><div class="invoice-div2  panel-body"><?php  foreach ($ORDER as  $value) { $value = json_decode($value); 

                         $desc = $this->model->Query_value('Products_Exp','Description','WHERE ProductID="'.$value->{'ItemID'}.'"');

                         ECHO  $desc.'<br><br>'; } ?></div></div>


                        </div>
                        
                    </div>
                  
                </div>
                </div>
                 <div class="separador col-xs-12"></div>
 <!-- entregado por -->  
               <div class="row">
                <div class="col-xs-5">
                    <div class="panelB panel-default">
                        <div class="panel-heading">
                            <strong> Entregado por: </strong>
                        </div>
                      
                        <div class="invoice-div invoice-div4  panel-body">
                           
                        </div>
                        
                    </div>
                  
                </div>
               
                <div class="col-xs-2"></div>
      <!--recibido por -->
               
                <div class="col-xs-5">
                    <div class="panelB panel-default">
                        <div class="panel-heading">
                            <strong> Recibido por: </strong>
                        </div>
                        
                        <div class="invoice-div invoice-div4  panel-body">
                            
                        </div>
                           
                           
                       
                        
                    </div>
                  
                </div> 
               </div>
                

</div>

</div>
</page>
</div>
</div>
