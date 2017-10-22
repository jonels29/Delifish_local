
<input type="hidden" id="FAC_NO_LINES" value="" />

<script type="text/javascript">

// ********************************************************
// * Aciones cuando la pagina ya esta cargada
// ********************************************************

$(window).load(function(){

 $('#ERROR').hide();

 // Variables globales
URL = document.getElementById('URL').value;
link = URL+"index.php";
chk = '';
NO_LINES = '';
cantLineas = ''; //Setea la cantidad de lineas disponibles en la tabla de solicitud de items
//Variables globales


JOBS = '';
PHASES = '';
CCOST = '';


//SET LINES
set_fact_lines();



   //lista jobs
     jobs();

    //lista phases

    var table = $("#table_req_tb").DataTable({
      

      bSort: false,
      responsive: true,
      searching: false,
      paging:    false,
      info:      false,
      collapsed: false

  });



});







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
			     ccost();
				
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
			
			if(res){
			    init(1); //llamo a construir tabla  
				
				}
                           
										      
     		}
});/*CCOST*/


}
/////////////////////////////////////////////////////////////////////////////////////////////////

function release_po_no(val){

$("#ordc").select2("val", "");

init(val);  

}

function init(chk){


var listitem = '';
var i = 1;
var datos= "url=bridge_query/get_ProductsCode/";
var reglon = '';



set_fact_lines();

setTimeout(function(){ 

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
						    '<select id="sel'+i+'" onchange="SetDesc(this.value,'+i+')">'+
						        '<option value=""></option>'
						         +listitem+
						    '</select>'+
						   /* '<input id="inp'+i+'" type="text" name="format" value="" />'+*/
						 /*'</div>'+*/
						'</td>';  
			
			

			 }        

			 var descid = "'desc"+i+"'"; 

			var line_table_req = '<tr>'+reglon+
		    '<td width="30%" class="rowtable_req" onkeyup="checkChar('+descid+');" contenteditable id="desc'+i+'"  ></td>'+
			'<td width="5%" class="rowtable_req"  contenteditable id="unit'+i+'"></td>'+
			'<td width="5%" class="rowtable_req  numb" onfocusout="recalcular('+i+');" contenteditable id="qty'+i+'"></td>'+
			'<td width="5%" class="rowtable_req  numb" onfocusout="calculate('+i+');" contenteditable id="unitprice'+i+'" ></td>'+
			'<td width="10%" class="rowtable_req numb" id="total'+i+'" ></td>'+
			'<td width="10%" class="rowtable_req numb" contenteditable><select id="JOB'+i+'" ><option  value="-" selected>-</option>'+JOBS+'</select></td>'+
			'<td width="10%" class="rowtable_req numb" contenteditable><select id="PHS'+i+'" ><option  value="-" selected>-</option>'+PHASES+'</select></td>'+
			'<td width="10%" class="rowtable_req numb" contenteditable><select id="CCO'+i+'" ><option  value="-" selected>-</option>'+CCOST+'</select></td>'+
			'</tr>' ;

			 i++
			 $('#table_req').append(line_table_req); //limpio la tabla 
			}

       
      }
     });



  },500);




}

function PO_Filter(vendorid){

$('#ERROR').hide();

$('#ordc').html('');
$("#ordc").select2("val", "");


var datos= "url=bridge_query/PO_filter_by_Vendor/"+vendorid;

if(vendorid != ''){

$.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){

      $('#ordc').html(res);

     } 
 });

}
}


//IF pPO IS SELECTED
function PO_SELECTED(id)
{
$('#ERROR').hide();

var listitem = '';
var i = 1;
var datos= "url=bridge_query/GET_PO_FOR_FACT/"+id;
var reglon = '';

if(id!=''){

$.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){

      $('#table_req').html(res);

     } 
 });

var datos= "url=bridge_query/GET_PO_FOR_LINES/"+id;

$.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){

      $('#FAC_NO_LINES').val(res);

     } 
 });

setTimeout(function(){ 
NO_LINES = document.getElementById('FAC_NO_LINES').value;
cantLineas = NO_LINES; //Setea la cantidad de lineas disponibles en la tabla de solicitud de items
//Variables globales
},500);
document.getElementById('check_val').value = '2';



}else{

init(1);

}



document.getElementById('subtotal').value = 0;
document.getElementById('tax').value = 0 ;
document.getElementById('total').value = 0;

setTimeout(function(){


sumar_total(); 

},500);




}

function set_fact_lines(){


var datos= "url=bridge_query/GET_FACT_LINES";

$.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){

      console.log(res);

      $('#FAC_NO_LINES').val(res);


     } 
 });


