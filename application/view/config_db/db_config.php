<?php 

if($_POST['save']){


$file = '';
$filename = 'db_val/db_variables.php';

$valuesfile = fopen($filename, 'w') or die('fopen failed '.var_dump(error_get_last()));
$open = $valuesfile;

$file .= "<?php define('DB_TYPE', 'mysql');\n
define('DB_HOST', '".$_POST['host']."');\n
define('DB_NAME', '".$_POST['dbname']."');\n
define('DB_USER', '".$_POST['user']."');\n
define('DB_PASS', '".$_POST['pass']."');\n
define('DB_CHARSET', 'utf8'); ?>";

fwrite ($valuesfile, $file) or die('fwrite failed '.var_dump(error_get_last()));
fclose($valuesfile);

header('location: index.php');


}


?>

<div class="page col-lg-12">

<div class="alert alert-danger">
  <strong>Error!</strong><?php echo $msg; ?>
</div>

<div  class="col-lg-12">
<!-- contenido -->
<h2>Configuracion de base de datos</h2>
<div class="title col-lg-12"></div>

<div class="col-lg-12">

<form action="" enctype="multipart/form-data" method="post" role="form" class="form-horizontal">
<fieldset>
	<div class="form-group">
			<label class="col-lg-3 control-label" >Hostname</label>
		<div class="input-group col-lg-2">


		<input type="text" class="form-control" id="host" name="host" value="<?php echo DB_HOST; ?>" />

		</div>
		</div>

		<div class="form-group">
			<label class="col-lg-3 control-label" >Nombre BD</label>
		<div class="input-group col-lg-2">

		<input type="text" class="form-control" id="dbname" name="dbname" value="<?php echo DB_NAME; ?>"  />

		</div>
        </div>
		
		<div class="form-group">
		<label class="col-lg-3 control-label" >Usuario</label>
		<div class="input-group col-lg-2">


		<input type="text" class="form-control" id="user" name="user" value="<?php echo DB_USER; ?>"  />

		</div>
		</div>

		<div class="form-group">
			<label class="col-lg-3 control-label" >Contraseña</label>
		<div class="input-group col-lg-2">


		<input type="password" class="form-control" id="pass" name="pass" value="<?php echo DB_PASS; ?>"  />

		</div>
		</div>
        <div class="col-lg-6"></div>
	    <div class="input-group col-lg-2">

		<input type="submit" class="btn btn-primary  btn-lg btn-icon icon-right" name="save" value="Guardar"  />

		</div>


</fieldset>	

		
</form>


</div>
</div>