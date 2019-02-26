<?php

/**
 * Created by PhpStorm.
 * User: johannes
 * Date: 2019/02/26
 * Time: 8:56 AM
 */
class TestController extends Controller {

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

    public function getList(){
        $test_model = new TestTableModel($this->cnx);
        echo Utils::response($test_model->get());
    }

    public function getTest(){
        $test_model = new TestTableModel($this->cnx);
        echo Utils::response($test_model->get($_POST["id"]));
    }

    public function insertTest(){
        $test_model = new TestTableModel($this->cnx);
        $test_model->insert([
            "first_name" => $_POST["first_name"],
            "last_name" => $_POST["last_name"],
        ]);
        echo Utils::response("Test insert successful");
    }

}