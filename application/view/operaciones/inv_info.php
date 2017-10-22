  <?php 

$detail = $this->model->get_Purchaseitem($this->ProductID);

foreach ($detail as $value) {

  $value = json_decode($value);

 $Prod_ID = $value->{'ProductID'};
 $desc = $value->{'Description'};
 $QTY =  $value->{'QtyOnHand'};
 $MEASURE = $value->{'UnitMeasure'};
 $PRICE =  $value->{'Price1'};
 $COMP =  $value->{'id_compania'};
}

?>


<div class="page col-lg-12">

<div  class="col-lg-12">
<!-- contenido -->
<h2>Detalles del Item</h2>
<div class="title col-lg-12"></div>
<div class="col-lg-1"></div>
<div class="col-lg-10">

<div  id="edit" class="col-lg-12"> 

<fieldset >
  <legend>Informacion de general</legend>

<fieldset >


<div class="col-lg-12" > 

   <div class="col-lg-3" > 
     <label class="control-label">ID Producto : </label>
     <input  class="form-control" id="item_id" name="item_id"  value="<?php  echo   $Prod_ID;  ?>" readonly/>
    </div>


<div class="separador col-lg-12" > </div>
 
    <div class="col-lg-1" ></div>

    <div class="form-group col-lg-6" > 
       <label class="control-label" >Descripcion:</label>
       <input class="form-control col-lg-10" id="desc_id" name="desc_id"  value="<?php echo  $desc; ?>" readonly/>
    </div>

    <div class="col-lg-1" ></div>

    <div class="form-group  col-lg-3" >    
     <label class="control-label">Id Compa&ntildeia: </label>
     <input class="form-control"  value="<?php echo  $COMP; ?>" readonly/>
    </div>

<div class="separador col-lg-12" > </div>

  <div class="col-lg-1" > </div>

    <div class="form-group  col-lg-3" > 
     <label class="control-label">Precio Unit. : </label>
     <input class="form-control"  value="<?php echo  $PRICE; ?>"  readonly/>
    </div>

    <div class="form-group  col-lg-3" > 

     <label class="control-label">Unidad : </label>
     <input class="form-control"  value="<?php echo  $MEASURE; ?>"  readonly/>

    </div>

     <div class="form-group  col-lg-3" > 
      <label class="control-label">Cant. en stock. :</label>
     <input class="numb  form-control" id="qty" name="qty" value="<?php echo  number_format($QTY,0, '.', ','); ?>"  readonly/>
    </div>


<div class="separador col-lg-12" ></div>

</div>
  
</fieldset>

</fieldset>

</div>
<div class="separador col-lg-12"></div>

<div  class="col-lg-3" >
<input type="button" name="" data-toggle='modal' data-target='#myModal' class="btn btn-primary  btn-block text-left" value="Agregar No. de Lote" />
</div>

<div class="separador col-lg-12"></div>

<div id="list" class="col-lg-12"> 

  <fieldset  >
    <legend>Clasificacion por No de Lote</legend>
    <table id="lotes" class="table table-striped " cellspacing="0"  >
            <thead>
              <tr>
                <th>Lote</th>
                <th>Fecha Venc.</th>
                <th>Cant. Stock</th>
              </tr>
            </thead>
            <tbody>

    <?php

$loteProd = $this->model->Get_lote_list($this->ProductID);
$QtyOnHand = $this->model->Query_value('Products_Exp','QtyOnHand','Where ProductID="'.$this->ProductID.'" and id_compania="'.$this->model->id_compania.'"');

