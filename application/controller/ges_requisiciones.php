<?PHP

class ges_requisiciones extends Controller
{

public $ProductID;

public function req_crear(){
 


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/req_crear.php';
            require APP . 'view/_templates/footer.php';


        }
          


	
}

public function req_hist(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/req_hist.php';
            require APP . 'view/_templates/footer.php';


        }
	
}

public function req_print($id){


 $res = $this->model->verify_session();

        if($res=='0'){

      $ORDER = $this->model->get_req_to_print($id);
  
            foreach ($ORDER as  $value) {

              $value = json_decode($value);

             $name = $this->model->Query_value('SAX_USER','name','Where ID="'.$value->{'USER'}.'"');
             $lastname =  $this->model->Query_value('SAX_USER','lastname','Where ID="'.$value->{'USER'}.'"');

             $Job= $value->{'JobID'};      
             $fase= $value->{'PhaseID'};
             $ccost= $value->{'CostCodeID'};
              
              $ref = $value->{'NO_REQ'};

              $rep = $name.' '.$lastname;

              $date = $value->{'DATE'};

              $desc = $value->{'NOTA'};



            }
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/req_print.php';
            require APP . 'view/_templates/footer.php';


        }


}

public function req_mailing($id){

 $res = $this->model->verify_session();

      if($res=='0'){


      require 'PHP_mailer/PHPMailerAutoload.php';
      $mail = new PHPMailer;


      $ORDER = $this->model->get_req_to_print($id);

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/req_mailing.php';
            require APP . 'view/_templates/footer.php';


        }


}

}

?>