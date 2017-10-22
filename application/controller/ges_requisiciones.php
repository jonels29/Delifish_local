<?PHP

class ges_requisiciones extends Controller
{

public $ProductID;

public function req_crear(){
 


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/req_crear.php';
            require APP . 'view/_templates/footer.php';


        }
          


  
}

public function req_hist(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/req_hist.php';
            require APP . 'view/_templates/footer.php';


        }
  
}

public function req_print($id){


 $res = $this->model->verify_session();

        if($res=='0'){

        $ORDER = $this->model->get_req_to_print($id, $this->model->id_compania);
  
            foreach ($ORDER as  $value) {

              $value = json_decode($value);

             $name     =  $this->model->Query_value('SAX_USER','name','Where ID="'.$value->{'USER'}.'"');
             $lastname =  $this->model->Query_value('SAX_USER','lastname','Where ID="'.$value->{'USER'}.'"');

             $Job= $value->{'JobID'};      
             $fase= $value->{'PhaseID'};
             $ccost= $value->{'CostCodeID'};
              
              $ref = $value->{'NO_REQ'};

              $rep = $name.' '.$lastname;

              $date = $value->{'DATE'};

              $desc = $value->{'NOTA'};



            }
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/req_print.php';
            require APP . 'view/_templates/footer.php';


        }


}

public function req_mailing($id){

 $res = $this->model->verify_session();

      if($res=='0'){


      require 'PHP_mailer/PHPMailerAutoload.php';
      $mail = new PHPMailer;


      $ORDER = $this->model->get_req_to_print($id, $this->model->id_compania);

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/req_mailing.php';
            require APP . 'view/_templates/footer.php';


        }


}

public function req_reception($id){


 $res = $this->model->verify_session();

      if($res=='0'){


            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';


        ECHO '<input id="reqidhide" type="hidden" value="'.$id.'" />';

            require APP . 'view/operaciones/req_reception.php';
            require APP . 'view/_templates/footer.php';


        }


}



///////////////////////////////////////////////////////////////////////////////////////////////
//LISTA DE JOBS, FASES Y CENTRO DE COSTOS
public function get_JobList(){
$this->model->verify_session();

$jobs = $this->model->get_JobList(); 


foreach ($jobs as $value) {

 $value = json_decode($value);

  $list.= '<option value="'.$value->{'JobID'}.'" >'.$value->{'JobID'}.'-'.$value->{'Description'}.'</option>';

}

echo $list;


}


public function get_phaseList(){
$this->model->verify_session();

$phase = $this->model->get_phaseList();

if($phase!='0'){

foreach ($phase as $value) {

$value = json_decode($value);

  $list.= '<option value="'.$value->{'PhaseID'}.'" >'.$value->{'PhaseID'}.'</option>';

}


echo $list;

}else{

echo '0';

}



}

public function get_costList(){
$this->model->verify_session();

$cost = $this->model->get_costList();


if($cost!='0'){

foreach ($cost as $value) {

$value = json_decode($value);

  $list.= '<option value="'.$value->{'CostCodeID'}.'" >'.$value->{'Description'}.'</option>';

}


echo $list;


}else{

echo '0';

}


}

public function get_ProductsCode(){

$this->model->verify_session();

$sql = 'SELECT ProductID FROM Products_Exp WHERE id_compania="'.$this->model->id_compania.'"';

$Codigos = $this->model->Query($sql);

foreach ($Codigos as $value) {

  $value = json_decode($value);
   
  $codes .= '<option value="'.$value->{'ProductID'}.'">'.$value->{'ProductID'}.'</option>';

 } 

echo $codes;

}


//REQUISICIONES//////////////////////////////////////////////////////////////////////////////////////////////////////////
public function set_req_header($JobID,$nota){
$this->model->verify_session();

$Req_NO = $this->model->Get_Req_No();

$value_to_set  = array( 
  'NO_REQ' => $Req_NO,   
  'ID_compania' => $this->model->id_compania, 
  'NOTA' => $nota , 
  'USER' => $this->model->active_user_id, 
  'DATE' => date("Y-m-d"), 
  );

$res = $this->model->insert('REQ_HEADER',$value_to_set);
$this->CheckError();


ECHO $Req_NO;

}



public function set_req_items($NO_REQ){
$this->model->verify_session();

$data = json_decode($_GET['Data']);

foreach ($data as $value) {


  list($null,$ITEMID, $DESC, $QTY, $UNIT, $JOB_ID, $PROY_DESC, $PHASE_ID, $PHASE_DESC ) = explode('@', $value );
   

  $value_to_set  = array( 
    'ProductID' => $ITEMID, 
    'DESCRIPCION' => $DESC,
    'CANTIDAD' => $QTY,  
    'UNIDAD' => $UNIT,  
    'JOB' => $JOB_ID,  
    'PHASE' => $PHASE_ID,    
    'NO_REQ' => $NO_REQ, 
    'ITEM_UNIQUE_NO' => $ITEMID.'@'.$NO_REQ.'@'.$ITEMID.'@'.$this->model->id_compania,
    'ID_compania' => $this->model->id_compania
    );

   //var_dump( $value_to_set);
  
   $res = $this->model->insert('REQ_DETAIL',$value_to_set);
   $this->CheckError();


}

echo '1';

}



public function set_req_recept($ITEM,$QTY,$REQ_NO,$COUNT, $ARRGL){
$this->model->verify_session();

    $value_to_set  = array( 
      'NO_REQ' => $REQ_NO, 
      'ITEM' => $ITEM, 
      'QTY' => $QTY, 
      'USER' => $this->model->active_user_id, 
      'ID_compania' => $this->model->id_compania
      );


    $res = $this->model->insert('REQ_RECEPT',$value_to_set);
    $this->CheckError();


    if($COUNT==$ARRGL){ //SI LOS ITEMS PROCESADOS CONTABILIZADOS CON count ES IGUAL EL NUMERO DE LINEAS EN EL ARRAY (ARRLENG) entonces devuelve 0 para terminar el proceso de insesion de registros
      echo '1'; 
    }else{ 
      echo '0';
    } 
}


