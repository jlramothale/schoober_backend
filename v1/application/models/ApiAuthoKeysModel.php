<?php

/**
 * Created by PhpStorm.
 * User: jramothale
 * Date: 2018/10/31
 * Time: 12:27 PM
 *
 * ApiAuthoKeysModel: Authentication Keys Database Model class
 */
final class ApiAuthoKeysModel extends Model
{
    /**
     * Default contructor
     * @param Database $cnx - The database connection object
     */
    public function __construct($cnx) {
        parent::__construct($cnx, "api_autho_keys");
    }

    /**
     * getByAuthoKey - get record by authentication key
     * @param $autho_key - autho key
     * @return null - BS Object is true else null
     */
    public function getByAuthoKey($autho_key) {
        if (!is_null($autho_key)) {
            $this->cnx->prepareStatement("SELECT * FROM $this->table WHERE autho_key = :autho_key");
            $this->cnx->execute(["autho_key" => $autho_key]);
            return $this->cnx->fetch();
        }
        return null;
    }

    /**
     * generateAuthoKey - Generates and returns API authentication key
     * @return string - API autho key
     */
    public function generateAuthoKey(){
        $count = count($this->get()) + 1;
        $autho_key = date("y") . Utils::randomNumber(0, 9)
            . date("m") . Utils::randomNumber(0, 9)
            . date("d") . Utils::randomNumber(0, 9)
            . $count;
        $diff = 15 - (int) strlen($autho_key);
        for ($i = 0; $i < $diff; $i++) {
            $autho_key .= "0";
        }
        return $autho_key;
    }


}