<?php



class bridge_query extends Controller
{



public function SESSION(){

  $this->model->verify_session();


}

public function get_items_by_invoice($invoice){


$query = 'SELECT
  Purchase_Header_Exp.PurchaseID, 
  Purchase_Header_Exp.PurchaseNumber, 
  Purchase_Header_Exp.VendorID,
  Products_Exp.ProductID,
  Products_Exp.Description,
  Products_Exp.UnitMeasure,
  Purchase_Detail_Exp.Quantity
from Purchase_Header_Exp
inner join  Purchase_Detail_Exp on Purchase_Header_Exp.PurchaseID = Purchase_Detail_Exp.PurchaseID
inner join  Products_Exp on Products_Exp.ProductID = Purchase_Detail_Exp.Item_id
 where isActive="1" and Purchase_Header_Exp.PurchaseID="'.$invoice.'"';


$lote_ven = $this->model->Query($query);

foreach ($lote_ven as $value) {

  $PROD_FACT= json_decode($value);
  
                      
  echo '<tr>
          <td >'.$PROD_FACT->{'ProductID'}.'</td>
          <td >'.$PROD_FACT->{'Description'}.'</td>
          <td class="numb" >'.number_format($PROD_FACT->{'Quantity'},0, ',', '.').'</td>
          <td ><a href="'.URL.'index.php?url=ges_inventario/inv_info/'.$PROD_FACT->{'ProductID'}.'" >Ver <i class="fa  fa-search"></i></a></td>
        </tr>';

  }


}

public function get_ProductsInfo($itemid){
$this->SESSION();

$sql= 'SELECT 
Products_Exp.ProductID,
Products_Exp.Description,
Products_Exp.UnitMeasure,
Products_Exp.QtyOnHand,
Products_Exp.Price1,
Products_Exp.LastUnitCost,
Products_Exp.TaxType
FROM Products_Exp 
WHERE 
Products_Exp.IsActive="1" 
AND  Products_Exp.id_compania="'.$this->model->id_compania.'" 
AND  Products_Exp.ProductID ="'.$itemid.'" ';

$res = $this->model->Query($sql);


foreach ($res as  $value) {
    echo $value;
}

}


public function get_items_defaultstock_qty($item){
$this->SESSION();
$lote = $item.'0000';

$prod = $this->model->Query_value('status_location','qty','inner join Products_Exp on status_location.id_product = Products_Exp.ProductID
where stock="1" and route="1" and  lote="'.$lote.'" and Products_Exp.ID_compania ="'.$this->model->id_compania.'"');

echo $prod;
}

public function get_ProductsPrice($itemid,$listid){
$this->SESSION();


$price = $this->model->Query_value('PRI_LIST_ITEM','PRICE','WHERE IDPRICE="'.$listid.'" AND IDITEM="'.$itemid.'" AND ID_compania ="'.$this->model->id_compania.'"');

echo $price;
 

}

public function get_lote_selectlist($itemid){
$this->SESSION();


$query_lotes = 'SELECT lote 
FROM status_location 
where id_product="'.$itemid.'" and stock="1" and route="1" and onoff="1" and qty > 0 and ID_compania ="'.$this->model->id_compania.'"';

$query_almacen= 'SELECT almacenes.id, almacenes.name  
FROM almacenes 
inner join ubicaciones on ubicaciones.id_almacen = almacenes.id
where almacenes.onoff="1" GROUP BY almacenes.name ';

$url = "'".URL."'";
$itemid =  "'".$itemid."'";

$table='<fieldset><legend><h4>Agregar lote a nueva ubicacion</h4></legend>
<table class="dataTable">
<tr>
<th>No. Lote</th>
<th>Cantidad</th>
<th>Almacen</th>
<th>Ruta</th></tr>
<tr><td><select id="no_lote" class="form-control col-xs-4" onchange="set_qty('.$url.','.$itemid.',this.value);" >
<option selected disabled>Seleccionar lote</option>';
$res = $this->model->Query($query_lotes);

foreach ($res as $value) {
  $value = json_decode($value);
  $table.='<option value="'.$value->{'lote'}.'">'.$value->{'lote'}.'</option>';
  }

$table.='</select></td>
<td><input class="form-control col-xs-4" type="number" id="qty_new" name="qty_new" min="1" max="" required readonly="true"/></td>
<td><select onchange="routes(this.value);" class="form-control col-xs-4" id="almacen" name="almacen" readonly="true">
<option selected disabled>Seleccionar Almacen</option>';
$res = $this->model->Query($query_almacen);

foreach ($res as $value) {
  $value = json_decode($value);
  $table.='<option value="'.$value->{'id'}.'">'.$value->{'name'}.'</option>';
  }

$table.='</select></td>
<td><select class="form-control col-xs-4" id="routes" name="routes" readonly="true"></select></td>
<td><button onclick="add_location_route();"  class="btn btn-primary  btn-block text-left" type="submit" >Ubicar</button></td>
<td><button class="btn btn-warning btn-block text-left" onclick="javascript: location.reload();" >cancelar</button></td></tr>
</table>
</fieldset>';

echo $table;
}


public function get_lote_qty($lote,$itemid){


$this->SESSION();


$res = $this->model->Query_value('status_location','Floor(qty)','where stock="1" and route="1" and lote="'.$lote.'" and id_product="'.$itemid.'" and ID_compania ="'.$this->model->id_compania.'";');

echo $res;
return $res;
}


public function get_any_lote_qty($lote,$itemid,$ruta){


$this->SESSION();


$res = $this->model->Query_value('status_location','Floor(qty)','where route="'.$ruta.'" and lote="'.$lote.'" and id_product="'.$itemid.'" and ID_compania ="'.$this->model->id_compania.'";');

//echo $res;
return $res;
}

public function  get_almacen_selectlist(){



$query_almacen= 'SELECT almacenes.id, almacenes.name  
FROM almacenes 
inner join ubicaciones on ubicaciones.id_almacen = almacenes.id
where almacenes.onoff="1" GROUP BY almacenes.name ';

$select = '<option selected disabled>Seleccionar Almacen</option>';
$res = $this->model->Query($query_almacen);

foreach ($res as $value) {
  $value = json_decode($value);
  $select.='<option value="'.$value->{'id'}.'">'.$value->{'name'}.'</option>';
  }

$select.='</select>';
echo $select;

return $select;
}


public function  get_routes_by_almacenid($almacen){
$query= 'SELECT id , etiqueta  FROM ubicaciones where id_almacen="'.$almacen.'" and onoff="1"';

$res = $this->model->Query($query);

echo '<option selected disabled>Seleccionar Ruta</option>';
foreach ($res as $value) {
  $value = json_decode($value);
  echo '<option value="'.$value->{'id'}.'">'.$value->{'etiqueta'}.'</option>';
  }
}


public function set_lote_location($ruta_selected,$almacen_selected,$item_id,$lote,$qty){
$this->SESSION();

//UBICO LA CANTIDAD ACTUAL EN STATUS_LOCATION
$CURRENT_QTY = $this->get_lote_qty($lote,$item_id);



//ACTUALIZO LA CANTIDAD EN LA UBICCION DEFAULT
$QTY_TO_SET = $CURRENT_QTY - $qty;

$query= 'UPDATE status_location SET qty="'.$QTY_TO_SET.'" where id_product="'.$item_id.'" and stock="1" and route="1" and onoff="1" and ID_compania ="'.$this->model->id_compania.'"';

$res = $this->model->Query($query);



$id_verify = $this->model->Query_value('status_location','id','where lote="'.$lote.'" and stock="'.$almacen_selected.'" and route="'.$ruta_selected.'" and ID_compania ="'.$this->model->id_compania.'";');

if(!$id_verify){ 

//agregar nueva location
$val_to_insert = array(
  'lote' => $lote, 
  'stock' => $almacen_selected, 
  'qty' => $qty, 
  'route' => $ruta_selected,
  'id_product' => $item_id,
  'ID_compania' => $this->model->id_compania);

$res = $this->model->insert('status_location',$val_to_insert);


//registro de traslado Default a una nueva ubicacion
$value_traslate = array(
  'id_almacen_ini' => '1',
  'route_ini' => '1' ,
  'id_almacen_des' => $almacen_selected,
  'route_des' => $ruta_selected,
  'lote' => $lote,
  'qty' => $qty ,
  'ProductID' => $item_id ,
  'id_user' => $this->model->active_user_id
   );

$this->model->insert('reg_traslado',$value_traslate);


}else{

$old_qty = $this->model->Query_value('status_location','qty','where id="'.$id_verify.'";');

$qty_to_up = $old_qty  + $qty;

$query= 'UPDATE status_location SET qty="'.$qty_to_up.'"  where id="'.$id_verify.'";';
$this->model->Query($query);


//registro de traslado Default a una nueva ubicacion
$id_route_reg = $this->model->Query_value('status_location','route','where id="'.$id_verify.'";');
$id_alma_reg = $this->model->Query_value('ubicaciones','id_almacen','where id="'.$id_route_reg.'";');


$value_traslate = array(
  'id_almacen_ini' => $id_alma_reg,
  'route_ini' => $id_route_reg,
  'id_almacen_des' => $almacen_selected,
  'route_des' => $ruta_selected,
  'lote' => $lote,
  'qty' => $qty ,
  'ProductID' => $item_id ,
  'id_user' => $this->model->active_user_id
   );

$this->model->insert('reg_traslado',$value_traslate);



}

//$this->clear_lotacion_register();

}


public function update_lote_location($OrigenROUTE,$OrigenALMACEN,$status_location_id,$ruta,$almacen,$lote,$qty){

$this->SESSION();

//ID DE UBICACIONES DE ORIGEN
$ruta_src = $this->model->Query_value('ubicaciones','id',' where etiqueta="'.$OrigenROUTE.'"');
$almacen_src = $this->model->Query_value('ubicaciones','id_almacen',' where id="'.$ruta_src.'"');

//ID DEL USER QUE REALIZA EL TRASLADO
$id_user_active = $this->model->active_user_id;

//QTY ACTUAL
$CURRENT_QTY = $this->model->Query_value('status_location','qty','where id="'.$status_location_id.'";');

$NEW_QTY = $CURRENT_QTY - $qty;

//ACTUALIZA LA CANTIDAD RESTANTE EN LA UBICACION ACTUAL
$query= 'UPDATE status_location SET qty="'.$NEW_QTY.'" where id="'.$status_location_id.'";';
$this->model->Query($query);


//ID DEL PRODUCTO
$ProductID = $this->model->Query_value('status_location','id_product','where id="'.$status_location_id.'";');


  //VERIFICO SI EXISTE UN LOTE IGUAL EN LA UBICACION DESTINO
  $id_verify = $this->model->Query_value('status_location','id','where lote="'.$lote.'" and stock="'.$almacen.'" and route="'.$ruta.'" and  ID_compania ="'.$this->model->id_compania.'"');

  if(!$id_verify){ //SI NO EXISTE CREO LA NUEVA UBICACION PARA EL LOTE 

  //agregar nueva location
  $val_to_insert = array(
    'lote' => $lote, 
    'stock' => $almacen, 
    'qty' => $qty, 
    'route' => $ruta,
    'id_product' => $ProductID,
    'ID_compania' => $this->model->id_compania);

  $res = $this->model->insert('status_location',$val_to_insert);



  //registro de traslado Default a una nueva ubicacion
  $value_traslate = array(
    'id_almacen_ini' => $almacen_src,
    'route_ini' => $ruta_src,
    'id_almacen_des' => $almacen,
    'route_des' => $ruta,
    'lote' => $lote,
    'qty' => $qty ,
    'ProductID' => $ProductID,
    'id_user' => $this->model->active_user_id,
    'ID_compania' => $this->model->id_compania
     );

  $this->model->insert('reg_traslado',$value_traslate);


}else{//SI EXISTE LE SUMO LA NUEVA CANTIDAD

    //consulta qty actual en lla ubicacion destino apra ese lote
    $old_qty = $this->model->Query_value('status_location','qty','where id="'.$id_verify.'";');

    $qty_to_up = $old_qty  + $qty; //se suma

    //se actualiza
    $query= 'UPDATE status_location SET qty="'.$qty_to_up.'"  where id="'.$id_verify.'";';
    $this->model->Query($query);


    //registro de traslado
    $value_traslate = array(
      'id_almacen_ini' => $almacen_src,
      'route_ini' => $ruta_src ,
      'id_almacen_des' => $almacen,
      'route_des' => $ruta,
      'lote' => $lote,
      'qty' => $qty ,
      'ProductID' => $ProductID,
      'id_user' => $this->model->active_user_id,
      'ID_compania' => $this->model->id_compania
       );


    $this->model->insert('reg_traslado',$value_traslate);

    }


}

/*

public function clear_lotacion_register(){

$query= 'select status_location.id from status_location 
inner join sale_pendding on sale_pendding.status_location_id = status_location.id 
where sale_pendding.status_pendding ="0"';

$id = $this->model->Query($query);

foreach ($id as  $value) {
  $value  = json_decode($value);
 $id =  $value ->{'id'};
}

$query = 'delete from status_location where id="'.$id.'";';
$this->model->Query($query);

}*/

//modificacion rey 23/11
public function get_items_location_by_lote($itemid){


$this->SESSION();


$query = 'SELECT 
lote ,
id as id_location,
(select name from almacenes where almacenes.id=status_location.stock) as stock,
(select etiqueta from ubicaciones where ubicaciones.id=status_location.route) as route,
(select qty from Prod_Lotes where Prod_Lotes.no_lote=status_location.lote) as qty,
(select fecha_ven from Prod_Lotes where Prod_Lotes.no_lote=status_location.lote) as venc,
(select Price1 from Products_Exp where Products_Exp.ProductID=status_location.id_product) as Price,
(select Description from Products_Exp where Products_Exp.ProductID=status_location.id_product) as Descr,
(select UnitMeasure from Products_Exp where Products_Exp.ProductID=status_location.id_product) as UnitMeasure
FROM status_location 
where id_product="'.$itemid.'" and status_location.ID_compania ="'.$this->model->id_compania.'"';

$res = $this->model->Query($query);


echo '<script type="text/javascript">
    $("#modal_table").dataTable({
      
      pageLength: 5
              
            });
</script>

<table id="modal_table" widht="100%" class="table table-striped"  cellspacing="0">
            <thead>
              <tr>  
                
                <th width="20%" >Almacen</th>
                <th width="20%" >Ruta</th>
                <th width="25%" >Lote</th>
                <th width="5%"  >Stock</th>
                <th width="10%" >Cant.</th>
                <th width="15%" >Venc.</th>
                <th width="5%" >Taxable</th>
              </tr>
            </thead><tbody>';


foreach ($res as $key => $value) {

$value = json_decode($value);

$PRICE =  $value->{'Price'};
$UNI =  $value->{'UnitMeasure'};
$NAME =  $value->{'Descr'};
$Lote =  $value->{'lote'};
$FECHA_VEN =  $value->{'venc'};
$STOCK =  $value->{'stock'};
$TAG_LOCATION =  $value->{'route'};
$ID_LOCATION = $value->{'id_location'};
$QTY = number_format($value->{'qty'},5, '.',',');



$ID_PRO="'".$itemid."'";
$PRICE="'".$PRICE."'";
$UNI_PRO="'".$UNI."'";
$NAME_PRO="'".$NAME."'";
$LOTE= "'".$Lote."'" ;
$VENC=  "'".str_replace('/','-', $FECHA_VEN)."'";
$RUTA= "'".$TAG_LOCATION."'";         
$QTYMAX =    "'".$value->{'qty'}."'"; 

$chk_val = $this->model->Query_value('Products_Exp','TaxType','WHERE ProductID="'.$itemid.'"');

if($chk_val=='1'){

$checked='checked disabled';
$chk_val='checked';

}else{

 $checked='disable'; 
 $chk_val='';
}

if($FECHA_VEN!='0000-00-00 00:00:00' and $FECHA_VEN!=null){
       $FECHA_VEN = date('Y-m-d',strtotime($FECHA_VEN));
     }else{
       $FECHA_VEN = '';
  }


if($QTY>=1){

  //<a href="javascript:void(0)" onclick="javascript: agregar_pro_sale_sale('.$ID_PRO.','.$NAME_PRO.','.$PRICE.','.$LOTE.','.$VENC.','.$RUTA.','.$QTYMAX.','.$UNI_PRO.');" ><i style="color:green" class="fa fa-plus"></i></a>

  $id_validar = "'".$Lote.$TAG_LOCATION."qty'";

//<td width="5%"><input type="checkbox" id="'.$Lote.$TAG_LOCATION.'" /></td>
  
 echo '<tr>
                  <td width="20%">'.$STOCK.'</td>
                  <td width="20%">'.$TAG_LOCATION.'</td>
                  <td width="25%">'.$Lote.'</td>
                  <td width="5%" class="numb" >'.$QTY.'</td>
                  <td width="10%"><input type="number" id="'.$Lote.$TAG_LOCATION.'qty" min="0.00001" max="'.$QTY.'" value=""  /></td>
                  <td width="15%" >'.$FECHA_VEN.'</td>
                  <td width="5%" ><input type="checkbox"  id="'.$Lote.$TAG_LOCATION.'taxable"  value="'.$chk_val.'" '.$checked.' />
                  </td>
                 </tr>';

}           


}

echo '</tbody></table>';

}
//modificacion rey 23/11
public function get_Cust_info($custid){

$query = 'SELECT * FROM Customers_Exp WHERE ID="'.$custid.'";';

$res = $this->model->Query($query);

echo $res[0];

}

public function get_Cust_info_int($custid){

$query = 'SELECT * FROM Customers_Exp WHERE ID="'.$custid.'";';

$res = $this->model->Query($query);

return $res[0];

}

public function GET_SO_NO(){
$this->SESSION();

echo $this->model->Get_SO_No();


}



public function set_sales_order_header($CustomerID,$Subtotal,$TaxID,$Net_due,$user,$nopo,$pago,$licitacion,$observaciones,$entrega,$ordertax,$salesrep){
$this->SESSION();

$id_compania = $this->model->id_compania;

$SalesOrderNumber = $this->model->Get_SO_No();


$custinfo = $this->get_Cust_info_int($CustomerID);
$custinfo = json_decode($custinfo);


$values = array(
'ID_compania'=>$this->model->id_compania,
'SalesOrderNumber'=> $SalesOrderNumber,
'CustomerID'=>  $this->model->Query_value('Customers_Exp','CustomerID','Where ID="'.$CustomerID.'" AND id_compania="'.$id_compania.'" ;'),
'CustomerName'=>$this->model->Query_value('Customers_Exp','Customer_Bill_Name','Where ID="'.$CustomerID.'" AND id_compania="'.$id_compania.'" ;'),
'Subtotal'=>$Subtotal,
'TaxID'=>$TaxID,
'OrderTax' => $ordertax,
'Net_due'=>$Net_due,
'user'=>$this->model->active_user_id,
'date'=>date("Y-m-d"),
'saletax'=>'0',
'CustomerPO' => $nopo,
'tipo_licitacion' => $licitacion,
'entrega' => $entrega,
'termino_pago' => $pago,
'observaciones' => $observaciones,
'ShipToName' => $CustomerID.'-'.$custinfo->{'Customer_Bill_Name'},
'ShipToAddressLine1' => $custinfo->{'AddressLine1'},
'ShipToAddressLine2' => $custinfo->{'AddressLine2'},
'ShipToCity' => $custinfo->{'City'},
'ShipToState' => $custinfo->{'State'},
'ShipToZip' => $custinfo->{'Zip'},
'ShipToCountry' => $custinfo->{'Country'});

$this->model->insert('SalesOrder_Header_Imp',$values);


echo $SalesOrderNumber ;

}







public function set_sales_order_detail_new($SalesOrderNumber){

$this->SESSION();

$id_compania= $this->model->id_compania;
$id_user_active= $this->model->active_user_id ;

$data = json_decode($_GET['Data']);

foreach ($data as $key => $value) {

if($value){

list($remarks,$UnitMeasure,$itemid,$unit_price,$qty,$Price ) = explode('@', $value );

$no_cover_qty = $qty;
$no_cover_uni = $UnitMeasure;
$no_cover_pri = $unit_price;

$custid  = $this->model->Query_value('SalesOrder_Header_Imp','CustomerID','WHERE SalesOrderNumber="'.$SalesOrderNumber.'" and ID_compania="'.$id_compania.'"');
$UNIT_TO_CONVERT = $this->model->Query_value('Customers_Exp','Custom_field5','WHERE CustomerID="'.$custid.'" and id_compania="'.$id_compania.'"');
$factor  = $this->model->Query_value('UNIT_MES_CONVRT','FACTOR','WHERE UNIT="'.$UnitMeasure.'" and UNIT_TO_CONVERT="'.$UNIT_TO_CONVERT.'" and ID_compania="'.$id_compania.'"');


  if($factor!=''){
   
    $unit_price =   $unit_price / $factor;
    $qty = $qty * $factor;
    $Price =  $unit_price * $qty;

    $UnitMeasure     = $this->model->Query_value('UNIT_MES_CONVRT','UNIT_NAME','WHERE UNIT="'.$UnitMeasure.'" AND UNIT_TO_CONVERT="'.$UNIT_TO_CONVERT.'" and ID_compania="'.$id_compania.'"');


          //EN CASO QUE NO SE HAGA CONVERSION DE UNIDDES ESCRIBE EN LA TABLA DE SALES ORDER DETAIL SIN INDICAR EL ITEMID. 
      $values1 = array(
          'ItemOrd' => $key ,
          'ID_compania'=>$id_compania,
          'SalesOrderNumber'=>$SalesOrderNumber,
          'Item_id'=> '',
          'Description'=>$itemid.'  ('.$UnitMeasure.') '.$this->model->Query_value('Products_Exp','Description','Where ProductID="'.$itemid.'";'),
          'REMARK'=>$remarks,
          'Quantity'=>$qty,
          'Unit_Price'=>$unit_price,
          'Net_line'=>$Price,
          'Taxable'=>$this->model->Query_value('Products_Exp','TaxType','Where ProductID="'.$itemid.'" and ID_compania="'.$id_compania.'";') );

      $this->model->insert('SalesOrder_Detail_Imp',$values1); //set item line

      
    $LastUnitCost = $this->model->Query_value('Products_Exp','LastUnitCost','Where ProductID="'.$itemid.'" and id_compania="'.$id_compania.'";');
    $UnitCost = $no_cover_qty *$LastUnitCost;

     $values = array(
      'ItemID' => $itemid,
      'ID_compania' => $this->model->id_compania,
      'Reference' => $SalesOrderNumber,
      'ReasonToAdjust' => 'ACIWEB ref: '.$SalesOrderNumber,
      'Account' =>  $this->model->Query_value('CTA_GL_CONF','GLACCT','where ID_compania="'.$this->model->id_compania .'";'),
      'Quantity' => $no_cover_qty ,
      'USER' => $id_user_active,
      'UnitCost' => $UnitCost,
      'Date' => date('Y-m-d'),
      'location_id' => $this->model->Query_value('status_location','id','where lote="'.$itemid.'0000" and id_product="'.$itemid.'" and route="1" and ID_compania="'.$id_compania.'"')
      );


      $this->model->insert('InventoryAdjust_Imp',$values);

      $factor = '';
   }else{

      //EN CASO QUE NO SE HAGA CONVERSION DE UNIDDES ESCRIBE EN LA TABLA DE SALES ORDER DETAIL INDICANDO EL ITEMID. 
      $values1 = array(
          'ItemOrd' => $key ,
          'ID_compania'=>$id_compania,
          'SalesOrderNumber'=>$SalesOrderNumber,
          'Item_id'=>$itemid,
          'Description'=> '('.$UnitMeasure.') '.$this->model->Query_value('Products_Exp','Description','Where ProductID="'.$itemid.'";'),
          'REMARK'=>$remarks,
          'Quantity'=>$qty,
          'Unit_Price'=>$unit_price,
          'Net_line'=>$Price,
          'Taxable'=>$this->model->Query_value('Products_Exp','TaxType','Where ProductID="'.$itemid.'" and ID_compania="'.$id_compania.'";') );



      $this->model->insert('SalesOrder_Detail_Imp',$values1); //set item line

   }



/*

$values = array(
'ItemID' => $itemID,
'ID_compania' => $this->model->id_compania,
'Reference' => $orderID,
'ReasonToAdjust' => $reasonToAd,
'Account' => $id_GLacct,
'Quantity' => $qty,
'Job_id_int' => $JobID,
'USER' => $id_user_active,
'JobID' => $JobDesc,
'JobPhaseID' =>  $JobPhase,
'JobCostCodeID' => $JobCost,
'UnitCost' => $UnitCost ,
'Date' => date('Y-m-d'),
'location_id' => $this->model->Query_value('status_location','id','where lote="'.$lote.'" and id_product="'.$itemID.'" and route="'.$ruta.'" and ID_compania="'.$this->model->id_compania.'"')
);


$this->model->insert('InventoryAdjust_Imp',$values);
*/





 }
}
echo '1';

}

public function set_sales_order_detail($SalesOrderNumber){

$this->SESSION();

$id_compania= $this->model->id_compania;


$data = json_decode($_GET['Data']);

foreach ($data as $index => $value) {


if($value){



list($itemid,$unit_price,$qty,$Price,$lote,$ruta,$venc) = explode('@', $value );


$chk_cur_val =  $this->model->Query_value('FAC_DET_CONF','DIV_LINE','where ID_compania="'.$this->model->id_compania .'"');

if (!$chk_cur_val){

    if($venc!=''){
           $venc = date('Y-m-d',strtotime($venc));
         }else{
           $venc = '';
      }


    //IF ITEMS EXIST
    $clause='where Item_id="'.$itemid.'" and SalesOrderNumber="'.$SalesOrderNumber.'" and ID_compania="'.$id_compania.'";';
    $ID = $this->model->Query_value('SalesOrder_Detail_Imp','ID',$clause);



    if ($ID==''){

          $values1 = array(
          'ItemOrd' => $index,
          'ID_compania'=>$id_compania,
          'SalesOrderNumber'=>$SalesOrderNumber,
          'Item_id'=>$itemid,
          'Description'=>$this->model->Query_value('Products_Exp','Description','Where ProductID="'.$itemid.'";'),
          'Quantity'=>$qty,
          'Unit_Price'=>$unit_price,
          'Net_line'=>$Price,
          'Taxable'=>'1');

           $this->model->insert('SalesOrder_Detail_Imp',$values1); //set item line

            if ($venc!=''){

              $caduc =   'Vence :'.$venc.' ';

            }else{

              $caduc = '';

            }


          $Description = 'Lote :'.$lote.' '.$caduc.' Cant.:'.$qty;

         

          $values2 = array(
          'ItemOrd' => $index,
          'ID_compania'=>$id_compania,
          'SalesOrderNumber'=>$SalesOrderNumber,
          'Item_id'=>'',
          'Description'=>$Description,
          'Quantity'=>'0',
          'Unit_Price'=>'0',
          'Net_line'=>'0',
          'Taxable'=>'1');


          $this->model->insert('SalesOrder_Detail_Imp',$values2);//set lote line



    }else{

          $QUERY='SELECT Quantity, Unit_Price FROM SalesOrder_Detail_Imp where ID="'.$ID.'"';

          $QTY_PRI = $this->model->Query($QUERY);

                foreach ($QTY_PRI  AS $QTY_PRI) {

                $QTY_PRI = json_decode($QTY_PRI);

                $now_qty = $QTY_PRI->{'Quantity'}+$qty;
                $net_line= $QTY_PRI->{'Unit_Price'} * $now_qty;

                }


          $query= 'UPDATE SalesOrder_Detail_Imp SET Quantity="'.$now_qty.'" , Net_line="'.$net_line.'" where ID="'.$ID.'";';

          
          $this->model->Query($query);

          if ($venc!=''){

              $caduc =   'Vence :'.$venc.' ';
              
            }else{

              $caduc = '';

            }

          $Description = 'Lote :'.$lote.' '.$caduc.' Cant.:'.$qty;

          $values2 = array(
          'ItemOrd' => $index,
          'ID_compania'=>$id_compania,
          'SalesOrderNumber'=>$SalesOrderNumber,
          'Item_id'=>'',
          'Description'=>$Description,
          'Quantity'=>'0',
          'Unit_Price'=>'0',
          'Net_line'=>'0',
          'Taxable'=>'1');


          $this->model->insert('SalesOrder_Detail_Imp',$values2);//set lote line


    }

}else{

      $values1 = array(
      'ItemOrd' => $index,
      'ID_compania'=>$id_compania,
      'SalesOrderNumber'=>$SalesOrderNumber,
      'Item_id'=>$itemid,
      'Description'=>$this->model->Query_value('Products_Exp','Description','Where ProductID="'.$itemid.'";'),
      'Quantity'=>$qty,
      'Unit_Price'=>$unit_price,
      'Net_line'=>$Price,
      'Taxable'=>'1');

       $this->model->insert('SalesOrder_Detail_Imp',$values1); //set item line

        if ($venc!=''){

          $caduc =   'Vence :'.$venc.' ';

        }else{

          $caduc = '';

        }


      $Description = 'Lote :'.$lote.' '.$caduc.' Cant.:'.$qty;

     

      $values2 = array(
      'ItemOrd' => $index,
      'ID_compania'=>$id_compania,
      'SalesOrderNumber'=>$SalesOrderNumber,
      'Item_id'=>'',
      'Description'=>$Description,
      'Quantity'=>'0',
      'Unit_Price'=>'0',
      'Net_line'=>'0',
      'Taxable'=>'1');


      $this->model->insert('SalesOrder_Detail_Imp',$values2);//set lote line



}





$ruta = $this->model->Query_value('ubicaciones','id','Where etiqueta="'.$ruta.'";');

$values3 = array(
'SaleOrderId'=>$SalesOrderNumber,
'no_lote'=>$lote,
'ProductID'=>$itemid,
'qty'=>$qty,
'status_pendding' => '1',
'status_location_id' => $this->model->Query_value('status_location','id','Where id_product="'.$itemid.'" and lote="'.$lote.'" and route="'.$ruta.'" and ID_compania="'.$id_compania.'";'),
'ID_compania' => $id_compania
);


$this->model->insert('sale_pendding',$values3);

//UBICO LA CANTIDAD ACTUAL EN STATUS_LOCATION
$CURRENT_QTY = $this->get_any_lote_qty($lote,$itemid,$ruta);

//ACTUALIZO LA CANTIDAD EN LA UBICCION DEFAULT
$QTY_TO_SET = $CURRENT_QTY - $qty;

$query= 'UPDATE status_location SET qty="'.$QTY_TO_SET.'" where lote="'.$lote.'" and id_product="'.$itemid.'" and route="'.$ruta.'" and ID_compania="'.$id_compania.'"';


$res = $this->model->Query($query);


 

}


}

echo '1';
}





public function set_sal_merc(){

$this->SESSION();
$id_compania= $this->model->id_compania;
$id_user_active = $this->model->active_user_id;

$reasonToAd = $_REQUEST['REASON'];

$Jobinfo = $_REQUEST['JOB'];
list($JobDesc,$JobPhase,$JobCost) = explode(';', $Jobinfo);

$orderID = $this->model->Get_Ref_No(); 

$data = json_decode($_REQUEST['Data']);

foreach ($data as $index => $value) {


if($value){


list($itemID,$qty,$lote,$ruta,$fecha_ven) = explode('@', $value);



$id_GLacct = $this->model->Query_value('CTA_GL_CONF','GLACCT','where ID_compania="'.$this->model->id_compania .'";');
$ruta = $this->model->Query_value('ubicaciones','id','Where etiqueta="'.$ruta.'";');
$LastUnitCost = $this->model->Query_value('Products_Exp','LastUnitCost','Where ProductID="'.$itemID.'" and id_compania="'.$id_compania.'";');

//echo 'LUC'.$LastUnitCost;

$UnitCost = $qty*$LastUnitCost;

$qty = (-1)*$qty;



$values = array(
'ItemID' => $itemID,
'ID_compania' => $this->model->id_compania,
'Reference' => $orderID,
'ReasonToAdjust' => $reasonToAd,
'Account' => $id_GLacct,
'Quantity' => $qty,
'Job_id_int' => $JobID,
'USER' => $id_user_active,
'JobID' => $JobDesc,
'JobPhaseID' =>  $JobPhase,
'JobCostCodeID' => $JobCost,
'UnitCost' => $UnitCost ,
'Date' => date('Y-m-d'),
'location_id' => $this->model->Query_value('status_location','id','where lote="'.$lote.'" and id_product="'.$itemID.'" and route="'.$ruta.'" and ID_compania="'.$this->model->id_compania.'"')
);


$this->model->insert('InventoryAdjust_Imp',$values);

// ******************************************************************************************************************


//UBICO LA CANTIDAD ACTUAL EN STATUS_LOCATION
$CURRENT_QTY = $this->get_any_lote_qty($lote,$itemID,$ruta);

//ACTUALIZO LA CANTIDAD EN LA UBICCION DEFAULT
$QTY_TO_SET = $CURRENT_QTY + $qty; //(qty viene con signo negativo)

$query= 'UPDATE status_location SET qty="'.$QTY_TO_SET.'" where lote="'.$lote.'" and id_product="'.$itemID.'" and route="'.$ruta.'" and ID_compania="'.$this->model->id_compania.'"';


$res = $this->model->Query($query);

}

}
echo $orderID;
}

public function get_invadj_info($id,$resp){


$this->SESSION();


$query ='SELECT * FROM `InventoryAdjust_Imp` 
inner JOIN `status_location` ON status_location.id = InventoryAdjust_Imp.location_id
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = InventoryAdjust_Imp.USER where InventoryAdjust_Imp.Reference ="'.$id.'" and  
status_location.ID_compania="'.$this->model->id_compania.'" GROUP BY InventoryAdjust_Imp.Reference';



$ORDER_detail= $this->model->Query($query);


echo '<br/><br/><fieldset><legend>Detalle de Salida de Mercancia</legend><table class="table table-striped table-bordered" cellspacing="0"  ><tr>';

  foreach ($ORDER_detail as $datos) {
    $ORDER_detail = json_decode($datos);

$Proyecto= $this->model->Query_value('Jobs_Exp','JobID','where ID="'.$ORDER_detail->{'JobID'}.'"');

    echo "<th><strong>No. Referencia</strong></th><td class='InfsalesTd order'>".str_pad($ORDER_detail->{'Reference'}, 7 ,"0",STR_PAD_LEFT)."</td>
          <th><strong>Fecha</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'Date'}."</td>
          <th><strong>Proyecto</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'JobID'}.' / '.$ORDER_detail->{'JobPhaseID'}.' / '.$ORDER_detail->{'JobCostCodeID'}."</td>
          <th><strong>Descripcion</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'ReasonToAdjust'}."</td>
          <th><strong>Responsable</strong></th><td class='InfsalesTd'>".$resp."</td>";

}



