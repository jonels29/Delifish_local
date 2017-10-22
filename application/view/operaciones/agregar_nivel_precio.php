<script type="text/javascript">

 
// ********************************************************
// * Aciones cuando la pagina ya esta cargada
// ********************************************************
$(window).load(function(){

$('#ERROR').hide();

});

</script>

<div class="page col-lg-12">

<!--INI DIV ERRO-->
<div id="ERROR" class="alert alert-danger"></div>
<!--INI DIV ERROR-->

<div  class="col-lg-12">
<!-- contenido -->
<h2>Nivel de Precios</h2>
<div class="title col-lg-12"></div>

<div class="col-lg-12">

<?php



$view = '<fieldset>
		<legend><h4>Gestion Niveles de Precio</h4></legend>
		<label>Id Cliente:</label><br>
		<input type="text" class="col-lg-3" name="id_cliente" value="'.$this->id_cus.'"><br>
		<label>Nombre del Cliente:</label><br>
		<input type="" class="col-lg-12" name="nombre_cliente" value="'.$this->cus_name.'">';

echo $view;



?>

<!-- <fieldset>
		<legend><h4>Gestion Niveles de Precio</h4></legend>

		<label>Id Cliente:</label>
		<input type="text" name="id_cliente" value=""><br>
		<label>Nombre del Cliente:</label>
		<input type="" name="nombre_cliente" value="">	 -->



</div>
</div>
</div>