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

}

?>
