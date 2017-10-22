<script type="text/javascript">


// ********************************************************
// * Aciones cuando la pagina ya esta cargada
// ********************************************************
$(window).load(function(){
   


   //lista jobs
     jobs();

    //lista phases
     

var table = $("#table_req_tb").dataTable({

      bSort: false,
      responsive: true,
      searching: false,
      paging:    false,
      info:      false,
      collapsed: false

 });


});





// Variables globales
URL = document.getElementById('URL').value;
link = URL+"index.php";

chk = '';
cantLineas = 500; //Setea la cantidad de lineas disponibles en la tabla de solicitud de items


JOBS = '';
PHASES = '';
CCOST = '';
//Variables globales

//datatables
/////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////////////////////////////
function jobs(){


/*JOBS*/
 var datos= "url=bridge_query/get_JobList";


	$.ajax({
	  type: "GET",
	  url: link,
	  data: datos,
	  success: function(res){

		JOBS = res;
        	    
	    if(res){
	    	    phase();
				}			

    		}
});/*JOBS*/

}
/////////////////////////////////////////////////////////////////////////////////////////////////

function phase(){

/*PHASES*/
 var datos= "url=bridge_query/get_phaseList";

		$.ajax({
			  type: "GET",
			  url: link,
			  data: datos,
			  success: function(res){

				PHASES = res;
                            
				if(res){
				init(1); //llamo a construir tabla
				}

                            

                
             }
   });/*PHASES*/

}
/////////////////////////////////////////////////////////////////////////////////////////////////
function ccost(){
/*CCOST*/
var datos= "url=bridge_query/get_costList";

		$.ajax({
			  type: "GET",
			  url: link,
			  data: datos,
			  success: function(res){

				CCOST = res;

                             
										      
     		}
});/*CCOST*/


}
/////////////////////////////////////////////////////////////////////////////////////////////////

function init(chk)

{

var listitem = '';
var i = 1;
var datos= "url=bridge_query/get_ProductsCode/";
var reglon = '';



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
						    '<select id="sel'+i+'" >'+
						        '<option value=""></option>'
						         +listitem+
						    '</select>'+
						   /* '<input id="inp'+i+'" type="text" name="format" value="" />'+*/
						 /*'</div>'+*/
						'</td>';

			 }        

			var line_table_req = '<tr>'+reglon+
		    '<td width="30%" class="rowtable_req" contenteditable></td>'+
			'<td width="15%" class="rowtable_req" contenteditable></td>'+
			'<td width="15%" class="rowtable_req" contenteditable></td>'+
			'<td width="15%" class="rowtable_req" contenteditable><select id="JOB'+i+'" ><option  value="-" selected>-</option>'+JOBS+'</select></td>'+
			'<td width="15%" class="rowtable_req" contenteditable><select id="PHS'+i+'" ><option  value="-" selected>-</option>'+PHASES+'</select></td>'+
			'</tr>' ;

			 i++
			 $('#table_req').append(line_table_req); //limpio la tabla 
			}

       
      }
     });


}


/////////////////////////////////////////////////////////////////////////////////////////////////
</script>


<div class="page col-lg-12">

<div  class="col-lg-12">
<!-- contenido -->
<h2>Requisiciones</h2>
<div class="title col-lg-12"></div>
<div class="separador col-lg-12"></div>

 <!-- FIN VENTANA -->

<input type="hidden" id='URL' value="<?php ECHO URL; ?>" />


	<div class="col-lg-6">
	   <button class="btn btn-blue btn-sm"  data-toggle="collapse" data-target="#Solicitud" onclick="javascript:  $(this).find('i').toggleClass('fa-plus-circle fa-minus-circle');"><i  class='fa fa-plus-circle'></i> Detalle de solicitud</button>
	   <input type="submit" onclick="send_req_order();" class="btn btn-primary  btn-sm btn-icon icon-right" value="Procesar" />
	  
	</div>


<div class="separador col-lg-12"></div>

<div id='Solicitud' class="collapse col-lg-6" >
	
<fieldset>
<input type="hidden" id='user' value="<?php echo $active_user_id; ?>" />

<div class="col-lg-12">
 <div   class="col-lg-6"> 
                     <label style="display:inline">Referencia </label>
    <INPUT class="input-control" type="text" name="Req_NO" id="Req_NO" readonly value="
     <?php echo  $this->model->Get_Req_No(); ?>" />
</div>

<div  class="col-lg-1"></div>

 <div   class="col-lg-5">
  <label style="display:inline" > Fecha : </label>
  <input style="text-align: center;" class="input-control" name="date" id="date" value="<?php echo date("Y-m-d"); ?>" readonly/>
  </div>

</div>


<div class="title col-lg-12"></div>

        
   	<div  class="separador col-lg-12"></div>
		
	 <div class="col-lg-12">
         <fieldset>
       	
         	<div class="comment-text-area col-lg-12">
         		<strong>Nota: </strong><textarea class="textinput" rows="5" cols="70" id="nota" name="nota">  </textarea>
        		
         	</div>
         </fieldset>
		</div> 
   
		

					
