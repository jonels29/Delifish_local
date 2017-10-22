<?php
$NO_LINES =  $this->model->Query_value('FAC_DET_CONF','NO_LINES','where ID_compania="'.$this->model->id_compania .'"');

echo '<input type="hidden" id="FAC_NO_LINES" value="'.$NO_LINES .'" />'; 

?>

<!--ADD JS FILE-->
<script  src="<?php echo URL; ?>js/operaciones/compras/ges_fact_compras.js" ></script>


<div class="page col-lg-12">


<!--INI DIV ERRO-->
<div id="ERROR" class="alert alert-danger"></div>
<!--INI DIV ERROR-->

<div  class="col-lg-12">
<!-- contenido -->
<h2>Compras/Recibo de mercancia</h2>
<div class="title col-lg-12"></div>

<div class="col-lg-12">

 <!-- INI VENTANA -->

<input type="hidden" id='URL' value="<?php ECHO URL; ?>" />


	<div class="col-lg-6">
	   <button class="btn btn-blue btn-sm"  data-toggle="collapse" data-target="#Solicitud" onclick="javascript:  $(this).find('i').toggleClass('fa-plus-circle fa-minus-circle');"><i  class='fa fa-minus-circle'></i> Detalle de Factura</button>
	   <input type="submit" onclick="send_fact();" class="btn btn-primary  btn-sm btn-icon icon-right" value="Procesar" />
	  
	</div>


<div class="separador col-lg-12"></div>

<div id='Solicitud' class="collapse in col-lg-6" >
	
<fieldset>
<input type="hidden" id='user' value="<?php echo $active_user_id; ?>" />

<div class="col-lg-12">
 <div   class="col-lg-6"> 
                     <label style="display:inline">Referencia </label>
    <INPUT class="input-control" type="text" name="FACT_NO" id="FACT_NO" readonly value="
     <?php echo  $this->model->Get_CO_No(); ?>" />
</div>

<div  class="col-lg-1"></div>

 <div   class="col-lg-5">
  <label style="display:inline" > Fecha : </label>
  <input style="text-align: center;" type="date" class="input-control" name="date" id="date" value="<?php echo date('Y-m-d');?>" />
  </div>

</div>


<div class="title col-lg-12"></div>
         <div class="col-lg-8">
         <fieldset>
			<p><strong>Proveedor</strong></p>
				
			<select  id="vendor" name="vendor" class="select col-lg-8" onchange="PO_Filter(this.value);" required>

				<option selected disabled></option>

				<?php  
				$vendor = $this->model-> get_VendorList(); 

				foreach ($vendor as $datos) {
																	
				$VENDOR_INF = json_decode($datos);
				echo '<option value="'.$VENDOR_INF ->{'VendorID'}.'" >'.$VENDOR_INF->{'Name'}."</option>";

				}
				?>
										
			</select>	
		 </fieldset>
        </div>
		 <div class="col-lg-4">
         <fieldset>
         	
         	<div class="col-lg-12">
         		<strong>No. Factura </strong><input type="text" id="nopo" name="nopo" /><br>
         		
         	</div>
         </fieldset>
		</div> 
		 <div class="col-lg-4">
         <fieldset>
         	
         	<div class="col-lg-12">
         		<strong>Ord. Compra</strong>

         		<select  id="ordc" name="ordc" class="select col-lg-12"  onchange="PO_SELECTED(this.value);" required>

				<option selected disabled></option>

				<!-- este select se va llenar dependiendo del vendor seleccionado -->

			</select>	
         		
         	</div>
         </fieldset>
		</div> 

<div class="title col-lg-12"></div>        
   	<div  class="separador col-lg-12"></div>
		
	 <div class="col-lg-12">
         <fieldset>
       	
         	<div class="comment-text-area col-lg-12">
         		<strong>Observaciones: </strong><textarea class="textinput" rows="5" cols="70" id="nota" name="nota">  </textarea>
        		
         	</div>
         </fieldset>
		</div> 
   
		

					
</fieldset>

</div>

<div class="separador col-lg-12"></div>		

<div class=" col-lg-12"> 

<fieldset class="table_req" >
<table id="table_req_tb" class="display table table-striped table-condensed table-bordered " cellspacing="0">
	<thead>
		<tr >
			<th width="10%" >
			<select id="check_val" onchange="release_po_no(this.value);">
			<option value="1" >Reglon</option>
			<option value="2" >Codigo</option> 
			</select></th>
			<th width="30%" class="text-center">Descripcion</th>
 			<th width="15%" class="text-center">Unidad</th>
			<th width="15%" class="text-center">Cantidad</th>
			<th width="15%" class="text-center">Precio Unit.</th>
			<th width="15%" class="text-center">Total</th>
 			<th width="10%" class="text-center">Proyecto</th>
			<th width="10%" class="text-center">Fase</th>
			<th width="10%" class="text-center">Ctr. Costo</th>
		
		</tr>
	</thead>
	<tbody id="table_req" >	

	</tbody>
</table>
</fieldset>

<div  class="separador col-lg-12" ></div>
<div  class="col-lg-8" ></div>
<div  class="col-lg-4" >
<fieldset>
<div class="col-lg-7" >
 	<label class="col-lg-12" >Sub - Total:</label>
    <label class="col-lg-12" >ITBMS: </label>
    <label class="col-lg-12" >Total: </label>
</div>

<div class="col-lg-5" >
	<input class="col-lg-12"  style="text-align:right;" type="number" step="0.01" id="subtotal" name="subtotal"  value="0" readonly />
    <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="tax" name="tax" value="0" onfocusout='sumar_total();' /> 
    <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="total" name="total" value="0" readonly />
</div>
</fieldset>
</div>
<div  class="separador col-lg-12" ></div>


</div>
</div>
</div>



</div>
</div>
</div>


