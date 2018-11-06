<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class OpenSSL {

    protected $method;
    protected $key;

    public function __construct($key = "encrypt_p@55W0rd") {
        $this->method = "AES-256-CBC";
        $this->key = hash("sha256", $key);
    }

    public function encrypt($data) {
        $ivSize = openssl_cipher_iv_length($this->method);
        $iv = openssl_random_pseudo_bytes($ivSize);
        $encrypted = openssl_encrypt($data, $this->method, $this->key, OPENSSL_RAW_DATA, $iv);
        $encrypted = base64_encode($iv . $encrypted);
        return $encrypted;
    }

    public function decrypt($data) {
        $data = base64_decode($data);
        $ivSize = openssl_cipher_iv_length($this->method);
        $iv = substr($data, 0, $ivSize);
        $data = openssl_decrypt(substr($data, $ivSize), $this->method, $this->key, OPENSSL_RAW_DATA, $iv);
        return $data;
    }

}
