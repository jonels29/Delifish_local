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
<h2>Gestión de Precios</h2>
<div class="title col-lg-12"></div>

<div class="col-lg-12">


<fieldset>
		<legend><h4>Gestion Niveles de Precio</h4></legend>

		<label>Clientes:</label>	
		

<div id=cust_table>



<script type="text/javascript">

show_cust_table();
	
function show_cust_table(){

URL = document.getElementById('URL').value;

var datos= "url=bridge_query/List_customers/";   
var link = URL+"index.php";


$('#cust_table').html('<P>CARGANDO ...</P>');

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
      	$('#cust_table').html(res);

       //alert(res);

        }
   });

}

</script>

</div>

</fieldset>


<div class="separador col-lg-12"></div>



</div>
</div>