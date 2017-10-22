<script type="text/javascript">

 // ********************************************************
// * Aciones cuando la pagina ya esta cargada
// ********************************************************
$(window).load(function(){

$('#ERROR').hide();

});
	
$(document).ready(function() {
    if (location.hash) {
        $("a[href='" + location.hash + "']").tab("show");
    }
    $(document.body).on("click", "a[data-toggle]", function(event) {
        location.hash = this.getAttribute("href");
    });
});

$(window).on("myTab", function() {
    var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
    $("a[href='" + anchor + "']").tab("show");
});


</script>

 <?php

 if (isset($_REQUEST['smtp'])) {

	
$value  = array(
'ID' => '1',
'HOSTNAME' => $_REQUEST['emailhost'],
'PORT'     => $_REQUEST['emailport'],
'USERNAME' => $_REQUEST['emailusername'],
'PASSWORD' => $_REQUEST['emailpass'],
'Auth' => 'true',
'SMTPSecure' => 'false',
'SMTPDebug' => '');

$this->model->Query('DELETE from CONF_SMTP;');

$this->model->insert('CONF_SMTP',$value);

unset($_REQUEST);

echo '<script> alert("Se ha actualizado con exito"); window.open("'.URL.'index.php?url=home/config_sys","_self");</script>';


}

 if(isset($_REQUEST['logo'])){

	$target_dir = "img/";

	$target_file = $target_dir . basename($_FILES["imageFile"]["name"]);
 
	$target_file;
	$uploadOk = 1;

	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image

   if ($imageFileType=='jpg'){ 

	      
	        $uploadOk = 1;


	 	   if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
	 			

	 		rename("img/".$_FILES["imageFile"]["name"], "img/logo.jpg");
	        

	        echo '<script> alert("Se ha actualizado el logo con exito","ok"); 
	             window.open("'.URL.'index.php?url=home/config_sys","_self");</script>';

            } 


	    } else {

	    	
	        $uploadOk = 0;

	    }

    if ($uploadOk==0){   echo '<script>
	         alert("Se produjo un error al subir la imagen","ok"); window.open("'.URL.'index.php?url=home/config_sys","_self");</script>'; }

}


//ACTUALIZA DATOS DE COMPAÑIA 
if (isset($_REQUEST['comp'])) {
	
$value  = array(
'company_name' => $_POST['company'],
'email' => $_POST['email_contact'],
'address' => $_POST['address'],
'Tel' => $_POST['tel1'],
'Fax' => $_POST['tel2'] );

$this->model->update('company_info',$value,'Where id="1";');

unset($_REQUEST);

echo '<script> alert("Se ha actualizado con exito"); window.open("'.URL.'index.php?url=home/config_sys","_self");</script>';


}

//ACTUALIZA DATOS DE TAX 
if (isset($_REQUEST['add'])) {
	
$value  = array(
'taxid' => $_POST['idtax'],
'rate' => $_POST['porc'],
 );


$this->model->INSERT('sale_tax',$value,'Where id="1";');

echo '<script> alert("El nuevo Tax se ha agregado con exito","ok"); window.open("'.URL.'index.php?url=home/config_sys","_self");</script>';


}


//actualizo info de modulos
if(isset($_REQUEST['mod'])){


	if($_POST['mod_sales']==true){

		$mod_sales = '1';

		}else{

		$mod_sales = '0';	

		}
	if($_POST['mod_fact']==true){

		$mod_fact = '1';

		}else{

		$mod_fact  = '0';	

		}
	if($_POST['mod_invt']==true){

		$mod_invt= '1';

		}else{

		$mod_invt  = '0';	

		}
	if($_POST['mod_rept']==true){

		$mod_rept= '1';

		}else{

		$mod_rept  = '0';	

		}
	if($_POST['mod_stock']==true){

		$mod_stock= '1';

		}else{

		$mod_stock = '0';	

		}
	if($_POST['mod_pro']==true){

		$mod_pro = '1';

		}else{

		$mod_pro = '0';	

		}


$value = array(
	'mod_sales' => $mod_sales,
	'mod_fact'  => $mod_fact,
	'mod_invt'  => $mod_invt,
	'mod_rept'  => $mod_rept,
	'mod_stock' => $mod_stock,
	'mod_pro'   => $mod_pro);


$this->model->delete('MOD_MENU_CONF','');
$this->model->insert('MOD_MENU_CONF',$value);

echo '<script> alert("Se ha actualizado los detalles con exito","ok"); window.open("'.URL.'index.php?url=home/config_sys","_self");</script>';

}



