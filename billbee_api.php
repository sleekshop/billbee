<?php
include("./conf.inc.php");
include("./vendor/sleekcommerce/init.inc.php");
include("./vendor/billbee/key.inc.php");
$action=$_GET["Action"];
$key=$_GET["Key"];
if($key!=BillbeeCtl::GetKey(APPLICATION_KEY)) die("ACCESS_DENIED");
$startdate=$_GET["StartDate"];
if($startdate=="") die("{'error':'NO_STARTDATE'}");
$constraint=array("creation_date"=>array(">",$startdate));
$orders=OrderCtl::SearchOrders($constraint,0,0);
$response=array();
$count=count($orders);
$log=date("Y-m-d H-i-s") . " - ".$startdate." - " . $count . "\n";
file_put_contents('./logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
$response["paging"]=array("page"=>1,"totalCount"=>$count,"totalPages"=>1);
foreach($orders as $order)
{
  $piece=array();
  $piece["order_id"]=$order["id"];
  $piece["order_number"]=$order["order_number"];
  $piece["currency_code"]="EUR";
  $piece["nick_name"]=$order["username"];
  $piece["ship_cost"]=$order["cart"]["delivery_costs"]["sum"];
  $piece["invoice_address"]=array(
    "firstname"=>$order["invoice_firstname"],
    "lastname"=>$order["invoice_lastname"],
    "street"=>$order["invoice_street"],
    "housenumber"=>$order["invoice_number"],
    "address2"=>"",
    "postcode"=>$order["invoice_zip"],
    "city"=>$order["invoice_city"],
    "country"=>$order["invoice_country"],
    "company"=>$order["invoice_company"],
    "state"=>$order["invoice_state"]
  );
  $piece["delivery_address"]=array(
    "firstname"=>$order["delivery_firstname"],
    "lastname"=>$order["delivery_lastname"],
    "street"=>$order["delivery_street"],
    "housenumber"=>$order["delivery_number"],
    "address2"=>"",
    "postcode"=>$order["delivery_zip"],
    "city"=>$order["delivery_city"],
    "country"=>$order["delivery_country"],
    "company"=>$order["delivery_company"],
    "state"=>$order["delivery_state"]
  );
  $piece["order_date"]=$order["creation_date"];
  $piece["email"]=$order["email"];
  $piece["phone1"]=$order["phone"];
  $piece["pay_date"]="";
  $piece["ship_date"]="";
  $piece["payment_method"]=PaymentCtl::GetBillBeePaymentMethod($order["order_payment_method"]);
  $piece["order_status_id"]=1;
  $piece["order_products"]=array();
  foreach($order["cart"]["contents"] as $element)
  {
    $element_piece=array();
    $element_piece["discount_percent"]=0;
    $element_piece["quantity"]=floatval($element["quantity"]);
    $element_piece["unit_price"]=floatval($element["price"]);
    $element_piece["product_id"]=$element["id_product"];
    $element_piece["name"]=$element["name"];
    $element_piece["sku"]=$element["element_number"];
    $element_piece["tax_rate"]=floatval(floatval($element["attributes"]["sys_tax"])*100);
    $element_piece["options"]=array();
    if($element["type"]!="DELIVERY_COSTS") $piece["order_products"][]=$element_piece;
  }
  $response["orders"][]=$piece;
}
header('Content-Type: application/json');
echo json_encode($response);
die();
//print_r(json_encode($response));



 ?>
