<script type="text/javascript">


$(window).load(function(){
//cuando la pagina ya cargo
var TaxID=$("#taxid option:selected").html();
var Taxval=$("#taxid option:selected").val();

$('#ERROR').hide();

set_taxid(Taxval);
});


</script>

<?php

$chk_cur_val =  $this->model->Query_value('FAC_DET_CONF','DIV_LINE','where ID_compania="'.$this->model->id_compania .'"');

if ($chk_cur_val){

echo '<input type="hidden" id="FAC_DET_CONF" value="'.$chk_cur_val.'" />'; 

}else{

echo '<input type="hidden" id="FAC_DET_CONF" value="" />';  

}

?>


<div class="page col-lg-12">

<div  class="col-lg-12">

<!--INI DIV ERRO-->
<div id="ERROR" class="alert alert-danger"></div>
<!--INI DIV ERROR-->


<!-- contenido -->
<h2>Orden de Venta</h2>
<div class="title col-lg-12"></div>
<div class="separador col-lg-12"></div>

<input type="hidden" id='URL' value="<?php ECHO URL; ?>" />
<div class="col-lg-12">

<!-- select clientes -->
<div class="col-lg-12">


    <fieldset>
    <legend><h4>Informacion General</h4></legend>
        
    <div class="col-lg-12"> 
      
      <div class="col-lg-6">
        <fieldset>
          <p><strong>Cliente</strong></p>
             <select  id="customer" name="customer" class="select col-lg-8" onchange="sendval(this.value);" required>

              <option selected disabled></option>

              <?php  
              $CUST = $this->model-> get_ClientList(); 

              foreach ($CUST as $datos) {
                                        
              $CUST_INF = json_decode($datos);
              echo '<option value="'.$CUST_INF->{'ID'}.'" >('.$CUST_INF->{'CustomerID'}.' ) - '.$CUST_INF->{'Customer_Bill_Name'}."</option>";

              }
              ?>
                          
            </select> 
       </fieldset>
      </div>
       
      <div class="col-lg-6">
        <fieldset>
        <p><strong>Representante de ventas</strong></p>          
          <select  id="salesrep" name="salesrep" class="select col-lg-8"  required>

            <option selected disabled></option>

            <?php  
            $srep = $this->model-> get_SalesRepre(); 

            foreach ($srep as $datos) {
                                      
            $srep_INF = json_decode($datos);


            echo '<option value="'.$srep_INF->{'SalesRepID'}.'" >'.$srep_INF->{'SalesRep_Name'}."</option>";

            }
            ?>
                        
          </select> 
        </fieldset>
      </div>

      <div class="separador col-lg-12"></div>

         <div class="col-lg-3" >
         <fieldset>
           <p><strong>Entrega a:</strong></p>
            <input class="col-lg-12" id="entrega" name="entrega" />  

         </fieldset>
         </div>
         <div class="col-lg-3" >
         <fieldset>
             <p><strong>Tipo de Licitacion</strong></p>
                <input class="col-lg-12" id="tipo_licitacion" name="tipo_licitacion"/> 
         </fieldset>
         </div>

       
         <div class="col-lg-3" >
         <fieldset>
             <p><strong>Terminos de pago</strong></p>
               <input  class="col-lg-12" id="termino_pago" name="termino_pago" />
         </fieldset>
         </div>

         <div class="col-lg-3" >
           <fieldset>
               <p><strong>Tax ID</strong></p>
                 <select  id="taxid" name="taxid" class="select col-lg-12" onchange="set_taxid(this.value);" required>
                   <?php  
                    $tax = $this->model->Get_sales_conf_Info(); 

                    foreach ($tax  as $datos) {
                      $tax  = json_decode($datos);

                      if($tax->{'taxid'}=='EXENTO'){

                        $selected = 'selected';

                      }else{   

                         $selected = '';

                      }
                                      
                    echo '<option value="'.$tax ->{'rate'}.'" '.$selected.'>'.$tax->{'taxid'}.'</option>';

                    }?>
              </select>
          </fieldset>
        </div>


         <div class="col-lg-6" >
           <fieldset>
             <p><strong>Observaciones</strong></p>
               <textarea class="col-lg-12"  rows="2" id="observaciones" name="observaciones"></textarea> 
         </fieldset> 
         </div>

        <div class="col-lg-3">
         <fieldset>
            <div class="col-lg-12">
                <strong>No. PO: </strong><input type="text"
                 id="nopo" name="nopo"/><br>
           </div>
         </fieldset>
        </div> 

