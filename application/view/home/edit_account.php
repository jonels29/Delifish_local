<!--INI DIV ERRO-->
<div id="ERROR" ></div>

<!--ERROR -->

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





<?php
error_reporting(0);



//GET ACCOUNT INFO
if($id){


$res = $this->model->Query('SELECT * FROM SAX_USER  where SAX_USER.onoff="1" and SAX_USER.id="'.$id.'";');
 
foreach ($res as $value) {

  $value = json_decode($value);

  $id = $value->{'id'};
  $name = $value->{'name'};
  $lastname = $value->{'lastname'};
  $email = $value->{'email'};
  $pass = $value->{'pass'};
  $role= $value->{'role'};
  $INF_OC= $value->{'notif_oc'};
  $INF_FC= $value->{'notif_fc'};
  $INF_PRICE= $value->{'mod_price'};
  $INF_INV= $value->{'inv_view'};
  $INF_STO= $value->{'stoc_view'};
  $INF_REP= $value->{'rep_view'};
  $PHOTO  = $value->{'photo'};
  $INF_rol_1= $value->{'role_purc'};
  $INF_rol_2= $value->{'role_fiel'};
  $CLO_SO   = $value->{'closeSO'};
  $PRINTER = $this->getPrinterById($value->{'printer'});

  $prnterID = $value->{'printer'};
   
  if( $PRINTER == ''){

   $PRINTER = 'Ninguna';
   $prnterID = null;
  }

  if($PHOTO == 'x'){

   $user_avatar = URL.'img/user_avatar/'.$id.'.jpg';

  }else{

   $user_avatar = URL.'img/default-avatar.png';

  }

  if($INF_OC==1){//notificaciones requisiciones

  $notif_oc = 'checked';

  }else{

  $notif_oc = ''; 
  }

  if($INF_FC==1){//notificaciones acturas

  $notif_fc = 'checked';

  }else{

  $notif_fc = ''; 
  }

   if($INF_PRICE==1){//modificar precio

  $price_mod = 'checked';

  }else{

  $price_mod = '';  
  }

  if($INF_INV==1){//ver inventario

  $INV_CK = 'checked';

  }else{

  $INV_CK = ''; 
  }

  if($INF_STO==1){//ver inventario

  $STO_CK = 'checked';

  }else{

  $STO_CK = ''; 
  }

  if($INF_REP==1){//ver inventario

  $REP_CK = 'checked';

  }else{

  $REP_CK = ''; 
  }

  if($CLO_SO==1){//cerrar ordenes

  $closeSO = 'checked';

  }else{

  $closeSO = ''; 
  }


	if($INF_rol_1==1){//notificaciones

	$rol_purc_value = 'checked';

	}else{

	$rol_purc_value= '';	
	}

	if($INF_rol_2==1){//notificaciones

	$rol_field_value = 'checked';

	}else{

	$rol_field_value = '';	
	}

}



if($this->model->active_user_role!='admin'){ 

	$notif_oc  .= ' disabled'; 
	$notif_fc  .= ' disabled'; 
	$price_mod .= ' disabled'; 
	$REP_CK .= ' disabled';
	$STO_CK .= ' disabled';
	$INV_CK .= ' disabled';
	$closeSO .= ' disabled';

}  

//UPDATE INFORMATION
if($_POST['flag2']=='1'){

		if($_POST['oc_chk']==true){//notificaciones

		$not_oc_value = '1';

		}else{

		$not_oc_value = '0';	
		}

		if($_POST['fc_chk']==true){//notificaciones

		$not_fc_value = '1';

		}else{

		$not_fc_value = '0';	
		}

		if($_POST['pri_chk']==true){//permite al usuario modificar precios

		$mod_price_value = '1';

		}else{

		$mod_price_value = '0';	
		}

		if($_POST['clo_chk']==true){//permite al usuario modificar precios

		$close_so_value = '1';

		}else{

		$close_so_value = '0';	
		}

		if($_POST['inv_chk']==true){

		$set_inv_chk= '1';

		}else{

		$set_inv_chk= '0';	
		}

		if($_POST['sto_chk']==true){

		$set_sto_chk = '1';

		}else{

		$set_sto_chk = '0';	
		}

		if($_POST['rep_chk']==true){

		$set_rep_chk = '1';

		}else{

		$set_rep_chk = '0';	
		}

		if($_POST['rpurch_chk']==true){//notificaciones

		$rol_purc_value = '1';

		}else{

		$rol_purc_value= '0';	
		}

		if($_POST['rfield_chk']==true){//notificaciones

		$rol_field_value = '1';

		}else{

		$rol_field_value = '0';	
		}
		

$pass_ck = $this->model->Query_value('SAX_USER','pass','where SAX_USER.onoff="1" and SAX_USER.id="'.$id.'"');


	if($pass_ck==$_POST['pass_22']){

	$pass==$_POST['pass_22'];

	}else{

	$pass = md5($_POST['pass_22']);
		
	}




//sube foto de perfil
if(basename($_FILES["image"]["name"])!=''){

	$target_dir = "img/user_avatar/";
	$target_file = $target_dir . basename($_FILES["image"]["name"]); 


	if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
		 			
			rename($target_dir.$_FILES["image"]["name"], $target_dir.$_POST['user_2'].".jpg");
	 } 

$foto_file = 'x';

}else{

$foto_file = '';

}

//elimina foto de perfi
if($_POST['trash_img']==1){

  unlink('img/user_avatar/'.$_POST['user_2'].".jpg");
  $foto_file = '';

}


if($_POST['DefaultPrint']!=''){

$printer = $_POST['DefaultPrint'];

}else{

$printer = 0;

}

$columns  = array( 'name'      => $_POST['name2'],
	               'lastname'  => $_POST['lastname2'],
	               'pass'      => $pass,
	               'role'      => $_POST['priv'],
	               'notif_oc'  => $not_oc_value,
	               'notif_fc'  => $not_fc_value, 
	               'mod_price' => $mod_price_value,
	               'inv_view'  => $set_inv_chk,
	               'rep_view'  => $set_rep_chk,
	               'stoc_view' => $set_sto_chk,
	               'photo'     => $foto_file,
	               'printer'   => $printer,
	               'role_purc' => $rol_purc_value,
		           'role_fiel' => $rol_field_value,
                   'closeSO'   => $close_so_value);

$clause = 'id="'.$_POST['user_2'].'"';



$this->model->update('SAX_USER',$columns,$clause);

$this->CheckError();



echo '<script>alert("Se ha actualizado los datos con exito");

self.location="'.URL.'index.php?url=home/edit_account/'.$id.'";


</script>';
}



}