//ACTUALIZO DATOS DE DETALLES DE FACTURACION
if($_POST['fact_detail_set']){

$fact_no_line = $_POST['fact_no_line'];

		if($_POST['fact_item_line']==true){

		$fact_item_line = '1';

		}else{

		$fact_item_line = '0';	

		}

$chk_cur_val =  $this->model->Query_value('FAC_DET_CONF','DIV_LINE','where ID_compania="'.$this->model->id_compania .'"');

$values =  array( 'ID_compania' => $this->model->id_compania , 'DIV_LINE' => $fact_item_line, 'NO_LINES' => $fact_no_line );

if($chk_cur_val!=''){
 $this->model->update('FAC_DET_CONF',$values, 'where ID_compania="'.$this->model->id_compania .'"');
}else{
 $this->model->insert('FAC_DET_CONF',$values);
}

echo '<script> alert("Se ha actualizado los detalles con exito","ok"); window.open("'.URL.'index.php?url=home/config_sys","_self");</script>';



}

//ACTUALIZO DATOS DE DETALLES DE CUENTAS GL
if($_POST['CTAS_GL']){

$cta_gl_cxp = $_POST['cta_gl_cxp'];
$cta_gl_pur = $_POST['cta_gl_pur'];
$cta_gl_tax = $_POST['cta_gl_tax'];
$cta_gl_acct = $_POST['Glacct'];

$chk_cur_val =  $this->model->Query_value('CTA_GL_CONF','ID','where ID_compania="'.$this->model->id_compania .'"');

$values =  array( 'ID_compania' => $this->model->id_compania , 
	              'CTA_CXP' => $cta_gl_cxp,
	              'CTA_PUR' => $cta_gl_pur,
	              'CTA_TAX' => $cta_gl_tax,
	              'GLACCT' => $cta_gl_acct,
	              );

if($chk_cur_val!=''){
 $this->model->update('CTA_GL_CONF',$values, 'where ID_compania="'.$this->model->id_compania .'"');
}else{
 $this->model->insert('CTA_GL_CONF',$values);
}

echo '<script> alert("Se ha actualizado los detalles con exito","ok"); window.open("'.URL.'index.php?url=home/config_sys","_self");</script>';


}
//////LLAMADAS DE DATOS

//RECUPERO INFO DE CUENTAS GL
$CTA_CXP = $this->model->Query_value('CTA_GL_CONF','CTA_CXP','WHERE ID_compania="'.$this->model->id_compania.'"');
$CTA_PUR = $this->model->Query_value('CTA_GL_CONF','CTA_PUR','WHERE ID_compania="'.$this->model->id_compania.'"');
$CTA_TAX = $this->model->Query_value('CTA_GL_CONF','CTA_TAX','WHERE ID_compania="'.$this->model->id_compania.'"');
$CTA_GLACCT = $this->model->Query_value('CTA_GL_CONF','GLACCT','WHERE ID_compania="'.$this->model->id_compania.'"');
//RECUPERO INFO DE MODULOS
$SQL = 'SELECT * FROM MOD_MENU_CONF';

$MOD_MENU = $this->model->Query($SQL);