<script type="text/javascript">


  function set_taxid(rate){

    var rate = rate/100;

    document.getElementById('saletaxid').value =  rate;
    
  }

</script>

<input type="hidden"  id="saletaxid"  value="" />

    </div>

    </fieldset>
</div>

</div>

<div class="separador col-lg-12"></div>

<div class="col-lg-12">
<!-- select products -->
<div class="col-lg-12">
<fieldset>
<legend><h4>Seleccionar productos</h4></legend>


<div id="product_list"><P CLASS="help-block">SELECCIONAR CLIENTE PARA DESPLEGAR LA LISTA DE PRODUCTOS</P></div>
        
<script type="text/javascript">
CHK_VALIDATION = false;

function set_product_table(ID_cust){


URL = document.getElementById('URL').value;

var datos= "url=bridge_query/get_product_byLevel/"+ID_cust;
var link= URL+"index.php";

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
   
       $('#product_list').html(res);

     
        }
   });





}

function modal(id,name,price){

URL = document.getElementById('URL').value;

document.getElementById('item_id_modal').value = id;
document.getElementById('desc_id_modal').value = name;


var datos= "url=bridge_query/get_items_location_by_lote/"+id;
var link= URL+"index.php";

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
   
       $('#line').html(res);

     
        }
   });


}
</script>

</fieldset>

</div>





<!-- oredn-->
<div class="col-lg-12" style="float:right;">
  
<fieldset>
<legend>Detalle</legend>

<input type="hidden" id='user' value="<?php echo $active_user_id; ?>" />

<div class="col-lg-12">

  <!-- valores ocultos -->
  <input type="text" id="cust_id" name="cust_id" hidden/>
  <input type="text" id="cust_comp" name="cust_comp" hidden/>


  <div class="title col-lg-12"></div>
  <div class="separador col-lg-12"></div>         
            
  <!-- Invoice Entries -->
  <table class="table table-bordered">
  <thead>
  <tr class="no-borders">
    <th class="text-center hidden-xs">Codigo</th>
    <th width="45%" class="text-center">Descripcion</th>
    <th class="text-center hidden-xs">Cant.</th>
    <th class="text-center">Prec. Unit</th>
    <th class="text-center">Total</th>
  </tr>
  </thead>
              
  <tbody id="invoice">
                
  </tbody>
  </table>
<script>

function sumar_total(id,item,price,qty,taxable){

price = parseFloat(price)*qty

if(taxable=='1'){

var saletax = document.getElementById('saletaxid').value;

}else{
  
var saletax = 0;

}

  subtotal =  document.getElementById('subtotal');
  tax =     document.getElementById('tax');
  total = document.getElementById('total');
  suma_subtotal = parseFloat(subtotal.value)+price;
  itbms = suma_subtotal * saletax;
  TOTAL = itbms + suma_subtotal;
  subtotal.value = parseFloat(Math.round(suma_subtotal* 100) / 100).toFixed(2);
  tax.value =  parseFloat(Math.round(itbms * 100) / 100).toFixed(2);
  total.value =  parseFloat(Math.round(TOTAL * 100) / 100).toFixed(2);

}
</script> 
<!-- Invoice Subtotals and Totals -->
          
<div  class="title  col-lg-12" ></div>  

<div  class="col-lg-4" ></div>                
<div  class="col-lg-8" >

<div class="col-lg-7" >
  <label class="col-lg-12" >Sub - Total:</label>
    <label class="col-lg-12" >ITBMS: </label>
    <label class="col-lg-12" >Total: </label>
</div>

<div class="col-lg-5" >
  <input class="col-lg-12"    style="text-align:right;" type="text"  step="0.01" id="subtotal" name="subtotal"  value="0" readonly />
    <input class="col-lg-12"  style="text-align:right;" type="text" step="0.01" id="tax" name="tax" value="0" readonly />
    <input class="col-lg-12"  style="text-align:right;" type="text" step="0.01" id="total" name="total" value="0" readonly />
</div>
</div>

              
<!-- Invoice Options Buttons -->
<div  class="separador col-lg-12" ></div> 
<div class="col-lg-10"></div>
<div class="col-lg-2">

  <input type="submit" onclick="send_sales_order();" class="btn btn-primary  btn-sm btn-icon icon-right" value="Procesar" />
  
</div>  
          
</fieldset>

