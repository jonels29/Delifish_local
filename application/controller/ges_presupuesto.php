<?PHP

class ges_presupuesto extends Controller
{

//******************************************************************************
//ORDEN DE VENTAS
public function crear_presupuesto(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_crea_presupuesto.php';
            require APP . 'view/_templates/footer.php';


        }
          
	
}



}

?>