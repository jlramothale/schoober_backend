<?php

/**
 * Created by PhpStorm.
 * User: jramothale
 * Date: 2018/10/31
 * Time: 7:18 PM
 *
 * SystemLogModel: System log database model class
 */
final class SystemLogModel extends  Model {
    /**
     * Default contructor
     * @param Database $cnx - The database connection object
     */
    public function __construct($cnx) {
        parent::__construct($cnx, "sys_log");
    }
}