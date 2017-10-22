<?php

$NO_LINES =  $this->model->Query_value('FAC_DET_CONF','NO_LINES','where ID_compania="'.$this->model->id_compania .'"');

echo '<input type="hidden" id="FAC_NO_LINES" value="'.$NO_LINES .'" />'; 

$pice_mod_ck = $this->model->Query_value('SAX_USER','mod_price','where SAX_USER.onoff="1" and SAX_USER.id="'.$this->model->active_user_id.'"');

if ($pice_mod_ck == 1) {

  echo '<input type="hidden" id="editable" value="contenteditable" />'; 

}else{

  echo '<input type="hidden" id="editable" value="" />'; 

}

?>

<script type="text/javascript">

$(window).load(function(){

 $('#ERROR').hide();






//cuando la pagina ya cargo

var TaxID=$("#taxid option:selected").html();

var Taxval=$("#taxid option:selected").val();

set_taxid(Taxval,1);

init(2);

console.log('lineas '+NO_LINES);

var table = $("#table_ord_tb").DataTable({

      bSort: false,

      responsive: false,

      searching: false,

      paging:    false,

      info:      false,

      collapsed: false

  });

 });

// Variables globales

URL = document.getElementById('URL').value;

NO_LINES = document.getElementById('FAC_NO_LINES').value;

link = URL+"index.php";

chk = '';

cantLineas = NO_LINES; //Setea la cantidad de lineas disponibles en la tabla de solicitud de items


function init(chk)
{
var listitem = '';
var i = 1;
var datos= "url=bridge_query/get_ProductsCode/";
var reglon = '';
var editable = document.getElementById('editable').value;
if(editable==''){ bg_color ='style="background-color:#D8D8D8;"';   }else{  bg_color = '';  }

$.ajax({

      type: "GET",

      url: link,

      data: datos,

      success: function(res){

      listitem = res;

$('#table_req').html(''); //limpio la tabla 

while(i <= cantLineas){

      if(chk==1){ 

          reglon = '<td  width="10%" >'+i+'</td>';  

       }else{

          reglon = '<td width="10%" >'+

             /*'<div class="select-editable">'+*/

                '<select class="selectItems col-lg-12" id="sel'+i+'" onchange="SetDesc(this.value,'+i+')" >'+

                    '<option selected></option>'

                     +listitem+

                '</select>'+

               /* '<input id="inp'+i+'" type="text" name="format" value="" />'+*/

             /*'</div>'+*/

            '</td>';  

       }        

      var line_table_req = '<tr>'+reglon+

      '<td width="15%" class="rowtable_req" onkeyup="checkArroba(this.id);" contenteditable id="desc'+i+'"  ></td>'+

      '<td width="15%" class="rowtable_req" onkeyup="checkArroba(this.id);" contenteditable id="nota'+i+'"  ></td>'+

      '<input type="hidden"   id="unit'+i+'" />'+
      '<td width="3%"  class="rowtable_req numb" onkeyup="checkArroba(this.id);" contenteditable ></td>'+

      '<input type="hidden"  id="taxable'+i+'" />'+
      '<td width="3%"  class="rowtable_req numb" onkeyup="checkArroba(this.id);" contenteditable ></td>'+

      '<input type="hidden"  id="stock'+i+'" />'+

      '<td width="5%"  class="rowtable_req  numb" onfocusout="recalcular('+i+');" contenteditable id="qty'+i+'"></td>'+

      '<td width="5%" '+bg_color+'  class="rowtable_req  numb" '+editable+' onfocusout="calculate('+i+');" id="unitprice'+i+'" ></td>'+

      '<td width="5%"  class="rowtable_req  numb" id="total'+i+'" ></td></tr>' ;

       i++

       $('#table_req').append(line_table_req); //limpio la tabla 

      }

      set_selectItemStyle(); 

      }

     });

}



