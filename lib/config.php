<?php
class Config
{


    /**
     * default fallback path
     *
     * @var unknown_type
     */
    CONST INCLUDE_PATTERN = 'config';


    /**
     * default pattern for the path only the config within shop_includes shall
     * be
     * used
     *
     * @var string
     */
    CONST PATH_PATTERN = 'app/config';


    /**
     * extension for config files
     *
     * @var string
     */
    CONST EXTENSION = '.cfg';


    /**
     * constant placeholder for the generic includes
     * 
     * @var string
     */
    CONST PREFIX = '*';
    
    /**
     * singelton instance check
     *
     * @var object
     */
    public static $instance = null;


    /**
     * dir handle
     *
     * @var ressource
     */
    private $dh = null;


    /**
     * default value if global defines should be created
     *
     * @var boolean
     */
    public $create_define = true;


    /**
     * identifier for the config
     *
     * @var string
     */
    public $host_id = '';


    /**
     * array of config files that are parsed
     *
     * @var array
     */
    public $config_set = array();


    /**
     * Constructor
     *
     * @return void
     */
    private function __construct()
    {

        return;
    }


    /**
     * singelton constructor
     *
     * @return object
     */
    public static function create_instance( $config_path = null )
    {

        if ( self::$instance === NULL )
        {
            self::$instance = new Config($config_path);
        }
        // get the path of the config
        self::$instance->__get_config_path();
        
        // load the variables defined in the config
        self::$instance->__load_config();
        
        // return singelton instance
        return self::$instance;
    }


    /**
     * gets the needed config files based on the
     * url or parameters given by the console
     *
     * @return array
     */
    protected function __get_config_set()
    {

        if ( empty($_SERVER) && empty($GLOBALS['argv']) )
        {
            return array();
        }
        
        // default config for all of them
        $_config_set = array();
        
        // if an apache is running use the http host of it
        if ( !empty($_SERVER['HTTP_HOST']) )
        {
            $this->host_id = (string) $_SERVER['HTTP_HOST'];
        }
        // else check if there are console parameters
        else
        {
            // split them via spaces
            foreach ( $GLOBALS['argv'] as $param )
            {
                // split the input into a key value pair
                $inp = (array) explode('=', $param);
                if ( strtolower(trim($inp[0])) == 'host' )
                {
                    $this->host_id = (string) trim($inp[1]);
                    break;
                }
            }
            unset($inp, $param);
        }
        if ( ($pos = strpos($this->host_id, ':')) !== false )
        {
            $this->host_id = substr($this->host_id, 0, $pos);
        }
        
        // split up the server host_id to shift the array
        $possible = (array) explode('.', $this->host_id);
        $count = (int) count($possible) + 1;
        $config_file = (string) $this->config_path . '/' . (string) implode('.', $possible) . (string) self::EXTENSION;
        $i = 0;
        do
        {
            // shift the first position of the array
            array_shift($possible);
            // if the file exists add it to the "to be parsed list"
            if ( file_exists($config_file) )
            {
                $_config_set[] = (string) $config_file;
            }
            $config_file = (string) $this->config_path . '/' . self::PREFIX . '.' . (string) implode('.', $possible) . (string) self::EXTENSION;
            // cleanup the case if there are two dots in a row (*..cfg)
            $config_file = (string) preg_replace('/[\.]{2}/', '.', $config_file);
            $i++;
        }
        while ( $i < $count );
        
        // clean up
        unset($config_file, $possible, $i, $count);
        // sort the hirachy
        /**
         * Config sort algorhythm
         *
         * ugly but should work ;)
         *
         * @param $a string            
         * @param $b string            
         * @return 1 -1
         */
        function config_sort( $a , $b )
        {

            if ( substr_count($a, '.') == substr_count($a, '.') )
            {
                if ( strpos($a, Config::PREFIX) == true && strpos($b, Config::PREFIX) == false )
                {
                    return -1;
                }
                elseif ( strpos($a, Config::PREFIX) == false && strpos($b, Config::PREFIX) == true )
                {
                    return 1;
                }
                else
                {
                    return 0;
                }
            }
            return (substr_count($a, '.') > substr_count($b, '.') ? -1 : 1);
        }
        
        // sort the hirachy
        uasort($_config_set, 'config_sort');
        return $_config_set;
    }


