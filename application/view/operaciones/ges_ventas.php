<script type="text/javascript">
$(document).ready(function(){

var table =  $("#products").dataTable({
        aLengthMenu: [
        [5,10, 25,50,-1], [5,10, 25, 50,"All"]
              ]
            });

 table.yadcf([
{column_number : 1},
{column_number : 2},
]);


  $("#modal_table").dataTable({
        aLengthMenu: [
        [5,10, 25,50,-1], [5,10, 25, 50,"All"]
              ]
            });

});
</script>

<?php
 $tax_rate= $this->model->Query_value('sale_tax','rate','where id="1";');
 $tax_rate = $tax_rate /100;
?>

<input type="hidden"  id="saletaxid"  value="<?php echo  $tax_rate; ?>" />

<div class="page col-lg-11">

<div  class="col-lg-12">
<!-- contenido -->
<h2>Orden de Venta</h2>
<div class="title col-lg-12"></div>
<div class="separador col-lg-12"></div>

<input type="hidden" id='URL' value="<?php ECHO URL; ?>" />
<div class="col-lg-6">

<!-- select clientes -->
<div class="col-lg-12">



		<fieldset>
		<legend><h4>Informacion General</h4></legend>

		<label>Cliente</label>	
		<select  id="customer" name="customer" class="select col-lg-8" onchange="sendval(this.value);" required>

			<option selected disabled></option>

			<?php  
			$CUST = $this->model-> get_ClientList(); 

			foreach ($CUST as $datos) {
																
			$CUST_INF = json_decode($datos);
			echo '<option value="'.$CUST_INF->{'ID'}.'" >'.$CUST_INF->{'Customer_Bill_Name'}."</option>";

			}
			?>
									
		</select>		
		</fieldset>
</div>

<div class="separador col-lg-12"></div>

<!-- select products -->
<div class="col-lg-12">
<fieldset>
<legend><h4>Productos</h4></legend>

<table id="products" class="table table-striped" cellspacing="0"  >
            <thead>
              <tr>
                <th width="5%"></th>
                <th width="30%">Codigo</th>
                <th width="50%">Descripcion</th>
                <th width="20%">Precio</th>
                <th width="10%">Cant. Dip</th>

              </tr>
            </thead>
          
          
            <tbody> 
        <?php  

      

        $Item =  $this->model->get_ProductsList(); 

        foreach ($Item as $datos) {

          $Item = json_decode($datos);
          $ID ='"'.$Item->{'ProductID'}.'"';
          $NAME='"'.$Item->{'Description'}.'"';
          $PRICE ='"'.number_format($Item->{'Price1'}, 2, '.', ',').'"';
                          
        echo  "<tr><td width='5%'><a title='Agregar a la orden' data-toggle='modal' data-target='#myModal' href='javascript:void(0)' onclick='javascript: modal(".$ID.",".$NAME.",".$PRICE."); ' ><i style='color:green' class='fa fa-plus'></i></a></td>
            <td width='30%' id=".$Item->{'ProductID'}."><strong> ".$Item->{'ProductID'}."</strong></td>
            <td width='50%' id=".$Item->{'ProductID'}.$Item->{'Description'}."><strong>".$Item->{'Description'}.'</strong></td> 
            <td width="20%" id='.$Item->{'ProductID'}.$Item->{'Description'}.'-price'.">".number_format($Item->{'Price1'}, 2, '.', ',').'</td>
            <td width="10%" id="'.$Item->{'ProductID'}.'qty'.'" >'.number_format($Item->{'QtyOnHand'},0).'</td></tr>';

  } ?>
              
            </tbody>
          </table>

<script type="text/javascript">

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
<div class="separador col-lg-12"></div>
</div>


<!-- oredn-->
<div class="col-lg-6" >
	
<fieldset>
<legend>Orden de venta</legend>

<input type="hidden" id='user' value="<?php echo $active_user_id; ?>" />

<div class="col-lg-12">
 <div   class="col-lg-6"> 
                 
    <label style="display:inline"> Orden No. </label><INPUT class="input-control" type="text" name="no_order" id="no_order" readonly value="
     <?php echo  $this->model->Get_SO_No(); ?>" />
</div>

<div  class="col-lg-2"></div>
  <div   class="col-lg-4">
  <label> Fecha : </label><input style="float:right; text-align: center;" class="input-control" name="date" id="date" value="<?php echo date("Y-m-d"); ?>" /></label>
  </div>