function SetDesc(itemId, line){

var datos= "url=bridge_query/get_ProductsInfo/"+itemId;

var id_desc_field = 'desc'+line;

var id_unit_field = 'unit'+line;

var id_price_field = 'unitprice'+line;

var id_taxable_field = 'taxable'+line;

var id_qty_field = 'qty'+line;

var id_stock_field = 'stock'+line;

var id_total_field = 'total'+line;

var listID =  document.getElementById('listID').value;

$.ajax({

      type: "GET",

      url: link,

      data: datos,

      success: function(res){

      console.log(res);

       json = JSON.parse(res);

       document.getElementById(id_desc_field).innerHTML  = json.Description;

       document.getElementById(id_unit_field).value  = json.UnitMeasure;

       document.getElementById(id_stock_field).value  = json.QtyOnHand;

       if(json.TaxType == 1){

        document.getElementById(id_taxable_field).value  = 'SI';

       }else{

        document.getElementById(id_taxable_field).value  = 'NO';

       }

     }

 });

setTimeout(function(){

 // set_Stockqty_default(itemId,id_stock_field);

    if(listID!=''){

     findprice(itemId, listID, id_price_field);

    }else{

     document.getElementById(id_price_field).innerHTML  = '';

    }

    if(itemId==''){

         document.getElementById(id_price_field).innerHTML  = '';

         recalcular(line);

         document.getElementById(id_qty_field).innerHTML  = '';

         document.getElementById(id_total_field).innerHTML  = '';       

         document.getElementById(id_unit_field ).innerHTML = '';

         document.getElementById(id_desc_field).innerHTML  = '';

         document.getElementById(id_taxable_field).innerHTML  = '';

         document.getElementById(id_price_field).innerHTML  = '';

  }

},500);

}

function set_Stockqty_default(itemId,id_stock_field){

var datos= "url=bridge_query/get_items_defaultstock_qty/"+itemId;

$.ajax({

      type: "GET",

      url: link,

      data: datos,

      success: function(res){

       document.getElementById(id_stock_field).innerHTML = res;

     }

     });

}

function findprice(itemId, listID, id_price_field){

var datos= "url=bridge_query/get_ProductsPrice/"+itemId+"/"+listID;

console.log(datos);

$.ajax({

      type: "GET",

      url: link,

      data: datos,

      success: function(res){

      console.log(res);

      if(res.trim()!=''){

       document.getElementById( id_price_field ).innerHTML  = parseFloat(res).toFixed(4); ;

      }else{

       console.log('yes');

       document.getElementById(id_price_field).innerHTML  = '';

       document.getElementById(id_price_field).setAttribute("contenteditable","");

      }

     }

});

}

function valid_qty(line){

$('#ERROR').hide();

var id_qty_field = 'qty'+line;

var id_stock_field = 'stock'+line;

stockqty= Number(document.getElementById(id_stock_field).innerHTML);

qty= Number(document.getElementById(id_qty_field).innerHTML);

if(qty!=''){

    var compare = (parseInt(stockqty) >= parseInt(qty));

    if(!compare){

      MSG_ERROR('ERROR LINEA '+line+' : El valor de la cantidad no debe exceder la cantidad disponible en Stock',0);

      document.getElementById(id_qty_field).innerHTML = '';

      recalcular(line);

     }else{

      recalcular(line);

     }

}

}

function recalcular(line){

PriceID = 'unitprice'+line;

UnitPrice = document.getElementById(PriceID).innerHTML;

  if(UnitPrice!=''){

      calculate(line);

  }

}

function calculate(line){

qtyID = 'qty'+line;
PriceID = 'unitprice'+line;
totalID = 'total'+line;
qty = document.getElementById(qtyID).innerHTML;
UnitPrice = document.getElementById(PriceID).innerHTML;

if(qty=='' || UnitPrice == ''){

qty = 0;
UnitPrice = 0;

}

total = qty * UnitPrice;

document.getElementById(totalID).innerHTML = parseFloat(total).toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]; 
document.getElementById(qtyID).innerHTML =   parseFloat(qty).toFixed(5);

//document.getElementById(PriceID).innerHTML = parseFloat(UnitPrice).toFixed(2);

sumar_total();

}

