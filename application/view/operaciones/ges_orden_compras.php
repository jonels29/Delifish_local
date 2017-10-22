<script type="text/javascript">

$(document).ready(function(){

var table =  $("#mercancia").dataTable();


/*table.yadcf([
{column_number : 0},
{column_number : 1},
{column_number : 2}
]); */
      
});


</script>

<div class="page col-lg-11">

<div  class="col-lg-12">
<!-- contenido -->  
<h2>Orden de compra</h2>
<div class="title col-lg-12"></div>

<div class="col-lg-12">


<div  class="col-lg-5"> 
<fieldset id="mercanciaF" >
<legend>Detalle de compra</legend>

<table   class="table table-striped " cellspacing="0"  >
    <tbody>
		<?php

	  $value = json_decode($oc[0]);

		$inv = "'".$value->{'PurchaseID'}."'";
		$url = "'".URL."'"; 


		echo " <tr><th style='text-align:left;' width='25%'>ID. Compra.</th><td >".$value->{'PurchaseOrderNumber'}.'</td></tr>
		       <tr><th style="text-align:left;" width="25%">Fecha</th><td >'.$value->{'Date'}.'</td></tr>
           <tr><th style="text-align:left;" width="25%">Requisición</th><td >'.$value->{'CustomerSO'}.'</td></tr>
		       <tr><th style="text-align:left;" width="25%">Proveedor</th><td >'.$value->{'VendorName'}.'</td></tr>
           <tr><th style="text-align:left;" width="10%">Estado</th> <td >'.$value->{'WorkflowStatusName'}.'</td></tr>
           <tr><th style="text-align:left;" width="10%">Asignado a</th> <td >'.$value->{'WorkflowAssignee'}.'</td></tr>
          <tr><th style="text-align:left;" width="30%">Nota</th><td >'.$value->{'WorkflowNote'}.'</td></tr>';
  
              
               


		?>   	
      </tbody>
</table>


</fieldset>
</div>


<div  class="col-lg-7"> 
<fieldset id="mercanciaF">
	<legend>Items</legend>


  <table id="Items" class="table table-striped" cellspacing="0"  >
    <thead>
      <tr>
        <th width="20%">Codigo Item</th>
        <th width="30%">Descripcion</th>
        <th width="10%">Cantidad</th>
        <th width="10%">Precio Uni.</th>
        <th width="10%">Total</th>
      </tr>
    </thead>
 
 <tbody >
 <?php
 	foreach ($oc as $value) {

    $value = json_decode($value);

    $inv = "'".$value->{'PurchaseID'}."'";
    $url = "'".URL."'"; 

    echo "<tr>
            <td >".$value->{'Item_id'}.'</td>
            <td >'.$value->{'Description'}.'</td>
            <td >'.$value->{'Quantity'}.'</td>
            <td >'.$value->{'Unit_Price'}.'</td>
            <td >'.$value->{'NetLine'}.'</td>
          </tr>';

    }

    ?>    
 </tbody>

</table>
</fieldset>
</div>


<div class="separador col-lg-11"></div>

</div>
</div>



</div>
</div>
</div>



