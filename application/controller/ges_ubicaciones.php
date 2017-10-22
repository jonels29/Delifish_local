<?PHP

class ges_ubicaciones extends Controller
{


public function location(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_ubicaciones.php';
            require APP . 'view/_templates/footer.php';


        }
          


	
}


}

?>