<script type="text/javascript">
	
$(window).load(function(){

window.print();

});
</script>

<?php



$clause= 'WHERE Purchase_Header_Imp.ID_compania="'.$this->model->id_compania.'" and  Purchase_Header_Imp.TransactionID="'.$id.'"' ; 
        
$fact = $this->model->Get_fact_header('ASC','1',$clause);
            
$value = json_decode($fact[0]);
                 
$DATE = date('m/d/Y',strtotime($value->{'Date'}));

$REF =  str_pad($value->{'TransactionID'}, 9 ,"0",STR_PAD_LEFT);
  
$VENDOR = $value->{'VendorName'};
$NO_FACTURA = $value->{'PurchaseNumber'};
$TOTAL = number_format($value->{'Net_due'},2,'.',',');
$NOTA = $value->{'nota'};

$USER = $this->model->Get_User_Info($value->{'USER'});
$USER = json_decode($USER[0]);
$USER_NAME = $USER->{'name'}.' '.$USER->{'lastname'};


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

                <h2 class="h_invoice_header" >Factura de Compra</h2>
                 <table class="tableInvoice">
                 	
                 	<tr>
                 	  <th><strong>No. Ref.:</strong> <?php echo $REF; ?></th>
                 	  <th></th>
                 	</tr>
                    <tr>
                      <th><strong>No. Factura:</strong> <?php echo $NO_FACTURA; ?></th>
                      <th></th>
                    </tr>
                 	<tr>
                 	  <th><strong>Fecha:</strong> <?php echo $DATE; ?></th>
                 	  <th></th>
                 	</tr>
                 	<tr>
                 	  <th><strong>Creado por: </strong> <?php echo $USER_NAME; ?> </th>
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
                            <strong> Proveedor </strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="invoice-div invoice-div4  panel-body">
                           <?php echo $VENDOR; ?>
                        </div>
                        
                    </div>
                  
                </div>
               
                <div class="col-xs-2"></div>
      <!--ship  to 
	           
                <div class="col-xs-5">
                    <div class="panelB panel-default">
                        <div class="panel-heading">
                            <strong> Entrega a </strong>
                        </div>
                       
                        <div class="invoice-div invoice-div4  panel-body">
                            <?php// echo  $entrega ?>
                        </div>
                           
                           
                       
                        
                    </div> 
                  
                </div>-->
               </div>
  <!--              <div class="row">

                <div class="col-xs-12">
                    <div class="panelB noMarginB  panel-default">
                        <div class="panel-heading">
                        
                             <TABLE   width="100%" >
                             	<TR >
                             		<TH width="33%">ID Cliente</TH>
                             		<TH width="33%">No. PO</TH>
                             		<TH width="33%">Rep. de ventas</TH>
                             	</TR>
                            </TABLE>
                       
                        </div>

                       
                        <div class="invoice-div panel-body">
       

                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php //echo  $custname; ?></div></div>
                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php //echo  $PO; ?></div></div>
                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php //echo  $salesRep; ?></div></div>
					   



                        </div>
                        
                    </div>
                  
                </div>
                <div class="col-xs-12">
                    <div class="panelB  panel-default">
                        <div class="panel-heading">
                        
                             <TABLE   width="100%" >
                             	<TR >
                             		<TH width="33%">Contacto</TH>
                             		<TH width="33%">Tipo de Licitacion</TH>
                             		<TH width="33%">Terminos de pago</TH>
                             	</TR>
                            </TABLE>
                       
                        </div>

                        
                        <div class="invoice-div panel-body">
       

                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php //echo  $contact; ?></div></div> 
                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php //echo  $tipo_lic; ?></div></div>
                        <div class="col-xs-4 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php //echo  $termino_pago; ?></div></div>

                        </div>
                        
                    </div>
                  
                </div>




               </div> -->

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
       

                        <div class="col-xs-12 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php echo  $NOTA; ?></div></div>
                    

                        </div>
                        
                    </div>
                  
                <div class="separador col-xs-12"></div>



                <div class="row">

                <div class="col-xs-12">
                    <div class="panelB noMarginB  panel-default">
