<script type="text/javascript">

 // ********************************************************
// * Aciones cuando la pagina ya esta cargada
// ********************************************************
$(window).load(function(){

$('#ERROR').hide();

//setea por defaul el valor 1 para mostrar el div de crear nueva lista de precios
set_div(1);


});

// ********************************************************
// * Aciones cuando la pagina incia|
// ******************************************************** 
document.addEventListener('DOMContentLoaded', function() {

      //ALTERNA LA SELECCION DE LOS CHECKBOX, PARA NO TENER DOS CHECKBOE SELECCIONADOS AL MISMO TIEMPO
		
		$("input:checkbox").on('click', function() {
		 
		  var $box = $(this);
		  if ($box.is(":checked")) { 
		    var group = "input:checkbox[name='" + $box.attr("name") + "']";
		    $(group).prop("checked", false);
		    $box.prop("checked", true);
		  } else {
		    $box.prop("checked", false);
		  }
   
       });

});

function set_div(val){

//OCULTA/MUESTRA EL DIV SEGUN SELECION DEL CHECKBOX PARA CREAR UN NUEVO IDPRICE O UTILIZAR UN IDPRICE EXISTENTE
	if(val=='1'){

      $('#nvo_lp').show();
      $('#used_lp').hide();      

	}else{

      $('#nvo_lp').hide();
      $('#used_lp').show();

	}



}

</script>


<div class="page col-lg-12">

<!--INI DIV ERRO-->
<div id="ERROR" ></div>
<!--INI DIV ERROR-->

<div  class="col-lg-12">
<!-- contenido -->
<h2>Lista de Precios</h2>
<div class="title col-lg-12"></div>

<div class="col-lg-12">

<fieldset>
<LEGEND>Cargar lista</LEGEND>

	<form action="" role="form" class="form-horizontal" enctype="multipart/form-data" method="POST">

	    <!--CHECKBOXES-->
		<div class="col-lg-4">
			<fieldset>
				<input type="checkbox" id="chk_lp" name="chk_lp" value="1" onclick="set_div(this.value)" checked>Crear nueva lista de precios<br>
			    <input type="checkbox" id="chk_lp" name="chk_lp" value="2" onclick="set_div(this.value)" >Utilizar lista de precios existente<br>
			</fieldset>
		</div>
	   <!--CHECKBOXES-->

    <div class="separador col-lg-12"></div> <!--SEPERADOR-->

	    <!--INPUT NUEVO PRICE ID-->
	    <div class="col-lg-8" id="nvo_lp" >
		    <fieldset>
		       <div class="col-lg-6" >
			      <label>ID LISTA DE PRECIOS (*)</label>
			      <input type="text" class="form-control" id="price_id" name="price_id" >
		       </div>

	           <div class="col-lg-6" >
			      <label>Descripción</label>
			      <input type="text" class="form-control" id="price_desc" name="price_desc">
		      </div>
		     </fieldset>
	    </div>
	    <!--INPUT NUEVO PRICE ID-->

    <div class="separador col-lg-12"></div> <!--SEPERADOR-->

	<!--CHECKBOXES-->
	<div class="col-lg-4"  id="used_lp">
	  <fieldset>
	   <legend>Lista de precios existentes(*)</legend>
	   <label>Seleccione ID de lista de precio</label>
		<select  id="price_list" name="price_list" class="select col-lg-12" >

					<option selected disabled></option>

					<?php  
					$CUST = $this->model-> get_PriceList(); 

					foreach ($CUST as $datos) {
																		
					$CUST_INF = json_decode($datos);
					echo '<option value="'.$CUST_INF->{'IDPRICE'}.'" >'.$CUST_INF->{'IDPRICE'}."</option>";

					}
					?>
											
		</select>

	  </fieldset>
	</div>	
	<!--CHECKBOXES-->

	<div class="separador col-lg-12"></div> <!--SEPERADOR-->


   <!--INPUT FILE-->
    <div class="col-lg-4">
      <fieldset>
        <legend>Seleccionar el archivo de carga</legend>
    	<input type="file" class="form-control" id="price_file" name="price_file" required="Debe seleccionar el archivo de lista de precios a cargar" />	
    	<p class="help-block">El archivo debe ser en formato (.xls)</p>
      </fieldset>
    </div>
   <!--INPUT FILE-->
    
    <div class="separador col-lg-12"></div> <!--SEPERADOR-->

    <!--SUBMIT BOTTON-->
	<div class="form-group col-lg-2">
		<input type="submit"  value="Cargar" class="btn btn-primary  btn-block text-lef" name="submit" />
	</div>
     <!--SUBMIT BOTTON-->

	</form>
