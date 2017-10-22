
<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.js"></script>
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.css">





<script type="text/javascript">
$(window).load(function(){ 
  
  var text = $('#code').text()+$('#code2').text()+$('#code3').text();
 
  eval(text);
  prettyPrint();
  

  });

function foo(e) {

  // Create a new LI
  var newLi = document.createElement('li');

  // Get the element that the click came from
  var el = e.target || e.srcElement;

  // Get it's parent LI if there is one
  var p = getParent(el);
  if (!p) return;

  // Get child ULs if there are any
  var ul = p.getElementsByTagName('ul')[0];

  // If there's a child UL, add the LI with updated text
  if (ul) {

    // Get the li children ** Original commented line was buggy 
//    var lis = ul.getElementsByTagName('li');
    var lis = ul.childNodes;

    // Get the text of the last li
    var text = getText(lis[lis.length - 1]);

    // Update the innerText of the new LI and add it
    setText(newLi, text.replace(/\.\d+$/,'.' + lis.length));
    ul.appendChild(newLi);

  // Otherwise, add a UL and LI  
  } else {
    // Create a UL
    ul = document.createElement('ul');

    // Add text to the new LI
    setText(newLi, getText(p) + '.0');
  }
}
</script>


<div class="separador col-lg-12"></div>
<div class="col-lg-3"> 
<fieldset >
<legend><img class='icon' src="img/List.png" /><a href="<?PHP ECHO URL; ?>index.php?url=ges_reportes/rep_reportes">Reportes</a></legend>
<ul class='tree'>
    <li><img class='icon' src="img/List.png" /><a href="#">Ventas</a></li>
     <ul class='tree'>
          <li ><a href="<?PHP ECHO URL; ?>index.php?url=ges_ventas/ges_hist_ventas"><img class='icon' src="img/News.png" />Historial de ordenes</a></li>
          <li ><a href="<?PHP ECHO URL; ?>index.php?url=ges_ventas/ges_hist_sal_merc"><img class='icon' src="img/News.png" />Salida de mercancia</a></li> 
          <!-- <li ><a href="<?PHP ECHO URL; ?>index.php?url=ges_ventas/ges_pro_hist_ventas"><img class='icon' src="img/invoice.png" />Facturas de ventas</a></li> -->  
     </ul>     
   <!-- <li><img class='icon' src="img/List.png" /><a href="#">Ordenes de entregas</a></li> -->
</ul> 

 
</fieldset>
<div class="separador col-lg-12"></div>

<fieldset >

</fieldset>

</div>

<div class="col-lg-9"> 
<fieldset>
  <legend><img class='icon' src="img/Chart Pie.png" />Estadisticas</legend>
     <div class="graphcont  col-lg-12">
      <fieldset>
        
        <div id="graph"></div>

      </fieldset>
         
      </div>


      <div class="graphcont col-lg-7">
        <fieldset>
        
        <div id="graph2"></div>

      </fieldset>
      </div>

      <div class="graphcont  col-lg-5">
       <fieldset>
        
        <div id="graph3"></div>

      </fieldset>
      </div>
</fieldset>

 
</div>


<!-- <div class="graphcont  col-lg-4">
  <fieldset>
  
  <div id="graph4"></div>

</fieldset>
</div> -->



<?php

$currentMoth = date('n');
$currentYear = date('Y');
$currentdate = date('m-d-Y');

$query = 'SELECT  date, SUM(Net_due) as Total, month(date) as  mes, year(date) as  year FROM `SalesOrder_Header_Imp`
inner JOIN `SalesOrder_Detail_Imp` ON SalesOrder_Header_Imp.SalesOrderNumber = SalesOrder_Detail_Imp.SalesOrderNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = SalesOrder_Header_Imp.user where SalesOrder_Header_Imp.Enviado = "1" and SalesOrder_Header_Imp.Error = "0" GROUP BY SalesOrder_Header_Imp.date';


