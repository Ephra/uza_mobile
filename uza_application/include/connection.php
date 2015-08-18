<?php

/**
 * file DB CONNECTION
 *
 *      INNOVATION NETWORK AND SOFTWARE COMPANY
 *
 *  @author Ephraim Swilla <swillae@yahoo.com>
 *  @author Yohanna Nasson <yohana.nasson@yahoo.com>
 *
 *
 * DESC: This is a super class file that used in all DB classes
 */
class dbpdo {

    /**
     * The database class to store PDO objects
     *
     * @var object
     */
    public $db;

    /**
     * The last query resource that ran
     *
     * @var object
     */
    public $last_query = "";
    public $seek_array = array();
    public $queries = 0;
    private $sth;

    /**
     * Connect to the database.
     *
     * @param string The database DSN.
     * @param string The database username. (depends on DSN)
     * @param string The database user's password. (depends on DSN)
     * @param array The databases driver options (optional)
     * @return boolean True on success
     */
    private $host = SERVER;
    private $user = DB_USERNAME;
    private $pass = DB_PASSWORD;
    private $dbname = DB_NAME;
    private $dbh;
    private $error;

    public function __construct() {
	// Set DSN
	$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
	// Set options
	$options = array(
	    PDO::ATTR_PERSISTENT => true,
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	);
	// Create a new PDO instanace
	try {
	    $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
	}
	// Catch any errors
	catch (PDOException $e) {
	    $this->error = $e->getMessage();
	    die('Connection failed: ' . $this->error);
	}
    }

    /**
     * Query the database.
     *
     * @param string The query SQL.
     * @return resource The query data.
     */
    function query($string) {
	++$this->queries;
//echo $string; 
	// $query = $this->db->query($string, PDO::FETCH_BOTH);
	$this->sth = $this->dbh->prepare($string);
	$this->last_query = $this->sth;
	$return = $this->sth->execute();
	$this->sth->guid = $this->queries;
	return $return;
    }

    public function insert($table, $arr = array()) {
	/*
	 *    Cleaning the key allows the developer to insert the entire
	 *    $_POST array should he wish to and still be safe from attacks.
	 */
	$arr = $this->change_keys($arr, $table);
	$keys = '`' . implode("`, `", $this->clean(array_keys($arr))) . '`';
	// Values should always be cleaned
	$values = "'" . implode("', '", $this->clean(array_values($arr))) . "'";

	// Build the query string
	$q_str = "INSERT INTO `" . TABLE_PREFIX . $table . "` (" . $keys . ") VALUES (" . $values . ")";

	return $this->query($q_str); // Execute
    }

    public function insertMultiZero($table, $arr = array()) {
	/*
	 *    Cleaning the key allows the developer to insert the entire
	 *    $_POST array should he wish to and still be safe from attacks.
	 */
	//$arr = $this->change_keys($arr[0], $table);
	$keys = '`' . implode("`, `", $this->clean(array_keys($arr[0]))) . '`';

	// Values should always be cleaned
	for ($i = 0; $i < count($arr); $i++) {
	    $val = '';
	    $arrSpace = array_count_values(array_values($arr[$i]));
	    if ($arrSpace[$val] != 2)
		$rows[$i] = "'" . implode("', '", $this->clean(array_values($arr[$i]))) . "'";
	}
	$values = "(" . implode('),(', $rows) . ")";

	// Build the query string
	$q_str = "INSERT INTO `" . TABLE_PREFIX . $table . "` (" . $keys . ") VALUES $values";

	return $this->query($q_str); // Execute
    }

    /*
     *    The update method works much in the same way as the insert
     *    method, except it takes an additional perameter which is the
     *    WHERE clause of the SQL query string which can be a string or
     *    an array coupled with the $andor perameter.
     */

    public function update($table, $arr = array(), $where = false, $andor = 'AND') {

	// Start the query string
	$q_str = "UPDATE `" . TABLE_PREFIX . $table . "` SET ";

	// Build the SET part of the query string
	foreach ($arr as $key => $value) {
	    $q_str .= '`' . $table . '_' . $this->clean($key) . "` = '" . $this->clean($value) . "', ";
	}
	$q_str = rtrim($q_str, ', ');
	$w_str = '';
	// Add WHERE clause if given
	$q_str.=$this->build_where($where, $table);
	//echo $q_str;

	return $this->query($q_str); // Execute
    }

    private function build_where($where, $table, $andor = ' AND ') {
$w_str='';
$q_str='';
	if (is_array($where) && count($where) > 0) {
	    foreach ($where as $key => $value) {
		$w_str .= '`' . $table . '_' . $this->clean($key) . "` = '" . $this->clean($value) . "' " . $andor . " ";
	    }
	    $w_str = rtrim($w_str, $andor . ' '); // Trim the last AND/OR off
	    $q_str .= " WHERE " . $w_str;
	} elseif (is_string($where) && strlen($where) > 0) {
	    $q_str .= " WHERE " . $table . '_' . $where;
	}
	return $q_str;
    }

    public function delete($table, $where) {
	// Start the query string
	$q_str = "DELETE FROM `" . TABLE_PREFIX . $table."` ";
	$q_str.=$this->build_where($where, $table);
	//echo $q_str;	exit();
	$this->query($q_str); // Execute
    }

    /*
     *    This method will take a given table name and return an integer
     *    counting the rows within the table.
     */

