<?php

/**
 * Description of IndexController
 * @author Johannes Ramothale <jramothale@iecon.co.za>
 * @since 09 Jan 2017, 7:07:01 PM
 */
final class IndexController extends Controller {

    function __construct() {
        parent::__construct(DATABASE);
        $this->tokenKey = "index_key";
    }

    public function index() {
        $root_url = "".ROOT_URL."";
        $html = "<h1>Schoober API. v1.0</h1>";
        $html .= "<p>Define below are the available REST API routes for version 1.0</p>";
        $html .= "<h4>Routes</h4>";
        $html .= "<p>
            {$root_url}users/register<br/>
            {$root_url}users/completeRegistration<br/>
            {$root_url}users/login<br/>
            {$root_url}users/updateUserProfile<br/>
            {$root_url}users/resetPasswordCode<br/>
            {$root_url}users/confirmResetCode<br/>
            {$root_url}users/resetPassword
        </p>";
        echo $html;
    }

}
