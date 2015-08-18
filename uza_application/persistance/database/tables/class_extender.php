<?php

/**
 * @author Ephraim Swilla <swillae1@gmail.com>
 */
defined('SERVER') ? NULL : define('SERVER', 'localhost');      //---database server, default localhost
defined('DB_USERNAME') ? NULL : define('DB_USERNAME', 'root'); //---database username, default root
defined('DB_PASSWORD') ? NULL : define('DB_PASSWORD', '');     //---database password 
defined('DB_NAME') ? NULL : define('DB_NAME', 'uza');       //---database name



mysql_connect(SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());

$query = mysql_query('SHOW TABLES');
$table = '';
$header = '<?php    
/**
 * @copyright      (c) ' . date('Y') . ', Innovation network and company limited
 *  @access        public 
 *  @author        Ephraim Swilla <swillae1@gmail.com>
 *  
 */
';
$class_statement='';
while ($row = mysql_fetch_array($query)) {
    $table = $row['Tables_in_' . DB_NAME];
    $sql = mysql_query("SHOW COLUMNS IN `$table` ") or die(mysql_error());
    $table = str_replace('karibu_', '', $table);
    $class_statement.= ' class  ' . $table . '  extends dbpdo{ }';
}
$footer='?>';

$data_to_write=$header.$class_statement.$footer;

$file ='tables.php';
$handle = fopen($file, 'wb');
fwrite($handle, $data_to_write);
?>
