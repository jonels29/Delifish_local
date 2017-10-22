<?PHP

class ges_inventario extends Controller
{

public $ProductID;

public function inv_list(){
 


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/inv_list.php';
            require APP . 'view/_templates/footer.php';


        }
          


	
}

public function inv_info($itemid){
 
 $this->ProductID = $itemid;

 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/inv_info.php';
            require APP . 'view/_templates/footer.php';


        }
          


	
}


}

?>