    /**
     * loads the config settings
     *
     * @throws Exception
     *
     * @return bool
     */
    protected function __load_config()
    {
        // check if the config path has been set
        if ( empty($this->config_path) ) return false;
        
        // default value
        $total_cfg = array();
        
        // open config directory for the config files
        $this->dh = opendir($this->config_path);
        
        // set it to the global config set array
        $this->config_set = $this->__get_config_set();
        
        if ( empty($this->config_set) )
        {
            // set default config set for the default execution
            $this->config_set = array(
                                    "{$this->config_path}/" . (string) self::PREFIX . (string) self::EXTENSION
            );
        }
        
        /**
         * create the total config parameter array and merge it recursive
         */
        try
        {
            if ( empty($this->config_set) || !is_readable($this->config_set[0]) )
            {
                throw new Exception("No default config file declared {$this->config_path}/*.cfg");
            }
            elseif ( !empty($this->config_set) )
            {
                foreach ( $this->config_set as $default_config )
                {
                    if ( ($fp = fopen($default_config, 'r')) === false )
                    {
                        throw new Exception("Couldn't open file handle for {$this->config_path}/*.cfg");
                    }
                    // if empty just skip it
                    if ( !filesize($default_config) ) continue;
                    
                    $config = (string) fread($fp, filesize($default_config));
                    if ( strpos($config, "\n") === false )
                    {
                        $current_config = array(
                                                $config
                        );
                    }
                    else
                    {
                        $current_config = (array) explode("\n", $config);
                    }
                    
                    // cleanup so that (A = 'b') == (A='B') == ( A ='B')
                    // still not the best way to go but to cover all ways would
                    // be a bit crazy
                    $count = (int) count($current_config);
                    for ( $i = 0 ; $i < $count ; $i++ )
                    {
                        // if there is no assignment ignore the line or if it'S
                        // commented
                        if ( strpos($current_config[$i], "=") === false ) continue;
                        if ( strpos(trim($current_config[$i]), '#') !== false && strpos(trim($current_config[$i]), '#') <= 3 ) continue;
                        
                        $match = explode('=', $current_config[$i]);
                        
                        if ( !empty($match) )
                        {
                            $key = strtolower(array_shift($match));
                            $value = implode('=', $match);
                            $clean_config[trim($key)] = trim($value);
                        }
                    }
                    unset($match, $current_config);
                }
                
                $this->__parse_config($clean_config);
            }
        }
        catch ( Exception $e )
        {
            echo $e->getMessage();
            return false;
        }
        
        return true;
    }


