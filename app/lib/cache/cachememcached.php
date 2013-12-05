<?php

/**
 * Memcached extension
*
* @author j
*/
class CacheMemcached extends Memcached
{


    /**
     * list array of all entries
     *
     * @var array
     */
    public $list = array();


    /**
     * storage for key flushing, if needed
     *
     * @var array
     */
    public $cache_listing = array();


    /**
     * md5 sum of the cache_list from
     * the contructor
     *
     * @var string
     */
    private $_md5_sum = NULL;


    /**
     * indicator if the connection was possible
     *
     * @var boolean
     */
    public $is_connected = false;


    /**
     * construct wrapper
     *
     * @param $persistant_key string            
     */
    public function __construct( $persistant_key = NULL )
    {
        
        parent::__construct((is_string($persistant_key) || is_numeric($persistant_key)) ? $persistant_key : NULL);
    }


    /**
     * (non-PHPdoc)
     *
     * @see Memcached::addServer()
     *
     * @abstract indicator of a positive connection should be returned
     */
    public function addServer( $host , $port , $weight = null )
    {

        $this->is_connected = parent::addServer($host, $port, $weight);
       
        
        
        // Get the Cache Listing
        $this->cache_listing = parent::get('cache_listing');
        
        if ( $this->cache_listing === FALSE )
        {
            parent::add('cache_listing', array());
            $this->cache_listing = array();
        }
        
        // check sum for the saving process
        $this->_md5_sum = md5(serialize($this->cache_listing));
    }


    /**
     * Save the cache_listing to memcached
     *
     * @return boolean
     */
    public function save_cache_listing()
    {

        if ( md5(serialize($this->cache_listing)) === $this->_md5_sum ) return;
        return parent::set('cache_listing', $this->cache_listing);
    }


    /**
     * a listing of all cached entries which have been
     * inserted through this wrapper
     *
     * @return boolean
     */
    public function list_cache()
    {

        $newlist = array();
        
        foreach ( $this->cache_listing as $key => $val )
        {
            $newlist[$key] = new stdClass();
            
            foreach ( $val as $skey => $sval )
            {
                $newlist[$key]->$skey = $sval;
            }
        }
        
        $this->list = $newlist;
        
        return TRUE;
    }


    /**
     * set method
     *
     * @param $key string            
     * @param $value mixed            
     * @param $expiration int            
     *
     * @return boolean
     */
    public function set( $key , $value , $expiration = NULL )
    {

        if ( parent::set($key, $value, ($expiration ? $expiration : NULL)) )
        {
            $expiration = (empty($expiration)) ? 0 : $expiration;
            
            // Prepare Listing
            $newListing = array(
                                'key' => (string) $key, 
                                'expiration' => (int) $expiration, 
                                'updated' => (string) date('Y-m-d H:i:s')
            );
            
            if ( isset($this->cache_listing[$key]) )
            {
                $this->cache_listing[$key] = array_merge($this->cache_listing[$key], $newListing);
            }
            else
            {
                $newListing['created'] = $newListing['updated'];
                $this->cache_listing[$key] = $newListing;
            }
            
            $this->save_cache_listing();
            
            return TRUE;
        }
        
        return FALSE;
    }


    /**
     * get method
     *
     * @param $key string            
     * @param $cache_cb callable
     *            [optional]
     * @param $cas_token float
     *            [optional]
     *            
     * @return mixed
     */
    public function get( $key , $cache_cb = NULL , &$cas_token = NULL )
    {

        if ( isset($this->cache_listing[$key]) )
        {
            return parent::get($key, $cache_cb, $cas_token);
        }
        
        return FALSE;
    }


    /**
     * flush the whole cache
     *
     * @param $delay integer
     *            delay in seconds
     *            
     * @return boolean
     */
    public function flush( $delay = 0 )
    {

        if ( parent::flush((int) $delay) )
        {
            $this->cache_listing = array();
            return TRUE;
        }
        
        return FALSE;
    }


    /**
     * add method
     *
     * @param $key string            
     * @param $value mixed            
     * @param $expiration int            
     *
     * @return boolean
     */
    public function add( $key , $value , $expiration = NULL )
    {

        return $this->set($key, $value, $expiration);
    }


    /**
     * delete method
     *
     * @param $key string            
     * @param $time int            
     * @return boolean
     */
    public function delete( $key , $time = 0 )
    {

        if ( parent::delete($key, ($time ? $time : NULL)) )
        {
            unset($this->cache_listing[$key]);
            $this->save_cache_listing();
            return TRUE;
        }
        
        return FALSE;
    }


    /**
     * delete method multiserver pools
     *
     * @param $server_key string            
     * @param $key string            
     * @param $time int            
     *
     * @return boolean
     */
    public function deleteByKey( $server_key , $key , $time = 0 )
    {

        if ( parent::deleteByKey($server_key, $key, ($time ? $time : NULL)) )
        {
            unset($this->cache_listing[$key]);
            $this->save_cache_listing();
            return TRUE;
        }
        
        return FALSE;
    }


    /**
     * delete memcached values based on an input array
     *
     *
     * @param array $key_array            
     */
    public function delete_from_list( $key_array = array() )
    {

        if ( empty($key_array) ) return false;
        
        foreach ( $key_array as $key_del )
        {
            if ( empty($this->cache_listing) ) break;
            foreach ( $this->cache_listing as $key => $set )
            {
                if ( strpos(strtolower($key), strtolower($key_del)) === false ) continue;
                
                $this->delete($key);
            }
        }
        
        return true;
    }


    public function __destruct()
    {
        parent::quit();
    }
}
?>
