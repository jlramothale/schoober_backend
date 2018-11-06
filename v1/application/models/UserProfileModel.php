<?php

/**
* Created by PhpStorm.
 * User: jramothale
* Date: 2018/10/31
* Time: 12:27 PM
*
 * UserProfileModel: Users Profile database model class
 */
final class UserProfileModel extends Model {

    /**
     * Default contructor
     * @param Database $cnx - The database connection object
     */
    public function __construct($cnx) {
        parent::__construct($cnx, "user_profile");
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
}
