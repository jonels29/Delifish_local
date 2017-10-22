<script type="text/javascript">

$(document).ready(function(){

var table =  $("#mercancia").dataTable();


table.yadcf([
{column_number : 0},
{column_number : 1},
{column_number : 2}
]); 
      
});


</script>

<div class="page col-lg-11">

<div  class="col-lg-12">
<!-- contenido -->
<h2>Facturas de compras</h2>
<div class="title col-lg-12"></div>

<div class="col-lg-12">


<div  class="col-lg-6"> 
<fieldset id="mercanciaF" >
<legend>Lista de Facturas</legend>

<table id="mercancia" class="table table-striped " cellspacing="0"  >
    <thead>
      <tr>
        <th width="30%">ID. Compra.</th>
        <th width="30%">Fecha</th>
        <th width="30%">Proveedor</th>
        <th width="15%"></th>
      </tr>
    </thead>
    <tbody>
		<?php

		$table = $this->model->fact_compras_list();

		foreach ($table as $value) {

		$value = json_decode($value);

		$inv = "'".$value->{'PurchaseID'}."'";
		$url = "'".URL."'"; 

		echo "<tr>
		        <td >".$value->{'PurchaseNumber'}.'</td>
		        <td >'.$value->{'fecha'}.'</td>
		        <td >'.$value->{'VendorName'}.'</td><td><a href="#"" onclick="items('.$url.','.$inv.');" >ver <i class="fa  fa-search"></i></a></td>
		      </tr>';

		}

		?>   	
      </tbody>
</table>


</fieldset>
</div>

<div  class="col-lg-6"> 
<fieldset id="mercanciaF">
	<legend>Items</legend>


  <table id="Items" class="table table-striped" cellspacing="0"  >
    <thead>
      <tr>
        <th width="20%">Codigo Item</th>
        <th width="30%">Descripcion</th>
        <th width="10%">Cantidad</th>
        <th width="10%">Detalle Item</th>
      </tr>
    </thead>
 
 <tbody id="tableInv">
 	
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



