<?php

/**
 * Unnett
 *
 * @author	Ephraim Swilla<swillae1@gmail.com>
 */

/**
 * Input Class
 *
 */
class input {

    /**
     * IP address of the current user
     *
     * @var string
     */
    var $ip_address = FALSE;

    /**
     * user agent (web browser) being used by the current user
     *
     * @var string
     */
    var $user_agent = FALSE;

    /**
     * If FALSE, then $_GET will be set to an empty array
     *
     * @var bool
     */
    var $_allow_get_array = TRUE;

    /**
     * If TRUE, then newlines are standardized
     *
     * @var bool
     */
    var $_standardize_newlines = TRUE;

    /**
     * Determines whether the XSS filter is always active when GET, POST or COOKIE data is encountered
     * Set automatically based on config setting
     *
     * @var bool
     */
    var $_enable_xss = FALSE;

    /**
     * Enables a CSRF cookie token to be set.
     * Set automatically based on config setting
     *
     * @var bool
     */
    var $_enable_csrf = FALSE;

    /**
     * List of all HTTP request headers
     *
     * @var array
     */
    protected $headers = array();

    /**
     * Constructor
     *
     * Sets whether to globally enable the XSS processing
     * and whether to allow the $_GET array
     *
     * @return	void
     */
    public function __construct() {
        //log_message('debug', "Input Class Initialized");
//		$this->_allow_get_array	= (config_item('allow_get_array') === TRUE);
//		$this->_enable_xss		= (config_item('global_xss_filtering') === TRUE);
//		$this->_enable_csrf		= (config_item('csrf_protection') === TRUE);

        $this->check_login();
        global $SEC;

//		$this->_sanitize_globals();
    }

    // --------------------------------------------------------------------

