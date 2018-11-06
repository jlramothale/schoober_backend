<?php

/**
 * Description of Utils
 *
 * @author Johannes Ramothale <jramothale@iecon.co.za>
 * @since 05 Oct 2016, 7:05:58 AM
 */
final class Utils {

    /**
     * requestHeader: sets request header
     */
    public static function request(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: access");
    }

    /**
     * response: respond to a client request
     * @param $response
     * @param int $code
     * @return string
     */
    public static function response($response) {
        header("Content-Type: application/json; charset=UTF-8");
        header("Cache-Control: no-cache, must-revalidate");
//        header("HTTP/1.1 " . $code . " " . self::responseStatusMessage($code));
        return json_encode(["response" => $response]);
    }

    public static function responseStatusMessage($code){
        $status = [
            100 => 'Continue',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            503 => 'Service Unavailable'
        ];
        return ($status[$code]) ? $status[$code] : $status[500];
    }

    public static function randomNumber($min, $max) {
        return intval(mt_rand($min, $max));
    }

    public static function randomString($length = 10) {
        $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str = "";
        $max = strlen($keyspace) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $keyspace[intval(mt_rand(0.0, $max))];
        }
        return $str;
    }

    public static function getIpAddress() {
        $ip = getenv('HTTP_CLIENT_IP') ?:
            getenv('HTTP_X_FORWARDED_FOR') ?:
                getenv('HTTP_X_FORWARDED') ?:
                    getenv('HTTP_FORWARDED_FOR') ?:
                        getenv('HTTP_FORWARDED') ?:
                            getenv('REMOTE_ADDR');
        return $ip;
    }

    public static function getUserAccessInfo() {
        $user_ip = self::getIpAddress();
        $ch = curl_init("https://freegeoip.net/json/$user_ip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $json = json_decode($result, true);
        return $json;
    //        {"ip":"41.150.151.16","country_code":"ZA","country_name":"South Africa","region_code":"GT","region_name":"Gauteng","city":"Johannesburg","zip_code":"1852","time_zone":"Africa/Johannesburg","latitude":-26.2309,"longitude":28.0583,"metro_code":0} ,,
    }

    public static function getUserLocation() {
        $json = self::getUserAccessInfo();
//        return "{$json["country_name"]},{$json["region_name"]},{$json["city"]},{$json["zip_code"]}";
        return "Johannesburg";
    }

    public static function getUserAgent($hashed = null) {
        if ($hashed) {
            return hash("sha256", $_SERVER["HTTP_USER_AGENT"] . rand(0, 999999));
        } else {
            return $_SERVER["HTTP_USER_AGENT"];
        }
    }

    public static function getTime($datetime = null) {
        if ($datetime) {
            $date = new DateTime($datetime);
            return $date->format("H:i:s");
        } else {
            $now = new DateTime();
            return $now->format("H:i:s");
        }
    }

    public static function getDate($datetime = null) {
        if ($datetime) {
            $date = new DateTime($datetime);
            return $date->format("Y-m-d");
        } else {
            $now = new DateTime();
            return $now->format("Y-m-d");
        }
    }

    public static function getDateTime($datetime = null) {
        if ($datetime) {
            $date = new DateTime($datetime);
            return $date->format("Y-m-d H:i:s");
        } else {
            $now = new DateTime();
            return $now->format("Y-m-d H:i:s");
        }
    }

    public static function formatDate($datetime) {
        $date = new DateTime($datetime);
        return $date->format("d M, Y");
    }

    public static function formatDateTime($datetime) {
        $date = new DateTime($datetime);
        return $date->format("d M, Y") . " at " . $date->format("H:i:s");
    }

    public static function incrementDate($increment, $date = null) {
        if (!$date) {
            $date = new DateTime(self::getDate());
        } else {
            $date = new DateTime($date);
        }
        $date->modify($increment);
        return $date->format("Y-m-d");
    }

    public static function incrementDateTime($increment, $date = null) {
        if (!$date) {
            $date = new DateTime(self::getDateTime());
        } else {
            $date = new DateTime($date);
        }
        $date->modify($increment);
        return $date->format("Y-m-d H:i:s");
    }

    public static function userLogEntry($id, $operation, $connection) {
        $model = new UserLogModel($connection);
        $model->insert([
            "user_id" => $id,
            "ip_address" => self::getIpAddress(),
            "device" => self::getUserAgent(),
            "date_entry" => self::getDateTime(),
            "operation" => $operation,
            "location" => self::getUserLocation()
        ]);
    }

    public static function systemLogEntry($operation, $connection) {
        $model = new SystemLogModel($connection);
        $model->insert([
            "ip_address" => self::getIpAddress(),
            "device" => self::getUserAgent(),
            "date_entry" => self::getDateTime(),
            "operation" => $operation,
            "location" => self::getUserLocation()
        ]);
    }

    public static function objectsToArray($objects) {
        $array = [];
        if ($objects) {
            foreach ($objects as $object) {
                $array[] = $object;
            }
        }
        return $array;
    }

    public static function makeDirectory($path, $mode = 0777) {
        return mkdir($path, $mode);
    }

    public static function changeDirectoryPermissions($path, $mode = 0777) {
        return chmod($path, $mode);
    }

}
