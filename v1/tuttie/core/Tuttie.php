<?php

/**
 * Description of Tutti
 *
 * @author jramothale
 */
final class Tuttie {

    /** @var string $url - Stores the request URL */
    private $url;

    /** @var array $args - Split the URL into an arguments array */
    private $args;

    /** @var string $action - Stores the function name controller */
    private $action;

    /** @var string $controllerName - Store the controller name */
    private $controllerName;

    /**
     * Default contructor - Init $url and $args fields
     */
    public function __construct() {
        if (isset($_GET['url'])) {
            $this->url = rtrim($_GET['url'], '/');
        } else {
            $this->url = "index";
        }
        $this->args = explode('/', $this->url);
    }

    /**
     * run - Runs the application
     */
    public function run() {
        $this->init();
        $this->autoLoad();
        $this->setControllerName();
        $this->setAction();
        $this->dispatch();
    }

    /**
     * init - Init contants
     */
    private function init() {
        define("DS", DIRECTORY_SEPARATOR);
        define("ROOT_PATH", getcwd() . DS);
        define("PHP_EXT", ".php");
        define("APP_PATH", ROOT_PATH . 'application' . DS);
        define("FRAMEWORK_PATH", ROOT_PATH . "tuttie" . DS);
        define("RESOURCES_PATH", ROOT_PATH . "resources" . DS);
        define("UPLOADS_PATH", ROOT_PATH . "uploads" . DS);
        define("SESSIONS_PATH", ROOT_PATH . "sessions" . DS);

        define("APP_CONFIG_PATH", APP_PATH . "config" . DS);
        define("CONTROLLER_PATH", APP_PATH . "controllers" . DS);
        define("MODEL_PATH", APP_PATH . "models" . DS);
        define("VIEW_PATH", APP_PATH . "views" . DS);
        define("SERVICES_PATH", APP_PATH . "services" . DS);

        define("CORE_PATH", FRAMEWORK_PATH . "core" . DS);
        define("LIBS_PATH", FRAMEWORK_PATH . "libraries" . DS);
    }

    /**
     * autoLoad - Dynamic loads classes with spl_autoload
     */
    private function autoLoad() {
        if (file_exists(APP_CONFIG_PATH . "app_config" . PHP_EXT)) {
            require_once APP_CONFIG_PATH . "app_config" . PHP_EXT;
        }
        spl_autoload_register(function ($class) {
            // auto loads application classes
            if (file_exists(APP_CONFIG_PATH . $class . PHP_EXT)) {
                require_once APP_CONFIG_PATH . $class . PHP_EXT;
            }
            if (file_exists(CONTROLLER_PATH . $class . PHP_EXT)) {
                require_once CONTROLLER_PATH . $class . PHP_EXT;
            }
            if (file_exists(MODEL_PATH . $class . PHP_EXT)) {
                require_once MODEL_PATH . $class . PHP_EXT;
            }
            if (file_exists(SERVICES_PATH . $class . PHP_EXT)) {
                require_once SERVICES_PATH . $class . PHP_EXT;
            }
            // auto loads the freamework
            if (file_exists(CORE_PATH . $class . PHP_EXT)) {
                require_once CORE_PATH . $class . PHP_EXT;
            }
            if (file_exists(LIBS_PATH . $class . PHP_EXT)) {
                require_once LIBS_PATH . $class . PHP_EXT;
            }
        });
    }

    /**
     * dispatch - Maps a logical URL to its view file, and dispatch the URL through the controller
     */
    private function dispatch() {
        $controller_file = CONTROLLER_PATH . $this->controllerName . PHP_EXT;
        if (file_exists($controller_file)) {
            require $controller_file;
            $controller = new $this->controllerName;
            if (method_exists($controller, $this->action)) {
                $controller->{$this->action}($this->args);
            } else {
                // handle the controller does not exist: 404
                header("Location: " . ROOT_URL . "error/e404");
            }
        } else {
            // handle the controller does not exist: 404
        }
    }

    /**
     * Set the value of controller using currently parsed route
     */
    private function setControllerName() {
        $this->controllerName = ucfirst(array_shift($this->args)) . "Controller";
    }

    /**
     * Set name of method to execute from Controller
     */
    private function setAction() {
        $action = array_shift($this->args);
        if ($action === null) {
            $action = "index";
        }
        $this->action = $action;
    }

}
