<?php
/**
 * Description of pdodb
 *
 * @author lissi
 */
class DBPDO extends DBHandler
{


    /**
     * (non-PHPdoc)
     *
     * @see DBHandler::initDB()
     */
    public function initDB()
    {

        try
        {
            $this->dbh = new PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        }
        catch ( Exception $e )
        {
            throw $e;
        }
    }
}

?>
