<?php

/**
 * Description of RestAPI
 *
 * @author jramothale
 */
class RestAPIController {

    /**
     * The database connection
     */
    protected $cnx;

    /**
     * Property: method
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     * GET - Used for basic read requests to the server
     * PUT- Used to modify an existing object on the server
     * POST- Used to create a new object on the server
     * DELETE - Used to remove an object on the server
     */
    protected $method;

    /**
     * Property: verb
     * An optional additional descriptor about the endpoint, used for things that can
     * not be handled by the basic methods. eg: /files/process
     */
    protected $verb;

    /**
     * Property: args
     * Any additional URI components after the endpoint and verb have been removed.
     * E.g, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1> (/api/users/1)
     * or /<endpoint>/<arg0>
     */
    protected $args = Array();

    /**
     * RestAPIController constructor.
     * @param null $dbconfig
     */
    public function __construct($dbconfig = null) {
        try {
            if (!is_null($dbconfig)) {
                $this->cnx = new Database($dbconfig);
            }
            $this->init();
        } catch (Exception $ex) {
            // log error
            //die($ex->getMessage());
        }
    }

    /**
     * init: Initialise the rest API
     * @throws Unexpcted Header Exception
     */
    private function init() {
        $this->args = explode('/', rtrim($_GET['url'], '/'));
        array_shift($this->args);
        $this->endpoint = array_shift($this->args);
        if (array_key_exists(0, $this->args) && !is_numeric($this->args[0])) {
            $this->verb = array_shift($this->args);
        }

        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }
    }

    /**
     * response: Process and submits a response to a request
     * @param $message - The return message
     * @param int $status - HTTP status
     * @return string - Json encoded string
     */
    protected function response($message, $code = 200) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Content-Type: application/json; charset=UTF-8");
//        header("Cache-Control: no-cache, must-revalidate");
//        header("HTTP/1.1 " . $code . " " . $this->responseStatusMessage($code));
        http_response_code($code);
        return json_encode(["message" => $message]);
    }

    /** responseStatusMessage: Get the correct response message based on the HTTP_CODE
     * @param $code - HTTP code
     * @return string - HTTP response message
     */
    protected function responseStatusMessage($code) {
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

}