echo "</tr></table>";

$query ='SELECT * FROM `InventoryAdjust_Imp` 
inner JOIN `status_location` ON status_location.id = InventoryAdjust_Imp.location_id
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = InventoryAdjust_Imp.USER where InventoryAdjust_Imp.Reference ="'.$id.'" and  
status_location.ID_compania="'.$this->model->id_compania.'"';


$ORDER= $this->model->Query($query);

echo '<table id="example-12" class="table table-striped table-bordered" cellspacing="0"  >
      <thead>
        <tr>
          <th>Codigo</th>
          <th>Descripcion</th>
          <th>Cantidad</th>
          <th>Unidad</th>
          <th>Estado Sinc.</th>
        </tr>
      </thead><tbody>';


foreach ($ORDER as $datos) {

    $ORDER = json_decode($datos);

    $id= "'".$ORDER_detail->{'SalesOrderNumber'}."'";

  if($ORDER->{'Error'}=='1') { 

   $status= "Error : ".$ORDER->{'ErrorPT'}. 'Se ha cancelado la Orden';
   $style="style='color:red;'"; 


 } else{

    if($ORDER->{'Enviado'}!="1"){

      $style="style='color:orange;'"; 
      $status='Por Procesar'; }else{ 

        $status= "Sincronizado el: ".$ORDER->{'Export_date'};
         $style="style='color:green;'";

       }   

    }

$quantity = number_format($ORDER->{'Quantity'},2,'.',',')*(-1);

$prod_desc = $this->model->Query_value('Products_Exp','Description','where ProductID="'.$ORDER->{'ItemID'}.'"');

$prod_measure= $this->model->Query_value('Products_Exp','UnitMeasure','where ProductID="'.$ORDER->{'ItemID'}.'"');

echo  "<tr>
          <td>".$ORDER->{'ItemID'}."</td>
          <td>".$prod_desc."</td>
          <td>".$quantity."</td>
          <td>".$prod_measure.'</td>
          <td '.$style.' >'.$status.'</td>
      </tr>';

  }

