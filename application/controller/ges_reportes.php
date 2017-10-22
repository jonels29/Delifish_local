<?PHP

class ges_reportes extends Controller
{


public function rep_reportes(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/rep_reportes.php';
            require APP . 'view/_templates/footer.php';


        }
          

	
}


}

?>