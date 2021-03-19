<?php

/*

# nginx config
location / {
    if ($request_method = 'OPTIONS') {
        add_header 'Access-Control-Allow-Origin' '*';
        add_header 'Access-Control-Allow-Methods' "POST,GET,DELETE,PUT,OPTIONS";
        add_header 'Access-Control-Allow-Headers' 'DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type';
        add_header 'Access-Control-Max-Age' 1728000;
        return 204;
    }
}

*/

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Accept, Referer, User-Agent, Content-Type, Origin, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// ini_set("display_errors", "On");

// require_once 'vendor/autoload.php';
// print_r($_REQUEST);

$script_uri = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}";

// session_start();

require_once("config.php");
require_once("include.php");
require_once("Mysql_connector.class.php");

$Action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$User = 0;

$DB = new Mysql_connector($DB_HOST, $DB_USERNAME, $DB_PASSWORD);
$DB->select_db($DB_NAME);

$mysqli = $DB->connection;


$ret = array();
$ret['debug'] = array();
$ret['debug']['request'] = $_REQUEST;

$json_params = file_get_contents("php://input");
if (strlen($json_params) > 0 && isValidJSON($json_params)) {
    $values = json_decode($json_params, true);
    // $ret['values'] = print_r($values, true);
    $_POST = $values;
    foreach ($_POST as $key => $value) {
        $_REQUEST[$key] = $value;
    }
}

switch ($Action) {

    case "login":

        $username = addslashes($_REQUEST['username']);
        $password = md5($_REQUEST['password']);

        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND active = '1'";
        if ($DB->querynum($query)) {
            $userData = $DB->fetch();
            $ret['result'] = "OK";

            session_start();
            $ret['session_id'] = session_id();

            $query = "SELECT * FROM sessions WHERE php_session_id = '{$ret['session_id']}'";
            if (!$DB->querynum($query)) {
                $data = array();
                $data['user_id'] = $userData['id'];
                $data['php_session_id'] = $ret['session_id'];
                $DB->queryinsert("sessions", $data);
            }
        }
        else {
            $ret['result'] = "ERR";
            $ret['error'] = "Wrong username or password";
        }

        break;

    case "submit":

        $session_id = addslashes($_REQUEST['session_id']);
        $type = addslashes($_REQUEST['type']);
        $manual_data = addslashes($_REQUEST['data']);
        $hash = addslashes($_REQUEST['hash']);
        $sentences = addslashes($_REQUEST['sentences']);

        $data = array();
        $allOk = true;

        if (!$manual_data) {
            $allOk = false;
            $ret['result'] = "ERR";
            $ret['error'] = "Missing data";
        }

        if (!$hash) {
            $allOk = false;
            $ret['result'] = "ERR";
            $ret['error'] = "Missing hash";
        }

        if (!$sentences) {
            $allOk = false;
            $ret['result'] = "ERR";
            $ret['error'] = "Missing sentences";
        }

        $parts = explode(",", $sentences);
        $parts = array_map("trim", $parts);
        foreach ($parts as $part) {
            if (!preg_match("/[0-9]+/", $part)) {
                $allOk = false;
                $ret['result'] = "ERR";
                $ret['error'] = "Invalid sentence number {$part}";
                break;
            }
        }

        if ($session_id) {
            $query = "SELECT * FROM sessions WHERE php_session_id = '{$session_id}'";
            if ($DB->querynum($query)) {
                $sessionData = $DB->fetch();
                $data['session_id'] = $sessionData['id'];
                $data['user_id'] = $sessionData['user_id'];
            }
            else {
                $allOk = false;
                $ret['result'] = "ERR";
                $ret['error'] = "Invalid session ID";
            }
        }

        $json = json_encode($manual_data, true);
        if (!is_array($json)) {
            $allOk = false;
            $ret['result'] = "ERR";
            $ret['error'] = "Invalid JSON data";
        }

        if ($allOk) {
            $data['data_type'] = $type;
            $data['manual_data'] = $manual_data;
            $data['hash'] = $hash;
            $data['sentences'] = $sentences;

            if ($DB->queryinsert("manual_data", $data)) {
                $ret['result'] = "OK";
            }
            else {
                $ret['result'] = "ERR";
                $ret['error'] = $DB->get_error();
            }
        }

        break;

}

echo json_encode($ret, JSON_PRETTY_PRINT);
