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
if($value->{'mod_req'}=='1'){ $mod_req_CK = 'checked'; }else{ $mod_req_CK = '';  }
}
?>

<!--ADD JS FILE-->
<script  src="<?php echo URL; ?>js/operaciones/reporte/rep_reportes.js" ></script>


<div class="page col-xs-12">

<!--INI DIV ERRO-->
<div id="ERROR" ></div>
<!--INI DIV ERROR-->

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
<?php   if($mod_req_CK  == 'checked'){?>          
           <optgroup label="Requisiciones">
       <?php if($this->model->rol_campo=='1' || $this->model->rol_compras=='1' ){ ?>
           <option  value="ReqStat" >REQUISICIONES</option>
       <?php } 
           if($this->model->rol_compras=='1' ){
       ?>
           <option  value="PurOrd"  >ORDENES DE COMPRA</option>
        <?php  } ?>
           </optgroup>
<?php } ?>    
<?php   if($mod_fact_CK  == 'checked'){
             if($this->model->rol_compras=='1' ){  ?>         
           <optgroup label="Compras">
           <option  value="PurFact" >FACTURAS DE COMPRA</option>
           <option  value="PurOrd"  >ORDENES  DE COMPRA</option>
           </optgroup>
<?php } }?>  

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
     
     <input class='numb' type="number" min="1" max="50000" id="limit" value="10000" required/>
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

<!-- Modal : VENTANA EMERGENTE QUE PERMITE MODIFICAR UN ITEM ESPECIFICO-->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 >Modificar Item</h3>
      </div>

      <div class="col-lg-12 modal-body">
        
      <div id='prod'></div>

        <div class="col-lg-3" > 
             <label class="control-label">ID Item: </label>
             <input  class="form-control" id="item_id_modal" name="item_id_modal"  readonly/>
             <input type="hidden" class="form-control" id="PL_id" name="PL_id"/>
        </div>
        
        <div class="col-lg-2" > 
             <label class="control-label">Precio: </label>
             <input type="number" class="form-control numb" id="price_id_modal" name="price_id_modal"/>
        </div>
        <div class="col-lg-2" > 
             <label class="control-label">Unidad: </label>
             <input  class="form-control" id="unit_id_modal" name="unit_id_modal" readonly/>
        </div>

        <div class="form-group col-lg-5" > 
              <label class="control-label" >Descripcion:</label>
              <input class="form-control col-lg-10" id="desc_id_modal" name="desc_id_modal"/>
        </div> 
        

<div class="col-lg-12" ></div>    
      </div>
      <div class="modal-footer">
        <button type="button" onclick="mod_item();" class="btn btn-primary" data-dismiss="modal">Modificar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>

<div class="separador col-lg-12"></div>


<!-- Modal : VENTANA EMERGENTE QUE PERMITE MODIFICAR UN ITEM ESPECIFICO-->
<div id="modal_additem" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 >Agregar Item</h3>
      </div>

      <div class="col-lg-12 modal-body">
        
      <div id='prod'></div>

        <div class="col-lg-3" > 
             <label class="control-label">ID Item: </label>
             <input  class="form-control" id="item_id_modal_2" name="item_id_modal_2"  type="text" />
             <input type="hidden" class="form-control" id="PL_id_2" name="PL_id_2"/>
        </div>
        
        <div class="col-lg-2" > 
             <label class="control-label">Precio: </label>
             <input type="number" class="form-control numb" id="price_id_modal_2" name="price_id_modal_2"/>
        </div>
        <div class="col-lg-2" > 
             <label class="control-label">Unidad: </label>
             <input class="form-control" id="unit_id_modal_2" name="unit_id_modal_2" />
        </div>

        <div class="form-group col-lg-5" > 
              <label class="control-label" >Descripcion:</label>
              <input class="form-control col-lg-10" id="desc_id_modal_2" name="desc_id_modal_2"/>
        </div> 
        

<div class="col-lg-12" ></div>    
      </div>
      <div class="modal-footer">
        <button type="button" onclick="add_item();" class="btn btn-primary" data-dismiss="modal">Agregar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>


<fieldset>
<div class="col-lg-12" > 
  <div id="table"></div>
</div>
</fieldset>
          

</div>  




</div>
</div>
</div>