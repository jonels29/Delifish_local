<?php

class login extends Controller
{

public function index($temp_url){


        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/login/control.php';
        require APP . 'view/_templates/footer.php';
    }


public function login_out(){


		session_start();

		if($_SESSION['EMAIL']){

			session_destroy();
			echo '<script>
			      alert("Usted ha cerrado su sesion con exito, hasta pronto!") 
			      self.location="'.URL.'index.php?url=home/index";</script>';

		}else{

		echo "<script>self.location='".URL."index.php?url=home/index';</script>";

		}

} 

	

}