echo '</tbody></table><div style="float:right;" class="col-md-2">
<a href="'.URL.'index.php?url=ges_ventas/ges_print_SalMerc/'.$ORDER_detail->{'Reference'}.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
   <img  class="icon" src="img/Printer.png" />
  <span>Imprimir</span>
</a>
</div></fieldset>';





}


public function get_salesorder_info($id){

$this->SESSION();
$id_compania= $this->model->id_compania;

$query ="SELECT * FROM `SalesOrder_Header_Imp`
inner JOIN `SalesOrder_Detail_Imp` ON SalesOrder_Header_Imp.SalesOrderNumber = SalesOrder_Detail_Imp.SalesOrderNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = SalesOrder_Header_Imp.user
inner JOIN Products_Exp ON Products_Exp.ProductID = SalesOrder_Detail_Imp.item_id
WHERE SalesOrder_Header_Imp.SalesOrderNumber='".$id."' and SalesOrder_Header_Imp.ID_compania='".$id_compania."'  GROUP BY SalesOrder_Detail_Imp.SalesOrderNumber ";



$ORDER_detail= $this->model->Query($query);


echo '<br/><br/><fieldset><legend>Detalle de Orden de venta</legend><table class="table table-striped table-bordered" cellspacing="0"  ><tr>';

  foreach ($ORDER_detail as $datos) {
    $ORDER_detail = json_decode($datos);


  if($ORDER_detail->{'Error'}=='1') { 

   $status= "Error : ".$ORDER_detail->{'ErrorPT'}. 'Se ha cancelado la Orden';
   $style="style='color:red;'"; 


 } else{

    if($ORDER_detail->{'Enviado'}!="1"){

      $style="style='color:orange;'"; 
      $status='Por Procesar'; }else{ 

        $status= "Sincronizado el: ".$ORDER_detail->{'Export_date'};
        $style="style='color:green;'";

       }   

    }

$aprobacion = $this->model->Query_value('SalesOrder_Header_Exp','Close_SO','Where SalesOrderNumber="'.$ORDER_detail->{'SalesOrderNumber'}.'" and  ID_compania="'.$this->model->id_compania.'" ');


//if($aprobacion==''){ $apro = 'En espera de envio'; $apro_style="style='color:orange; font-style:bold;'"; }

if($aprobacion=='0' || $aprobacion==''){ $apro = 'En espera de aprobacion';  $apro_style="style='color:orange; font-style:bold;'";  }

if($aprobacion=='1' ){ $apro = 'Aprobado'; $apro_style="style='color:green; font-style:bold;'"; }


    echo "<th><strong>No. Orden</strong></th><td class='InfsalesTd order'>".$ORDER_detail->{'SalesOrderNumber'}."</td>
          <th><strong>Fecha</strong></th><td class='InfsalesTd numb'>".$ORDER_detail->{'date'}."</td>
          <th><strong>Cliente</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'CustomerName'}."</td>
          <th ><strong>Total venta</strong></th><td class='InfsalesTd numb'>".$ORDER_detail->{'Net_due'}."</td>
          <th><strong>Vendedor</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'name'}.' '.$ORDER_detail->{'lastname'}."</td>";

}


