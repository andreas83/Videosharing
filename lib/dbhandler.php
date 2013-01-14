<?php
abstract class DBHandler
{
    /**
     * database handler based on selected extension
     * @var type 
     */
    public $dbh;
    
    public function  __construct() {
        set_exception_handler(array(__CLASS__, 'fallback_handler'));
        restore_exception_handler();
        $this->initDB();
    }
    

    public function fallback_handler($exception) {
        die('Uncaught exception: '. $exception->getMessage());
    }
    
    abstract public function initDB();
}

?>