</div>
<div  class="separador col-lg-12" ></div> 

</div>



</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 >Ubicacion y Lote</h3>
      </div>

      <div class="col-lg-12 modal-body">
        
      <div id='prod'></div>

        <div class="col-lg-3" > 
             <label class="control-label">ID Producto : </label>
             <input  class="form-control" id="item_id_modal" name="item_id_modal"  readonly/>
        </div>
        <div class="col-lg-1" ></div>
        <div class="form-group col-lg-6" > 
              <label class="control-label" >Descripcion:</label>
              <input class="form-control col-lg-10" id="desc_id_modal" name="desc_id_modal"   readonly/>
        </div> 

<div class="col-lg-12" >  
<div id="line"></div> <!-- /*Aqui se imprime la tabla de productos x lotes*/ -->

</div>    
      </div>
      <div class="modal-footer">
        <button type="button" onclick="set_items();" class="btn btn-primary" >Aceptar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>

<script>
var LineArray = []; //array para los items de la cotizacion
var COUNT_QTY_ARR = [];
var count = 0;
var itemsArray = [];

function sendval(ID){

if(ID){

URL = document.getElementById('URL').value;

var datos= "url=bridge_query/get_Cust_info/"+ID;
var link= URL+"index.php";

function get_customer_info(){

    return $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){

        res = JSON.parse(res);

        }
   });

}

 $.when(get_customer_info()).done(function(cust_info){

  cust_info = JSON.parse(cust_info);
  console.log(cust_info.PriceLevel);
  set_product_table(cust_info.PriceLevel);


 }); 


}else{

  $('#product_list').html(''); //limpio la lista de productos

}
               
}



//FUNCION PARA GUARDAR ITEMS EN ARRAY 
function set_items(){

itemsArray.length ='';

var flag = ''; 
var theTbl = document.getElementById('modal_table'); //objeto de la tabla que contiene los datos de items
var line = '';
var l = '';

for(var i=1; i<theTbl.rows.length ;i++) //BLUCLE PARA LEER LINEA POR LINEA LA TABLA theTbl
{

  var idline = '';
  var cell1 = '';
  var taxfield = '';
  var qty = '';
  var cell ='';
  var iditem ='';
  var descitem = '';

  taxfield = theTbl.rows[i].cells[2].innerHTML+theTbl.rows[i].cells[1].innerHTML+'taxable';
  qty = theTbl.rows[i].cells[2].innerHTML+theTbl.rows[i].cells[1].innerHTML+'qty';
  iditem = document.getElementById('item_id_modal').value;
  descitem = document.getElementById('desc_id_modal').value;



 var qty_val =document.getElementById(qty).value;

if(document.getElementById(qty).value > 0){

  l = 1 + l; //contador de registros
  
    for(var j=0;j<theTbl.rows[i].cells.length; j++) //BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA
        {       

                 switch (j){

                       case 1:
                            var   ruta=theTbl.rows[i].cells[j].innerHTML;
                              break;
                       case 2:
                            var   lote=theTbl.rows[i].cells[j].innerHTML;
                              break;
                       case 3:
                            var   max=theTbl.rows[i].cells[j].innerHTML;
                              break;
                       case 4:
                            
                             if(qty_val ==''){

                              alert('Se requiere indicar la cantidad para el lote: '+theTbl.rows[i].cells[2].innerHTML);
                              l='';
                              break;

                             }

                             break;

                       case 5:
                            var  venc=theTbl.rows[i].cells[j].innerHTML;
                              break;
                       case 6:
                            var  taxable=document.getElementById(taxfield).value;
                              break;
                 }

           }//FIN BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA

        }

        if (qty_val > 0  && l!=''){

              agregar_pro_sale_sale(iditem,descitem,ruta,lote,max,qty_val,venc,taxable);
               
            }
 
}//FIN BLUCLE PARA LEER LINEA POR LINEA DE LA TABLA 

  if(l!=''){

      $('#myModal').modal('hide');
  }

}