$total_qty = 0;

  foreach ($loteProd  as $value) {
     $value = json_decode($value);

    $lote = "'".$value->{'no_lote'}."'";
    $lote_qty = "'".$value->{'lote_qty'}."'";


if($value->{'no_lote'} == $this->ProductID.'0000' ){

  $button = '<button class="btn btn-primary  btn-block text-left" type="submit" disabled>Borrar</button>';

}else{

    if($this->model->active_user_role=='admin'){ 

      $button = '<button onclick="eliminar_lote('.$lote.','.$lote_qty.');"  class="btn btn-primary  btn-block text-left" type="submit" >Borrar</button>'; 
    }else{

      $button = '<button class="btn btn-primary  btn-block text-left" type="submit" disabled>Borrar</button>';

    }

  
}
    


$sumqty = $this->model->Query_value('status_location','sum(qty)','where id_product="'.$this->ProductID.'" and lote="'.$value->{'no_lote'}.'" and ID_compania="'.$this->model->id_compania.'"');

$qtypend = $this->model->Query_value('sale_pendding','sum(qty)','where ProductID="'.$this->ProductID.'" and no_lote="'.$value->{'no_lote'}.'" and status_pendding="1" and ID_compania="'.$this->model->id_compania.'"');

$qty = $sumqty + $qtypend;
$total_qty = $total_qty+$qty;

//echo $value->{'fecha_ven'};

   if($value->{'fecha_ven'}!='0000-00-00 00:00:00' and $value->{'fecha_ven'}!=null){

               $venc = date('Y-m-d',strtotime($value->{'fecha_ven'}));

            }else{

              $venc = '';
            }




     $table_lote.= '<tr>     
          <td><input class="form-control col-lg-2"  value="'.$value->{'no_lote'}.'" readonly/></td>
          <td><input class="form-control col-lg-2"  value="'. $venc.'" readonly/></td>
          <td><input class="numb form-control col-lg-2"  value="'.number_format($qty,0, '.', ',').'" readonly/></td>
          <td>'.$button.'<td>
          </tr>';

   

   }

   $dif = $QtyOnHand - $total_qty;

    $table_lote .=  '<tr>     
          <td><strong><input class="form-control col-lg-2" style="text-align:right;"  value="Total Stock" readonly/></strong></td>
          <td><strong><input class="form-control col-lg-2" style="text-align:right;"  value="'.number_format($total_qty,0, '.', ',').'" readonly/></strong></td>
          <td><strong><input class="numb form-control col-lg-2"  style="text-align:right;" Value="Dif. "  readonly/><strong></td>
          <td><strong><input type="text" class="numb form-control col-lg-1" style="text-align:right; background-color:#F5A9A9;"    value="'.number_format($dif,0, '.', ',').'" readonly /><strong><td>
          </tr>';
    

    echo $table_lote;

    ?>

    <script type="text/javascript">
      
      function eliminar_lote(lote,qty){

      var r = confirm('Esta seguro de quere eliminar el no de lote : '+lote);

      if(r==true){

          var re = confirm('El proseguir se eliminara el No de lote de la lista y las cantidades de items seran colocadas en el lote "Default", asi como las cantidades de cada ubicacion del lote a eliminar');

          if(re==true){
          
            var URL = $('#URL').val();
            var datos= "url=bridge_query/erase_lote/"+lote+'/'+qty;
            var link= URL+"index.php";

              $.ajax({
                  type: "GET",
                  url: link,
                  data: datos,
                  success: function(res){
                           
                           location.reload(true);
                   
                    }
               });


          }else{

            location.reload();

          }


           
      

      }else{

        location.reload();
      }

    


      }
    </script>


    </tbody>
   </table>
  </fieldset>

</div>


<div class="separador col-lg-12"></div>



<!-- <div id="addlocation" class="col-lg-3" >
<button  onclick="add_location(<?php  echo   "'".URL."','".$Prod_ID."'";  ?>);" style="margin-top: 3px;" class="btn btn-primary  btn-block text-left" type="submit" >Ubicar Item por Lote</button>
</div> -->

<div class="separador col-lg-12"></div>

<div id="line" class="col-lg-12"></div>

<div class="separador col-lg-12"></div>


<div id="list" class="col-lg-12"> 

<fieldset id="update_loc_field" >
  <legend>Ubicaciones</legend>

<div class="col-lg-12" > 

<script type="text/javascript">
  
function add_location(url,id){


dir = url;
$('#update_loc_field').hide();
$('#addlocation').hide();

var datos= "url=bridge_query/get_lote_selectlist/"+id;
   
var link= url+"index.php";

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
    	

              
       $("#line").append(res);


       //$("lote_list").html(res);

        }
   });


}

function set_qty(url,itemid,lote){



var datos= "url=bridge_query/get_lote_qty/"+lote+'/'+itemid;
var link= url+"index.php";

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
   
        document.getElementById("qty_new").setAttribute("max", res);
        document.getElementById("qty_new").setAttribute("value", "1");
        $("#qty_new").removeAttr("readonly");      
        $("#almacen").removeAttr("readonly");
        //$("#routes").removeAttr("readonly");
  
        }
   });
}

function routes(id){
//LA VARIABLE DE URL PROVEIENE DE LA VARIABLO GLOBAL url SETEADA EN LA FUNCION add_location
var datos= "url=bridge_query/get_routes_by_almacenid/"+id;
var link= dir+"index.php";

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
   
        $("#routes").removeAttr("readonly");
        $("#routes").html(res);
        $("#up_route").html(res);
        }
   });

}

function add_location_route(){



var ruta_selected = $('#routes').val();
var almacen_selected = $('#almacen').val();
var item_id = $('#item_id').val();
var lote = $('#no_lote').val();
var qty = $('#qty_new').val();

if ( $('#routes').val() &&  $('#almacen').val() &&  $('#item_id').val() &&  $('#no_lote').val() &&  $('#qty_new').val() ){


var datos= "url=bridge_query/set_lote_location/"+ruta_selected+'/'+almacen_selected+'/'+item_id+'/'+lote+'/'+qty;
var link= dir+"index.php";



 $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
   
        location.reload();
        }
   });



}else{

 alert('Debe definir todos los parametros de ubicacion');

}




 
}