?>

<div class="col-lg-3"></div>
<div class="page col-lg-6">

<div  class="col-lg-12">
<!-- contenido -->
    <!-- Modal content-->
    <div >
      <div >
       
        <h3 >Perfil de Usuario</h3>

<div class="separador col-lg-12"></div>
<fieldset>
<form action="" enctype="multipart/form-data" method="post" role="form" class="form-horizontal">

<input type="hidden" id="user_2" name="user_2" value="<?php echo $id; ?>" />

<div class="separador col-lg-12"></div>

<fieldset class="col-lg-3">
  <div  class="col-lg-12"  >
  
    <img id="blah"  class="img-circle" src="<?php echo $user_avatar; ?>"  width="90px" height="90px"  alt="avatar" />

 </div>
             <label style="right:5px;  position:absolute; top:80%;" title='Editar' class="fa fa-edit"> 
                 <input  id="image" name="image" type="file" onchange="readURL(this);"  style="display: none;" />

             </label>  
             <label style="left:5px; position:absolute; top:80%; color:red" title='Eliminar' class="fa  fa-trash"> 

                 <input  id="trash" name="trash"  onclick="avatar_trash();"  style="display: none;" />
                 
             </label> 

             <input type="hidden" name="trash_img"  id="trash_img" value="" />

             <script type="text/javascript">
             	function avatar_trash(){

             		$('#blah').attr('src' , '<?php echo URL.'img/default-avatar.png'; ?>');

             		document.getElementById('trash_img').value = 1;
                    
             	}

             </script>

</fieldset>

<fieldset class="col-lg-9">
	<div class="col-lg-12" > 
		<label class="col-lg-4 control-label" >Nombre</label>							
		<div class="col-lg-8">								
		
		<input type="text" class="form-control" id="name2" name="name2"  value="<?php echo $name; ?>" required/>
		
		</div>
	</div>

	<div class="col-lg-12" > 
		<label class="col-lg-4 control-label" >Apellido</label>						
		<div class="col-lg-8">								
		
		<input type="text" class="form-control" id="lastname2" name="lastname2"  value="<?php echo $lastname; ?>" required/>
		
		</div>
	</div>

	<div class="col-lg-12" > 
		<label class="col-lg-4 control-label" for="tagsinput-1"> Email</label>								
		<div class="col-lg-8">								
		<div class="input-group">
		<input type="text" class="form-control" name="email2" id="email2"  value="<?php echo $email; ?>"readonly/>		
		<span class="input-group-addon"><i class= "fa fa-envelope-o"></i></span>
		</div>
		</div>
	</div>
</fieldset>

<div class="title col-lg-12"></div>

<fieldset>
	<div class="col-lg-12" > 
	<label class="col-lg-4 control-label" >Password</label>						
	<div class="col-lg-8">								
	
	<input type="password" class="form-control" id="pass_12" name="pass_12"  value="<?php echo $pass; ?>" required/>
	
	</div>
	</div>

	<div class="col-lg-12" > 
		<label class="col-lg-4 control-label" >Repetir Password</label>					
		<div class="col-lg-8">								
		
		<input type="password" class="form-control" id="pass_22" name="pass_22" value="<?php echo $pass; ?>" required/>
		
		</div>
	</div>
