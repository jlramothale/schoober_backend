<?php

final class View {

    /** @var string $page - The view page/name */
    private $page;

    /** @var string $title - The view page title */
    private $title;

    /** @var string $heading - The view page heading */
    private $heading;

    /** @var assoc array $model - The view model */
    private $model = array();

    /**
     * Default constructor
     */
    function __construct() {

    }

    /**
     * render - This function renders a page on the screen
     *
     * @param string $view to render, e.g. about or contact
     */
    public function render($view) {
        $this->includeHeader();
        $this->includeView($view);
        $this->includeFooter();
    }

    /**
     * includeView - includes a physical view
     *
     * @param string $view - View name
     */
    public function includeView($view) {
        if (file_exists(VIEW_PATH . $view . PHP_EXT)) {
            require VIEW_PATH . $view . PHP_EXT;
        } else {
            // handle view does not exists error 404
        }
    }

    /**
     * includeHeader - includes header file if it exists
     */
    private function includeHeader() {
        if (file_exists(VIEW_PATH . DS . "pages" . DS . "header" . PHP_EXT)) {
            require VIEW_PATH . DS . "pages" . DS . "header" . PHP_EXT;
        }
    }

    /**
     * includeFooter - includes footer if it exists
     */
    private function includeFooter() {
        if (file_exists(VIEW_PATH . DS . "pages" . DS . "footer" . PHP_EXT)) {
            require VIEW_PATH . DS . "pages" . DS . "footer" . PHP_EXT;
        }
    }

    /**
     * setPage - Set a page
     *
     * @param string $page
     */
    public function setPage($page) {
        $this->page = $page;
    }

    /**
     * getPage - Get a page
     *
     * @return string - Page name
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * setTitle - Set a page title
     *
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * getTitle - Get a title
     *
     * @return string - Page title
     */
    public function getTitle() {
        return $this->title;
    }

    public function setPageHeading($heading) {
        $this->heading = $heading;
    }

    public function getPageHeading() {
        return $this->heading;
    }

    /**
     * setModel - Set the associative view model array
     *
     * @param string $key - Array key
     * @param mixed $value - The value of the model
     */
    public function setModel($key, $value) {
        $this->model[$key] = $value;
    }

    /**
     * getModel - Returns a model value by key
     *
     * @param string $key - Associative array key
     * @return mixed - Return value
     */
    public function getModel($key) {
        return $this->model[$key];
    }

}