setTimeout(function(){ 
NO_LINES = document.getElementById('FAC_NO_LINES').value;
cantLineas = NO_LINES; //Setea la cantidad de lineas disponibles en la tabla de solicitud de items
//Variables globales
},500);


}

function valid(line,max){

$('#ERROR').hide();

var ID = 'qty'+line;

var input_qty = document.getElementById(ID).innerHTML;

if(input_qty!=''){

    var compare = (parseInt(max) >= parseInt(input_qty));

    if(!compare){

       MSG_ERROR('El valor de la cantidad no debe exceder al valor maximo propuesto :'+max,0);
     
       document.getElementById(ID).innerHTML = max;

       recalcular(line);

     }else{

      recalcular(line);
     }

}







}

function SetDesc(itemId, line){

var datos= "url=bridge_query/get_ProductsInfo/"+itemId;

var id_desc_field = 'desc'+line;
var id_unit_field = 'unit'+line;
var id_job_field = 'JOB'+line;
var id_phs_field = 'PHS'+line;
var id_cco_field = 'CCO'+line;
var id_price_field = 'unitprice'+line;
var id_qty_field = 'qty'+line;
var id_total_field = 'total'+line;

$.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){

       json = JSON.parse(res);

       document.getElementById(id_unit_field ).innerHTML = json.UnitMeasure;
       document.getElementById(id_desc_field).innerHTML  = json.Description;

     }


});

 if(itemId==''){

         document.getElementById(id_price_field).innerHTML  = '0.00';
         recalcular(line);
         
         document.getElementById(id_qty_field).innerHTML  = '';
         document.getElementById(id_total_field).innerHTML  = '';       
         document.getElementById(id_unit_field ).innerHTML = '';
         document.getElementById(id_desc_field).innerHTML  = '';
         document.getElementById(id_price_field).innerHTML  = '';
         document.getElementById(id_job_field).value  = '-';
         document.getElementById(id_phs_field).value  = '-';
         document.getElementById(id_cco_field).value  = '-';


  }

}

function recalcular(line){

PriceID = 'unitprice'+line;
QTYID = 'qty'+line; 

chkinput = checkQtyInput(QTYID);
if (chkinput != 0){ return; }


    UnitPrice = document.getElementById(PriceID).innerHTML;
	if(UnitPrice!=''){
      calculate(line);
	}


}

function calculate(line){


qtyID = 'qty'+line;
PriceID = 'unitprice'+line;
totalID = 'total'+line;

chkinput = checkQtyInput(PriceID);
if (chkinput != 0){  return false; }

qty = document.getElementById(qtyID).innerHTML;
UnitPrice = document.getElementById(PriceID).innerHTML;

if(qty=='' || UnitPrice == ''){

qty = 0;
UnitPrice = 0;

}

total = qty * UnitPrice;

document.getElementById(totalID).innerHTML = parseFloat(total).toFixed(5); 
document.getElementById(qtyID).innerHTML =   parseFloat(qty).toFixed(5);
document.getElementById(PriceID).innerHTML = parseFloat(UnitPrice).toFixed(4);


sumar_total();

}


function sumar_total(){

var theTbl = document.getElementById('table_req_tb'); //objeto de la tabla que contiene los datos de items
var l = '';  
var total = [];
//var itbms = [];

subtotal_field =  document.getElementById('subtotal');
tax_field =     document.getElementById('tax');
tax_value =   document.getElementById('tax').value;
total_field = document.getElementById('total');

for(var i=1; i<theTbl.rows.length ;i++) //BLUCLE PARA LEER LINEA POR LINEA LA TABLA theTbl
{
  l = 1 + l; //contador de registros
  ITEMid = 'sel'+i;
  ITEM_ID = '';
         
    for(var j=0;j<theTbl.rows[i].cells.length; j++) //BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA
        {       
         
            switch (j){

                   case 5:

                    total.push(theTbl.rows[i].cells[j].innerHTML);
                    break;
            }
                   
           }//FIN BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA
       
}//FIN BLUCLE PARA LEER LINEA POR LINEA DE LA TABLA

var subtotal  = 0;
//var TAX  = 0;

for(var i=0; i<total.length; i++){
    subtotal  += Number(total[i]);
}


/*for(var i=0; i<itbms.length; i++){
    TAX    += Number(itbms[i]);
}
    
    TAX = subtotal*TAX*/

    TOTAL = subtotal + Number(tax_value);


 	subtotal_field.value = parseFloat(subtotal).toFixed(2);
	tax_field.value      = parseFloat(Number(tax_value)).toFixed(2);
	total_field.value   =  parseFloat(TOTAL).toFixed(2);



}