</fieldset>

</div>
</div>
</div>

<?php

//SE EJECUTA SCRIPT PHP SI SE DÁ SUBMIT AL MOTON "SUBIR"
if (isset($_POST['submit'])){

	try {
		
		$TABLE = 'PRI_LIST_ITEM';


		//INFORMACION  LISTA DE PRECIO
				if ($_POST['price_list'] != '') {
								
							$priceid = trim($_POST['price_list']);

							//elimino la lista de items para ese idprice
							$DEL_STATEMENT = 'DELETE FROM '.$TABLE.' WHERE IDPRICE = "'.$priceid.'" AND ID_compania="'.$this->model->id_compania.'"';
			                $res = $this->model->Query($DEL_STATEMENT);

			               $this->CheckError();

				}else{

				    		$priceid = trim($_POST['price_id']);

		                    //CHECA SI YA EXISTE EL ID PROPUESTO
							$check_id = $this->model->Query('SELECT IDPRICE FROM PRI_LIST_ID WHERE IDPRICE ="'.$priceid.'"');

							if($check_id!=''){//SI EXISTE INDICA ERROR y mata el proceso php

			                
				            die("<script>$(window).load(function(){ MSG_ERROR('EL ID DE LA LISTA DE PRECIO PROPUESTA YA EXISTE',0); });</script>");

			                 }else{

			                //elimino la lista de items para ese idprice
							$DEL_STATEMENT = 'DELETE FROM '.$TABLE.' WHERE IDPRICE = "'.$priceid.'" AND ID_compania="'.$this->model->id_compania.'"';
			                $res = $this->model->Query($DEL_STATEMENT);

			                $this->CheckError();

			                 }

		        }

				//INI LECTURA DE ARCHIVO EXCEL
					$reader=new Spreadsheet_Excel_Reader();

					$filename=$_FILES["price_file"]["tmp_name"];

						if($_FILES["price_file"]["size"] > 0)
						 {

							$reader->setUTFEncoder('iconv');
							$reader->setOutputEncoding('UTF-8');
							$reader->read($filename);


							 foreach($reader->sheets as $k=>$data)
							 {
					        

								$i=1;
								$values = array();
								$STATEMENT = '';

								 while ($i<=$data['numRows']){


                                 if(sizeof($data['cells'][$i]) > 0){

								     $values['1'] = $priceid;

									foreach($data['cells'][$i] as $KEY=>$cell) 
									{
										   
					                        if($cell !=''){
					                   	
											if ($KEY=='1')   	$values['2'] = utf8_encode($cell) ;
											if ($KEY=='2')   	$values['3'] = utf8_encode($cell) ;
											if ($KEY=='3')   	$values['4'] = utf8_encode($cell) ;
											if ($KEY=='4')   	$values['5'] = utf8_encode($cell) ;      	

					                        }
									
								    }

								   $values['6'] = $this->model->id_compania;

                           

						             //INSERTA EN BD LA LINEA ACTUAL
									  $STATEMENT= "INSERT INTO ".$TABLE." (
											`IDPRICE`,
											`IDITEM` ,
											`DESCRIPTION` , 
											`PRICE` ,
											`UNIT`,
											`ID_compania`)  
											VALUES 
											('".implode("','", $values)."');";
								
							
					 		        $res = $this->model->Query($STATEMENT);

				                  $this->CheckError();



                                }

								 	

					
                                   

							

								$i=$i+1;
					          }
				              
				              
							}//termina el proceso de insercion

					}

		        //SI IDPRICE EXISTE
		    	if ($_POST['price_id'] != '') {
							

				    $values  = array(  'IDPRICE' => $priceid ,
						               'DESCRIPTION' => $_POST['price_desc'] ,
						               'ID_compania' =>  $this->model->id_compania );

			    	$res = $this->model->insert('PRI_LIST_ID',$values);
		           
		           $this->CheckError();

		           echo "<script>$(window).load(function(){ MSG_CORRECT('1 LA LISTA  SE HA CARGADO CON EXITO ',0); });</script>"; 
			            

				}else{

		           echo "<script>$(window).load(function(){ MSG_CORRECT('2 LA LISTA  SE HA CARGADO CON EXITO ',0); });</script>"; 
		     
		      	}


	} catch (Exception $e) {

		 die("<script>$(window).load(function(){ MSG_ERROR('".$e->getMessage()."',0); });</script>"); 
	}



$_POST = array(); //limpia las variables de $_post
$_FILES = array();


}




?>