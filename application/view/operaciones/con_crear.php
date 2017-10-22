<!--JAVASCRIPT -->
<script type="text/javascript">
$(document).ready(function(){

//DEFINE ESTILO Y FUNCIONES CON LA CLASE DATATABLES PARA LAS TABLAS
var table =  $("#products").dataTable({
        aLengthMenu: false,
        pageLength: 5

            });
    table.yadcf([
        {column_number : 1},
        {column_number : 2},
        ]);
//FIN DEFINE ESTILO Y FUNOCIONES CON LA CLASE DATATABLES PARA LAS TABLAS

//CONSULTA LA LISTA DE ALMACENES A TRAVES DE UN METODO EN EL DOC BRIDGE_QUERY
var URL = $('#URL').val();
var datos= "url=bridge_query/get_almacen_selectlist/";
var link= URL+"index.php";


 $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
        
         $('#up_stock').html(res); //IMPRIME EL RESULTADO DE LA CONSULTA EN EL ELEMENTE HTML CON ID up_stock
        }
   });

});
//FIN CONSULTA LA LISTA DE ALMACENES A TRAVES DE UN METODO EN EL DOC BRIDGE_QUERY

</script>
<!--JAVASCRIPT -->

<div class="page col-lg-12">

<div  class="col-lg-12">
<!-- contenido -->
<h2>Consignaciones</h2>
<div class="title col-lg-12"></div>
<div class="separador col-lg-12"></div>

<input type="hidden" id='URL' value="<?php ECHO URL; ?>" />
<div class="col-lg-6">

<!-- INFORMACION DE PROYECTO -->
<div class="col-lg-12">

	<fieldset>
		<legend><h4>Informacion General</h4></legend>
        
        <div class="col-lg-12"> 
         <div class="col-lg-12">
         <fieldset>
			<p><strong>Proyecto</strong></p>
				
			<select  id="jobs" name="jobs" class="select col-lg-12" onchange="set_job(this.value);" required>

			<option selected disabled></option>

			<?php  
			$JOBS = $this->model-> get_JobList(); 

			foreach ($JOBS as $datos) {
																
			$JOBS_INF = json_decode($datos);
			echo '<option value="'.$JOBS_INF->{'JobID'}.'" >'.$JOBS_INF->{'Description'}."</option>";

			}
			?>
									
		</select>		
		 </fieldset>
         </div>
         <div class="separador col-lg-12"></div>
         <div class="col-lg-6" >
            <fieldset>
			<p><strong>Fase</strong></p>
				
			<select  id="jobphase" name="jobphase" class="select col-lg-12" onchange="set_phase(this.value);" required>

			<option selected disabled></option>

			<?php  
			$phase = $this->model->get_phaseList(); 

			foreach ($phase as $datos) {
																
			$phase_INF = json_decode($datos);
			echo '<option value="'.$phase_INF->{'PhaseID'}.'" >'.$phase_INF->{'Description'}."</option>";

			}
			?>
									
		</select>		
		 </fieldset>
       </div>
      
        <div class="col-lg-6" >
            <fieldset>
			<p><strong>Centro de Costo</strong></p>
				
			<select  id="jobcost" name="jobcost" class="select col-lg-12" onchange="set_cost(this.value);" required>

			<option selected disabled></option>

			<?php  
			$cost = $this->model->get_costList(); 

			foreach ($cost as $datos) {
																
			$cost_INF = json_decode($datos);
			echo '<option value="'.$cost_INF->{'CostCodeID'}.'" >'.$cost_INF->{'Description'}."</option>";

			}
			?>
									
		</select>		
		 </fieldset>
         </div>

       	</div>

		</fieldset>
</div>
<!-- FIN INFORMACION DE PROYECTO -->

<div class="separador col-lg-12"></div>

<!-- LISTA DE PRODUCTOS -->
<div class="col-lg-12">
<fieldset>
<legend><h4>Productos</h4></legend>