</fieldset>

</div>

<div class="separador col-lg-12"></div>		

<div class=" col-lg-12"> 

<fieldset class="table_req" >
<table id="table_req_tb" class="table table-striped table-condensed table-bordered " cellspacing="0">
	<thead>
		<tr >
			<th width="10%" >
			<select id="check_val" onchange="init(this.value);">
			<option value="1" >Reglon</option>
			<option value="2" >Codigo</option> 
			</select></th>
			<th width="30%" class="text-center">Descripcion</th>
			<th width="15%" class="text-center">Cantidad</th>
			<th width="15%" class="text-center">Unidad</th>
			<th width="15%" class="text-center">Proyecto</th>
			<th width="15%" class="text-center">Fase</th>
		</tr>
	</thead>
	<tbody id="table_req" >	

	</tbody>
</table>
</fieldset>

</div>
</div>
</div>




 <!-- FIN VENTANA -->
</div>
</div>


<script>
var falta = 1;
LineArray = [];
URL = document.getElementById('URL').value;



function set_job(jobid){

document.getElementById('jobID_db').value = jobid;


}

function set_phase(phaseid){

document.getElementById('phaseID_db').value = phaseid;


}

function set_cost(costid){

document.getElementById('costID_db').value = costid;


}



function send_req_order(){

var flag = '';
var count= 0;
var arrLen = '';

flag = set_items(); //GUARDO ITEM EN ARRAY 

if(flag==1){  //SI HAY ITEMS EN LA LISTA

     var r = confirm('Desea enviar esta requisicion ahora?');
    
		if (r == true) { 

		var link = URL+"index.php";

		//REGITRO DE CABECERA
		//var job = document.getElementById('jobID_db').value;
		//var phase = document.getElementById('phaseID_db').value;
       // var cost  = document.getElementById('costID_db').value;
        var Req_NO = document.getElementById('Req_NO').value;
        var nota = document.getElementById('nota').value;

  
		var datos= "url=bridge_query/set_req_header/"+Req_NO+"/"+nota; //LINK DEL METODO EN BRIDGE_QUERY

 					$.ajax({
								type: "GET",
								url: link,
								data: datos,
								success: function(res){

								
					            }
							});
		//FIN REGISTRO DE CABECERA

		// REGISTROS DE ITEMS 
			var arrLen = LineArray.length;
	        var Req_NO = Req_NO.trim();
	        
           console.log('Numero de registros: '+arrLen);

			$.each(LineArray, function(index,value) {//BUCLE PARA LEER CADA REGISTRO DE ITEM GUARDADO EN EL ARREGLO LineArray

                setTimeout(function(){ //Esta funcion aplica un retrso de 500mseg por cada ejecucion. 

                count++; //Contabiliza las lineas de registros que se mandan a procesar. 

				var datos= "url=bridge_query/set_req_items"+value+'/'+ Req_NO+'/'+count+'/'+arrLen;//LINK DEL METODO EN BRIDGE_QUERY
				
				if(value){
                  console.log(datos);

							$.ajax({
								type: "GET",
								url: link,
								data: datos,
								success: function(res){

									console.log('RES:'+res);
							      
									if(res==1){//TERMINA EL LLAMADO AL METODO set_req_items SI ESTE DEVUELV UN '1', indica que ya no hay items en el array que procesar.
									
									send_mail(link,Req_NO);

										
									}

					            }
							}); 


				}
         
				}, 500);
																			    
			}); 
    //FIN REGISTROS DE ITEMS

  }

}
if(flag==0){ 
	alert('Debe llenar la solicitud con almenos un item en la lista'); 
}
								
}
	
function send_mail(link,Req_NO){

    //ENVIO POR MAIL 
	var datos= "url=ges_requisiciones/req_mailing/"+Req_NO; //LINK A LA PAGINA DE MAILING
    

	$.ajax({
		type: "GET",
		url: link,
		data: datos,
		success: function(res){

									      
			if(res==0){

			 alert('NO SE HA PODIDO ENVIAR LA NOTIFICACION DE ORDEN DE COMPRA.');
			 
			}

			 msg(link,Req_NO);

		}
	}); 
	//FIN ENVIO POR MAIL 


}		 			

//FUNCION PARA SOLICITAR IMPRESION DEL REPORTE
function msg(link,Req_NO){


   alert("La orden se ha enviado con exito");

	var R = confirm('Desea imprimir la orden de venta?');

	if(R==true){

         count = 1;
         LineArray.length='';
         window.open(link+'?url=ges_requisiciones/req_print/'+Req_NO,'_self');
                 
    }else{

		count = 1;
	    LineArray.length='';
		location.reload();

	}



}





