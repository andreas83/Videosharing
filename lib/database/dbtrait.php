<?php
/**
 * trait to load the current databasehandler 
 * base on the config settings
 * 
 * 
 * @author j
 *
 */
trait DBTrait
{


    /**
     * only the database handling object
     *
     * @var mysqli pdo
     */
    public $dbh = null;


    /**
     * complete object
     */
    public $dbobject = null;


    private function load_database_handler()
    {

        switch ( Config::get('database_handler') )
        {
            case 'mysqli' :
                $this->dbobject = new DBMySQLi();
                $this->dbh = $this->dbobject->dbh;
                break;
            case 'pdo' :
            default :
                $this->dbobject = new DBPDO();
                $this->dbh = $this->dbobject->dbh;
                break;
        }
        
        return true;
    }
}