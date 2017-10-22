

<!--ADD JS FILE-->
<script  src="<?php echo URL; ?>js/operaciones/requisiciones/req_reception.js" ></script>

<input id="count_lines" type="hidden" value="" />

<div class="page col-lg-12">

<!--INI DIV ERRO-->
<div id="ERROR" class="alert alert-danger"></div>
<!--INI DIV ERROR-->

<div  class="col-lg-12">

<h2>Recepción de Materiales</h2>
<div class="title col-lg-12"></div>
<div class="col-lg-12">

<!-- contenido -->


		 <div class="col-lg-4" >
         <fieldset>
	         <p><strong>No. Requisición:</strong></p>
	          <input class="col-lg-10" id="buscar" name="buscar"/>&nbsp; 
	          <a title="Buscar ID" href="javascript:void(0)" onclick="get_reception();"><i class="fa fa-search"></i></a>

         </fieldset>
         </div>

         <div class="title col-lg-12"></div>
         <div id="info" class="col-lg-12"></div>
</div>
</div>
</div>