//FUNCION PARA GUARDAR ITEMS EN ARRAY 
function set_items(){

LineArray.length=''; //limpio el array

var flag = ''; 
var theTbl = document.getElementById('table_req'); //objeto de la tabla que contiene los datos de items
var chk = document.getElementById('check_val').value;    //Valor que determina si es solicitud por Reglon o por Codigo de items
var line = '';

for(var i=0; i<cantLineas ;i++) //BLUCLE PARA LEER LINEA POR LINEA LA TABLA theTbl
{
	cell = '';
	y='';

    for(var j=0;j<theTbl.rows[i].cells.length; j++) //BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA
        {

              y=i+1;
    	  	  var selid = "sel"+y;
    	  	  var jobid = "JOB"+y;
    	  	  var phsid = "PHS"+y;
    	      var ccoid = "CCO"+y;
    	    
    	  if(chk==2){//CHECK 2 SON PARA RESGISTROS POR CODIGO DE PRODUCTOS

/*    	  	  y=i+1;
    	  	  var selid = "sel"+y;
    	  	  var jobid = "JOB"+y;
    	  	  var phsid = "PHS"+y;
    	      var ccoid = "CCO"+y;*/
			  var cell1 =document.getElementById(selid).value;
    	 
			  if(j==0){//leeo la columna 0   

				    if(cell1!=''){  //valido que la columna 0 sea diferente a vacio  	

				    	val= cell1.replace('/','@');

			             cell += '/'+val; //agrego el registo de la col 0
	   
					  }
	        
			    }else{

				    if(cell1!=''){  //valido que la columna 0 sea diferente a vacio  	

		                //leer columnas de jobs
    	  	      	switch (j){

                       case 4:
                             cell += '/'+document.getElementById(jobid).value+'/'+document.getElementById(jobid).options[document.getElementById(jobid).selectedIndex].text;
                             
                             break;                

                       case 5:
                           cell += '/'+document.getElementById(phsid).value+'/'+document.getElementById(phsid).options[document.getElementById(phsid).selectedIndex].text+'//';
                              break;

       /*                case 6:
                           cell += '/'+document.getElementById(ccoid).value+'/'+document.getElementById(ccoid).options[document.getElementById(ccoid).selectedIndex].text;
                              break;*/

                       default: 

                            val= theTbl.rows[i].cells[j].innerHTML.replace('/','@');

                             cell += '/'+val;//agrego el registo de las demas columnas
                             break;
                            }
                     //fin leer columnas de jobs
		            }
     	           
			    }   

    	  }else{//si es por reglon

    	  	      if(theTbl.rows[i].cells[1].innerHTML!=''){ //valido que la columna 1 (DESCRIPCION) sea diferente a vacio.	

                    //leer columnas de jobs
    	  	      	switch (j){

                       case 4:
                             cell += '/'+document.getElementById(jobid).value+'/'+document.getElementById(jobid).options[document.getElementById(jobid).selectedIndex].text;
                             
                             break;                

                       case 5:
                           cell += '/'+document.getElementById(phsid).value+'/'+document.getElementById(phsid).options[document.getElementById(phsid).selectedIndex].text+'//';
                              break;

                /*       case 6:
                           cell += '/'+document.getElementById(ccoid).value+'/'+document.getElementById(ccoid).options[document.getElementById(ccoid).selectedIndex].text;
                              break;*/

                       default: 

                             val= theTbl.rows[i].cells[j].innerHTML.replace('/','@');

                             cell += '/'+val;//agrego el registo de las demas columnas

                            break;
                            }
                     //fin leer columnas de jobs
    	  	      	

    	  	      }
    	  	  }

        }//FIN BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA

      	
      	if(chk==1){ //INSERTA valor de CELL en el arreglo si es por REGLON

	    	if (theTbl.rows[i].cells[1].innerHTML!=''){

        
		       LineArray[i]=cell; 

		       console.log(LineArray);

			    
		     }
		}

        
        if(chk==2){ //INSERTA valor de CELL en el arreglo si es por CODIGO


              if (cell1!=''){ //Inserta si el valos de la columna 0 no es vacio

					if (theTbl.rows[i].cells[1].innerHTML!=''){ // Verifica que la linea tenga descripcion

						theTbl.rows[i].style.backgroundColor = '';
	   
		                LineArray[i]=cell;  //INSERTA EL VALIR DE CELL AL ARREGLO

						console.log('LINE '+i+' DATO: '+cell);

                        falta=1;

					}else{//Si la descripcion esta vacia

						r = i+1;
                       
                        alert('Falta la Descricion en la fila '+r+' ');
						theTbl.rows[i].style.backgroundColor = '#F5A9A9'; //Marca la linea en color rojo
						
						falta = 0;     	
                        break;
					}

				}
		}

}//FIN BLUCLE PARA LEER LINEA POR LINEA DE LA TABLA 


//SETEA RETURN DE LA FUNCION, FLAG 1 Ó 0, SI ES 1 LA TABLA ESTA LLENA SI ES 0 LA TABLA ESTA VACIA.
if(falta==1){

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



</script>