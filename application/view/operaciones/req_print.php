<script type="text/javascript">
	
$(window).load(function(){

window.print();



});



</script>

<style type="text/css">
    
td {




}



</style>

<?php	

//sete localtime
//setlocale(LC_ALL,”es_ES”);

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


?>

<div  class="page-print col-xs-11">
<div  class="col-xs-12">
<!-- contenido -->
<page size="A4">

<div class=" col-xs-12">

<div style="float:right;" class="print_button col-md-2">
<a href="#" onClick="window.print()" class="print_button btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
 <img  class='icon' src="img/Printer.png" />
  <span>Imprimir</span>
</a>
</div>

<div id="printable" class="col-xs-12">

<!-- company info  -->
               <div class="row">
                <div class="invoice_header  tableInvDe col-xs-5">
                 <h2 class="h_invoice_header" ><?php echo $name ; ?></h2>
                 <table class="tableInvoice">
                    <tr>
                      <th><strong><?php echo $address ; ?></strong></th>
                    </tr>
                    <tr>
                      <th><strong>Voice :</strong><?php echo $tel ; ?></th>
                      <th></th>
                    </tr>
                    <tr>
                      <th><strong>Fax :</strong> <?php echo $fax ; ?></th>
                      <th></th>
                    </tr>
                 </table>
                   
                </div>
             
                <div class="col-xs-2"></div>
 
<!-- Order Info  -->   
               
                <div class="invoice_header tableInvDe col-xs-5">
                <h2 class="h_invoice_header" >Requisición</h2>
                 <table class="tableInvoice">
                    
                    <tr>
                      <th><strong>Referencia:</strong> <?php echo $ref; ?></th>
                      <th></th>
                    </tr>
                    <tr>  
                      <th><strong>Fecha:</strong> <?php echo $meses[date('n',strtotime($date))-1].' '.date(' j, Y',strtotime($date)); ?></th>
                      <th></th>
                    </tr>
                    <tr>
                      <th><strong>Responsable: </strong> <?php echo $rep; ?></th>
                      <th></th>
                    </tr>
                    <tr>
                      <th></th>
                      <th></th>
                    </tr>
                 </table>
                  
                </div>
               </div>

               <div class="row">
   
                <div class="separador col-xs-12"></div>
                <div class="col-xs-12">
                    <div class="panelB noMarginB  panel-default">
                        <div class="panel-heading">
                        
                             <TABLE   width="100%" >
                                <TR >
                                    <TH width="100%">Descripcion</TH>
                                    
                                </TR>
                            </TABLE>
                       
                        </div>
                        <!-- /.panel-heading -->
                        <div class="invoice-div panel-body">
       
                        <div class="col-xs-12 panelB noMarginB panel-default"><div class="invoice-div4  panel-body"><?php echo  $desc; ?></div></div>
                    
                        </div>
                        
                    </div>
                  
                </div>
               </div>

               <div class="separador col-xs-12"></div>

                <div class="row">
                <div class="col-xs-12">
                    <div class="panelB noMarginB  panel-default">
                   
                             <table width="100%" class="table table-bordered ">
                                <TR >
                                    
                                    <TH width="15%">#Item</TH>
                                    <TH width="35%">Descripcion</TH>
                                    <TH width="10%">Cant.</TH>
                                    
                                    <TH width="10%">Uni.</TH>
                                    <TH width="10%">Proyecto.</TH>
                                    <TH width="10%">Fase.</TH>
                                   <!-- <TH width="10%">C. Cost</TH> --> 
                                    
                                </TR>
             
                     
                    <?php  foreach ($ORDER as  $value) { 

                    $value = json_decode($value);  




                    $table .= '<tr>
                       <td width="15%" style="padding-right:10px; text-align: left;">'.$value->{'ProductID'}.'</td>
                       <td width="35%" ">'.trim($value->{'DESCRIPCION'}).'</td>
                       <td width="10%" class="numb" style="text-align: center; padding-right">'.number_format($value->{'CANTIDAD'},2).'</td>
                       <td width="10%" style="text-align: center; padding-right">'.$value->{'UNIDAD'}.'</td>
                       <td width="10%" style="text-align: center; padding-right">'.$value->{'JOB'}.'</td>
                       <td width="10%" style="text-align: center; padding-right">'.$value->{'PHASE'}.'</td>

                       </tr>';

                    }



                    echo $table;
                    ?>
                    </table> 
                        
                
                  
                </div>
                </div>
                <div class="separador col-xs-12"></div>
 <!-- entregado por -->  
               <div class="row">
                <div class="col-xs-5">
                    <div class="panelB panel-default">
                        <div class="panel-heading">
                            <strong> Entregado por: </strong>
                        </div>
                      
                        <div class="invoice-div invoice-div4  panel-body">
                           
                        </div>
                        
                    </div>
                  
                </div>
               
                <div class="col-xs-2"></div>
      <!--recibido por -->
               
                <div class="col-xs-5">
                    <div class="panelB panel-default">
                        <div class="panel-heading">
                            <strong> Recibido por: </strong>
                        </div>
                        
                        <div class="invoice-div invoice-div4  panel-body">
                            
                        </div>
                           
                           
                       
                        
                    </div>
                  
                </div> 
               </div>
                

</div>
</div>
</div>

</page>
</div>
</div>

<?php
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

?>