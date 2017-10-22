<?php

error_reporting(1); 


class Model
{
    /**
     * @param object $db A PDO database connection
     */

     public  $active_user_id = null;
     public  $active_user_name  = null;
     public  $active_user_lastname  = null;
     public  $active_user_email  = null;
     public  $active_user_role  = null;
     public  $active_user_almacen  = null;
     public  $id_compania = null;
     public  $sage_connected = null;




    function __construct($db,$dbname)
    {
        try {
           
           $this->db = $db;
           $this->dbname = $dbname;

        } catch (mysqli_connect_errno $e) {
            exit('No se pude realizar la conexion a la base de datos');
        }

         $this->sage_connected =   $this->ConexionSage();
    }
////////////////////////////////////////////////////////////////////////////////////////
/**
* test connetion BD
*/ 
    public function TestConexion(){

            $Mysql =  $this->db; 


            if (mysqli_connect_errno()) {
             
                $status ="Error: (" . mysqli_connect_errno() . ") " . mysqli_connect_error();

            }else{  

                $status= "Conectado a Mysql";

            }

           
            return $status;

            }

    
////////////////////////////////////////////////////////////////////////////////////////
/**
* test connetion BD
*/ 
public function ConexionSage(){

        $connected = $this->Query_value('CompanySession','isConnected','order by LAST_CHANGE DESC limit 1');

return $connected;
}



////////////////////////////////////////////////////////////////////////////////////////
    /**
     * CONNECTION DB
     */
    public function connect($query){

      mysqli_set_charset($this->db, 'utf8' );
      
     
      $conn =  mysqli_query($this->db,$query);

      // Perform a query, check for error
        if (!$conn)
          {

           $conn = "0";

           return $conn;

          }else{

            return $conn;
          }

    
    }
////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Query STATEMEN, DEVUELVE JSON
     */
        public function Query($query){
        //$this->verify_session();
            
        $ERROR = '';
       
        $i=0;

         $res = $this->connect($query);

        if($res=='0'){
         
          $ERROR['ERROR'] = date("Y-m-d h:i:sa").','.mysqli_error($this->db).','.$query;

          file_put_contents("LOG_ERROR/TEMP_LOG.json",json_encode($ERROR),FILE_APPEND);

          file_put_contents("LOG_ERROR/ERROR_LOG.log",'/SAGEID-'.$this->id_compania.'/'.date("Y-m-d h:i:sa").'/'.$this->active_user_name.''.$this->active_user_lastname.'/'.mysqli_error($this->db).'/'.$query."\n",FILE_APPEND);

          die(mysqli_error($this->db));
          
        }else{
             file_put_contents("LOG_ERROR/TEMP_LOG.json",''); //LIMPIO EL ARCHIVO

             $columns = mysqli_fetch_fields($res);
         

        
             while ($datos=  mysqli_fetch_assoc($res)) {
                 
                  foreach ($columns as $value) {
                    $currentField=$value->name;

                    $FIELD[$currentField]=$datos[$currentField];

                    $JSON[$i]=json_encode($FIELD);

                   
                 }
                 $i++;
               } 
               
      

        return  $JSON;


        }

        
        $this->close();
        }
////////////////////////////////////////////////////////////////////////////////////////
    /**
     * UPDATE STATEMEN
     */
    public function update($table,$columns,$clause){


    $whereSQL = '';
    if(!empty($clause))
    {
       
        if(substr(strtoupper(trim($clause)), 0, 5) != 'WHERE')
        {
           
            $whereSQL = " WHERE ".$clause;
        } else
        {
            $whereSQL = " ".trim($$clause);
        }
    }
    
    $query = "UPDATE ".$table." SET ";
   
    $sets = array();
    foreach($columns as $column => $value)
    {
         $sets[] = "`".$column."` = '".$value."'";
    }
    $query .= implode(', ', $sets);
    
    $query .= $whereSQL;

    
    $res = $this->Query($query);


    $this->close();
    return $res;

    }
////////////////////////////////////////////////////////////////////////////////////////
    /**
     * QUERY QUE DEVUELVE UN SOLO VALOR CONSULTADO
     */

function Query_value($table,$columns,$clause){

$query = 'SELECT '.$columns.' FROM '.$table.' '.$clause.';';



$res= $this->connect($query);
$columns= mysqli_fetch_fields($res);



     while ($datos=mysqli_fetch_assoc($res)) {
         
          foreach ($columns as $value) {
           
            $currentField=$value->name;

            $column_value=$datos[$currentField];

 
         }

       } 

//echo $column_value;
return  $column_value;
$this->close();
}
////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////
    /**
     * INSERT
     */

public function insert($table,$values){


$fields = array_keys($values);

$query= "INSERT INTO ".$table." (`".implode('`,`', $fields)."`) VALUES ('".implode("','", $values)."');";

$insert = $this->Query($query);

}

////////////////////////////////////////////////////////////////////////////////////////

public function read_db_error(){
    $string = file_get_contents("LOG_ERROR/TEMP_LOG.json");
    $json_a = json_decode($string, true);
    $R_ERRORS = '';

    $R_ERRORS .= $json_a->{'ERROR'}; 



    file_put_contents("LOG_ERROR/TEMP_LOG.json",''); //LIMPIO EL ARCHIVO

   return $R_ERRORS ;

}