    /**
     * parses the config
     *
     * @param $total_cfg array            
     *
     * @return boolean
     */
    public function __parse_config( $total_cfg = null )
    {

        if ( empty($total_cfg) ) return false;
        
        foreach ( $total_cfg as $key => $value )
        {
            if ( empty($value) ) continue;
            
            // check if it's not a json array or object
            if ( strpos(trim($value), '[') === 0 || strpos(trim($value), '{') === 0 )
            {
                $this->set(strtolower($key), json_decode(trim($value)));
            }
            // check if it's a string
            elseif ( (preg_match('/^["|\']{1}(.*)["|\']{1}$/', trim($value), $match)) === 1 )
            {
                if ( $this->create_define === true )
                {
                    if ( !defined(strtoupper(trim($key))) ) define(strtoupper(trim($key)), $match[1]);
                }
                
                $this->set($key, (string) $match[1]);
            }
            // if it's not numeric but no quotes it's still a tring
            elseif ( !is_numeric(trim($value)) && preg_match('/^(true|false){1}$/', trim($value)) )
            {
                $bool = (trim($value) == "true") ? true : false;
                if ( $this->create_define === true )
                {
                    if ( !defined(strtoupper($key)) ) define(strtoupper($key), (boolean) $bool);
                }
                $this->set(strtolower($key), (boolean) $bool);
            }
            elseif ( !is_numeric(trim($value)) )
            {
                if ( $this->create_define === true )
                {
                    if ( !defined(strtoupper($key)) ) define(strtoupper($key), trim($value));
                }
                $this->set(strtolower($key), (string) trim($value));
            }
            // else it hast to be a numeric value
            else
            {
                // integer
                if ( is_numeric($value) && strpos($value, '.') === false )
                {
                    // anyway define the constant
                    if ( $this->create_define === true )
                    {
                        if ( !defined(strtoupper($key)) ) define(strtoupper($key), (int) trim($value));
                    }
                    $this->set(strtolower($key), (int) trim($value));
                }
                // american decadic system - float
                elseif ( is_numeric($value) && strpos($value, '.') > 1 )
                {
                    // anyway define the constant
                    if ( $this->create_define === true )
                    {
                        if ( !defined(strtoupper($key)) ) define(strtoupper($key), (float) trim($value));
                    }
                    $this->set(strtolower($key), (float) trim($value));
                }
            }
            unset($match);
        }
        unset($total_cfg);
        
        return true;
    }


    /**
     * load specific modules for scripts so that the config doesn't always need
     * all the settings
     *
     * @param $module_name string            
     * @throws Exception
     *
     * @return boolean
     */
    public static function load_module( $module_name = null )
    {
        // default self constructor
        if ( empty(self::$instance) ) $this->create_instance();
        
        if ( !file_exists(self::$instance->config_path . "/module/$module_name" . self::EXTENSION) )
        {
            return false;
        }
        
        if ( ($fp = fopen(self::$instance->config_path . "/module/$module_name" . self::EXTENSION, 'r')) === false )
        {
            throw new Exception("Couldn't open file handle for " . self::$instance->config_path . "/module/$module_name" . self::EXTENSION);
        }
        // if empty just skip it
        if ( !filesize(self::$instance->config_path . "/module/$module_name.cfg") )
        {
            return false;
        }
        
        $config = (string) fread($fp, filesize(self::$instance->config_path . "/module/$module_name" . self::EXTENSION));
        
        if ( strpos($config, "\n") === false )
        {
            $current_config = array(
                                    $config
            );
        }
        else
        {
            $current_config = (array) explode("\n", $config);
        }
        
        // cleanup so that (A = 'b') == (A='B') == ( A ='B')
        // still not the best way to go but to cover all ways would
        // be a bit crazy
        $count = (int) count($current_config);
        for ( $i = 0 ; $i < $count ; $i++ )
        {
            // if there is no assignment ignore the line or if it'S commented
            if ( strpos($current_config[$i], "=") === false ) continue;
            if ( strpos(trim($current_config[$i]), '#') !== false && strpos(trim($current_config[$i]), '#') <= 3 ) continue;
            
            $match = explode('=', $current_config[$i]);
            if ( !empty($match) )
            {
                $clean_config[strtolower(trim($match[0]))] = trim($match[1]);
            }
        }
        
        return self::$instance->__parse_config($clean_config);
    }