if($ORDER_detail->{'Error'}=='1') { 
$apro ='';

 }

echo "</tr></table>";

echo '<table  class="table table-striped table-bordered" cellspacing="0"  width="50%">
      <tr>
      <th><strong>Estado</strong></th><td '.$style.' class="InfsalesTd">'.$status.'</td>
      <th><strong>Aprobacion</strong></th><td   '.$apro_style.'  class="InfsalesTd" >'.$apro.'</td>
      </tr>
      </table>';

$query ="SELECT * FROM `SalesOrder_Detail_Imp`
WHERE SalesOrder_Detail_Imp.SalesOrderNumber='".$id."' and  SalesOrder_Detail_Imp.ID_compania='".$id_compania."' ORDER BY SalesOrder_Detail_Imp.ItemOrd ASC ;";


$ORDER= $this->model->Query($query);

echo '<table id="example-12" class="table table-striped table-bordered" cellspacing="0"  >
      <thead>
        <tr>
          <th>Codigo</th>
          <th>Descripcion</th>
          <th>Cantidad</th>
          <th>Precio Unit.</th>
        </tr>
      </thead><tbody>';


foreach ($ORDER as $datos) {

    $ORDER = json_decode($datos);

    $id= "'".$ORDER_detail->{'SalesOrderNumber'}."'";


echo  "<tr>
          <td>".$ORDER->{'Item_id'}."</td>
          <td>".$ORDER->{'Description'}."</td>
          <td class='numb' >".number_format($ORDER->{'Quantity'},4,'.',',')."</td>
          <td class='numb' >".number_format($ORDER->{'Unit_Price'},4,'.',',')."</td>

      </tr>";

  }

echo '</tbody></table><div style="float:right;" class="col-md-2">
<a href="'.URL.'index.php?url=ges_ventas/ges_print_salesorder/'.$ORDER_detail->{'SalesOrderNumber'}.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
   <img  class="icon" src="img/Printer.png" />
  <span>Imprimir</span>
</a>
</div></fieldset>';


}

public function get_sales_info($id){


$this->SESSION();


$query ="SELECT * FROM `Sales_Header_Imp`
inner JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = Sales_Detail_Imp.InvoiceNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = Sales_Header_Imp.user
inner JOIN Products_Exp ON Products_Exp.ProductID = Sales_Detail_Imp.item_id
WHERE Sales_Header_Imp.InvoiceNumber='".$id."' GROUP BY Sales_Detail_Imp.InvoiceNumber";



$ORDER_detail= $this->model->Query($query);


echo '<br/><br/><fieldset><legend>Detalle de  venta</legend><table class="table table-striped table-bordered" cellspacing="0"  ><tr>';

  foreach ($ORDER_detail as $datos) {
    $ORDER_detail = json_decode($datos);


    echo "<th><strong>No. Orden</strong></th><td class='InfsalesTd order'>".str_pad($ORDER_detail->{'InvoiceNumber'}, 7 ,"0",STR_PAD_LEFT)."</td>
          <th><strong>Fecha</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'date'}."</td>
          <th><strong>Cliente</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'CustomerName'}."</td>
          <th><strong>Total venta</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'Net_due'}."</td>
          <th><strong>Vendedor</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'name'}.' '.$ORDER_detail->{'lastname'}."</td>";

}




echo "</tr></table>";

$query ="SELECT * FROM `Sales_Header_Imp`
inner JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = Sales_Detail_Imp.InvoiceNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = Sales_Header_Imp.user
WHERE Sales_Header_Imp.InvoiceNumber='".$id."';";


$ORDER= $this->model->Query($query);

echo '<table id="example-12" class="table table-striped table-bordered" cellspacing="0"  >
      <thead>
        <tr>
          <th>Codigo</th>
          <th>Descripcion</th>
          <th>Cantidad</th>
          <th>Precio Unit.</th>
          <th>Unidad</th>
          <th>Estado Sinc.</th>
        </tr>
      </thead><tbody>';


foreach ($ORDER as $datos) {

    $ORDER = json_decode($datos);

    $id= "'".$ORDER_detail->{'InvoiceNumber'}."'";

  if($ORDER->{'Error'}=='1') { 

   $status= "Error : ".$ORDER->{'ErrorPT'}. 'Se ha cancelado la Orden';
   $style="style='color:red;'"; 


 } else{

    if($ORDER->{'Enviado'}!="1"){

      $style="style='color:orange;'"; 
      $status='Por Procesar'; }else{ 

        $status= "Sincronizado el: ".$ORDER->{'Export_date'};
         $style="style='color:green;'";

       }   

    }

echo  "<tr>
          <td>".$ORDER->{'Item_id'}."</td>
          <td>".$ORDER->{'Description'}."</td>
          <td>".number_format($ORDER->{'Quantity'},4,'.',',')."</td>
          <td>".number_format($ORDER->{'Unit_Price'},4,'.',',')."</td>
          <td>".$ORDER->{'UnitMeasure'}.'</td>
          <td '.$style.' >'.$status.'</td>
      </tr>';

  }

echo '</tbody></table><div style="float:right;" class="col-md-2">
<a href="'.URL.'index.php?url=ges_ventas/ges_print_sales/'.$ORDER_detail->{'InvoiceNumber'}.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
   <img  class="icon" src="img/Printer.png" />
  <span>Imprimir</span>
</a>
</div></fieldset>';


}



public function set_almacen($name){


$id = $this->model->Query('SELECT id from almacenes where name="'.$name.'" and onoff="1";');

foreach ($id as $value) {

$value = json_decode($value);

 $id = $value->{'id'};

}

if($id==''){ 

 $value = array('name' => $name);

 $this->model->insert('almacenes',$value);

 echo 'Se ha agregado el nuevo almacen';

}else{

    echo 'El nombre de almacen ya existe';

 }

}


public function set_location($id_almacen,$ruta){

$check= $this->model->Query('select * from ubicaciones where etiqueta="'.$ruta.'";');

foreach ($check as $value) {

 $value = json_decode($value);

 $id=$value->{'id'};
}

if($id==''){

$value = array(
  'id_almacen' => $id_almacen,
  'etiqueta' => $ruta
  );



$this->model->insert('ubicaciones',$value);


Echo 'La ubicacion '.$ruta.' se ha creado con exito';

}else{ 

Echo 'La ubicacion '.$ruta.' ya existe ';
}

}


public function get_item_filter_by_stock($id_stock){


$this->SESSION();

$query = '
SELECT *
FROM status_location 
inner join Products_Exp on status_location.id_product = Products_Exp.ProductID
where  stock="'.$id_stock.'" and Products_Exp.ID_compania ="'.$this->model->id_compania.'" ';



$prod = $this->model->Query($query);




$table.= '<script>
var table = $("#items_ubicaciones").dataTable({
      aLengthMenu: [
        [10, 25,50,-1], [10, 25, 50,"All"]
      ]
    });

table.yadcf([
{column_number : 0},
{column_number : 1},
{column_number : 2}
]); 
</script>

<table id="items_ubicaciones" class="table table-striped responsive">
            <thead>
              <tr>
                <th width="20%">Id</th>
                <th width="30%">Descripcion</th>
                <th width="20%">Lote</th>
                <th width="10%">Cant. Disp.</th>
                <th width="10%">Cant. Pend.</th>
                <th width="10%">Almacen</th>
                <th width="10%">Ruta</th></tr>
            </thead>
            <tbody> ';

foreach ($prod as $value) {

$prod = json_decode($value);


$Descr = $this->model->Query_value('Products_Exp','Description',' where ProductID="'.$prod->{'id_product'}.'" ');

$stock = $this->model->Query_value('almacenes','name',' where id="'.$prod->{'stock'}.'"');

$route = $this->model->Query_value('ubicaciones','etiqueta',' where id="'.$prod->{'route'}.'"');

$sale_pendding = $this->model->Query_value('sale_pendding','qty',' where status_pendding="1" and status_location_id="'.$prod->{'id'}.'" and no_lote="'.$prod->{'lote'}.'" and ID_compania="'.$this->model->id_compania.'" ');

if($sale_pendding>=1 || $prod->{'qty'}>=1){

$table.= '<tr><td>'.$prod->{'id_product'}.'</td><td>'.$Descr.'</td><td>'.$prod->{'lote'}.'</td><td>'.$prod->{'qty'}.'</td><td style="background-color:#F5A9A9;" >'.$sale_pendding.'</td><td>'.$stock.'</td><td>'.$route.'</td></tr>';

}



  }          


$table.='</tbody>
            </table>';



echo $table;
  

}


public function  get_item_filter_by_route($id_route){


$this->SESSION();


$query = '
SELECT *
FROM status_location 
inner join Products_Exp on status_location.id_product = Products_Exp.ProductID
where route="'.$id_route.'" and  Products_Exp.ID_compania ="'.$this->model->id_compania.'" ';


$prod = $this->model->Query($query);




$table.= '<script>
var table = $("#items_ubicaciones").dataTable({
      aLengthMenu: [
        [10, 25,50,-1], [10, 25, 50,"All"]
      ]
    });

table.yadcf([
{column_number : 0},
{column_number : 1},
{column_number : 2}
]); 
</script>

<table id="items_ubicaciones" class="table table-striped responsive">
            <thead>
              <tr>
                <th width="20%">Id</th>
                <th width="30%">Descripcion</th>
                <th width="20%">Lote</th>
                <th width="10%">Cant. Disp.</th>
                <th width="10%">Cant. Pend.</th>
                <th width="10%">Almacen</th>
                <th width="10%">Ruta</th></tr>
            </thead>
            <tbody> ';

foreach ($prod as $value) {

$prod = json_decode($value);


$Descr = $this->model->Query_value('Products_Exp','Description',' where ProductID="'.$prod->{'id_product'}.'" ');

$stock = $this->model->Query_value('almacenes','name',' where id="'.$prod->{'stock'}.'"');

$route = $this->model->Query_value('ubicaciones','etiqueta',' where id="'.$prod->{'route'}.'"');

$sale_pendding = $this->model->Query_value('sale_pendding','qty',' where status_pendding="1" and status_location_id="'.$prod->{'id'}.'" and no_lote="'.$prod->{'lote'}.'" and ID_compania="'.$this->model->id_compania.'"');

if($sale_pendding>=1 || $prod->{'qty'}>=1){

$table.= '<tr><td>'.$prod->{'id_product'}.'</td><td>'.$Descr.'</td><td>'.$prod->{'lote'}.'</td><td>'.$prod->{'qty'}.'</td><td style="background-color:#F5A9A9;" >'.$sale_pendding.'</td><td>'.$stock.'</td><td>'.$route.'</td></tr>';

}



  }          


$table.='</tbody>
            </table>';



echo $table;

  

}

public function erase_account($id){

$query='UPDATE SAX_USER SET onoff="0" where id="'.$id.'"';

$this->model->Query($query);


}

