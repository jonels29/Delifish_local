
<?php

$NO_LINES =  $this->model->Query_value('FAC_DET_CONF','NO_LINES','where ID_compania="'.$this->model->id_compania .'"');

echo '<input type="hidden" id="FAC_NO_LINES" value="'.$NO_LINES .'" />'; 

?>

<!--INI DIV ERRO-->
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
<script  src="<?php echo URL; ?>js/operaciones/requisiciones/req_crear.js" ></script>


<div class="page col-lg-12">

<!--INI DIV ERRO-->
<div id="ERROR" class="alert alert-danger"></div>
<!--INI DIV ERROR-->

<div  class="col-lg-12">
<!-- contenido -->
<h2>Requisiciones</h2>
<div class="title col-lg-12"></div>
<div class="separador col-lg-12"></div>

 <!-- FIN VENTANA -->

<input type="hidden" id='URL' value="<?php ECHO URL; ?>" />


	<div class="col-lg-6">
	   <button class="btn btn-blue btn-sm"  data-toggle="collapse" data-target="#Solicitud" onclick="javascript:  $(this).find('i').toggleClass('fa-minus-circle fa-plus-circle ');"><i  class='fa fa-minus-circle'></i> Detalle de solicitud</button>
	   <input type="submit" onclick="send_req_order();" class="btn btn-primary  btn-sm btn-icon icon-right" value="Procesar" />
	  
	</div>


<div class="separador col-lg-12"></div>

<div id='Solicitud' class="collapse in col-lg-6" >
	
<fieldset>
<input type="hidden" id='user' value="<?php echo $active_user_id; ?>" />

<div class="col-lg-12">

<div   class="col-lg-6"> 
<label style="display:inline" > Proyecto : </label>
<select id="JOBID" >
<option value="-" selected>-</option>
</select>
</div>

<div  class="col-lg-1"></div>

 <div   class="col-lg-5">
  <label style="display:inline" > Fecha : </label>
  <input style="text-align: center;" class="input-control" name="date" id="date" value="<?php echo date("Y-m-d"); ?>" readonly/>
  </div>

</div>

        
   	<div  class="title col-lg-12"></div>
		
	 <div class="col-lg-12">
         <fieldset>
       	
         	<div class="comment-text-area col-lg-12">
         		<strong>Nota: </strong><textarea class="textinput" onkeyup="checkNOTA();" rows="5" cols="70" id="nota" name="nota"></textarea>
        		
         	</div>
         </fieldset>
		</div> 
   
		

					
</fieldset>

</div>

<div class="separador col-lg-12"></div>		

<div class=" col-lg-12"> 

<fieldset class="table_req" >
<table id="table_req_tb" class="table table-striped table-condensed table-bordered " cellspacing="0">
	<thead>
		<tr >
			<th width="10%" >
			<!--<select id="check_val" onchange="init(this.value);">
			<option value="1" >Renglon</option>
			<option value="2" >Codigo</option> 
			</select>-->
                         Renglon
                        </th>
			<th width="35%" class="text-center">Descripcion</th>
			<th width="15%" class="text-center">Cantidad</th>
			<th width="15%" class="text-center">Unidad</th>
			<!-- <th width="15%" class="text-center">Proyecto</th> -->
			<th width="15%" class="text-center">Fase</th>
		</tr>
	</thead>
	<tbody id="table_req" >	

	</tbody>
</table>
</fieldset>

</div>
</div>
</div>
<input type="hidden" id="req_no_jobid" value="" />



 <!-- FIN VENTANA -->
</div>
</div>


