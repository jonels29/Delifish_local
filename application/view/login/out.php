<?php
session_start();

if($_SESSION['EMAIL']){

	session_destroy();
	echo '<script>alert("Usted ha cerrado su sesion con exito, hasta pronto!") 
	self.location="control.php"
	</script>';

}else{

echo "<script>self.location='control.php';</script>";

}


?> 