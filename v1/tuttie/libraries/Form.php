<?php

/**
 * Description of Form
 *
 * @author jramothale
 */
final class Form {

    /** @var array $postData - Stores the Form post data  */
    private $postData = array();

    /** @var array $getData - Stores URL get data */
    private $getData = array();

    /** @var string $currentItem - The current posted field name */
    private $currentItem = null;

    /** @var object $validator - The validator object */
    private $validator = null;

    /** @var array $errors - The formm validation errors */
    private $errors = array();

    /**
     * The default contructor
     */
    public function __construct() {
        $this->validator = new Validator();
    }

    /**
     * post - The function that runs the $_POST method
     *
     * @param string $field - The HTML field name to post
     * @return \Form
     */
    public function post($field) {
        $this->postData[$field] = stripcslashes(trim(filter_input(INPUT_POST, $field)));
        $this->currentItem = $field;
        return $this;
    }

    /**
     * get - The function that runs the $_GET method
     *
     * @param string $field - The HTML field name to get
     * @return \Form
     */
    public function get($field) {
        $this->getData[$field] = stripcslashes(trim(filter_input(INPUT_GET, $field)));
        $this->currentItem = $field;
        return $this;
    }

    /**
     * fetchPost - Returns the posted data
     *
     * @param mixed $field - The field name to return
     * @return mixed
     */
    public function fetchPost($field = false) {
        if ($field) {
            if (isset($this->postData[$field])) {
                return $this->postData[$field];
            }
            return false;
        } else {
            return $this->postData;
        }
    }

    /**
     * fetchGet - Returns the posted data
     *
     * @param mixed $field - The field name to return
     * @return mixed
     */
    public function fetchGet($field = false) {
        if ($field) {
            if (isset($this->getData[$field])) {
                return $this->getData[$field];
            }
            return false;
        } else {
            return $this->getData;
        }
    }

    /**
     * val - Validates form input
     *
     * @param string $typeOfValidator - The validator type or function
     * @param mixed $argument - The argument for the validation to take place
     * @return \Form
     */
    public function val($typeOfValidator, $argument = null) {
        try {
            if (!is_null($argument)) {
                $this->validator->{$typeOfValidator}($this->postData[$this->currentItem], $argument);
            } else {
                $this->validator->{$typeOfValidator}($this->postData[$this->currentItem]);
            }
        } catch (Exception $ex) {
            $this->errors[$this->currentItem] = $ex->getMessage();
        }
        return $this;
    }

    /**
     * getError - Returns an array of errors or a single error value
     *
     * @param string $field - The field name for the error
     * @return mixed - Array or String
     */
    public function getError($field = null) {
        if ($field) {
            return $this->errors[$field];
        } else {
            return $this->errors;
        }
    }

}