public function Get_SalesOrders($sort,$limit,$date1,$date2){


$this->SESSION();

$clause='';

$clause.= 'where SalesOrder_Header_Imp.ID_compania="'.$this->model->id_compania.'" ';



if($date1!=''){

   if($date2!=''){

      $clause.= 'and  date between "'.$date1.'" and "'.$date2.'" ';           
    }
   
   if($date2==''){ 

     $clause.= 'and date="'.$date1.'"';
   }
     
}



if($this->model->active_user_role=='admin'){

$query ='SELECT * FROM `SalesOrder_Header_Imp` 
inner JOIN `SalesOrder_Detail_Imp` ON SalesOrder_Header_Imp.SalesOrderNumber = SalesOrder_Detail_Imp.SalesOrderNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = SalesOrder_Header_Imp.user '.$clause.' GROUP BY SalesOrder_Header_Imp.SalesOrderNumber order by SalesOrder_Header_Imp.LAST_CHANGE '.$sort.' limit '.$limit ; }

if($this->active_user_role=='user'){

  if($clause!=''){ $clause.= 'and `SAX_USER`.`id`="'.$this->active_user_role_id.'"'; } else{ $clause.= ' Where `SAX_USER`.`id`="'.$this->active_user_role_id.'"'; }

$query='SELECT * FROM `SalesOrder_Header_Imp`
inner JOIN `SalesOrder_Detail_Imp` ON SalesOrder_Header_Imp.SalesOrderNumber = SalesOrder_Detail_Imp.SalesOrderNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = SalesOrder_Header_Imp.user '.$clause.' GROUP BY SalesOrder_Header_Imp.SalesOrderNumber  order by SalesOrder_Header_Imp.LAST_CHANGE '.$sort.' limit '.$limit;

}

       
$table.= '<script type="text/javascript">
  jQuery(document).ready(function($)
  {
   var table = $("#table_report").dataTable({
      bSort: false,
      
      aLengthMenu: [
        [5,10, 25,50,-1], [5,10, 25, 50,"All"]
      ]
    });

   
      
  });
  </script>
  <table id="table_report" class="tableReport table table-striped table-bordered" cellspacing="0"  >
    <thead>
      <tr>
        <th>No. Orden</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Total Venta</th>
        <th>Procesado por:</th>
         <th>Estado</th>
        <th>Aprobacion</th>
        <th>Detalle</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>No. Orden</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Total Venta</th>
        <th>Procesado por:</th>
        <th>Estado</th>
        <th>Aprobacion</th>
        <th>Detalle</th>
      </tr>
    </tfoot>';



$filter =  $this->model->Query($query);

$URL ='"'.URL.'"';

foreach ($filter as $datos) {

  $filter = json_decode($datos);


  $ID ='"'.$filter->{'SalesOrderNumber'}.'"';

   if($filter->{'Error'}==1) { 

     $status= "Error : ".$filter->{'ErrorPT'}. '  Cancelado';
     $style="style='color:red; font-style:bold;'"; 


   } else{

    if($filter->{'Enviado'}==0){

      $style="style='color:orange; font-style:bold;'"; 
      $status='No sincronizado';

       }else{ 

         $status= "Enviado";
         $style="style='color:green; font-style:bold;'";

       }   

    }


$aprobacion = $this->model->Query_value('SalesOrder_Header_Exp','Close_SO','Where SalesOrderNumber="'.$filter->{'SalesOrderNumber'}.'" and  ID_compania="'.$this->model->id_compania.'" ');


//if($aprobacion==''){ $apro = 'En espera de envio'; $apro_style="style='color:orange; font-style:bold;'"; }

if($aprobacion=='0' || $aprobacion==''){ $apro = 'En espera de aprobacion';  $apro_style="style='color:orange; font-style:bold;'";  }

if($aprobacion=='1' ){ $apro = 'Aprobado'; $apro_style="style='color:green; font-style:bold;'"; }



$user = $this->model->Get_User_Info($filter->{'user'}); 

foreach ($user as $value) {
$value = json_decode($value);
$name= $value->{'name'};
$lastname = $value->{'lastname'};
}


if($filter->{'Error'}!=1){$apr = $apro;}

$table.= "<tr>
    <td >".$filter->{'SalesOrderNumber'}."</td>
    <td class='numb' >".$filter->{'date'}."</td>
    <td >".$filter->{'CustomerName'}.'</td>
    <td class="numb" >'.$filter->{'Net_due'}.'</td>
    <td >'.$name.' '.$lastname.'</td>
    <td '.$style.'>'.$status.'</td>
    <td '.$apro_style.'>'.$apr."</td>
    <td ><a href='#' onclick='javascript: show_sales(".$URL.",".$ID."); ' ><i style='color:blue' class='fa fa-search'></i></a>   </td>
   </tr>";

$apr = '';
}

$table.= '</table>';

echo $table;

}

//*****************************************************************

//SECCION DE REPORTES



public function get_report($type,$sort,$limit,$date1,$date2){

$this->SESSION();

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

$table = '';
$clause='';

$clause.= 'where REQ_HEADER.ID_compania="'.$this->model->id_compania.'" and REQ_DETAIL.ID_compania="'.$this->model->id_compania.'" ';

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
      


      responsive: true,
      pageLength: 50,
      dom: "Bfrtip",
      bSort: false,
      select: false,
      scrollY: "200px",
      scrollCollapse: true,
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
[
{column_number : 0,
 column_data_type: "html",
 html_data_type: "text"
 },
{column_number : 1},
{column_number : 3},
{column_number : 4}
],
{cumulative_filtering: true, 
filter_reset_button_text: false}); 

});

table.columns.adjust().draw();

  </script>



   <table id="table_reportReqStat" class="display nowrap table table-condensed table-striped table-bordered" >
   
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

$name = $this->model->Query_value('SAX_USER','name','Where ID="'.$Item->{'USER'}.'"');
$lastname =  $this->model->Query_value('SAX_USER','lastname','Where ID="'.$Item->{'USER'}.'"');

$status='';

$ID = '"'.$Item->{'NO_REQ'}.'"';

$URL = '"'.URL.'"';


$total_restante = $this->get_req_status($Item->{'NO_REQ'});

if($total_restante > 0){

$style = 'style="background-color:#F5A9A9;"';
$status = 'POR PROCESAR';

}else{


$style = 'style="background-color:#BCDFA8;"';
$status = 'PROCESADA';

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

break;
//Reporte  de consignaciones
case "ConList":

$this->SESSION();
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
[
{column_number : 0},
{column_number : 1,
 column_data_type: "html",
 html_data_type: "text" 
 },
{column_number : 2},
{column_number : 3},
{column_number : 4}
],
{cumulative_filtering: true}); 

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

$this->SESSION();
$table = '';
$clause='';

 
$clause.= 'WHERE PurOrdr_Header_Exp.ID_compania="'.$this->model->id_compania.'"'; 

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
      pageLength: 50,
      dom: "Bfrtip",
      bSort: false,
      select:true,
      scrollY: "200px",
      scrollCollapse: true,

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
[
{column_number : 0,
 column_data_type: "html",
 html_data_type: "text" 
},
{column_number : 1},
{column_number : 2},
{column_number : 3},
{column_number : 4}
],
{cumulative_filtering: true}); 

});


  </script>



  <table id="table_reportPurOrd" class="table table-striped responsive table-bordered" cellspacing="0" >

    <thead>
      <tr>
        <th width="10%">ID Ord. Compra</th>
        <th width="10%">Fecha</th>
        <th width="10%">Proveedor</th> 
        <th width="10%">Total</th>
        <th width="10%">Estado</th>
        <th width="10%">Asignado a</th>
        <th width="30%">Nota</th>
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
               <td >".'<a href="javascript:void(0)" onclick="get_OC('.$PO_NO.')"><strong>'.$value->{'PurchaseOrderNumber'}.'</strong></a>'.'</td>
               <td >'.$date.'</td>
               <td >'.$value->{'VendorName'}.'</td>
               <td class="numb">'.$value->{'Total'}.'</td>
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

//FIN Reporte  de Ordenes de Compra

//Reporte  FACTURAS DE COMPRA
case "PurFact":

$this->SESSION();
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
[
{column_number : 0,
 column_data_type: "html",
 html_data_type: "text" 
},
{column_number : 1},
{column_number : 2},
{column_number : 3}
],
{cumulative_filtering: true}); 

});


  </script>



  <table id="table_reportPurFact" class="display nowrap table table-condensed table-striped table-bordered" cellspacing="0" >

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

$this->SESSION();
$table = '';
$clause='';

 
$clause.= 'WHERE PRI_LIST_ID.ID_compania="'.$this->model->id_compania.'"'; 

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
[
{column_number : 0,
 column_data_type: "html",
 html_data_type: "text" 
},
{column_number : 1}
],
{cumulative_filtering: true}); 

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
         <td><a href="javascript:void(0)" onclick="get_PL('.$PL_ID.','.$date1.','.$PL_Desc.')"><strong>'.$value->{'IDPRICE'}.'</strong></a></td>
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



///////////////////////////////SALESS!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


