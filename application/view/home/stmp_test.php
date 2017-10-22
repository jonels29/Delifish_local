<?php

$mail->IsSMTP(); // enable SMTP
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

$mail->AddAddress($_RESQUEST['emailtest']);



if(!$mail->send()) {
 

   $alert .= 'El correo no puede ser enviado.';
   $alert .= 'Error: ' . $mail->ErrorInfo;

   

} else {
  
  $alert = 'El correo de verificación ha sido enviado';
}

return $alert;

?>