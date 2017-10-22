 // ********************************************************
// * Aciones cuando la pagina ya esta cargada
// ********************************************************
$(window).load(function(){

$('#ERROR').hide();

URL = document.getElementById('URL').value;
link = URL+"index.php";

//set_listprice();
SetSoToAdd();

});




//VALIABLES GLOBALES
CHK_VALIDATION = false;
vendorID = '';
falta = 0;
LineArray = [];
FaltaArray = [];
FUSION = [];
URL   = $('#URL').val();
link = URL + 'index.php';



function ShowModal(ref){

  if(ref!=''){
  	$('#fusion').modal('show');
    $('#aciref').val(ref);
 

	var datos= "url=ges_invoice/GetOrdrDetail2Fusion/"+ref ;

		$.ajax({
				type: "GET",
				url: link,
				data: datos,
				success: function(res){

			    	$("#ModalDetail").html(res);
		            
				}

		 }); 
	}


}

function AddLineToDetail(){

var theTbl = document.getElementById('DetailFusion'); //objeto de la tabla que contiene los datos de items
var line = '';
var i=1;
var n=0;
var x = document.getElementById('NO_LINES').value; 
var aciRef = $("#aciref").val();
//BLUCLE PARA LEER LINEA POR LINEA LA TABLA 


while (i <= theTbl.rows.length-1){

	for(var j=0;j<theTbl.rows[i].cells.length; j++) //BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA
	{
        checkID = '#check'+i;
        switch (j){

                case 5:

                    line = '';

                    if ($(checkID).is(":checked"))
					{

	                    ItemID     = theTbl.rows[i].cells[1].innerHTML;
	                    Desc       = theTbl.rows[i].cells[2].innerHTML;
	                    taxable    = theTbl.rows[i].cells[3].innerHTML;
	                    Ordenado   = theTbl.rows[i].cells[4].innerHTML;
	                    price      = theTbl.rows[i].cells[5].innerHTML;
	                   
	                    n = Number(i) + Number(x);

	                    line = "<tr><td >"+ItemID+'('+aciRef+")</td>"+
						         "<td contenteditable >"+Desc+"</td>"+
						         "<td >"+taxable+"</td>"+
						         "<td class='numb' id='qty"+n+"'>"+Ordenado+"</td>"+
						         "<td class='numb' contenteditable onfocusout='calculate("+n+");' id='desp"+n+"'></td>"+
						         "<td class='numb'  id='unitprice"+n+"'>"+price +"</td>"+
						         "<td class='numb'  id='total"+n+"'></td>"+
						         "<td class='numb' ><i onclick='del_tr(this)' style='color:red;' class='fa fa- fa-trash-o' ></i></td>"+
						     "</tr>";
                    }

                   break;

                  }  

       } //FIN BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA
       
       if(line != ''){

       	$("#invoice").append(line);
       	$("#NO_LINES").val(n);
       
       }    
      //INSERTA valor de CELL en el arreglo 
i++;       
}//FIN BLUCLE PARA LEER LINEA POR LINEA DE LA TABLA 

if(n>0){

	SetToFusion($("#NO_LINES").val(),aciRef);
	$("#DetailFusion").html('');
	$('#addSoLines option[value='+aciRef+']').remove();
    $('#addSoLines').select2('val','');
}

}

function SetToFusion(n,id){

 FUSION[n]= id ;


}

function ShowOrderToCancel(){


if(FUSION.length > 0 ){

	$('#cancelar').modal('show');
    $('#table_aciref').html('');

    arr = FUSION.filter( function( item, index, inputArray ) {
           return inputArray.indexOf(item) == index;
    });

    var i = 0;

	arr.forEach(function myFunction(item, index) {

		i = Number(index)+1;
			     
			        line =  "<tr>"+
			                    "<td class='numb' ><input type='checkbox' id='checkRef"+i+"' /></td>"+ 
			                    "<td>"+item+"</td>"+
			                   "</tr>";



			         $('#table_aciref').append(line);

	 });


}else{

  GenInvoice(false);

}
	


}