</fieldset>

<div class="title col-lg-12"></div>

<fieldset>
	<div class="col-lg-12" > 
		<label class="col-lg-4 control-label" for="tagsinput-2">Role</label>					
		<div class="col-lg-8">
	     <input type="text" class="form-control" id="priv" name="priv" value="<?php echo $role; ?>" readonly/>
		
	     </div>
	</div>	
</fieldset>
<input type="hidden"  name="flag2" value="1" />

<div class="title col-lg-12"></div>
<div class="col-lg-6">
<fieldset>
<legend><h4>Notificaciones por correo</h4></legend>
<?PHP if ($mod_fact_CK == 'checked') { ?>
    <input type="CHECKBOX" name="fc_chk" <?php echo $notif_fc; ?> />&nbsp<label>Facturas de Compra</label>
<?php } ?>

<?PHP if ($mod_req_CK  == 'checked') { ?>
	<input type="CHECKBOX" name="oc_chk" <?php echo $notif_oc; ?> />&nbsp<label>Requisiciones</label><br>
<?php } ?>
</fieldset>
</div>
<div class="col-lg-6">
<fieldset>
<legend><h4>Autorizaciones</h4></legend>
<?PHP if ($mod_sales_CK == 'checked') { ?>

<input type="CHECKBOX" name="pri_chk" <?php echo $price_mod;  ?> />&nbsp<label>Modificar de Precios</label><p class="help-block">(Autoriza modificacion de precios en Pedidos/Ordenes con venta directas)</p><br> 

<input type="CHECKBOX" name="clo_chk" <?php echo $closeSO;  ?> />&nbsp<label>Cerrar Ordenes de ventas/Pedidos</label><p class="help-block">(Autoriza cerrar Pedidos/Ordenes de ventas)</p><br> 

<?php } ?>
<?PHP if ($mod_invt_CK  == 'checked') { ?>

<input type="CHECKBOX" name="inv_chk" <?php echo $INV_CK;  ?> />&nbsp<label>Gestionar Inventario</label><br><p class="help-block">(Configuraciones y gestion de inventario)</p>

<?php } ?>

<?PHP if ($mod_stoc_CK  == 'checked') { ?>
<input type="CHECKBOX" name="sto_chk" <?php echo $STO_CK;  ?> />&nbsp<label>Gestionar Ubicaciones</label><br><p class="help-block">(Reportes y gestion de ubicaciones de inventario)</p>
<?php } ?>

<?PHP if ($mod_rept_CK  == 'checked') { ?>
<input type="CHECKBOX" name="rep_chk" <?php echo $REP_CK;  ?> />&nbsp<label>Gestionar Reportes</label><br><p class="help-block">(Generar reportes)</p>
<?php } ?>
</fieldset>
</div>
<?PHP if ($mod_sales_CK == 'checked') { ?>
<div class="col-lg-6">
<fieldset>
<legend><h4>Impresora fiscal predeterminada</h4></legend>
	<select class='select col-lg-12' id='DefaultPrint' name='DefaultPrint' >
	<option value="" selected></option>
		<?PHP 

         $list = $this->getPrinterList();
         $Printers = '';
         foreach ($list as $key => $value) {
         	$value = json_decode($value);

            $Printers .= '<option value="'.$value->{'ID'}.'">'.$value->{'DESCRIPCION'}.' ( '.$value->{'SERIAL'}.') </option>';

         }
         
         echo $Printers;

		 ?>
	</select>
	<br>
	<label>Seleccionada: </label><input class='col-lg-12' type="text" name="printerAsigned"  value="<?php echo $PRINTER;  ?>" readonly/>

</fieldset>
</div>
<?php } ?>

<?PHP if ($mod_req_CK == 'checked') { ?>
<div class="col-lg-6">
<fieldset>
<legend><h4>Rol de usuario</h4></legend>
<input type="CHECKBOX" name="rpurch_chk" <?php echo $rol_purc_value; ?> />&nbsp<label>Oficina</label> <p class="help-block">(Crea órdenes de compra y actualiza fechas de inicio de cotización. Mod. Requisiciones)</p>
<input type="CHECKBOX" name="rfield_chk" <?php echo $rol_field_value; ?> />&nbsp<label>Campo</label><p class="help-block">(Crea requisiciones y reporta cantidades/fechas recibidas en órdenes de compra.  Mod. Requisiciones)</p>
</fieldset>
</div>
<?php } ?>

<div class="title col-lg-12"></div>
<div class="col-lg-6"></div>

<div class="col-lg-4">
<button   class="btn btn-primary  btn-block text-left" type="submit" >Actualizar</button>
</div>		

</form>
<div class="col-lg-2">
<button  onclick="erase_user('<?php echo URL; ?>');" class="btn btn-danger btn-sm btn-icon icon-left"  >Eliminar</button>
</div>	
</fieldset>
<div class="separador col-lg-12"></div>


      <!-- -->
     </div>
   </div>
 </div>
</div>
