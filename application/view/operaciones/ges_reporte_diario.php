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
<div id="ERROR" class="alert alert-danger"></div>
<!--INI DIV ERROR-->

<div  class="col-lg-12">
<!-- contenido -->
<h2>Reporte Diario</h2>
	<div class="title col-lg-12"></div>

	<div class="col-lg-12">
	<!--INI  contenido -->

		<div class="col-lg-12">
		  <fieldset>
		   <div class="separador col-lg-12"></div>
		   
		   

		  <div class="col-lg-5" >
		    
		     <label>Registros entre</label>
		     <input type="date" id="date1" name="name1"  />
		     <label>y</label>
		     <input type="date" id="date2" name="name2" />
		   
		  </div>

		  <div class="col-lg-5" >
            <label  class='col-lg-3' >Serial de impresora fiscal</label>
            <select class='select col-lg-9' id='Printer' name='Printer' >
						<option value="" selected></option>
							<?PHP 

					         $list = $this->getPrinterList();
					         $Printers = '';

					         foreach ($list as $key => $value) {
					         	$value = json_decode($value);



			                      $Printers .= '<option value="'.$value->{'SERIAL'}.'"  '.$selected.' >'.$value->{'DESCRIPCION'}.' ( '.$value->{'SERIAL'}.') </option>';

			                   }
                   
					         
					         
					         echo $Printers;

							 ?>
		    </select>

		  </div>


		  <div class="col-lg-2">

		  <input type="submit" onclick="Filtrar();" class="btn btn-primary  btn-sm btn-icon icon-right" value="Consultar" />
		  
		  </div>  


		 


		</fieldset> 
		<div class="separador col-lg-12"></div>

		<script type="text/javascript">
		  
		function Filtrar(){

		var date1 = $('#date1').val();
		var date2 = $('#date2').val();
        var printer = $('#Printer').val(); 


		var datos= "url=ges_ventas/GetDaylySales/"+date1+"/"+date2+'/'+printer;
		  
		var link= +"index.php";

		  $.ajax({
		      type: "GET",
		      url: link,
		      data: datos,
		      success: function(res){
		      
		       $('#table').html(res);
		       // alert(res);

		        }
		   });

		       $('html, body').animate({
		        scrollTop: $("#table").offset().top
		        }, 2000);




		}


		</script>

		 <fieldset>

		<div id="table"></div>


		</fieldset>
		 


		<div id="info"></div>

		</div>


	<!--END contenido -->
	</div>
</div>
</div>
</div>