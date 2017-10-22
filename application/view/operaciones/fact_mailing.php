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



//INFORMACION DE FACTURA DE COMPRA
$clause= 'WHERE Purchase_Header_Imp.ID_compania="'.$this->model->id_compania.'" and  Purchase_Header_Imp.TransactionID="'.$id.'"' ; 
        
$fact = $this->model->Get_fact_header('ASC','1',$clause);
            
$value = json_decode($fact[0]);
                 
$DATE = date('m/d/Y',strtotime($value->{'Date'}));

$REF =  str_pad($value->{'TransactionID'}, 9 ,"0",STR_PAD_LEFT);
  
$VENDOR = $value->{'VendorName'};
$NO_FACTURA = $value->{'PurchaseNumber'};
$TOTAL = number_format($value->{'Net_due'},2,'.',',');
$NOTA = $value->{'nota'};

$USER = $this->model->Get_User_Info($value->{'USER'});
$USER = json_decode($USER[0]);
$USER_NAME = $USER->{'name'}.' '.$USER->{'lastname'};


//company
$comp = $this->model->Get_company_Info();

foreach ($comp as $value) {
  $value = json_decode($value);
  $address = $value->{'address'};
  $name = $value->{'company_name'};
  $tel= $value->{'Tel'};
  $fax = $value->{'Fax'};
}



?>

?>

<div  class="page-print col-xs-11">
<div  class="col-xs-12">

<?php



$message .='<table  border="1"  cellspacing="0" >
                  
                  <tr>
                    <th style="text-align:left;"><strong>No. Ref.:</strong></th><th style="text-align:right;" >'.$REF.'</th>
                    
                  </tr>
                    <tr>
                      <th style="text-align:left;" ><strong>No. Factura:</strong></th><th style="text-align:right;" >'.$NO_FACTURA.'</th>
                      
                    </tr>
                  <tr>
                    <th style="text-align:left;"><strong>Fecha:</strong></th><th >'.$meses[date('n',strtotime($DATE))-1].' '.date(' j, Y',strtotime($DATE)).'</th>
                   
                  </tr>
                  <tr>
                    <th style="text-align:left;"><strong>Creado por: </strong></th><th  >'.$USER_NAME.'</th>
                   
                  </tr>
                  <tr>
                    <th style="text-align:left;"><strong>Proveedor: </strong></th><th>'.$VENDOR.'</th>
                    
                  </tr>

                 </table>
                  
<br>
                                             
                       
<TABLE   width="100%" border="1"  cellspacing="0"  >
   <TR >
    <TH width="100%">Descripcion</TH>
   </TR>
   <TR >
   <TD width="100%">'.$NOTA.'</TD>
  </TR>
</TABLE>

<br>';                  

 

                        $query ="SELECT * FROM `Purchase_Header_Imp`
                        inner JOIN `Purchase_Detail_Imp` ON Purchase_Detail_Imp.TransactionID = Purchase_Header_Imp.TransactionID
                        WHERE Purchase_Header_Imp.TransactionID='".$id."'  and 
                              Purchase_Header_Imp.ID_compania='".$this->model->id_compania."' and
                              Purchase_Detail_Imp.ID_compania='".$this->model->id_compania."'
                       order BY Purchase_Detail_Imp.ID ASC";


                        $fact_items= $this->model->Query($query);

                        $message.= '<table id="example-12" width="100%" border="1"  cellspacing="0"  >
                              <thead>
                                <tr>
                                  <th>Cant.</th>
                                  <th>Item</th>
                                  <th>Unidad</th>
                                  <th>Descripcion</th>
                                  <th>Cta. GL</th>
                                  <th>Precio Unit.</th>
                                  <th>Total</th>
                                  <th>Job</th>
                                </tr>
                              </thead><tbody>';


                        foreach ($fact_items as $datos) {

                           $fact_items = json_decode($datos);

                            $id= "'".$fact_items->{'InvoiceNumber'}."'";


                        $message.=  "<tr>
                                  <td  style='text-align:right;' >".number_format($fact_items->{'Quantity'},5,'.',',')."</td>
                                  <td>".$fact_items->{'Item_id'}."</td>
                                  <td>".$fact_items->{'UnitMeasure'}.'</td>
                                  <td>'.$fact_items->{'Description'}.'</td>
                                  <td  style="text-align:right;" >'.$fact_items->{'GL_Acct'}."</td>
                                  <td  style='text-align:right;'>".number_format($fact_items->{'Unit_Price'},4,'.',',')."</td>
                                  <td  style='text-align:right;'>".number_format($fact_items->{'Net_line'},4,'.',',').'</td>
                                  <td>'.$fact_items->{'JobID'}.'</td>
                              </tr>';

                          }

                       $message.= '</tbody></table>


                       "<TABLE  width="25%" border="1" style="margin-left:60%;" cellspacing="0"  >
                       <tr><td style="text-align:right;" >TOTAL</td><td style="text-align:right;">'.$TOTAL.'</td></TR></table>';
                



//echo $message;

$message_to_send ='<html>
<head>
<meta charset="UTF-8">
<title>Factura de compra</title>
</head>
<body>
<P>Se ha creado desde la interfaz ACIWEB la siguiente Factura de compra:</p>
<div width="800px">'.$message.'</div></body>
</html>';

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



$mail->Subject = utf8_decode("Factura de compra-".$REF);
$mail->Body = $message_to_send;




//VERIFICA USUARIOS CON OPCION D ENOTIFICACION DE ORDEN DE COMPRAS
$sql = 'SELECT name, lastname, email from SAX_USER WHERE notif_fc="1" and onoff="1"';
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



