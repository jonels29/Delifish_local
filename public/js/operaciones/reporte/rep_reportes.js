function Filtrar(){


var limit = $('#limit').val();
var sort =  $('#sort').val();
var type =  $('#reportType').val();
var date1 = $('#date1').val();
var date2 = $('#date2').val();



URL = document.getElementById('URL').value;

var datos= "url=ges_reportes/get_report/"+type+"/"+sort+"/"+limit+"/"+date1+"/"+date2;   
var link = URL+"index.php";


$('#table').html('<P>CARGANDO ...</P>');

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
      
       $('#table').html(res);


        }
   });



}

function get_OC(id){

  URL = document.getElementById('URL').value;

var datos= "url=ges_reportes/get_PO_details/"+id;  
var link = URL+"index.php";




  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
      
       $('#table2').html(res);

       // alert(res);

        }
   });
  
  $('html, body').animate({
        scrollTop: $("#table2").offset().top
    }, 2000);


}

function get_PL(id_PL) {

URL = document.getElementById('URL').value;

var datos= "url=ges_reportes/get_PL_details/"+id_PL;  
var link = URL+"index.php";




  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
        console.log(datos);
      
       $('#table3').html(res);

       // alert(res);

        }
   });



}


function del_PL(id_PL) {

/*var id=document.getElementById("user_2").value;
var name = document.getElementById("name2").value;
var lastname =  document.getElementById("lastname2").value;
*/

var datos = 'url=ges_reportes/del_PL_detail/'+id_PL;
var link = URL+"index.php";

var r = confirm('Este seguro de eliminar definitivamente la Lista de Precio '+id_PL+' ?');

if(r==true){

$.ajax({
type: 'GET',
url: link,
data: datos,
success: function(dat){

 alert('La Lista de Precio se ha eliminado exitosamente.'); 

location.reload(true);
/*history.go(-1); 
return true;*/

}


});


}

}

//SETEA EL MODAL CON LOS VALORES ACTUALES

function modal_PL_item(id_PL,id_item,unit,desc){


document.getElementById('item_id_modal').value = id_item;
document.getElementById('PL_id').value = id_PL;
document.getElementById('unit_id_modal').value = unit;
document.getElementById('desc_id_modal').value = desc;

}

//FUNCION JS PARA MODIFICAR EL ITEM

function mod_item(){


  var id_PL = document.getElementById('PL_id').value;
  var iditem = document.getElementById('item_id_modal').value;
  var descitem = document.getElementById('desc_id_modal').value;
  var priceitem = document.getElementById('price_id_modal').value;
  var unit = document.getElementById('unit_id_modal').value;


if(descitem=='' || priceitem=='' || unit==''){

  MSG_ERROR('Debe llenar al menos un campo a modificar',0);

}else{

var R = confirm('Desea modificar el item '+iditem+' ?');

  if (R==true) {

var datos= "url=ges_reportes/modify_item/"+id_PL+"/"+iditem+"/"+descitem+"/"+priceitem+"/"+unit;
var link= URL+"index.php";

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
   
       console.log(res);
       if (res == '1') {

        MSG_CORRECT('El item ha sido modificado exitosamente.',0); 

        get_PL(id_PL);

             
        }
        }
   });
  
}

document.getElementById('PL_id').value = '';
document.getElementById('item_id_modal').value = '';
document.getElementById('desc_id_modal').value = '';
document.getElementById('price_id_modal').value = '';
document.getElementById('unit_id_modal').value = '';
}
}

function del_PL_item(id_PL,id_item){

var R = confirm('Desea modificar el item '+id_item+' ?');

if (R==true) {


var datos= "url=ges_reportes/delete_item/"+id_PL+"/"+id_item;
var link= URL+"index.php";

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
   
       
       console.log(res);
       
       if (res == '1') {

        MSG_CORRECT('El item ha sido eliminado exitosamente.',0); 

        get_PL(id_PL);

             
        }
     
        }
   });


}

}


CHK_VALIDATION = false;

function validacion(){

var itemID    = document.getElementById('item_id_modal_2').value ;
var descPrice = document.getElementById('desc_id_modal_2').value ;
var priceVal  = document.getElementById('price_id_modal_2').value ;

  if (itemID == ''){

   MSG_ERROR('Se debe seleccionar un item Id',0);

   CHK_VALIDATION = true;

  }

  if (descPrice == ''){

   MSG_ERROR('Se debe indicar una descripcion para el nuevo item',0);

   CHK_VALIDATION = true;

  }

  if (priceVal == ''){

   MSG_ERROR('Se debe indicar un valor para el precio del item',0);

   CHK_VALIDATION = true;

  }

}


//agrega ITEMS
function add_item(){

var priceID   = document.getElementById('PL_id_2').value;
var itemID    = document.getElementById('item_id_modal_2').value ;
var descPrice = document.getElementById('desc_id_modal_2').value ;
var priceVal  = document.getElementById('price_id_modal_2').value ;
var unitMes   = document.getElementById('unit_id_modal_2').value ;

//////////////////////////////

validacion();

if(CHK_VALIDATION == true){ CHK_VALIDATION = false;  return;  }

/////////////////////////////

itemData = priceID+'@'+itemID+'@'+descPrice+'@'+priceVal+'@'+unitMes;

var link= URL+"index.php";

  $.ajax({
      type: "GET",
      url: link,
      data:  {url: 'ges_reportes/add_item/', Data : itemData}, 
      success: function(res){
          
       if (res == '1') {

        MSG_CORRECT('El item ha agregado exitosamente.',0); 

        get_PL(priceID);

             
        }else{

        MSG_ERROR(res,0); 

        }
     
        }
   });

}

function show_req(URL,id){


     var datos= "url=ges_requisiciones/get_req_info/"+id;

      
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


 function close_req(id){ 

var reason = document.getElementById('req_reason_close').value;
var URL    = document.getElementById('URL').value;
var datos  = "url=ges_requisiciones/set_reason_close/"+id+"/"+reason;

document.getElementById('info').value = 'Procesando cierre...';

r = confirm ('Esta seguro de  procesar el cierre definitivo de la requisicion "'+id+'" ?');

if(r==true){

   $.ajax({
         type: "GET",
         url: URL+'index.php',
         data: datos,
         success: function(res){

           alert('Se ha cerrado con exito la requisicion No. '+id);

           show_req(URL,id);

           
                 }
            });


  }
 }