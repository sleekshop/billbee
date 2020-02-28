<?php

class ConfCtl
{

  function __construct()
  {

  }

public static function CheckConf()
 {
   if(file_exists("./conf.inc.php")) return(true);
   self::CreateConf("","","","",BillbeeCtl::GenerateKey());
 }

public static function CreateConf($api_endpoint="",$licence_username="",$licence_password="",$application_token="",$application_key="")
 {
   $res=file_get_contents("./conf_tmp.inc.php");
   $res=str_replace("###API_ENDPOINT###",$api_endpoint,$res);
   $res=str_replace("###LICENCE_USERNAME###",$licence_username,$res);
   $res=str_replace("###LICENCE_PASSWORD###",$licence_password,$res);
   $res=str_replace("###APPLICATION_TOKEN###",$application_token,$res);
   $res=str_replace("###APPLICATION_KEY###",$application_key,$res);
   file_put_contents("./conf.inc.php",$res);
 }


}

?>