    /**
     * delete
     */

public function delete($table,$clause){


$query= "DELETE FROM ".$table.' '.$clause.';';

$res = $this->Query($query);


}

////////////////////////////////////////////////////////////////////////////////////////
    /**
     * CIERRA LA CONEXION DE BD
     */
    public function close(){

    return mysqli_close($this->db);

    }
////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////
//METODOS PARA GESTION DE LOGIN
////////////////////////////////////////////////////////////////////////////////////////
public function login_in($user,$pass,$temp_url){



$res = $this->Query("SELECT * FROM SAX_USER WHERE email='".$user."' AND pass='".$pass."' AND onoff='1';");

foreach ($res as $value) {

    $value = json_decode($value);

    $email= $value->{'email'};
    $id= $value->{'id'};
    $name= $value->{'name'};
    $lastname= $value->{'lastname'};
    $role=$value->{'role'};
    $pass=$value->{'pass'};

    $rol_compras=$value->{'role_purc'};
    $rol_campo  =$value->{'role_fiel'};
}


if($email==''){

 echo "<script> alert('Usuario o Password no son correctos.');</script>";
 

}else{


$columns= array('last_login' => $timestamp = date('Y-m-d G:i:s'));

$this->update('SAX_USER',$columns,'id='.$id);

session_start();


$_SESSION['ID_USER'] = $id;
$_SESSION['NAME'] = $name;
$_SESSION['LASTNAME'] = $lastname;
$_SESSION['EMAIL'] = $email;
$_SESSION['ROLE'] = $role;
$_SESSION['PASS'] = $pass;
//$_SESSION['ALMACEN'] = $almacen;
$_SESSION['ROLE1'] = $rol_compras;
$_SESSION['ROLE2'] = $rol_campo;

if($temp_url!=''){

$url = str_replace('@',  '/', $temp_url);

  
 echo '<script>self.location="'.URL.'index.php?url='.$url.'";</script>';


}else{

   $conn = $this->sage_connected ;

   
        if($conn==0){

         echo '<script>
                   console.log('.$conn.');
                   alert("Advertencia: El sistema se encuentra desconectado de SageConnect, Por favor verificar");
                   self.location="'.URL.'index.php?url='.$url.'";
                  </script>';

        }else{
               
          echo '<script>console.log('.$conn.'); self.location="'.URL.'index.php?url=home/index";</script>';
           
        }


} 

}

}


public function verify_session(){

        $conexion = $this->TestConexion();

        list($error,$msg) = explode(':', $conexion);

        //echo $conexion.' '.$error;

        $msg = str_replace('/', '-', $msg);

        if($error=='Error'){
          

          $res = '1';

            echo '<script>self.location ="index.php?url=db_config/index/'.$msg.'";</script>';



        }else{

            session_start();

            if(!$_SESSION){

            // echo "<script>alert('Usuario no auntenticado');</script>";
      
            $res = '1';
            echo '<script>self.location ="index.php?url=login/index";</script>';

             
            }else{
       
            $res = '0';

            $this->set_login_parameters();
           }

        }

       
     return $res;
    }

public function set_login_parameters(){

        $this->active_user_id = $_SESSION['ID_USER'];
        $this->active_user_name = $_SESSION['NAME'];
        $this->active_user_lastname = $_SESSION['LASTNAME'];
        $this->active_user_email = $_SESSION['EMAIL'];
        $this->active_user_role = $_SESSION['ROLE'] ;
        $this->active_user_almacen = $_SESSION['ALMACEN'];
        $this->id_compania = $this->Query_value('CompanySession','ID_compania','ORDER BY LAST_CHANGE DESC LIMIT 1');
        //$active_user_pass = $_SESSION['PASS'] ;
        
    }


////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////
//METODOS PARA GESTION DE OPERACIONES
////////////////////////////////////////////////////////////////////////////////////////
public function Get_lote_list($itemid){

$query='SELECT
Prod_Lotes.no_lote, 
Prod_Lotes.fecha_ven, 
(select sum(qty) from status_location where status_location.lote = Prod_Lotes.no_lote 
and Prod_Lotes.ID_compania ="'.$this->id_compania.'" 
and status_location.ID_compania ="'.$this->id_compania.'") as lote_qty
from Prod_Lotes
where Prod_Lotes.ProductID ="'.$itemid.'" ;';

$list = $this->Query($query);

return $list;


}


public function fact_compras_list(){


$query='SELECT
Purchase_Header_Exp.PurchaseID, 
Purchase_Header_Exp.PurchaseNumber, 
Purchase_Header_Exp.VendorName, 
Purchase_Header_Exp.Date as fecha
from Purchase_Header_Exp
INNER join  Purchase_Detail_Exp on Purchase_Detail_Exp.PurchaseID = Purchase_Header_Exp.PurchaseID
WHERE Purchase_Detail_Exp.Item_id <> " " GROUP BY Purchase_Header_Exp.PurchaseID ';

$list = $this->Query($query);

return $list;
}

public function Get_fact_header($sort,$limit,$clause){

$query='SELECT *
from Purchase_Header_Imp
'.$clause.' Order by Purchase_Header_Imp.TransactionID '.$sort.' limit '.$limit.';';;


$list = $this->Query($query);

return $list;
}


public function lote_loc_by_itemID($itemid){

$query ='SELECT * 
FROM status_location
INNER JOIN Prod_Lotes ON Prod_Lotes.no_lote = status_location.lote
WHERE Prod_Lotes.ProductID="'.$itemid.'"  GROUP BY status_location.ID';

$res = $this->Query($query);

return $res;
}

public function get_Purchaseitem($itemid){

$query ='SELECT
Products_Exp.ProductID,
Products_Exp.Description,
Products_Exp.QtyOnHand,
Products_Exp.UnitMeasure,
Products_Exp.Price1,
Products_Exp.id_compania
from Products_Exp
inner join Prod_Lotes on Prod_Lotes.ProductID=Products_Exp.ProductID
where  Products_Exp.ProductID="'.$itemid.'" ;';



$res = $this->Query($query);

return $res;
}

public function get_ProductsList(){


$query='SELECT 
Products_Exp.ProductID,
Products_Exp.Description,
Products_Exp.UnitMeasure,
Products_Exp.QtyOnHand,
Products_Exp.Price1,
Products_Exp.Price2,
Products_Exp.Price3,
Products_Exp.Price4,
Products_Exp.Price5,
Products_Exp.Price6,
Products_Exp.Price7,
Products_Exp.Price8,
Products_Exp.Price9,
Products_Exp.Price10,
Products_Exp.LastUnitCost
FROM Products_Exp 
inner join Prod_Lotes on Prod_Lotes.ProductID=Products_Exp.ProductID
WHERE Products_Exp.IsActive="1" AND  Products_Exp.QtyOnHand > 0 and Products_Exp.id_compania="'.$this->id_compania.'" and Prod_Lotes.ID_compania="'.$this->id_compania.'" group by Products_Exp.ProductID';


$res = $this->Query($query);

return $res;

}

public function get_ClientList(){

$query='SELECT * FROM Customers_Exp where  id_compania="'.$this->id_compania.'" order by CustomerID ASC';

$res = $this->Query($query);

return $res;

}

public function get_SalesRepre(){

$query='SELECT * FROM Sales_Representative_Exp where  ID_compania="'.$this->id_compania.'" order by SalesRepID ASC';

$res = $this->Query($query);

return $res;

}

public function get_VendorList(){

$query='SELECT * FROM Vendors_Exp where  ID_compania="'.$this->id_compania.'"';

$res = $this->Query($query);

return $res;

}


public function get_PurOrdList(){

$query='SELECT * FROM PurOrdr_Header_Exp where  ID_compania="'.$this->id_compania.'" and PurchaseOrderNumber <> ""';

$res = $this->Query($query);

return $res;

}

public function GET_MAX_QTY($invoice){  


$clause ='INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
WHERE PurOrdr_Header_Exp.ID_compania="'.$this->id_compania.'"
AND PurOrdr_Header_Exp.PurchaseOrderNumber ="'.$invoice.'"';
$QTY_IN_PO = $this->Query_value('PurOrdr_Header_Exp','SUM( Quantity )',$clause );


$clause ='WHERE ID_compania="'.$this->id_compania.'"
AND NO_PO ="'.$invoice.'"';
$QTY_INVOICED = $this->Query_value('PO_FACT_LOG','SUM( ITEM_QTY )',$clause );

$res =  $QTY_IN_PO - $QTY_INVOICED;

return $res;
}


public function GET_MAX_QTY_BY_ITEM($invoice,$ITEM){  


$clause ='INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
WHERE PurOrdr_Header_Exp.ID_compania="'.$this->id_compania.'"
AND PurOrdr_Header_Exp.PurchaseOrderNumber ="'.$invoice.'" 
AND PurOrdr_Detail_Exp.Item_id="'.$ITEM.'"';

$QTY_IN_PO = $this->Query_value('PurOrdr_Header_Exp','SUM( Quantity )',$clause );


$clause ='WHERE ID_compania="'.$this->id_compania.'"
                AND NO_PO ="'.$invoice.'"
                AND ITEM_ID="'.$ITEM.'"';
$QTY_INVOICED = $this->Query_value('PO_FACT_LOG','SUM( ITEM_QTY )',$clause );

$res =  $QTY_IN_PO - $QTY_INVOICED;

return $res;
}

public function Get_CO_No(){

$order = $this->Query_value('Purchase_Header_Imp','TransactionID','where ID_compania="'.$this->id_compania.'" ORDER BY TransactionID DESC LIMIT 1');


//$NO_ORDER = str_pad($NO_ORDER, 7 ,"0",STR_PAD_LEFT);

$NO_ORDER = number_format((int)$order+1);
$NO_ORDER = str_pad($NO_ORDER, 9 ,"0",STR_PAD_LEFT);


if($NO_ORDER< '1'){

    $NO_ORDER=0;
    $NO_ORDER = str_pad($NO_ORDER, 9 ,"0",STR_PAD_LEFT);

}

return $NO_ORDER; 
}


public function Get_SO_No(){

$order = $this->Query_value('SalesOrder_Header_Imp','SalesOrderNumber','where ID_compania="'.$this->id_compania.'" ORDER BY ID DESC LIMIT 1');

list($ACI , $NO_ORDER) = explode('-', $order);


$NO_ORDER = number_format((int)$NO_ORDER+1);
//$NO_ORDER = str_pad($NO_ORDER, 7 ,"0",STR_PAD_LEFT);

$NO_ORDER = 'ACI-'.$NO_ORDER;

if($NO_ORDER< '1'){

    $NO_ORDER=0;
    $NO_ORDER = 'ACI-'.$NO_ORDER;
   // $NO_ORDER = str_pad($NO_ORDER, 7 ,"0",STR_PAD_LEFT);

}



return $NO_ORDER; 
}


public function Get_Order_No(){

$order = $this->Query_value('Sales_Header_Imp','InvoiceNumber','where ID_compania="'.$this->id_compania.'" order by InvoiceNumber DESC LIMIT 1');

$NO_ORDER = number_format((int)$order+1);
$NO_ORDER = str_pad($NO_ORDER, 9 ,"0",STR_PAD_LEFT);


if($NO_ORDER< '1'){

    $NO_ORDER=0;
    $NO_ORDER = str_pad($NO_ORDER, 9 ,"0",STR_PAD_LEFT);

}


return $NO_ORDER; 
}



public function Get_Ref_No(){


$order = $this->Query_value('InventoryAdjust_Imp','Reference','where ID_compania="'.$this->id_compania.'" order by Reference DESC LIMIT 1');

$NO_ORDER = number_format((int)$order+1);
$NO_REF = str_pad($NO_ORDER, 9 ,"0",STR_PAD_LEFT);


if($NO_REF < '1'){

    $NO_REF=0;
    $NO_REF = str_pad($NO_REF, 9 ,"0",STR_PAD_LEFT);

}


return $NO_REF; 
}


public function Get_con_No(){


$order = $this->Query_value('CON_HEADER','refReg','where ID_compania="'.$this->id_compania.'" order by refReg DESC LIMIT 1');

$NO_ORDER = number_format((int)$order+1);
$NO_REF = str_pad($NO_ORDER, 9 ,"0",STR_PAD_LEFT);


if($NO_REF < '1'){

    $NO_REF=0;
    $NO_REF = str_pad($NO_REF, 9 ,"0",STR_PAD_LEFT);

}


return $NO_REF; 
}

public function Get_Req_No(){

$order = $this->Query_value('REQ_HEADER','NO_REQ','where ID_compania="'.$this->id_compania.'" ORDER BY ID DESC LIMIT 1');

list($ACI , $NO_ORDER) = explode('-', $order);


$NO_ORDER = number_format((int)$NO_ORDER+1);
//$NO_ORDER = str_pad($NO_ORDER, 7 ,"0",STR_PAD_LEFT);

$NO_ORDER = 'REQ-'.$NO_ORDER;

if($NO_ORDER< '1'){

    $NO_ORDER=0;
    $NO_ORDER = 'REQ-'.$NO_ORDER;
   

}


return $NO_ORDER; 
}



public function get_JobList(){

$jobs = $this->Query('Select * from Jobs_Exp where ID_compania="'.$this->id_compania.'" and IsActive="1"'); 

if(!$jobs){
 return '0';

}else{
  return $jobs;  
}

}

public function get_phaseList(){

$jobs = $this->Query('Select * from Job_Phases_Exp where ID_compania="'.$this->id_compania.'" and IsActive="1"'); 

if(!$jobs){
 return '0';

}else{
  return $jobs;  
}

}

public function get_costList(){

$jobs = $this->Query('Select * from Job_Cost_Codes_Exp where ID_compania="'.$this->id_compania.'" and IsActive="1"'); 

if(!$jobs){
 return '0';

}else{
  return $jobs;  
}


}

public function Get_User_Info($id){

$user = $this->Query('Select * from SAX_USER where id="'.$id.'"'); 


return $user;

}

public function Get_company_Info(){

$Company= $this->Query('Select * from company_info;'); 

return $Company;

}

public function Get_order_to_invoice($id){

$id_compania = $this->id_compania;

$ORDER= $this->Query('SELECT * FROM `SalesOrder_Header_Imp`
inner JOIN `SalesOrder_Detail_Imp` ON SalesOrder_Header_Imp.SalesOrderNumber = SalesOrder_Detail_Imp.SalesOrderNumber
inner JOIN `Customers_Exp` ON SalesOrder_Header_Imp.CustomerID = Customers_Exp.CustomerID
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = SalesOrder_Header_Imp.user where SalesOrder_Header_Imp.SalesOrderNumber="'.$id.'" and 
SalesOrder_Detail_Imp.ID_compania="'.$id_compania.'" and SalesOrder_Header_Imp.ID_compania="'.$id_compania.'"
group by SalesOrder_Detail_Imp.ID order by SalesOrder_Detail_Imp.ID;'); 

return $ORDER;

}

public function Get_sales_to_invoice($id){

$ORDER= $this->Query('SELECT * FROM `Sales_Header_Imp`
inner JOIN `Sales_Detail_Imp` ON Sales_Header_Imp.InvoiceNumber = Sales_Detail_Imp.InvoiceNumber
inner JOIN `Customers_Exp` ON Sales_Header_Imp.CustomerID = Customers_Exp.CustomerID
inner JOIN `SAX_USER` ON `SAX_USER`.`id` = Sales_Header_Imp.user where Sales_Header_Imp.InvoiceNumber="'.$id.'" 
and  SalesOrder_Detail_Imp.ID_compania="'.$id_compania.'" and SalesOrder_Header_Imp.ID_compania="'.$id_compania.'"
group by Sales_Detail_Imp.ID order by Sales_Detail_Imp.ID;'); 

return $ORDER;

}

public function Get_sal_merc_to_invoice($id){

$ORDER= $this->Query("SELECT * FROM InventoryAdjust_Imp where Reference='".$id."' and ID_compania='".$this->id_compania."';"); 

return $ORDER;

}

public function Get_sales_conf_Info(){

$saleinfo = $this->Query('SELECT * FROM sale_tax;');

return $saleinfo;

}

//ModifGPH
////////////////////////////////////////////////////
//QUERYS PARA REPORTES

public function get_InvXven($sort,$limit,$clause){

     $order = $this->Query('

         SELECT 
         a.name Almacen, 
         u.etiqueta Ubicacion, 
         l.no_lote Lote, 
         p.ProductID Producto, 
         p.Description Descripcion, 
         l.fecha_ven Vencimiento, 
         s.qty Cantidad
        from Products_Exp p
         inner join Prod_Lotes l  on p.ProductID = l.ProductID 
         inner join status_location s on p.ProductID = s.id_product and s.lote = l.no_lote
         inner join ubicaciones u  on s.route = u.id
         inner join almacenes a on u.id_almacen = a.id 

        '.$clause.' order by l.fecha_ven '.$sort.' limit '.$limit.';');



    return $order;

}


public function get_InvXStk($sort,$limit,$clause){

   $sql = 'SELECT 
         a.name Almacen, 
         u.etiqueta Ubicacion, 
         s.lote Lote, 
         p.ProductID Producto, 
         p.LastUnitCost,
         p.Description Descripcion, 
         s.qty Cantidad
        from Products_Exp p
         inner join status_location s on p.ProductID = s.id_product 
         inner join ubicaciones u  on s.route = u.id
         inner join almacenes a on u.id_almacen = a.id '.$clause.' order by a.name '.$sort.' limit '.$limit.';';

     $order = $this->Query($sql);


    return $order;

}


public function get_req_to_report($sort,$limit,$clause){

$sql='SELECT * FROM `REQ_HEADER` 
inner join REQ_DETAIL ON REQ_HEADER.NO_REQ = REQ_DETAIL.NO_REQ
'.$clause.' group by REQ_HEADER.NO_REQ order by ID '.$sort.' limit '.$limit.';';

$get_req = $this->Query($sql);


return $get_req;
}



public function get_inv_qty_disp($sort,$limit,$clause){

$sql=' SELECT 
p.ProductID, 
p.Description, 
p.QtyOnHand, 
SUM( s.qty )  as LoteQty
FROM Products_Exp p
INNER JOIN status_location s ON s.id_product = p.ProductID AND s.ID_compania = p.id_compania
'.$clause.' GROUP BY p.ProductID order by p.ProductID '.$sort.' limit '.$limit.';';

$get_inv_qty = $this->Query($sql);


return $get_inv_qty;

}

////////////////////////////////////////////////////



////////////////////////////////////////////////////
//Req to print
public function get_req_to_print($id){


$sql='SELECT * FROM `REQ_HEADER` 
inner join REQ_DETAIL ON REQ_HEADER.NO_REQ = REQ_DETAIL.NO_REQ
WHERE 
REQ_HEADER.ID_compania="'.$this->id_compania.'" AND  
REQ_DETAIL.ID_compania="'.$this->id_compania.'" and 
REQ_HEADER.NO_REQ="'.$id.'" and 
REQ_DETAIL.NO_REQ="'.$id.'"';

$req_info = $this->Query($sql);

return $req_info ;
}


////////////////////////////////////////////////////
//Orden de compras por id
public function get_items_by_OC($invoice){

$query ='SELECT * 
FROM PurOrdr_Header_Exp
INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
WHERE PurOrdr_Header_Exp.ID_compania="'.$this->id_compania.'"
AND PurOrdr_Header_Exp.PurchaseOrderNumber ="'.$invoice.'"';

$res = $this->Query($query);


return $res;
}


//Orden de compras por id
public function get_items_lines_OC($invoice){


$clause = 'INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
                                                            WHERE PurOrdr_Header_Exp.ID_compania="'.$this->id_compania.'"
                                                            AND PurOrdr_Header_Exp.PurchaseOrderNumber ="'.$invoice.'"';

$res = $this->Query_value('PurOrdr_Header_Exp','count(*)', $clause);


return $res;
}

//Orden de compras total
public function get_OC($sort,$limit,$clause){

$query ='SELECT * 
FROM PurOrdr_Header_Exp
INNER JOIN PurOrdr_Detail_Exp ON PurOrdr_Header_Exp.TransactionID = PurOrdr_Detail_Exp.TransactionID
'.$clause.' group by PurOrdr_Header_Exp.TransactionID Order by PurOrdr_Header_Exp.Date '.$sort.' limit '.$limit.';';


$res = $this->Query($query);


return $res;
}

////////////////////////////////////////////////////



////////////////////////////////////////////////////
//Consignacion

public function con_reg($refReg,$cont,$ID_compania){

$idReg = $this->Query_value('CON_HEADER','idReg','WHERE refReg = "'.$refReg.'" and ID_compania="'.$ID_compania.'";');

$regTra = $this->Query('SELECT id from reg_traslado where ID_compania="'.$ID_compania.'" ORDER BY LAST_CHANGE desc limit '.$cont.';');

   foreach ($regTra as $value) {
  
   $value = json_decode($value);

   $ID_REG_TRAS = $value->{'id'};

    $this->Query('INSERT INTO CON_REG_TRAS (idReg,idRegTras,ID_compania) values ("'.$idReg.'","'.$ID_REG_TRAS.'","'.$ID_compania.'");');

    }

}



public function get_con_to_report($sort,$limit,$clause){

$sql='SELECT      
                  CON_HEADER.date,
                  CON_HEADER.refReg as REF,
                  CON_HEADER.idJob  as JOB,
                  CON_HEADER.idPha as  PHASE,
                  CON_HEADER.idCost as COST,
                  CON_HEADER.nota as NOTA,
                  reg_traslado.id_almacen_ini,
                  reg_traslado.route_ini,
                  reg_traslado.id_almacen_des,
                  reg_traslado.route_des,
                  reg_traslado.id_user as USER,
                  reg_traslado.lote as LOTE,
                  reg_traslado.ProductID,
                  reg_traslado.qty as CANT
                  FROM CON_HEADER 
                  INNER JOIN CON_REG_TRAS ON CON_REG_TRAS.idReg = CON_HEADER.idReg 
                  INNER JOIN reg_traslado ON CON_REG_TRAS.idRegTras = reg_traslado.id 
                  '.$clause.' order by CON_HEADER.idReg '.$sort.' limit '.$limit.';';

$get_con = $this->Query($sql);


return $get_con;
}
////////////////////////////////////////////////////

//Metodo para traer la lista de precios

public function get_PriceList(){

$query='SELECT * FROM PRI_LIST_ID where  ID_compania="'.$this->id_compania.'"';

$res = $this->Query($query);

return $res;

}


//Metodo para traer lista de precios para reporte

public function get_Price_list($sort,$limit,$clause){

$query ='SELECT * FROM PRI_LIST_ID '.$clause.' order by PRI_LIST_ID.IDPRICE '.$sort.' limit '.$limit.';';


$res = $this->Query($query);


return $res;
}


////////////////////////////////////////////////////
//Trae Detalle de lista de precios

public function get_items_by_PL($PL_id){

$query ='SELECT * 
FROM PRI_LIST_ITEM
WHERE PRI_LIST_ITEM.IDPRICE ="'.$PL_id.'" AND ID_compania="'.$this->id_compania.'"';


$res = $this->Query($query);


return $res;
}

}
?>
