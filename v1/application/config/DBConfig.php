<?php

/**
 * Application config.
 * 
 * NOTE: DO NOT CHANGE FINE NAME OR CLASSE NAME
 *
 * @author Johannes Ramothale <jramothale@iecon.co.za>
 * @since 05 Oct 2016, 7:05:58 AM
 */
final class DBConfig {

    /** @var array config data */
    private $data = null;

    /**
     * getConfig - Returns the database connection in an assoc array
     * @return array
     * @throws Exception
     */
    public function getConfig($section = null) {
        if ($section === null) {
            return $this->getData();
        }
        $data = self::getData();
        if (!array_key_exists($section, $data)) {
            throw new Exception('Unknown config section: ' . $section);
        }
        return $data[$section];
    }

    /**
     * getData - Get the database configuration information from an .ini setup file
     * @return array
     */
    private function getData() {
        if ($this->data !== null) {
            return $this->data;
        }
        $this->data = parse_ini_file('db_config.ini', true);
        return $this->data;
    }

}
