 <script type="text/javascript">
$(window).load(function(){
$('[data-submenu]').submenupicker();
});
</script> 

<?php
//RECUPERO INFO DE DETALLES DE MODULOS ACTIVOS
$SQL = 'SELECT * FROM MOD_MENU_CONF';

$MOD_MENU = $this->model->Query($SQL);

foreach ($MOD_MENU as $value) {

$value = json_decode($value);

ECHO $value->{'closeSO'};

if($value->{'mod_sales'}=='1'){ $mod_sales_CK = 'checked';  }else{ $mod_sales_CK = '';   }
if($value->{'mod_invo'}=='1') { $mod_invo_CK  = 'checked';  }else{ $mod_invo_CK  = '';   }
if($value->{'mod_fact'}=='1') { $mod_fact_CK  = 'checked';  }else{ $mod_fact_CK  = '';   }
if($value->{'mod_invt'}=='1') { $mod_invt_CK  = 'checked';  }else{ $mod_invt_CK  = '';   }
if($value->{'mod_rept'}=='1') { $mod_rept_CK  = 'checked';  }else{ $mod_rept_CK  = '';   }
if($value->{'mod_stock'}=='1'){ $mod_stoc_CK  = 'checked';  }else{ $mod_stoc_CK  = '';   }
if($value->{'mod_pro'}=='1' )  { $mod_pro_CK   = 'checked';  }else{ $mod_pro_CK  = '';   }
if($value->{'mod_req'}=='1' )  { $mod_req_CK   = 'checked';  }else{ $mod_req_CK  = '';   }



}


$res = $this->model->Query('SELECT * FROM SAX_USER  where SAX_USER.onoff="1" and SAX_USER.id="'.$this->model->active_user_id.'";');
 
foreach ($res as $value) {

  $value = json_decode($value);

  $INF_OC= $value->{'notif_oc'};
  $INF_FC= $value->{'notif_fc'};
  $INF_PRICE= $value->{'mod_price'};
  $INF_INV= $value->{'inv_view'};
  $INF_STO= $value->{'stoc_view'};
  $INF_REP= $value->{'rep_view'};
  $PHOTO  = $value->{'photo'};
  $close_sales_ck = $value->{'closeSO'};

  if($PHOTO == 'x'){

   $user_avatar = URL.'img/user_avatar/'.$this->model->active_user_id.'.jpg';

  }else{

   $user_avatar = URL.'img/default-avatar.png';

  }


  if($INF_OC==1){//notificaciones requisiciones

  $notif_oc = 'checked';

  }else{

  $notif_oc = ''; 
  }

  if($INF_FC==1){//notificaciones acturas

  $notif_fc = 'checked';

  }else{

  $notif_fc = ''; 
  }

   if($INF_PRICE==1){//modificar precio

  $price_mod = 'checked';

  }else{

  $price_mod = '';  
  }

  if($INF_INV==1){//ver inventario

  $INV_CK = 'checked';

  }else{

  $INV_CK = ''; 
  }

  if($INF_STO==1){//ver inventario

  $STO_CK = 'checked';

  }else{

  $STO_CK = ''; 
  }

  if($INF_REP==1){//ver inventario

  $REP_CK = 'checked';

  }else{

  $REP_CK = ''; 
  }


}




?>
<div  class='menu_header col-xs-12'>

<nav id='menu' class="navbar navbar-default">
  <div class="navbar-header">
    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>

   <a  class="navbar-brand" onClick="history.go(-1); return true;" ><img title="Atras" class='icon' src="img/Arrow Left.png" /></a>
   <a  class="navbar-brand" onClick="history.go(+1); return true;" ><img title="Adelante" class='icon' src="img/Arrow Right.png" /></a>
   <a  class="navbar-brand" onClick="location.reload();" ><img title="Actualizar" class='icon' src="img/Button White Load.png" /></a>
   <a  class="navbar-brand" href="<?PHP ECHO URL; ?>index.php?url=home/index"><img title="Dashboard"  class='icon' src="img/Dashboard.png" /></a>
  </div>


<div class="collapse navbar-collapse">

<ul class="nav navbar-nav">