public function Get_Sales($sort,$limit,$date1,$date2){


$this->SESSION();


$clause='';

$clause.= 'where Sales_Header_Imp.ID_compania="'.$this->model->id_compania.'" ';



if($date1!=''){

   if($date2!=''){

      $clause.= ' and date between "'.$date1.'" and "'.$date2.'" ';           
    }
   
   if($date2==''){ 

     $clause.= ' and date="'.$date1.'"';
   }
     
}



if($this->model->active_user_role=='admin'){

$query ='SELECT * FROM `Sales_Header_Imp` 
inner JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = Sales_Detail_Imp.InvoiceNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = Sales_Header_Imp.user '.$clause.' GROUP BY Sales_Header_Imp.InvoiceNumber order by LAST_CHANGE '.$sort.' limit '.$limit ; }

if($this->active_user_role=='user'){

  if($clause!=''){ $clause.= 'and `SAX_USER`.`id`="'.$this->active_user_role_id.'"'; } else{ $clause.= ' Where `SAX_USER`.`id`="'.$this->active_user_role_id.'"'; }

$query='SELECT * FROM `Sales_Header_Imp`
inner JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = SalesOrder_Detail_Imp.InvoiceNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = Sales_Header_Imp.user '.$clause.' GROUP BY SalesOrder_Header_Imp.InvoiceNumber  order by LAST_CHANGE '.$sort.' limit '.$limit;

}

       
$table.= '<script type="text/javascript">
  jQuery(document).ready(function($)
  {
   var table = $("#table_report").dataTable({
      bSort: false,
      
      aLengthMenu: [
        [5,10, 25,50,-1], [5,10, 25, 50,"All"]
      ]
    });

table.yadcf([

{column_number : 2},
{column_number : 3},
{column_number : 4},
{column_number : 5},

]);
   
      
  });
  </script>
  <table id="table_report" class="tableReport table table-striped" cellspacing="0"  >
    <thead>
      <tr>
        <th>No. Orden</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th width="15%">Total Venta</th>
        <th>Procesado por:</th>
        <th>Estado</th>
        <th>Detalle</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>No. Orden</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Total Venta</th>
        <th>Procesado por:</th>
        <th>Estado</th>
        <th>Detalle</th>
      </tr>
    </tfoot>';



$filter =  $this->model->Query($query);

$URL ='"'.URL.'"';

foreach ($filter as $datos) {

  $filter = json_decode($datos);


  $ID ='"'.$filter->{'InvoiceNumber'}.'"';

   if($filter->{'Error'}==1) { 

     $status= "Error : ".$filter->{'ErrorPT'}. 'Cancelado';
     $style="style='color:red; font-style:bold;'"; 


   } else{

    if($filter->{'Enviado'}==0){

      $style="style='color:orange; font-style:bold;'"; 
      $status='No sincronizado';

       }else{ 

         $status= "Enviado";
         $style="style='color:green; font-style:bold;'";

       }   

    }



$user = $this->model->Get_User_Info($filter->{'user'}); 

foreach ($user as $value) {
$value = json_decode($value);
$name= $value->{'name'};
$lastname = $value->{'lastname'};
}


$table.= "<tr>
    <td >".$filter->{'InvoiceNumber'}."</td>
    <td >".$filter->{'date'}."</td>
    <td >".$filter->{'CustomerName'}.'</td>
    <td width="15%">'.$filter->{'Net_due'}.'</td>
    <td >'.$name.' '.$lastname.'</td>
    <td '.$style.'>'.$status."</td>
    <td ><a href='#' onclick='javascript: show_invoice(".$URL.",".$ID."); ' ><i style='color:blue' class='fa fa-search'></i></a>   </td>
   </tr>";

}

$table.= '</table>';

echo $table;

}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SALIDA DE MERCANCIA

public function Get_SalMerc($sort,$limit,$date1,$date2){

$this->SESSION();



$clause='';

$clause.= 'where InventoryAdjust_Imp.ID_compania="'.$this->model->id_compania.'" ';



if($date1!=''){

   if($date2!=''){

      $clause.= ' and Date between "'.$date1.'" and "'.$date2.'" ';           
    }
   
   if($date2==''){ 

     $clause.= ' and Date ="'.$date1.'"';
   }
     
}



if($this->model->active_user_role=='admin'){

$query ='SELECT * FROM `InventoryAdjust_Imp` 
inner JOIN `status_location` ON status_location.id = InventoryAdjust_Imp.location_id
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = InventoryAdjust_Imp.USER '.$clause.' 
and   status_location.ID_compania="'.$this->model->id_compania.'"
GROUP BY InventoryAdjust_Imp.Reference order by InventoryAdjust_Imp.Reference '.$sort.' limit '.$limit ;

 }

if($this->active_user_role=='user'){

  if($clause!=''){ $clause.= 'and `SAX_USER`.`id`="'.$this->active_user_role_id.'"'; } else{ $clause.= ' Where `SAX_USER`.`id`="'.$this->active_user_role_id.'"'; }

$query ='SELECT * FROM `InventoryAdjust_Imp` 
inner JOIN `status_location` ON status_location.id = InventoryAdjust_Imp.location_id
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = InventoryAdjust_Imp.USER '.$clause.' 
and   status_location.ID_compania="'.$this->model->id_compania.'"
GROUP BY InventoryAdjust_Imp.Reference order by Date '.$sort.' limit '.$limit ;

}

       
$table.= '<script type="text/javascript">
  jQuery(document).ready(function($)
  {
   var table = $("#table_report").dataTable({
      bSort: false,
      
      aLengthMenu: [
        [5,10, 25,50,-1], [5,10, 25, 50,"All"]
      ]
    });

table.yadcf([

{column_number : 2},
{column_number : 3},
{column_number : 4},
{column_number : 5},

]);
   
      
  });
  </script>
  <table id="table_report" class="tableReport table table-striped" cellspacing="0"  >
    <thead>
      <tr>
        <th>No. Referencia</th>
        <th>Fecha</th>
        <th>Descripcion</th>
        <th>Proyecto</th>
        <th>Procesado por:</th>
        <th>Estado</th>
        <th width="5%">Detalle</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>No. Referencia</th>
        <th>Fecha</th>
        <th>Descripcion</th>
        <th>Proyecto</th>
        <th>Procesado por:</th>
        <th>Estado</th>
        <th>Detalle</th>
      </tr>
    </tfoot>';



$filter =  $this->model->Query($query);

$URL ='"'.URL.'"';

foreach ($filter as $datos) {

  $filter = json_decode($datos);


  $ID ='"'.$filter->{'Reference'}.'"';

   if($filter->{'Error'}==1) { 

     $status= "Error : ".$filter->{'ErrorPT'}. 'Cancelado';
     $style="style='color:red; font-style:bold;'"; 


   } else{

    if($filter->{'Enviado'}==0){

      $style="style='color:orange; font-style:bold;'"; 
      $status='No sincronizado';

       }else{ 

         $status= "Enviado";
         $style="style='color:green; font-style:bold;'";

       }   

    }



$user = $this->model->Get_User_Info($filter->{'USER'}); 

foreach ($user as $value) {
$value = json_decode($value);
$name= $value->{'name'};
$lastname = $value->{'lastname'};
}

$resp = '"'.$name.' '.$lastname.'"';

$table.= "<tr>
    <td >".$filter->{'Reference'}."</td>
    <td >".$filter->{'Date'}."</td>
    <td >".$filter->{'ReasonToAdjust'}.'</td>
    <td >'.$filter->{'JobID'}.'</td>
    <td >'.$name.' '.$lastname.'</td>
    <td '.$style.'>'.$status."</td>
    <td ><a href='#' onclick='javascript: show_invadj(".$URL.",".$ID.",".$resp."); ' ><i style='color:blue' class='fa fa-search'></i></a>   </td>
   </tr>";

}

$table.= '</table>';

echo $table;


}





public function set_sales_header($ID_compania,$SalesOrderNumber,$CustomerID,$Subtotal,$TaxID,$Net_due,$user){


$this->SESSION();


$SalesOrderNumber = trim(preg_replace('/000+/','',$SalesOrderNumber));



$values = array(
'ID_compania'=>$ID_compania,
'InvoiceNumber'=>$SalesOrderNumber,
'CustomerID'=>$this->model->Query_value('Customers_Exp','CustomerID','Where ID="'.$CustomerID.'";'),
'CustomerName'=>$this->model->Query_value('Customers_Exp','Customer_Bill_Name','Where ID="'.$CustomerID.'";'),
'Subtotal'=>$Subtotal,
'TaxID'=>$TaxID,
'Net_due'=>$Net_due,
'user'=>$user,
'date'=>date("Y-m-d"),
'saletax'=>$this->model->Query_value('sale_tax','rate','Where taxid="'.$TaxID.'";')
);


//echo var_dump($values);

$this->model->insert('Sales_Header_Imp',$values);

}

public function set_sales_detail($ID_compania,$itemid,$unit_price,$SalesOrderNumber,$qty,$Price,$lote,$ruta,$venc,$arrLen,$count){


$SalesOrderNumber = trim(preg_replace('/000+/','',$SalesOrderNumber));

//IF ITEMS EXIST
$clause='where Item_id="'.$itemid.'" and InvoiceNumber="'.$SalesOrderNumber.'";';

$ID = $this->model->Query_value('Sales_Detail_Imp','ID',$clause);



if ($ID==''){


      $values1 = array(
      'ID_compania'=>$ID_compania,
      'invoiceNumber'=>$SalesOrderNumber,
      'Item_id'=>$itemid,
      'Description'=>$this->model->Query_value('Products_Exp','Description','Where ProductID="'.$itemid.'";'),
      'Quantity'=>$qty,
      'Unit_Price'=>$unit_price,
      'Net_line'=>$Price,
      'Taxable'=>'1');

      //echo  'insert';

      $this->model->insert('Sales_Detail_Imp',$values1); //set item line



}else{

$QUERY='SELECT Quantity, Unit_Price FROM Sales_Detail_Imp where ID="'.$ID.'";';

$QTY_PRI = $this->model->Query($QUERY);

foreach ($QTY_PRI  AS $QTY_PRI) {

$QTY_PRI = json_decode($QTY_PRI);

$now_qty = $QTY_PRI->{'Quantity'}+$qty;
$net_line= $QTY_PRI->{'Unit_Price'} * $now_qty;

}



 //echo  'qty: '.$now_qty;
 //echo  'netline: '.$net_line;

      $query= 'UPDATE Sales_Detail_Imp SET Quantity="'.$now_qty.'" , Net_line="'.$net_line.'" where ID="'.$ID.'";';

    //  echo  'query: '.$query;
      

      $this->model->Query($query);


}



$values2 = array(
'ID_compania'=>$ID_compania,
'InvoiceNumber'=>$SalesOrderNumber,
'Item_id'=>'',
'Description'=>'Lote : '.$lote.' / Venc. :'.$venc. '/ Ubicacion: '.$ruta,
'Quantity'=>'0',
'Unit_Price'=>'0',
'Net_line'=>'0',
'Taxable'=>'1');



$this->model->insert('Sales_Detail_Imp',$values2);//set lote line


$ruta = $this->model->Query_value('ubicaciones','id','Where etiqueta="'.$ruta.'";');


$values3 = array(
'SaleOrderId'=>$SalesOrderNumber,
'no_lote'=>$lote,
'ProductID'=>$itemid,
'qty'=>$qty,
'status_pendding' => '1',
'status_location_id' => $this->model->Query_value('status_location','id','Where id_product="'.$itemid.'" and lote="'.$lote.'" and route="'.$ruta.'" and ID_compania="'.$this->model->id_compania.'";')
);



$this->model->insert('sale_fact_pendding',$values3);



//UBICO LA CANTIDAD ACTUAL EN STATUS_LOCATION
$CURRENT_QTY = $this->get_any_lote_qty($lote,$itemid,$ruta);

//ACTUALIZO LA CANTIDAD EN LA UBICCION DEFAULT
$QTY_TO_SET = $CURRENT_QTY - $qty;

$query= 'UPDATE status_location SET qty="'.$QTY_TO_SET.'" where lote="'.$lote.'" and id_product="'.$itemid.'" and route="'.$ruta.'" and ID_compania="'.$this->model->id_compania.'"';

$res = $this->model->Query($query);

//$this->clear_lotacion_register();
if($count==$arrLen){ echo '1';}else{ echo '0';}
}



public function SET_NO_LOTE($item,$no_lote,$qty,$fecha){


$this->SESSION();


//Verifico No_lote si existe
$lote = $this->model->Query_value('Prod_Lotes','no_lote','Where no_lote="'.$no_lote.'" and ID_compania="'.$this->model->id_compania.'"');

if($lote==''){ 


//Actualizo la cantidad en el lote default
$now_qty = $this->model->Query_value('status_location','sum(qty)','Where lote="'.$item.'0000" and ID_compania="'.$this->model->id_compania.'"');

$qty_to_up = $now_qty - $qty;


$query= 'UPDATE status_location SET qty="'.$qty_to_up.'" where lote="'.$item.'0000" and route="1" and stock="1" and ID_compania="'.$this->model->id_compania.'"';
$res = $this->model->Query($query);




//Agrego nuevo lote
$value  = array(
  'ProductID' => $item ,
  'no_lote' => $no_lote ,
  'lote_qty' => '' ,
  'fecha_ven' => $fecha,
  'REG_KEY' => uniqid(),
  'ID_compania' => $this->model->id_compania );


$res = $this->model->insert('Prod_Lotes',$value);

//Agrego ubicacion de nuevo lote por default
$value  = array(
  'id_product' => $item ,
  'lote' => $no_lote ,
  'qty' => $qty ,
  'stock' => '1',
  'route' => '1',
  'ID_compania' => $this->model->id_compania );


$res = $this->model->insert('status_location',$value);



}else{


echo 'El No de Lote ya existe, por favor elija otro nombre';


}

}


public function erase_lote($no_lote,$qty){


$this->SESSION();



$item = $this->model->Query_value('Prod_Lotes','ProductID','Where no_lote="'.$no_lote.'" and ID_compania="'.$this->model->id_compania.'"');


//Actualizo la cantidad en el lote default
$now_qty = $this->model->Query_value('status_location','qty','Where lote="'.$item.'0000" and stock="1" and route="1" and ID_compania="'.$this->model->id_compania.'";');

$qty_to_up = $now_qty + $qty;

$query= 'UPDATE status_location SET qty="'.$qty_to_up.'" where lote="'.$item.'0000" and route="1" and stock="1" and ID_compania="'.$this->model->id_compania.'"';
$res = $this->model->Query($query);


$this->model->Query('DELETE FROM Prod_Lotes WHERE no_lote="'.$no_lote.'" and ID_compania="'.$this->model->id_compania.'"');
$this->model->Query('DELETE FROM status_location WHERE lote="'.$no_lote.'" and ID_compania="'.$this->model->id_compania.'"');

}


public function del_tax($id){


$this->model->Query('delete from sale_tax Where id="'.$id.'";');

}


public function get_ProductsCode(){

$this->SESSION();

$sql = 'SELECT ProductID FROM Products_Exp WHERE id_compania="'.$this->model->id_compania.'"';

$Codigos = $this->model->Query($sql);

foreach ($Codigos as $value) {

  $value = json_decode($value);
   
  $codes .= '<option value="'.$value->{'ProductID'}.'">'.$value->{'ProductID'}.'</option>';

 } 

echo $codes;

}


//REQUISICIONES//////////////////////////////////////////////////////////////////////////////////////////////////////////
public function set_req_header($Req_NO,$nota){
$this->SESSION();

$value_to_set  = array( 
  'NO_REQ' => $Req_NO,   
  'ID_compania' => $this->model->id_compania, 
  'NOTA' => $nota , 
  'USER' => $this->model->active_user_id, 
  'DATE' => date("Y-m-d"), 
  );

$res = $this->model->insert('REQ_HEADER',$value_to_set);

}

public function set_req_items($ID,$DESC,$QTY,$UNIT,$JOB_ID,$JOB_DESC,$PHASE_ID,$PHASE_DESC,$COST_ID,$COST_DESC,$Req_NO,$COUNT,$ARRLENG){
$this->SESSION();

$value_to_set  = array( 
  'ProductID' => $ID, 
  'DESCRIPCION' => $DESC,
  'CANTIDAD' => $QTY,  
  'UNIDAD' => $UNIT,  
  'JOB' => '('.$JOB_ID.')-'.$JOB_DESC,  
  'PHASE' => '('.$PHASE_ID.')-'.$PHASE_DESC,  
  'CCOST' => '('.$COST_ID.')-'.$COST_DESC,  
  'NO_REQ' => $Req_NO, 
  'ITEM_UNIQUE_NO' => $Req_NO.'@'.$ITEMID.date("Ymd").'@'.$this->model->id_compania.$COUNT,
  'ID_compania' => $this->model->id_compania
  );


$res = $this->model->insert('REQ_DETAIL',$value_to_set);

if($COUNT==$ARRLENG){ //SI LOS ITEMS PROCESADOS CONTABILIZADOS CON count ES IGUAL EL NUMERO DE LINEAS EN EL ARRAY (ARRLENG) entonces devuelve 0 para terminar el proceso de insesion de registros
  echo '1'; 
}else{ 
  echo '0';
} 

}

public function get_req_status($id){
$this->SESSION();

//saco estatus de REQUISICION
$sql_total = 'SELECT 
sum(PurOrdr_Detail_Exp.Quantity) as TOTAL_COMPRADO
FROM PurOrdr_Header_Exp
INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
WHERE PurOrdr_Header_Exp.CustomerSO =  "'.$id.'"
AND PurOrdr_Header_Exp.ID_compania =  "'.$this->model->id_compania.'"';

$sql_TOTAL_COMPRADO = $this->model->Query($sql_total);

foreach ($sql_TOTAL_COMPRADO as $value) {
  $value =  json_decode($value);

  $total_comprado = $value->{'TOTAL_COMPRADO'};

  }

$total_REQ = $this->model->Query_value('REQ_DETAIL','sum(CANTIDAD)','WHERE ID_compania="'.$this->model->id_compania.'" and NO_REQ="'.$id.'"');

/*ECHO $total_REQ = $total_REQ ;
ECHO '<BR>'.$total_comprado = $total_comprado;*/


$total_restante = $total_REQ - $total_comprado; 


//ECHO '<BR>'.$total_restante;
return $total_restante;
}


public function get_req_info($id){
$this->SESSION();



$ORDER_detail = $this->model->get_req_to_report('DESC','1','WHERE REQ_HEADER.ID_compania="'.$this->model->id_compania.'" AND  REQ_HEADER.ID_compania="'.$this->model->id_compania.'" and REQ_HEADER.NO_REQ="'.$id.'" and REQ_DETAIL.NO_REQ="'.$id.'"');

echo '<script>

var table = $("#table_info").dataTable({

       rowReorder: {
            selector: "td:nth-child(2)"
        },

      bSort: false,
      select:true,
      scrollY: "800px",
      scrollCollapse: true,
      responsive: true,
      searching: false,
      paging:    false,
      info:      false });


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

$total_restante = $this->get_req_status($ORDER_detail->{'NO_REQ'});

if($total_restante > 0){

$style = 'style="background-color:#F5A9A9;"';
$status = '<strong>POR PROCESAR</strong>';

}else{


$style = 'style="background-color:#BCDFA8;"';
$status = '<strong>PROCESADA</strong>';

}



echo     "<tr><th style='text-align:left;' ><strong>No. Req</strong></th><td class='InfsalesTd order'>".$ORDER_detail->{'NO_REQ'}."</td><tr>
          <tr><th style='text-align:left;'><strong>Fecha</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'DATE'}."</td><tr>
          <tr><th style='text-align:left;'><strong>Solicitado por:</strong></th><td class='InfsalesTd'>".$name.' '.$lastname.'</td><tr>
          <tr><th style="text-align:left;" ><strong>Estado</strong></th><td '.$style.' class="InfsalesTd">'.$status.'</td><tr>';

}


