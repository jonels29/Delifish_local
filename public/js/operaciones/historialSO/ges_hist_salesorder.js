function closeSo(url,id){
  
var datos= "url=ges_ventas/CloseSelesOrder/"+id;

r = confirm ('Esta seguro de  procesar el cierre definitivo de la orden "'+id+'" ?');

  if(r==true){

         $.ajax({
           type: "GET",
           url: url+'index.php',
           data: datos,
           success: function(res){

            console.log(res);

                if(res==1){
                   
                   alert('Se ha cerrado con exito la orden No. '+id);
                   location.reload(true);

                  }

               }
            });
   }
}