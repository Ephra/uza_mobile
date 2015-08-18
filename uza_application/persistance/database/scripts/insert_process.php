<?php

/**
 * @author Ephraim Swilla <swillae1@gmail.com>
 */
defined('SERVER') ? NULL : define('SERVER', 'localhost');      //---database server, default localhost
defined('DB_USERNAME') ? NULL : define('DB_USERNAME', 'root'); //---database username, default root
defined('DB_PASSWORD') ? NULL : define('DB_PASSWORD', '');     //---database password 
defined('DB_NAME') ? NULL : define('DB_NAME', '896128_unnett');       //---database name



mysql_connect(SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());

$query = mysql_query('SHOW TABLES');
$table = '';

while ($row = mysql_fetch_array($query)) {
    echo $table = $row['Tables_in_' . DB_NAME];

    $sql = mysql_query("SHOW COLUMNS IN `$table` ") or die(mysql_error());

    $class = '';
    $field = '';
    $data = 'array(';
    while ($row1 = mysql_fetch_array($sql)) {

        echo $column = $row1['Field'] . '<br/>';
        $data.="'$column'=>$$column";
    }
    $data.=')';
 
    $file='$db->insert("'.$table.'",$data)';
    
    echo $file;
    exit();
}
?>
