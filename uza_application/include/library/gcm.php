<?php

class gcm {

    public static $HEADER = array(
    'Authorization: key=AIzaSyCEmicefa98_ECH5kFDfqADl_Sl5cQusuA',
    'Content-Type: application/json'
    );

    const URL = 'https://android.googleapis.com/gcm/send';
    const BROADCAST_ID = 'APA91bGawSnXF9n00n00gZpkkCfNRIaOJvzjFeC7X6RgcW7wNoc83snrcigx_MpzW6SPtt6LShKu-gNnG6UwQ490z8K8ZQ35esAN_dSxSOY9hqkq3Z_CywGVdoHEQniXEY7DpjnWxlq_QIs9aNOeRKi25YygT8fd_g';
    
    const INETS_ID='APA91bGaJRoN0ZsQCPRurDGfd5aTHj0k2j4FbQdw_w0DwfoeSnMM8OF0tjbYm53dgWHX91JF9uTG05jJ9RRPRK6qdoYjj9jhIpyihwmwTAA33wiVLs6yNxVQfCXcDL7fl2p7aeE0UN1ntaAp0QP9KMWjhi50nc4vbg';

    public function __construct() {
        // $reg_id=$_GET['regId'];
        // 'APA91bGawSnXF9n00n00gZpkkCfNRIaOJvzjFeC7X6RgcW7wNoc83snrcigx_MpzW6SPtt6LShKu-gNnG6UwQ490z8K8ZQ35esAN_dSxSOY9hqkq3Z_CywGVdoHEQniXEY7DpjnWxlq_QIs9aNOeRKi25YygT8fd_g'
        //$message = array("Notice" => $_POST['message']);
    }

    /**
     * 
     * @param type $message
     * @param type $gcm_id
     * @return type
     */
    public static function push_to_id($message, $gcm_id) {
        $fields = array(
        'registration_ids' => array($gcm_id),
        'data' => array("Notice" => $message),
        );
        //echo json_encode($fields);
       return self::curl($fields);
    }

    public static function push_from_platform($message) {
        $fields = array(
        'registration_ids' => array(self::INETS_ID),
        'data' => array("Notice" => $message),
        );
       return self::curl($fields);
    }

    public function push_to_all($message) {
        $sms = array("Notice" => $message);
        $fields = array(
        'registration_ids' => array(self::BROADCAST_ID),
        'data' => $sms,
        );
        self::curl($fields);
    }

    private static function curl($fields) {
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, self::URL);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::$HEADER);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);

        curl_close($ch);

        if ($result != '') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
?>