function update_location(STOCK_ROUTE_SRC,STOCK_NAME_SRC,URL,id_location,lote,ven,qty){

dir = URL;

STOCK_ROUTE_SRC = "'"+STOCK_ROUTE_SRC+"'";
STOCK_NAME_SRC = "'"+STOCK_NAME_SRC+"'";
qty_scr = "'"+qty+"'";

var header= '<input id="up_id" type="hidden" value="'+id_location+'" /><fieldset><legend><h4>Reubicacion de Lote</h4></legend><table id="ubicaciones" class="dataTable"><tr><th>No. Lote</th><th>Fecha Venc.</th><th>Cantidad</th><th>Almacen</th><th>Ruta</th></tr>';

var line= '<tr><td><input id="up_lote" class="form-control col-lg-2"  value="'+lote+'" readonly/></td><td><input id="up_venc" class="form-control col-lg-2"  value="'+ven+'" readonly/></td><td><input id="up_qty" type="number" class="form-control col-lg-2"  min="1" max="'+qty+'" /></td><td><select id="up_stock" class="form-control"  onclick="routes(this.value);"></select></td><td><select id="up_route" class="form-control" ></select></td><td><button onclick="javascript: location.reload();"  class="btn btn-warning  btn-block text-left" type="submit" >cancelar</button></td>';
var line2= '<td><button onclick="set_update_location('+STOCK_ROUTE_SRC+','+STOCK_NAME_SRC+','+qty_scr+');" class="btn btn-primary  btn-block text-left"   >Ubicar</button></td></tr>';

var footer = '</table></fieldset>';

 $('#update_loc').html(header+line+line2+footer);


var datos= "url=bridge_query/get_almacen_selectlist/";
var link= URL+"index.php";


 $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
        
         $('#up_stock').html(res);
        }
   });


}

function set_update_location(STOCK_ROUTE_SRC,STOCK_NAME_SRC,maxqty){


var ruta= $('#up_route').val();
var almacen = $('#up_stock').val();

var id_location = $('#up_id').val();
var lote =$('#up_lote').val();
var qty = $('#up_qty').val();


maxqty = Number(maxqty); 
qty = Number(qty);

console.log(maxqty+' '+qty);

if(qty <= maxqty){ 



  if ( $('#up_route').val() && $('#up_stock').val()  && $('#up_qty').val() ){

  var datos= "url=bridge_query/update_lote_location/"+STOCK_ROUTE_SRC+'/'+STOCK_NAME_SRC+'/'+id_location+'/'+ruta+'/'+almacen+'/'+lote+'/'+qty;


  var link= dir+"index.php";


   $.ajax({
        type: "GET",
        url: link,
        data: datos,
        success: function(res){
       //   $("#prueba").html(res);
     
           location.reload();
          }
     });

  }else{

   alert('Debe definir todos los parametros de ubicacion');

  }

}else{


alert('La cantidad a reubicar no debe ser mayor a la cantidad de items en el almacen','ok');
 location.reload();
}


}
</script>

