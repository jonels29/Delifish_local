<?PHP




class ges_reportes extends Controller
{


public function rep_reportes(){


 $res = $this->model->verify_session();

        if($res=='0'){
            

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/rep_reportes.php';
            require APP . 'view/_templates/footer.php';


        }
          

	
}


//Funcion para modificar ITEMS individualmente
public function modify_item($id_PL,$iditem,$descitem,$priceitem,$unit){

	$this->model->verify_session();


$Values = array('PRICE' => $priceitem,
				   'DESCRIPTION'=> $descitem, 
				   'UNIT'=> $unit);
$clause = 'IDPRICE="'.$id_PL.'" AND IDITEM="'.$iditem.'" AND ID_compania="'.$this->model->id_compania.'";';


$this->model->update('PRI_LIST_ITEM',$Values,$clause);
$this->CheckError();
echo '1';

}

//Funcion para Elminar un item especifico
public function delete_item($id_PL,$iditem){

	$this->model->verify_session();

	$sql ='DELETE FROM PRI_LIST_ITEM WHERE IDPRICE="'.$id_PL.'" AND IDITEM="'.$iditem.'" AND ID_compania="'.$this->model->id_compania.'";';

	$this->model->Query($sql);
	$this->CheckError();
	echo '1';

}



//agregar nuevo item a lista de precios
public function add_item(){

$this->model->verify_session();

list($priceId,$itemId,$descItem,$priceItem,$unitMes) = explode('@', $_REQUEST['Data']);

$priceId = str_replace(' ', '_', $priceId);

$Values = array( 
  'IDPRICE' => $priceId ,
  'IDITEM'  => $itemId ,
  'PRICE'=> $priceItem ,
  'DESCRIPTION'  => $descItem,
  'UNIT'=> $unitMes,
  'ID_compania' => $this->model->id_compania);

$this->model->insert('PRI_LIST_ITEM',$Values);

$this->CheckError();

echo '1';

}

public function CheckError(){


  $CHK_ERROR =  $this->model->read_db_error();
  

  if ($CHK_ERROR!=''){ 

   die("<script>$(window).load(  
        function(){   
          MSG_ERROR('".$CHK_ERROR."',0);
         }
       );</script>"); 

  }

}

//*****************************************************************
//SECCION DE REPORTES
public function get_report($type,$sort,$limit,$date1,$date2){

$this->model->verify_session();

switch ($type) {

//CASE 1

case "InvXVen":
$table = '';
$clause='';


$clause.= 'where  s.qty > "0"  and l.fecha_ven > 0 and p.id_compania="'.$this->model->id_compania.' "';

if($date1!=''){
   if($date2!=''){
      $clause.= ' and  l.fecha_ven >= "'.$date1.'%" and l.fecha_ven <= "'.$date2.'%" ';           
    }
   if($date2==''){ 
     $clause.= ' and  l.fecha_ven like "'.$date1.'%" ';
   }
}

 //ModifGPH 

 $table.= '<script type="text/javascript">

 jQuery(document).ready(function($)

  {

   var table = $("#table_reportInvXVen").dataTable({


      responsive: true,

      dom: "Bfrtip",

      bSort: false,

      bPaginate: false,

      select:true,

        buttons: [

          {

          extend: "excelHtml5",

          text: "Exportar",

          title: "Reporte_STOCK_X_VEN",

           
          exportOptions: {

                columns: ":visible",

                 format: {
                    header: function ( data ) {

                      var StrPos = data.indexOf("<div");

                        if (StrPos<=0){

                          
                          var ExpDataHeader = data;

                        }else{
                       
                          var ExpDataHeader = data.substr(0, StrPos); 

                        }
                       
                      return ExpDataHeader;
                      }
                    }
                 
                  }
                

          },

          {

          extend:  "colvis",

          text: "Seleccionar",

          columns: ":gt(0)"           

         },

         {

          extend: "colvisGroup",

          text: "Ninguno",

          show: [0],

          hide: [":gt(0)"]

          },

          {

            extend: "colvisGroup",

            text: "Todo",

            show: ["*"]

          }

          ]

   

    });


table.yadcf(
[
{column_number : 0},
{column_number : 1},
{column_number : 2,
 column_data_type: "html",
 html_data_type: "text" 
},
{column_number : 3}
],
{cumulative_filtering: true}); 

});


  </script>



  <table id="table_reportInvXVen" class="table table-striped responsive" cellspacing="0">

    <thead>

      <tr>
        <th width="15%">Almacen</th>

        <th width="15%">Ubicacion</th>

        <th width="20%">Producto</th>

        <th width="15%">Lote</th>

        <th width="15%">Descripcion</th>

        <th width="5%">Cantidad</th>

        <th width="10%">Vence</th>

        <th width="5%">Dias</th>     

      </tr>

    </thead>

    <tbody>';



      $Item = $this->model->get_InvXven($sort,$limit,$clause);



      foreach ($Item as $datos) {

       $Item = json_decode($datos);

       $fechaVen = $Item->{'Vencimiento'};



       $fechaVen = strtotime($fechaVen);

       $fechaVen =  date( 'Y-m-d', $fechaVen);



       $dife = strtotime($fechaVen)-strtotime('now');



       $intervalo=intval($dife/60/60/24);



 

$style='bgcolor="red"'; 



       if ($intervalo<16) {



          $style='style="background-color:#F5A9A9;"';



        }elseif ($intervalo>=16 && $intervalo<=31) {



          $style='style="background-color:#F7D358;"';

     

        }else{ $style=''; }



$table.='<tr '.$style.'  >
              <td  >'.$Item->{'Almacen'}.'</td>
              <td  >'.$Item->{'Ubicacion'}.'</td>
              <td  ><a href="'.URL.'index.php?url=ges_inventario/inv_info/'.$Item->{'Producto'}.'" >'.$Item->{'Producto'}.'</a></td></td>
              <td  >'.$Item->{'Lote'}.'</td>
              <td  >'.$Item->{'Descripcion'}.'</td>
              <td class="numb" >'.$Item->{'Cantidad'}.'</td>
              <td  >'.$fechaVen.'</td>
              <td  >'.$intervalo.'</td>

           </tr>';
 

      }

   

$table.= '</tbody></table>'; 



break;



case "InvXStk":

$table = '';
$clause.= 'where  s.qty > "0"  and p.id_compania="'.$this->model->id_compania.' "';



 $table.= '<script type="text/javascript">

 jQuery(document).ready(function($)

  {

   var table = $("#table_reportInvXStk").dataTable({

         rowReorder: {
            selector: "td:nth-child(2)"
        },

      responsive: true,


      dom: "Bfrtip",
      bSort: false,
      select:true,
      
      bPaginate:false,

        buttons: [

          {

          extend: "excelHtml5",

          text: "Exportar",

          title: "Reporte_INVENTARIO_x_STOCK",

           
          exportOptions: {

                columns: ":visible",

                 format: {
                    header: function ( data ) {

                      var StrPos = data.indexOf("<div");

                        if (StrPos<=0){

                          
                          var ExpDataHeader = data;

                        }else{
                       
                          var ExpDataHeader = data.substr(0, StrPos); 

                        }
                       
                      return ExpDataHeader;
                      }
                    }
                 
                  }
                

          },

          {

          extend:  "colvis",

          text: "Seleccionar",

          columns: ":gt(0)"           

         },

         {

          extend: "colvisGroup",

          text: "Ninguno",

          show: [0],

          hide: [":gt(0)"]

          },

          {

            extend: "colvisGroup",

            text: "Todo",

            show: ["*"]

          }

          ]

   

    });


table.yadcf(
[
{column_number : 0},
{column_number : 1},
{column_number : 2},
{column_number : 3,
column_data_type: "html",
 html_data_type: "text"}
],
{cumulative_filtering: true}); 

});


  </script>



  <table id="table_reportInvXStk" class="table table-striped responsive" cellspacing="0">

    <thead>

      <tr>
        <th width="15%">Almacen</th>

        <th width="15%">Ubicacion</th>
        <th width="15%">Lote</th>
        <th width="15%">Producto</th>
        <th width="15%">Descripcion</th>
        
        <th width="5%">Cantidad</th>
        <th width="10%">Costo Unit.</th>
        <th width="10%">Costo Total</th>     

      </tr>

    </thead>

    <tbody>';



      $Item = $this->model->get_InvXStk($sort,$limit,$clause);



      foreach ($Item as $datos) {

       $Item = json_decode($datos);

$total = $Item->{'Cantidad'}*$Item->{'LastUnitCost'};


$table.='<tr '.$style.'  >
              <td  >'.$Item->{'Almacen'}.'</td>
              <td  >'.$Item->{'Ubicacion'}.'</td>
              <td  >'.$Item->{'Lote'}.'</td>
              <td  ><a href="'.URL.'index.php?url=ges_inventario/inv_info/'.$Item->{'Producto'}.'" >'.$Item->{'Producto'}.'</a></td>
              <td  >'.$Item->{'Descripcion'}.'</td>
              <td class="numb" >'.$Item->{'Cantidad'}.'</td>
              <td class="numb" >'.$Item->{'LastUnitCost'}.'</td>
              <td class="numb" >'.$total.'</td>

           </tr>';
 

      }

   

$table.= '</tbody></table>'; 

        break;

case "ReqStat":

$this->model->verify_session();
$id_compania = $this->model->id_compania ;

$table = '';
$clause='';

$clause.= 'where REQ_HEADER.ID_compania="'.$id_compania.'" and REQ_DETAIL.ID_compania="'.$id_compania.'" ';

if($date1!=''){
   if($date2!=''){
      $clause.= ' and  DATE >= "'.$date1.'%" and DATE <= "'.$date2.'%" ';           
    }
   if($date2==''){ 
     $clause.= ' and  DATE like "'.$date1.'%" ';
   }
}




 $table.= '<script type="text/javascript">
 jQuery(document).ready(function($)
  {
   var table = $("#table_reportReqStat").dataTable({
      
      responsive: false,
      pageLength: 10,
      dom: "Bfrtip",
      bSort: false,
      select: false,
 
      info: false,
        buttons: [
          {
          extend: "excelHtml5",
          text: "Exportar",
          title: "Reporte_Estado_de_requisiciones",
           
          exportOptions: {
                columns: ":visible",
                 format: {
                    header: function ( data ) {
                      var StrPos = data.indexOf("<div");
                        if (StrPos<=0){
                          
                          var ExpDataHeader = data;
                        }else{
                       
                          var ExpDataHeader = data.substr(0, StrPos); 
                        }
                       
                      return ExpDataHeader;
                      }
                    }
                 
                  }
                
          },
          {
          extend:  "colvis",
          text: "Seleccionar",
          columns: ":gt(0)"           
         },
         {
          extend: "colvisGroup",
          text: "Ninguno",
          show: [0],
          hide: [":gt(0)"]
          },
          {
            extend: "colvisGroup",
            text: "Todo",
            show: ["*"]
          }
          ]
   
    });

table.yadcf(
[{column_number : 0,
 column_data_type: "html",
 html_data_type: "text" ,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 1,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 2,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 3,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 4,
 select_type: "select2",
 select_type_options: { width: "100%" }

}],
{cumulative_filtering: true, 
filter_reset_button_text: false}
);
});

  </script>
   <table id="table_reportReqStat" class="display table table-condensed table-striped table-bordered" >
   
    <thead>
      <tr>
        
        <th width="10%">No. Ref.</th>
        <th width="10%">Fecha </th>
        <th width="45%">Descripcion</th>
        <th width="25%">Solicitado por:</th>
        <th width="10%">Estado</th>
        
      </tr>
    </thead>
    <tbody>';


$Item = $this->model->get_req_to_report($sort,$limit,$clause);


foreach ($Item as $datos) {

  $Item = json_decode($datos);


  $name     = $this->model->Query_value('SAX_USER','name','Where ID="'.$Item->{'USER'}.'"');
  $lastname = $this->model->Query_value('SAX_USER','lastname','Where ID="'.$Item->{'USER'}.'"');

  $status='';

  $ID = '"'.$Item->{'NO_REQ'}.'"';
  $req = $Item->{'NO_REQ'};

  $URL = '"'.URL.'"';

 //obtengo estatus de la requisicion
$status = $this->req_status($req,$id_compania);
 

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


$table.="<tr  >
              
              <td width='10%' ><a href='#' onclick='javascript: show_req(".$URL.",".$ID.");'>".$Item->{'NO_REQ'}."</a></td>
              <td width='10%' >".date('m/d/Y',strtotime($Item->{'DATE'}))."</td>
              <td width='45%' >".$Item->{'NOTA'}.'</td>
              <td width="25%" >'.$name.' '.$lastname.'</td>
              <td width="10%" '.$style.' >'.$status.'</td>
          </tr>';
 

      }

   

$table.= '</tbody></table> <div class="separador col-lg-12"></div><div id="info"></div>'; 

break;//Reporte  de consignaciones

case "ConList":

$this->model->verify_session();
$table = '';
$clause='';

 
$clause.= 'WHERE CON_HEADER.ID_compania="'.$this->model->id_compania.'"
                 and CON_REG_TRAS.ID_compania="'.$this->model->id_compania.'"  
                 and reg_traslado.ID_compania="'.$this->model->id_compania.'" ';

if($date1!=''){
   if($date2!=''){
      $clause.= ' and  CON_HEADER.date >= "'.$date1.'%" and CON_HEADER.date <= "'.$date2.'%" ';           
    }
   if($date2==''){ 
     $clause.= ' and  CON_HEADER.date like "'.$date1.'%" ';
   }
}

 //ModifGPH 

 $table.= '<script type="text/javascript">

 jQuery(document).ready(function($)

  {

   var table = $("#table_reportCON").dataTable({

         rowReorder: {
            selector: "td:nth-child(2)"
        },

      responsive: true,


      dom: "Bfrtip",
      bSort: false,
      select:true,
      scrollY: "200px",
      scrollCollapse: true,

        buttons: [

          {

          extend: "excelHtml5",

          text: "Exportar",

          title: "Reporte_CONSIGNACIONES",

           
          exportOptions: {

                columns: ":visible",

                 format: {
                    header: function ( data ) {

                      var StrPos = data.indexOf("<div");

                        if (StrPos<=0){

                          
                          var ExpDataHeader = data;

                        }else{
                       
                          var ExpDataHeader = data.substr(0, StrPos); 

                        }
                       
                      return ExpDataHeader;
                      }
                    }
                 
                  }
                

          },

          {

          extend:  "colvis",

          text: "Seleccionar",

          columns: ":gt(0)"           

         },

         {

          extend: "colvisGroup",

          text: "Ninguno",

          show: [0],

          hide: [":gt(0)"]

          },

          {

            extend: "colvisGroup",

            text: "Todo",

            show: ["*"]

          }

          ]

   

    });


table.yadcf(
[{column_number : 0,
 column_data_type: "html",
 html_data_type: "text" ,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 1,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 2,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 3,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 4,
 select_type: "select2",
 select_type_options: { width: "100%" }

}],
{cumulative_filtering: true, 
filter_reset_button_text: false}
);

});


  </script>



  <table id="table_reportCON" class="table table-striped responsive" cellspacing="0">

    <thead>

      <tr>
        <th width="10%">Fecha</th>
        <th width="10%">No. Ref.</th>
        <th width="10%">Responsable</th> 
        <th width="10%">Producto</th>
        <th width="10%">Lote</th>
        <th width="15%">Descripcion</th>
        <th width="10%">Almacen Origen</th>
        <th width="5%">Ruta</th>
        <th width="10%">Almacen Destino</th>
        <th width="5%">Ruta</th>
        <th width="5%">Cantidad</th>
      </tr>
    </thead>
   <tbody>';



$Item = $this->model->get_con_to_report($sort,$limit,$clause);


foreach ($Item as $datos) {

$Item = json_decode($datos);

$name = $this->model->Query_value('SAX_USER','name','Where ID="'.$Item->{'USER'}.'"');
$lastname =  $this->model->Query_value('SAX_USER','lastname','Where ID="'.$Item->{'USER'}.'"');


$ID = '"'.$Item->{'REF'}.'"';

$URL = '"'.URL.'"';



//RUTA ORIGEN
$route_src = $this->model->Query_value('ubicaciones','etiqueta',' where id="'.$Item->{'route_ini'}.'"');
$stock_src = $this->model->Query_value('almacenes','name',' where id="'.$Item->{'id_almacen_ini'}.'"');

//RUTA DESTINO
$route_des = $this->model->Query_value('ubicaciones','etiqueta',' where id="'.$Item->{'route_des'}.'"');
$stock_des = $this->model->Query_value('almacenes','name',' where id="'.$Item->{'id_almacen_des'}.'"');

$table.='<tr  >
              <td  >'.$Item->{'date'}."</td>
              <td  ><a href='#' onclick='javascript: show_con(".$URL.",".$ID.");'>".$Item->{'REF'}."</td>
              <td  >".$name.' '.$lastname."</td>
              <td  >".$Item->{'ProductID'}.'</td>
              <td  >'.$Item->{'LOTE'}."</td>
              <td  >".$Item->{'NOTA'}.'</td>
              <td style="background-color:#F3F781;" >'.$stock_src.'</td>
              <td style="background-color:#F3F781;" >'.$route_src.'</td>
              <td style="background-color:#BCDFA8;" >'.$stock_des.'</td>
              <td style="background-color:#BCDFA8;" >'.$route_des.'</td>
              <td  >'.$Item->{'CANT'}.'</td>

          </tr>';
 

      } 

   

$table.= '</tbody></table> <div class="separador col-lg-12"></div><div id="info"></div>'; 



        break;
//FIN Reporte  de consignaciones

//Reporte  ORDENES DE COMPRA
case "PurOrd":

$this->model->verify_session();
$table = '';
$clause='';

 
$clause.= 'WHERE PurOrdr_Header_Exp.ID_compania="'.$this->model->id_compania.'" AND PurOrdr_Header_Exp.PurchaseOrderNumber <> ""'; 

if($date1!=''){
   if($date2!=''){
      $clause.= ' and  Date >= "'.$date1.'%" and Date <= "'.$date2.'%" ';           
    }
   if($date2==''){ 
     $clause.= ' and  Date like "'.$date1.'%" ';
   }
}



 $table.= '<script type="text/javascript">

 jQuery(document).ready(function($)

  {

   var table = $("#table_reportPurOrd").dataTable({
   rowReorder: {
            selector: "td:nth-child(2)"
        },

      responsive: true,
      pageLength: 10,
      dom: "Bfrtip",
      bSort: false,
      select: false,

      info: false,
        buttons: [

          {

          extend: "excelHtml5",

          text: "Exportar",

          title: "Reporte_Ordenes_de_Compras",

           
          exportOptions: {

                columns: ":visible",

                 format: {
                    header: function ( data ) {

                      var StrPos = data.indexOf("<div");

                        if (StrPos<=0){

                          
                          var ExpDataHeader = data;

                        }else{
                       
                          var ExpDataHeader = data.substr(0, StrPos); 

                        }
                       
                      return ExpDataHeader;
                      }
                    }
                 
                  }
                

          },

          {

          extend:  "colvis",

          text: "Seleccionar",

          columns: ":gt(0)"           

         },

         {

          extend: "colvisGroup",

          text: "Ninguno",

          show: [0],

          hide: [":gt(0)"]

          },

          {

            extend: "colvisGroup",

            text: "Todo",

            show: ["*"]

          }

          ]

   

    });


table.yadcf(
[{column_number : 0,
 column_data_type: "html",
 html_data_type: "text" ,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 1,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 2,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 3,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 4,
 select_type: "select2",
 select_type_options: { width: "100%" }

}],
{cumulative_filtering: true, 
filter_reset_button_text: false}
);


});


  </script>


  <table id="table_reportPurOrd"  class="display table  table-condensed table-striped table-bordered" >

    <thead>
      <tr>
        <th width="10%">ID Ord. Compra</th>
        <th width="10%">Fecha</th>
        <th width="10%">Proveedor</th> 
        <th width="10%">Total</th>
        <th width="10%">Fecha de Entrega</th>
        <th width="10%">Asignado a</th>
        <th width="40%">Nota</th>
      </tr>
    </thead>
   <tbody>';

 

    $oc = $this->model->get_OC($sort,$limit,$clause);

    foreach ($oc as $value) {
     $value = json_decode($value);

     $date = strtotime($value->{'Date'});

     $date = date('m/d/Y',$date);


    $PO_NO = trim ($value->{'PurchaseOrderNumber'});
    $PO_NO = "'".$PO_NO."'";

     $table .= " <tr>
               <td  >".'<a href="javascript:void(0)" onclick="get_OC('.$PO_NO.')"><strong>'.$value->{'PurchaseOrderNumber'}.'</strong></a></td>
               <td  >'.$date.'</td>
               <td  >'.$value->{'VendorName'}.'</td>
               <td  class="numb">'.number_format($value->{'Total'},2).'</td>
               <td >'.$value->{'WorkflowStatusName'}.'</td>
               <td >'.$value->{'WorkflowAssignee'}.'</td>
               <td >'.$value->{'WorkflowNote'}.'</td>
               </tr>';

     
    }

   
      $table .= '</tbody></table>

            <div class="separador col-lg-12"></div>
            <div class="col-lg-12" > 
            <div id="table2"></div>
            </div>';

        break;

//Reporte  FACTURAS DE COMPRA
case "PurFact":

$this->model->verify_session();
$table = '';
$clause='';

 
$clause.= 'WHERE Purchase_Header_Imp.ID_compania="'.$this->model->id_compania.'"'; 

if($date1!=''){
   if($date2!=''){
      $clause.= ' and  Date >= "'.$date1.'%" and Date <= "'.$date2.'%" ';           
    }
   if($date2==''){ 
     $clause.= ' and  Date like "'.$date1.'%" ';
   }
}


 $table.= '<script type="text/javascript">

 jQuery(document).ready(function($)

  {

   var table = $("#table_reportPurFact").dataTable({
   rowReorder: {
            selector: "td:nth-child(2)"
        },

      responsive: true,
      pageLength: 50,
      dom: "Bfrtip",
      bSort: false,
      
      scrollY: "200px",
      scrollCollapse: true,

        buttons: [

          {

          extend: "excelHtml5",

          text: "Exportar",

          title: "Reporte_Facturas_de_Compras",

           
          exportOptions: {

                columns: ":visible",

                 format: {
                    header: function ( data ) {

                      var StrPos = data.indexOf("<div");

                        if (StrPos<=0){

                          
                          var ExpDataHeader = data;

                        }else{
                       
                          var ExpDataHeader = data.substr(0, StrPos); 

                        }
                       
                      return ExpDataHeader;
                      }
                    }
                 
                  }
                

          },

          {

          extend:  "colvis",

          text: "Seleccionar",

          columns: ":gt(0)"           

         },

         {

          extend: "colvisGroup",

          text: "Ninguno",

          show: [0],

          hide: [":gt(0)"]

          },

          {

            extend: "colvisGroup",

            text: "Todo",

            show: ["*"]

          }

          ]

   

    });

table.yadcf(
[{column_number : 0,
 column_data_type: "html",
 html_data_type: "text" ,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 1,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 2,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 3,
 select_type: "select2",
 select_type_options: { width: "100%" }

}],
{cumulative_filtering: true, 
filter_reset_button_text: false}
);

});


  </script>



  <table id="table_reportPurFact" class="display  table table-condensed table-striped table-bordered" cellspacing="0" >

    <thead>
      <tr>
        <th width="10%">Ref. </th>
        <th width="20%">Proveedor</th>
        <th width="10%">No. Factura</th>
        <th width="10%">Fecha</th>
        <th width="10%">Total</th>
        <th width="30%">Nota</th>
        <th width="10%">Estado</th>
      </tr>
    </thead>
   <tbody>';

  

    $fact = $this->model->Get_fact_header($sort,$limit,$clause);

    foreach ($fact as $value) {
     
    $value = json_decode($value);


    if($value->{'Error'}=='1') { 

     $status= "Error : ".$value->{'ErrorPT'};
     $style="style='color:red;'"; 

    } else{

      if($value->{'Enviado'}!="1"){

        $style="style='color:orange;'"; 
        $status='Por Procesar'; 

       }else{ 

          $status= "Sincronizado el: ".$value->{'Export_date'};
          $style="style='color:green;'";

         }   

      }

     

     $date = strtotime($value->{'Date'});
     $date = date('Y-m-d',$date);

     $REF =  str_pad($value->{'TransactionID'}, 9 ,"0",STR_PAD_LEFT);

     $ID = "'".$value->{'TransactionID'}."'";
     $URL = "'".URL."'";

     $table .= " <tr>
               <td >".'<a href="#"  onclick="javascript: show_fact('.$URL.','.$ID.');"  ><strong>'.$REF.'</strong></a>'.'</td>
               <td >'.$value->{'VendorName'}.'</td>
               <td >'.$value->{'PurchaseNumber'}.'</td>
               <td class="numb">'.date('m/d/Y',strtotime($value->{'Date'})).'</td>
               <td class="numb">'.number_format($value->{'Net_due'},2,'.',',').'</td>
               <td >'.$value->{'nota'}.'</td>
               <td '. $style.'>'.$status.'</td>
               </tr>';

     
    }

   
    $table .= '</tbody></table><div class="separador col-lg-12"></div><div id="info"></div>';

    break;
     
    //FIN Reporte  FACTURAS DE COMPRA

     //Reporte  LISTA DE PRECIOS
case "PriceList":

$this->model->verify_session();
$table = '';
$clause='';

 
$clause.= 'WHERE PRI_LIST_ID.ID_compania="'.$this->model->id_compania.'" GROUP BY IDPRICE ASC'; 

if($date1!=''){
   if($date2!=''){
      $clause.= ' and  Date >= "'.$date1.'%" and Date <= "'.$date2.'%" ';           
    }
   if($date2==''){ 
     $clause.= ' and  Date like "'.$date1.'%" ';
   }
}



 $table.= '<script type="text/javascript">

 jQuery(document).ready(function($)

  {

   var table = $("#table_PriceList").dataTable({
   rowReorder: {
            selector: "td:nth-child(2)"
        },

      responsive: false,
      pageLength: 100,
      dom: "Bfrtip",
      bSort: false,
      select:true,
      scrollY: "500px",
      scrollCollapse: true,

        buttons: [

          {

          extend: "excelHtml5",

          text: "Exportar",

          title: "Reporte_Lista_Precios",

           
          exportOptions: {

                columns: ":visible",

                 format: {
                    header: function ( data ) {

                      var StrPos = data.indexOf("<div");

                        if (StrPos<=0){

                          
                          var ExpDataHeader = data;

                        }else{
                       
                          var ExpDataHeader = data.substr(0, StrPos); 

                        }
                       
                      return ExpDataHeader;
                      }
                    }
                 
                  }
                

          },

          {

          extend:  "colvis",

          text: "Seleccionar",

          columns: ":gt(0)"           

         },

         {

          extend: "colvisGroup",

          text: "Ninguno",

          show: [0],

          hide: [":gt(0)"]

          },

          {

            extend: "colvisGroup",

            text: "Todo",

            show: ["*"]

          }

          ]

   

    });


table.yadcf(
[{column_number : 0,
 column_data_type: "html",
 html_data_type: "text" ,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 1,
 select_type: "select2",
 select_type_options: { width: "100%" }

}],
{cumulative_filtering: true, 
filter_reset_button_text: false}
);

});

</script>

<table id="table_PriceList" class="table table-striped responsive table-bordered" cellspacing="0" >
    <thead>
      <tr>
        <th width="20%">Id. Lista de Precios</th>
        <th width="10%">Fecha</th>
        <th width="30%">Descripcion</th> 
        <th width="5%"></th>
      </tr>
    </thead>
   <tbody>';

  

    $PL = $this->model->get_Price_list($sort,$limit,$clause);

    foreach ($PL as $value) {

     $value = json_decode($value);

     $date1 = strtotime($value->{'LAST_CHANGE'});
     //$date1 = $date;
     $date = date('m/d/Y',$date1);
     //$date1 = date('mdY',$date1);

    $PL_ID = trim ($value->{'IDPRICE'});
    $PL_ID = "'".$PL_ID."'";
    $PL_Desc = "'".$value->{'DESCRIPTION'}."'";
    $date1 = "'".$date1."'";

     $table .= ' <tr>
         <td><a href="javascript:void(0)" onclick="get_PL('.$PL_ID.')"><strong>'.$value->{'IDPRICE'}.'</strong></a></td>
         <td class="numb">'.$date.'</td>
         <td>'.$value->{'DESCRIPTION'}.'</td>
         <td><a  href="javascript:void(0)" onclick="del_PL('.$PL_ID.')"><input type="button" id="modal_button" name="modal_button"  class="btn btn-danger btn-sm btn-icon icon-left" value="Eliminar"></td>
         </tr>';

     
    }

   
      $table .= '</tbody></table>

            <div class="separador col-lg-12"></div>
            <div class="col-lg-12" > 
            <div id="table3"></div>
            </div>';

        break;

    //FIN Reporte  LISTA DE PRECIOS

   /*  case "Compras":

         $query = ''; 

        break;*/



}


echo $table;

}

//TRAE Y MUESTRA EL DETALLE DE UNA LISTA DE PRECIOS

public function get_PL_details($id_PL){

$this->model->verify_session();

$date = $this->model->Query_value('PRI_LIST_ID','LAST_CHANGE',"where IDPRICE='".$id_PL."' and ID_compania='".$this->model->id_compania."';");
$Desc = $this->model->Query_value('PRI_LIST_ID','DESCRIPTION',"where IDPRICE='".$id_PL."' and ID_compania='".$this->model->id_compania."';");

$PL = $this->model->get_items_by_PL($id_PL);

$date = strtotime($date);
$date = date('m/d/Y',$date);

$table.= '<fieldset>
          <legend>Detalle de Lista de Precio</legend>
          <table   class="display nowrap table table-striped table-bordered " cellspacing="0"  >
    <tbody>';
  
    $value = json_decode($PL[0]);


    $table.= "<tr><th style='text-align:left;' width='25%'>ID Lista de Precio.</th><td >".$id_PL.'</td></tr>
              <tr><th style="text-align:left;" width="25%">Fecha de Modificacion</th><td >'.$date.'</td></tr>
              <tr><th style="text-align:left;" width="30%">Descripcion</th><td >'.$Desc.'</td></tr>';
    $table.= '</tbody></table>
    
    <div class="separador col-lg-12"></div>
    <div class="col-lg-10"></div>
    <div class="col-lg-2">';
    
    $PL_id_2 = '"PL_id_2"';
    $id_PL2 = '"'.$id_PL.'"';

    $table.="<a  href='javascript:void(0)' ><input type='button' onclick='javascript: document.getElementById(".$PL_id_2.").value =".$id_PL2.";'  data-toggle='modal' data-target='#modal_additem' class='btn btn-primary btn-sm btn-icon icon-left' value='Agregar Item' />
    </div>";

    $table.= '<div class="separador col-lg-12"></div>

    <table id="Items" class="table table-striped responsive table-bordered" cellspacing="0"  >
    <thead>
      <tr>
        <th width="10%">Codigo Item</th>
        <th width="30%">Descripcion</th>
        <th width="10%">Precio</th>
        <th width="10%">Unidad</th>
        <th width="10%"></th>
      </tr>
    </thead>
 
 <tbody >';
 
  foreach ($PL as $value) {

    $value = json_decode($value);

          $id_Price_List = "'".$id_PL."'";
          $id_item = "'".$value->{'IDITEM'}."'";
          $id_unit = "'".$value->{'UNIT'}."'";
          $id_desc = "'".$value->{'DESCRIPTION'}."'";


          $table.= '<tr>
            <td width="10%"><a title="modificar Item" data-toggle="modal" data-target="#myModal" href="javascript:void(0)" onclick="modal_PL_item('.$id_Price_List.','.$id_item.','.$id_unit.','.$id_desc.');"><STRONG>'.$value->{'IDITEM'}.'</STRONG></a></td>
            <td width="30%">'.$value->{'DESCRIPTION'}.'</td>
            <td width="10%" class="numb">'.number_format($value->{'PRICE'},4, '.', ',').'</td>
            <td width="10%">'.$value->{'UNIT'}.'</td>
            <td width="10%"><CENTER><a  href="javascript:void(0)" onclick="del_PL_item('.$id_Price_List.','.$id_item.')"><input type="button" id="modal_button" name="modal_button"  class="btn btn-danger btn-sm btn-icon icon-left" value="Eliminar"></CENTER></a></td>
                   </tr>';

    }

     
  $table.='</tbody></table></fieldset>';


    echo $table;

}


//Elimina lista de precio

public function del_PL_detail($id_PL){

$this->model->verify_session();


$clause = 'WHERE IDPRICE = "'.$id_PL.'" and ID_compania="'.$this->model->id_compania.'"';
$table_PL_ITEM = 'PRI_LIST_ITEM';
$table_PL_ID = 'PRI_LIST_ID';

$this->model->delete($table_PL_ITEM,$clause);
$this->model->delete($table_PL_ID,$clause);


}



//TRAE Y MUESTRA EL DETALLE DE UNA PO

public function get_PO_details($id){

$this->model->verify_session();
$oc = $this->model->get_items_by_OC($id);

$table.= '<table   class="table table-striped table-bordered" cellspacing="0"  >
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

    <table id="Items" class="table table-striped table-bordered" cellspacing="0"  >
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
            <td >'.$value->{'Quantity'}.'</td>
            <td >'.$value->{'Unit_Price'}.'</td>
            <td >'.$value->{'NetLine'}.'</td>
          </tr>';

    }

     
  $table.='</tbody></table>';


    echo $table;




}

//estatus requisiciones

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




































//Corchete de la clase
}

?>
