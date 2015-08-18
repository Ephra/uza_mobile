<?php

/**
  Document   : send_sms
  Created on : 02-03-2014, 22:38:29
  @author     : Ephraim Swilla <swillae1@gmail.com>
 * 
  Description:
  Purpose of the file is to send sms to user mobile phone
 */
//send sms in easy way
//
//Please do not touch anything here, for any change please contact us via our emails
//
//------------------------------------------------------------------------------
/**
  Document   : send_email_processor
  Created on : 26-Jul-2012, 22:38:29
  @author     : Ephraim Swilla <swillae1@gmail.com>
  Description:
  Purpose of the file is to send sms to user mobile phone
 */

class Sender {

    var $host;
    var $port;
    /*
     * Username that  is to be used  for submission 
     */
    var $strUserName;
    /*
     * password  that  is to be used along with username 
     */
    var $strPassword;
    /*
     * Sender Id to be used for submitting the  message 
     */
    var $strSender;
    /*
     * Message content    that  is to be transmitted 
     */
    var $strMessage;
    /*
     * Mobile No is to be transmitted. 
     */
    var $strMobile;

    /*
     * What type of the message  that  is to be sent 
     * <ul> 
     * <li>0:means plain text</li> 
     * <li>1:means flash</li> 
     * <li>2:means Unicode (Message content        should be in Hex)</li> 
     * <li>6:means Unicode Flash (Message content         should be in Hex)</li> 
     * </ul> 
     */
    var $strMessageType;
    /*
     * Require DLR  or not 
     * <ul> 
     * <li>0:means DLR is  not  Required</li> 
     * <li>1:means DLR is  Required</li> 
     * </ul> 
     */
    var $strDlr;

    private function sms_unicode($message) {

        $hex1 = '';

        if (function_exists('iconv')) {
            $latin = @iconv('UTF-8', 'ISO-8859- 1', $message);
            if (strcmp($latin, $message)) {
                $arr = unpack('H*hex', @iconv('UTF-8', 'UCS- 
          2BE', $message));
                $hex1 = strtoupper($arr['hex ']);
            }
            if ($hex1 == '') {
                $hex2 = '';
                $hex = '';

                for ($i = 0; $i < strlen($message); $i++) {
                    $hex = dechex(ord($message[$i]));
                    $len = strlen($hex);
                    $add = 4 - $len;
                    if ($len < 4) {
                        for ($j = 0; $j < $add; $j++) {
                            $hex = "0" . $hex;
                        }
                    }
                    $hex2.=$hex;
                }
                return $hex2;
            } else {
                return $hex1;
            }
        } else {
            print 'iconv Function Not Exists !';
        }
    }

//Constructor.. 
    public function Sender($host, $port, $username, $password, $sender, $message, $mobile, $msgtype, $dlr) {
        $this->host = $host;
        $this->port = $port;
        $this->strUserName = $username;
        $this->strPassword = $password;
        $this->strSender = $sender;
        $this->strMessage = $message; //URL  Encode The Message.. 
        $this->strMobile = $mobile;
        $this->strMessageType = $msgtype;
        $this->strDlr = $dlr;
    }

    public function Submit() {
        if ($this->strMessageType == "2" ||
        $this->strMessageType == "6") {
//Call The Function Of String To HEX . 
            $this->strMessage = $this->sms_unicode(
            $this->strMessage);

            try {
//Smpp http  Url to send  sms. 
                $live_url = "http://" . $this->host . ":" . $this->port . "/bulksms/bulksms?username=" . $this->strUserName . "&password=" . $this->strPassword . "&type=" . $this->strMessageType . "&dlr=" . $this->strDlr . "&destination=" . $this->strMobile . "&source=" . $this->strSender . "&message=" . $this->strMessage . "";
                //$parse_url = file($live_url);
                $parse_url = fopen($live_url, 'r');
                $parse_url[0];
                //  echo    $parse_url[0];
            } catch (Exception $e) {

//echo 'Message:'  .$e->getMessage();
                $e->getMessage();
            }
        } else
            $this->strMessage = urlencode($this->strMessage);
        try {
//Smpp http  Url to send  sms. 
            $live_url = "http://" . $this->host . ":" . $this->port . "/bulksms/bulksms?username=" . $this->strUserName . "&password=" . $this->strPassword . "&type=" . $this->strMessageType . "&dlr=" . $this->strDlr . "&destination=" . $this->strMobile . "&source=" . $this->strSender . "&message=" . $this->strMessage . "";
            $parse_url = file($live_url);
            // echo $parse_url[0];
            // $parse_url[0];
        } catch (Exception $e) {
            // echo 'Message:' . $e->getMessage();
            // $e->getMessage();
        }
        return $parse_url[0];
    }

}

class sms_sender {

    public $phone_number;
    public $message;
    private $name;

    /**
     * 
     * @param string/integer $phone_number of user
     */
    public function set_phone_number($phone_number) {
        $this->phone_number = $phone_number;
    }

    /**
     * 
     * @param string $message Message to be sent to user phone
     */
    public function set_message($message) {
        $this->message = $message;
    }

    public function set_from_name($name = '') {
        global $ses_user;
        if (!empty($ses_user) && $name == '') {
            $this->name = $ses_user->name;
        } else {
            $this->name = $name;
        }
    }

    /**
     * 
     * @return boolean true if sms sent
     * @throws Exception if not phone number and message are set
     */
    public function send() {

        if (empty($this->message) && empty($this->phone_number)) {
            throw new Exception('<h4 class="alert_error">You can not send sms without first set message and phone number</h4>');
        } else {
            /**
             * User is connected to the internet
             */
            $send = new Sender('platform.jumbosms.biz', '8080', 'tml-inets', '12345', urlencode($this->name), $this->message, $this->phone_number, '5', '0');
             $result = $send->Submit();
             $result2 = substr($result, 0, 4);
            switch ($result2) {
                case '1701':
                    return TRUE;
                    break;
                case '1702':
                    error_record('Invalid URL Error, This means that one of the parameters was not
provided or left blank', $result);
                    return FALSE;
                case '1703':
                    error_record('<h4 class="alert_error">Invalid value</h4>', $result2);
                    return FALSE;
                    break;
                case '1704':
                    error_record('<h4 class="alert_error">Invalid value type</h4>', $result2);
                    return FALSE;
                    break;
                case '1705':
                    error_record('<h4 class="alert_error">Invalid message</h4>', $result2);
                    return FALSE;
                    break;
                case '1706':
                    error_record('<h4 class="alert_error">Invalid destination</h4>', $result2);
                    return FALSE;
                    break;
                case '1707':
                    error_record('<h4 class="alert_error">Invalid source</h4>', $result2);
                    return FALSE;
                    break;
                case '1709':
                    error_record('<h4 class="alert_error">User validation failed</h4>', $result2);
                    return FALSE;
                    break;
                case '1710':
                    error_record('<h4 class="alert_error">Enternal error</h4>', $result2);
                    return FALSE;
                    break;
                case '1025':
                    error_record('<h4 class="alert_error">Insufficient credit, <a href="#" id="contact_us">click here</a> to recharge</h4>', $result2);
                    return FALSE;
                    break;
                default :
                    return FALSE;
                    break;
            }
        }
    }

}
?>