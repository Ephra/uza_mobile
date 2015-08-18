<?php

class login {

    function __construct() {
	
    }

    function authenticate() {
	global $db;
	$email = $_GET['email'];
	$password = sha1(md5($_GET['password']));
	$where = array(
	    'email' => $email,
	    'password' => $password
	);
	$user = $db->fetch_object("select * from user where email='" . $email . "' and password='" . $password . "' ");
	if (empty($user)) {
	    //this user is not in the database
	    $result = array(
		'status' => 0,
		'message' => 'Wrong email or password'
	    );
	} else {
	    //this user is in the database
	    $result = array(
		'status' => 1,
		'message' => 'success'
	    );
	}
	echo $_GET['callback'] . '(' . json_encode($result) . ')';
    }

}

?>