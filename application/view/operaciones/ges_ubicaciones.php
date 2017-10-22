<script type="text/javascript">
  jQuery(document).ready(function($)
{
$("#crear_alm_imp").hide();
    
  document.getElementById("stock").addEventListener("change", setalamacen);

  function setalamacen(){

      ALAMCEN_ID = document.getElementById("stock").value;

      $("#alm_id").html('A'+ALAMCEN_ID);
  }

document.getElementById("stock_estand").addEventListener("change", setmueble);

  function setmueble(){

      mueble_ID = document.getElementById("stock_estand").value;

      $("#stand_id").html('M'+mueble_ID);
  }

document.getElementById("stock_column").addEventListener("change", setcolum);

function setcolum(){

      colum_ID = document.getElementById("stock_column").value;

       $("#colum_id").html('C'+colum_ID);
  }

document.getElementById("stock_row").addEventListener("change", setrow);

function setrow(){

      ROW_ID = document.getElementById("stock_row").value;

       $("#row_id").html('F'+ROW_ID);
  }



});




  
</script>
<div class="page col-xs-12">

<div  class="col-xs-12">
<!-- contenido -->
<h2>Ubicaciones</h2>
<div class="title col-xs-12"></div>
<div class="col-xs-12">

<div class="col-lg-12"> 

<fieldset>
  
<div class="col-lg-6"> 

<fieldset id="#ubicacionF" >
  <legend>Crear ubicacion</legend>

<div class="col-lg-12"> 
<div id="bodega">
 <label class="col-lg-3" >Almacen/Bodega</label>
  <div  class="col-lg-3">  
   <select class="form-control" id="stock" name="stock">
   <option desabled></option>
     <?php  

        $query="select * from almacenes where onoff='1';";

        $res = $this->model->Query($query); 

        foreach ($res as $datos) {
                                  
        $datos = json_decode($datos);
        echo "<option value='".$datos->{'id'}."'>".$datos ->{'name'}."</option>";

        }
    ?>       
 </select>

</div>

  <div class="col-lg-1"><a href="#"><i onclick="crear_almacen();" style="color: green;" class="fa fa-plus"></i></a></div>

 </div> 


  <div id="crear_alm_imp">
  <fieldset>
    <label style="margin-top: 10px;" class="col-lg-3" >Almacen/Bodega</label>
    <div   style="margin-top: 10px;" class="col-lg-3"> <input type="text" id="almacen" name="almacen"  />
  </div>
 <div class="col-lg-1"></div>
    
  <div class="col-lg-2">
     <button  onclick="save_alm('<?php echo URL; ?>');" style="margin-top: 3px;" class="btn btn-primary  btn-block text-left" type="submit" >ok</button>
  </div>
  <div class="col-lg-3">
     <button  onclick="javascript: location.reload(true);" style="margin-top: 3px;" class="btn btn-primary  btn-block text-left" type="submit" >Cancelar</button>
  </div>

  </fieldset>
 </div> 
 
    
  
 

</div>


<div class="separador col-lg-12"></div>

<div class="col-lg-12"> 


<div class="col-lg-4"> 
   <label class="col-lg-6" >Mueble</label>
   <input class="col-lg-6 form-control"  type="number" min="1" id="stock_estand" name="stock_estand" />
</div>

<div class="col-lg-4"> 
  <label class="col-lg-6">Columna</label>
 
   <select class="form-control" id="stock_column" name="stock_column">
   <option></option>
     <?php
        for ($char = 'A'; $char <= 'Z'; $char++) {
            echo "<option>".$char. "</option>";
        }
     ?>
   </select>

 </div>

<div class="col-lg-4"> 
 <label class="col-lg-6 ">Fila</label>
 <input  class="col-lg-6 form-control" type="number" min="1" id="stock_row" name="stock_row"  />
</div>

</div>

<div class="title col-lg-12"></div>

<div class="col-lg-9">
  <label  class="col-lg-2" >Ruta: </label>
  <strong>
  <div class="col-lg-1" id="alm_id" name="alm_id"></div>
  <div class="col-lg-1"  id="stand_id" name="stand_id" ></div>
  <div class="col-lg-1"  id="colum_id" name="colum_id"></div>
  <div class="col-lg-1"  id="row_id"  name="row_id"></div>
  <div class="col-lg-6" ></div>
  </strong>
</div>
<div class="separador col-lg-12"></div>
<div class="col-lg-6"></div> 
<div class="col-lg-3"> 
  <button onclick="set_location('<?php echo URL; ?>');" class="btn btn-primary  btn-block text-left" type="submit">Crear</button>
</div>
<div class="col-lg-3"> 
  <button onclick="javascript: location.reload(true);" class="btn btn-primary  btn-block text-left" type="submit">Limpiar</button>
</div>

</fieldset>
</div>


<div class="col-lg-6"> 
<fieldset  style="height: 46%" >
  <legend>Creados</legend>



<script type="text/javascript">
jQuery(document).ready(function($)
  {

var table = $("#ubicaciones").dataTable({
      aLengthMenu: [
        [10, 25,50,-1], [10, 25, 50,"All"]
      ]
    });

table.yadcf([
{column_number : 0},
{column_number : 1} 
]); 



});
</script>


<table id="ubicaciones" class="table table-striped" cellspacing="0"  >
            <thead>
              <tr>
                <th>Almacen</th>
                <th>Ruta</th>
                <th>Ver</th>
              </tr>
            </thead>

  
            <tbody>
            <?php
            $Item = $this->model->Query('SELECT * FROM ubicaciones where onoff="1"');

            foreach ($Item as $datos) {


            $Item = json_decode($datos);

            $item = "'".$Item->{'etiqueta'}."'";

            $clause = ' where onoff="1" and id="'.$Item->{'id_almacen'}.'"';

            $almacen = $this->model->Query_value('almacenes','name',$clause);

            $route_id= "'".$Item->{'id'}."'";

            $URL= "'".URL."'";

            echo  "<tr>
                  <td >".$almacen."</td>
                  <td >".$Item->{'etiqueta'}.'</td>
                  <td ><a href="#"" onclick="view_items('.$URL.','.$route_id.');" ><i class="fa  fa-search"></i></a></td>
                 </tr>';

            }?>


            </tbody>
          </table>
</fieldset>
</div>

</fieldset>
</div>


<div class="separador col-lg-12"></div>

<div  class="col-lg-12"> 
<fieldset>

<div  style="height: 78%" class="col-lg-12"> 

<fieldset >
  <legend>Items </legend>

<div class="col-lg-4">
<label>Filtrar por Almacen</label>
<select class="form-control" id="item_by_stock" name="item_by_stock">
<option selected disabled></option>
<?php  

$query="select * from almacenes where onoff='1';";

$res = $this->model->Query($query); 

foreach ($res as $datos) {
                          
$datos = json_decode($datos);
echo "<option value='".$datos->{'id'}."'>".$datos ->{'name'}."</option>";

}
?>
</select>
</div>

<div class="col-lg-2"></div>

<div class="col-lg-3">
<div class="separador col-lg-12"></div>
  <button onclick="FILTER('<?php echo URL; ?>');" class="btn btn-primary  btn-block text-left" type="submit" >Filtrar</button>
</div>

<div class="separador col-lg-12"></div>

<div id="items_by_stock" class="col-lg-12"></div>

</fieldset>

</div>

</fieldset>
</div>

</div>
</div>
</div>