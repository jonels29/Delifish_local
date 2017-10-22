<?PHP

class ges_ventas extends Controller
{

//******************************************************************************
//ORDEN DE VENTAS
public function ges_orden_ventas(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_orden_ventas.php';
            require APP . 'view/_templates/footer.php';


        }
          


	
}

//ORDEN DE VENTAS
public function ges_orden_ventas_direct(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_orden_ventas_direct.php';
            require APP . 'view/_templates/footer.php';


        }
          


  
}

public function ges_hist_ventas(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_hist_ventas.php';
            require APP . 'view/_templates/footer.php';


        }
          


	
}


public function ges_print_salesorder($id){

$tax= $this->model->Query_value('sale_tax','rate','where id="1";');
$tax_sale = $tax/100;

 
$id = trim(preg_replace('/000+/','',$id));

 $res = $this->model->verify_session();

        if($res=='0'){

        $ORDER = $this->model->Get_order_to_invoice($id);
 
 
            foreach ($ORDER as  $value) {
               $value = json_decode($value);

               $custid = $value->{'Customer_Bill_Name'};
              
               $custname = $value->{'Customer_Bill_Name'}.'/ Dir:'.$value->{'AddressLine1'}.' '.$value->{'AddressLine2'};
               
     
               $saleorder = $value->{'SalesOrderNumber'};

               $salesRep = $value->{'name'}.' '.$value->{'lastname'} ;

               $saledate = $value->{'date'};

               $PO =  $value->{'CustomerPO'};

               $subtotal= number_format($value->{'Subtotal'},4);

         
               $tax = number_format($value->{'OrderTax'},4);

               $total=number_format($value->{'Net_due'},4);


               $contact = $value->{'email'}.' / '.$value->{'Phone_Number'};

               $tipo_lic = $value->{'tipo_licitacion'};
               $termino_pago =  $value->{'termino_pago'};
               $obser =  $value->{'observaciones'} ;
               $entrega =   $value->{'entrega'};

               

            }
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_print_salesorder.php';
            require APP . 'view/_templates/footer.php';


        }
          


    
}


//******************************************************************************
//FACTURAS DE VENTAS


public function ges_pro_ventas(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_pro_ventas.php';
            require APP . 'view/_templates/footer.php';

        }

}

public function ges_pro_hist_ventas(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_pro_hist_ventas.php';
            require APP . 'view/_templates/footer.php';


        }
          


  
}


public function ges_print_sales($id){

$tax= $this->model->Query_value('sale_tax','rate','where id="1";');
$tax_sale = $tax/100;

 
$id = trim(preg_replace('/000+/','',$id));

 $res = $this->model->verify_session();

        if($res=='0'){

        $ORDER = $this->model->Get_sales_to_invoice($id);
 
 
            foreach ($ORDER as  $value) {
               $value = json_decode($value);
              
               $custid = $value->{'CustomerID'};
               
               $custname = $value->{'Customer_Bill_Name'};

               $saleorder = $value->{'InvoiceNumber'};

               $salesRep = $value->{'name'}.' '.$value->{'lastname'} ;

               $saledate = $value->{'date'};

               $subtotal= number_format($value->{'Subtotal'},2);

               $tax = $value->{'saletax'};

               $tax_sale = $tax/100;

               $tax =  number_format(($subtotal * $tax_sale),2);

              

               $total=number_format($value->{'Net_due'},2);

               $contact = $value->{'email'}.' / '.$value->{'Phone_Number'};
               

            }
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_print_sales.php';
            require APP . 'view/_templates/footer.php';


        }
          


    
}

//******************************************************************************
//SALIDA DE INVENTARIO POR AJUSTES

public function ges_sal_merc(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_sal_merc.php';
            require APP . 'view/_templates/footer.php';


        }
           
}

public function ges_hist_sal_merc(){


 $res = $this->model->verify_session();

        if($res=='0'){
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_hist_sal_merc.php';
            require APP . 'view/_templates/footer.php';


        }
          


  
}

public function  ges_print_SalMerc($id){

 
$id = trim(preg_replace('/000+/','',$id));

 $res = $this->model->verify_session();

        if($res=='0'){

        $ORDER = $this->model->Get_sal_merc_to_invoice($id);
 
        

 
            foreach ($ORDER as  $value) {

              $value = json_decode($value);

             $name = $this->model->Query_value('SAX_USER','name','Where ID="'.$value->{'USER'}.'"');
             $lastname =  $this->model->Query_value('SAX_USER','lastname','Where ID="'.$value->{'USER'}.'"');

             $Job= $value->{'JobID'};      
             $fase= $value->{'JobPhaseID'};
             $ccost= $value->{'JobCostCodeID'};
              
              $ref = $value->{'Reference'};

              $rep = $name.' '.$lastname;

              $date = $value->{'Date'};

              $desc = $value->{'ReasonToAdjust'};

              $accnt =  $value->{'Account'};


            }
        

            // load views
            require APP . 'view/_templates/header.php';
            require APP . 'view/_templates/panel.php';
            require APP . 'view/operaciones/ges_print_SalMerc.php';
            require APP . 'view/_templates/footer.php';


        }
          





}

}

?>