<?php

class register {
    
    
    function register_client() {
    global $db;

    if (isset($_GET['callback']) === TRUE) {
    

    $object = array(
    "fname"=>$_GET['fname'],
    "lname"=>$_GET['lname'],
    "email"=>$_GET['email'],
    "password"=>sha1(md5($_GET['password'])),
    "phone"=>$_GET['phone'],
    "location"=>$_GET['location'],
   "user_type"=>$_GET['user_type']
   
    );
    
   $register=$db->insert('user',$object);
   if (empty($register)) {
	    $result = array(
		'status' => 0,
		'message' => 'Wrong parameters'
	    );
	} else {
	    $result = array(
		'status' => 1,
		'message' => 'Success'
	    );
	}

    echo $_GET['callback'] . '(' . json_encode($result) . ')';

          }
	}
}

