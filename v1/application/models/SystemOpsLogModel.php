<?php

/**
 * Description of DefaultPackagesModel
 *
 * @author root1
 */
final class SystemOpsLogModel extends Model {

    /**
     * Default contructor
     * @param Database $cnx - The database connection object
     */
    public function __construct($cnx) {
        parent::__construct($cnx, "sys_ops_log");
    }

    public function getByName($_name) {
        if (!is_null($_name)) {
            $this->cnx->prepareStatement("SELECT * FROM $this->table WHERE _name = :_name");
            $this->cnx->execute(["_name" => $_name]);
            return $this->cnx->fetch();
        }
        return null;
    }

}
