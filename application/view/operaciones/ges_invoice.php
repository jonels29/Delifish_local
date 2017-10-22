<script type="text/javascript">

 // ********************************************************
// * Aciones cuando la pagina ya esta cargada
// ********************************************************
$(window).load(function(){

$('#ERROR').hide();

});

</script>

<div class="page col-lg-12">


<!--INI DIV ERRO-->
<div id="ERROR" ></div>
<!--INI DIV ERROR-->


<div  class="col-lg-12">
<!-- contenido -->
<h2>Facturacion de Ordenes</h2>
	<div class="title col-lg-12"></div>

	<div class="col-lg-12">
	<!--INI  contenido -->

	<script type="text/javascript">
	  jQuery(document).ready(function($)
	  {
	   var table = $("#invoice").dataTable({
	      bSort: false,
	      select:true,
	      aLengthMenu: [
	       [25, 50, 100, 500 , -1], [25, 50, 100 , 500 , "All"]
	      ]
	    });


table.yadcf(
[{column_number : 0,
 column_data_type: "html",
 html_data_type: "text" ,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 1,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 2,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 3,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 4,
 select_type: "select2",
 select_type_options: { width: "100%" }

},
{column_number : 5,
 select_type: "select2",
 select_type_options: { width: "100%" }

}],
{cumulative_filtering: true, 
filter_reset_button_text: false}
);

});
	   


	  function SaleToInvoice(URL,id){


      var datos= "url=ges_invoice/GetOrdrDetail/"+id;

      
       $.ajax({
         type: "GET",
         url: URL+'index.php',
         data: datos,
         success: function(res){

           //$("historial").hide();

           $("#info").html(res);

                 }
            });

       $('html, body').animate({
        scrollTop: $("#info").offset().top
        }, 2000);



	  }
	 </script>

    <fieldset> 
		<table id='invoice' class="table table-bordered">
		    <thead>
		    	<th>Referencia</th>
		    	<th>Cliente ID</th>
		    	<th>Nombre Cliente</th>
		    	<th>Fecha</th>
		    	<th>Lugar de despacho</th>
		    	<th>Enviado a despacho</th>
		    </thead>
		    <tbody>
		     <?php 
		     $table = '';

		     $invoices = $this->GetOrdrToInvoice(); 

			     foreach ($invoices as $key => $value) {
			     
			       $value = json_decode($value);

			       $SalesID = '"'.$value->{'SalesOrderNumber'}.'"';
			       $URL     = '"'.URL.'"';

                   if($value->{'DispachPrinted'}==1){

                      $despachado = 'Si';
                      $style = 'style="background-color:#BCF5A9;"';
                   
                   }else{


                      $despachado = 'No';
                      $style = 'style="background-color:#D8D8D8;"';
                   }



		           $table .= "<tr>
								<td><a href='#' onclick='javascript: SaleToInvoice(".$URL.",".$SalesID."); ' >".$value->{'SalesOrderNumber'}."</a>   </td>
								<td>".$value->{'CustomerID'}.'</td>
								<td>'.$value->{'CustomerName'}.'</td>
								<td>'.$value->{'date'}.'</td>
								<td>'.$value->{'lugar_despacho'}.'</td>
								<td  '.$style.' >'.$despachado.'</td>
							 </tr>';


			     }

			  echo $table;
		     ?>
		    </tbody>
		</table>
    </fieldset> 
    <div class="separador col-lg-12"></div>
    <fieldset>
    	<div id="info"></div>
    </fieldset>
 	<!--END contenido -->
	</div>
</div>
</div>
</div>