function CancelOrders() {

var theTbl = document.getElementById('CancelRef'); //objeto de la tabla que contiene los datos de items
var i=1;
var ACIREF = [];
//BLUCLE PARA LEER LINEA POR LINEA LA TABLA 



while (i <= theTbl.rows.length-1){

	console.log(i);

	for(var j=0;j<theTbl.rows[i].cells.length; j++) //BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA
	{
        checkID = '#checkRef'+i;
        
        switch (j){

                case 1:
                console.log('cell: '+j);

                    if ($(checkID).is(":checked"))
					{

	                    AciRef = theTbl.rows[i].cells[1].innerHTML;

	                    ACIREF[i-1] = AciRef;
                 

                    }

                   break;

                  }  

       } //FIN BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA
         
      //INSERTA valor de CELL en el arreglo 
i++;       
}//FIN BLUCLE PARA LEER LINEA POR LINEA DE LA TABLA 


 function UpdateAciRef(){

	if(ACIREF.length > 0){

		ACIREF.forEach(function myFunction(item,index){


		    SetSOEmit(item);

	   });

 }

}

  $.when(UpdateAciRef()).done(function(ID){ 

   GenInvoice(true);

  });

	


}


function SetSOEmit(aciref){

refID  = document.getElementById("ref").value;

var datos= "url=ges_invoice/SetSOEmit/"+aciref+'/'+refID;

return	$.ajax({
			type: "GET",
			url: link,
			data: datos,
			success: function(res){

	            
			}

	 }); 




}


function SetSoToAdd(){

refID  = document.getElementById("ref").value;

var datos= "url=ges_invoice/GetSoToAdd/"+refID ;

	$.ajax({
			type: "GET",
			url: link,
			data: datos,
			success: function(res){

			$("#addSoLines").html(res);
	            
			}

	 }); 

}
	

function calculate(line){

	qtyID =   'desp'+line;
	PriceID = 'unitprice'+line;
	totalID = 'total'+line;

	qty       = document.getElementById(qtyID).innerHTML;
	UnitPrice = document.getElementById(PriceID).innerHTML;

		if(qty=='' || UnitPrice == ''){

			qty = 0;

			UnitPrice = 0;

		}



	total = qty * UnitPrice;

	document.getElementById(totalID).innerHTML = parseFloat(total).toString().match(/^-?\d+(?:\.\d{0,2})?/)[0]; 
	document.getElementById(qtyID).innerHTML =   parseFloat(qty).toFixed(5);
	//document.getElementById(PriceID).innerHTML = parseFloat(UnitPrice).toFixed(4);

	sumar_total();
}