/////////////////////////////////////////////////////////////////////////////////////////////////
</script>

<div class="page col-lg-12">


<!--INI DIV ERRO-->
<div id="ERROR" class="alert alert-danger"></div>
<!--INI DIV ERROR-->

<div  class="col-lg-12">
<!-- contenido -->
<h2>Compras/Recibo de mercancia</h2>
<div class="title col-lg-12"></div>

<div class="col-lg-12">

 <!-- INI VENTANA -->

<input type="hidden" id='URL' value="<?php ECHO URL; ?>" />


	<div class="col-lg-6">
	   <button class="btn btn-blue btn-sm"  data-toggle="collapse" data-target="#Solicitud" onclick="javascript:  $(this).find('i').toggleClass('fa-plus-circle fa-minus-circle');"><i  class='fa fa-minus-circle'></i> Detalle de Factura</button>
	   <input type="submit" onclick="send_fact();" class="btn btn-primary  btn-sm btn-icon icon-right" value="Procesar" />
	  
	</div>


<div class="separador col-lg-12"></div>

<div id='Solicitud' class="collapse in col-lg-6" >
	
<fieldset>
<input type="hidden" id='user' value="<?php echo $active_user_id; ?>" />

<div class="col-lg-12">
 <div   class="col-lg-6"> 
                     <label style="display:inline">Referencia </label>
    <INPUT class="input-control" type="text" name="FACT_NO" id="FACT_NO" readonly value="
     <?php echo  $this->model->Get_CO_No(); ?>" />
</div>

<div  class="col-lg-1"></div>

 <div   class="col-lg-5">
  <label style="display:inline" > Fecha : </label>
  <input style="text-align: center;" type="date" class="input-control" name="date" id="date" value="<?php echo date('Y-m-d');?>" />
  </div>

</div>


<div class="title col-lg-12"></div>
         <div class="col-lg-8">
         <fieldset>
			<p><strong>Proveedor</strong></p>
				
			<select  id="vendor" name="vendor" class="select col-lg-8" onchange="PO_Filter(this.value);" required>

				<option selected disabled></option>

				<?php  
				$vendor = $this->model-> get_VendorList(); 

				foreach ($vendor as $datos) {
																	
				$VENDOR_INF = json_decode($datos);
				echo '<option value="'.$VENDOR_INF ->{'VendorID'}.'" >'.$VENDOR_INF->{'Name'}."</option>";

				}
				?>
										
			</select>	
		 </fieldset>
        </div>
		 <div class="col-lg-4">
         <fieldset>
         	
         	<div class="col-lg-12">
         		<strong>No. Factura </strong><input type="text" id="nopo" name="nopo" /><br>
         		
         	</div>
         </fieldset>
		</div> 
		 <div class="col-lg-4">
         <fieldset>
         	
         	<div class="col-lg-12">
         		<strong>Ord. Compra</strong>

         		<select  id="ordc" name="ordc" class="select col-lg-12"  onchange="PO_SELECTED(this.value);" required>

				<option selected disabled></option>

				<!-- este select se va llenar dependiendo del vendor seleccionado -->

			</select>	
         		
         	</div>
         </fieldset>
		</div> 

<div class="title col-lg-12"></div>        
   	<div  class="separador col-lg-12"></div>
		
	 <div class="col-lg-12">
         <fieldset>
       	
         	<div class="comment-text-area col-lg-12">
         		<strong>Observaciones: </strong><textarea class="textinput" rows="5" cols="70" id="nota" name="nota">  </textarea>
        		
         	</div>
         </fieldset>
		</div> 
   
		

					
</fieldset>

</div>

<div class="separador col-lg-12"></div>		

<div class=" col-lg-12"> 

<fieldset class="table_req" >
<table id="table_req_tb" class="display table table-striped table-condensed table-bordered " cellspacing="0">
	<thead>
		<tr >
			<th width="10%" >
			<select id="check_val" onchange="release_po_no(this.value);">
			<option value="1" >Reglon</option>
			<option value="2" >Codigo</option> 
			</select></th>
			<th width="30%" class="text-center">Descripcion</th>
 			<th width="15%" class="text-center">Unidad</th>
			<th width="15%" class="text-center">Cantidad</th>
			<th width="15%" class="text-center">Precio Unit.</th>
			<th width="15%" class="text-center">Total</th>
 			<th width="10%" class="text-center">Proyecto</th>
			<th width="10%" class="text-center">Fase</th>
			<th width="10%" class="text-center">Ctr. Costo</th>
		
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
	<input class="col-lg-12"  style="text-align:right;" type="number" step="0.01" id="subtotal" name="subtotal"  value="0" readonly />
    <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="tax" name="tax" value="0" onfocusout='sumar_total();' /> 
    <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="total" name="total" value="0" readonly />