function sumar_total(){

var theTbl = document.getElementById('table_ord_tb'); //objeto de la tabla que contiene los datos de items
var l = '';  
var total = [];
var itbms = [];

subtotal_field = document.getElementById('subtotal');
tax_field =      document.getElementById('tax');
tax_value =      document.getElementById('saletaxid').value;
total_field =    document.getElementById('total');

for(var i=1; i<theTbl.rows.length ;i++) //BLUCLE PARA LEER LINEA POR LINEA LA TABLA theTbl

{

  l = 1 + l; //contador de registros

  ITEMid = 'sel'+i;
  taxableID = 'taxable'+i;
  ITEM_ID = '';

    for(var j=0;j<theTbl.rows[i].cells.length; j++) //BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA

        {       

            switch (j){

                   case 7:

                    //console.log(theTbl.rows[i].cells[3].innerHTML);

                    if(document.getElementById(taxableID).value=='SI'){

                    itbms_sum = ( Number(theTbl.rows[i].cells[j].innerHTML) * Number(theTbl.rows[i].cells[5].innerHTML) ) * Number(tax_value);
                    itbms.push(itbms_sum);

                    }

                    total.push(theTbl.rows[i].cells[j].innerHTML);

                    break;

            }

           }//FIN BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA

}//FIN BLUCLE PARA LEER LINEA POR LINEA DE LA TABLA

var subtotal  = 0;
var TAX  = 0;

for(var i=0; i<total.length; i++){

    subtotal  += Number(total[i]);

}

for(var i=0; i<itbms.length; i++){

    TAX    += Number(itbms[i]);

}

    console.log(itbms);

    //TAX = Number(subtotal)*Number(TAX);

    TOTAL = subtotal + TAX;

  subtotal_field.value = parseFloat(subtotal).toFixed(2);
  tax_field.value      = parseFloat(TAX).toFixed(2);
  total_field.value   =  parseFloat(TOTAL).toFixed(2);

}

function set_taxid(rate,opt){

var rate = rate/100;

if(opt==1){

    document.getElementById('saletaxid').value =  rate;

  }else{

     r = confirm('Esta seleccion implica cambios en el calculo del total, desea proceder con este cambio?');

     if(r==true){

      document.getElementById('saletaxid').value =  rate;

      sumar_total();

     }

  }

}

function set_listprice(ID){

var datos= "url=bridge_query/get_Cust_info/"+ID;

var link= URL+"index.php";

  $.ajax({

      type: "GET",
      url: link,
      data: datos,
      success: function(res){
        res = JSON.parse(res);
        document.getElementById('listID').value = res.Custom_field4;
        }

   });


var datos= "url=ges_ventas/GetPayTerm/"+ID;

    $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){

        document.getElementById('termino_pago').value = res;
        }

   });

  init(2);             

}



function mod_price_auth(){

 $('#ERROR').hide();

  //inicio variables de session

  user = $('#user').val();

  pass = $('#pass').val();

var datos= "url=bridge_query/login_to_auth/"+user+'/'+pass;

var link= URL+"index.php";

  $.ajax({

      type: "GET",

      url: link,

      data: datos,

      success: function(res){

         if(res==0){

          MSG_ERROR('El Usuario indicado no tiene aurotizacion para modificacion de precios unitarios.',0);

         }else{ 

          set_price_fields();

         }

        }

   });

}

function set_price_fields(){

    i = 1;

    while ( i <= cantLineas){

    id_unitprice = 'unitprice'+i;

    document.getElementById(id_unitprice).setAttribute("contenteditable", ""); 

    document.getElementById(id_unitprice).setAttribute("style", "background-color:#A9F5BC"); 

    i++;

    }

}



/////////////////////////////////////////////////////////////////////////////////////////////////

</script>

<!--modal-->

<div id="AuthLogin" class="modal fade" role="dialog">

  <div class="modal-dialog modal-lg">

    <!-- Modal content-->

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h3 >Credenciales de autorización</h3>

      </div>

      <div class="col-lg-12 modal-body">

      <!--ini Modal  body-->  

            <div class="form-group col-lg-12">

              <label class="control-label" for="username">Usuario</label>

              <input type="text" class="form-control" id="user" name="user"  autocomplete="off" />

            </div>            

            <div class="form-group col-lg-12">

              <label class="control-label" for="passwd">Password</label>

              <input type="password" class="form-control" name="pass" id="pass" autocomplete="off" />

            </div>

      <!--fin Modal  body-->

      </div>

      <div class="modal-footer">

        <button type="button" onclick="javascript:mod_price_auth();" data-dismiss="modal" class="btn btn-primary" >Aceptar</button>

        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

      </div>

    </div>

  </div>

</div>

<!--modal-->

<div class="page col-lg-12">

<!--INI DIV ERRO-->

<div id="ERROR" class="alert alert-danger"></div>

<!--INI DIV ERROR-->

<div  class="col-lg-12">

<!-- contenido -->

<h2>Pedidos</h2>

<div class="title col-lg-12"></div>

<div class="separador col-lg-12"></div>

<input type="hidden" id='URL' value="<?php ECHO URL; ?>" />

<input type="hidden"  id="saletaxid"  value="" />