<table id="products" class="table table-striped" cellspacing="0"  >
            <thead>
              <tr>
                <th width="5%"></th>
                <th width="30%">Codigo</th>
                <th width="50%">Descripcion</th>
                <th width="20%">Unidad</th>
                <th width="10%">Cantidad</th>

              </tr>
            </thead>
          
          
            <tbody> 
        <?php  

      

 
     $Item =  $this->model->get_ProductsList(); 

        foreach ($Item as $datos) {

          $Item = json_decode($datos);
        
         if($Item->{'QtyOnHand'}>=1){
         	
          $ID ='"'.$Item->{'ProductID'}.'"';
          $NAME='"'.$Item->{'Description'}.'"';
          $PRICE ='"'.number_format($Item->{'Price1'}, 2, '.', ',').'"';
                          
        echo  "<tr><td width='5%'><a title='Agregar a la orden' data-toggle='modal' data-target='#myModal' href='javascript:void(0)' onclick='javascript: modal(".$ID.",".$NAME.",".$PRICE."); ' ><i style='color:green' class='fa fa-plus'></i></a></td>
            <td width='30%' id=".$Item->{'ProductID'}."><strong> ".$Item->{'ProductID'}."</strong></td>
            <td width='50%' id=".$Item->{'ProductID'}.$Item->{'Description'}."><strong>".$Item->{'Description'}.'</strong></td> 
            <td width="20%" class="numb" id='.$Item->{'ProductID'}.$Item->{'Description'}.'-price'.">".$Item->{'UnitMeasure'}.'</td>
            <td width="10%" class="numb" id="'.$Item->{'ProductID'}.'qty'.'" >'.number_format($Item->{'QtyOnHand'},0, '.', ',').'</td></tr>';


        	}

          
  } ?>
              
            </tbody>
          </table>
<!-- LISTA DE PRODUCTOS -->

<!-- JAVASCRIPT -->
<script type="text/javascript">

