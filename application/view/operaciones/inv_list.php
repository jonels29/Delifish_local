<div class="page col-xs-12">

<div  class="col-xs-12">
<!-- contenido -->
<h2>Lista de inventario</h2>
<div class="title col-xs-12"></div>
<div class="col-lg-12">

<fieldset>
<legend><h4>Listado de inventario</h4></legend>
<div class="col-xs-12">
 <script type="text/javascript">
  jQuery(document).ready(function($)
  {
   
   var table = $("#productos").dataTable({
      aLengthMenu: [
        [10, 25,50,-1], [10, 25, 50,"All"]
      ]
    });

table.yadcf([
{column_number : 0},
{column_number : 1}
]); 


  });
  </script>



<table id="productos" class="table table-striped " cellspacing="0"  >
<thead>
<tr>
<th>Codigo</th>
<th >Descripcion</th>
<th>Unidad</th>
<th>Cant.</th>
<!--<th width="10%">Fecha Venc.</th>
<th width="10%">Lote</th>
<th width="10%">Compa&ntildeia</th>-->
<th>Costo Unit.</th>
<th>Detalle</th>
</tr>
</thead>
<tbody>
<?php
			$Item = $this->model->get_ProductsList();

			foreach ($Item as $datos) {


			$Item = json_decode($datos);


			echo	'<tr>
					<td>'.$Item->{'ProductID'}.'</td>
					<td>'.$Item->{'Description'}.'</td>
					<td>'.$Item->{'UnitMeasure'}.'</td>
					<td class="numb">'.number_format($Item->{'QtyOnHand'},0, '.', ',').'</td>
					<td class="numb">'.number_format($Item->{'LastUnitCost'},4, '.', ',').'</td>
					<td><a href="'.URL.'index.php?url=ges_inventario/inv_info/'.$Item->{'ProductID'}.'" >Ver <i class="fa  fa-search"></i></a></td>';
				}

?>

</tbody>
</table>
</fieldset>
</div>
</div>
</div>