<?php   if($mod_sales_CK == 'checked'){?>
<li class="dropdown">
        <a tabindex="0"  data-toggle="dropdown" data-submenu="" aria-expanded="false">
          <img class='icon' src="img/Chart Bar.png" />Ventas<span class="caret"></span>
        </a>

<ul class="dropdown-menu">
<?php if($mod_invt_CK == 'checked'){ ?>
  <li><a tabindex="0" href="<?PHP ECHO URL; ?>index.php?url=ges_ventas/ges_orden_ventas"><img class='icon' src="img/Document Checklist.png" />Orden de Venta</a></li> 

<?php }else{ ?>

  <li><a tabindex="0" href="<?PHP ECHO URL; ?>index.php?url=ges_ventas/ges_orden_ventas_direct"><img class='icon' src="img/Document Checklist.png" />Pedidos</a></li>
  <li><a tabindex="1" href="<?PHP ECHO URL; ?>index.php?url=ges_niveles_prec/gestion_precios"><img class='icon' src="img/Document Checklist.png" />Lista de Precios</a></li>

<?php } if($mod_invo_CK == 'checked'){ ?>
   
  <li><a tabindex="1" href="<?PHP ECHO URL; ?>index.php?url=ges_invoice/Init"><img class='icon' src="img/Printer.png" />Ordenes por Facturar</a></li>
  <li><a tabindex="1" href="<?PHP ECHO URL; ?>index.php?url=ges_notasdecredito/Init"><img class='icon' src="img/Printer.png" />Notas de Credito</a></li>
<?php } ?>
</ul>
</li>
<?php }  if($mod_fact_CK == 'checked'){ ?>

<li class="dropdown">
        <a tabindex="0"  data-toggle="dropdown" data-submenu="" aria-expanded="false">
          <img class='icon' src="img/invoice.png" />Compras<span class="caret"></span>
        </a>

<ul class="dropdown-menu">

  <li><a tabindex="0" href="<?PHP ECHO URL; ?>index.php?url=ges_compras/crear_fact"><img class='icon' src="img/Document Checklist.png" />Factura de Compras</a></li>

</ul>
</li>
<?php } ?>

<?php   if($mod_pro_CK == 'checked'){ ?>
<!--AQUI VA PRESUPUESTO-->
<?php } ?>


<?php   if($mod_invt_CK == 'checked' and $INF_INV==1 ){?>
<li class="dropdown">
        <a tabindex="0"  data-toggle="dropdown" data-submenu="" aria-expanded="false">
          <img class='icon' src="img/Products.png" />Inventario<span class="caret"></span>
        </a>

<ul class="dropdown-menu">
  <li><a tabindex="0" href="<?PHP ECHO URL; ?>index.php?url=ges_inventario/inv_list"><img class='icon' src="img/Box.png" />Inventario</a></li> 
  <li><a href="<?PHP ECHO URL; ?>index.php?url=ges_consignaciones/con_crear"><img class='icon' src="img/Consignacion.png" />Consignaciones</a></li>
   <li><a tabindex="0"  href="<?PHP ECHO URL; ?>index.php?url=ges_ventas/ges_sal_merc"><img class='icon' src="img/Box Up.png" />Salida de mercancia</a></li> 
</ul>
</li>
<?php } ?>


<?php  if($mod_req_CK == 'checked' ){

if($this->model->rol_campo=='1'){ ?>
  
<li class="dropdown">
        <a tabindex="0"  data-toggle="dropdown" data-submenu="" aria-expanded="false">
          <img class='icon' src="img/Products.png" />Requisiciones<span class="caret"></span>
        </a>

<ul class="dropdown-menu">
  <li><a tabindex="0" href="<?PHP ECHO URL; ?>index.php?url=ges_requisiciones/req_crear"><img class='icon' src="img/Box Add.png" />Requisición</a></li>
  <li><a tabindex="0" href="<?PHP ECHO URL; ?>index.php?url=ges_requisiciones/req_reception/0"><img class='icon' src="img/Box Down.png" />Recepción</a></li>
</ul>
<?php } } ?>