function agregar_pro_sale_sale(id,item,ruta,lote,max,qty,venc,taxable){

var Productid = '/'+id+'/';
var ProductLote =  '/'+lote+ '/';

var flag = COUNT_QTY(id,lote,qty,max);

console.log(flag);

if(flag==1){  alert('La cantidad solicitada para este Lote excede los disponible en stock'); return;}
 


var  Line_per_item = '';

Line_per_item =  document.getElementById('FAC_DET_CONF').value;

if(document.getElementById('saletaxid').value==''){

alert('Se debe indicar el TaxID');

}else{



  FechaVenc = venc;

  //nuevo, reemplaza el precio original del producto
  var idprice = id+'price';
  var price = document.getElementById(idprice).value;

  //taxable 

  //var idtaxable = id+'taxable';
  if(taxable=='checked'){

     var taxable = '1';

  }else{

     var taxable = '0';

  }


if (price >= 0){

  var idqty = id+lote+ruta+"qty";
  var element = id+lote+ruta;           
  var qty =  parseFloat(qty).toFixed(5);
  var max =  parseInt( max );
  var total= parseFloat(price*qty).toFixed(4);
  var price= parseFloat(price).toFixed(4);
  var caduc = '';

  if(venc!=''){

    caduc = 'VENCE :'+venc;

  }else{

    caduc = '';

  }


var line_counter = 0;
var qty_new = 0;
var limit = 99999.00000;

if(Line_per_item=='1' && qty > limit){

   var unit_price = price;
   var arrayItem = LineArray.length;

while (qty > 0){

    total = 0;

    qty_count = parseFloat(qty).toFixed(5) - parseFloat(limit).toFixed(5);
   
      if(qty > limit){

        qty_new = parseFloat(limit).toFixed(5);

      }else{

        qty_new = parseFloat(qty).toFixed(5);
      }
    

    total  =  parseFloat(price).toFixed(4) * qty_new; 

    total  =   parseFloat(total).toFixed(2);

    console.log(total+'/'+price+'/'+qty_new);

    Lote = "LOTE :"+lote+" "+caduc+" Cant: "+qty_new;
  
  

  var line = '<tr id="'+element+'"><td class="text-center hidden-xs"><a href="#stay_here" onclick="javascript: rest_value('+price+','+qty_new+','+taxable+'); erase_item('+arrayItem+'); del_tr(this);  del_count('+Productid+','+ProductLote+'); "><i style="color:red;" class="fa fa-minus"></i></a>&nbsp&nbsp'+id+'</td><td>'+item+' <br> '+Lote+'</td><td class="text-right hidden-xs">'+qty_new+'</td><td class="text-right text-primary text-bold">'+unit_price+'</td><td class="text-right text-primary text-bold">'+total+'</td></tr>';
     
              
      $( "#invoice" ).append( line );

      sumar_total(id,item,price,qty_new,taxable);

      // guardo cada linea en el array
      line_price = parseFloat(price)*qty_new;

      LineArray[LineArray.length] = id+"/"+unit_price+"/"+qty_new+"/"+line_price+"/"+lote+'/'+ruta+'/'+FechaVenc;

      qty = qty_count;

}

}else{

     Lote = "LOTE :"+lote+" "+caduc+" Cant: "+qty;

     total  =   parseFloat(total).toFixed(2);

     var arrayItem = LineArray.length

     var line = '<tr id="'+element+'"><td class="text-center hidden-xs"><a href="#stay_here" onclick="javascript: rest_value('+price+','+qty+','+taxable+'); erase_item('+arrayItem+'); del_count('+Productid+','+ProductLote+'); del_tr(this);  " ><i style="color:red;" class="fa fa-minus"></i></a>&nbsp&nbsp'+id+'</td><td>'+item+' <br> '+Lote+'</td><td class="text-right hidden-xs">'+qty+'</td><td class="text-right text-primary text-bold">'+price+'</td><td class="text-right text-primary text-bold">'+total+'</td></tr>';
              

              
      $( "#invoice" ).append( line );

       sumar_total(id,item,price,qty,taxable);
             
             var unit_price = price;

                  // guardo cada linea en el array
      price = parseFloat(price)*qty;

      LineArray[LineArray.length] = id+"@"+unit_price+"@"+qty+"@"+price+"@"+lote+'@'+ruta+'@'+FechaVenc;

}


}else{

alert('El precio del producto no debe ser menor a 0');

}

}

}

function del_tr(remtr){
               
while((remtr.nodeName.toLowerCase())!='tr')
remtr = remtr.parentNode;
remtr.parentNode.removeChild(remtr);

}

function del_id(id){   
 del_tr(document.getElementById(id));
}

function erase_item(line){

    LineArray[line]='';

}

function del_count(ITEM,LOTE){

 COUNT_QTY_ARR[ITEM+''+LOTE]=0;

}

