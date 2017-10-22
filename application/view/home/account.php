
 <?php
 error_reporting(0);

if (isset($_POST['mail'])){

$mail=$_POST['mail'];
$name=$_POST['name'];
$lastname=$_POST['lastname'];
$pass=$_POST['pass_1'];
$role=$_POST['role'];
//$cliente_name = $_POST['cliente_id'];

$mail_verify = $this->model->Query_value('SAX_USER','email',"where email='".$mail."' and onoff='1'");

//echo $mail_verify;

if (!$mail_verify){

$pass = md5($pass);

$this->model->Query("INSERT INTO SAX_USER (name,lastname,email,pass,role) values ('".$name."','".$lastname."','".$mail."','".$pass."','".$role."')");

?>
    <script>
      alert('El registro se ha agredago con exito'); 
    </script>

<?php }else{ ?>
    <script>
      alert('Error: El email de usuario ya existe');;
    </script>
<?php } } ?>

<script>
//TABLE OF ACCOUNT
  jQuery(document).ready(function($)
  {
   
   var table = $("#user").dataTable({
      aLengthMenu: [
        [5,10, 25,50,-1], [5,10, 25, 50,"All"]
      ]
    });

table.yadcf([
{column_number : 0},
{column_number : 1},
{column_number : 2},
{column_number : 3}
]); 

});
//TABLE OF ACCOUNT
</script>

<div class="page col-lg-12">

<div  class="col-lg-12">
<!-- contenido -->
<h2>Cuentas de usuarios</h2>
<div class="title col-lg-12"></div>


<div class="col-lg-12">
	
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#menu1">Agregar usuario</a></li>
  <li><a data-toggle="tab" href="#menu2">Lista de usuarios</a></li>
</ul>

<div class="tab-content">
  <!--crear usuario-->
  <div id="menu1" class="tab-pane in fade active">
	<fieldset >
	<form action="" enctype="multipart/form-data" method="post" role="form" class="form-horizontal">
	<div class="separador col-lg-12"></div>

	<div class="col-lg-6" > 
		<label class="col-lg-4 control-label" >Nombre</label>							
		<div class="col-lg-7">								
		
		<input type="text" class="form-control" id="name" name="name"  required/>
		
		</div>
	</div>

	<div class="col-lg-6" > 
		<label class="col-lg-4 control-label" >Apellido</label>						
		<div class="col-lg-7">								
		
		<input type="text" class="form-control" id="lastname" name="lastname"  required/>
		
		</div>
	</div>
	<div class="separador col-lg-12"></div>

	<div class="col-lg-12" > 
		<label class="col-lg-2 control-label" for="tagsinput-1"> Email</label>								
		<div class="col-lg-6">								
		<div class="input-group">
		<input type="text" class="form-control" name="mail" id="mail" required />		
		<span class="input-group-addon"><i class= "fa fa-envelope-o"></i></span>
		</div>
		</div>
	</div>
	<div class="separador col-lg-12"></div>
	<div class="col-lg-12" > 
		<label class="col-lg-2 control-label" >Password</label>						
		<div class="col-lg-4">								
		
		<input type="password" class="form-control" id="pass_1" name="pass_1" required/>
		
		</div>
	</div>
	<div class="separador col-lg-12"></div>
	<div class="col-lg-12" > 
		<label class="col-lg-2 control-label" >Repetir Password</label>					
		<div class="col-lg-4">								
		
		<input type="password" class="form-control" id="pass_2" name="pass_2" required/>
		
		</div>
	</div>
	<div class="separador col-lg-12"></div>
	<div class="col-lg-12" > 
		<label class="col-lg-2 control-label" for="tagsinput-2">Privilegio</label>					
		<div class="col-lg-3">
	     <select class="form-control" id="role" name="role">
			<option value="admin" >Administrador</option>
			<option value="user" >Usuario</option>
		  </select>
	     </div>
	</div>	
	<div class="separador col-lg-12"></div>
	<div class="col-lg-10"></div>
	<div class="col-lg-2">
	<button   class="btn btn-primary  btn-block text-left" type="submit" >Guardar</button>
	</div>		

	</form>
  </fieldset>
  </div>

  <!--lista de usuario-->
  <div id="menu2" class="tab-pane fade">
    <fieldset>
	<table id="user" width='100%' class="table table-striped" cellspacing="0"  >
	<thead>
	<tr>
	<th width="15%">Nombra</th>
	<th width="15%">Apellido</th>
	<th width="15%">Correo</th>
	<!-- <th width="15%">Compañia/Cliente</th> -->
	<th width="15%">Role</th>
	<th width="10%">Login</th>
	<th width="10%"></th>
	</tr>
	</thead>
	<tbody>
	<?php

	$user = $this->model->Query('SELECT * FROM SAX_USER WHERE onoff="1" ORDER BY "name" asc;');

		foreach ($user as $datos) {

	     $user = json_decode($datos);

	     

	     $id="'".$user->{'id'}."'";
	     

	     echo '<tr>
			<th>'.$user->{'name'}.'</th>
			<th>'.$user->{'lastname'}.'</th>
			<th>'.$user->{'email'}.'</th>
			<th>'.$user->{'role'}.'</th>
			<th>'.$user->{'last_login'}.'</th>
			<th><a  href="'.URL.'index.php?url=home/edit_account/'.$user->{'id'}.'" ><input type="button" id="modal_button" name="modal_button"  class="btn btn-danger btn-sm btn-icon icon-left" value="Editar" ></th>
			</tr>';
	 // data-toggle="modal" data-target="#myModal";

	     }
	?>
	 </tbody>
	 </table>
	 </fieldset>
  </div>
 </div>
</div><!-- aqui termina el tab -->	



 </div>
</div>