    /**
     * Fetch from array
     *
     * This is a helper function to retrieve values from global arrays
     *
     * @access	private
     * @param	array
     * @param	string
     * @param	bool
     * @return	string
     */
    function _fetch_from_array(&$array, $index = '', $xss_clean = FALSE) {
        if (!isset($array[$index])) {
            return FALSE;
        }

        if ($xss_clean === TRUE) {
            return $this->security->xss_clean($array[$index]);
        }

        return $array[$index];
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the GET array
     *
     * @access	public
     * @param	string
     * @param	bool
     * @return	string
     */
    function get($index = NULL, $xss_clean = FALSE) {
        // Check if a field has been provided
        if ($index === NULL AND ! empty($_GET)) {
            $get = array();

            // loop through the full _GET array
            foreach (array_keys($_GET) as $key) {
                $get[$key] = $this->_fetch_from_array($_GET, $key, $xss_clean);
            }
            return $get;
        }

        return $this->_fetch_from_array($_GET, $index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from the POST array
     *
     * @access	public
     * @param	string
     * @param	bool
     * @return	string
     */
    function post($index = NULL, $xss_clean = FALSE) {
        // Check if a field has been provided
        if ($index === NULL AND ! empty($_POST)) {
            $post = array();

            // Loop through the full _POST array and return it
            foreach (array_keys($_POST) as $key) {
                $post[$key] = $this->_fetch_from_array($_POST, $key, $xss_clean);
            }
            return $post;
        }

        return $this->_fetch_from_array($_POST, $index, $xss_clean);
    }

    // --------------------------------------------------------------------

    /**
     * Fetch an item from either the GET array or the POST
     *
     * @access	public
     * @param	string	The index key
     * @param	bool	XSS cleaning
     * @return	string
     */
    function get_post($index = '', $xss_clean = FALSE) {
        if (!isset($_POST[$index])) {
            return $this->get($index, $xss_clean);
        } else {
            return $this->post($index, $xss_clean);
        }
    }

    /**
     * Validate IP Address
     *
     * @access	public
     * @param	string
     * @param	string	ipv4 or ipv6
     * @return	bool
     */
    public function valid_ip($ip, $which = '') {
        $which = strtolower($which);

        // First check if filter_var is available
        if (is_callable('filter_var')) {
            switch ($which) {
                case 'ipv4':
                    $flag = FILTER_FLAG_IPV4;
                    break;
                case 'ipv6':
                    $flag = FILTER_FLAG_IPV6;
                    break;
                default:
                    $flag = '';
                    break;
            }

            return (bool) filter_var($ip, FILTER_VALIDATE_IP, $flag);
        }

        if ($which !== 'ipv6' && $which !== 'ipv4') {
            if (strpos($ip, ':') !== FALSE) {
                $which = 'ipv6';
            } elseif (strpos($ip, '.') !== FALSE) {
                $which = 'ipv4';
            } else {
                return FALSE;
            }
        }

        $func = '_valid_' . $which;
        return $this->$func($ip);
    }

    // --------------------------------------------------------------------

    /**
     * Validate IPv4 Address
     *
     * Updated version suggested by Geert De Deckere
     *
     * @access	protected
     * @param	string
     * @return	bool
     */
    protected function _valid_ipv4($ip) {
        $ip_segments = explode('.', $ip);

        // Always 4 segments needed
        if (count($ip_segments) !== 4) {
            return FALSE;
        }
        // IP can not start with 0
        if ($ip_segments[0][0] == '0') {
            return FALSE;
        }

        // Check each segment
        foreach ($ip_segments as $segment) {
            // IP segments must be digits and can not be
            // longer than 3 digits or greater then 255
            if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3) {
                return FALSE;
            }
        }

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Validate IPv6 Address
     *
     * @access	protected
     * @param	string
     * @return	bool
     */
    protected function _valid_ipv6($str) {
        // 8 groups, separated by :
        // 0-ffff per group
        // one set of consecutive 0 groups can be collapsed to ::

        $groups = 8;
        $collapsed = FALSE;

        $chunks = array_filter(
        preg_split('/(:{1,2})/', $str, NULL, PREG_SPLIT_DELIM_CAPTURE)
        );

        // Rule out easy nonsense
        if (current($chunks) == ':' OR end($chunks) == ':') {
            return FALSE;
        }

        // PHP supports IPv4-mapped IPv6 addresses, so we'll expect those as well
        if (strpos(end($chunks), '.') !== FALSE) {
            $ipv4 = array_pop($chunks);

            if (!$this->_valid_ipv4($ipv4)) {
                return FALSE;
            }

            $groups--;
        }

        while ($seg = array_pop($chunks)) {
            if ($seg[0] == ':') {
                if (--$groups == 0) {
                    return FALSE; // too many groups
                }

                if (strlen($seg) > 2) {
                    return FALSE; // long separator
                }

                if ($seg == '::') {
                    if ($collapsed) {
                        return FALSE; // multiple collapsed
                    }

                    $collapsed = TRUE;
                }
            } elseif (preg_match("/[^0-9a-f]/i", $seg) OR strlen($seg) > 4) {
                return FALSE; // invalid segment
            }
        }

        return $collapsed OR $groups == 1;
    }

    // --------------------------------------------------------------------

    /**
     * User Agent
     *
     * @access	public
     * @return	string
     */
    public function user_agent() {
        if ($this->user_agent !== FALSE) {
            return $this->user_agent;
        }
        $user_device = "";
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match("/iPhone/", $agent)) {
            $user_device = "iPhone Mobile";
        } else if (preg_match("/Android/", $agent)) {
            $user_device = "Android Mobile";
        } else if (preg_match("/IEMobile/", $agent)) {
            $user_device = "Windows Mobile";
        } else if (preg_match("/Chrome/", $agent)) {
            $user_device = "Google Chrome";
        } else if (preg_match("/MSIE/", $agent)) {
            $user_device = "Internet Explorer";
        } else if (preg_match("/Firefox/", $agent)) {
            $user_device = "Firefox";
        } else if (preg_match("/Safari/", $agent)) {
            $user_device = "Safari";
        } else if (preg_match("/Opera/", $agent)) {
            $user_device = "Opera";
        }
        $OSList = array
        (
        // Match user agent string with operating systems
        'Windows 3.11' => 'Win16',
        'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
        'Windows 98' => '(Windows 98)|(Win98)',
        'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
        'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
        'Windows Server 2003' => '(Windows NT 5.2)',
        'Windows Vista' => '(Windows NT 6.0)',
        'Windows 7' => '(Windows NT 6.1)|(Windows NT 7.0)',
        'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
        'Windows ME' => 'Windows ME',
        'Open BSD' => 'OpenBSD',
        'Sun OS' => 'SunOS',
        'Linux' => '(Linux)|(X11)',
        'Mac OS' => '(Mac_PowerPC)|(Macintosh)',
        'QNX' => 'QNX',
        'BeOS' => 'BeOS',
        'OS2' => 'OS2',
        'Mac OS' => 'Mac OS',
        //'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
        );

        // Loop through the array of user agents and matching operating systems
        foreach ($OSList as $CurrOS => $Match) {
            // Find a match
            $Match = "/$Match/i";
            if (preg_match($Match, $agent)) {
                break;
            } else {
                $CurrOS = "Unknown OS";
            }
        }
        $DEVICE = "$user_device : $CurrOS";
        ;
        return $DEVICE;
        /**
         * @uses device Give a user which device he/she use
         * @example ---Google Chrome : Windows 7--- user use this device
         */
        // $this->user_agent = (!isset($_SERVER['HTTP_USER_AGENT'])) ? FALSE : $_SERVER['HTTP_USER_AGENT'];
    }

    // --------------------------------------------------------------------

    /**
     * Sanitize Globals
     *
     * This function does the following:
     *
     * Unsets $_GET data (if query strings are not enabled)
     *
     * Unsets all globals if register_globals is enabled
     *
     * Standardizes newline characters to \n
     *
     * @access	private
     * @return	void
     */
    function _sanitize_globals() {
        // It would be "wrong" to unset any of these GLOBALS.
        $protected = array('_SERVER', '_GET', '_POST', '_FILES', '_REQUEST',
        '_SESSION', '_ENV', 'GLOBALS', 'HTTP_RAW_POST_DATA',
        'system_folder', 'application_folder', 'BM', 'EXT',
        'CFG', 'URI', 'RTR', 'OUT', 'IN');

        // Unset globals for securiy.
        // This is effectively the same as register_globals = off
        foreach (array($_GET, $_POST, $_COOKIE) as $global) {
            if (!is_array($global)) {
                if (!in_array($global, $protected)) {
                    global $$global;
                    $$global = NULL;
                }
            } else {
                foreach ($global as $key => $val) {
                    if (!in_array($key, $protected)) {
                        global $$key;
                        $$key = NULL;
                    }
                }
            }
        }

        // Is $_GET data allowed? If not we'll set the $_GET to an empty array
        if ($this->_allow_get_array == FALSE) {
            $_GET = array();
        } else {
            if (is_array($_GET) AND count($_GET) > 0) {
                foreach ($_GET as $key => $val) {
                    $_GET[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
                }
            }
        }

        // Clean $_POST Data
        if (is_array($_POST) AND count($_POST) > 0) {
            foreach ($_POST as $key => $val) {
                $_POST[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
        }

        // Clean $_COOKIE Data
        if (is_array($_COOKIE) AND count($_COOKIE) > 0) {
            // Also get rid of specially treated cookies that might be set by a server
            // or silly application, that are of no use to a CI application anyway
            // but that when present will trip our 'Disallowed Key Characters' alarm
            // http://www.ietf.org/rfc/rfc2109.txt
            // note that the key names below are single quoted strings, and are not PHP variables
            unset($_COOKIE['$Version']);
            unset($_COOKIE['$Path']);
            unset($_COOKIE['$Domain']);

            foreach ($_COOKIE as $key => $val) {
                $_COOKIE[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
        }

        // Sanitize PHP_SELF
        $_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);


        // CSRF Protection check on HTTP requests
        if ($this->_enable_csrf == TRUE && !$this->is_cli_request()) {
            $this->security->csrf_verify();
        }
    }

    // --------------------------------------------------------------------

    /**
     * Clean Input Data
     *
     * This is a helper function. It escapes data and
     * standardizes newline characters to \n
     *
     * @access	private
     * @param	string
     * @return	string
     */
    function _clean_input_data($str) {
        if (is_array($str)) {
            $new_array = array();
            foreach ($str as $key => $val) {
                $new_array[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
            return $new_array;
        }

        /* We strip slashes if magic quotes is on to keep things consistent

          NOTE: In PHP 5.4 get_magic_quotes_gpc() will always return 0 and
          it will probably not exist in future versions at all.
         */

        // Should we filter the input data?
        if ($this->_enable_xss === TRUE) {
            $str = $this->security->xss_clean($str);
        }

        // Standardize newlines if needed
        if ($this->_standardize_newlines == TRUE) {
            if (strpos($str, "\r") !== FALSE) {
                $str = str_replace(array("\r\n", "\r", "\r\n\n"), PHP_EOL, $str);
            }
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
     * Clean Keys
     *
     * This is a helper function. To prevent malicious users
     * from trying to exploit keys we make sure that keys are
     * only named with alpha-numeric text and a few other items.
     *
     * @access	private
     * @param	string
     * @return	string
     */
    function _clean_input_keys($str) {
        if (!preg_match("/^[a-z0-9:_\/-]+$/i", $str)) {
            exit('Disallowed Key Characters.');
        }

        return $str;
    }

    /**
     * Is ajax Request?
     *
     * Test to see if a request contains the HTTP_X_REQUESTED_WITH header
     *
     * @return 	boolean
     */
    public function is_ajax_request() {
        return ($_SERVER('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest');
    }

    // --------------------------------------------------------------------
    function keys_map($arr, $arr2) {
        $key = null;
        foreach ($arr as $key1 => $value) {
            foreach ($arr2 as $key2) {
                if ($key1 == $key2) {
                    $key = $key1;
                }
            }
        }
        return $key;
    }

    public static function url() {
/**
 * 
 */
        $global_data = isset($_GET) ? $_GET : $_POST;
        $input = new input();

        $page = preg_replace('/#/', '', $input->get_post('pg'));
	
        $param = array("process", 'section', 'part');
	
        $key_match = $input->keys_map($global_data, $param);
	
        if ($key_match != '') {
            $build_url = $key_match . '/' . $input->get_post($key_match) . '.php';
        } else if (isset($_GET['sec'])) {
            $build_url = 'section/' . $input->get_post('sec') . '.php';
        } else {
            $build_url = $page . '.php';
        }
        return 'modules/' . $page . '/' . $build_url;
    }

    /**
     * Is cli Request?
     *
     * Test to see if a request was made from the command line
     *
     * @return 	bool
     */
    public function is_cli_request() {
        return (php_sapi_name() === 'cli' OR defined('STDIN'));
    }

    public function check_login() {
        if (!isset($_SESSION['id'])) {

            if (isset($_COOKIE[PHONE_COOKIE]) != '' && isset($_COOKIE[PASS_COOKIE]) != '') {

                $phone_number = $this->decrypt($_COOKIE[PHONE_COOKIE]);
                $password = $this->decrypt($_COOKIE[PASS_COOKIE]);
                $data = array(
                "phone_number" => preg_replace('/[^0-9]/', '', $phone_number),
                "password" => $password
                );

                $result = business::find_where($data);
                if (!empty($result)) {

                    /* ------user is correct, take user ------- */
                    $user = array_shift($result);


                    $_SESSION['id'] = $user->id;
                    $_SESSION['username'] = $user->username;
                    $_SESSION['login'] = TRUE;

                    //we have to create a login table to keep track of all login data

                    /* ------establish cookie------- */

                    setcookie(PHONE_COOKIE, $this->encrypt($user->phone_number), time() + 60 * 60 * 24 * 100, "/"); // Cookie set to expire in about 30 days
                    setcookie(PASS_COOKIE, $this->encrypt($user->password), time() + 60 * 60 * 24 * 100, "/"); // Cookie set to expire in about 30 days
                }
            }
        }
    }

    /**
     * 
     * @param type $timeposted
     */
    public function make_time_ago($date) {
        if (empty($date)) {
            return "No date provided";
        }

        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        $now = time();
        $unix_date = strtotime($date);

        // check validity of date
        if (empty($unix_date)) {
            return "Bad date";
        }

        // is it future date or past date
        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "ago";
        } else {
            $difference = $unix_date - $now;
            $tense = "from now";
        }

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j].= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }

    public function clean($data) {
        // http://svn.bitflux.ch/repos/public/popoon/trunk/classes/externalinput.php
        // +----------------------------------------------------------------------+
        // | Copyright (c) 2001-2006 Bitflux GmbH                                 |
        // +----------------------------------------------------------------------+
        // | Licensed under the Apache License, Version 2.0 (the "License");      |
        // | you may not use this file except in compliance with the License.     |
        // | You may obtain a copy of the License at                              |
        // | http://www.apache.org/licenses/LICENSE-2.0                           |
        // | Unless required by applicable law or agreed to in writing, software  |
        // | distributed under the License is distributed on an "AS IS" BASIS,    |
        // | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or      |
        // | implied. See the License for the specific language governing         |
        // | permissions and limitations under the License.                       |
        // +----------------------------------------------------------------------+
        // | Author: Christian Stocker <chregu@bitflux.ch>                        |
        // +----------------------------------------------------------------------+
        //
		// Kohana Modifications:
        // * Changed double quotes to single quotes, changed indenting and spacing
        // * Removed magic_quotes stuff
        // * Increased regex readability:
        //   * Used delimeters that aren't found in the pattern
        //   * Removed all unneeded escapes
        //   * Deleted U modifiers and swapped greediness where needed
        // * Increased regex speed:
        //   * Made capturing parentheses non-capturing where possible
        //   * Removed parentheses where possible
        //   * Split up alternation alternatives
        //   * Made some quantifiers possessive
        // Fix &entity\n;
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        return $data;
    }

    public function language($name) {
        if (isset($_COOKIE['lang']) && $_COOKIE['lang'] != '') {
            define('LANG', $_COOKIE['lang']);
        } else {
            define('LAND', 'en');
        }
    }

    /**
     * Returns an encrypted & utf8-encoded
     */
    function encrypt($pure_string) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, ENCRYPTION_KEY, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
        return $encrypted_string;
    }

    /**
     * Returns decrypted original string
     */
    function decrypt($encrypted_string) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, ENCRYPTION_KEY, $encrypted_string, MCRYPT_MODE_ECB, $iv);
        return $decrypted_string;
    }

    static function check_android_state() {
        if (isset($_GET['a_business_id']) && $_GET['a_business_id'] != '') {
            $business_info = business::find_by_id($_GET['a_business_id']);
            if (!empty($business_info)) {
                //this user okay
                $business = array_shift($business_info);
                $_SESSION['id'] = $business->id;
                $_SESSION['username'] = $business->username;
                $_SESSION['idx'] = base64_encode("g4p3h9xfn8sq03hs2234{$business->id}"); //use of token when nessessary
$input=new input();
                setcookie(PHONE_COOKIE, $input->encrypt($business->phone_number), time() + 60 * 60 * 24 * 100, "/"); // Cookie set to expire in about 30 days
                setcookie(PASS_COOKIE, $input->encrypt($business->password), time() + 60 * 60 * 24 * 100, "/");
            }
        }
    }

}

//date_default_timezone_set("Africa/Dar_es_Salaam");

$input = new input();
input::check_android_state();
defined('DEVICE') ? NULL : define('DEVICE', $input->user_agent());
