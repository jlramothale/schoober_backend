<?php

/**
 * Created by PhpStorm.
 * User: jramothale
 * Date: 2018/10/31
 * Time: 12:27 PM
 *
 * UsersModel: Users database model class
 */

final class UsersModel extends Model {

    /**
     * Default contructor
     * @param Database $cnx - The database connection object
     */
    public function __construct($cnx) {
        parent::__construct($cnx, "users");
    }

    /**
     * getByUserId - Get user by user id
     * @param string $user_id - User ID
     * @return mix - User object or null
     */
    public function getByUserId($user_id) {
        if (!is_null($user_id)) {
            $this->cnx->prepareStatement("SELECT * FROM $this->table WHERE user_id = :user_id");
            $this->cnx->execute(["user_id" => $user_id]);
            return $this->cnx->fetch();
        }
        return null;
    }

    /**
     * getByEmail - Get user by email
     * @param string $email - User email
     * @return mix - User object or null
     */
    public function getByEmail($email) {
        if (!is_null($email)) {
            $this->cnx->prepareStatement("SELECT * FROM $this->table WHERE email = :email");
            $this->cnx->execute(["email" => $email]);
            return $this->cnx->fetch();
        }
        return null;
    }

    /**
     * generateUserId - Generate and return user id
     * @return string - user id
     */
    public function generateUserId() {
        $count = count($this->get()) + 1;
        $user_id = date("y") . Utils::randomNumber(0, 9)
                . date("m") . Utils::randomNumber(0, 9)
                . date("d") . Utils::randomNumber(0, 9)
                . $count;
        $diff = 15 - (int) strlen($user_id);
        for ($i = 0; $i < $diff; $i++) {
            $user_id .= "0";
        }
        return $user_id;
    }

}
