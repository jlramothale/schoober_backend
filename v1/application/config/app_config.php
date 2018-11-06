<?php

date_default_timezone_set('Africa/Johannesburg');
define("DEVELOPMENT", true);
define("MAILER", true);
define("APP_NAME", "Schoober");
define("SUPPORT_EMAIL", "support@schoober.com");
define('DATABASE', DEVELOPMENT ? "schoober_db" : "schoober_db");
define('ROOT_URL', DEVELOPMENT ? "http://www.schoober.local" . DS : "https://www.schoober.com" . DS);
define('APPLICATION_URL', ROOT_URL . "application" . DS);
define('RESOURCES_URL', ROOT_URL . "resources" . DS);
define('UPLOADS_URL', ROOT_URL . "uploads" . DS);

if (DEVELOPMENT) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}


