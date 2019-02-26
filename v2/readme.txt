Schoober API. v1.0

Test Interface: This interfaces with a Test Controller.

As you will see from the code of TestController.php, I have defined a simple REST interface, to get, getList and insert.

Root url or host url: https://api.schoober.co.za.

Define below are the available REST API routes for version 1.0 test interface.
  - https://api.schoober.co.za/test/get?id={1 .... n} // get an item by id
  - https://api.schoober.co.za/test/getList // get a list of items
  - https://api.schoober.co.za/test/insert?first_name={"johannes"}&last_name={"ramothale"} // insert a new item with first_name and last_name
 
Data structure: MySQL database table
create table if not exists test_table (
  id bigint auto_increment primary key not null,
  first_name varchar(256) not null default '',
  last_name varchar(256) not null default '',
  avator varchar(256) not null default '',
  deleted tinyint(1) not null default 0
) ENGINE=InnoDB;

-- Test Controller
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