</div>
</fieldset>
</div>
<div  class="separador col-lg-12" ></div>


</div>
</div>
</div>



</div>
</div>
</div>

<script type="text/javascript">

//VALIABLES GLOBALES
CHK_VALIDATION = false;
vendorID = '';
falta = 1;
LineArray = [];
//VALIABLES GLOBALES

function validacion(){

 MSG_ERROR_RELEASE();

    //VALIDAR VENDOR
	vendorID = document.getElementById('vendor').value;

	if (vendorID == ''){

	 MSG_ERROR('Se debe indicar el Proveedor',1);
	 
	 CHK_VALIDATION = true;
	}


    //VALIDAR fecha
	date = document.getElementById('date').value;

	if (date == ''){

	 MSG_ERROR('Se debe indicar la fecha de la factura',1);
	 
	 CHK_VALIDATION = true;
	}

    //VALIDAR FACT NUMBRE
	nofact = document.getElementById('nopo').value;

	if (nofact == ''){

	 MSG_ERROR('Se debe indicar el numero de la factura',1);
	 
	 CHK_VALIDATION = true;
	}


}



function send_fact(){




/////////////////////////////
//variables internas
var flag = '';
var count= 0;
var arrLen = '';
////////////////////////////

//////////////////////////////
validacion();

if(CHK_VALIDATION == true){ CHK_VALIDATION = false;  return;  }else{   $('#ERROR').hide();  }
/////////////////////////////



//AGRUPO LAS LINEAS DE ITEMS EN ARRAY
flag = set_items();

if(flag==1){  //SI HAY ITEMS EN LA LISTA

///////////////////////////////////////////
//SE PROCESA EL REGISTRO EN BD

var r = confirm('Desea registra la compra ahora?');
    
		if (r == true) { 

		var link    = URL+"index.php";
        var FACT_NO = document.getElementById('FACT_NO').value;
        var po_no   = document.getElementById('nopo').value;
        var nota    = document.getElementById('nota').value;
        var total   = document.getElementById('total').value;


        var date    = document.getElementById('date').value
            date    = formatDate(date);

        var lines_type   = document.getElementById('check_val').value;

        //INI REGISTRO DE CABECERA
		var datos= "url=bridge_query/set_fact_header/"+FACT_NO+"/"+vendorID+'/'+po_no+'/'+nota+"/"+total+'/'+date; //LINK DEL METODO EN BRIDGE_QUERY
        console.log(datos);

 					$.ajax({
								type: "GET",
								url: link,
								data: datos,
								success: function(res){

                                   console.log(res);
								
					            }
							});
		//FIN REGISTRO DE CABECERA

		//INI REGISTROS DE ITEMS 
			var arrLen = LineArray.length;
	        var FACT_NO = FACT_NO.trim();
	        var PO_NO = document.getElementById('ordc').value;
	        
            //console.log('Numero de registros: '+arrLen);


			$.each(LineArray, function(index,value) {//BUCLE PARA LEER CADA REGISTRO DE ITEM GUARDADO EN EL ARREGLO LineArray

                setTimeout(function(){ //Esta funcion aplica un retrso de 500mseg por cada ejecucion. 

                count++; //Contabiliza las lineas de registros que se mandan a procesar. 

				var datos= "url=bridge_query/set_fact_items/"+PO_NO+''+value+'/'+FACT_NO+'/'+count+'/'+arrLen+'/'+lines_type;//LINK DEL METODO EN BRIDGE_QUERY

				
				if(value){
                  console.log(datos);

							$.ajax({
								type: "GET",
								url: link,
								data: datos,
								success: function(res){

									console.log('RES:'+res);
							      
									if(res==1){//TERMINA EL LLAMADO AL METODO set_fact_items SI ESTE DEVUELV UN '1', indica que ya no hay items en el array que procesar.
									
									insert_itbms(FACT_NO);
									//send_mail(link,FACT_NO);
								    //msg(link,FACT_NO);
                                    
										
									}

					            }
							}); 


				}
         
				}, 500);
																			    
			}); 
        //FIN REGISTROS DE ITEMS

  }




///////////////////////////////////////////
}
if(flag==0){ //SI NO HAY ITEMS EN LA LISTA

	MSG_ERROR('No se han indicado items',0); 
    return;
}


}