<div id="update_loc">
<table id="ubicaciones" class="table table-striped " cellspacing="0"  >
            <thead>
              <tr>
                <th>Lote</th>
                <th>Fecha Venc.</th>
                <th>Cant. Stock</th>
                <th>Cant. Orden de Venta.</th>
                <th>Almacen</th>
                <th>Ruta</th>
                
              </tr>
            </thead>
            <tbody>
            <?php

           
             $STATUS_LOC = $this->model->lote_loc_by_itemID($this->ProductID);

             foreach ($STATUS_LOC as $STATUS_LOC) { 

             $STATUS_LOC= json_decode($STATUS_LOC); 



             $ID_STATUS = $STATUS_LOC->{'id'};
             $LOTE= $STATUS_LOC->{'no_lote'};
             $VENC= $STATUS_LOC->{'fecha_ven'};
             $STOCK_QTY= $STATUS_LOC->{'qty'};
             $STOCK_ROUTE= $this->model->Query_value('ubicaciones','etiqueta','where id="'.$STATUS_LOC->{'route'}.'"');
             $STOCK_NAME=  $this->model->Query_value('almacenes','name',' where id="'.$STATUS_LOC->{'stock'}.'"');
             
             $STOCK_ROUTE_SRC =  "'".$STOCK_ROUTE."'";
             $STOCK_NAME_SRC =  "'".$STOCK_NAME."'";

             
               $id="'".$Prod_ID."'";
               $lote="'".$STATUS_LOC->{'no_lote'}."'";

               $status_location_id = "'".$STATUS_LOC->{'id'}."'";
            
               $qty="'".$STATUS_LOC->{'qty'}."'";
               $url="'".URL."'";

              
          
             //  if($pendding_sale>0 || $STOCK_QTY>0 ){



$pendding_sale = $this->model->Query_value('sale_pendding','sum(qty)','where status_pendding="1" and status_location_id="'.$STATUS_LOC->{'id'}.'" and ProductID="'.$this->ProductID.'" and no_lote="'.$STATUS_LOC->{'no_lote'}.'"'); //verifico si tiene estatus pendiente por orden de compra


             if ($pendding_sale>=1) { $color = 'style="background-color:#F5A9A9;"'; }else{  $color = 'style="background-color:;"';  }

             if ($pendding_sale>=1 || $STOCK_QTY>=1) {


              if($STOCK_QTY==0){

                $disabled = 'disabled'; 

              }else{ 

                $disabled = ''; 

              }

             


            if($STATUS_LOC->{'fecha_ven'}!=NULL and $STATUS_LOC->{'fecha_ven'}!='0000-00-00 00:00:00' ){

               $venc = date('Y-m-d',strtotime($STATUS_LOC->{'fecha_ven'}));
               $venc2= "'".date('Y-m-d',strtotime($STATUS_LOC->{'fecha_ven'}))."'";

            }else{

              $venc = '';
              $venc2= "'-'";
            }

             echo '<tr><td><input class="form-control col-lg-2"    value="'.$LOTE.'" readonly/></td>
                   <td><input class="form-control col-lg-2"  value="'.$venc.'" readonly/></td>
                   <td><input class="numb form-control col-lg-2"  value="'.number_format($STOCK_QTY,0, '.', ',').'" readonly/></td>
                   <td><input '.$color.' class="form-control col-lg-2"  value="'.$pendding_sale.'" readonly/></td>
                   <td><input class="form-control col-lg-2"  value="'.$STOCK_NAME.'"  readonly/></td>
                   <td><input class="form-control col-lg-2"  value="'.$STOCK_ROUTE.'"  readonly/></td>
                   <td><button onclick="update_location('.$STOCK_ROUTE_SRC.','.$STOCK_NAME_SRC.','.$url.','.$status_location_id.','.$lote.','.$venc2.','.$qty.');"  class="btn btn-primary  btn-block text-left" type="submit" '.$disabled.' >Reubicar</button><td></tr>';

              }
          //   }

       }
             
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>  
</tbody>
</table> 
</div><!--  update location  -->

<dic id="prueba"></dic>
</div>

</fieldset>

</div>

</div>
</div>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog  modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 >Informacion de Lote</h3>
      </div>


      <div class="col-lg-12 modal-body">
        
      <div id='prod'></div>

        <div class="col-lg-5" > 
             <label class="control-label">No. Lote: </label>
             <input  class="form-control" id="no_lote" name="no_lote"  />
        </div>
         <div class="form-group col-lg-3" > 
              <label class="control-label" >Fecha Venc:</label>
              <input type="date" class="form-control col-lg-10" id="fecha_lote" name="fecha_lote"  />
        </div> 
        <div class="col-lg-1" ></div>

        <?php $maxLote = $this->model->Query_value('status_location','sum(qty)', 'where lote="'.$this->ProductID.'0000" and route="1" and stock="1" and ID_compania="'.$this->model->id_compania.'";'); ?>

        <div class="form-group col-lg-3" > 
              <label class="control-label" >Cantidad:</label>
              <input type="number" min='1' max="<?php echo $maxLote; ?>" value='<?php echo $maxLote; ?>' class="form-control col-lg-10" id="qty_lote" name="qty_lote"   />
        </div>        
   
      </div>
      <div class="modal-footer">

      
        <button onclick="agregar_lote('<?php echo $this->ProductID; ?>','<?php echo $maxLote; ?>');" type="button"  class="btn btn-primary " >Guardar</button>
      
    
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick='javascript: location.reload(true);'>Cerrar</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">

function agregar_lote(item,maxqty){



var lote = $('#no_lote').val();
var fecha = $('#fecha_lote').val();
var qty = $('#qty_lote').val();
var URL = $('#URL').val();

qty = Number(qty);
maxqty = Number(maxqty);

console.log(qty+' '+maxqty);

if(qty <= maxqty || qty <= 0 ){

var datos= "url=bridge_query/SET_NO_LOTE/"+item+'/'+lote+'/'+qty+'/'+fecha;
var link= URL+"index.php";

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){

        console.log('RES '+res);

           if(res.trim()!=''){

               alert(res);
               

           }else{
              
     
                location.reload(true);

           }
               
               
       
        }
   });



}else{
alert('Verifique que existen suficientes items en la ubicacion Default del lote "'+item+'0000" para poder crear la existencia de un nuevo lote','ok');
 location.reload(true);
}




 }

</script>