<input type="hidden"  id="listID"  value="" />

<!--contenido-->

<div class="col-lg-12">

<!--INI cabecera-->

<div class="col-lg-12">

    <fieldset>

    <legend><h4>Informacion General</h4></legend>

        <div class="col-lg-12"> 

           <div class="col-lg-6">

           <fieldset>

           <p><strong>Cliente</strong></p>

            <select  id="customer" name="customer" class="select col-lg-8" onchange="set_listprice(this.value);" required>

            <option selected disabled></option>

            <?php  

            $CUST = $this->model-> get_ClientList(); 

            foreach ($CUST as $datos) {

            $CUST_INF = json_decode($datos);

            echo '<option value="'.$CUST_INF->{'ID'}.'" >'.$CUST_INF->{'CustomerID'}.' - '.$CUST_INF->{'Customer_Bill_Name'}."</option>";

            }

            ?>

         </select>  

         </fieldset>

         </div>

         <div class="col-lg-2" >

         <fieldset>

           <p><strong>Entrega a:</strong></p>

            <input class="col-lg-12" id="entrega" onkeyup="checkNOTA(this.id);" name="entrega" />  

         </fieldset>

         </div>

        <div class="col-lg-2">

         <fieldset>

          <p><strong>No. PO: </strong><p>

            <input  class="col-lg-12" id="nopo" onkeyup="checkNOTA(this.id);" name="nopo"/>

         </fieldset>

        </div> 

         <div class="col-lg-2" >

         <fieldset>

             <p><strong>Tipo de Licitacion</strong></p>

                <input class="col-lg-12" id="tipo_licitacion" onkeyup="checkNOTA(this.id);" name="tipo_licitacion"/> 

         </fieldset>

         </div>

         <div class="separador col-lg-12"></div>

         <div class="col-lg-3" >

         <fieldset>

             <p><strong>Terminos de pago</strong></p>

               <input  class="col-lg-12" id="termino_pago" onkeyup="checkNOTA(this.id);" name="termino_pago" readonly />

         </fieldset>

         </div>

         <div class="col-lg-3" >

         <fieldset>

             <p><strong>Tax ID</strong></p>

               <select  id="taxid" name="taxid" class="select col-lg-12" onchange="set_taxid(this.value,2);" required>

            <?php  

            $tax = $this->model->Get_sales_conf_Info(); 

            foreach ($tax  as $datos) {

              $tax  = json_decode($datos);

              if($tax->{'taxid'}=='ITBMS'){

                $selected = 'selected';

              }else{   

                 $selected = '';

              }

            echo '<option value="'.$tax ->{'rate'}.'" '.$selected.'>'.$tax->{'taxid'}.'</option>';

            }

            ?>

           </select>

         </fieldset>

       </div>


         <div class="col-lg-3" >

         <fieldset>

             <p><strong>Fecha de entrega</strong></p>

               <input  class="col-lg-12" id="fecha_entrega" onkeyup="checkNOTA(this.id);" name="fecha_entrega" />

         </fieldset>

         </div>


         <div class="col-lg-3" >

         <fieldset>

             <p><strong>Lugar de Depacho</strong></p>

               <input  class="col-lg-12" id="lugar_despacho" onkeyup="checkNOTA(this.id);" name="lugar_despacho" />

         </fieldset>

         </div>

         <div class="separador col-lg-12"></div>

         <div class="col-lg-8" >

           <fieldset>

             <p><strong>Observaciones</strong></p>

               <textarea class="col-lg-12" onkeyup="checkNOTA(this.id);"  rows="2" id="observaciones" name="observaciones"></textarea> 

         </fieldset> 

         </div>

  </div>

 <div class="separador col-lg-12"> </div>

 <div class="col-lg-10"> </div>

  <div  class="col-lg-2">

       <input type="submit" onclick="send_order_2();" class="btn btn-primary  btn-sm btn-icon icon-right" value="Procesar" />

  </div>

</fieldset>

</div>

<!--fin cabecera-->

<div class="separador col-lg-12"> </div>

<?php 

if($pice_mod_ck!=1){ ?>

  <div  class="col-lg-10"></div>

  <div  class="col-lg-2">

       <input data-toggle="modal" data-target="#AuthLogin" type="submit" class="btn btn-primary  btn-sm btn-icon icon-right" value="Aut. Cambio" />

  </div>

<?php }  ?>

