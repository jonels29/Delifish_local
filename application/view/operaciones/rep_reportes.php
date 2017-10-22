<?php
//RECUPERO INFO DE DETALLES DE FACTURACION
$SQL = 'SELECT * FROM MOD_MENU_CONF';

$MOD_MENU = $this->model->Query($SQL);

foreach ($MOD_MENU as $value) {

$value = json_decode($value);

if($value->{'mod_sales'}=='1'){ $mod_sales_CK = 'checked'; }else{ $mod_sales_CK = '';  }
if($value->{'mod_fact'}=='1'){ $mod_fact_CK  = 'checked'; }else{ $mod_fact_CK = '';  }
if($value->{'mod_invt'}=='1'){ $mod_invt_CK = 'checked'; }else{ $mod_invt_CK  = '';  }
if($value->{'mod_rept'}=='1'){ $mod_rept_CK = 'checked'; }else{ $mod_rept_CK = '';  }
if($value->{'mod_stock'}=='1'){ $mod_stoc_CK = 'checked'; }else{ $mod_stoc_CK = '';  }

}
?>

<div class="page col-xs-12">

<div  class="col-xs-12">
<!-- contenido -->
<h2>Generar Reportes</h2>
<div class="title col-xs-12"></div>

<div class="col-xs-12">


<div class="col-lg-12">
  <fieldset>

   <div class="col-lg-3">
       <select  id="reportType" required>
           <option  selected disabled>Seleccione el reporte</option>
 
<?php   if($mod_invt_CK  == 'checked'){?>          
           <optgroup label="Inventario">
           <option  value="InvXVen" >INV. STOCK x VENC.</option>
           <option  value="InvXStk" >INVENTARIO x STOCK</option>
           <option  value="ConList" >CONSIGNACIONES</option>
           <option  value="ReqStat" >REQUISICIONES</option>
           </optgroup>
<?php } ?>     
<?php   if($mod_fact_CK  == 'checked'){  ?>         
           <optgroup label="Compras">
           <option  value="PurFact" >FACTURAS DE COMPRA</option>
           <option  value="PurOrd"  >ORDENES  DE COMPRA</option>
           </optgroup>
<?php } ?>  

<?php   if($mod_sales_CK  == 'checked'){  ?> 
           <optgroup label="Ventas">
           <option  value="PriceList"  >LISTA DE PRECIOS</option>
           </optgroup>
<?php } ?> 
      </select>

  </div>



<div id='par_filtro' class="collapse " >
<div class="col-lg-12"></div>
<fieldset>
  <div class="col-lg-5" >

     <label>Registros entre</label>
     <div class='col-lg-12'>
     <input class='numb' type="date" id="date1" name="name1"  value="" /> -
     <input class='numb' type="date" id="date2" name="name2"  value=""/> 
     </div>
  
  </div>

  <div class="col-lg-3" >
    
     <label>Sortear</label>
     <div class='col-lg-12'>
     <select   id="sort" required>
           
           <option  value="ASC">Ascendente (A-Z)</option>
           <option  value="DESC" selected>Descendente (Z-A)</option>
         
    </select>
    </div>
   
  </div>

  <div class="col-lg-2" >
  <label>Limitar</label>
    <div class='col-lg-12'>
     
     <input class='numb' type="number" min="1" max="50000" id="limit" value="1000" required/>
     <p class="help-block">Maximo de 50000 registros</p>
    </div>
  </div>

</fieldset>

</div>

<div class="separador col-lg-12"></div>

<div class="col-lg-4" >
   <div class="col-xs-6">
   <button class="btn btn-blue btn-sm"  data-toggle="collapse" data-target="#par_filtro" onclick="javascript:  $(this).find('i').toggleClass('fa-plus-circle fa-minus-circle');"><i  class='fa fa-plus-circle'></i> Filtros</button>
   </div>

   <div class="col-xs-6">
   <input type="submit" onclick="Filtrar();" class="btn btn-primary  btn-sm  btn-icon icon-right" value="Consultar" />
   </div>      
</div>

 
</fieldset> 
<div class="separador col-lg-12"></div>


<script type="text/javascript">
  
function Filtrar(){


var limit = $('#limit').val();
var sort =  $('#sort').val();
var type =  $('#reportType').val();
var date1 = $('#date1').val();
var date2 = $('#date2').val();



URL = document.getElementById('URL').value;

var datos= "url=bridge_query/get_report/"+type+"/"+sort+"/"+limit+"/"+date1+"/"+date2;   
var link = URL+"index.php";


$('#table').html('<P>CARGANDO ...</P>');

  $.ajax({
      type: "GET",
      url: link,
      data: datos,
      success: function(res){
      
       $('#table').html(res);

       // alert(res);

        }
   });



}

function get_OC(id){

  URL = document.getElementById('URL').value;

var datos= "url=bridge_query/get_PO_details/"+id;  
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


}

function get_PL(id_PL,date,Desc) {

URL = document.getElementById('URL').value;

var datos= "url=bridge_query/get_PL_details/"+id_PL+"/"+date+"/"+Desc;  
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

var datos = 'url=bridge_query/del_PL_detail/'+id_PL;
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

</script>

<fieldset>
<div class="col-lg-12" > 
  <div id="table"></div>
</div>
</fieldset>
          

</div>  




</div>
</div>
</div>