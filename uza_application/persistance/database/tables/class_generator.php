<?php

/**
 * @author Ephraim Swilla <swillae1@gmail.com>
 */
defined('SERVER') ? NULL : define('SERVER', 'localhost');      //---database server, default localhost
defined('DB_USERNAME') ? NULL : define('DB_USERNAME', 'root'); //---database username, default root
defined('DB_PASSWORD') ? NULL : define('DB_PASSWORD', '');     //---database password 
defined('DB_NAME') ? NULL : define('DB_NAME', '896128_uza');       //---database name



mysql_connect(SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());

$query = mysql_query('SHOW TABLES');
$table = '';

while ($row = mysql_fetch_array($query)) {
    $table = $row['Tables_in_' . DB_NAME];

    $sql = mysql_query("SHOW COLUMNS IN `$table` ") or die(mysql_error());
    $table=  str_replace('', '', $table);

    $class = '<?php    
/**
 * @copyright      (c) '.  date('Y').', Innovation network and company limited
 * 
 *  ' . $table . ' 
 *  @access        public 
 *  @author        Ephraim Swilla <swillae1@gmail.com>
 *  
 */

class  ' . $table . '  extends db{

';
    $field = '';
    while ($row1 = mysql_fetch_array($sql)) {
        $column = str_replace($table.'_', '', $row1['Field']);

        $field.='\'' . $column . '\',';

        $class.='

    /**
    *@var ' . $column . '
    */
    public $' . $column . ';
 
';
    }

$field=  rtrim($field, ',');
    $class.='
   /**
     * @access  by database to instatiate fields but you can use it
     * @return array of fields present in database table
     */     
    public function fields(){

      return $data=array(' . $field . ');
    }


';
    if ($table == 'user') {
        $class.=' public function user_pic($width = 40, $height = 40, $option = "") {
        if (empty($this->profile_pic)) {
            $this->profile_pic = "profile.jpg";
            $id = 0;
            $link = $id . "/" . $this->profile_pic;
        } else {
            $link = $this->id . "/" . $this->profile_pic;
        }
        $title = $this->firstname;
        if (preg_match("/http/", $this->profile_pic)) {
            $src = $this->profile_pic;
        } else {
            $img_src = "media/members/" . $link;
            if (is_file($img_src)) {
                $src = HOME . $img_src;
            } else {
                $src = HOME . "media/members/0/profile.jpg";
            }
        }
        return "<img src=\"" . $src . "\" width=\"$width\" height=\"$height\" title=\"$title\" $option>";
    }
    }
?>';
    } else {
        $class.='} 
?>';
    }

    $file = $table . '.php';
    $handle = fopen($file, 'wb');
    fwrite($handle, $class);
}
?>
