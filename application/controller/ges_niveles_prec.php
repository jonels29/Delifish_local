<?php

class ges_niveles_prec extends Controller
{

//Declaracion de Variables Publicas

public $id_cus;
public $cus_name;

//******************************************************************************
//Gestion de niveles de Precios. Carga vista de gestion de precios.
public function gestion_precios(){


 $res = $this->model->verify_session();

        if($res=='0'){
            
            require('Excel/reader.php');
            require('Excel/simple_html_dom.php');

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_nivel_precios.php';
            require APP . 'view/_templates/footer.php';


        }
          


	
}



//******************************************************************************
//Pagina que mostrara los precios de un cliente especifico y permitira modificar y anadirlos.


public function agregar_precios($id_customer,$customer_name){

$this->id_cus = $id_customer;
$this->cus_name = $customer_name;

 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/agregar_nivel_precio.php';
            require APP . 'view/_templates/footer.php';


        }
          


	
}

}

?>