<!--                         <div class="panel-heading">
                        
                             <TABLE   width="100%" >
                             	<TR >
                             		<TH width="15%">Cantidad</TH>
                             		<TH width="50%">Descripcion</TH>
                             		<TH width="15%">Precio Unit.</TH>
									<TH width="15%">Monto</TH>
                             	</TR>
                            </TABLE>
                       
                        </div> -->
     

                        <!-- /.panel-heading -->
                        <div class="invoice-div panel-body">

                        <?php 



                        $query ="SELECT * FROM `Purchase_Header_Imp`
                        inner JOIN `Purchase_Detail_Imp` ON Purchase_Detail_Imp.TransactionID = Purchase_Header_Imp.TransactionID
                        WHERE Purchase_Header_Imp.TransactionID='".$id."'  and 
                              Purchase_Header_Imp.ID_compania='".$this->model->id_compania."' and
                              Purchase_Detail_Imp.ID_compania='".$this->model->id_compania."'
                        order BY Purchase_Detail_Imp.ID ASC";


                        $fact_items= $this->model->Query($query);

                        echo '<table id="example-12" class="table table-striped table-bordered" cellspacing="0"  >
                              <thead>
                                <tr>
                                  <th>Cant.</th>
                                  <th>Item</th>
                                  <th>Unidad</th>
                                  <th>Descripcion</th>
                                  <th>Cta. GL</th>
                                  <th>Precio Unit.</th>
                                  <th>Total</th>
                                  <th>Job</th>
                                </tr>
                              </thead><tbody>';


                        foreach ($fact_items as $datos) {

                           $fact_items = json_decode($datos);

                            $id= "'".$fact_items->{'InvoiceNumber'}."'";


                        echo  "<tr>
                                  <td class='numb'>".number_format($fact_items->{'Quantity'},5,'.',',')."</td>
                                  <td>".$fact_items->{'Item_id'}."</td>
                                  <td>".$fact_items->{'UnitMeasure'}.'</td>
                                  <td>'.$fact_items->{'Description'}.'</td>
                                  <td class="numb">'.$fact_items->{'GL_Acct'}."</td>
                                  <td class='numb'>".number_format($fact_items->{'Unit_Price'},4,'.',',')."</td>
                                  <td class='numb'>".number_format($fact_items->{'Net_line'},4,'.',',').'</td>
                                  <td>'.$fact_items->{'JobID'}.'</td>
                              </tr>';

                          }

                        echo '</tbody></table>';

                        ?>
                            


                        
                        </div>
                        
                    </div>
                  
                </div>
                </div>
                <div class="row">

                <div class="col-xs-12">
                    <div class="panelB noMarginB  panel-default">
                       

                        <!-- /.panel-heading -->
                        <div class="invoice-div panel-body">
<!--        
                        <div class="col-xs-4"></div>
						<div class="col-xs-6 panelB noMarginB panel-default"><div class="invoice-div3  panel-body">Sub Total</div></div>
						<div class="col-xs-2 panelB noMarginB panel-default"><div class="invoice-div3  panel-body"><?php echo  $subtotal;?></div></div>
						<div class="col-xs-4"></div>
						<div class="col-xs-6 panelB noMarginB panel-default"><div class="invoice-div3  panel-body">ITBMS</div></div>
						<div class="col-xs-2 panelB noMarginB panel-default"><div class="invoice-div3  panel-body"><?php echo  $tax;?></div></div>
						<div class="col-xs-4"></div> -->
						<div class="col-xs-8 panelB noMarginB panel-default panel-heading"><div class="invoice-div3  panel-body">TOTAL </div></div>
						<div class="col-xs-4 panelB noMarginB panel-default panel-heading"><div class="invoice-div3  panel-body"><?php echo  $TOTAL; ?></div></div>


                        </div>
                        
                    </div>
                  
                </div>
                </div>
                

</div>

</div>
</page>
</div>
</div>
