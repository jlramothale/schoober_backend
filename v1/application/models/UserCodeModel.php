<?php

/**
 * Created by PhpStorm.
 * User: jramothale
 * Date: 2018/11/05
 * Time: 10:02 PM
 */
final class UserCodeModel extends Model
{
    /**
     * Default contructor
     * @param Database $cnx - The database connection object
     */
    public function __construct($cnx) {
        parent::__construct($cnx, "user_code");
    }

    /**
     * getByUserId - Get user by user id
     * @param string $user_id - User ID
     * @return mix - User object or null
     */
    public function getByUserId($user_id) {
        if (!is_null($user_id)) {
            $this->cnx->prepareStatement("SELECT * FROM $this->table WHERE user_id = :user_id AND is_confirmed = :is_confirmed ORDER BY id DESC LIMIT 1");
            $this->cnx->execute(["user_id" => $user_id, "is_confirmed" => 0]);
            return $this->cnx->fetch();
        }
        return null;
    }

    /**
     * confirmCode - Confirms a code for a user
     * @param int $user_id - user number
     * @param string $code - cofirmation code
     * @return boolean - true is confirmed, else false
     */
    public function confirmCode($user_id, $code) {
        if (!isset($user_id, $code)) {
            return false;
        }
        $objCode = $this->getByUserId($user_id);
        if (!is_null($objCode) && (bool) $objCode->is_confirmed) {
            return false;
        }
        if (!is_null($objCode) && (bool) $objCode->is_expired) {
            return false;
        }
        if (!is_null($objCode) && $objCode->code === $code) {
            $this->updateCode($objCode->id);
            return true;
        }
    }

    /**
     * updateCode - updates a confirmation code confirmed boolean flag
     * @param int $pk - primary key
     */
    private function updateCode($pk) {
        if (isset($pk)) {
            $this->cnx->prepareStatement("UPDATE $this->table SET is_expired = :is_expired, is_confirmed = :is_confirmed, date_confirmed = :date_confirmed WHERE {$this->fields["pk"]} = :{$this->fields["pk"]}");
            $this->cnx->execute([
                "is_expired" => 1,
                "is_confirmed" => 1,
                "date_confirmed" => Utils::getDateTime(),
                "{$this->fields["pk"]}" => $pk
            ]);
        }
    }
}