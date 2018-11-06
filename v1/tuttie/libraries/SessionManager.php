<?php

/**
 * Description of SecureSessionHandler
 *
 * @author Johannes Ramothale <jramothale@iecon.co.za>
 * @since 05 Oct 2016, 7:05:58 AM
 */
final class SessionManager extends SessionHandler {

    public $open_ssl;

    public function __construct() {
        $this->open_ssl = new OpenSSL("myadminpal_session_key");
        $this->setup();
    }

    private function setup() {
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        session_name("myadminpal_session");
        session_set_cookie_params(0, ini_get('session.cookie_path'), ini_get('session.cookie_domain'), isset($_SERVER['HTTPS']), true);
    }

    public function start() {
        session_start();
    }

    protected function refresh() {
        return session_regenerate_id(true);
    }

    public function read($id) {
        $data = parent::read($id);
        if (!$data) {
            return "";
        } else {
            return $this->open_ssl->decrypt($data);
        }
    }

    public function write($id, $data) {
        $data = $this->open_ssl->encrypt($data);
        return parent::write($id, $data);
    }

    protected function isExpired($ttl = 30) {
        $last = isset($_SESSION['_last_activity']) ? $_SESSION['_last_activity'] : false;

        if ($last !== false && time() - $last > $ttl * 60) {
            return true;
        }

        $_SESSION['_last_activity'] = time();

        return false;
    }

    protected function isValid() {
        return !$this->isExpired() && $this->isFingerprint();
    }

    public static function forget() {
        if (session_id() === '') {
            return false;
        }
        $_SESSION = [];
        setcookie("myadminpal_session", '', time() - 42000, ini_get('session.cookie_path'), ini_get('session.cookie_domain'), isset($_SERVER['HTTPS']), true);
        return session_destroy();
    }

    public static function isFingerprint() {
        $hash = md5(
                $_SERVER['HTTP_USER_AGENT'] .
                (ip2long($_SERVER['REMOTE_ADDR']) & ip2long('255.255.0.0'))
        );
        if (isset($_SESSION['_fingerprint'])) {
            return $_SESSION['_fingerprint'] === $hash;
        }
        $_SESSION['_fingerprint'] = $hash;
        return true;
    }

    public static function get($name) {
        $parsed = explode('.', $name);
        $result = $_SESSION;
        while ($parsed) {
            $next = array_shift($parsed);
            if (isset($result[$next])) {
                $result = $result[$next];
            } else {
                return null;
            }
        }
        return $result;
    }

    public static function put($name, $value) {
        $parsed = explode('.', $name);
        $session = & $_SESSION;
        while (count($parsed) > 1) {
            $next = array_shift($parsed);
            if (!isset($session[$next]) || !is_array($session[$next])) {
                $session[$next] = [];
            }
            $session = & $session[$next];
        }
        $session[array_shift($parsed)] = $value;
    }

    public static function isLogged() {
        if (self::get("acc_id") !== null &&
                self::get("bus_name") !== null &&
                self::get("bus_logo") !== null &&
                self::get("user_id") !== null &&
                self::get("user_type") !== null &&
                self::get("user_name") !== null &&
                self::get("first_name") !== null &&
                self::get("last_name") !== null &&
                self::get("access_levels") !== null) {
            return true;
        }
        return false;
    }

}
