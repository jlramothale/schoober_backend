<?php

/**
 * Description of UsersController
 *
 * This is the Users REST API Controller
 *
 * @author johannes
 */
final class UsersController extends Controller {

    /**
     * UsersController constructor.
     */
    function __construct() {
        parent::__construct(DATABASE);
    }

    /**
     * default index function to access /users endpoint
     */
    public function index(){
        echo Utils::response([
            "message" => "Invalid API endpoint, check your API documentation for reference.",
            "status" => "501"
        ]);
    }

    /**
     * register - register a new user.
     */
    public function register() {
        $user_service = new UserService($this->cnx);
        echo $user_service->registerUser([
            "device_id" => $_POST["device_id"],
            "user_type" => $_POST["user_type"],
            "first_name" => $_POST["first_name"],
            "last_name" => $_POST["last_name"],
            "email" => $_POST["email"],
            "password" => $_POST["password"],
        ]);
    }

    /**
     * completeRegistration - Completes user registration:
     * This function is typical for collecting more client data during registration
     */
    public function completeRegistration(){
        if(!$this->authenticateRequest($_GET["api_key"])){
            Utils::systemLogEntry("Invalid API Key: {$_POST["api_key"]}", $this->cnx);
            echo Utils::response([
                "message" => "Invalid API Key: ". $_POST["api_key"],
                "status" => "501"
            ]);
        } else {
            $user_service = new UserService($this->cnx);
            echo $user_service->completeRegistration([
                "user_id" => $_POST["user_id"],
                "gender" => $_POST["gender"],
                "cell_number" => $_POST["cell_number"],
                "street_address" => $_POST["street_address"],
                "town" => $_POST["town"],
                "province" => $_POST["province"],
                "code" => $_POST["code"],
            ]);
        }
    }

    /**
     * login - sign-in a user into the app.
     */
    public function login() {
        if(!$this->authenticateRequest($_GET["api_key"])){
            Utils::systemLogEntry("Invalid API Key: {$_POST["api_key"]}", $this->cnx);
            echo Utils::response([
                "message" => "Invalid API Key: ". $_POST["api_key"],
                "status" => "501"
            ]);
        } else {
            $user_service = new UserService($this->cnx);
            echo $user_service->loginUser([
                "email" => $_POST["email"],
                "password" => $_POST["password"],
            ]);
        }
    }

    /**
     * updateUserProfile - updates user profile information within the application
     */
    public function updateUserProfile(){
        if(!$this->authenticateRequest($_GET["api_key"])){
            Utils::systemLogEntry("Invalid API Key: {$_POST["api_key"]}", $this->cnx);
            echo Utils::response([
                "message" => "Invalid API Key: ". $_POST["api_key"],
                "status" => "501"
            ]);
        } else {
            $user_service = new UserService($this->cnx);
            echo $user_service->updateUserProfile([
                "user_id" => $_POST["user_id"],
                "first_name" => $_POST["first_name"],
                "last_name" => $_POST["last_name"],
                "gender" => $_POST["gender"],
                "cell_number" => $_POST["cell_number"],
                "street_address" => $_POST["street_address"],
                "town" => $_POST["town"],
                "province" => $_POST["province"],
                "code" => $_POST["code"],
            ]);
        }
    }

    /**
     * resetPasswordCode - Retrieves a code to confirm the password reset process
     */
    public function resetPasswordCode(){
        if(!$this->authenticateRequest($_GET["api_key"])){
            Utils::systemLogEntry("Invalid API Key: {$_POST["api_key"]}", $this->cnx);
            echo Utils::response([
                "message" => "Invalid API Key: ". $_POST["api_key"],
                "status" => "501"
            ]);
        } else {
            $user_service = new UserService($this->cnx);
            echo $user_service->resetPasswordCode([
                "email" => $_POST["email"],
            ]);
        }
    }

    /**
     * confirmResetCode - confirms the reset password code
     */
    public function confirmResetCode(){
        if(!$this->authenticateRequest($_GET["api_key"])){
            Utils::systemLogEntry("Invalid API Key: {$_POST["api_key"]}", $this->cnx);
            echo Utils::response([
                "message" => "Invalid API Key: ". $_POST["api_key"],
                "status" => "501"
            ]);
        } else {
            $user_service = new UserService($this->cnx);
            echo $user_service->confirmResetCode([
                "user_id" => $_POST["user_id"],
                "code" => $_POST["code"],
            ]);
        }
    }

    /**
     * resetPassword - final action to reset a user password
     */
    public function resetPassword(){
        if(!$this->authenticateRequest($_GET["api_key"])){
            Utils::systemLogEntry("Invalid API Key: {$_GET["api_key"]}", $this->cnx);
            echo Utils::response([
                "message" => "Invalid API Key: ". $_GET["api_key"],
                "status" => "501"
            ]);
        } else {
            $user_service = new UserService($this->cnx);
            echo $user_service->resetPassword([
                "user_id" => $_POST["user_id"],
                "password" => $_POST["password"],
            ]);
        }
    }

}