foreach ($MOD_MENU as $value) {

$value = json_decode($value);

if($value->{'mod_sales'}=='1'){ $mod_sales_CK = 'checked'; }else{ $mod_sales_CK = '';  }
if($value->{'mod_fact'}=='1'){ $mod_fact_CK  = 'checked'; }else{ $mod_fact_CK = '';  }
if($value->{'mod_invt'}=='1'){ $mod_invt_CK = 'checked'; }else{ $mod_invt_CK  = '';  }
if($value->{'mod_rept'}=='1'){ $mod_rept_CK = 'checked'; }else{ $mod_rept_CK = '';  }
if($value->{'mod_stock'}=='1'){$mod_stoc_CK = 'checked'; }else{ $mod_stoc_CK = '';  }
if($value->{'mod_pro'}=='1'){  $mod_pro_CK  = 'checked'; }else{ $mod_pro_CK = '';  }
}



//LLAMO LOS VALORES ACTUALES DE LOS DATOS DE LA COMPAÑIA
$res= $this->model->Get_company_Info();
foreach ($res as $Comp_Info) {
	$Comp_Info = json_decode($Comp_Info);

	$name = $Comp_Info->{'company_name'};
	$email = $Comp_Info->{'email'};
	$address = $Comp_Info->{'address'};
	$tel= $Comp_Info->{'Tel'};
	$fax = $Comp_Info->{'Fax'};
}	 

//LLAMO LOS VALORES ACTUALES DE LOS DATOS DE VENTA
$saleRes= $this->model->Get_sales_conf_Info();

foreach ($saleRes as $sale) {
	$sale = json_decode($sale);

	$tax =  $sale->{'taxid'};
	$porc = $sale->{'rate'};

	$table .= '<div class="col-lg-1"></div>
	            <div class="col-lg-4">
	             <input type="text" class="form-control"  value="'.$tax.'" disabled/> 
               </div>
               <div class="col-lg-4">
	             <input type="text" class="form-control"  value="'.$porc.'" disabled/> 
               </div>
               <div class="col-lg-2">
	             <input type="button" onclick="del_tax('.$sale->{'id'}.');" value="Borrar" class="btn btn-primary  btn-block text-lef"  />
               </div><div class="col-lg-12"></div>';
}




//RECUPERO INFO DE DETALLES DE FACTURACION
$DIV_LINE = $this->model->Query_value('FAC_DET_CONF','DIV_LINE','WHERE ID_compania="'.$this->model->id_compania.'"');

if($DIV_LINE){

if($DIV_LINE=='1'){ $DIV_LINE_CK = 'checked'; }else{ $DIV_LINE_CK = '';  }

}else{

$DIV_LINE_CK = '';	
}


$NO_LINES = $this->model->Query_value('FAC_DET_CONF','NO_LINES','WHERE ID_compania="'.$this->model->id_compania.'"');




//Recupero datos smtp
$sql = "SELECT * FROM CONF_SMTP WHERE ID='1'";

$smtp= $this->model->Query($sql);

foreach ($smtp as $smtp_val) {
  $smtp_val= json_decode($smtp_val);

  $hostname       = $smtp_val->{'HOSTNAME'};
  $emailport      = $smtp_val->{'PORT'};
  $emailusername  = $smtp_val->{'USERNAME'};
  $emailpass      = $smtp_val->{'PASSWORD'};

}

unset($_POST);

?>	
<div class="page col-xs-12">

<!--INI DIV ERRO-->
<div id="ERROR" class="alert alert-danger"></div>
<!--INI DIV ERROR-->


<div  class="col-xs-12">
<!-- contenido -->
<h2>Configuracion del sistema</h2>
<div class="title col-xs-12"></div>


