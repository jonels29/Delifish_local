// ********************************************************
// * Aciones cuando la pagina ya esta cargada
// ********************************************************
$(window).load(function(){

$('#ERROR').hide();

REQ_ID = document.getElementById('reqidhide').value;

//console.log(REQ_ID);

  if(REQ_ID!='0'){

  document.getElementById('buscar').value = REQ_ID  ;

  get_reception();

  }

});

//VALIABLES GLOBALES
LineArray = [];
FaltaArray  = [];
count='';

URL = document.getElementById('URL').value;
link= URL+"index.php";
//VALIABLES GLOBALES


function get_reception(){
$('#ERROR').hide();

id = document.getElementById('buscar').value;

$('#info').html('');

var datos= "url=ges_requisiciones/get_reception/"+id;

console.log(datos);

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){

    
        $('#info').html(res);
        
        spin_hide();
       

        }
   });



}



function save(){

//ACTIVA SPIN MIENTRAS REALIZA EL PROCESO
spin_show();
///////////////////////////////////////////////////////////////////////

$('#ERROR').hide();

set_lines = set_count_lines(); //setea el numero de lineas que tiene la requisicion


setTimeout(function(){

if(set_lines == true){


flag = set_items(); //GUARDO ITEM EN ARRAY 



  if(flag==1){  //SI HAY ITEMS EN LA LISTA

    // REGISTROS DE ITEMS 
          var arrLen = LineArray.length;
          var Req_NO = document.getElementById('buscar').value;

          count=0;

          $.each(LineArray, function(index,value) {//BUCLE PARA LEER CADA REGISTRO DE ITEM GUARDADO EN EL ARREGLO LineArray

            setTimeout(function(){ //Esta funcion aplica un retrso de 500mseg por cada ejecucion. 
 
 
          if(value){            
              count++; //Contabiliza las lineas de registros que se mandan a procesar. 

            var datos= "url=ges_requisiciones/set_req_recept"+value+'/'+Req_NO+'/'+count+'/'+arrLen;//LINK DEL METODO EN BRIDGE_QUERY
           
            console.log(datos);             
            if(value){

                      $.ajax({
                        type: "GET",
                        url: link,
                        data: datos,
                        success: function(res){

                          //console.log('RES:'+res);
                          //alert(res);
                            
                          if(res==1){//TERMINA EL LLAMADO AL METODO set_req_items SI ESTE DEVUELV UN '1', indica que ya no hay items en el array que procesar.
                          
                             count = 1;
                             LineArray.length='';
                             get_reception();


                          }

                        }
                      }); 



            }

}
                 
            }, 500);
                                              
          }); 
        //FIN REGISTROS DE ITEMS

}


  
if(flag==0){ //SI NO HAY ITEMS EN LA LISTA

//TERMINA SPIN/////////////////////////////////////////////////////
 spin_hide();
////////////////////////////////////////////////////////////////////

  MSG_ERROR('No existen cambios que guardar',0); 

    return;
}

if(flag==2){ //MANEJO DE ERRORES POR FAMPO FALTANTES EN LOS ITEMS

MSG_ERROR_RELEASE(); //LIMPIO DIV DE ERRORES

FaltaArray.forEach(Errores);

//TERMINA SPIN/////////////////////////////////////////////////////
 spin_hide();
////////////////////////////////////////////////////////////////////

function Errores(msg,index){

      MSG_ERROR('ERROR en el Item: '+index+" : "+msg, 1);  

  }

FaltaArray.length = ''; //LIMPIO ARRAY DE ERRORES

}

    

}

}, 500);

}





//FUNCION PARA GUARDAR ITEMS EN ARRAY 
function set_items(){

LineArray.length=''; //limpio el array
FaltaArray.length='';

var flag = ''; 
var theTbl    = document.getElementById('table_info'); //objeto de la tabla que contiene los datos de items
var cantLineas = document.getElementById('count_lines').value;
var rec = 0;
var val = 0;
var ord = 0;


var i=1;
var y=0;
//BLUCLE PARA LEER LINEA POR LINEA LA TABLA 

//alert(cantLineas);

while (i <= cantLineas){

          lineid = 'ID'+i;
          REG =  document.getElementById(lineid).innerHTML;

          QTYRCV = 'QTYRCV'+REG;
          QTYORD = 'QTYORD'+REG;
          QTYVAL = 'rec'+REG;
          
          rec = document.getElementById(QTYRCV).value;
          ord = document.getElementById(QTYORD).value;
          val = document.getElementById(QTYVAL).value;

          console.log('a:'+rec+' b:'+ord+' c:'+val);

          cell = '';
        
         
           if(Number(val)!=''){
                
            console.log('c:'+val);
       
                             cell += '/'+REG;//agrego el registo de las demas columnas                              
                             
                             val  = Number(val);
                             REST = Number(ord) - Number(rec); 

                                                                               
                             compare = (parseFloat(val) > parseFloat(REST));

                             //console.log(compare);

                             if(compare){
                              
                                  FaltaArray[i] = 'Cantidad recibida es mayor a la suma de las cantidades ordenadas' ;
                              }

                              compare = (parseFloat(val) <= 0.00);

                              if(compare){
                              
                                  FaltaArray[i] = 'No se permiten valores negativos o nulos' ;
                              }

                            
                             cell += '/'+val;//agrego el registo de las demas columnas                              
                             
         //INSERTA valor de CELL en el arreglo 
         LineArray[y]=cell;
        
      y++;
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

function set_count_lines(){

    var Req_NO = document.getElementById('buscar').value;
    var datos= "url=ges_requisiciones/get_req_item_lines/"+Req_NO;//LINK DEL METODO EN BRIDGE_QUERY

        $.ajax({
                    type: "GET",
                    url: link,
                    data: datos,
                    success: function(res){

                    
                    document.getElementById('count_lines').value = res;

                    }
              }); 

return true;
 
}