<div class="separador col-lg-12"> </div>

<!--ini tabla-->

<div class=" col-lg-12"> 

<fieldset class="table_req" >

<table id="table_ord_tb" class="display table table-striped table-condensed table-bordered " cellspacing="0">

  <thead>

    <tr >

      <th width="10%" >Item ID

<!--       <select id="check_val" onchange="init(this.value);">

      <option value="1" >Renglon</option>

      <option value="2" >Codigo</option> 

      </select> -->

      </th>

      <th width="15%" class="text-center">Descripcion</th>

      <th width="15%" class="text-center">Nota</th>

      <th width="3%"  class="text-center">Chico</th>

      <th width="3%"  class="text-center">Grande</th>

      <th width="5%" class="text-center">Cant.</th>

      <th width="5%" class="text-center">Precio Unit.</th>

      <th width="5%" class="text-center">Total</th>

    </tr>

  </thead>

  <tbody id="table_req" > 

  </tbody>

</table>

</fieldset>

<div  class="separador col-lg-12" ></div>

<div  class="col-lg-8" ></div>

<div  class="col-lg-4" >

<fieldset>

<div class="col-lg-7" >

  <label class="col-lg-12" >Sub - Total:</label>

    <label class="col-lg-12" >ITBMS: </label>

    <label class="col-lg-12" >Total: </label>

</div>

<div class="col-lg-5" >

  <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="subtotal" name="subtotal"  value="0.00" readonly />

  <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="tax" name="tax" value="0.00" readonly/> 

  <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="total" name="total" value="0.00" readonly />

</div>

</fieldset>

</div>

<div  class="separador col-lg-12" ></div>

</div>

<!--fin tabla-->

</div>

<!--fin contenido-->

</div>

</div>

<script type="text/javascript">

//VALIABLES GLOBALES

CHK_VALIDATION = false;

vendorID = '';

falta = 0;

LineArray = [];

FaltaArray = [];

//VALIABLES GLOBALES

function validacion(){

  CUSTOMER = document.getElementById('customer').value;

  if (CUSTOMER == ''){

   MSG_ERROR('Se debe seleccionar un cliente',0);

   CHK_VALIDATION = true;

  }

}

