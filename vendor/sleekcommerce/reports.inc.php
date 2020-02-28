<?php

class ReportCtl
{

  function __construct()
  {

  }

public static function GetReport($id_location=0,$start_date="",$end_date="",$firstname="",$lastname="")
{
  $conn=MySQL::getInstance();
  if($start_date!=""){$start_date_qry=" AND  creation_date>'".$start_date."'";}else{$start_date_qry="";}
  if($end_date!=""){$end_date_qry=" AND  creation_date<'".$end_date."'";}else{$end_date_qry="";}
  if($firstname!=""){$firstname_qry=" AND  employee like '".$firstname."%'";}else{$firstname_qry="";}
  if($lastname!=""){$lastname_qry=" AND  employee like '%, ".$lastname."%'";}else{$lastname_qry="";}
  $res=$conn->select("select sum(sum_price) as sum from tbl_orders where id_location=".$id_location. $start_date_qry. $end_date_qry. $firstname_qry. $lastname_qry.";");
  $res=$res->fetch_assoc();
  $sum=$res["sum"];
  $res=$conn->select("select * from tbl_orders where id_location=".$id_location. $start_date_qry. $end_date_qry. $firstname_qry. $lastname_qry." ORDER by employee ASC;");
  $orders=array();
  while($arr=$res->fetch_assoc())
  {
   $orders[]=$arr;
  }
  $result=array();
  $result["sum"]=$sum;
  $result["orders"]=$orders;
  return($result);
}

public static function GetLocations()
 {
   $conn=MySQL::getInstance();
   $res=$conn->select("select * from tbl_locations order by name ASC;");
   $result=array();
   if($res->num_rows>0)
   {
     while($arr=$res->fetch_assoc())
     {
       $result[]=$arr;
     }
     return($result);
   }
   else {
     return(false);
   }
 }

  public static function GetLocation($location="")
  {
    $conn=MySQL::getInstance();
    $res=$conn->select("select * from tbl_locations where name='".$location."';");
    if($res->num_rows>0)
		{
			$arr=$res->fetch_assoc();
      return($arr["id_location"]);
    }
    else {
      return(false);
    }
  }

  public static function AddLocation($location="")
  {
    $conn=MySQL::getInstance();
    $res=$conn->insert("insert into tbl_locations (name) values('".$location."');");
    print_r($res);
    return($res);
  }

  public static function AddOrder($id_location=0,$employee="",$id_product=0,$name="",$qty=0,$sum_price=0,$id_order=0,$creation_date="")
  {
    $conn=MySQL::getInstance();
    $res=$conn->select("insert into tbl_orders (id_location,employee,id_product,name,quantity,sum_price,order_id_order,creation_date) values(".$id_location.",'".$employee."',".$id_product.",'".$name."',".$qty.",".$sum_price.",".$id_order.",'".$creation_date."');");
    return($res);
  }

  public static function Init()
  {
    $res=OrderCtl::SearchOrders(array("id_order"=>array(">",self::GetLastOrder())),0,0);
    while($row=array_shift($res))
    {
      $id_order=$row["id"];
      $creation_date=$row["creation_date"];
      if(!$id_location=ReportCtl::GetLocation($row["invoice_companyname"])) $id_location=ReportCtl::AddLocation($row["invoice_companyname"]);
      foreach($row["cart"]["contents"] as $element)
      {
        if($element["type"]!="DELIVERY_COSTS")
        {
          $employee=$element["attributes"]["employe"];
          $id_product=$element["id_product"];
          $name=$element["name"];
          $quantity=$element["quantity"];
          $sum_price=$element["sum_price"];
          ReportCtl::AddOrder($id_location,$employee,$id_product,$name,$quantity,$sum_price,$id_order,$creation_date);
        }
      }
    }
  }

  public static function GetLastOrder()
  {
    $conn=MySQL::getInstance();
    $res=$conn->select("select max(order_id_order) as max from tbl_orders;");
    $res=$res->fetch_assoc();
    if($res["max"]==NULL) return(0);
    return($res["max"]);
  }

}

?>
