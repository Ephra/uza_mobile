<?php

/**
 * Description of track_user
 *
 * @author Ephraim Swilla <swillae1@gmail.com>
 */
class track_user {

    //put your code here
    private $viewer;
    private $business_pages;

    public function __construct() {
        $this->save_record();
    }

    function ip() {

        return $_SERVER["REMOTE_ADDR"];
    }

    function country() {
        
    }

    function location() {
        require_once(RT . 'include/geoplugin.class.php');

        $geoplugin = new geoPlugin();

//locate the IP
        $geoplugin->locate();

        echo "Geolocation results for {$geoplugin->ip}: <br />\n" .
        "City: {$geoplugin->city} <br />\n" .
        "Region: {$geoplugin->region} <br />\n" .
        "Area Code: {$geoplugin->areaCode} <br />\n" .
        "DMA Code: {$geoplugin->dmaCode} <br />\n" .
        "Country Name: {$geoplugin->countryName} <br />\n" .
        "Country Code: {$geoplugin->countryCode} <br />\n";
        return '';
    }

    function start_time() {
        return date('y-m-d H:m:s', time());
    }

    function end_time() {
        
    }

    function session_id() {
        // @session_start(); // we remove warnings if session is already started
        return session_id();
    }

    function page_visit() {
        return $_SERVER['REQUEST_URI'];
    }

    function business_username() {
        global $ses_user;
        return !empty($ses_user) ? $ses_user->username : '';
    }

    function user_info() {
        if ($this->business_username() == '') {
            $this->viewer['type'] = 'subscriber';
            $this->viewer['username'] = '';
        } else {
            global $ses_user;
            $this->viewer['type'] = 'business';
            $this->viewer['username'] = $ses_user->username;
        }
    }

    public function find_user() {
        $sql = "SELECT * FROM karibu_viewer WHERE viewer_ip='" . $this->ip() . "' AND viewer_time LIKE '%" . date('Y-m-d') . "%'";
        $viewer_info = viewer::find_by_sql($sql);
        $this->viewer['info'] = array_shift($viewer_info);
    }

    function save_record() {
        global $db;
        $this->user_info();
        $this->find_user();
        $this->get_business_page();
        if (empty($this->viewer['info'])) {

            $data = array(
            'business_username' => $this->business_username(),
            'type' => $this->viewer['type'],
            'session' => $this->session_id(),
            'type_username' => $this->viewer['username'],
            'device' => DEVICE,
            'location' => '',
            'from_url' => $this->get_source(),
            'page_visited' => $this->get_page(),
            'ip' => $this->ip()
            );
            return $db->insert('viewer', $data);
        } else {
            $this->update_record();
        }
    }

    function get_business_page() {
        $business = business::find_all();
        $usernames = array();
        foreach ($business as $busines) {
            $usernames = array_merge($usernames, array($busines->username));
        }
        if (!empty($_GET['pg']) && in_array($_GET['pg'], $usernames)) {
            global $db;
            $set = array('business_page' => $_GET['pg'], 'session' => $this->session_id());
            $pv = pv::find_where($set);
            if (empty($pv)) {
                $db->insert('pv', $set);
            }
        } 
    }

    function update_record() {
        global $db;
        $pages = explode(',', $this->viewer['info']->page_visited);
        $pages_visited = array_merge($pages, (array) $this->get_page());
        $set = array('page_visited' => implode(',', $pages_visited), 'end_time' => date('Y-m-d H:m:s'));
        $db->update('viewer', $set, array('session' => $this->session_id()));
    }

    function notify_admin() {
        
    }

    function spider() {
        // Add as many spiders you want in this array
        $spiders = array('Googlebot', 'Yammybot', 'Openbot', 'Yahoo', 'Slurp', 'msnbot', 'ia_archiver', 'Lycos', 'Scooter', 'AltaVista', 'Teoma', 'Gigabot', 'Googlebot-Mobile');
        $spider = 'NONE';
        // Loop through each spider and check if it appears in
        // the User Agent
        foreach ($spiders as $spider) {
            if (preg_match("/$spider/", $_SERVER['HTTP_USER_AGENT'])) {
                return $spider;
            } else {

                return $spider;
            }
        }
    }

    function get_source() {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] :'';
    }

    function get_page() {
        // return str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']);
        // we need only simple data
        return isset($_GET['pg']) &&
        !preg_match("/media/i", input::url()) ?
        str_replace('modules/', '', input::url()) : 'index';
    }

}
