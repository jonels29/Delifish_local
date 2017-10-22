<?PHP

class ges_compras extends Controller
{


public function crear_fact(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_fact_compras.php';
            require APP . 'view/_templates/footer.php';


        }
          


    
}


public function fact_compras(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_compras.php';
            require APP . 'view/_templates/footer.php';


        }
          


	
}


public function orden_compras($id){


 $res = $this->model->verify_session();

        if($res=='0'){
        

$oc = $this->model->get_items_by_OC($id);


            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_orden_compras.php';
            require APP . 'view/_templates/footer.php';


        }
          
}

public function print_fact($id){


 $res = $this->model->verify_session();

        if($res=='0'){

             // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_print_FactCompras.php';
            require APP . 'view/_templates/footer.php';


        }





}

public function fact_mailing($id){

 $res = $this->model->verify_session();

      if($res=='0'){


      require 'PHP_mailer/PHPMailerAutoload.php';
      $mail = new PHPMailer;


     // $ORDER = $this->model->get_req_to_print($id);

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/fact_mailing.php';
            require APP . 'view/_templates/footer.php';


        }


}
          


    


}

?>