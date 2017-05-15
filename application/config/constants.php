<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
// ------------------------------------------------------------------------
// v! Instagram credentials
// ------------------------------------------------------------------------
define('INSTA_CLIENT_ID', '17fd6de1ec314541afd3cb022b9417be');
define('INSTA_CLIENT_SECRET', '329c8fdfb8114840b6413b1f543ddc5d');
define('GOOGLE_MAP_API', 'AIzaSyBrAT6XIzO4FSwU1_iXBgvvOkAqqx8GRBw');
define('MAX_SWIPES_PER_DAY', 100);
define('MAX_POWERLUVS_PER_DAY', 5);
define('MAX_POWERLUVS_PER_DAY_P', 25);

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    define('UPLOADPATH_VIDEO', $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/Video');
    define('UPLOADPATH_IMAGE', $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/Image');
    define('UPLOADPATH_THUMB', $_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/thumb');
    define('FFMPEG_PATH', 'C:\wamp64\www\ffmpeg\bin\ffmpeg.exe');
} else if ($_SERVER['REMOTE_ADDR'] == "::1") {
    define('UPLOADPATH_VIDEO', $_SERVER['DOCUMENT_ROOT'] . '/Luvr/assets/uploads/Video');
    define('UPLOADPATH_IMAGE', $_SERVER['DOCUMENT_ROOT'] . '/Luvr/assets/uploads/Image');
    define('UPLOADPATH_THUMB', $_SERVER['DOCUMENT_ROOT'] . '/Luvr/assets/uploads/thumb');
    define('FFMPEG_PATH', 'C:\wamp\www\ffmpeg\bin\ffmpeg.exe');
} else {
    define('UPLOADPATH_VIDEO', '/var/www/html/Luvr/Webservices/upload/Video');
    define('UPLOADPATH_IMAGE', '/var/www/html/Luvr/Webservices/upload/Image');
    define('UPLOADPATH_THUMB', '/var/www/html/Luvr/Webservices/upload/thumb');
    define('FFMPEG_PATH', 'ffmpeg');
}

define('PAYMENTMODE', 'test'); // test or live
define('SK_TEST', 'sk_test_1875LyNh0Os2itzBhXqr1PdY');
define('PK_TEST', 'pk_test_juEgzUzoPJDOOg0PSOmcjNST');
define('SK_LIVE', 'sk_live_bkbdJ9bHYnyuJZyJubMxEV0C');
define('PK_LIVE', 'pk_live_pPrWC34t8fIoB4pmCUQZeuPa');
define('PAY_CURRENCY', 'usd');
define('MOBFOX_ACCID', '70867');
define('MOBFOX_APIKEY', 'd760574985b121451bac270681299360');
define('MOBFOX_INVHASH_DEV', '42a2d1ef33dff37f3cc611e90d1c7105');
define('MOBFOX_INVHASH_LIVE', '0a8d0704acb5f06d5f6b3ece7310fe43');
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    define('TWILIO_SID', 'ACb2e5ebf44e6a7261e2c6b80df0a6ec3c');
    define('TWILIO_TOKEN', 'e826454c36c845e16dc3ebb07bf3e2be');
} else {
    define('TWILIO_SID', 'ACc7fb9741b3167e4694bdbc7ac7c3b3ff');
    define('TWILIO_TOKEN', 'd990daed3f178c683cf5cdd84b67865b');
}