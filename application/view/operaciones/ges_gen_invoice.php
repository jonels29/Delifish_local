

<?PHP 
$ref = $id;

$ORDER = $this->GetOrdrHeaderById($id);
$LinesNum = $this->GetRegNum($id);

foreach($ORDER AS $value){

$value = json_decode($value);

$date = $value->{'date'};
$Cliente = $value->{'CustomerID'}.' - '.$value->{'CustomerName'};
$nopo = $value->{'CustomerPO'};
$typago = $value->{'termino_pago'};
$entrega = $value->{'entrega'};
$tylici = $value->{'tipo_licitacion'};
$nota = $value->{'observaciones'};
$taxID  = $value->{'TaxID'};
$total = number_format($value->{'Net_due'},2);
$TAX = number_format($value->{'OrderTax'},2);
$subtotal = number_format($value->{'Subtotal'},2);

}

$taxval = $this->model->Query_value('sale_tax','rate','where taxid="'.$taxID.'"');
$taxval = $taxval/100;


?>
<!--INI DIV ERRO-->


<!--ERROR -->

<div id="ErrorModal" class="modal fade" role="dialog">

  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" onclick="javascript:history.go(-1);" class="close" data-dismiss="modal">&times;</button>
        <h3 >Error</h3>
      </div>

      <div class="col-lg-12 modal-body">

      <!--ini Modal  body-->  

            <div id='ErrorMsg'></div>

      <!--fin Modal  body-->

      </div>

      <div class="modal-footer">

        <button type="button" onclick="javascript:history.go(-1); return true;" data-dismiss="modal" class="btn btn-primary" >OK</button>

      </div>

    </div>

  </div>

</div>

<!--modal-->
<!--INI DIV ERROR-->

<!--ADD JS FILE-->
<script  src="<?php echo URL; ?>js/operaciones/invoice/ges_gen_invoice.js" ></script>




<!--modal-->
<div id="fusion" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h3 >Detalle de Orden de Venta a fusionar</h3>

      </div>
      <div class="col-lg-12 modal-body">
      <!--ini Modal  body-->  
       <input type="text" id='aciref' class="col-lg-2" disabled/><br>
       <div id='ModalDetail' ></div>
      <!--fin Modal  body-->
      </div>
      <div class="modal-footer">

        <button type="button" onclick="javascript:AddLineToDetail();" data-dismiss="modal" class="btn btn-primary" >fusionar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

      </div>
    </div>
  </div>
</div>
<!--modal-->

<!--modal-->
<div id="cancelar" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h3 >Cancelar Ordenes</h3>

      </div>
      <div class="col-lg-12 modal-body">
      <p> Las siguientes ordenes fueron seleccionadas para fusion. Desear cancelar estas ordenes para no utilizarlas en futuras facturaciones ?</p>
      <!--ini Modal  body-->  
        <table id="CancelRef" class="display table table-striped table-condensed table-bordered " cellspacing="0">
           <thead>
              <tr >
               <th width="1%" class="text-center" ></th>
               <th width="5%" >Orden de Venta</th>
              </tr>
            </thead>
            <tbody id="table_aciref" >
            </tbody>
         </table>
      <!--fin Modal  body-->
      </div>
      <div class="modal-footer">

        <button type="button" onclick="javascript:CancelOrders();" data-dismiss="modal" class="btn btn-primary" >Aceptar</button>
        <button type="button" onclick="javascript:GenInvoice();" class="btn btn-default" data-dismiss="modal" >Obviar</button>

      </div>
    </div>
  </div>
</div>
<!--modal-->

<div class="page col-lg-12">

<input type="hidden"  id="saletaxid"  value="<?php echo $taxval; ?>" />
<input type="hidden"  id="NO_LINES"   value="<?php echo $LinesNum; ?>" />




<div  class="col-lg-12">
<!-- contenido -->

<div id="ERROR" ></div>


