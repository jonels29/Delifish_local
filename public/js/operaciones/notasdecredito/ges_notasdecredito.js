
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

function SetAttri(ID){

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


var datos= "url=ges_notasdecredito/GetPayTerm/"+ID;

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

function set_selectItemStyle(){

  //selectc con buscador 

  $(".selectItems").select2({

  placeholder: '',

  allowClear: true,

  maximumSelectionSize: 1

  }); 

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


//Llamada a metodos de ges_invoice para crear los archivos
function CreateCreditMemoFile(id){
  
  var URL   = $('#URL').val();
  var link = URL + 'index.php';
  var datos= "url=ges_notasdecredito/CreateCreditMemoFile/"+id;

   // console.log(id);

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){

      spin_hide();

      //history.go(-1); return true;

      location.reload(  
        function(){   
          MSG_CORRECT('Se ha creado la Nota de Credito',0);
         });
      //msg(link,OS_NO);
              
      }

   }); 



}


function show_creditmemo(URL,id){


     var datos= "url=ges_notasdecredito/GetCreditMemoInfo/"+id;

      
       $.ajax({
         type: "GET",
         url: URL+'index.php',
         data: datos,
         success: function(res){

           //$("historial").hide();

           $("#info").html(res);

                 }
            });

 }

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

  PRINTER = document.getElementById('Printer').value;

  if (PRINTER == ''){

   MSG_ERROR('Debe seleccionar la impresora fiscal',0);

   CHK_VALIDATION = true;

  }

  NoFactura = document.getElementById('nofact').value;
  serial    = document.getElementById('serial').value;


  if (NoFactura != '' ||  serial != '' ){

     if(NoFactura== ''){

         MSG_ERROR('Se debe rellenar ambos campos de de facturacion fiscal',0);

         CHK_VALIDATION = true;


     }
    if(serial== ''){

         MSG_ERROR('Se debe rellenar ambos campos de de facturacion fiscal',0);

         CHK_VALIDATION = true;


     }
  }

}

function SetNota(){

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



var r = confirm('Desea procesar la nota credito?');

    if (r == true) { 
    spin_show();
    

    var CustomerID= $("#customer").val();
    var termino_pago = document.getElementById('termino_pago').value;
    var tipo_licitacion = document.getElementById('tipo_licitacion').value;
    var observaciones = document.getElementById('observaciones').value;
    var entrega = document.getElementById('entrega').value;
    var user=document.getElementById('active_user_id').value;
    var nopo=document.getElementById('nopo').value;
    var fecha_entrega=document.getElementById('fecha_entrega').value;
    var noinvoice=$("#serial").val()+'-'+$("#nofact").val();
    var Subtotal=$("#subtotal").val();
    var total=   $("#total").val();
    var Ordertax =$("#tax").val();
    var TaxID=$("#taxid option:selected").html();  //ultimo cambio
    var printer = $('#Printer').val();

    //REGITRO DE CABECERA

    function set_header(){



    //INI REGISTRO DE CABECER

    //METODO EN BRIDGE_QUERY
    var datos= "url=ges_notasdecredito/SetCreditNoteHeader/"+CustomerID+

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

                '/'+noinvoice+
                
                '/'+printer;



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

         data:  {url: 'ges_notasdecredito/SetCreditNoteDetail/'+OS_NO , Data : JSON.stringify(LineArray)}, 

         success: function(res){


          console.log(res);

          if(res==1){//TERMINA EL LLAMADO AL METODO set_req_items SI ESTE DEVUELV UN '1', indica que ya no hay items en el array que procesar.

              CreateCreditMemoFile(OS_NO); 

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

                       default: 

                          if(j!=0 ){

                            val= theTbl.rows[i].cells[j].innerHTML;

                            //SI LA CELDA NO CONTIENE VALOR
                             if(j!=2 && j!=3 && j!=4  ){//excluye la columna 2 de la verificacion

                                 if(val==''){                                  

                                    FaltaArray[j] = i ;

                                 }

                              } 

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

  case 0: val ='ITEM ID'; break;

  case 2: val ='NOTA'; break;

  case 3: val ='UNIDAD'; break;

  case 4: val ='CANTIDAD'; break;

}

return val;

}

function msg(link,SalesOrderNumber){

          alert("La orden se ha enviado con exito");

          // var R = confirm('Desea imprimir la orden de venta?');

          // if(R==true){

          //        window.open(link+'?url=ges_notasdecredito/ges_print_OrdEmpaque/'+SalesOrderNumber,'_self');

          // }else{

          //   location.reload();

          // }
          location.reload();
        }

 
