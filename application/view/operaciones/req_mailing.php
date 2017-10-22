<?php 

$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");


//company
$comp = $this->model->Get_company_Info();

foreach ($comp as $value) {
    $value = json_decode($value);

    $address = $value->{'address'};
    $name = $value->{'company_name'};
    $tel= $value->{'Tel'};
    $fax = $value->{'Fax'};
}


//valores de la requisicion segun ID. 
foreach ($ORDER as  $value) {

    $value = json_decode($value);

    $name     =  $this->model->Query_value('SAX_USER','name','Where ID="'.$value->{'USER'}.'"');
    $lastname =  $this->model->Query_value('SAX_USER','lastname','Where ID="'.$value->{'USER'}.'"');

              
    $ref = $value->{'NO_REQ'};

    $rep = $name.' '.$lastname;

    $date = $value->{'DATE'};
    $desc = $value->{'NOTA'};
}

?>

<div  class="page-print col-xs-11">
<div  class="col-xs-12">

<?php

$message .='<h2 class="h_invoice_header" >Requisicion</h2>
                 <table BORDER="1">
                    
                    <tr>
                      <th style="text-align:left;"><strong>Referencia:</strong>'.$ref.'</th>
                      
                    </tr>
                    <tr>
                      <th style="text-align:left;"><strong>Fecha:</strong>'.$meses[date('n',strtotime($date))-1].' '.date(' j, Y',strtotime($date)).'</th>
                      
                    </tr>
                    <tr>
                      <th style="text-align:left;"><strong>Solicitante: </strong>'.$rep.'</th>
                      
                    </tr>
</table>
                  
<br>
                                             
                       
<TABLE   width="100%" border="1" >
   <TR >
    <TH width="100%">Descripcion</TH>
   </TR>
   <TR >
   <TD width="100%">'.$desc.'</TD>
  </TR>
</TABLE>

<br>                   

<TABLE   width="100%" border="1" >
<TR >
   <TH width="15%">Codigo</TH>
   <TH width="35%">Descripcion</TH>
   <TH width="10%">Cant.</TH>
   <TH width="10%">Uni.</TH>
   <TH width="10%">Proyecto</TH>
   <TH width="10%">Fase</TH>
   
    </TR>';

foreach ($ORDER as  $value) { 

$value = json_decode($value);  


$message .= '<tr>
   <td width="15%" style="padding-right:10px; text-align: left;">'.$value->{'ProductID'}.'</td>
   <td width="35%" ">'.trim($value->{'DESCRIPCION'}).'</td>
   <td width="10%" class="numb" style="text-align: center; padding-right">'.number_format($value->{'CANTIDAD'},2).'</td>
   <td width="10%" style="text-align: center; padding-right">'.$value->{'UNIDAD'}.'</td>
   <td width="10%" style="text-align: center; padding-right">'.$value->{'JOB'}.'</td>
   <td width="10%" style="text-align: center; padding-right">'.$value->{'PHASE'}.'</td>
   
   </tr>';

}


$message .= '</table><BR><BR>';


$message .= '<a href="'.URL.'index.php?url=ges_requisiciones/set_req_quota/'.$ref.'/'.$this->model->id_compania.'" type="button" id="cotizar" >INICIAR COTIZACION</a>';

$message_to_send ='<html>
<head>
<meta charset="UTF-8">
<title>Requisicion de materiales</title>
</head>
<body>'.$message.'</body>
</html>';




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



$mail->Subject = utf8_decode("Requisicion-".$ref);
$mail->Body = $message_to_send;




//VERIFICA USUARIOS CON OPCION D ENOTIFICACION DE ORDEN DE COMPRAS
$sql = 'SELECT name, lastname, email from SAX_USER WHERE notif_oc="1" and onoff="1"';
$address = $this->model->Query($sql);

foreach ($address as  $value) {
$value = json_decode($value);

$mail->AddAddress($value->{'email'}, $value->{'name'}.' '.$value->{'lastname'});

}



if(!$mail->send()) {
 

   $alert .= 'Message could not be sent.';
   $alert .= 'Mailer Error: ' . $mail->ErrorInfo;

  //echo '<script> alert("'.$alert.'"); </script>';

} else {

  ECHO '1';

   // echo '<script> alert("Message has been sent"); </script>';
}



function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}


?>

</div>
</div>