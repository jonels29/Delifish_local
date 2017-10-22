


<script type="text/javascript">
$(window).load(function(){ 
  
  var text = $('#code').text();
 // $('#code2').text()+$('#code3').text();
  eval(text);
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
<div class="col-lg-2"> 
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
</div>

<div class="col-lg-10"> 
<fieldset>
  <legend><img class='icon' src="img/Chart Pie.png" />Estadisticas</legend>
     
     <div class="graphcont  col-lg-5">
      <fieldset>
        <legend>Facturación Mensual<p class="help-block">(Por Periodo, año corriente)</p></legend>
        <div id="graph"></div>
      </fieldset>
      </div>

      <div class="graphcont  col-lg-7">
       <fieldset>
        <legend>Porcentage de ventas por cliente <p class="help-block">(Periodo actual - primeros 10)</p></legend>
        
          <canvas id="cvs"  width="800px" height="360px">[No canvas support]</canvas>
           
      <!--   <div id="graph3"></div> -->            
            <?PHP
            $this->model->verify_session();
            $idCompania = $this->model->id_compania;
            
            $currentMoth = date('n');
            $currentYear = date('Y');
            $currentdate = date('m-d-Y');

            $query1 = 'SELECT  date, SUM(Net_due) as Total, month(date) as  mes, year(date) as  year
                         FROM `Sales_Header_Imp`
                         INNER JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = Sales_Detail_Imp.InvoiceNumber and Sales_Header_Imp.ID_compania = Sales_Detail_Imp.ID_compania
                         WHERE Sales_Header_Imp.Enviado = "1" and Sales_Header_Imp.Error = "0"  and month(date)="'.$currentMoth.'" and Sales_Header_Imp.ID_compania="'.$idCompania .'"';


            $totalSales = $this->model->Query($query1);
            $totalSales = json_decode($totalSales[0]);

               $query = 'SELECT SUM(Net_due) as Total, month(date) as  mes , CustomerName FROM `Sales_Header_Imp`
                inner JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = Sales_Detail_Imp.InvoiceNumber
                and Sales_Header_Imp.ID_compania = Sales_Detail_Imp.ID_compania
                where month(date)="'.$currentMoth.'" and Sales_Header_Imp.ID_compania="'.$idCompania .'" 
                GROUP BY mes, CustomerName order by Total DESC limit 10';

                  $totalSold = $this->model->Query($query);

                  foreach ($totalSold  as $value) {

                    $totalSold = json_decode($value);

                    $total = $totalSold->{'Total'};

                    $perc = ($total*100)/$totalSales->{'Total'};

                    $perc = number_format($perc,2);

                    $data .= $perc.',';
                    $labels .= "'".$totalSold->{'CustomerName'}."',";

                
                  }
                

            if($data!=''){  
            ?>
              

             <script>
                new RGraph.HBar({
                    id: "cvs",
                    data: [<?php echo trim($data); ?>],
                    options: {
                        gutterLeftAutosize: true,
                        vmargin: 5,
                        backgroundGridHlines: false,
                        backgroundGridBorder: false,
                        labelsAboveDecimals: 2,
                        labelsAbove: true,
                        noaxes: true,
                        colors: ['#FDB515','#164366'],
                        unitsPost: '%',
                        xmax: 100,
                        textAccessible: true,
                        labels:[<?php echo $labels; ?>],
                        textSize: 10
                    }
                }).wave();
             </script>
            
            <?php } ?>
      </fieldset>
      </div>
     <div class="separador col-lg-12" ></div>
     <div class="graphcont  col-lg-8">
      <fieldset>
        <legend>Ordenes de ventas Abiertas vs facturadas <p class="help-block">(Por Periodo, año corriente)</p></legend>
        
         <div id="container" style="display: inline-block; position: relative">
              <canvas id="cvs2" width="550" height="350"> [No canvas support] </canvas>        
        </div>     
       <?PHP
             $query = 'SELECT COUNT(*) AS CUENTA, date, month(date)  FROM `SalesOrder_Header_Imp` where month(date)="'.$currentMoth.'" and ID_compania="'.$idCompania .'"';
             $totalSO = $this->model->Query($query);
             $totalSO = json_decode($totalSO[0]);

             $total100 = $totalSO->{'CUENTA'};

             $query = 'SELECT COUNT(*) AS CUENTA, date, month(date)  FROM `Sales_Header_Imp` where month(date)="'.$currentMoth.'" and ID_compania="'.$idCompania .'"';
             $totalInv = $this->model->Query($query);
             $totalInv =json_decode($totalInv[0]);

             $totaInv = $totalInv->{'CUENTA'};

             $totalSO =  $total100 -  $totaInv;

             $totaInv = ($totaInv*100)/$total100;

             $totalSO = ($totalSO*100)/$total100;



      ?>
      <script>

            var colors = ['yellow','green'];
            var data   =  [<?PHP echo number_format($totalSO,2); ?>,<?PHP echo number_format($totaInv,2); ?>];
            var labels = ['Abiertas','Facturadas'];
            
            for (var i=0; i<data.length; i++) {
                labels[i] = '{1}: {2}%'.format(labels[i], data[i]);
            }

            var key = RGraph.HTML.Key('container',
            {
                colors: colors,
                labels: labels,
                tableCss: {
                    position: 'absolute',
                    top: '50%',
                    right: '-40px',
                    transform: 'translateY(-50%)'
                }
            });



            new RGraph.Pie({
                id: 'cvs2',
                data: data,
                options: {
                    strokestyle: '#e8e8e8',
                    variant: 'pie3d',
                    linewidth: 2,
                    shadowOffsetx: 0,
                    shadowOffsety: 7,
                    shadowColor: '#ddd',
                    shadowBlur: 15,
                    radius: 80,
                    exploded: [,20],
                    colors: colors,

                }
            }).draw();

/*              var pie = new RGraph.Pie({
                  id: 'cvs2',
                  data: [<?PHP echo $totalSO; ?>,<?PHP echo $totaInv; ?>],
                  options: {
                      gutterLeft: 50,
                      gutterRight: 50,
                      linewidth: 0,
                      strokestyle: 'rgba(0,0,0,0)',
                      tooltips: ['Abiertas','Facturadas'],
                      labels: ['Abiertas','Facturadas'],
                      colors: ['yellow','green'],
                      variant: 'pie3d',
                      radius: 100,
                      labelsSticksList: true,
                      labelsSticksColors: ['black','black'],
                      radius: 80,
                      shadowOffsety: 5,
                      shadowColor: '#aaa',
                      exploded: [,20],
                      textAccessible: false
                  }
              }).draw();*/
      </script>




      </fieldset>
      </div>

</fieldset>

 
</div>

      <?php

      $query = 'SELECT  date, SUM(Net_due) as Total, month(date) as  mes, year(date) as  year
            FROM `Sales_Header_Imp`
            INNER JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = Sales_Detail_Imp.InvoiceNumber
            and Sales_Header_Imp.ID_compania = Sales_Detail_Imp.ID_compania
            WHERE Sales_Header_Imp.Enviado = "1" and Sales_Header_Imp.Error = "0" 
            and Sales_Header_Imp.ID_compania="'.$idCompania .'"  GROUP BY mes';


      $GetOrder=$this->model->Query($query);


      if (!$GetOrder) {

        $table =  "{x: '".$currentdate."', z: '0' , y: '0' },";

      } 

       $y= '';
       $z= '';
       $meses = array();

      foreach ($GetOrder as $value) { 
        
      $value =json_decode($value);

        if($value->{'year'}==$currentYear) {
           
           $table .=  "{ y: '".$value->{'year'}.'-'.$value->{'mes'}."' , a: '".$value->{'Total'}."'},";
           $lastMonth = $value->{'mes'};

           $meses[$lastMonth] = 'x';

        } 

       
      }

      for ($a=1; $a <= $lastMonth ; $a++) { 
        
        if(!$meses[$a]){

           $table .=  "{ y: '".$currentYear.'-'.$a."' , a: '0'},";

        }
       
       $y = $y + 1;
      }

      if($y >= '1'){

          echo "<pre  id='code' class='prettyprint linenums'>
                 // Use Morris.Bar
                  Morris.Line({
                    element: 'graph',
                    data: [ ".$table."],
                    xkey: 'y',
                    ykeys: ['a'],
                    labels: ['Total Facturado']
                  });
              </pre>";

      } 

?>
</body>