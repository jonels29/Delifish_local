<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class home extends Controller
{

    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
  
    public function index()
    {

        $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/home/index.php';
            require APP . 'view/_templates/footer.php';


        }
       
       
    }


     public function accounts()
    {    
        $res = $this->model->verify_session();

        if($res=='0'){
        
        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/panel.php';
        require APP . 'view/home/account.php';
        require APP . 'view/_templates/footer.php';
        
        }


   }

   public function edit_account($id){


       $res = $this->model->verify_session();

        if($res=='0'){


        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/panel.php';
        require APP . 'view/home/edit_account.php';
        require APP . 'view/_templates/footer.php';
       }

}


    public function config_sys(){


       $res = $this->model->verify_session();

        if($res=='0'){


        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/_templates/panel.php';
        require APP . 'view/home/config_sys.php';
        require APP . 'view/_templates/footer.php';
       }

}

}