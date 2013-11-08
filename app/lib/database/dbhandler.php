<?php
/**
 * Abstract DBHandler Class
 * 
 * @author j
 */
abstract class DBHandler implements DBInterface
{


    /**
     * database handler based on selected extension
     *
     * @var type
     */
    public $dbh;


    /**
     * constructor initializes the default database connection
     */
    public function __construct()
    {

        set_exception_handler(array(
                                    __CLASS__, 
                                    'fallback_handler'
        ));
        restore_exception_handler();
        
        Config::set('db_dsn', 'mysql:host=' . Config::get('db_host') . ';dbname=' . Config::get('db_name'));
        $this->initDB();
    }


    /**
     * fallback Exception handler
     *
     * @param unknown_type $exception            
     */
    public function fallback_handler( $exception )
    {

        die('Uncaught exception: ' . $exception->getMessage());
    }


    /**
     * abstract initialization of the database handler
     */
    abstract public function initDB();
}

?>