echo "</tbody></table>";



$ORDER= $this->model->get_req_to_print($id);

echo '<table id="table_info" class="table table-striped table-bordered" cellspacing="0"  >
      <thead>
        <tr>
          <th>Codigo</th>
          <th>Descripcion</th>
          <th>Unidad</th>
          <th>Cant. Requerida</th>
          <th>Cant. Ordenada</th>
          <th>Cant. Por Ordenar</th>
          <th>OC asociadas (Cant. Ordenada)</th>
        </tr>
      </thead><tbody>';



foreach ($ORDER as $datos) {

$ORDER = json_decode($datos);



//Informacion de ORDEN DE COMPRA PARA ESTE PRODUCTO EN LA REQUISICION
$sql_OC = 'SELECT 
PurOrdr_Header_Exp.PurchaseOrderNumber,
PurOrdr_Detail_Exp.Quantity
FROM PurOrdr_Header_Exp
INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
WHERE PurOrdr_Header_Exp.CustomerSO =  "'.$ORDER_detail->{'NO_REQ'}.'"
AND PurOrdr_Header_Exp.ID_compania =  "'.$this->model->id_compania.'"
AND PurOrdr_Detail_Exp.Item_id = "'.$ORDER->{'ProductID'}.'"';

$INFO_OC = $this->model->Query($sql_OC);

$QTY_TOTAL=0;

unset($Qty_Comprada);

foreach ($INFO_OC as $datos) {

    $INFO_OC = json_decode($datos);

    $Qty_Comprada[$INFO_OC->{'PurchaseOrderNumber'}] = $INFO_OC->{'Quantity'};

    $QTY_TOTAL += $INFO_OC->{'Quantity'};

  }

$QTY_FALTANTE = $ORDER->{'CANTIDAD'}-$QTY_TOTAL;

if($QTY_FALTANTE > 0 ){ $style_row='style="background-color:#F5A9A9;"'; }else{  $style_row=''; }


//Informacion de ORDEN DE COMPRA PARA ESTE PRODUCTO EN LA REQUISICION

$oc_list='';

foreach ($Qty_Comprada as $key => $value) {
/*  
$oc_list .=  '<strong>'.$key.' ('.number_format($value,0,'.',',').')</strong><br>';*/

$oc_list .='<a href="'.URL.'index.php?url=ges_compras/orden_compras/'.$key.'" target="_blank" ><strong>'.$key.' ('.number_format($value,0,'.',',').')</strong></a><BR>';

}

  echo  "<tr ".$style_row." >
            <td>".$ORDER->{'ProductID'}."</td>
            <td>".$ORDER->{'DESCRIPCION'}."</td>
            <td>".$ORDER->{'UNIDAD'}."</td>
            <td class='numb'>".number_format($ORDER->{'CANTIDAD'},4,'.',',')."</td>
            <td class='numb'>".number_format($QTY_TOTAL,4,'.',',')."</td>
            <td class='numb'>".number_format($QTY_FALTANTE,4,'.',',')."</td>
            <td>".$oc_list."</td>
        </tr>";

  

  }

echo '</tbody></table><div style="float:right;" class="col-md-2">
<a href="'.URL.'index.php?url=ges_requisiciones/req_print/'.$ORDER->{'NO_REQ'}.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
   <img  class="icon" src="img/Printer.png" />
  <span>Imprimir</span>
</a>
</div></fieldset>';



}
  

////////////////////////////////////////////////////////////////////////////////////
//PROCESO DE ENVIO DE EMAIL (TEST)
public function send_test_mail($emailtest){

require 'PHP_mailer/PHPMailerAutoload.php';
$mail = new PHPMailer;

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

$mail->AddAddress($emailtest);



if(!$mail->send()) {
 

   $alert .= 'El correo no puede ser enviado.';
   $alert .= 'Error: ' . $mail->ErrorInfo;

   

} else {
  
  $alert = 'El correo de verificacion ha sido enviado';
}

echo $alert;

}


///////////////////////////////////////////////////////////////////////////////////////////////
//PROCESOD DE CONSIGNACION

public function set_consignacion_header($JobDesc,$PhaseDesc,$CostDesc,$reasonToAdj,$no_order){

$this->SESSION();

  $val_to_insert = array(
  'date'   => date('Y-m-d'),
  'idJob'  => $JobDesc, 
  'idPha'  => $PhaseDesc, 
  'idCost' => $CostDesc, 
  'nota'   => $reasonToAdj,
  'refReg' => $no_order,
  'ID_compania' => $this->model->id_compania);

  $this->model->insert('CON_HEADER',$val_to_insert);

}

public function set_con_reg_tras($idItem,$orderNum,$qty,$lote,$ruta,$caduc,$ruta_dest,$almacen_dest,$count,$arrLen){

$this->SESSION();


//RUTA ORIGEN
$route_src = $this->model->Query_value('ubicaciones','id',' where etiqueta="'.$ruta.'"');
$stock_src = $this->model->Query_value('ubicaciones','id_almacen',' where etiqueta="'.$ruta.'"');


//VERIFICO STATUS_LOCATION_ID ACTUAL
$clause = 'WHERE ID_compania="'.$this->model->id_compania.'"  and id_product="'.$idItem.'" and lote="'.$lote.'" and stock="'.$stock_src.'" and route="'.$route_src.'"';

$id_status_loc = $this->model->Query_value('status_location','id',$clause);


//ACTUALIZO LOCACION Y RREGISTRA EL MOVIMIENTO
$this->update_lote_location($ruta,'',$id_status_loc,$ruta_dest,$almacen_dest,$lote,$qty);


  if($count==$arrLen){

    $this->model->con_reg($orderNum,$arrLen,$this->model->id_compania);

    echo '0';

  }else{

    echo '1';

  }

}


public function get_con_info($id){
$this->SESSION();

$clause= 'WHERE CON_HEADER.ID_compania="'.$this->model->id_compania.'"
                 and CON_REG_TRAS.ID_compania="'.$this->model->id_compania.'"  
                 and reg_traslado.ID_compania="'.$this->model->id_compania.'"
                 and CON_HEADER.refReg="'.$id.'"';

$ORDER_detail = $this->model->get_con_to_report('DESC','1',$clause);

echo '<br/><br/><fieldset><legend>Detalle de consignación</legend><table class="table table-striped table-bordered" cellspacing="0"  ><tr>';

foreach ($ORDER_detail as $datos) {
    $ORDER_detail = json_decode($datos);


$user = $this->model->Get_User_Info($ORDER_detail->{'USER'}); 

foreach ($user as $value) {
$value = json_decode($value);
$name= $value->{'name'};
$lastname = $value->{'lastname'};
}



echo     "<th><strong>No. Req</strong></th><td class='InfsalesTd order'>".$ORDER_detail->{'REF'}."</td>
          <th><strong>Fecha</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'date'}."</td>
          <th><strong>Responsable</strong></th><td class='InfsalesTd'>".$name.' '.$lastname."</td>
          <th><strong>Description</strong></th><td class='InfsalesTd'>".$ORDER_detail->{'NOTA'}."</td>";

}

echo "</tr></table>";


//$ORDER= $this->model->get_req_to_print($id);

echo '<table id="example-12" class="table table-striped table-bordered" cellspacing="0"  >
      <thead>
        <tr>
          <th width="10%">Producto</th>
          <th width="10%">Lote</th>
          <th width="10%">Almacen Origen</th>
          <th width="5%">Ruta</th>
          <th width="10%">Almacen Destino</th>
          <th width="5%">Ruta</th>
          <th width="5%">Cantidad</th>
        </tr>
      </thead><tbody>';


$ORDER = $this->model->get_con_to_report('DESC','100',$clause);

foreach ($ORDER as $datos) {

$ORDER = json_decode($datos);


//RUTA ORIGEN
$route_src = $this->model->Query_value('ubicaciones','etiqueta',' where id="'.$ORDER->{'route_ini'}.'"');
$stock_src = $this->model->Query_value('almacenes','name',' where id="'.$ORDER->{'id_almacen_ini'}.'"');

//RUTA DESTINO
$route_des = $this->model->Query_value('ubicaciones','etiqueta',' where id="'.$ORDER->{'route_des'}.'"');
$stock_des = $this->model->Query_value('almacenes','name',' where id="'.$ORDER->{'id_almacen_des'}.'"');

echo  '<tr  >
              <td  >'.$ORDER->{'ProductID'}.'</td>
              <td  >'.$ORDER->{'LOTE'}.'</td>
              <td style="background-color:#F3F781;" >'.$stock_src.'</td>
              <td style="background-color:#F3F781;" >'.$route_src.'</td>
              <td style="background-color:#BCDFA8;" >'.$stock_des.'</td>
              <td style="background-color:#BCDFA8;" >'.$route_des.'</td>
              <td  >'.$ORDER->{'CANT'}.'</td>

          </tr>';
 
  

  }

echo '</tbody></table><div style="float:right;" class="col-md-2">
<a href="'.URL.'index.php?url=ges_consignaciones/con_print/'.$ORDER->{'REF'}.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
   <img  class="icon" src="img/Printer.png" />
  <span>Imprimir</span>
</a>
</div></fieldset>';



}


///////////////////////////////////////////////////////////////////////////////////////////////
//LISTA DE JOBS, FASES Y CENTRO DE COSTOS

public function get_JobList(){
$this->SESSION();

$jobs = $this->model->get_JobList(); 


if($jobs!='0'){
  
foreach ($jobs as $value) {

 $value = json_decode($value);

  $list.= '<option value="'.$value->{'JobID'}.'" >'.$value->{'Description'}.'</option>';

}

echo $list;


}else{



echo '0';


}
}

public function get_phaseList(){
$this->SESSION();

$phase = $this->model->get_phaseList();

if($phase!='0'){

foreach ($phase as $value) {

$value = json_decode($value);

  $list.= '<option value="'.$value->{'PhaseID'}.'" >'.$value->{'Description'}.'</option>';

}


echo $list;

}else{

echo '0';

}



}

