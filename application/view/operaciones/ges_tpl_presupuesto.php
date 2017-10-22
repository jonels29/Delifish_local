<script type="text/javascript">

 // ********************************************************
// * Aciones cuando la pagina ya esta cargada
// ********************************************************
$(window).load(function(){

$('#ERROR').hide();

});

var table = $("#table_tpl").DataTable({

      bSor: false,
      responsive: true,
      searching: false,
      paging:    false,
      info:      false,
      collapsed: false

  });



</script>

<div class="page col-lg-12">

<!--INI DIV ERRO-->
<div id="ERROR" class="alert alert-danger"></div>
<!--INI DIV ERROR-->

<div  class="col-lg-12">
<!-- contenido -->
<h2>Modelos de Propuestas</h2>
	<div class="title col-lg-12"></div>

	<div class="col-lg-12">
	<!--INI  contenido -->

	<form action="" role="form" class="form-horizontal" enctype="multipart/form-data" method="POST">
		<!--INPUT FILE-->
	    <div class="col-lg-4">
	      <fieldset>
			<label>Nombre Modelo de Presupuesto (*)</label>
			<input type="text" class="form-control" id="quo_id" name="quo_id" ><br><br>

	        <legend>Seleccionar el archivo de carga</legend>
	    	<input type="file" class="form-control" id="price_file" name="price_file" required="Debe seleccionar el archivo de lista de precios a cargar" />	
	    	<p class="help-block">El archivo debe ser en formato (.xls)</p>
	      </fieldset>
	    </div>
	   <!--INPUT FILE-->
       
        <!--SUBMIT BOTTON-->
		<div class="form-group col-lg-2">
			<input type="submit"  value="Cargar" class="btn btn-primary  btn-block text-lef" name="submit" />
		</div>
        <!--SUBMIT BOTTON-->
     </form>

<div class="separador col-lg-12"></div>

<?php
//INI LECTURA DE ARCHIVO EXCEL

$reader= new Spreadsheet_Excel_Reader();


if($_FILES["price_file"]["size"] > 0)
{


$filename=$_FILES["price_file"]["tmp_name"];
	
			$reader->setUTFEncoder('iconv');
			$reader->setOutputEncoding('UTF-8');
			$reader->read($filename);

			$template_name = $_POST['quo_id'];
			$temp = "SELECT MAX(quo_templateID) FROM QUO_TEMP_HEADER;";

			$check_lastID = $this->model->Query($temp);

			if ($check_lastID != '') {
				
				foreach ($check_lastID as $value2) {

  					$highest_ID= json_decode($value2);

  					$templateid = $highest_ID->{'quo_templateID'} + 1;

  			}
			}else {

				$templateid = 1;
			}

			

			 foreach($reader->sheets as $k=>$data)
			 {
	        

				$i=2;
				$values = array();

				 while ($i<=$data['numRows']){

				 	$values['1'] = $templateid;

					foreach($data['cells'][$i] as $KEY=>$cell) 
					{
						   
	                            if($cell !=''){
	                   	
							if ($KEY=='1')   	$values['2'] = utf8_decode($cell) ;
							if ($KEY=='2')   	$values['3'] = utf8_decode($cell) ;
							if ($KEY=='3')   	$values['4'] = utf8_decode($cell) ;
								    	
					
				   				}else{

				   					if ($KEY=='1')   	$values['2'] = '';
									if ($KEY=='2')   	$values['3'] = '';
									if ($KEY=='3')   	$values['4'] = '';
				   				}
					}
				   
	             //INSERTA EN BD LA LINEA ACTUAL
				  echo $STATEMENT= "INSERT INTO QUO_TEMP_DETAIL (
						`quo_templateID`,
						`itemID`,
						`description`, 
						`unit`) 						 
						VALUES 
						('".implode("','", $values)."');";
			
	 		        $res = $this->model->Query($STATEMENT);

				 	 //checa errores de bd y detiene el proceso si existe alguno
			        $CHK_ERROR =  $this->model->read_db_error();

			        if ($CHK_ERROR!=''){ 

	          
			        echo "<script>$(window).load(function(){ MSG_ERROR('".$CHK_ERROR."',0); });</script>"; 
			          
			          $OK = false;

			        }else{

                       $OK = TRUE;

			        }
			

				$i=$i+1;
	          
              

			}

			}
		}

	if ($OK == TRUE) {
		
		$values_head  = array( 'quo_templateID' => $templateid ,
						                  'quotation_name' => $template_name,
						                  'ID_compania' =>  $this->model->id_compania,
						                  'status' =>  1);

					$res = $this->model->insert('QUO_TEMP_HEADER',$values_head);

	
						 //checa errores de bd y detiene el proceso si existe alguno
			        $CHK_ERROR =  $this->model->read_db_error();

					

					if ($CHK_ERROR!=''){ 

	                    //BORRA LA TABLA DE LISTA DE PRECIOS CON EL ID ESPECIFICADO, ya que previamente se habia creado
						$DEL_STATEMENT = 'DELETE FROM QUO_TEMP_DETAIL WHERE quo_templateID = "'.$templateid.'"';
						$res = $this->model->Query($DEL_STATEMENT);

						echo "<script>$(window).load(function(){ MSG_CORRECT('HA OCURRIDO UN ERROR AL CARGAR LA PLANTILLA, POR FAVOR REVISE EL ARCHIVO',0); });</script>"; 
					}else{


                    echo "<script>$(window).load(function(){ MSG_CORRECT('LA PLANTILLA SE HA CARGADO CON EXITO ',0); });</script>"; 

			        }

	}

?>
	<!--END contenido -->
	</div>
</div>
</div>
</div>

