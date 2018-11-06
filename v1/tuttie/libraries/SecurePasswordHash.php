<?php

/**
 * Description of SecurePasswordHash
 *
 * @author Johannes Ramothale <jramothale@iecon.co.za>
 * @since 05 Oct 2016, 7:05:58 AM
 */
define("PBKDF2_HASH_ALGORITHM", "sha256");
define("PBKDF2_ITERATIONS", 1000);
define("PBKDF2_SALT_BYTE_SIZE", 64);
define("PBKDF2_HASH_BYTE_SIZE", 64);

define("HASH_SECTIONS", 4);
define("HASH_ALGORITHM_INDEX", 0);
define("HASH_ITERATION_INDEX", 1);
define("HASH_SALT_INDEX", 2);
define("HASH_PBKDF2_INDEX", 3);

final class SecurePasswordHash {

    public function __construct() {

    }

    public function hash($password) {
        $options = [
            "cost" => 10,
            "salt" => hash("sha256", $password . rand(0, 999999))
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    public function verify($password, $hash) {
        return password_verify($password, $hash);
    }

    public function rehash($password, $hash) {
        if (password_needs_rehash($hash, PASSWORD_BCRYPT)) {
            return $this->hash($password);
        }
    }

}