public function get_req_item_lines($id){
$this->model->verify_session();

$count = $this->model->Query_value('REQ_DETAIL','COUNT(*)', 'WHERE ID_compania="'.$this->model->id_compania.'" AND NO_REQ="'.$id.'"');

ECHO $count;
}

public function set_req_quota($REQ_NO,$ID_compania){

$this->model->verify_session();

if($_SESSION){

$id_user = $this->model->active_user_id;

$VALID = $this->model->Query_value('REQ_QUOTA','NO_REQ','WHERE  NO_REQ ="'.$REQ_NO.'" 
                                                           AND  ID_compania="'.$ID_compania.'"');

if(!$VALID){

  $value = array( 'NO_REQ' => $REQ_NO , 
                  'USER' => $id_user, 
                  'ID_compania' => $ID_compania );

  $insert = $this->model->insert('REQ_QUOTA',$value);


  echo '<script>  alert("Cotizacion iniciada correctamente para la requisicion No. '.$REQ_NO.'");  

                 self.location="'.URL.'index.php?url=ges_reportes/rep_reportes"; 

       </script>';


}else{


  echo '<script>  alert("Para la requisicion No. '.$REQ_NO.' ya se ha iniciado la cotizacion");  
                 self.location="'.URL.'index.php?url=ges_reportes/rep_reportes"; 
        </script>';


}

}


}




public function get_PO_details($id){

$this->model->verify_session();
$oc = $this->model->get_items_by_OC($id);

$table.= '<button type="button" class="close" aria-label="Close" onclick="CLOSE_DIV('."'table2'".');" >
          <span STYLE="color:red" aria-hidden="true">&times; </span> Cerrar
          </button>

          <fieldset>
          
          <legend>Detalle de Orden de Compra</legend>



          <table   class="table table-striped table-bordered" cellspacing="0"  >
    <tbody>';
  
    $value = json_decode($oc[0]);

    $inv = "'".$value->{'PurchaseID'}."'";
    $url = "'".URL."'"; 


    $table.= "<tr><th style='text-align:left;' width='25%'>ID. Compra.</th><td >".$value->{'PurchaseOrderNumber'}.'</td></tr>
           <tr><th style="text-align:left;" width="25%">Fecha</th><td >'.$value->{'Date'}.'</td></tr>
           <tr><th style="text-align:left;" width="25%">Requisición</th><td >'.$value->{'CustomerSO'}.'</td></tr>
           <tr><th style="text-align:left;" width="25%">Proveedor</th><td >'.$value->{'VendorName'}.'</td></tr>
           <tr><th style="text-align:left;" width="10%">Estado</th> <td >'.$value->{'WorkflowStatusName'}.'</td></tr>
           <tr><th style="text-align:left;" width="10%">Asignado a</th> <td >'.$value->{'WorkflowAssignee'}.'</td></tr>
          <tr><th style="text-align:left;" width="30%">Nota</th><td >'.$value->{'WorkflowNote'}.'</td></tr>';
  
    $table.= '</tbody></table>

    <table id="Items" class="table table-striped" cellspacing="0"  >
    <thead>
      <tr>
        <th width="20%">Codigo Item</th>
        <th width="30%">Descripcion</th>
        <th width="10%">Cantidad</th>
        <th width="10%">Precio Uni.</th>
        <th width="10%">Total</th>
      </tr>
    </thead>
 
 <tbody >';
 
  foreach ($oc as $value) {

    $value = json_decode($value);

    $inv = "'".$value->{'PurchaseID'}."'";
    $url = "'".URL."'"; 

          $table.= "<tr>
            <td >".$value->{'Item_id'}.'</td>
            <td >'.$value->{'Description'}.'</td>
            <td class="numb" >'.number_format($value->{'Quantity'},2,'.',',').'</td>
            <td class="numb"  >'.number_format($value->{'Unit_Price'},2,'.',',').'</td>
            <td class="numb" >'.$value->{'NetLine'}.'</td>
          </tr>';

    }

     
  $table.='</tbody></table></fieldset>';


    echo $table;




}

public function get_reception($id){

$this->model->verify_session();

//IF EXSIST
$EXIST_ID = $this->model->Query_value('REQ_HEADER','NO_REQ','WHERE REQ_HEADER.ID_compania="'.$this->model->id_compania.'" and REQ_HEADER.NO_REQ="'.$id.'"');

if($EXIST_ID!=''){

$ORDER_detail = $this->model->get_req_to_report('DESC','1','WHERE REQ_HEADER.ID_compania="'.$this->model->id_compania.'" AND  REQ_HEADER.ID_compania="'.$this->model->id_compania.'" and REQ_HEADER.NO_REQ="'.$id.'" and REQ_DETAIL.NO_REQ="'.$id.'"');

echo '<script>

var table = $("#table_info").dataTable({

       rowReorder: {
            selector: "td:nth-child(2)"
        },

      bSort: false,
      select:true,
      scrollX: "100%",
      scrollCollapse: true,
      responsive: false,
      searching: false,
      paging:    false,
      info:      false
     });


</script>';

echo '<br/><br/><fieldset><legend>Detalle de Requisición</legend>
<table  class="display nowrap table table-striped table-bordered" cellspacing="0"  ><tbody>';

  foreach ($ORDER_detail as $datos) {
    $ORDER_detail = json_decode($datos);


$user = $this->model->Get_User_Info($ORDER_detail->{'USER'}); 

foreach ($user as $value) {
$value = json_decode($value);
$name= $value->{'name'};
$lastname = $value->{'lastname'};
}


//obtengo estatus de la requisicion
$status = $this->req_status($ORDER_detail->{'NO_REQ'},$this->model->id_compania);

switch ($status) {
  case 'CERRADA':
     $style = 'style="background-color:#D8D8D8 ;"';//verder
    break;
  case 'FINALIZADO':
     $style = 'style="background-color:#BCF5A9;"';//verder
    break;
  case 'ORDENADO':
     $style = 'style="background-color:#F2F5A9;"';//AMARILLO
    break;
  case 'PARCIALMENTE ORDENADO':
     $style = 'style="background-color:#F3E2A9;"';//NARANJA
    break;
  case 'COTIZANDO':
     $style = 'style="background-color:#F7BE81;"';//NARANJA
    break; 
  case 'POR COTIZAR':
     $style = 'style="background-color:#F5A9A9;"';//ROJO
    break; 

}


echo     "<tr><th style='text-align:left;' ><strong>No. Req</strong></th><td class='InfsalesTd order'>".$ORDER_detail->{'NO_REQ'}."</td><tr>
          <tr><th style='text-align:left;'><strong>Fecha</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'DATE'}."</td><tr>
          <tr><th style='text-align:left;'><strong>Solicitado por:</strong></th><td class='InfsalesTd'>".$name.' '.$lastname.'</td><tr>
          <tr><th style="text-align:left;" ><strong>Estado</strong></th><td '.$style.' class="InfsalesTd">'.$status.'</td><tr>';

}


echo "</tbody></table>";



$ORDER= $this->model->get_req_to_print($id, $this->model->id_compania);

echo '<table id="table_info" class="table table-striped table-bordered" cellspacing="0"  >
      <thead>
        <tr>
          <th>Codigo</th>
          <th>Descripcion</th>
          <th>Unidad</th>
          <th>Cant. Requerida</th>
          <th>Cant. Ordenada</th>
          <th>Cant. Recibida</th>
          <th>OC. Asociadas</th>
          <th>Status</th>
          <th>Registar Recibido</th>
        </tr>
      </thead><tbody>';

$i=1;

foreach ($ORDER as $datos) {

$ORDER = json_decode($datos);



//Informacion de ORDEN DE COMPRA PARA ESTE PRODUCTO EN LA REQUISICION
    $sql_OC = 'SELECT 
    PurOrdr_Header_Exp.PurchaseOrderNumber,
    sum(PurOrdr_Detail_Exp.Quantity) as Quantity
    FROM PurOrdr_Header_Exp
    INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
    WHERE PurOrdr_Header_Exp.CustomerSO =  "'.$ORDER_detail->{'NO_REQ'}.'"
    AND PurOrdr_Header_Exp.ID_compania =  "'.$this->model->id_compania.'"
    AND PurOrdr_Detail_Exp.Item_id = "'.$ORDER->{'ProductID'}.'"
    AND PurOrdr_Header_Exp.PurchaseOrderNumber <> " "';

    $INFO_OC = $this->model->Query($sql_OC);

    $QTY_TOTAL=0;

    unset($Qty_Comprada);

    foreach ($INFO_OC as $datos) {

        $INFO_OC = json_decode($datos);
        $Qty_Comprada[$INFO_OC->{'PurchaseOrderNumber'}] = $INFO_OC->{'Quantity'};
        $QTY_TOTAL += $INFO_OC->{'Quantity'};

      }

    $QTY_FALTANTE = $ORDER->{'CANTIDAD'}-$QTY_TOTAL;

    $oc_list='';

    foreach ($Qty_Comprada as $key => $value) {

    $PO_NO = "'".$key."'";

    $oc_list .='<a href="javascript:void(0)" onclick="get_OC('.$PO_NO.');"><strong>'.$key.' ('.number_format($value,0,'.',',').')</strong></a><BR>';
    }
//Informacion de ORDEN DE COMPRA PARA ESTE PRODUCTO EN LA REQUISICION




//INI STATUS POR ITEM/////////////////////////////////////////////////////////////////////////////////////////////
  $status = $this->req_item_status($ORDER_detail->{'NO_REQ'},$ORDER->{'ProductID'},$ORDER->{'CANTIDAD'},$this->model->id_compania);

  $VISIBLE = ' ';

switch ($status) {
  case 'CERRADA':
     $style_row = 'style="background-color:#D8D8D8;"';//verder
    break;

  case 'FINALIZADO':
     $style_row = 'style="background-color:#BCF5A9;"';//verder
    break;
  case 'ORDENADO':
     $style_row = 'style="background-color:#F2F5A9;"';//AMARILLO
    break;
  case 'SOBREORDENADO':
     $style_row = 'style="background-color:#F2F5A9;"';//AMARILLO
    break;
  case 'ORDENADO / RECEPCION PARCIAL':
      $style_row = 'style="background-color:#F2F5A9;"';//AMARILLO
    break; 
  case 'PARCIALMENTE ORDENADO':
     $style_row = 'style="background-color:#F3E2A9;"';//NARANJA
    break;
  case 'PARCIALMENTE ORDENADO / RECEPCION PARCIAL':
      $style_row = 'style="background-color:#F3E2A9;"';//NARANJA
    break; 
  case 'COTIZANDO':
     $style_row= 'style="background-color:#F7BE81;"';//NARANJA
    break; 
  case 'POR COTIZAR':
     $style_row = 'style="background-color:#F5A9A9;"';//ROJO
    break; 

  }
//FIN  STATUS POR ITEM/////////////////////////////////////////////////////////////////////////////////////////////


//BLOQUEA CAMPO D EENTRADA SI LO ORDENADO ES IGUAL A LO RECIBIDO
//TOTEL RECIVIDO POR ITEM
$total_reciv = $this->model->Query_value('REQ_RECEPT','SUM(QTY)','WHERE  ID_compania="'.$this->model->id_compania.'" 
                                                                         AND NO_REQ="'.$ORDER_detail->{'NO_REQ'}.'"
                                                                         AND ITEM="'.$ORDER->{'ProductID'}.'"');

if($QTY_TOTAL <= $total_reciv){ $VISIBLE = 'style="background-color : #D8D8D8;"  readonly';  }
//BLOQUEA CAMPO D EENTRADA SI LO ORDENADO ES IGUAL A LO RECIBIDO


echo  "<tr ".$style_row.">
            <td><label  id='ID".$i."'  >".$ORDER->{'ProductID'}."</label></td>
            <td>".$ORDER->{'DESCRIPCION'}."</td>
            <td class='numb' >".$ORDER->{'UNIDAD'}."</td>
            <td class='numb'>
            <label class='numb' id='QTYREQ".$ORDER->{'ProductID'}."' >".$ORDER->{'CANTIDAD'}."</label>
            </td>
            <td class='numb'>
            <label class='numb' id='QTYORD".$ORDER->{'ProductID'}."' >".$QTY_TOTAL."</label>             
            </td>
            <td class='numb'>
            <label class='numb' id='QTYRCV".$ORDER->{'ProductID'}."' >".$total_reciv."</label>
            </td>
            <td>".$oc_list."</td>
            <td>".$status."</td>
            <td width='5%' class='numb'>
            <input class='numb' id='rec".$ORDER->{'ProductID'}."'  type='number' min='0.01' step='0.1' value='' ".$VISIBLE." />
            </td></tr>";
$i+=1;
  }

echo '</tbody></table>

<div class=" separador col-lg-12"></div>

<div style="float:right;" class="col-md-2">
<input type="buttom" onclick="save();" class="btn btn-primary  btn-sm btn-icon icon-right" value="Guardar">
</div>
</fieldset>
<div class=" separador col-lg-12"></div>

  <div class="col-lg-12">
    <fieldset>
    <legend>Historial de recepción</legend>
        <table class="table table-striped table-bordered" cellspacing="0" >
          <thead> 
            <th># Item</th>
            <th>Cantidad Recibida</th>
            <th>Fecha Recepcion</th>
            <th>Registrado por:</th>
            
          </thead>
          <tbody>';

$SQL = 'SELECT * 
        FROM REQ_RECEPT
        WHERE  ID_compania="'.$this->model->id_compania.'" 
        AND NO_REQ="'.$ORDER_detail->{'NO_REQ'}.'"';

$RECEPT = $this->model->Query($SQL);

        foreach ($RECEPT  as $value) {
         
         $value = json_decode($value);

          $user = $this->model->Get_User_Info($value->{'USER'}); 

          foreach ($user as $USER) {
            $USER = json_decode($USER);
            $name= $USER->{'name'};
            $lastname = $USER->{'lastname'};
          }

        $date = strtotime($value->{'DATE'});
        $date = date('m/d/Y',$date);
   
 ECHO   '<tr><td>'.$value->{'ITEM'}.'</td>'.
        '<td class="numb" >'.number_format($value->{'QTY'},4,'.',',').'</td>'.
        '<td class="numb" >'.$date.'</td>'.
        '<td>'.$name.' '.$lastname.'</td></tr>';

        }

 ECHO   '</tbody>
        </table>';

  if(!$RECEPT){
    echo 'AUN NO EXISTEN REGISTROS DE RECEPCIÓN';

  }

    ECHO  '</fieldset>
           </div>
          <div class=" separador col-lg-12"></div>

            <div id="table2" class="col-lg-12"></div>


          ';


  }else{


     echo '<div id="ERROR" class="alert alert-danger">EL ID <strong>'.$id.'</strong> DE REQUISICIÓN NO EXISTE</div>';

  }

}




public function req_status($id,$id_compania){
//////////////////////////////////////////////////ESTADO DEL PROCESO DE LA REQUISICION //////////////////////////////////////////////////////////

//STATUS INICIAL
$status = 'POR COTIZAR';

//CHECHO SI COTIZACION HA SIDO INICIADA 
$chk_quota = $this->model->Query_value('REQ_QUOTA','ID',' WHERE NO_REQ = "'.$id.'" AND
                                                                ID_compania =  "'.$id_compania.'"');

if($chk_quota){
    $status = 'COTIZANDO';
}


//CHECO ORDENES DE COMPRAS ASOCIADAS
$clause = 'INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
           INNER JOIN REQ_DETAIL ON REQ_DETAIL.NO_REQ = PurOrdr_Header_Exp.CustomerSO AND REQ_DETAIL.ProductID = PurOrdr_Detail_Exp.Item_id
           WHERE PurOrdr_Header_Exp.CustomerSO =  "'.$id.'"
            AND PurOrdr_Header_Exp.ID_compania =  "'.$id_compania.'"
            AND PurOrdr_Header_Exp.PurchaseOrderNumber <> ""';

$chk_po =$this->model->Query_value('PurOrdr_Header_Exp','PurOrdr_Header_Exp.TransactionID', $clause);


if($chk_po){
    $status = 'PARCIALMENTE ORDENADO';
}


//CHECO TOTAL RESTANTE 
$total_restante = $this->get_req_status($id,$id_compania);


if($total_restante == 0 ){
    
    $status = 'ORDENADO';

    //TOTAL ORDENADO 
    $TOTAL_ORDENADO = $this->get_req_ord($id,$id_compania);

    //CHECO TOTAL RECIBIDO
    $totel_reciv = $this->model->Query_value('REQ_RECEPT','SUM(QTY)','WHERE  ID_compania="'.$id_compania.'" 
                                                                             AND NO_REQ="'.$id.'"');

    $rev_ord = $TOTAL_ORDENADO - $totel_reciv;

    if($TOTAL_ORDENADO > 0){

       if($rev_ord==0){
           $status = 'FINALIZADO';
       }

    }

}




//CHECO SI ESTA CERRADA FORZOSAMENTE
$chk_closed = $this->model->Query_value('REQ_HEADER','st_closed','WHERE  ID_compania="'.$id_compania.'" 
                                                                         AND NO_REQ="'.$id.'"');
if($chk_closed =='1'){

  $status = 'CERRADA';

}

return $status;

}


public function req_item_status($id,$item,$qty,$comp){


//////////////////////////////////////////////////ESTADO DEL PROCESO DE LA REQUISICION //////////////////////////////////////////////////////////

//STATUS INICIAL
$status = 'POR COTIZAR';

//CHECHO SI COTIZACION HA SIDO INICIADA 
$chk_quota = $this->model->Query_value('REQ_QUOTA','ID',' WHERE NO_REQ = "'.$id.'" AND
                                                                ID_compania =  "'.$comp.'"');

if($chk_quota){
    $status = 'COTIZANDO';
}


 $sql_OC = 'SELECT 
  PurOrdr_Header_Exp.PurchaseOrderNumber,
  PurOrdr_Detail_Exp.Quantity
  FROM PurOrdr_Header_Exp
  INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
  WHERE PurOrdr_Header_Exp.CustomerSO =  "'.$id.'"
  AND PurOrdr_Header_Exp.ID_compania =  "'.$comp.'"
  AND PurOrdr_Detail_Exp.Item_id = "'.$item.'"
  AND PurOrdr_Header_Exp.PurchaseOrderNumber <> ""';

  $INFO_OC = $this->model->Query($sql_OC);

  $QTY_TOTAL=0;

  foreach ($INFO_OC as $datos) {

      $INFO_OC = json_decode($datos);
      $QTY_TOTAL += $INFO_OC->{'Quantity'};

    }

if($INFO_OC){
    $status = 'PARCIALMENTE ORDENADO';
 }


//CHECO TOTAL RESTANTE 
//TOTEL RECIVIDO POR ITEM
$total_reciv = $this->model->Query_value('REQ_RECEPT','SUM(QTY)','WHERE  ID_compania="'.$comp.'" 
                                                                         AND NO_REQ="'.$id.'"
                                                                         AND ITEM="'.$item.'"');
//TOTAL ORDENADO POR ITEM
$TOTAL_ORDENADO = $QTY_TOTAL;

//TOTAL RESTANTE POR ORDENAR
$total_restante = $qty - $TOTAL_ORDENADO;


if($total_restante == 0 ){
    
    $status = 'ORDENADO';


    $rev_ord = $TOTAL_ORDENADO - $total_reciv;

    if($TOTAL_ORDENADO > 0){

       if($rev_ord==0){
           $status = 'FINALIZADO';
       }

    }

}elseif ($total_restante < 0) {
  

    $status = 'SOBREORDENADO';

     $rev_ord = $TOTAL_ORDENADO - $total_reciv;

    if($TOTAL_ORDENADO > 0){

       if($rev_ord==0){
           $status = 'FINALIZADO';
       }

    }


}


if ($total_reciv > 0 && $status <> 'FINALIZADO'){

  $status .= ' / RECEPCION PARCIAL';

}


//CHECO SI ESTA CERRADA FORZOSAMENTE
$chk_closed = $this->model->Query_value('REQ_HEADER','st_closed','WHERE  ID_compania="'.$comp.'" 
                                                                         AND NO_REQ="'.$id.'"');
if($chk_closed =='1'){

  $status = 'CERRADA';

}


return $status;
}



public function get_req_status($id,$id_compania){

 $total_comprado = 0;
 $total_restante = 0;

//saco estatus de REQUISICION
$sql_total = 'SELECT 
sum(PurOrdr_Detail_Exp.Quantity) as TOTAL_COMPRADO
FROM PurOrdr_Header_Exp
INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
WHERE PurOrdr_Header_Exp.CustomerSO =  "'.$id.'"
AND PurOrdr_Header_Exp.ID_compania =  "'.$id_compania.'"';

$sql_TOTAL_COMPRADO = $this->model->Query($sql_total);
$this->CheckError();

foreach ($sql_TOTAL_COMPRADO as $value) {
  $value =  json_decode($value);

  $total_comprado = $value->{'TOTAL_COMPRADO'};

  }



$clause = "WHERE ID_compania='".$id_compania."' and NO_REQ='".$id."'";
$total_REQ = $this->model->Query_value('REQ_DETAIL','sum(CANTIDAD)',$clause);

if(!$total_REQ){

 $total_REQ = $this->model->Query_value('REQ_DETAIL','sum(CANTIDAD)',$clause);

}
/*ECHO $total_REQ = $total_REQ ;
ECHO '<BR>'.$id.' '.$total_REQ.'  '.$id_compania.'<BR>';*/


$total_restante = $total_REQ - $total_comprado; 


//ECHO '<BR>'.$total_restante;
return $total_restante;
}

public function get_req_ord($id,$id_compania){


//saco estatus de REQUISICION
$sql_total = 'SELECT 
sum(PurOrdr_Detail_Exp.Quantity) as TOTAL_COMPRADO
FROM PurOrdr_Header_Exp
INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
INNER JOIN REQ_DETAIL ON REQ_DETAIL.NO_REQ = PurOrdr_Header_Exp.CustomerSO
AND REQ_DETAIL.ProductID = PurOrdr_Detail_Exp.Item_id
WHERE PurOrdr_Header_Exp.CustomerSO =  "'.$id.'"
AND PurOrdr_Header_Exp.ID_compania =  "'.$id_compania.'"
AND PurOrdr_Header_Exp.PurchaseOrderNumber <> ""';

$sql_TOTAL_COMPRADO = $this->model->Query($sql_total);


foreach ($sql_TOTAL_COMPRADO as $value) {
  $value =  json_decode($value);

  $total_comprado = $value->{'TOTAL_COMPRADO'};

  }

return $total_comprado;
}


public function get_req_info($id){
$this->model->verify_session();

$id_compania = $this->model->id_compania;

$ORDER_detail = $this->model->get_req_to_report('DESC','1','WHERE REQ_HEADER.ID_compania="'.$id_compania.'" AND  REQ_HEADER.ID_compania="'.$id_compania.'" and REQ_HEADER.NO_REQ="'.$id.'" and REQ_DETAIL.NO_REQ="'.$id.'"');

echo '<script>

var table = $("#table_info").dataTable({

       rowReorder: {
            selector: "td:nth-child(2)"
        },

      bSort: false,
      select:true,
      scrollX: "100%",
      scrollCollapse: true,
      responsive: false,
      searching: false,
      paging:    false,
      info:      false });


</script>';

echo '<br/><br/>
<button type="button" class="close" aria-label="Close" onclick="CLOSE_DIV('."'info'".');" >
          <span STYLE="color:red" aria-hidden="true">&times; </span> Cerrar
          </button>
<fieldset><legend>Detalle de Requisición</legend>
<table  class="display nowrap table table-striped table-bordered" cellspacing="0"  ><tbody>';

  foreach ($ORDER_detail as $datos) {
    $ORDER_detail = json_decode($datos);


$user = $this->model->Get_User_Info($ORDER_detail->{'USER'}); 

foreach ($user as $value) {
$value = json_decode($value);
$name= $value->{'name'};
$lastname = $value->{'lastname'};
}


//obtengo estatus de la requisicion
$status_gen = $this->req_status($id,$id_compania);

switch ($status_gen) {
  case 'CERRADA':
     $style = 'style="background-color:#D8D8D8 ;"';//verder
    break;
  case 'FINALIZADO':
     $style = 'style="background-color:#BCF5A9;"';//verder
    break;
  case 'ORDENADO':
     $style = 'style="background-color:#F2F5A9;"';//AMARILLO
    break;
  case 'PARCIALMENTE ORDENADO':
     $style = 'style="background-color:#F3E2A9;"';//NARANJA
    break;
  case 'COTIZANDO':
     $style = 'style="background-color:#F7BE81;"';//NARANJA
    break; 
  case 'POR COTIZAR':
     $style = 'style="background-color:#F5A9A9;"';//ROJO
    break; 

}




echo     "<tr><th style='text-align:left;' ><strong>No. Req</strong></th><td class='InfsalesTd order'>".$ORDER_detail->{'NO_REQ'}."</td><tr>
          <tr><th style='text-align:left;'><strong>Fecha</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'DATE'}."</td><tr>
          <tr><th style='text-align:left;'><strong>Solicitado por:</strong></th><td class='InfsalesTd'>".$name.' '.$lastname.'</td><tr>
          <tr><th style="text-align:left;" ><strong>Estado</strong></th><td '.$style.' class="InfsalesTd">'.$status_gen.'</td><tr>';

}


echo "</tbody></table>";



$ORDER= $this->model->get_req_to_print($id,$id_compania);

echo '<table id="table_info" class="table table-striped table-bordered" cellspacing="0">
      <thead>
        <tr>
          <th width="5%">Renglon</th>
          <th width="10%">Descripcion</th>
          <th width="5%">Unidad</th>
          <th width="10%">Fase</th>
          <th width="5%">Cant. Requerida</th>
          <th width="5%">Cant. Ordenada</th>
          <th width="5%">Cant. Por Ordenar</th>
          <th width="5%">Cant. Recibida</th>
          <th width="20%">OC asociadas (Cant. Ordenada)</th>
          <th width="20%">Estado</th>
        </tr>
      </thead><tbody>';



foreach ($ORDER as $datos) {

$ORDER = json_decode($datos);


//Informacion de ORDEN DE COMPRA PARA ESTE PRODUCTO EN LA REQUISICION
$sql_OC = 'SELECT 
  PurOrdr_Header_Exp.PurchaseOrderNumber,
  sum(PurOrdr_Detail_Exp.Quantity) as Quantity
  FROM PurOrdr_Header_Exp
  INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
  WHERE PurOrdr_Header_Exp.CustomerSO =  "'.$ORDER_detail->{'NO_REQ'}.'"
  AND PurOrdr_Header_Exp.ID_compania =  "'.$id_compania.'"
  AND PurOrdr_Detail_Exp.Item_id = "'.$ORDER->{'ProductID'}.'"
  AND PurOrdr_Header_Exp.PurchaseOrderNumber <> ""';

  $INFO_OC = $this->model->Query($sql_OC);

  $QTY_TOTAL=0;

  unset($Qty_Comprada);

  foreach ($INFO_OC as $datos) {

      $INFO_OC = json_decode($datos);
      $Qty_Comprada[$INFO_OC->{'PurchaseOrderNumber'}] = $INFO_OC->{'Quantity'};
       $QTY_TOTAL += $INFO_OC->{'Quantity'};

    }


//INI STATUS POR ITEM/////////////////////////////////////////////////////////////////////////////////////////////
$status = $this->req_item_status($ORDER_detail->{'NO_REQ'},$ORDER->{'ProductID'},$ORDER->{'CANTIDAD'},$id_compania );


switch ($status) {
  case 'CERRADA':
     $style_row = 'style="background-color:#D8D8D8;"';//verder
    break;
  case 'FINALIZADO':
     $style_row = 'style="background-color:#BCF5A9;"';//verder
    break;
  case 'ORDENADO':
     $style_row = 'style="background-color:#F2F5A9;"';//AMARILLO
    break;
  case 'SOBREORDENADO':
     $style_row = 'style="background-color:#F2F5A9;"';//AMARILLO
    break;
  case 'ORDENADO / RECEPCION PARCIAL':
      $style_row = 'style="background-color:#F2F5A9;"';//AMARILLO
    break; 
  case 'PARCIALMENTE ORDENADO':
     $style_row = 'style="background-color:#F3E2A9;"';//NARANJA
    break;
  case 'PARCIALMENTE ORDENADO / RECEPCION PARCIAL':
      $style_row = 'style="background-color:#F3E2A9;"';//NARANJA
    break; 
  case 'COTIZANDO':
     $style_row= 'style="background-color:#F7BE81;"';//NARANJA
    break; 
  case 'POR COTIZAR':
     $style_row = 'style="background-color:#F5A9A9;"';//ROJO
    break; 


}


//Informacion de ORDEN DE COMPRA PARA ESTE PRODUCTO EN LA REQUISICION
$oc_list='';

foreach ($Qty_Comprada as $key => $value) {

//$oc_list .='<a href="'.URL.'index.php?url=ges_compras/orden_compras/'.$key.'" target="_blank" ><strong>'.$key.' ('.number_format($value,0,'.',',').')</strong></a><BR>';

$PO_NO = "'".$key."'";
  if($key){
   $oc_list .='<a href="javascript:void(0)" onclick="get_OC('.$PO_NO.');"><strong>'.$key.' ('.number_format($value,0,'.',',').')</strong></a><BR>';
  }

}


//cantidad recibida para este item
$qty_reciv = $this->model->Query_value('REQ_RECEPT','SUM(QTY)','WHERE  ID_compania="'.$id_compania.'" 
                                        AND NO_REQ="'.$ORDER_detail->{'NO_REQ'}.'" AND ITEM="'.$ORDER->{'ProductID'}.'"');

$QTY_FALTANTE = $ORDER->{'CANTIDAD'} - $QTY_TOTAL;

  echo  "<tr ".$style_row." >
            <td>".$ORDER->{'ProductID'}."</td>
            <td>".$ORDER->{'DESCRIPCION'}."</td>
            <td>".$ORDER->{'UNIDAD'}."</td>
            <td>".$ORDER->{'PHASE'}."</td>
            <td class='numb'>".number_format($ORDER->{'CANTIDAD'},2,'.',',')."</td>
            <td class='numb'>".number_format($QTY_TOTAL,2,'.',',')."</td>
            <td class='numb'>".number_format($QTY_FALTANTE,2,'.',',')."</td>
            <td class='numb'>".number_format($qty_reciv,2,'.',',')."</td>
            <td>".$oc_list."</td>
            <td>".$status."</td>
        </tr>";

  

  }

echo '</tbody></table>
<div style="float:right;" class="col-md-2">
<a href="'.URL.'index.php?url=ges_requisiciones/req_print/'.$ORDER->{'NO_REQ'}.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
   <img  class="icon" src="img/Printer.png" />
  <span>Imprimir</span>
</a>
</div>';

if($ORDER_detail->{'st_closed'}=='0' && $status_gen !='FINALIZADO'){

if($this->model->rol_campo=='1'){ 
echo '<div style="float:right;" class="col-md-2">
<a href="'.URL.'index.php?url=ges_requisiciones/req_reception/'.$ORDER->{'NO_REQ'}.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
   <img  class="icon" src="img/Box Down.png" />
  <span>Registrar entradas</span>
</a>
</div>';
}

if($this->model->rol_compras=='1' && $status_gen !='FINALIZADO'){ 

echo '<div style="float:right;" class="col-md-2">
        <a title="Cerrar Rerquisición" data-toggle="modal" data-target="#CerrarModal" href="javascript:void(0)"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
          <img  class="icon" src="img/Stop.png" />
          <span>Cerrar Requisición</span>
        </a>
      </div>';

}

if($this->model->rol_compras=='1'){ 

  if($status_gen =='POR COTIZAR'){

    echo '<div style="float:right;" class="col-md-2">
    <a href="'.URL.'index.php?url=ges_requisiciones/set_req_quota/'.$ORDER->{'NO_REQ'}.'/'.$id_compania.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
       <img  class="icon" src="img/Search.png" />
      <span>Iniciar Cotización</span>
    </a>
    </div>';
  }



}


}



echo '</fieldset>';



echo '</fieldset>

<div class=" separador col-lg-12"></div>

  <div class="col-lg-12">
    <fieldset>
    <legend>Historial de Movimientos</legend>
        <table class="table table-striped table-bordered" cellspacing="0" >
          <thead> 
            <th>Registro</th>
            <th>Fecha</th>
            <th>Usuario</th>            
          </thead>
          <tbody>';






$CREACION = $this->model->Query("SELECT DATE, USER FROM REQ_HEADER WHERE  ID_compania='".$id_compania."' 
                                                                              AND NO_REQ='".$ORDER_detail->{'NO_REQ'}."'");
        foreach ($CREACION as $value ){

        $value = json_decode($value);

        $date = strtotime($value->{'DATE'});
        $date = date('m/d/Y',$date );

        echo '<tr><td>Creación de Requisición</td><td class="numb" >'.$date.'</td><td>'.$this->model->Get_User_Name($value->{'USER'}).'</td></tr>';

        }

$QUOTA  = $this->model->Query("SELECT DATE, USER FROM REQ_QUOTA WHERE  ID_compania='".$id_compania."' 
                                                                         AND NO_REQ='".$ORDER_detail->{'NO_REQ'}."'");
        foreach ($QUOTA  as $value ){

        $value = json_decode($value);

        $date = strtotime($value->{'DATE'});
        $date = date('m/d/Y',$date );

        echo '<tr><td>Inicio de cotización</td><td class="numb" >'.$date.'</td><td>'.$this->model->Get_User_Name($value->{'USER'}).'</td></tr>';

        }

$TEMP_LOG[] = '';
$i=1;

$PO =  $this->model->Query('SELECT LAST_CHANGE, PurchaseOrderNumber FROM PurOrdr_Header_Exp WHERE  PurOrdr_Header_Exp.CustomerSO =  "'.$ORDER_detail->{'NO_REQ'}.'"
                                                                                        AND PurOrdr_Header_Exp.ID_compania =  "'.$id_compania.'"
                                                                                        AND PurOrdr_Header_Exp.PurchaseOrderNumber <> ""
                                                                                        ORDER BY PurOrdr_Header_Exp.LAST_CHANGE asc');

        foreach ($PO  as $value) {

        $value = json_decode($value);

        $date = strtotime($value->{'LAST_CHANGE'});

        $TEMP_LOG[$date.';PO'.$i] = 'Creación de PO en Peachtree ('.$value->{'PurchaseOrderNumber'}.');Usuario Peachtree'; 

       $i+=1;
        }

$RECEP = $this->model->Query("SELECT DATE, USER, QTY, ITEM FROM REQ_RECEPT WHERE  ID_compania='".$id_compania."' 
                                                                                  AND NO_REQ='".$ORDER_detail->{'NO_REQ'}."'
                                                                                  ORDER BY DATE ASC");
$i=1;
        foreach ($RECEP as $value ){

        $value = json_decode($value);

        $date = strtotime($value->{'DATE'});

        $TEMP_LOG[$date.';rep'.$i] = 'Recepción en almacen Item: '.$value->{'ITEM'}.' / Cant.'.$value->{'QTY'}.';'.$this->model->Get_User_Name($value->{'USER'}); 

        $i+=1;
        }

    ksort($TEMP_LOG);

    foreach($TEMP_LOG as $key => $value ){

    list($date,) = explode(';', $key);

    list($msg,$user) = explode(';', $value);

    $date = date('m/d/Y',$date);

    if($msg!=''){
     
     echo '<tr><td>'.$msg.'</td><td class="numb" >'.$date.'</td><td>'.$user.'</td></tr>';

    }

    

    }


if($status_gen=='FINALIZADO'){

$FIN = $this->model->Query_value('REQ_RECEPT','DATE',"WHERE  ID_compania='".$id_compania."' 
                                                                                  AND NO_REQ='".$ORDER_detail->{'NO_REQ'}."'
                                                                                  ORDER BY DATE DESC LIMIT 1");

 $date = strtotime($FIN);
 $date = date('m/d/Y',$date );


 echo '<tr><td>Proceso FINALIZADO </td><td class="numb" >'.$date.'</td><td>SISTEMA ACIWEB</td></tr>';


}

if($status_gen=='CERRADA'){

 $CERRADO= $this->model->Query_value('REQ_HEADER','LAST_CHANGE',"WHERE  ID_compania='".$id_compania."' 
                                                                                  AND NO_REQ='".$ORDER_detail->{'NO_REQ'}."'");
 $date = strtotime($CERRADO);
 $date = date('m/d/Y',$date );

 $MOTIVO= $this->model->Query_value('REQ_HEADER','desc_closed',"WHERE  ID_compania='".$id_compania."' 
                                                                       AND NO_REQ='".$ORDER_detail->{'NO_REQ'}."'");
 
 echo '<tr><td>CERRADO POR : '. $MOTIVO.'</td><td class="numb" >'.$date.'</td><td>SISTEMA ACIWEB</td></tr>';

}


 ECHO   '</tbody>
        </table>';

  
 ECHO  '</fieldset>
         </div>  
          <div class="separador col-lg-12"></div>
          <div id="table2" class="col-lg-12"></div>
          ';

//MODA PARA CIERRE FORZOSO DE LA REQUISICION
$id = "'".$id."'";
$MODAL = '
<!-- Modal -->
<div id="CerrarModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 >Razón de Cierre</h3>
      </div>

      <div class="col-lg-12 modal-body">
      <!--ini Modal  body-->  
        <textarea class="textinput" rows="5" cols="70" id="req_reason_close" name="req_reason_close"></textarea>  
        <p class="help-block" >Indique la razón del cierre de la requisición</p>  
      <!--fin Modal  body-->
      </div>
      <div class="modal-footer">
        <button type="button" onclick="close_req('.$id.');" data-dismiss="modal" class="btn btn-primary" >Aceptar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>



';

ECHO $MODAL;

}

public function set_reason_close($id,$reason){

$this->model->verify_session();

$table = 'REQ_HEADER';
$columns = array( 'st_closed'   => 1, 
                  'desc_closed' => $reason);

$clause = ' NO_REQ = "'.$id.'"
            AND ID_compania =  "'.$this->model->id_compania.'"';


$this->model->update($table,$columns,$clause);

}
  



public function CheckError(){


  $CHK_ERROR =  $this->model->read_db_error();
  

  if ($CHK_ERROR!=''){ 

   die("<script>$(window).load(  
        function(){   
          $('#ErrorModal').modal('show');
          $('#ErrorMsg').html('".$CHK_ERROR."');
         }
       );</script>"); 

  }

}


}

?>