<h2>Generar Factura</h2>
	<div class="title col-lg-12"></div>

	<div class="col-lg-12">
	<!--INI  contenido -->
			<!--INI cabecera-->

			<div class="col-lg-12">

			    <fieldset>

			        <div class="col-lg-12"> 

			           <div class="col-lg-3">

			           <fieldset>

				           <p><strong>Referencia</strong></p>

				           <input class="col-lg-12"  id="ref" name="ref"  value="<?php echo $ref; ?>" readonly/>

			           </fieldset>

			         </div>

			          <div class="col-lg-3">

			           <fieldset>

				           <p><strong>Fecha</strong></p>

				           <input id="date" name="date"  value="<?php echo $date; ?>" readonly/>

			           </fieldset>

			         </div>

		             <div class="col-lg-2" >

			         <fieldset>

			           <p><strong>Entrega a:</strong></p>

			            <input class="col-lg-12" id="entrega" name="entrega"  value="<?php echo $entrega; ?>" />  

			         </fieldset>

			         </div>



			        <div class="col-lg-2">

			         <fieldset>

			          <p><strong>No. PO: </strong></p>

			            <input  class="col-lg-12" id="nopo" name="nopo"  value="<?php echo $nopo; ?>"/>

			         </fieldset>

			        </div> 

			         <div class="col-lg-2" >

			         <fieldset>

			             <p><strong>Tipo de Licitacion</strong></p>

			                <input class="col-lg-12" id="tipo_licitacion" name="tipo_licitacion" value="<?php echo $tylici; ?>" /> 

			         </fieldset>

			         </div>

                     <div class="separador col-lg-12"></div>

				     <div class="col-lg-6">

			           <fieldset>

				           <p><strong>Cliente</strong></p>

				           <input class="col-lg-12"  id="customer" name="customer"  value="<?php echo $Cliente; ?>" readonly/>

			           </fieldset>

			         </div>

			


			         <div class="col-lg-3" >

			         <fieldset>

			             <p><strong>Terminos de pago</strong></p>

			               <input  class="col-lg-12" id="termino_pago" name="termino_pago" value="<?php echo $typago; ?>" readonly/>

			         </fieldset>

			         </div>

			         <div class="col-lg-3" >

			         <fieldset>

			             <p><strong>Tax ID</strong></p>

			               <select  id="taxid" name="taxid" class="select col-lg-12" onchange="set_taxid(this.value,2);" required>

			            <?php  

			            $tax = $this->model->Get_sales_conf_Info(); 

			            foreach ($tax  as $datos) {

			              $tax  = json_decode($datos);

				              if($tax->{'taxid'}==$taxID){

				                $selected = 'selected';

				              }else{   

				                 $selected = '';

				              }

			            echo '<option value="'.$tax ->{'rate'}.'" '.$selected.'>'.$tax->{'taxid'}.'</option>';

			            }

			            ?>

			           </select>

			         </fieldset>

			       </div>

                   <div class="separador col-lg-12"></div>

			         <div class="col-lg-6" >

			           <fieldset>

			             <p><strong>Observaciones</strong></p>

			               <textarea class="col-lg-12"  rows="2" id="observaciones" name="observaciones" ><?php echo $nota; ?></textarea> 

			         </fieldset> 

			         </div>

			         <div class="col-lg-6">
					<fieldset>
					<legend><h4>Imprimir en:</h4></legend>
						<select class='select col-lg-6' id='Printer' name='Printer' >
						<option value="" selected></option>
							<?PHP 

					         $list = $this->getPrinterList();
					         $Printers = '';
					         $DefPrint = $this->GetUserDefaultPrinter();

					         foreach ($list as $key => $value) {
					         	$value = json_decode($value);

					           if($DefPrint == $value->{'SERIAL'}){ $selected = 'selected'; }else { $selected = ''; }

			                      $Printers .= '<option value="'.$value->{'SERIAL'}.'"  '.$selected.' >'.$value->{'DESCRIPCION'}.' ( '.$value->{'SERIAL'}.') </option>';

			                   }
                   
					         
					         
					         echo $Printers;

							 ?>
						</select>
						<br>
					</fieldset>
					</div>

			  </div>

			 <div class="separador col-lg-12"> </div>


			</fieldset>

			</div>

			<!--fin cabecera-->

			<div class="separador col-lg-12"> </div>


			<!--ini tabla-->

			<div class=" col-lg-12"> 

			<fieldset class="table_req" >

			<table id="invoice" class="display table table-striped table-condensed table-bordered " cellspacing="0">

			  <thead>

			    <tr >

			      <th width="10%" >Item ID</th>
			      <th width="15%" class="text-center">Descripcion</th>
			      <th width="5%" class="text-center">Gravable</th>
			      <th width="5%" class="text-center" >Ordenado</th>
			      <th width="5%" class="text-center" >A Despachar</th>
			      <th width="5%" class="text-center" >Precio Unit.</th>
			      <th width="5%" class="text-center" >Total</th>
			      <th width="1%" class="text-center" ></th>
			    </tr>

			  </thead>

			  <tbody id="table_req" > 

              <?php 

              $detail = $this->GetOrdrDetail2Invoice($ref);
              $i = 1;

              foreach ($detail as $key => $value) {

              	$ORDER = json_decode($value);

              	$PRICE = $this->numberFormatPrecision($ORDER->{'Unit_Price'});

			    echo  "<tr>
			              <td>".$ORDER->{'Item_id'}."[".$ORDER->{'ID'}."]</td>
			              <td contenteditable >".$ORDER->{'Description'}."</td>
			              <td>".$ORDER->{'Taxable'}."</td>
			              <td class='numb' id='qty".$i."' >".number_format($ORDER->{'Quantity'},5,'.',',')."</td>
			              <td class='numb' contenteditable onfocusout='calculate(".$i.");' id='desp".$i."' ></td>
			              <td class='numb' id='unitprice".$i."' >".$PRICE."</td>
			              <td class='numb' id='total".$i."' ></td>
			              <td class='numb' ><i onclick='del_tr(this)' style='color:red;' class='fa fa- fa-trash-o' ></i></td> 

			          </tr>";
                  $i += 1;
			      }
                
			    echo '</tbody></table>';

             ?>

			</fieldset>

			<div  class="separador col-lg-12" ></div>

			<div  class="col-lg-4" >
			
     		<fieldset>
            <legend>Fusionar Orden de venta</legend>
		    	<select id="addSoLines" class="select col-lg-12" onchange="ShowModal(this.value)" ></select>      
 
			</fieldset>  

			</div>

			<div  class="col-lg-4" ></div>

			<div  class="col-lg-4" >

			<fieldset>

			<div class="col-lg-7" >
			    <label class="col-lg-12" >Sub - Total:</label>
			    <label class="col-lg-12" >ITBMS: </label>
			    <label class="col-lg-12" >Total: </label>
			</div>

			<div class="col-lg-5" >
			  <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="subtotal" name="subtotal"  value="0.00" readonly />
			  <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="tax" name="tax" value="0.00" readonly/> 
			  <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="total" name="total" value="0.00" readonly />
			</div>

			</fieldset>

			</div>


            <div  class="separador col-lg-12" ></div>

			     <div  class="col-lg-10"></div>
				 <div   class="col-lg-2">
				    <button  style='float:right;' type="submit" onclick="ShowOrderToCancel();" class="btn btn-primary  btn-sm btn-icon icon-right" ><img  class="icon" src="img/Printer.png" />Generar</button>
				 </div>
                
            <div  class="separador col-lg-12" ></div>
			

			</div>

			<!--fin tabla-->

	<!--END contenido -->
	</div>
</div>
</div>
</div>

