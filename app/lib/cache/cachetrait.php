<?php
/**
 * trait to load the current cache system 
 * based on the config setting 
 * 
 * @author j
 *
 */
trait CacheTrait
{


    /**
     * cache object
     *
     * @var current Cache object
     */
    public $cache = null;


    /**
     * load current cache system
     */
    public function load_cache_handler()
    {

        switch ( Config::get('cache_handler') )
        {
            case 'memcached' :
            default :
                $this->cache = new CacheMemcached();
                $this->cache->addServer(Config::get('memcached_host'), Config::get('memcached_port'));
                break;
        }
    }
}
?>