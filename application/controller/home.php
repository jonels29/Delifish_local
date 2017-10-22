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


public function GetPrintInfo(){

$RES = $this->model->Query('Select * from INV_PRINT_CONF');


return $RES;
}

public function del_tax($id){


$this->model->Query('delete from sale_tax Where id="'.$id.'";');

}

public function del_print($id){


$this->model->Query('delete from INV_PRINT_CONF Where SERIAL="'.$id.'";');


}

public function GetBDLog(){


echo file_get_contents('LOG_ERROR/ERROR_LOG.txt');

  
}


public function GetSyncLog(){

echo file_get_contents('webhook_log.txt');


}



public function ClearBDLog(){

 file_put_contents("LOG_ERROR/ERROR_LOG.txt",'');

}


public function ClearSyncLog(){

 file_put_contents('webhook_log.txt','');


}

public function getPrinterList(){


return $this->model->Query('SELECT * FROM INV_PRINT_CONF');

}

public function getPrinterById($id){
  
$RES = '';

$printer = $this->model->Query('SELECT * FROM INV_PRINT_CONF where ID ="'.$id.'"');


if($printer){
  $printer = json_decode($printer[0]);
  $RES = $printer->{'SERIAL'}.' - '.$printer->{'DESCRIPCION'};
}


return $RES;
}

public function CheckError(){


  $CHK_ERROR =  $this->model->read_db_error();


  if ($CHK_ERROR!=''){ 

   
    die( "<script>  $(window).on('load', function () {   
                           $('#ErrorModal').modal('show');
                           $('#ErrorMsg').html('".$CHK_ERROR."');
                         }); 
          </script>");

  }

}

////////////////////////////////////////////////////////////////////////////////////
//PROCESO DE ENVIO DE EMAIL (TEST)
public function send_test_mail($emailtest){

require 'PHP_mailer/PHPMailerAutoload.php';
$mail = new PHPMailer;

$mail->IsMail(); // enable SMTP
$mail->IsHTML(true);



$sql = "SELECT * FROM CONF_SMTP WHERE ID='1'";

$smtp= $this->model->Query($sql);

foreach ($smtp as $smtp_val) {
  $smtp_val= json_decode($smtp_val);

  $mail->Host =     $smtp_val->{'HOSTNAME'};
  $mail->Port =     $smtp_val->{'PORT'};
  $mail->Username = $smtp_val->{'USERNAME'};
  $mail->Password = $smtp_val->{'PASSWORD'};
  $mail->SMTPAuth = $smtp_val->{'Auth'};
  $mail->SMTPSecure=$smtp_val->{'SMTPSecure'};
  $mail->SMTPDebug= $smtp_val->{'SMTPSDebug'};

  $mail->SetFrom($smtp_val->{'USERNAME'});

}



$mail->Subject = utf8_decode('Prueba de configurarión SMTP (ACI-WEB)');

$message_to_send ='<html>
<head>
<meta charset="UTF-8">
<title>Prueba de configurarión SMTP (ACI-WEB)</title>
</head>
<body>Este es un correo de prueba del sistema ACI-WEB de APCON Consulting, 
para certificar el funcionamiento de su configuracion SMTP.</body>
</html>';

$mail->Body = $message_to_send;

$mail->AddAddress($emailtest);



if(!$mail->send()) {
 

   $alert .= 'El correo no puede ser enviado.';
   $alert .= 'Error: ' . $mail->ErrorInfo;

   

} else {
  
  $alert = 'El correo de verificacion ha sido enviado';
}

echo $alert;

}


}