<?php



/**
 * Description of view
 *
 * @author user
 */
class view extends dbpdo {

    public $view_name;

    public function __construct($view_name) {
         parent::__construct();
        $this->view_name = $view_name;
    }

    public  function get_where($where,$andor="AND") {
        $q_str = "SELECT * FROM " . $this->view_name;
        // build where clause 
        $w_str = '';
        if (is_array($where) && count($where) > 0) {
            foreach ($where as $key => $value) {
                $w_str .= '`'. $this->clean($key) . "` = '" . $this->clean($value) . "' " . $andor . " ";
            }
            $w_str = rtrim($w_str, $andor . ' '); // Trim the last AND/OR off
            $q_str .= " WHERE " . $w_str;
        } elseif (is_string($where) && strlen($where) > 0) {
            $q_str .= " WHERE " . $this->view_name . '_' . $where;
        }
        return $this->fetch_object($q_str);
    }

    public function get_all() {
        $sql = "SELECT * FROM `" . $this->view_name . "`";
        return $this->fetch_object($sql);
    }

}