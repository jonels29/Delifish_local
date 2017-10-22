<?php

$NO_LINES =  $this->model->Query_value('FAC_DET_CONF','NO_LINES','where ID_compania="'.$this->model->id_compania .'"');

echo '<input type="hidden" id="FAC_NO_LINES" value="'.$NO_LINES .'" />'; 

$pice_mod_ck = $this->model->Query_value('SAX_USER','mod_price','where SAX_USER.onoff="1" and SAX_USER.id="'.$this->model->active_user_id.'"');

if ($pice_mod_ck == 1) {

  echo '<input type="hidden" id="editable" value="contenteditable" />'; 

}else{

  echo '<input type="hidden" id="editable" value="" />'; 

}

?>



<!--modal-->

<div id="AuthLogin" class="modal fade" role="dialog">

  <div class="modal-dialog modal-lg">

    <!-- Modal content-->

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h3 >Credenciales de autorización</h3>

      </div>

      <div class="col-lg-12 modal-body">

      <!--ini Modal  body-->  

            <div class="form-group col-lg-12">

              <label class="control-label" for="username">Usuario</label>

              <input type="text" class="form-control" id="user" name="user"  autocomplete="off" />

            </div>            

            <div class="form-group col-lg-12">

              <label class="control-label" for="passwd">Password</label>

              <input type="password" class="form-control" name="pass" id="pass" autocomplete="off" />

            </div>

      <!--fin Modal  body-->

      </div>

      <div class="modal-footer">

        <button type="button" onclick="javascript:mod_price_auth();" data-dismiss="modal" class="btn btn-primary" >Aceptar</button>

        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

      </div>

    </div>

  </div>

</div>

<!--modal-->

<div class="page col-lg-12">

<!--INI DIV ERRO-->

<div id="ERROR" class="alert alert-danger"></div>

<!--INI DIV ERROR-->

<div  class="col-lg-12">

<!-- contenido -->

<h2>Nota de Credito</h2>

<div class="title col-lg-12"></div>

<div class="separador col-lg-12"></div>

<input type="hidden" id='URL' value="<?php ECHO URL; ?>" />

<input type="hidden"  id="saletaxid"  value="" />

<input type="hidden"  id="listID"  value="" />

<!--contenido-->

<div class="col-lg-12">

<!--INI cabecera-->

<div class="col-lg-12">

    <fieldset>

    <legend><h4>Informacion General</h4></legend>

        <div class="col-lg-12"> 

           <div class="col-lg-6">

           <fieldset>

           <p><strong>Cliente</strong></p>

            <select  id="customer" name="customer" class="select col-lg-8" onchange="SetAttri(this.value);" required>

            <option selected disabled></option>

            <?php  

            $CUST = $this->model-> get_ClientList(); 

            foreach ($CUST as $datos) {

            $CUST_INF = json_decode($datos);

            echo '<option value="'.$CUST_INF->{'ID'}.'" >'.$CUST_INF->{'CustomerID'}.' - '.$CUST_INF->{'Customer_Bill_Name'}."</option>";

            }

            ?>

         </select>  

         </fieldset>

         </div>

         <div class="col-lg-2" >

         <fieldset>

           <p><strong>Entrega a:</strong></p>

            <input class="col-lg-12" id="entrega" onkeyup="checkNOTA(this.id);" name="entrega" />  

         </fieldset>

         </div>

        <div class="col-lg-2">

         <fieldset>

          <p><strong>No. PO: </strong><p>

            <input  class="col-lg-12" id="nopo" onkeyup="checkNOTA(this.id);" name="nopo"/>

         </fieldset>

        </div> 

         <div class="col-lg-2" >

         <fieldset>

             <p><strong>Tipo de Licitacion</strong></p>

                <input class="col-lg-12" id="tipo_licitacion" onkeyup="checkNOTA(this.id);" name="tipo_licitacion"/> 

         </fieldset>

         </div>

         <div class="separador col-lg-12"></div>

         <div class="col-lg-4" >

           <fieldset>

             <p><strong>Observaciones</strong></p>

               <textarea class="col-lg-12" onkeyup="checkNOTA(this.id);"  rows="2" id="observaciones" name="observaciones"></textarea> 

         </fieldset> 

         </div>


         <div class="col-lg-2" >

         <fieldset>

             <p><strong>Fecha de entrega</strong></p>

               <input  class="col-lg-12" id="fecha_entrega" onkeyup="checkNOTA(this.id);" name="fecha_entrega" />

         </fieldset>

         </div>

         <div class="col-lg-3" >

         <fieldset>

             <p><strong>Terminos de pago</strong></p>

               <input  class="col-lg-12" id="termino_pago" onkeyup="checkNOTA(this.id);" name="termino_pago" readonly />

         </fieldset>

         </div>

         <div class="col-lg-3" >

         <fieldset>

             <p><strong>Tax ID</strong></p>

               <select  id="taxid" name="taxid" class="select col-lg-12" onchange="set_taxid(this.value,2);" required>

            <?php  

            $tax = $this->model->Get_sales_conf_Info(); 

            foreach ($tax  as $datos) {

              $tax  = json_decode($datos);

              if($tax->{'taxid'}=='ITBMS'){

                $selected = 'selected';

              }else{   

                 $selected = '';

              }

            echo '<option value="'.$tax ->{'rate'}.'" '.$selected.'>'.$tax->{'taxid'}.'</option>';

            }

            ?>

           </select>

         </fieldset>

       </div>
       <div class="separador col-lg-12"></div>
         <div class="col-lg-3" >

         <fieldset>
         <p><strong>No. Serial Impresora </strong></p>
            <select class='select col-lg-12' id='serial' name='serial' >
            <option value="" selected></option>
              <?PHP 

                   $list = $this->getPrinterList();
                   $Printers = '';
                   foreach ($list as $key => $value) {
                    $value = json_decode($value);

                      $Printers .= '<option value="'.$value->{'SERIAL'}.'">'.$value->{'SERIAL'}.'</option>';

                   }
                   
                   echo $Printers;

               ?>
            </select>
         </fieldset>

       </div>
       <div class="col-lg-3" >
         <fieldset>
         <p><strong>No. Factura Fiscal</strong></p>
         <input type="text" id="nofact" name="nofact" class="col-lg-8" maxlength="10" />

         </fieldset>

       </div>
        <div class="col-lg-4">
          <fieldset>
          <p><strong>Imprimir en: </strong></p>
            <select class='select col-lg-12' id='Printer' name='Printer' >
            <option value="" selected></option>
              <?PHP 

                   $list = $this->getPrinterList();
                   $DefPrint = $this->GetUserDefaultPrinter();
                   $Printers = '';

                   foreach ($list as $key => $value) {
                   
                    $value = json_decode($value);

                    if($DefPrint == $value->{'SERIAL'}){ $selected = 'selected'; }else { $selected = ''; }

                      $Printers .= '<option value="'.$value->{'SERIAL'}.'"  '.$selected.' >'.$value->{'DESCRIPCION'}.' ( '.$value->{'SERIAL'}.') </option>';

                   }
                   
                   echo $Printers;

               ?>
            </select>
          </fieldset>
          </div>
       <div class="col-lg-5" ></div>

  </div>

 <div class="separador col-lg-12"> </div>

 <div class="col-lg-10"> </div>

  <div  class="col-lg-2">

       <input type="submit" onclick="SetNota();" class="btn btn-primary  btn-sm btn-icon icon-right" value="Imprimir" />

  </div>