<?php   if($mod_stoc_CK  == 'checked' and $INF_STO==1){?>
<li class="dropdown">
        <a tabindex="0"  data-toggle="dropdown" data-submenu="" aria-expanded="false">
          <img class='icon' src="img/Stock.png" />Almacen<span class="caret"></span>
        </a>

<ul class="dropdown-menu">

  <li><a tabindex="0" href="<?PHP ECHO URL; ?>index.php?url=ges_ubicaciones/location"><img class='icon' src="img/Maps.png" />Ubicaciones</a></li>

</ul>
</li>
<?php } ?>


<?php   if($mod_rept_CK  == 'checked' and $INF_REP==1){?>
<li class="dropdown">
        <a tabindex="0" data-toggle="dropdown" data-submenu="">
         <img class='icon' src="img/Chart Pie.png" />Reportes<span class="caret"></span>
        </a>

  <ul class="dropdown-menu" > 
  <?php   if($mod_sales_CK == 'checked'){?>
   
    <li ><a tabindex="1" href="<?PHP ECHO URL; ?>index.php?url=ges_ventas/ges_hist_salesorder"><img class='icon' src="img/News.png"   />Ordenes de Ventas/Pedidos</a></li>
    <li ><a tabindex="2" href="<?PHP ECHO URL; ?>index.php?url=ges_ventas/ges_hist_sales"><img class='icon' src="img/News.png"   />Facturas de Ventas</a></li>
    <li ><a tabindex="3" href="<?PHP ECHO URL; ?>index.php?url=ges_notasdecredito/ges_hist_creditmemo"><img class='icon' src="img/News.png"   />Notas de Credito</a></li>
    <li ><a tabindex="4" href="<?PHP ECHO URL; ?>index.php?url=ges_ventas/ges_reporte_diario"><img class='icon' src="img/News.png"   />Reporte Diario</a></li>
  

  <?php } ?>
  <?php   if($mod_invt_CK == 'checked' or $mod_sales_CK == 'checked' ){?>

   
    <li ><a tabindex="0" href="<?PHP ECHO URL; ?>index.php?url=ges_ventas/ges_hist_sal_merc"><img class='icon' src="img/News.png" />Salida de Mercancia</a></li> 
  
  <?php } ?>
 <!--    <li ><a tabindex="0" href="<?PHP ECHO URL; ?>index.php?url=ges_ventas/ges_pro_hist_ventas"><img class='icon' src="img/invoice.png" />Facturas de ventas</a></li>  -->
    <li class="divider"></li>
    <li><a tabindex="0" href="<?PHP ECHO URL; ?>index.php?url=ges_reportes/rep_reportes"><img class='icon' src="img/Chart Pie.png" />Otros</a></li> 
  </ul>
</li>
<?php } ?>

</ul>




     <!--left side-->
        <ul class="nav navbar-nav navbar-right">
           <li class="dropdown">
                <a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">
                <img class='icon profile'  src="<?php echo  $user_avatar; ?>" /> <?php echo $this->model->active_user_name.' '.$this->model->active_user_lastname; ?><span class="caret"></span>
                </a>

        <ul class="dropdown-menu">
          
        <li><a tabindex="0" title="Ir al perfil de usuario"  href="<?PHP ECHO URL; ?>index.php?url=home/edit_account/<?php echo $this->model->active_user_id; ?>"><img class='icon' src="img/Contact.png" />Perfil&nbsp;&nbsp;</a></li>

        <?php if($this->model->active_user_role=='admin'){?>
        <li><a tabindex="0" title="Administrar Usuarios" href="<?PHP ECHO URL; ?>index.php?url=home/accounts" ><img class='icon' src="img/Users.png" />Perfiles</a></li>
        <li><a tabindex="0" title="Configuracion"  href="<?PHP ECHO URL; ?>index.php?url=home/config_sys" ><img  class='icon' src="img/Cog.png" />Configuracion</a></li>
        <?php } ?>
                 
        <li class="divider"></li>

        <li><a  title="Salir del sistema" href="<?PHP ECHO URL; ?>index.php?url=login/login_out/" ><img  class='icon' src="img/Shut.png" />Salir</a></li>

        </ul>
      </li>
    </ul>
  </div>
</nav>

</div>


