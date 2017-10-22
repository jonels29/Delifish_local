// ********************************************************
// * Aciones cuando la pagina ya esta cargada
// ********************************************************
$(window).load(function(){

$('#ERROR').hide();

});
	
$(document).ready(function() {
    if (location.hash) {
        $("a[href='" + location.hash + "']").tab("show");
    }
    $(document.body).on("click", "a[data-toggle]", function(event) {
        location.hash = this.getAttribute("href");
    });
});

$(window).on("myTab", function() {
    var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
    $("a[href='" + anchor + "']").tab("show");
});



	
function ShowLogBD(){

URL = document.getElementById('URL').value;
link = URL+"index.php";
DATOS = "url=home/GetBDLog";

$.ajax({
      type: "GET",
      url: link,
      data: DATOS,

      success: function(res){

      var button = '</br><button onclick="ClearLogBD();"><i class="fa fa-trash" ></i> Limpiar archivo</button></br>';

      $('#logViewBD').html(button+res);


     } 
 });

}

	
function ClearLogBD(){

URL = document.getElementById('URL').value;
link = URL+"index.php";
DATOS = "url=home/ClearBDLog";

$.ajax({
      type: "GET",
      url: link,
      data: DATOS,

      success: function(res){

      $('#logViewBD').html(res);


     } 
 });

}
	
function ShowLog(){

URL = document.getElementById('URL').value;
link = URL+"index.php";
DATOS = "url=home/GetSyncLog";

$.ajax({
      type: "GET",
      url: link,
      data: DATOS,

      success: function(res){
      
      var button = '</br><button onclick="ClearLog();"><i class="fa fa-trash" ></i> Limpiar archivo</button></br>';

      $('#logView').html(button+res);


     } 
 });

}

function ClearLog(){

URL = document.getElementById('URL').value;
link = URL+"index.php";
DATOS = "url=home/ClearSyncLog";

$.ajax({
      type: "GET",
      url: link,
      data: DATOS,

      success: function(res){

      $('#logView').html(res);


     } 
 });

}

function send_test(){

		URL       = document.getElementById('URL').value;
		var email = document.getElementById('emailtest').value;
		var datos= "url=home/send_test_mail/"+email;
		var link= +"index.php";

		$('#notificacion').html('<P>Enviando...</P>');

		  $.ajax({
		      type: "GET",
		      url: link,
		      data: datos,
		      success: function(res){
		      
		       $('#notificacion').html(res);
		      
		        }
		   });

		 console.log = function(message) {$('#notificacion').append('<p>' + message + '</p>');};

		}