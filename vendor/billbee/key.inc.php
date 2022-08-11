<?php

class BillbeeCtl
{

  function __construct()
  {

  }

 public static function GetKey($app_key="")
  {
    $unixtimestamp = substr(time(), 0, 7);
    // API Passwort, kann beliebig festgelegt werden
    $pwd = $app_key;
    // API Passwort wird mit Algorithmus SHA256 und dem Key Timestamp verschlüsselt
    $hash = hash_hmac( "sha256", utf8_encode($pwd), utf8_encode($unixtimestamp));
    // Das Ergebnis wird BASE64 kodiert
    $bsec = base64_encode($hash);
    // HTML spezifische Zeichen werden entfernt
    $bsec = str_replace("=","",$bsec);
    $bsec = str_replace("/","",$bsec);
    $bsec = str_replace("+","",$bsec);
    return $bsec;
  }

public static function GenerateKey()
 {
   $key = md5(microtime().rand());
   return($key);
 }

 public static function GetBillBeePaymentMethod($name="")
  {
    if($name=="PayPal") return(3); //PayPal
    if($name=="PrePayment") return(1); //Banküberweisung also prepayment
    if($name=="Cash") return(4); //Cash
    if($name=="EC-Cash") return(48); //EC-CASH
    if($name=="Sofortueberweisung") return(19); //Sofortüberweisung
    if($name=="PayMill" OR $name=="Stripe") return(31); //Kreditkarte (stripe oder paymill)
    if($name=="Mollie") return(105); //Mollie
    return(22);
  }


public static function GetBillBeeOrderStatus($order=array())
 {
   $orderstatus=1;
   if($order["order_payment_state"]=="PAYMENT_RECEIVED") $orderstatus=3;
   if($order["order_delivery_state"]=="CLOSED") $orderstatus=4;
   if($order["order_state"]=="CANCELED") $orderstatus=8;
   if($order["order_state"]=="CLOSED") $orderstatus=7;
   return($orderstatus);
 }


}

?>