<DIV CLASS='SEPARADOR col-lg-12'></DIV>
<DIV class='col-lg-12'>
 <ul class="nav nav-tabs" id="myTab">
    <li class="active" ><a data-toggle="tab" href="#menu1">Compañia</a></li>
    <li><a data-toggle="tab" href="#menu2">Logo</a></li>
    <li><a data-toggle="tab" href="#menu3">Facturacion</a></li>
    <li><a data-toggle="tab" href="#menu4">SMTP</a></li>
    <li><a data-toggle="tab" href="#menu5">Modulos</a></li>
    <li><a data-toggle="tab" href="#menu6">Ctas. GL</a></li>
  </ul>

  <div class="tab-content">

     <!--CONFIGURACION GENERAL DE COMPAÑIA -->
    <div id="menu1" class="tab-pane fade in active">
      <fieldset >
		 <legend>Datos de generales</legend> 

		<form action="" role="form" class="form-horizontal" enctype="multipart/form-data" method="post" >

		<input type="hidden" id="comp" name="comp" value="1" />

		<div class="form-group">
		<label class="col-sm-2 control-label" for="field-1">Compañia</label>

		<div class="col-sm-10">
			<input type="text" class="form-control" id="company" name="company"  value="<?php echo $name; ?>"  /> 
		</div>
		</div>


		<div class="form-group">
		<label class="col-sm-2 control-label" >Dirección</label>
		<div class="col-sm-10">
		<input type="text" class="form-control" id="address" name="address" value="<?php echo $address; ?>" />
		</div>
		</div>


		<div class="form-group">
		<label class="col-sm-2 control-label" >Email</label>
		<div class="col-sm-10">
		<input type="email" class="form-control" id="email_contact" name="email_contact" value="<?php echo $email; ?>" />
			<p class="help-block">Indique el email de contacto de su compañia.</p>
			</div>
		</div>


		<div class="form-group">
			<label class="col-sm-3 control-label" >Teléfono</label>
		<div class="input-group col-sm-2">
				<span class="input-group-addon">
					<i class="fa fa-phone"></i>
				</span>

		<input type="text" class="form-control" id="tel1" name="tel1" value="<?php echo $tel; ?>" />

		</div>


		<label class="col-sm-3 control-label" >Fax</label>
			<div class="input-group col-sm-2">
			<span class="input-group-addon">
				<i class="fa fa-phone"></i>
			</span>
		<input type="text" class="form-control" id="tel2" name="tel2" value="<?php echo $fax; ?>" />

			</div>
		</div>
										
		<div class="form-group col-lg-2">
		<input type="submit"  value="Guardar" class="btn btn-primary  btn-block text-lef"/>
		</div>
		</form>
										
		 </fieldset>
    </div>
     <!--CONFIGURACION DE LOGO-->
    <div id="menu2" class="tab-pane fade">
	 <fieldset>
	 	<legend>Logo</legend>

	<form action="" role="form" class="form-horizontal" enctype="multipart/form-data" method="POST">

	<div class="form-group">
	<input type="hidden" id="logo" name="logo" value="1" />
		

	    <img class="confLogo col-sm-2" src="img/logo.jpg" width="150" heigh="100" />

		<div class="col-sm-8">
			<input type="file" class="form-control" id="imageFile" name="imageFile">
				<p class="help-block">Formato de imagen permitido es jpg, tamaño maximo de 300k y dimensiones 150x150px</p>
		</div>
	</div>
	<div class="form-group col-lg-2">
	<input type="submit"  value="Cargar imagen" class="btn btn-primary  btn-block text-lef" name="submit" />
	</div>
	 </form>

	 </fieldset>
    </div>

    <!--CONFIGURACION DE VENTAS -->
    <div id="menu3" class="tab-pane fade">
      <fieldset>
 	  	<form action="" role="form" class="form-horizontal" enctype="multipart/form-data" method="POST">
		<input type="hidden" id="sale" name="sale" value="1" />


		<fieldset>
		<legend><h4>Tax ID</h4></legend>
		<div class="form-group">
		<div class="col-lg-12"></div>

		<?php echo $table; ?>

		<script type="text/javascript">
			
		function del_tax(id){

		URL = document.getElementById('URL').value;

		var datos= "url=bridge_query/del_tax/"+id;
		var link= URL+"index.php";

		  $.ajax({
		      type: "GET",
		      url: link,
		      data: datos,
		      success: function(res){

				 alert("Se ha eliminado el tax seleccionado","ok"); 
				 window.open("index.php?url=home/config_sys","_self");

				}
		   });


		}
		</script>

		<div class="separador col-lg-12"></div>

		<div class="col-lg-1"></div>
		<div class="col-lg-4">
			<input type="text" class="form-control" id="idtax" name="idtax" required/> 
			<p class="help-block">ID del TAX que esta configurado en SAGE 50</p>
		</div>

		<div class="col-lg-4">
			<input type="text" class="form-control" id="porc" name="porc"  placeholder="0.00" required/> 
			<p class="help-block">% RATE o porcentaje del TAX que esta configurado en SAGE 50</p>
		</div>

		<div class="col-lg-2">
		<input type="submit"  value="Agregar" class="btn btn-primary  btn-block text-lef" id="add" name="add"  />
		</div>


		</div>
		</fieldset>
		</form>


		<form action="" role="form" class="form-horizontal" enctype="multipart/form-data" method="POST">
		<fieldset>
		<legend>Detalle en facturas/recibos</legend>
        
		<div class="col-lg-2">
		<fieldset>
		<input type="CHECKBOX" name="fact_item_line" <?php echo $DIV_LINE_CK; ?> />&nbsp<label>Dividir lineas de detalles de Items en facturas</label><p class='help-block'>Permite no sumarizar la cantidades de Lotes seleccionados, se  mostraran en lineas independientes con el detalle del Item</p>  
		</fieldset>
		</div>
       

       
		<div class="col-lg-2">
		<fieldset>
		<label>No. de lineas </label><input type="text" name="fact_no_line" value="<?php echo $NO_LINES; ?>" />&nbsp<p class='help-block'>Determina el No. de Lineas para las tablas con campos de entrada. Maximo 9999 lineas</p>
        </fieldset> 
		</div>


		<div class="col-lg-12"></div>
		<div class="col-lg-10"></div>
	    <div class="col-lg-2">
		<input type="submit"  value="Guardar" class="btn btn-primary btn-block text-lef" id="fact_detail_set" name="fact_detail_set"  />
		</div>
 		

		</fieldset>
		</form>

		</fieldset>
    </div>
     <!--CONFIGURACION DE CORREO SMTP-->
    <div id="menu4" class="tab-pane fade">
      
		<fieldset>
		  <legend>Configuracion SMTP</legend>
		<form action="" role="form" class="form-horizontal" enctype="multipart/form-data" method="POST">
		<input type="hidden" id="smtp" name="smtp" value="1" />

		<div class="form-group">
		<label class="col-sm-2 control-label" >Host</label>
		<div class="col-sm-8">
		<input class="form-control" id="emailhost" name="emailhost" type="text" maxlength="64" value="<?php echo $hostname; ?>" required>
		</div>
		</div>


		<div class="form-group">
		<label class="col-sm-2 control-label" >Puerto</label>
		<div class="col-sm-8">
		<input  class="form-control" id="emailport" name="emailport" type="text" value="<?php echo $emailport; ?>" required>
			</div>
		</div>

		<div class="form-group">
		<label class="col-sm-2 control-label" >Usuario</label>
		<div class="col-sm-8">
		<input class="form-control" id="emailusername" name="emailusername" type="text" maxlength="64" value="<?php echo $emailusername; ?>" required>
			</div>
		</div>

		<div class="form-group">
		<label class="col-sm-2 control-label" >Contraseña</label>
		<div class="col-sm-8">
		<input class="form-control" name="emailpass" id="emailpass" type="password" maxlength="64" value="<?php echo $emailpass; ?>" required>
			</div>
		</div>

		<div style='float:right;' class="col-sm-2">
		<input type="submit"  value="Guardar" class="btn btn-primary  btn-block text-lef"  />
		</div>

		</form>


		<script type="text/javascript">
		function send_test(){

		URL       = document.getElementById('URL').value;
		var email = document.getElementById('emailtest').value;
		var datos= "url=bridge_query/send_test_mail/"+email;
		var link= +"index.php";

		$('#notificacion').html('<P>Enviando...</P>');

		  $.ajax({
		      type: "GET",
		      url: link,
		      data: datos,
		      success: function(res){
		      
		       $('#notificacion').html(res);
		       // alert(res);
		        }
		   });

		}
		</script>


		<div class="separador col-lg-12"></div>

		<div class="form-group">
		<div class="col-sm-3">
		<input type='button' onclick="javascript: send_test(); return false;" class="btn btn-default  btn-block text-lef" id="testmail" name="testmail"  value='Enviar email de prueba' />
		</div>
		<div class="col-sm-7">
		<input class="form-control" name="emailtest" id="emailtest" type="email"  value="">
			</div>
		<div class="col-sm-12" id='notificacion'></div>
		</div>

		</fieldset>
    </div>

    <!--CONFIGURACION DE  MODULOS-->
    <div id="menu5" class="tab-pane fade">
	 <fieldset>
	 	<legend>Modulos</legend>

	<form action="" role="form" class="form-horizontal" enctype="multipart/form-data" method="POST">
		<div class="col-lg-2">
		<fieldset>
			<input type="CHECKBOX" name="mod_sales" <?php echo $mod_sales_CK; ?> />&nbsp<label>Gestion de Ventas</label><br>
			<input type="CHECKBOX" name="mod_fact" <?php echo  $mod_fact_CK; ?> />&nbsp<label>Gestion de Compras</label><br>
			<input type="CHECKBOX" name="mod_invt" <?php echo  $mod_invt_CK; ?> />&nbsp<label>Gestion de Inventario</label><br>
			<input type="CHECKBOX" name="mod_rept" <?php echo  $mod_rept_CK; ?> />&nbsp<label>Gestion de Reportes</label><br>
			<input type="CHECKBOX" name="mod_stock" <?php echo $mod_stoc_CK; ?> />&nbsp<label>Gestion de Almacenes</label><br>
			<input type="CHECKBOX" name="mod_pro" <?php echo $mod_pro_CK; ?> />&nbsp<label>Gestion de Propuestas</label><br>
		</fieldset>
		</div>
      <div class="separador col-lg-12"></div>
	   <div class="col-lg-2">
		<input type="submit"  value="Guardar" class="btn btn-primary  btn-block text-lef" id="mod" name="mod"  />
		</div>
	 </form>

	 </fieldset>
    </div>

     <!--CONFIGURACION DE CUENTAS GL-->
     <div id="menu6" class="tab-pane fade">
      <form action="" role="form" class="form-horizontal" enctype="multipart/form-data" method="POST">
		<fieldset>
		<legend>Uso de cuentas GL</legend>
        
		<div class="col-lg-2">
		<fieldset>
		<label>Cta. MG/CxP</label> <input type="text"  name="cta_gl_cxp" id="cta_gl_cxp" onkeyup="check_num(this.value,'cta_gl_cxp');"  class='numb' value="<?php echo $CTA_CXP; ?>" />
        </fieldset> 
		</div>
		<div class="col-lg-2">
		<fieldset>
		<label>Cta. MG/Compras</label> <input type="text"  name="cta_gl_pur" id="cta_gl_pur" onkeyup="check_num(this.value,'cta_gl_pur');" class='numb' value="<?php echo $CTA_PUR; ?>" />
        </fieldset> 
		</div>
		<div class="col-lg-2">
		<fieldset>
		<label>Cta. MG/ITBMS</label> <input type="text"  name="cta_gl_tax" id="cta_gl_tax" onkeyup="check_num(this.value,'cta_gl_tax');" class='numb' value="<?php echo $CTA_TAX; ?>" />
        </fieldset> 
		</div>
		<div class="col-lg-2">
		<fieldset>          
          
            <label>Cta. MG/Contra partida: </label><input type="text" onkeyup="check_num(this.value,'Glacct');"  id="Glacct" name="Glacct" value="<?php echo $CTA_GLACCT; ?>"/>
         </fieldset>
		</div>

		<div class="col-lg-12"></div>
		<div class="col-lg-10"></div>
	    <div class="col-lg-2">
		<input type="submit"  value="Guardar" class="btn btn-primary btn-block text-lef" id="CTAS_GL" name="CTAS_GL"  />
		</div>
 		

		</fieldset>
		</form>
    </div>

</div>


</div>
</div>
