<?php
class BaseApp
{
    /**
     * load database trait
     */
    use DBTrait;
    
    /**
     * load cache trait
     */
    use CacheTrait;


    public function __construct()
    {
        
        // load database handler
        $this->load_database_handler();
        // load cache handler
        $this->load_cache_handler();
    }
}