function sumar_total(){

	var theTbl = document.getElementById('invoice'); //objeto de la tabla que contiene los datos de items

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


	    for(var j=0;j<theTbl.rows[i].cells.length; j++) //BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA

	        {       

	            switch (j){

	                   case 6:

	                    if(theTbl.rows[i].cells[2].innerHTML==2){

	                    itbms_sum = ( Number(theTbl.rows[i].cells[j].innerHTML) * Number(theTbl.rows[i].cells[4].innerHTML) ) * Number(tax_value);

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


function SetDetail(){

LineArray.length=''; //limpio el array
var flag = ''; 
var theTbl = document.getElementById('invoice'); //objeto de la tabla que contiene los datos de items
var line = '';
var cantLineas = Number(document.getElementById('NO_LINES').value);

var i=1;
//BLUCLE PARA LEER LINEA POR LINEA LA TABLA 


while (i <= theTbl.rows.length-1){

  cell = '';

	for(var j=0;j<theTbl.rows[i].cells.length; j++) //BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA
	{

        switch (j){

                       case 6:

                            ItemOrd    = i;
                            ItemID     = theTbl.rows[i].cells[0].innerHTML;
                            ItemDesc   = theTbl.rows[i].cells[1].innerHTML;
                            UnitPrice  = theTbl.rows[i].cells[5].innerHTML;
                            Despachado = theTbl.rows[i].cells[4].innerHTML;
                            TotalLinea = theTbl.rows[i].cells[6].innerHTML;

                            cell +=   ItemDesc+'@'+ItemID+'@'+ItemOrd+'@'+Despachado+'@'+TotalLinea+'@'+UnitPrice;//agrego el registo de las demas columnas
                            console.log(cell);
                       break;

        }  

       if(j==4 || j==6){

	         Despachado = theTbl.rows[i].cells[4].innerHTML;
             TotalLinea = theTbl.rows[i].cells[6].innerHTML;

             //SI LA CELDA NO CONTIENE VALOR 
             if(Despachado=='' || TotalLinea ==''){                                  

             FaltaArray[j] = i ;

             }
                           
        }

       } //FIN BLUCLE PARA LEER CELDA POR CELDA DE CADA LINEA

      //INSERTA valor de CELL en el arreglo 
      LineArray[i]=cell; 


i++;       

}//FIN BLUCLE PARA LEER LINEA POR LINEA DE LA TABLA 

//SETEA RETURN DE LA FUNCION, FLAG 1 Ó 0, SI ES 1 LA TABLA ESTA LLENA SI ES 0 LA TABLA ESTA VACIA.
if(FaltaArray.length == 0 && theTbl.rows.length-1 > 0){

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

function validacion(){

  PRINTER = document.getElementById('Printer').value;

  if (PRINTER == ''){

   MSG_ERROR('Debe seleccionar la impresora fiscal',0);

   CHK_VALIDATION = true;

  }

}

//GENERAR FACTURA
function GenInvoice(set){


//LIMPIO ERRORES
MSG_ERROR_RELEASE();

$('#ERROR').hide();
/////////////////////////////


//variables internas
var id = $('#ref').val();
var flag = '';
var count= 0;
var arrLen = '';
var URL   = $('#URL').val();
var link = URL + 'index.php';
var ID = '';

////////////////////////////

//VALIDACIONES
validacion();

if(CHK_VALIDATION == true){ CHK_VALIDATION = false;  return;  }
/////////////////////////////


//AGRUPO LAS LINEAS DE ITEMS EN ARRAY
flag = SetDetail();

/////////////////////////////////////////////////////////////////////////////////////

	//SI NO HAY ITEMS EN LA LISTA
	if(flag==0){ 

	  MSG_ERROR('No se han indicado registros para envio'); 

	    return;

	}

	//SI HAY ITEMS EN LA LISTA
	if(flag==1){ 

		if(set == true){

			r = true;

		}else{

		    r = confirm('Desea generar la factura del pedido '+id+'?');

		}



		if(r==true){
			console.log(LineArray);
	    //INIT BLOQUE DE ENVIO 
	    spin_show();
            
            //INI REGISTRO DE CABECERA
		    function SetInvoiceHeader(){

		    	var Total    = $('#total').val();
		    	var Subtotal = $('#subtotal').val();
                var Itbms    = $('#tax').val();
                var TaxID    = $('#taxid').val();
				var termino_pago = $('#termino_pago').val();
				var tipo_licitacion = $('#tipo_licitacion').val();
				var entrega        = $('#entrega').val();
				var observaciones  = $('#observaciones').html();
				var printer = $('#Printer').val();


			    var datos= "url=ges_invoice/SetInvoiceHeader/"+id+
			                '/'+Subtotal+
			                '/'+Itbms+
			                '/'+Total+
			                '/'+TaxID+
			                '/'+observaciones+'-'+termino_pago+
			                '/'+printer;


			     return $.ajax({
				            type: "GET",
				            url: link,
				            data: datos,
				            success: function(res){

				            	ID = res;
	            
				            }

			      		  }); 
 


		     }
		     //FIN REGISTRO DE CABECERA

            //INIT REGISTROS DE DETALLE
		    $.when(SetInvoiceHeader()).done(function(ID){ //ESPERA QUE TERMINE LA INSERCION DE CABECERA

		        
		        $.ajax({
		         type: "GET",
		         url:  link,
		         data:  {url: 'ges_invoice/SetInvoiceDetail/'+ID , Data : JSON.stringify(LineArray)}, 
		         success: function(res){

		         	 
			          if(res==1){
			          //PROCESO DE IMPRESION
			             
                       CreateInvoiceFile(ID);


			          }else{
                       spin_hide();
			           MSG_ERROR(res);

			          }

		          }

		        });  
		      
		     });
             //FIN REGISTROS DE  DETALLE     */

	    //END BLOQUE DE ENVIO 
		}

	}

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


}



//IDENTIFICA CLUMNA EN LA CUAL SE VALIDO VALOR VACIO
function FIND_COLUMN_NAME(item){

var val;

	switch (item){

	  case 3: val ='A Despachar'; break;
	  case 5: val ='Total'; break;
	
	}

	return val;
}


//Llamada a metodos de ges_invoice para crear los archivos
function CreateInvoiceFile(id){
	var URL   = $('#URL').val();
    var link = URL + 'index.php';
	var datos= "url=ges_invoice/GenInvoiceFiles/"+id;

   // console.log(id);

	$.ajax({
			type: "GET",
			url: link,
			data: datos,
			success: function(res){

			spin_hide();
			history.go(-1); return true;
	            
			}

	 }); 



}



function del_tr(remtr){
               
while((remtr.nodeName.toLowerCase())!='tr')
remtr = remtr.parentNode;
remtr.parentNode.removeChild(remtr);

sumar_total();

}
