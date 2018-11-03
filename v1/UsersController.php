<?php

/**
 * Description of StaffController
 *
 * @author johannes
 */
final class UsersController extends Controller {

    function __construct() {
        parent::__construct(DATABASE);
    }

    public function index(){

    }

    public function register($data) {
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

    public function resetPasswordLink(){
        if(!$this->authenticateRequest($_GET["api_key"])){
            Utils::systemLogEntry("Invalid API Key: {$_POST["api_key"]}", $this->cnx);
            echo Utils::response([
                "message" => "Invalid API Key: ". $_POST["api_key"],
                "status" => "501"
            ]);
        } else {
            $user_service = new UserService($this->cnx);
            echo $user_service->resetPasswordLink([
                "email" => $_POST["email"],
            ]);
        }
    }

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

}
