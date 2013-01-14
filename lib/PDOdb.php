<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pdodb
 *
 * @author lissi
 */
class PDOdb extends DBHandler 
{
    //put your code here
    
    /**
     * asdfasdf
     */
    public function initDB()
    {
        $this->dbh = new PDO(Config::get('pdo_dsn'),Config::get('pdo_user'),Config::get('pdo_pass'));
    }
}

?>
