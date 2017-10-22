<div class="page col-xs-12">

<div  class="col-xs-12">
<!-- contenido -->
<h2>Historial de ventas</h2>
<div class="title col-xs-12"></div>
<div class="col-xs-12">

<div class="col-lg-12">
  <fieldset>
   <div class="separador col-lg-12"></div>
   
   

  <div class="col-lg-5" >
    
     <label>Registros entre</label>
     <input type="date" id="date1" name="name1" />
     <label>y</label>
     <input type="date" id="date2" name="name2" />
   
  </div>

  <div class="col-lg-3" >
    
     <label>Sortear</label>
     <select   id="sort" required>
           
           <option            value="ASC">Ascendente (A-Z)</option>
           <option  selected value="DESC">Descendente (Z-A)</option>
         
    </select>
   
  </div>

  <div class="col-lg-2" >
    
     <label>Limitar</label>
     <input type="number" min="1" max="10000" id="limit" value="100" requerid/>
     <p class="help-block">Maximo de 10000 registros</p>
   
  </div>




  <div class="col-lg-2">

  <input type="submit" onclick="Filtrar();" class="btn btn-primary  btn-sm btn-icon icon-right" value="Consultar" />
  
  </div>  


 


</fieldset> 
<div class="separador col-lg-12"></div>

<script type="text/javascript">
  
function Filtrar(){


var limit = $('#limit').val();
var sort = $('#sort').val();
var date1 = $('#date1').val();
var date2 = $('#date2').val();


var datos= "url=bridge_query/Get_SalesOrders/"+sort+"/"+limit+"/"+date1+"/"+date2;
  
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



}


</script>

 <fieldset>

<div id="table"></div>


</fieldset>
 


<div id="info"></div>

</div>
</div>
</div>
</div>