function rest_value(price,qty,taxable){

 if(taxable=='1'){

     var saletax = document.getElementById('saletaxid').value;

  }else{

      var saletax = 0;

  }

  price = parseFloat(price)*qty;

  var subtotal =  document.getElementById('subtotal');
  rest_subtotal = subtotal.value-price;
  subtotal.value = rest_subtotal;

  var tax =document.getElementById('tax');
  itbms = parseFloat(price).toFixed(2)*saletax;
  tax.value = parseFloat(tax.value).toFixed(2)-parseFloat(itbms).toFixed(2);

  subtotal_new =parseFloat(price)+itbms;

  var total = document.getElementById('total');
  total_nuevo = total.value - subtotal_new;
  total.value = total_nuevo;
          

}


function validacion(){

  CUSTOMER = $("#customer").val();

  if (CUSTOMER == ''){

   MSG_ERROR('Se debe seleccionar un cliente',0);

   CHK_VALIDATION = true;

  }
}


function send_sales_order(){

MSG_ERROR_RELEASE();
$('#ERROR').hide();


validacion();

if(CHK_VALIDATION == true){ CHK_VALIDATION = false;  return;  } 


  if(LineArray!=''){

          var r = confirm("Desea enviar esta Orden ahora ?");
                  

              if (r == true) {


               var CustomerID= $("#customer").val();
               var termino_pago = document.getElementById('termino_pago').value;
               var tipo_licitacion = document.getElementById('tipo_licitacion').value;
               var observaciones = document.getElementById('observaciones').value;
               var entrega = document.getElementById('entrega').value;
               var user=document.getElementById('active_user_id').value;
               var nopo=document.getElementById('nopo').value;
               var Subtotal=$("#subtotal").val();
               var total=$("#total").val();
               var Ordertax =$("#tax").val();
               var TaxID=$("#taxid option:selected").html();  //ultimo cambio
               var salesrepre = $("#salesrep").val(); 

               URL = document.getElementById('URL').value;



          //REGITRO DE CABECERA
            function set_header(){

                var datos= "url=bridge_query/set_sales_order_header/"+CustomerID+'/'+Subtotal+'/'+TaxID+'/'+total+'/'+user+'/'+nopo+'/'+termino_pago+'/'+tipo_licitacion+'/'+observaciones+'/'+entrega+'/'+Ordertax+'/'+salesrepre;

                var link= URL+"index.php";

                 console.log(datos);

                return  $.ajax({
                      type: "GET",
                      url: link,
                      data: datos,
                      success: function(res){

                           OS_NO = res;

                        }
                   });

             }

             $.when(set_header()).done(function(OS_NO){ //ESPERA QUE TERMINE LA INSERCION DE CABECERA

               console.log(OS_NO);

                var link= URL+"index.php";

                //REGISTROS DE ITEMS 
                  $.ajax({
                   type: "GET",
                   url:  link,
                   data:  {url: 'bridge_query/set_sales_order_detail/'+OS_NO , Data : JSON.stringify(LineArray)}, 
                   success: function(res){
                                       
                    console.log(res);

                    if(res==1){//TERMINA EL LLAMADO AL METODO set_req_items SI ESTE DEVUELV UN '1', indica que ya no hay items en el array que procesar.
                            
                       msg(link,OS_NO);
                  
                    }

                     }
                  });  
                //FIN REGISTROS DE ITEMS     
           
               }); 

   } 

  }else{

      alert('Debe incluir al menos un Item en la orden');

  }
            
}

function msg(link,SalesOrderNumber){

 alert("La orden se ha enviado con exito");

   var R = confirm('Desea imprimir la orden de venta?');

  if(R==true){

      window.open(link+'?url=ges_ventas/ges_print_salesorder/'+SalesOrderNumber,'_self');
                   
   }else{

      location.reload();

  }

}


function COUNT_QTY(ITEM,LOTE,QTY, MAX){

var SUM = 0;

QTY = Number(QTY.replace(/[^0-9\.]+/g,""));
MAX = Number(MAX.replace(/[^0-9\.]+/g,""));

CURR_QTY =  COUNT_QTY_ARR[ITEM+LOTE];

  if(Number(CURR_QTY)){ 

    SUM = CURR_QTY + QTY;

  }else{

    SUM =  QTY;

  }

compare = (MAX > SUM);

  if(MAX >= SUM){

      COUNT_QTY_ARR[ITEM+LOTE] = SUM;

      return 0;

  }else{

      return 1;

  }

}
</script>