function send_order_2(){

//LIMPIO ERRORES

MSG_ERROR_RELEASE();

$('#ERROR').hide();

/////////////////////////////

//variables internas

var flag = '';

var count= 0;

var arrLen = '';

////////////////////////////

//////////////////////////////

validacion();

if(CHK_VALIDATION == true){ CHK_VALIDATION = false;  return;  }

/////////////////////////////

//AGRUPO LAS LINEAS DE ITEMS EN ARRAY

flag = set_items();

/////////////////////////////////////////////////////////////////////////////////////

//SI NO HAY ITEMS EN LA LISTA

if(flag==0){ 

  MSG_ERROR('No se han indicado registros para envio'); 

    return;

}

/////////////////////////////////////////////////////////////////////////////////////

//SI HAY ITEMS EN LA LISTA

if(flag==1){  

var r = confirm('Desea procesar la orden?');

    if (r == true) { 

    var CustomerID= $("#customer").val();

    var termino_pago = document.getElementById('termino_pago').value;

    var tipo_licitacion = document.getElementById('tipo_licitacion').value;

    var observaciones = document.getElementById('observaciones').value;

    var entrega = document.getElementById('entrega').value;

    var user=document.getElementById('active_user_id').value;

    var nopo=document.getElementById('nopo').value;

    var fecha_entrega=document.getElementById('fecha_entrega').value;

    var Subtotal=$("#subtotal").val();

    var total=   $("#total").val();

    var Ordertax =$("#tax").val();

    var TaxID=$("#taxid option:selected").html();  //ultimo cambio

    var LugDesp = document.getElementById('lugar_despacho').value;
    //REGITRO DE CABECERA

    function set_header(){

    //INI REGISTRO DE CABECERA

    //METODO EN BRIDGE_QUERY



    var datos= "url=bridge_query/set_sales_order_header/"+CustomerID+

                '/'+Subtotal+

                '/'+TaxID+

                '/'+total+

                '//'+nopo+

                '/'+termino_pago+

                '/'+tipo_licitacion+

                '/'+observaciones+

                '/'+entrega+

                '/'+Ordertax+

                '/'+fecha_entrega+

                '/'+LugDesp+

                '/';

console.log(datos);

      return  $.ajax({

            type: "GET",

            url: link,

            data: datos,

            success: function(res){

            console.log(res);

            OS_NO = res;

        }

      });

     }//FIN REGISTRO DE CABECERA

    $.when(set_header()).done(function(OS_NO){ //ESPERA QUE TERMINE LA INSERCION DE CABECERA

     console.log(OS_NO);

      //REGISTROS DE ITEMS 

        $.ajax({

         type: "GET",

         url:  link,

         data:  {url: 'bridge_query/set_sales_order_detail_new/'+OS_NO , Data : JSON.stringify(LineArray)}, 

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

}

/////////////////////////////////////////////////////////////////////////////////////

//MANEJO DE ERRORES POR cAMPO FALTANTES EN LOS ITEMS

if(flag==2){ 

MSG_ERROR_RELEASE(); //LIMPIO DIV DE ERRORES

FaltaArray.forEach(ListFaltantes);

  function ListFaltantes(item,index){

    column = FIND_COLUMN_NAME(index);

      MSG_ERROR('No se indico valor en el Item: '+item+" / Campo :" +column, 1); 

  }

FaltaArray.length = ''; //LIMPIO ARRAY DE ERRORES

}

/////////////////////////////////////////////////////////////////////////////////////

}

/////////////////////////////////////////////////////////////////////////////////////

//FUNCION PARA GUARDAR ITEMS EN ARRAY 

function set_items(){

LineArray.length=''; //limpio el array

var flag = ''; 

var theTbl = document.getElementById('table_ord_tb'); //objeto de la tabla que contiene los datos de items

var line = '';

var cantLineas = Number(document.getElementById('FAC_NO_LINES').value);

var i=1;

//BLUCLE PARA LEER LINEA POR LINEA LA TABLA 

while (i <= cantLineas){

//for(var i=1; i > cantLineas; i++) {

  cell = '';

  //i=i+1;

  for(var j=0;j<theTbl.rows[i].cells.length; j++) //BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA

        {

        var selid = "sel"+i;
        var unitid = 'unit'+i;

        if(document.getElementById(selid).value !=''){

              switch (j){

                       case 7:


                            
                            itemId      = document.getElementById(selid).value;
                            desc        = theTbl.rows[i].cells[1].innerHTML;
                            nota        = theTbl.rows[i].cells[2].innerHTML;
                            UnitMeasure = document.getElementById(unitid).value;

                            qty       = theTbl.rows[i].cells[5].innerHTML;
                            UnitPrice = theTbl.rows[i].cells[6].innerHTML;
                            total     = theTbl.rows[i].cells[7].innerHTML;

                            chic  = theTbl.rows[i].cells[3].innerHTML;
                            gran  = theTbl.rows[i].cells[4].innerHTML;

                            cell += desc+'@'+nota+'@'+UnitMeasure+'@'+itemId+'@'+UnitPrice+'@'+qty+'@'+total+'@'+chic+'@'+gran;//agrego el registo de las demas columnas

                          break;

                       default: 

                          val= theTbl.rows[i].cells[0].innerHTML;

                          if(val==''){                              

                                    FaltaArray[j] = i ;

                                 }


                             break;

                       }

            }      

       } //FIN BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA

       //INSERTA valor de CELL en el arreglo 

       if(document.getElementById(selid).value !=''){

        LineArray[i]=cell; 

       }

i++;       

}//FIN BLUCLE PARA LEER LINEA POR LINEA DE LA TABLA 

//SETEA RETURN DE LA FUNCION, FLAG 1 Ó 0, SI ES 1 LA TABLA ESTA LLENA SI ES 0 LA TABLA ESTA VACIA.

if(FaltaArray.length == 0){

    if(LineArray.length >= 1){ 

    flag = 1; 

     }else{  

      flag = 0; 

     }

}else{

    LineArray.length = '';

    cell = '';

    flag = 2; //Alguna linea no tiene descripcion

}

return flag;

}

//FIN ITEMS

function FIND_COLUMN_NAME(item){

switch (item){

  case 1: val ='Descripcion'; break;

}

return val;

}

function msg(link,SalesOrderNumber){

          alert("La orden se ha enviado con exito");

          var R = confirm('Desea imprimir la orden de venta?');

          if(R==true){

                 window.open(link+'?url=ges_ventas/ges_print_OrdEmpaque/'+SalesOrderNumber,'_self');

          }else{

            location.reload();

          }

        }

 


</script>
