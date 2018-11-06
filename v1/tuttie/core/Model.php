<?php

/**
 * Description of Model
 *
 * The model class get the data connection variable from the controller that uses it,
 * this ensures that, multiple Model classes can commit on the same database connection.
 *
 * @author Johannes Ramothale <jramothale@iecon.co.za>
 * @since 05 Oct 2016, 7:05:58 AM
 */
class Model {

    /** @var object $cnx - The connection variable */
    protected $cnx;

    /** @var string $table - The database table name */
    protected $table;

    /** @var array $fields - Table fields */
    protected $fields;

    /**
     * Default constructor
     * @param Object $cnx - Database connection
     * @param string $table - Model table name
     */
    public function __construct($cnx = null, $table = "") {
        if (!is_null($cnx)) {
            $this->cnx = $cnx;
            $this->table = $table;
            $this->setFields();
        }
    }

    private function setFields() {
        $sql = "DESC " . $this->table;
        $this->cnx->prepareStatement($sql);
        $this->cnx->execute();
        $result = $this->cnx->fetchAll();
        foreach ($result as $value) {
            if ($value->Key == 'PRI') {
                $this->fields['pk'] = $value->Field;
            }
            $this->fields[] = $value->Field;
        }
    }

    /**
     * get - Get all records or by PK
     *
     * @param int $pk - Primary key
     * @return array - A 2D associative array containing records
     */
    public function get($pk = null) {
        if (!is_null($pk)) {
            $this->cnx->prepareStatement("SELECT * FROM $this->table WHERE {$this->fields['pk']} = :{$this->fields['pk']}");
            $this->cnx->execute(["{$this->fields['pk']}" => $pk]);
            return $this->cnx->fetch();
        } else {
            $this->cnx->prepareStatement("SELECT * FROM $this->table");
            $this->cnx->execute();
            return $this->cnx->fetchAll();
        }
    }

    /**
     * insert - Insert a new record
     * @param array $data - Associative array containing information to insert
     */
    public function insert($data) {
        $fnames = implode(",", array_keys($data));
        $fvalues = ":" . implode(",:", array_keys($data));
        $this->cnx->prepareStatement("INSERT INTO $this->table ($fnames) VALUES ($fvalues)");
        $this->cnx->execute($data);
    }

    /**
     * lastInsertId - Returns last insert statement id
     * @return int - id
     */
    public function lastInsertId() {
        return $this->cnx->lastInsertId();
    }

    /**
     * update - Update records
     * @param array $data - Associative array containing information to update
     * @return int - Return the count of affected rows
     */
    public function update($data) {
        $uplist = "";
        $where = "";
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fields)) {
                if ($key === $this->fields['pk']) {
                    $where = "$key = :$key";
                } else {
                    $uplist .= "$key = :$key,";
                }
            }
        }
        $uplist = rtrim($uplist, ',');
        $query = "UPDATE $this->table SET $uplist WHERE $where";
        $this->cnx->prepareStatement($query);
        $this->cnx->execute($data);
        return $this->cnx->rowCount();
    }

    /**
     * delete - Delete records
     * @param int $pk - Primary key where to delete
     * @return int - Return the count of deleted records
     */
    public function delete($pk) {
        $query = "DELETE FROM $this->table WHERE {$this->fields['pk']} = :{$this->fields['pk']}";
        $this->cnx->prepareStatement($query);
        $this->cnx->execute(["{$this->fields['pk']}" => $pk]);
        return $this->cnx->rowCount();
    }

    /**
     * hide - Hide records by setting the deleted field to 1
     * @param int $pk - Primary key where to hide
     * @return mixed - Return the count of affected records else null
     */
    public function hide($pk) {
        if (isset($pk)) {
            $query = "UPDATE $this->table SET deleted = :deleted WHERE {$this->fields['pk']} = :{$this->fields['pk']}";
            $this->cnx->prepareStatement($query);
            $this->cnx->execute(["deleted" => 1, "{$this->fields['pk']}" => $pk]);
            return $this->cnx->rowCount();
        }
        return null;
    }

    /**
     * retrieve - Unhide/Retrieve hiden records
     * @param int $pk - Primary key where to retrieve
     * @return mixed - Return the count of affected records else null
     */
    public function retrieve($pk) {
        if (isset($pk)) {
            $query = "UPDATE $this->table SET deleted = :deleted WHERE {$this->fields['pk']} = :{$this->fields['pk']}";
            $this->cnx->prepareStatement($query);
            $this->cnx->execute(["deleted" => 0, "{$this->fields['pk']}" => $pk]);
            return $this->cnx->rowCount();
        }
        return null;
    }

}
