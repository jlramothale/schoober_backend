<?php

/**
 * Description of Validator
 *
 * @author jramothale
 */
final class Validator {

    /**
     * Default constructor to create the object
     */
    public function __construct() {

    }

    /**
     * minLength - Check if min length is within range
     *
     * @param string $data - String to validate
     * @param int $arg - Argument
     * @return boolean - True if within range
     * @throws LengthException
     */
    public function minLength($data, $arg) {
        if (strlen($data) < $arg) {
            throw new LengthException("Field out of bounds: Your string must be atleast $arg characters long");
        }
        return true;
    }

    /**
     * maxLength - Check if max length is within range
     *
     * @param string $data - String to validate
     * @param int $arg - Argument
     * @return boolean - True if within range
     * @throws LengthException
     */
    public function maxLength($data, $arg) {
        if (strlen($data) > $arg) {
            throw new LengthException("Field out of bounds: Your string must be atmost $arg characters long");
        }
        return true;
    }

    /**
     * lengthRange - Check string length range
     *
     * @param string $data - String to validate
     * @param array $arg - Arguments aray
     * @return boolean - True if within range
     * @throws RangeException
     */
    public function lengthRange($data, $arg) {
        $length = strlen($data);
        if ($length >= $arg[0] && $length <= $arg[1]) {
            return true;
        }
        throw new RangeException("Field out of range: $data should be between $arg[0] and $arg[1] in length");
    }

    /**
     * minValue - Check if value is within range
     *
     * @param mixed $data - Value to validate
     * @param int $arg - Argument
     * @return boolean - True if within range
     * @throws RangeException
     */
    public function minValue($data, $arg) {
        $this->digit($data);
        $this->digit($arg);
        if ($data > $arg) {
            return true;
        }
        throw new RangeException("Field value does not meet the required minimum value of $arg");
    }

    /**
     * maxValue - Check if value is within range
     *
     * @param mixed $data - Value to validate
     * @param int $arg - Argument
     * @return boolean - True if within range
     * @throws RangeException
     */
    public function maxValue($data, $arg) {
        $this->digit($data);
        $this->digit($arg);
        if ($data < $arg) {
            return true;
        }
        throw new RangeException("Field value exceeds the maximum allowed value of $arg");
    }

    /**
     * valueRange - Validates if a value  is within range
     *
     * @param mixed $data - Value to validate
     * @param array $arg - Arguments array
     * @return boolean - True if within range
     * @throws RangeException
     */
    public function valueRange($data, $arg) {
        $this->digit($data);
        $this->digit($arg[0]);
        $this->digit($arg[1]);
        if ($data >= $arg[0] && $data <= $arg[1]) {
            return true;
        }
        throw new RangeException("Value out of range: $data should between $arg[0] and $arg[1]");
    }

    /**
     * digit - Check if an passed string is a valid digit
     *
     * @param string $data - Input digit string
     * @return boolean true if valid else false
     */
    public function digit($data) {
        $int = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        if (ctype_digit($int) && is_numeric($int)) {
            return true;
        }
        throw new InvalidArgumentException("Invalid digit argument: $data is not a digit");
    }

    /**
     * email - Checks if an input is a valid email address
     *
     * @param string $data The passed email addres
     * @return boolean true if valid else false
     */
    public function email($data) {
        $email = filter_var($data, FILTER_SANITIZE_EMAIL);
        if (preg_match('/^[^\s()<>@,;:\/]+@\w[\w\.s-]+\.[a-z]{2,}$/i', $email)) {
            return true;
        }
        throw new InvalidArgumentException("Invalid email format: $email");
    }

    /**
     * password - Checks if a password follows the regurlar expression rule:
     *  * at least one lowercase char
     *  * at least one uppercase char
     *  * at least one digit
     *  * at least one special sign of @#-_$%^&+=§!?
     *  * at least 8-12 characters
     *
     * @param string $data The password
     * @return boolean true if valid else false
     */
    public function password($data) {
        if (!preg_match('/^(?=.*\d)(?=.*[@#$%*()])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#$%*()]{8,20}$/', $data)) {
            $data = "<p>
                Invalid password format, please ensure that your password is:
                <ul>
                <li>at least one lowercase char</li>
                <li>at least one uppercase char</li>
                <li>at least one digit</li>
                <li>at least one special sign of: @#$%*()</li>
                <li>at least 8-12 characters</li>
                </ul>
                </p>";
            throw new InvalidArgumentException($data);
        }
        return true;
    }

    /**
     * cell - Checks if a cell number if correct
     *
     * @param string $data The cell number
     * @return boolean true if valid else false
     */
    public function cell($data) {
        $this->digit($data);
        if (!preg_match('/(0[0-9]{9})/', $data)) {
            throw new InvalidArgumentException("Invalid cell number format: $data");
        }
        return true;
    }

}