    public function count($table, $where = '') { // yes, we're using a native php function name...
	if (!$table) {
	    return 0;
	}
	$count = $this->query("SELECT count(*) FROM `" . TABLE_PREFIX . $table . "` " . $where);
	return count($count);
    }

    /**
     * Return a result array for a query.
     *
     * @param resource The query resource.
     * @return array The array of results.
     */
    function fetchArray() {

	return $this->sth->fetchAll();
    }

    function fetch_object($query) {
	$this->query($query);
	return $this->sth->fetchAll(PDO::FETCH_OBJ);
	//return $this->sth->fetch(PDO::FETCH_OBJ,$this->seek($this->sth->guid,'offset'), $this->seek($this->sth->guid,'row'));
    }

    /**
     * Moves internal row pointer to the next row
     *
     * @param resource The query resource.
     * @param int The pointer to move the row to.
     */
    function seek($query, $row) {
	if (!is_object($query)) {
	    return;
	}

	$this->seek_array[$query->guid] = array('offset' => PDO::FETCH_ORI_ABS, 'row' => $row);
    }

    /**
     * Return the number of rows resulting from a query.
     *
     * @param resource The query resource.
     * @return int The number of rows in the result.
     */
    function num_rows($query) {
	if (!is_object($query)) {
	    return;
	}

	return count($query->rowCount());
    }

    /**
     * Return the last id number of inserted data.
     *
     * @param string The name of the insert id to check. (Optional)
     * @return int The id number.
     */
    function id() {
	return $this->dbh->lastInsertId();
    }

    /**
     * Return an error number.
     *
     * @param resource The query resource.
     * @return int The error number of the current error.
     */
    function error_number($query) {
	if (!is_object($query) || !method_exists($query, "errorCode")) {
	    return;
	}

	$errorcode = $query->errorCode();

	return $errorcode;
    }

    /**
     * Return an error string.
     *
     * @param resource The query resource.
     * @return int The error string of the current error.
     */
    function error_string($query) {
	if (!is_object($query) || !method_exists($query, "errorInfo")) {
	    return $this->db->errorInfo();
	}
	return $query->errorInfo();
    }

    /**
     * Roll back the last query.
     *
     * @return boolean true on success, false otherwise.
     */
    function roll_back() {
	return $this->db->rollBack();
    }

    /**
     * Returns the number of affected rows in a query.
     *
     * @return int The number of affected rows.
     */
    function affected() {
	return $this->last_query->rowCount();
    }

    /**
     * Return the number of fields.
     *
     * @param resource The query resource.
     * @return int The number of fields.
     */
    function num() {
	return $this->last_query->columnCount();
    }

    function escape_string($string) {
	// Remove ' from the begginging of the string and at the end of the string, because we already use it in insert_query
	$string = substr($this->db->quote($string), 1);
	$string = substr($string, 0, -1);

	return $string;
    }

    public static function find_where($where = false, $andor = 'AND', $LIMIT = "") {
	$table = get_called_class();
	// Start the query string
	$q_str = "SELECT * FROM " . TABLE_PREFIX . $table;
	$db = new dbpdo();
	// build where clause 
	
	$q_str.=$db->build_where($where, $table);
	$up_limit = $LIMIT != '' ? 'LIMIT ' . $LIMIT : '';
	$q_str.=' order by ' . $table . '_id DESC ' . $up_limit;
	//echo $q_str;          exit();   
      
	return self::find_by_sql($q_str, $table . '_');  //return all user data
    }

    public static function find_by_id($id) {

	$table = get_called_class();
	return self::find_by_sql("SELECT * FROM " . TABLE_PREFIX . $table . " WHERE " . $table . "_id='$id'  ", $table . '_');
    }

    public static function find_all($option = '') {
	$table = get_called_class();
	return self::find_by_sql("SELECT * FROM `" . TABLE_PREFIX . $table . "` ORDER BY " . $table . "_id DESC ", $table . '_');
    }

    public static function find_by_sql($sql = "", $table='') {
	$db = new dbpdo();
        $table=$table==''? get_called_class().'_': $table;
	$obj = $db->remove_table_prefix($db->fetch_object($sql), $table);
	return $obj;
    }

    /**
     * Return a selected attribute
     *
     * @param constant The attribute to check.
     * @return string The value of the attribute.
     */
    function get_attribute($attribute) {
	$attribute = $this->db->getAttribute(constant("PDO::" . $attribute . ""));

	return $attribute;
    }

    private function change_keys($arr, $table) {
	foreach ($arr as $key => $val) {
	    $arraydisplay[$table . '_' . $key] = $val;
	}
	return $arraydisplay;
    }

    private function remove_table_prefix($arr, $table) {
	$temp_array = array();
	foreach ($arr as $key => $val) {
	    $object = new stdClass();
	    $x = (array) $val;
	    foreach ($x as $key2 => $value) {
		$new_key = str_replace($table, '', $key2);
		$object->$new_key = $value;
	    }
	    $temp_array[] = $object;
	}
	return $temp_array;
    }

    public function clean($input) {

	if (is_array($input)) {
	    foreach ($input as $key => $str) {
		$arr[$this->clean($key)] = $this->clean($str); // Call self this time with a string
	    }
	    return $arr;
	} elseif (is_string($input)) {
	    if (get_magic_quotes_gpc()) { // If magic quotes is set to on
		$input = preg_replace('#[;)(]#i', '', stripslashes($input)); // Undo what magic quotes did
	    }
	}
	return $input;
    }

}

$db = new dbpdo();
?>