<?php

/**
 * Description of Controller
 *
 * The Controller create the database connection, then pass it to the Model class.
 * The Controller has the Model and the View
 *
 * @author jramothale
 */
class Controller {

    /** @var object $mailer - The SwiftMailer class reference */
    protected $mailer;

    /** @var object $cnx - The database connection reference */
    protected $cnx;

    /**
     * RestAPIController constructor.
     * @param null $dbconfig
     */
    public function __construct($dbconfig = null) {
        $this->mailer = null;
        $this->tokenKey = "";
        try {
            if (!is_null($dbconfig)) {
                $this->cnx = new Database($dbconfig);
            }
            Utils::request();
        } catch (Exception $ex) {
            // log error
            //die($ex->getMessage());
        }
    }

    /**
     * authenticateRequest: Authenticate a request from a client (mobile app)
     * @param $autho_key - api autho key
     * @return bool - true if authentication is successful else false
     */
    protected function authenticateRequest($autho_key){
        $api_autho_key_model = new ApiAuthoKeysModel($this->cnx);
        $api_autho_key = $api_autho_key_model->getByAuthoKey($autho_key);
        if(!$api_autho_key){
            return false;
        }
        if($api_autho_key->autho_key !== $autho_key){
            return false;
        }
        return true;
    }

}