$GetOrder=$this->model->Query($query);


if (!$GetOrder) {

  $table =  "{x: '".$currentdate."', z: '0' , y: '0' },";

} 

 $y= '';
 $z= '';
foreach ($GetOrder as $value) { 
  
  $value =json_decode($value);

$MES_ACTUAL =$currentMoth;
$MES_ANTERIOR =$currentMoth-1;

  if($value->{'year'}==$currentYear) {


    if ($value->{'mes'}==$MES_ACTUAL) {

    $table .=  "{x: '".$value->{'date'}."', y: '".$value->{'Total'}."'},";

    $y = $y + 1;

    }

    if ($value->{'mes'}==$MES_ANTERIOR) {

    $table .=  "{x: '".$value->{'date'}."', z: '".$value->{'Total'}."'},";
     
    $z = $z + 1;
    }

   

  }  }

if($y >= '1'){

echo "<pre  id='code' class='prettyprint linenums'>
       // Use Morris.Bar
        Morris.Bar({
          element: 'graph',
          axes: false,
          data: [ ".$table."],
          xkey: 'x',
          ykeys: ['y','z'],
          labels: ['Mes corriente','Mes Anterior']
        });
    </pre>
    <pre id='code2' class='prettyprint linenums'>

    // Use Morris.Area instead of Morris.Line
    Morris.Area({
      element: 'graph2',
      behaveLikeLine: true,
      data:  [ ".$table."],
      xkey: 'x',
      ykeys: ['y', 'z'],
      labels: ['Mes corriente','Mes Anterior']
    });
</pre> ";

} 


$query='SELECT SUM(Net_due) as Total, month(date) as  mes, year(date) as  year, CustomerName FROM `SalesOrder_Header_Imp`
inner JOIN `SalesOrder_Detail_Imp` ON SalesOrder_Header_Imp.SalesOrderNumber = SalesOrder_Detail_Imp.SalesOrderNumber
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = SalesOrder_Header_Imp.user where SalesOrder_Header_Imp.Enviado = "1" and SalesOrder_Header_Imp.Error = "0"  GROUP BY mes order by Total DESC';


$totalMensual = $this->model->Query($query);

if (!$totalMensual) {

	 $Seller .='{value: 0, label: "NO EXISTE DATOS EN EL MES CORRIENTE", formatted: "0%" },';

} 

foreach ($totalMensual as $value2) {	

$value2 =json_decode($value2);

if ($value2->{'year'}==$currentYear && $value2->{'mes'}==$currentMoth ) 
   {
    $query = 'SELECT SUM(Net_due) as Total, month(date) as  mes  FROM `SalesOrder_Header_Imp`
		inner JOIN `SalesOrder_Detail_Imp` ON SalesOrder_Header_Imp.SalesOrderNumber = SalesOrder_Detail_Imp.SalesOrderNumber
		inner JOIN `SAX_USER` ON `SAX_USER`.`id` = SalesOrder_Header_Imp.user where month(date)="'.$currentMoth.'" GROUP BY mes order by Total DESC';

			$totalSold = $this->model->Query($query);

			foreach ($totalSold  as $value) {
				$totalSold = json_decode($value);

				$total = $totalSold->{'Total'};

            $perc = ($total*100)/$value2->{'Total'};

		    $perc = number_format($perc,2);

        $Seller .='{value: '.$perc.', label: "'.$value2->{'CustomerName'}.'", formatted: "'.$perc.'%" },';
		
			}

        }else{


        $Seller .='{value: 0, label: "NO EXISTE DATOS EN EL MES CORRIENTE", formatted: "0%" },';

        }
}
?>
<pre id="code3" class="prettyprint linenums">
     Morris.Donut({
      element: 'graph3',
      data: [
        <?php  echo $Seller; ?>
       
      ],
      formatter: function (x, data) { return data.formatted; }
    });
</pre> 

</body>