
<div id="footer" class="footer  col-xs-12">

<div    class="crop col-xs-6">
<?php   

$conn = $this->model->sage_connected;

	if($conn==1){

	  $status ="<img width='15px' src='img/Check.png' /> Conectado a ".$this->model->Query_value('CompanySession','CompanyNameSage50','order by LAST_CHANGE DESC  limit 1');

	}else{

	  $status = "<img width='15px' src='img/Stop.png' /> No conectado a Sage";

	}

echo $status;

?>
</div>


<div style="float: right; text-align:right;" class="crop col-xs-6">
<img width="15px" src="img/Database.png" /><?php echo  '    '.$this->model->TestConexion(); ?>
&nbsp&nbsp V_3.7.17
</div>


</div>

</div>

</body>
</html>