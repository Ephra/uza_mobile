<?php

/**
 * In every request sent back to mobile app, we return 
 * json array with at least two parameters of status and message
 * 
 * 
 * STATUS CODES
 * 
 *   200='success'
 *   201='application logic error'
 *   202='database error'
 *   203=''
 */
$page = '';
if (isset($_GET['pg']) && $_GET['pg'] != '') {
    $page = $input->get_post('pg');
    $url = input::url();
    if (file_exists($url)) {
	include($url);

	$classname = $_GET['pg'];
	$method = $_GET['method'];

	$myclass = new $classname;
	$myclass->$method();
    } else {
	include('404.php');
    }
} else {
    $array = array(
	'status' => 201,
	'message' => 'No page specified'
    );
    
    echo $_GET['callback'] . '(' . json_encode($array) . ')';
    //echo json_encode($array);
}
?>
