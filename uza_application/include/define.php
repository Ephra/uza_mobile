<?php

/**
 * file Project definition
 *
 *   karibu.com
 *
 *  @author Ephraim Swilla <swillae@yahoo.com>
 *
 *
 * DESC: This define all important parameters used in a system
 */
$environment = $_SERVER["HTTP_HOST"] == 'localhost' ? 'development' : 'production';

defined('ENVIRONMENT') ? NULL : define('ENVIRONMENT', $environment);
/*
 * ---------------------------------------------------------------
 * ERROR REPORTING
 * ---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
if (defined('ENVIRONMENT')) {
    switch (ENVIRONMENT) {

        case 'development':
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            $server = 'localhost';
            $dbname = '896128_uza';
            $username = 'root';
            $password = '';
            $table_prefix = '';
            $root_folder = 'uza_application/';
            break;

        case 'testing':

        case 'production':
            error_reporting(0);
            $server = 'pdb7.runhosting.com';
            $dbname = '896128_uza';
            $username = '896128_uza';
            $password = 'inets@inets2015';
            $table_prefix = 'uza_';
            $root_folder = '';

            break;

        default:
            exit('The application environment is not set correctly.');
    }
}

/**
 * 
 * --define PROJECT TITLE  >>>>>SHOULD NOT EXCEED 1O CHARACTERS
 * 
 */
defined('TITLE') ? NULL : define('TITLE', 'karibuSMS');





#----------DOCUMENT ROOT-----------------------#

/*
 * 
 * -this define where you put your project inside a pointed directory
 * -use "" if you put in root pointed eg. C:\xampp\htdocs\....(default)
 * 
 * for our case, we put in a folder called engineering thus it is in
 *     C:\xammp\htdocs\engineering
 *   better do like what I did
 */
defined('ROOT_FOLDER') ? NULL : define('ROOT_FOLDER', $root_folder);


#----------DATABASE CONSTANSTS---------------------#

/*
 *
 * --Use this part to define your database variables 
 */


/**
 * --define name of your server
 */
defined('SERVER') ? NULL : define('SERVER', $server);




/**
 * --define database name
 */
defined('DB_NAME') ? NULL : define('DB_NAME', $dbname);




/**
 * --define database username/server name
 */
defined('DB_USERNAME') ? NULL : define('DB_USERNAME', $username);




/**
 * --define database password
 */
defined('DB_PASSWORD') ? NULL : define('DB_PASSWORD', $password);


/**
 * --define database table prefix
 */
defined('TABLE_PREFIX') ? NULL : define('TABLE_PREFIX', $table_prefix);


/**
 * --define our encryption key
 */
defined('ENCRYPTION_KEY') ? NULL : define('ENCRYPTION_KEY', "kjbksbdbnsbdkkdskj");

/**
 * --define password cookie
 */
defined('PASS_COOKIE') ? NULL : define('PASS_COOKIE', substr(sha1(TITLE . 'id'), 10));


/**
 * --define phone number cookie
 */
defined('PHONE_COOKIE') ? NULL : define('PHONE_COOKIE', substr(sha1(TABLE_PREFIX . 'id'), 10));

/**
 * --define company contact
 */
defined('COMPANY_CONTACTS') ? NULL : define('COMPANY_CONTACTS', '+255 759 55 33 55');


/**
 * --define payment costs
 */
defined('SMS_COST') ? NULL : define('SMS_COST', 100);


defined('GOLD_COST') ? NULL : define('GOLD_COST', 35);

defined('DIAMOND_COST') ? NULL : define('DIAMOND_COST', 40);

defined('SILVER_COST') ? NULL : define('SILVER_COST', 45);


defined('COST_PER_SMS') ? NULL : define('COST_PER_SMS', 25);

/**
 * These are payment bundles
 */
defined('GOLD') ? NULL : define('GOLD', 450000);
defined('DIAMOND') ? NULL : define('DIAMOND', 240000);
defined('NICKEL') ? NULL : define('NICKEL', 120000);
defined('PLATNUM') ? NULL : define('PLATNUM', 60000);
//defined('SILVER') ? NULL : define('SILVER', 30000);
//defined('BRONZE') ? NULL : define('BRONZE', 15000);


$SUBSCRIPTION_NUMBER = '15573';

$options = array(
'gold' => GOLD,
 'diamond' => DIAMOND,
 'nickel' => NICKEL,
 'platnum' => PLATNUM,
 // 'silver' => SILVER,
//'bronze' => BRONZE
);
$payment_options = array(
'gold' => array('cost' => GOLD, 'sms' => 1000, 'contact' => 'Unlimited '),
 'diamond' => array('cost' => DIAMOND, 'sms' => 450, 'contact' => 10000),
 'nickel' => array('cost' => NICKEL, 'sms' => 210, 'contact' => 4000),
 'platnum' => array('cost' => PLATNUM, 'sms' => 100, 'contact' => 1500),
 //'silver' => array('cost' => SILVER, 'sms' => 50, 'contact' => 800),
// 'bronze' => array('cost' => BRONZE, 'sms' => 30, 'contact' => 300)
);

$smart_costs = array(
'Apple' => array('cost' => '100000',
 'sms' => ' <i class="fa fa-envelope icon-muted"></i> Unlimited',
 'contact' => ' <i class="fa fa-phone-square"></i> Unlimited ',
 'shortcode Subscription' => '<i class="fa fa-cloud"></i> Allow',
 'video upload' => '<i class="fa fa-youtube-play"></i> Unlimited',
 'photo upload' => '<i class="fa fa-camera"></i> Unlimited',
 'Social Page Connection' => '<i class="fa fa-share-square-o"></i> Allow',
 'File uploads' => '<i class="fa fa-cloud-upload"></i> Allow',
 'Embed  Map location' => '<i class="fa fa-location-arrow"></i> Allow'
),
 'Mango' => array('cost' => '60000',
 'sms' => ' <i class="fa fa-envelope icon-muted"></i> Unlimited',
 'contact' => '<i class="fa fa-phone-square"></i> 3000 ',
 'shortcode Subscription' => '<i class="fa fa-cloud"></i> No',
 'video upload' => ' <i class="fa fa-youtube-play"></i> 10 maximum',
 'photo upload' => '<i class="fa fa-camera"></i> 30',
 'File uploads' => '<i class="fa fa-cloud-upload"></i> Allow',
 'Social Page Connection' => '<i class="fa fa-share-square-o"></i> No',
 'Embed  Map location' => '<i class="fa fa-location-arrow"></i> No'
),
 'Orange' => array('cost' => '20000',
 'sms' => ' <i class="fa fa-envelope icon-muted"></i> unlimted',
 'contact' => '<i class="fa fa-phone-square"></i> 400',
 'shortcode Subscription' => ' <i class="fa fa-cloud"></i> No',
 'video upload' => '<i class="fa fa-youtube-play"></i> No',
 'photo upload' => '<i class="fa fa-camera"></i> 5',
 'File uploads' => '<i class="fa fa-cloud-upload"></i> No',
 'Social Page Connection' => '<i class="fa fa-share-square-o"></i> No',
 'Embed  Map location' => '<i class="fa fa-location-arrow"></i> No'
),
);
?>
