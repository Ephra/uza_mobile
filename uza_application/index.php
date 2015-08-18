<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
header('Content-Type: text/javascript;');
header('Access-Control-Allow-Origin: http://client');
header('Access-Control-Max-Age: 3628800');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');


include_once 'include/bootstrap.php';

include_once 'body.php';
// you need to echo data as follows
/*
 * 

  $result=array(
  'data'=>'my data',
  'status'=>1
  );
  echo $_GET['callback'] . '(' . json_encode($result) . ')';
 * 
 */
?>