public function get_costList(){
$this->SESSION();

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


////////////////////////////////////////////////////////////////////////////////////////////////////////7
//PROCESO COMPRAS/RECIBO DE MERCANCIAS
public function set_fact_header($FACT_NO,$vendorID,$PO_ID,$nota,$total,$date){
$this->SESSION();

$date = date("Y-m-d", strtotime($date));

$VendorName = $this->model->Query_value('Vendors_Exp','Name', 'where VendorID="'.$vendorID.'" AND ID_compania="'.$this->model->id_compania.'"');
$CTA_CXP    = $this->model->Query_value('CTA_GL_CONF','CTA_CXP','WHERE ID_compania="'.$this->model->id_compania.'"');

$value_to_set  = array( 
  'TransactionID' => $FACT_NO,  
  'PurchaseNumber' => $PO_ID, 
  'ID_compania' => $this->model->id_compania,
  'VendorID' => $vendorID,
  'VendorName' => $VendorName,
  'AP_Account' => $CTA_CXP,
  'Net_due' => $total,
  'Subtotal' => $total,
  'nota' => $nota, 
  'USER' => $this->model->active_user_id, 
  'Date' => $date
  );

$res = $this->model->insert('Purchase_Header_Imp',$value_to_set);

echo $res;
}



public function set_fact_items($ITEM_ID,$DESC,$UnitMeasure,$QTY,$Unit_Price,$Net_line,$JOB_ID,$JOB_DESC,$PHASE_ID,$PHASE_DESC,$COST_ID,$COST_DESC,$FACT_NO,$COUNT,$ARRLENG,$lineType){
$this->SESSION();



//$gl_acnt = $this->model->Query_value('Products_Exp','GL_Sales_Acct','WHERE ProductID="'.$ITEM_ID.'" and ID_compania="'.$this->model->id_compania.'"');

if($JOB_ID == '-' ){
  $JOB_ID = '';
}

if ($PHASE_ID == '-' ) {
  $PHASE_ID = '';
}

if ($COST_ID == '-') {
 $COST_ID = '';
}


if($lineType=='1'){

  if($ITEM_ID=='ITBMS'){

  $gl_acnt = $this->model->Query_value('CTA_GL_CONF','CTA_TAX','WHERE ID_compania="'.$this->model->id_compania.'"');


  }else{

  $gl_acnt = $this->model->Query_value('CTA_GL_CONF','CTA_PUR','WHERE ID_compania="'.$this->model->id_compania.'"');

  $ITEM_ID = '';
  }

  

}else{

 $gl_acnt = $this->model->Query_value('Products_Exp','GL_Sales_Acct','WHERE ProductID="'.$ITEM_ID.'" and ID_compania="'.$this->model->id_compania.'"');


}


$value_to_set  = array( 
  'TransactionID' => $FACT_NO, 
  'Item_id' => $ITEM_ID,
  'Description' => $DESC,
  'Quantity' => $QTY,  
  'Unit_Price' => $Unit_Price,
  'Net_line'  => $Net_line,
  'GL_Acct' => $gl_acnt,
  'JobID' => $JOB_ID,  
  'JobPhaseID' => $PHASE_ID,  
  'JobCostCodeID' => $COST_ID, 
  'ID_compania' => $this->model->id_compania
  );



$res = $this->model->insert('Purchase_Detail_Imp',$value_to_set);



if($COUNT==$ARRLENG){ //SI LOS ITEMS PROCESADOS CONTABILIZADOS CON count ES IGUAL EL NUMERO DE LINEAS EN EL ARRAY (ARRLENG) entonces devuelve 0 para terminar el proceso de insesion de registros
  echo '1'; 
}else{ 
  echo '0';
} 

}



public function get_fact_by_id($id){

$this->SESSION();

$query ="SELECT * FROM `Purchase_Header_Imp`
inner JOIN `Purchase_Detail_Imp` ON Purchase_Detail_Imp.TransactionID = Purchase_Header_Imp.TransactionID
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = Purchase_Header_Imp.USER
inner JOIN Products_Exp ON Products_Exp.ProductID =Purchase_Detail_Imp.item_id
WHERE Purchase_Header_Imp.TransactionID='".$id."' and 
      Purchase_Header_Imp.ID_compania='".$this->model->id_compania."'
GROUP BY Purchase_Header_Imp.TransactionID limit 1";



$fact_detail= $this->model->Query($query);

  
  

echo '<br/><br/><fieldset><div class="col-lg-6"><legend>Detalle de factura</legend><table class="table table-striped table-bordered" cellspacing="0"  >';


foreach ($fact_detail as $datos) {

   $fact_detail = json_decode($datos);

   if($fact_detail->{'Error'}=='1') { 

   $status= "Error : ".$fact_detail->{'ErrorPT'}. 'Se ha cancelado la Orden';
   $style="style='color:red;'"; 


 } else{

    if($fact_detail->{'Enviado'}!="1"){

      $style="style='color:orange;'"; 
      $status='Por Procesar'; 

     }else{ 

        $status= "Sincronizado el: ".$fact_detail->{'Export_date'};
        $style="style='color:green;'";

       }   

    }



echo "<tr><th style='text-align:left;'><strong>Ref.</strong></th><td class='InfsalesTd order'>".str_pad($fact_detail->{'TransactionID'}, 9 ,"0",STR_PAD_LEFT)."</td></tr>
      <tr><th style='text-align:left;'><strong>No. Factura</strong></th><td class='InfsalesTd'>".$fact_detail->{'PurchaseNumber'}."</td></tr>
      <tr><th style='text-align:left;'><strong>Fecha</strong></th><td class='InfsalesTd'>".$fact_detail->{'Date'}."</td></tr>
      <tr><th style='text-align:left;'><strong>Proveedor</strong></th><td class='InfsalesTd'>".$fact_detail->{'VendorName'}."</td></tr>
      <tr><th style='text-align:left;'><strong>Total Factura</strong></th><td class='InfsalesTd'>".number_format($fact_detail->{'Net_due'},2,'.',',')."</td></tr>
      <tr><th style='text-align:left;'><strong>Creado por:</strong></th><td class='InfsalesTd'>".$fact_detail->{'name'}.' '.$fact_detail->{'lastname'}."</td></tr>
      <tr><th style='text-align:left;'><strong>Estado:</strong></th><td class='InfsalesTd'  ".$style." >".$status."</td></tr>";

}
echo "</table></div>";




$query ="SELECT * FROM `Purchase_Header_Imp`
inner JOIN `Purchase_Detail_Imp` ON Purchase_Detail_Imp.TransactionID = Purchase_Header_Imp.TransactionID
WHERE Purchase_Header_Imp.TransactionID='".$id."'  and 
      Purchase_Header_Imp.ID_compania='".$this->model->id_compania."' and
      Purchase_Detail_Imp.ID_compania='".$this->model->id_compania."'
 order BY Purchase_Detail_Imp.ID ASC";





$fact_items= $this->model->Query($query);

echo '<table id="example-12" class="table table-striped table-bordered" cellspacing="0"  >
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


echo  "<tr>
          <td class='numb'>".number_format($fact_items->{'Quantity'},5,'.',',')."</td>
          <td>".$fact_items->{'Item_id'}."</td>
          <td>".$fact_items->{'UnitMeasure'}.'</td>
          <td>'.$fact_items->{'Description'}.'</td>
          <td class="numb">'.$fact_items->{'GL_Acct'}."</td>
          <td class='numb'>".number_format($fact_items->{'Unit_Price'},4,'.',',')."</td>
          <td class='numb'>".number_format($fact_items->{'Net_line'},4,'.',',').'</td>
          <td>'.$fact_items->{'JobID'}.'</td>
      </tr>';

  }

echo '</tbody></table><div style="float:right;" class="col-md-2">
<a href="'.URL.'index.php?url=ges_compras/print_fact/'.$fact_items->{'TransactionID'}.'"  class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right btn-single text-left">
   <img  class="icon" src="img/Printer.png" />
  <span>Imprimir</span>
</a>
</div></fieldset>';





}


//TRAE Y MUESTRA EL DETALLE DE UNA PO

public function get_PO_details($id){

$this->SESSION();
$oc = $this->model->get_items_by_OC($id);

$table.= '<table   class="table table-striped " cellspacing="0"  >
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
            <td >'.$value->{'Quantity'}.'</td>
            <td >'.$value->{'Unit_Price'}.'</td>
            <td >'.$value->{'NetLine'}.'</td>
          </tr>';

    }

     
  $table.='</tbody></table>';


    echo $table;




}


//AGREGADO POR ALEX PROYECTO DELIFISH BORRAR DESPUES DE PRUEBAS ESTE COMENTARIO.
//LISTA LOS CLIENTES EN BASE DE DATOS

public function List_customers(){

$this->SESSION();

$CUST_LIST = $this->model-> get_ClientList(); 

$table = '<script type="text/javascript">

 jQuery(document).ready(function($)

  {

   var table = $("#table_CustomerList").dataTable({
   rowReorder: {
            selector: "td:nth-child(2)"
        },

      responsive: true,
      pageLength: 100,
      dom: "Bfrtip",
      bSort: false,
      select:true,
      scrollY: "200px",
      scrollCollapse: true,

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
[
{column_number : 0,
 column_data_type: "html",
 html_data_type: "text" 
},
{column_number : 1},
{column_number : 2},
{column_number : 3},
{column_number : 4}
],
{cumulative_filtering: true}); 

});


  </script>



  <table id="table_CustomerList" class="table table-striped responsive table-bordered" cellspacing="0" >

    <thead>
      <tr>
        <th width="10%">ID. Cliente</th>
        <th width="20%">Nombre del Cliente</th> 
        <th width="30%">Direccion de Facturacion</th>
      </tr>
    </thead>
   <tbody>';

  
  foreach ($CUST_LIST as $datos) {
                                
      $CUST_INF = json_decode($datos);

      if ($CUST_INF->{'IsActive'} == 1) {
           
      $table.= '<tr>
            <td ><a href="'.URL.'index.php?url=ges_niveles_prec/agregar_precios/'.$CUST_INF->{'CustomerID'}.'/'.$CUST_INF->{'Customer_Bill_Name'}.'">'.$CUST_INF->{'CustomerID'}.'</a></td>
            <td >'.$CUST_INF->{'Customer_Bill_Name'}.'</td>
            <td >'.$CUST_INF->{'AddressLine1'}.'</td>
              </tr>';
      }
      }


    $table.='</tbody></table>';

    echo $table;

}


//TRAE Y MUESTRA EL DETALLE DE UNA LISTA DE PRECIOS

public function get_PL_details($id_PL,$date,$Desc){

$this->SESSION();
$PL = $this->model->get_items_by_PL($id_PL);
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

    <table id="Items" class="table table-striped" cellspacing="0"  >
    <thead>
      <tr>
        <th width="20%">Codigo Item</th>
        <th width="30%">Descripcion</th>
        <th width="10%">Precio</th>
        <th width="10%">Unidad</th>
      </tr>
    </thead>
 
 <tbody >';
 
  foreach ($PL as $value) {

    $value = json_decode($value);

    
          $table.= '<tr>
            <td>'.$value->{'IDITEM'}.'</td>
            <td >'.$value->{'DESCRIPTION'}.'</td>
            <td class="numb">'.$value->{'PRICE'}.'</td>
            <td >'.$value->{'UNIT'}.'</td>
                   </tr>';

    }

     
  $table.='</tbody></table></fieldset>';


    echo $table;

}


//Elimina lista de precio

public function del_PL_detail($id_PL){

$this->SESSION();


$clause = 'WHERE IDPRICE = "'.$id_PL.'" and ID_compania="'.$this->model->id_compania.'"';
$table_PL_ITEM = 'PRI_LIST_ITEM';
$table_PL_ID = 'PRI_LIST_ID';

$this->model->delete($table_PL_ITEM,$clause);
$this->model->delete($table_PL_ID,$clause);


}


public function login_to_auth($user,$pass){


$pass = md5($pass);

$ID= $this->model->Query_value("SAX_USER","id" ,"WHERE email='".$user."' AND pass='".$pass."' AND onoff='1' and mod_price='1';");

if($ID==''){ echo "0"; }else{ echo "1"; }


}

public function get_product_byLevel($ID_cust){



$this->SESSION();

$Item =  $this->model->get_ProductsList(); 

echo '<script>var table =  $("#products").dataTable({
        aLengthMenu: [
        [10,20, 25,50,-1], [10,20, 25, 50,"All"]
              ]
            });

 table.yadcf([
{column_number : 1},
{column_number : 2},
]);</script>

<table id="products" class="table table-striped table-bordered" cellspacing="0"  >
            <thead>
              <tr>
                <th width="5%"></th>
                <th width="30%">Codigo</th>
                <th width="40%">Descripcion</th>
                <th width="30%">Precio</th>
                <th width="10%">Cant. Dip</th>

              </tr>
            </thead>
          
          
            <tbody> ';



   foreach ($Item as $datos) {

         $Item = json_decode($datos);
        
         if($Item->{'QtyOnHand'}>=1){
          
          $ID ='"'.$Item->{'ProductID'}.'"';
          $NAME='"'.$Item->{'Description'}.'"';

          


          for ($i=1; $i<=9 ; $i++) {   

           $control = 'false';
           

            switch ($i) {
              case '1':

                if ($ID_cust == 1) {
                    $control = 'true';
                }
                $price_item = $Item->{'Price1'};
                break;

              case '2':

                if ($ID_cust == 2) {
                    $control = 'true';
                }
                $price_item = $Item->{'Price2'};
                
                break;
              case '3':

                if ($ID_cust == 3) {
                    $control = 'true';
                }
                $price_item = $Item->{'Price3'};
               // echo 'entro3';
                break;

              case '4':
                if ($ID_cust == 4) {
                    $control = 'true';
                }
                $price_item = $Item->{'Price4'};
                break;

              case '5':
                if ($ID_cust == 5) {
                    $control = 'true';
                }
                $price_item = $Item->{'Price5'};
                break;
              case '6':
                if ($ID_cust == 6) {
                    $control = 'true';
                }
                $price_item = $Item->{'Price6'};
                break;
              case '7':
                if ($ID_cust == 7) {
                    $control = 'true';
                }
                $price_item = $Item->{'Price7'};
                break;
              case '8':
                if ($ID_cust == 8) {
                    $control = 'true';
                }
                $price_item = $Item->{'Price8'};
                break; 
              case '9':
                if ($ID_cust == 9) {
                    $control = 'true';
                }
                $price_item = $Item->{'Price9'};
                break;
              
              
            }

           

             if ($control == 'true') {
                
                $options.= '<option value="'.number_format($price_item, 4, '.', ',').'" SELECTED >NP '.$ID_cust.' - '.number_format($price_item, 4, '.', ',').'</option>';

                $PRICE ='"'.number_format($price_item, 4, '.', ',').'"';
                $control = 'false';

              }else{

                 $options.= '<option value="'.number_format($price_item, 4, '.', ',').'" >NP '.$i.' - '.number_format($price_item, 4, '.', ',').'</option>';

              }   

              }

          //$PRICE ='"'.number_format($Item->{'Price1'}, 4, '.', ',').'"';
                          
        echo  "<tr>
             <td >
        <a title='Agregar a la orden' data-toggle='modal' data-target='#myModal' href='javascript:void(0)' onclick='javascript: modal(".$ID.",".$NAME.",".$PRICE."); ' ><i style='color:green' class='fa fa-plus'></i></a></td>

            <td  id=".$Item->{'ProductID'}."><strong> ".$Item->{'ProductID'}."</strong></td>

            <td  id=".$Item->{'ProductID'}.$Item->{'Description'}."><strong>".$Item->{'Description'}.'</strong></td> 

            <td class="numb" >
            

            <select  id="'.$Item->{'ProductID'}.'price" name="customer" class="select col-lg-12 numb" required>'.$options.'</select>  
            </td>

            <td  class="numb" id="'.$Item->{'ProductID'}.'qty'.'" >'.number_format($Item->{'QtyOnHand'},5, '.', ',').'</td>
            </tr>';

          }


              $Price_array = array();
              $options = '';

          
  }

  echo '  </tbody>
          </table>';

}

// -WARNING- la llave debajo de este comentario es la que cierra la clase. NO BORRAR NI MODIFICAR.
}

?>