</div>
    <div class="title col-lg-12"></div>
	<!-- Client and Payment Details -->
	
         <div class="col-lg-8">
         <fieldset>
         	<legend><h5>Entrega para:</h5></legend>
         	<div class="col-lg-12">
         		<label id="cust_name" name="cust_name"></label><br>
         		<label id="cust_addr" name="cust_addr"></label><br>
         		<label id="cust_mail" name="cust_mail"></label>&nbsp/
         		<label id="cust_phone" name="cust_phone"></label>
         	</div>
         </fieldset>
		</div> 


							<!-- ESTO PUEDE VARIAR SEGUN SEA EL CASO -->
	<input type="text" id="taxid" name="taxid" value="1" hidden/>
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
function sumar_total(id,item,price,qty){

	price = parseFloat(price)*qty

var saletax = document.getElementById('saletaxid').value;

var subtotal =  document.getElementById('subtotal');
var tax =document.getElementById('tax');
var total = document.getElementById('total');

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
	<input class="col-lg-12" type="text"  step="0.01" id="subtotal" name="subtotal"  value="0" readonly />
    <input class="col-lg-12" type="text" step="0.01" id="tax" name="tax" value="0" readonly />
    <input class="col-lg-12" type="text" step="0.01" id="total" name="total" value="0" readonly />
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
  <div class="modal-dialog">

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

<table id="modal_table" widht="100%" class="table table-striped"  cellspacing="0">
            <thead>
              <tr>  
                <th width="5%" ><i style="color:green" class="fa fa-check"></i></th>
                <th width="20%" >Almacen</th>
                <th width="20%" >Ruta</th>
                <th width="20%" >Lote</th>
                <th width="5%" >Stock</th>
                <th width="10%" >Cant.</th>
                <th width="15%" >Fecha Ven.</th>
              </tr>
            </thead>
            <tbody id="line"></tbody>
</table>

</div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>

  </div>
</div>

<script>
var LineArray = []; //array para los items de la cotizacion
var count = 0;

function sendval(ID){



URL = document.getElementById('URL').value;

var datos= "url=bridge_query/get_Cust_info/"+ID;
var link= URL+"index.php";

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){

        res = JSON.parse(res);

        document.getElementById('cust_name').innerHTML= res.Customer_Bill_Name;
		document.getElementById('cust_addr').innerHTML= res.AddressLine1+' '+res.AddressLine2;
		document.getElementById('cust_mail').innerHTML= res.Email;
		document.getElementById('cust_phone').innerHTML= res.Phone_Number;
		document.getElementById('cust_id').value= res.ID;
		document.getElementById('cust_comp').value= res.id_compania;

        }
   });

							 
}

function agregar_pro_sale_sale(id,item,price,lote,venc,ruta){

   var Lote = "LOTE :"+lote+" / FECHA VENC: "+venc+" / Ubicacion: "+ruta;

	FechaVenc = venc;

	var idqty = id+lote+ruta+"qty";

	var element = id+lote+ruta; 
							
    var qty = document.getElementById(idqty).value;
							
	var total=parseFloat(price*qty).toFixed(2);


		if(!document.getElementById(element)){

			if(qty!=''){

           var arrayItem =LineArray.length;



			var line = '<tr id="'+element+'"><td class="text-center hidden-xs"><a href="#" onclick="javascript: rest_value('+price+','+qty+'); erase_item('+arrayItem+'); del_tr(this);"><i style="color:red;" class="fa fa-minus"></i></a>&nbsp&nbsp'+id+'</td><td>'+item+' '+Lote+'</td><td class="text-center hidden-xs">'+qty+'</td><td class="text-right text-primary text-bold">'+price+'</td><td class="text-right text-primary text-bold">'+total+'</td></tr>';
							

							
			$( "#invoice" ).append( line );

			sumar_total(id,item,price,qty);
             
             var unit_price = price;

									// guardo cada linea en el array
			price = parseFloat(price)*qty;

	        LineArray[LineArray.length] = id+"/"+unit_price+"/"+document.getElementById('no_order').value+"/"+qty+"/"+price+"/"+lote+'/'+ruta+'/'+FechaVenc;



			}else{ 

              alert("La cantidad debe ser especificada.");
			}


			
							}else{

							alert("Ya se han ingresado items del mismo lote.");
							}

						}

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

							//alert(LineArray);

						}

						function rest_value(price,qty){

							var saletax = document.getElementById('saletaxid').value;

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


			function send_sales_order(){


			if(LineArray!=''){

				var r = confirm("Desea enviar esta Orden ahora ?");
								

						if (r == true) {
								    
								

						 
						 ID_compania=$("#cust_comp").val();
						 var SalesOrderNumber=document.getElementById('no_order').value;
						 var CustomerID=$("#cust_id").val();

						 var user=document.getElementById('active_user_id').value;
						
						
 						 var Subtotal=$("#subtotal").val();
 						 var total=$("#total").val();
						 var TaxID=$("#taxid").val();

						URL = document.getElementById('URL').value;

						// var datos =  "option=9&ID_compania="+ID_compania+"&SalesOrderNumber="+SalesOrderNumber+"&CustomerID="+CustomerID+"&CustomerName="+CustomerName+"&Subtotal="+Subtotal+"&TaxID="+TaxID+"&Net_due="+total+"&date="+date+"&user="+user;

				if(SalesOrderNumber && CustomerID ){


							var datos= "url=bridge_query/set_sales_order_header/"+ID_compania+'/'+SalesOrderNumber+'/'+CustomerID+'/'+Subtotal+'/'+TaxID+'/'+total+'/'+user;

							var link= URL+"index.php";

							  $.ajax({
							      type: "GET",
							      url: link,
							      data: datos,
							      success: function(res){

                                   console.log(res);

							        }
							   });

			

				$.each(LineArray, function(index,value) {


                           setTimeout( function(){ 

                               	count++;
										    //console.log(value);

											var datos= "url=bridge_query/set_sales_order_detail/"+ID_compania+'/'+value+'/'+LineArray.length+'/'+count;

											var link= URL+"index.php";

											// console.log(datos);

												$.ajax({
													type: "GET",
													url: link,
													data: datos,
													success: function(res){

												    //console.log(res);
												  //  alert(res);	
												  if(res==1){


						                               msg(URL+"index.php",SalesOrderNumber);

						  						     }
												  
				     
					                                 }
												});



							 }, 500);
										  							    
											

							}); 	

	
						 }else{


						 	alert('Debe seleccionar el cliente');

						 }

						 

						
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




			function send_data_sales(data){

 

 								$.ajax({
						            type: 'POST',
						            url: 'form_query.php',
						            data: data,
						            dataType: 'html',   
						            success: function(res) {
//$("#prueba").append(res);

						    

						            }
						        });


						 }

</script>