    /**
     * extracts the config path from the execution path
     *
     * @return boolean string Ambigous
     */
    protected function __get_config_path()
    {

        /**
         * self recursive function call to match the path pattern to
         * the curren list of possible directories based on the execution path
         *
         * @param $n path_part            
         * @param $tmp path_array            
         *
         * @return boolean string Ambigous
         */
        if ( !function_exists('extract_subpath') )
        {


            function extract_subpath( $n , $tmp )
            {

                if ( empty($tmp) || empty($n) ) return false;
                
                if ( strpos($n, $tmp[0]) === false ) return false;
                
                $nx = $n . '/' . array_shift($tmp);
                echo $nx;
                
                if ( count($tmp) == 1 && is_dir("$n/{$tmp[0]}") )
                {
                    echo "$n/{$tmp[0]}";
                    return "$n/{$tmp[0]}";
                }
                elseif ( is_dir("$n/{$tmp[0]}") )
                {
                    return extract_subpath("$n/{$tmp[0]}", $tmp);
                }
                
                return false;
            }
        }
        
        // if it's opened from the browser just take the default
        // docroot
        if ( !empty($_SERVER['DOCUMENT_ROOT']) && empty($this->config_path) )
        {
            $this->config_path = $_SERVER['DOCUMENT_ROOT'] . '/' . self::PATH_PATTERN;
            return true;
        }
        
        // get the current path of execution
        $execution_path = getcwd();
        // get the class constant
        $tmp = self::PATH_PATTERN;
        
        // if there is no pattern match just quit
        if ( empty($tmp) ) return false;
        
        // if we're on the docroot lets see if the basic config path exists
        if ( file_exists("$execution_path/" . self::PATH_PATTERN) )
        {
            $this->config_path = (string) "$execution_path/" . self::PATH_PATTERN;
            return true;
        }
        
        // check if it's a multiple path otherwise convert it to an array anyway
        if ( strstr($tmp, '/') !== false )
        {
            $tmp_path = (array) explode('/', $tmp);
        }
        else
        {
            $tmp_path = array(
                            $tmp
            );
        }
        
        $directory_depth = (array) explode('/', $execution_path);
        $count = (int) count($directory_depth);
        
        /**
         * self aware fork -> if the shop_includes path can be found from the
         * current file
         */
        if ( ($cut = strpos(__DIR__, self::INCLUDE_PATTERN)) !== false )
        {
            $this->config_path = substr(__DIR__, 0, $cut) . self::PATH_PATTERN;
            return true;
        }
        
        for ( $i = 0 ; $i < $count ; $i++ )
        {
            $current_dir = (string) array_shift($directory_depth);
            $parent_dir = (string) "/" . implode('/', $directory_depth);
            
            // open dir handle and lets try to find the directory
            $dh = @opendir("$parent_dir/$current_dir");
            
            if ( !$dh ) continue;
            
            // start the loop based on the directory
            while ( ($n = (string) readdir($dh)) !== false )
            {
                // check if it's an directory
                if ( !is_dir($n) ) continue;
                
                // start self recursion on the path pattern
                if ( ($path = extract_subpath($execution_path . '/' . $n, $tmp_path)) !== false )
                {
                    // if there's a hit for the config path
                    $this->config_path = (string) $path;
                    return true;
                }
            }
        }
        
        return false;
    }


    /**
     * dynamic getter
     *
     * @param $var string            
     *
     * @return boolean string array
     */
    public static function get( $var = '' )
    {
        // default self constructor
        if ( empty(self::$instance) ) $this->create_instance();
        
        // check if it's set exist
        if ( empty($var) || !property_exists(self::$instance, (string) $var) ) return false;
        
        return self::$instance->$var;
    }


    /**
     * dynamic setter
     *
     * 1st param property name
     * 2nd param property val
     *
     * @param $var string            
     * @param $val string|int|bool|array            
     *
     * @return boolean
     */
    public static function set( $var , $val )
    {
        // default self constructor
        if ( empty(self::$instance) ) $this->create_instance();
        
        // set the variable
        if ( empty($var) ) return false;
        
        // set it as a konstant if wanted
        if ( !defined(strtoupper((string) $var)) && is_scalar($val) && self::$instance->create_define === true )
        {
            define(strtoupper($var), $val);
        }
        
        self::$instance->$var = $val;
        return true;
    }
}
?>