</fieldset>

</div>

<!--fin cabecera-->

<div class="separador col-lg-12"> </div>

<?php 

if($pice_mod_ck!=1){ ?>

  <div  class="col-lg-10"></div>

  <div  class="col-lg-2">

       <input data-toggle="modal" data-target="#AuthLogin" type="submit" class="btn btn-primary  btn-sm btn-icon icon-right" value="Aut. Cambio" />

  </div>

<?php }  ?>

<div class="separador col-lg-12"> </div>

<!--ini tabla-->

<div class=" col-lg-12"> 

<fieldset class="table_req" >

<table id="table_ord_tb" class="display table table-striped table-condensed table-bordered " cellspacing="0">

  <thead>

    <tr >

      <th width="10%" >Item ID

<!--       <select id="check_val" onchange="init(this.value);">

      <option value="1" >Renglon</option>

      <option value="2" >Codigo</option> 

      </select> -->

      </th>

      <th width="15%" class="text-center">Descripcion</th>

      <th width="15%" class="text-center">Nota</th>

      <th width="3%"  class="text-center">Chico</th>

      <th width="3%"  class="text-center">Grande</th>

      <th width="5%" class="text-center">Cant.</th>

      <th width="5%" class="text-center">Precio Unit.</th>

      <th width="5%" class="text-center">Total</th>

    </tr>

  </thead>

  <tbody id="table_req" > 

  </tbody>

</table>

</fieldset>

<div  class="separador col-lg-12" ></div>

<div  class="col-lg-8" ></div>

<div  class="col-lg-4" >

<fieldset>

<div class="col-lg-7" >

  <label class="col-lg-12" >Sub - Total:</label>

    <label class="col-lg-12" >ITBMS: </label>

    <label class="col-lg-12" >Total: </label>

</div>

<div class="col-lg-5" >

  <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="subtotal" name="subtotal"  value="0.00" readonly />

  <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="tax" name="tax" value="0.00" readonly/> 

  <input class="col-lg-12"  style="text-align:right;" type="number"  step="0.01" id="total" name="total" value="0.00" readonly />

</div>

</fieldset>

</div>

<div  class="separador col-lg-12" ></div>

</div>

<!--fin tabla-->

</div>

<!--fin contenido-->

</div>

</div>

<!--ADD JS FILE-->
<script  src="<?php echo URL; ?>js/operaciones/notasdecredito/ges_notasdecredito.js" ></script>

