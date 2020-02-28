<?php

class UserCtl
{

  function __construct()
  {

  }



 /*
  * Logs in the user
  */
 public static function Login($session="",$username="",$password="")
 {
 	$sr=new SleekShopRequest();
 	$xml=$sr->login_user($session,$username,$password);
 	$xml=new SimpleXMLElement($xml);
 	$result=array();
 	$result["status"]=(string)$xml->status;
 	$result["id_user"]=(int)$xml->id_user;
 	$result["session_id"]=(string)$xml->session_id;
 	$result["username"]=(string)$xml->username;
 	$result["email"]=(string)$xml->email;
 	return($result);
 }


/*
 * Logs out the user
 */
 public static function LogOut($session="")
 {
 	$sr=new SleekShopRequest();
 	$xml=$sr->logout_user($session,$username,$password);
 	$xml=new SimpleXMLElement($xml);
 	$result["status"]=(string)$xml->status;
 	return($result);
 }


 /*
  * Get the user - data
  */
 public static function GetUserData($session="")
 {
 	$sr=new SleekShopRequest();
 	$xml=$sr->get_user_data($session);
 	$xml=new SimpleXMLElement($xml);
 	$result=array();
 	$result["id_user"]=(int)$xml->id_user;
 	$result["session_id"]=(string)$xml->session_id;
 	$result["username"]=(string)$xml->username;
 	$result["email"]=(string)$xml->email;
 	$result["salutation"]=(string)$xml->attributes->salutation;
 	$result["firstname"]=(string)$xml->attributes->firstname;
 	$result["lastname"]=(string)$xml->attributes->lastname;
 	$result["companyname"]=(string)$xml->attributes->companyname;
 	$result["department"]=(string)$xml->attributes->department;
 	$result["street"]=(string)$xml->attributes->street;
 	$result["number"]=(string)$xml->attributes->number;
 	$result["zip"]=(string)$xml->attributes->zip;
 	$result["city"]=(string)$xml->attributes->city;
 	$result["state"]=(string)$xml->attributes->state;
 	$result["country"]=(string)$xml->attributes->country;
  foreach($xml->additional_attributes->attribute as $attribute)
	 {
		 $type=(string)$attribute->attributes()->type;
		 $name=(string)$attribute->name;
	   $result[$name]=(string)$attribute->value;
	 }
   $employes=explode("\n",$result["employes"]);
   $result["employes"]=$employes;
   $result["addresses"]=array();
  foreach($xml->addresses->address as $address)
  {
    $piece=array();
    $piece["id_address"]=(int)$address->id_address;
    $piece["salutation"]=(string)$address->salutation;
   	$piece["firstname"]=(string)$address->firstname;
   	$piece["lastname"]=(string)$address->lastname;
   	$piece["companyname"]=(string)$address->companyname;
   	$piece["department"]=(string)$address->department;
   	$piece["street"]=(string)$address->street;
   	$piece["number"]=(string)$address->number;
   	$piece["zip"]=(string)$address->zip;
   	$piece["city"]=(string)$address->city;
   	$piece["state"]=(string)$address->state;
   	$piece["country"]=(string)$address->country;
    $result["addresses"][]=$piece;
  }
 	return($result);
 }


/*
 * Sets the user - data
 */
 public static function SetUserData($session="",$args=array())
 {
 	$sr=new SleekShopRequest();
 	$xml=$sr->set_user_data($session,$args);
 	$xml=new SimpleXMLElement($xml);
 	$result=array();
 	$result["status"]=(string)$xml->status;
 	return($result);
 }


 /*
  * Sets the user - password
 */
 public static function SetUserPassword($session="",$passwd1="",$passwd2="",$passwd3="")
 {
 	$sr=new SleekShopRequest();
 	$xml=$sr->set_user_password($session,$passwd1,$passwd2,$passwd3);
 	$xml=new SimpleXMLElement($xml);
 	$result=array();
 	$result["status"]=(string)$xml->status;
 	return($result);
 }

 /*
  * Get the user - orders
 */
 public static function GetUserOrders($session="")
 {
 	$sr=new SleekShopRequest();
 	$xml=$sr->get_user_orders($session);
 	$xml=new SimpleXMLElement($xml);
 	$result=array();
 	foreach($xml->order as $order)
 	{
 		$piecearray=array();
 		$piecearray["id"]=(int)$order->id;
 		$piecearray["order_number"]=(int)$order->order_number;
 		$piecearray["creation_date"]=(string)$order->creation_date;
 		$piecearray["order_email"]=(string)$order->order_email;
 		$piecearray["payment_method"]=(string)$order->payment_method;
 		$piecearray["payment_state_name"]=(string)$order->payment_state->name;
 		$piecearray["payment_state_label"]=(string)$order->payment_state->label;
 		$piecearray["delivery_method"]=(string)$order->delivery_method;
 		$piecearray["delivery_state_name"]=(string)$order->delivery_state->name;
 		$piecearray["delivery_state_label"]=(string)$order->delivery_state->label;
 		$piecearray["order_state"]=(string)$order->order_state;
 		$piecearray["cart_sum"]=(float)$order->cart->sum;
    $cart=CartCtl::get_cart_array($order->cart);
    $piecearray["cart"]=$cart;
 		$result[]=$piecearray;
 	}
 	return($result);
 }



/*
 * Registering a user
 */
 public static function RegisterUser($args=array(),$lang=DEFAULT_LANGUAGE)
 {
 	$sr=new SleekShopRequest();
 	$xml=$sr->register_user($args,$lang);
 	$xml=new SimpleXMLElement($xml);
 	$result=array();
 	$result["status"]=(string)$xml->status;
 	$result["id_user"]=(int)$xml->id_user;
 	$result["session_id"]=(string)$xml->session_id;
 	return($result);
 }


 /*
  * Verifies a user
 */
 public static function VerifyUser($id_user=0,$session_id="")
 {
 	$sr=new SleekShopRequest();
 	$xml=$sr->verify_user($id_user,$session_id);
 	$xml=new SimpleXMLElement($xml);
 	$result=array();
 	$result["status"]=(string)$xml->status;
 	return($result);
 }


}

?>