function insert_itbms(FACT_NO){

itbms_val = document.getElementById('tax').value;
lines_type   = document.getElementById('check_val').value;

var datos= "url=bridge_query/set_fact_items//ITBMS/ITBMS/UND/1/1/"+itbms_val +"///////"+FACT_NO+'/1/1/'+lines_type ;//LINK DEL METODO EN BRIDGE_QUERY				


$.ajax({
								type: "GET",
								url: link,
								data: datos,
								success: function(res){

									//console.log('RES:'+res);
							      
									if(res==1){//TERMINA EL LLAMADO AL METODO set_fact_items SI ESTE DEVUELV UN '1', indica que ya no hay items en el array que procesar.
									
									send_mail(link,FACT_NO);
								    //msg(link,FACT_NO);
                                    
										
									}

					            }
							}); 





}

function send_mail(link,FACT_NO){

    //ENVIO POR MAIL 
	var datos= "url=ges_compras/fact_mailing/"+FACT_NO; //LINK A LA PAGINA DE MAILING
    

	$.ajax({
		type: "GET",
		url: link,
		data: datos,
		success: function(res){

									      
			if(res==0){

			 alert('NO SE HA PODIDO ENVIAR LA NOTIFICACION POR CORREO.');
			 
			}

			 msg(link,FACT_NO);

		}
	}); 
	//FIN ENVIO POR MAIL 

}		 			

//FUNCION PARA SOLICITAR IMPRESION DEL REPORTE
function msg(link,FACT_NO){


   alert("Los registros se han enviado con exito");

	var R = confirm('Desea imprimir la factura de compra?');

	if(R==true){

         count = 1;
         LineArray.length='';
         window.open(link+'?url=ges_compras/print_fact/'+FACT_NO,'_self');
                 
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
var theTbl = document.getElementById('table_req_tb'); //objeto de la tabla que contiene los datos de items
var chk    = document.getElementById('check_val').value;    //Valor que determina si es solicitud por Reglon o por Codigo de items
var line   = '';

console.log('lineas: '+cantLineas);
for(var i=1; i<=cantLineas; i++) //BLUCLE PARA LEER LINEA POR LINEA LA TABLA theTbl
{
	cell = '';
	y='';

    for(var j=0;j<theTbl.rows[i].cells.length; j++) //BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA
        {

              y=i;
    	  	  var selid = "sel"+y;
    	  	  var jobid = "JOB"+y;
    	  	  var phsid = "PHS"+y;
    	      var ccoid = "CCO"+y;
    	    
    	  if(chk==2){//CHECK 2 SON PARA RESGISTROS POR CODIGO DE PRODUCTOS

    	  	  console.log(selid);

			  var cell1 = document.getElementById(selid).value;
    	 
			  if(j==0){//leeo la columna 0   

				    if(cell1!=''){  //valido que la columna 0 sea diferente a vacio  	

				    	 val= cell1.replace('/',' ');

			             cell += '/'+val; //agrego el registo de la col 0
	   
					  }
	        
			    }else{

				    if(cell1!=''){  //valido que la columna 0 sea diferente a vacio  	

		                //leer columnas de jobs
    	  	      	switch (j){

                       case 6:
                             cell += '/'+document.getElementById(jobid).value+'/'+document.getElementById(jobid).options[document.getElementById(jobid).selectedIndex].text;
                             
                             break;                

                       case 7:
                           cell += '/'+document.getElementById(phsid).value+'/'+document.getElementById(phsid).options[document.getElementById(phsid).selectedIndex].text;
                              break;

                       case 8:
                           cell += '/'+document.getElementById(ccoid).value+'/'+document.getElementById(ccoid).options[document.getElementById(ccoid).selectedIndex].text;
                              break;

                       default: 

                             val= theTbl.rows[i].cells[j].innerHTML.replace('/',' ');

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

                       case 6:
                             cell += '/'+document.getElementById(jobid).value+'/'+document.getElementById(jobid).options[document.getElementById(jobid).selectedIndex].text;
                             
                             break;                

                       case 7:
                           cell += '/'+document.getElementById(phsid).value+'/'+document.getElementById(phsid).options[document.getElementById(phsid).selectedIndex].text;
                              break;

                       case 8:
                           cell += '/'+document.getElementById(ccoid).value+'/'+document.getElementById(ccoid).options[document.getElementById(ccoid).selectedIndex].text;
                              break;

                       default: 

                             val= theTbl.rows[i].cells[j].innerHTML.replace('/',' ');

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
                       
                        MSG_ERROR('Falta la Descricion en la fila '+r+' ',0);
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
//FIN ITEMS


</script>