//LA FUNCION MODAL ES LLAMADA AL MOMENTO DE SELECCIONAR UN PRODUCTO DESDE LA LISTA DE PRODUCTOS
function modal(id,name){

var URL = $('#URL').val();

document.getElementById('item_id_modal').value = id;
document.getElementById('desc_id_modal').value = name;

//SE CONSULTA LA LISTA DE LOTES DEL PRODUCTO  A TRAVES DEL BRIDGE_QUERY Y ES IMPRESA EN EL ELEMENTO HTML line
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
<!-- JAVASCRIPT -->

</fieldset>

</div>
<div class="separador col-lg-12"></div>
</div>


<!-- oredn-->
<div class="col-lg-6" >
	
<fieldset>
<legend><h4>Detalle</h4></legend>

<input type="hidden" id='user' value="<?php echo $active_user_id; ?>" />

<div class="col-lg-12">
 <div   class="col-lg-6"> 
                 
    <label style="display:inline">Referencia </label><INPUT class="input-control" type="text" name="no_order" id="no_order" readonly value="
     <?php echo  $this->model->Get_con_No(); ?>" />
</div>

<div  class="col-lg-1"></div>
  <div   class="col-lg-5">
  <label> Fecha : </label><input style="float:right; text-align: center;" class="input-control" name="date" id="date" value="<?php echo date("Y-m-d"); ?>" readonly/></label>
  </div>

</div>
    <div class="title col-lg-12"></div>
	<!-- Client and Payment Details -->
         <div class="col-lg-12">

         <fieldset>
         	<input type="hidden" name="jobID_db" id="jobID_db" value="" />
         	<input type="hidden" name="phaseID_db" id="phaseID_db"" value="" />
         	<input type="hidden" name="costID_db" id="costID_db" value="" />




         	<div class="col-lg-12">
         		<strong>Proyecto : </strong><label   id="JobDesc" name="JobDesc"></label><br>

         		<strong>Fase : </strong><label  id="PhaseDesc" name="PhaseDesc"></label><br>

         		<strong>Centro de Costo : </strong><label   id="CostDesc" name="CostDesc"></label>
         		
         	</div>
         </fieldset>
		 </div> 

		<div  class="separador col-lg-12"></div>
		 <div class="col-lg-12">

         <fieldset>
         	
         	<div class="col-lg-12">
         		<strong>Nota: </strong><textarea rows="2" cols="70" id="reasonToAdj" name="reasonToAdj">  </textarea>

         		
         	</div>
         </fieldset>
		</div> 

<!-- JAVASCRIPT -->
<script type="text/javascript">
	
//LA FUNCION ROUTES ES LLAMADAS AL MOMENTO DE SELECCIONAR UN ELEMENTO DE LA LISTA DE ALMACENES Y CONSULTA A TRAVES DEL BRIDGE_QUERY LA LISTA DE ROUTAS POR ALMACEN, EL PARAMETRO ID ES EL ID DEL ALMACEN
function routes(id){

var URL = $('#URL').val();
var datos= "url=bridge_query/get_routes_by_almacenid/"+id;
var link= URL+"index.php";

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
</script>
<!-- JAVASCRIPT -->

							<!-- ESTO PUEDE VARIAR SEGUN SEA EL CASO -->
	<input type="text" id="taxid" name="taxid" value="1" hidden/>
							<!-- valores ocultos -->
	<input type="text" id="cust_id" name="cust_id" hidden/>
	<input type="text" id="cust_comp" name="cust_comp" hidden/>
    
     <div class="separador col-lg-12"></div>	
     <div class="col-lg-12" >
            <fieldset>
			<p><strong>Ubicacion destino</strong></p>
<table width="100%">
<tr>
<th>Almacen</th>
<th>Ruta</th>
</tr>

<tr>
<td>
<select id="up_stock" class="form-control"  onclick="routes(this.value);"></select>
</td>
<td><select id="up_route" class="form-control" ></select>
</td>				
</tr></table>

		 </fieldset>
       </div>
	<div class="title col-lg-12"></div>
	<div class="separador col-lg-12"></div>			



						
	<!-- Invoice Entries -->
	<table class="table table-bordered">
	<thead>
	<tr class="no-borders">
		<th class="text-center hidden-xs">Codigo</th>
		<th width="45%" class="text-center">Descripcion</th>
		<th class="text-center hidden-xs">Cantidad</th>
		
		
	</tr>
	</thead>
							
	<tbody id="invoice">
								
	</tbody>
	</table>
	
		    
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


<!-- Modal : VENTANA EMERGENTE QUE SE LLAMA AL SELECCIONAR UN PRODUCTO DE LA LISTA DE PRODUCTOS-->
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


<!--JAVASCRIPT-->
<script>
var LineArray = []; //array para los items de la cotizacion
var count = 0;
var itemsArray = [];

function set_job(jobid){

document.getElementById('jobID_db').value = jobid;
document.getElementById('JobDesc').innerHTML = jobid;

}

function set_phase(phaseid){

document.getElementById('phaseID_db').value = phaseid;
document.getElementById('PhaseDesc').innerHTML= phaseid;

}

function set_cost(costid){

document.getElementById('costID_db').value = costid;
document.getElementById('CostDesc').innerHTML=costid;

}



//FUNCION QUE AGREGA PRODUCTOS SELECCIONADOS A LA LISTA DE SELECCION DE LA ORDEN, SE LLAMA DESDE SET_ITEMS()
function agregar_pro_sale_sale(id,item,price,lote,venc,ruta,max,qty){


var caduc = '';

  if(venc!=''){

    caduc = 'VENCE :'+venc;

  }else{

    caduc = '';

  }

  var Lote = "LOTE :"+lote+" "+caduc+" Cant: "+qty;

	var idqty = id+lote+ruta+"qty";


	var element = id+lote+ruta; 
							
    var qty =  parseInt(qty);
							

max = Number(parseFloat(max).toFixed(0));

qty = Number(parseFloat(qty).toFixed(0));


 if(!document.getElementById(element)){


           var arrayItem =LineArray.length;



			var line = '<tr id="'+element+'"><td class="text-center hidden-xs"><a href="#" onclick="javascript: erase_item('+arrayItem+'); del_tr(this);"><i style="color:red;" class="fa fa-minus"></i></a>&nbsp&nbsp'+id+'</td><td>'+item+' '+Lote+'</td><td class="text-center hidden-xs">'+qty+'</td></tr>';
							

							
			$( "#invoice" ).append( line );

			var orderNum = document.getElementById('no_order').value;

	        LineArray[LineArray.length] = id+"/"+orderNum.trim()+"/"+qty+"/"+lote+'/'+ruta+'/'+caduc;

				   }else{
					alert("Ya se han ingresado items del mismo lote.");
    					}
				}

//FUNCIONES PARA BORRAR ITEMS DE LA LISTA DE SELECCION DE LA ORDEN
function del_tr(remtr)  
		{   
		while((remtr.nodeName.toLowerCase())!='tr')
		remtr = remtr.parentNode;
         remtr.parentNode.removeChild(remtr);
		}

function del_id(id)  
		{   
		del_tr(document.getElementById(id));
		}


function erase_item(line){						
		LineArray[line]='';

		}
//FIN FUNCIONES PARA BORRAR ITEMS DE LA LISTA DE SELECCION DE LA ORDEN


//FUNCION PARA GUARDAR ITEMS EN ARRAY, ES LLAMDA POR EL BOTON (ACEPTAR) HTML DE LA VENTANA MODAL
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


  //idline = theTbl.rows[i].cells[3].innerHTML+theTbl.rows[i].cells[2].innerHTML;
  //cell1 = document.getElementById(idline).checked;
  taxfield = theTbl.rows[i].cells[2].innerHTML+theTbl.rows[i].cells[1].innerHTML+'taxable';
  qty = theTbl.rows[i].cells[2].innerHTML+theTbl.rows[i].cells[1].innerHTML+'qty';
  iditem = document.getElementById('item_id_modal').value;
  descitem = document.getElementById('desc_id_modal').value;


  ruta_dest = document.getElementById('up_stock').value;
  almacen_dest  = document.getElementById('up_route').value;

 
 var   qty_val =document.getElementById(qty).value;

if(qty_val > 0){

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

        
        //SE LLAMA LA FUNCION PARA AGREGAR LOS ITEMS EN LA LISTA DE SELECCION DE LA ORDEN
        if (qty_val > 0  && l!=''){

            agregar_pro_sale_sale(iditem,descitem,'',lote,venc,ruta,max,qty_val);
             
            }
 
        

}//FIN BLUCLE PARA LEER LINEA POR LINEA DE LA TABLA 

  if(l!=''){

      $('#myModal').modal('hide'); //SI LA LISTA DE ITEMS NO ESTA VACIA SE CIERRA LA VENTANA EMERGENTE
  }


}

//VALIDA QUE LA CANTIDAD DEL LOTE SELECCIONADO SEA MENOR O IGUAL AL EXISTENTE EN STOCK. LA FUNCION ES LLAMADA DESDE LA LINEA DEL LOTE AL MOMENDO DE REALIZAR UN ONUNFOCUS EN EL CAMPO DE CANTIDAD.
function valida_qty(max,qty,id){  

console.log('MAX:'+max+' Qty:'+qty);



if(qty!=''){

    var compare = (parseInt(max) >= parseInt(qty));

    if(!compare){

      alert('El valor de la cantidad no debe exceder la cantidad disponible en Stock');
     
       document.getElementById(id).value = '';

      return '0';

     }else{

      return '1';
     }

}

}
//FIN VALIDACION

//LA FUNCION send_sales_order PROCESA LA ORDEN PARA EL REGISTRO DE LOS DATOS EN LA BD.
function send_sales_order(){

	if(up_stock != 'Seleccionar Almacen'){

	if(LineArray!='' ){

		var r = confirm("Desea enviar esta Orden ahora ?");
								

	if (r == true) {

		var arrLen = LineArray.length;
	    var count= 1;

	    var JobDesc = document.getElementById('jobID_db').value;
	    var PhaseDesc = document.getElementById('phaseID_db').value;
	    var CostDesc = document.getElementById('costID_db').value ;

	    var reasonToAdj = document.getElementById('reasonToAdj').value;
	    var no_order = document.getElementById('no_order').value;

	    var up_stock = document.getElementById('up_stock').value;
	    var up_route = document.getElementById('up_route').value;

						
		URL = document.getElementById('URL').value;

	
	   if(up_stock && up_route){

             var cont = 1;
             //ModifGPH

			//INSERTAR HEADER 
			var datos= "url=bridge_query/set_consignacion_header/"+JobDesc+'/'+PhaseDesc+'/'+CostDesc+'/'+reasonToAdj.trim()+'/'+no_order;


							var link = URL+"index.php";

							$.ajax({
								type: "GET",
								url: link,
								data: datos,
								success: function(res){
							      
									

					            }
							});

				$.each(LineArray, function(index,value) {


                    setTimeout( function(){ 

                    count++;

					var datos= "url=bridge_query/set_con_reg_tras/"+value.trim()+'/'+up_route+'/'+up_stock+'/'+count+'/'+arrLen;

					console.log(datos);


							var link = URL+"index.php";

							$.ajax({
								type: "GET",
								url: link,
								data: datos,
								success: function(res){

							    console.log(res);
							      
									if(res==0){
										 msg(URL+"index.php",no_order);
									}

					            }
							});

						}, 500);
									  							    
					}); 	

					
	
	
						 }else{


						 	alert('Se deben llenar todos los campos');

						 }

					}	
								
          //
			}else{

				alert('Debe incluir al menos un Item en la orden');


			}
		}else{
			alert('Debe seleccionar un Almacen');
		}

			 			
	     	}
	     	

function msg(link,SalesOrder){


			    alert("La orden se ha enviado con exito");

			    var R = confirm('Desea imprimir la orden de venta?');

			    if(R==true){

                
				 count = 1;
                 LineArray.length='';
                           
                 window.open(link+'?url=ges_consignaciones/con_print/'+SalesOrder,'_self');
                 


			    }else{

			  count = 1;
              LineArray.length='';
			  location.reload();

			    }

			    

	     	}




</script>