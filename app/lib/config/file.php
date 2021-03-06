<?php
/**
 * Created by JetBrains PhpStorm.
 * User: j
 * Date: 26.10.13
 * Time: 17:53
 *
 */

class Config_File extends Config_Generic{


    /**
     * default fallback path
     *
     * @var string
     */
    CONST INCLUDE_PATTERN = 'include';

    /**
     * default pattern for the path only the config within shop_includes shall
     * be used
     *
     * @var string
     */
    CONST PATH_PATTERN = '/include/config';

    /**
     * extension for config files
     *
     * @var string
     */
    CONST FILE_EXTENSION = 'cfg';


    /**
     * dir handle
     *
     * @var resource
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
     * an array of characters that if they are at the
     * start of a string indicate it'S a comment and should not be added to the
     * Config object
     *
     * @var array
     */
    private $_comment_character_list = array();


    /**
     * constructor
     */
    public function __construct($param = null)
    {
        // set the default comment characters
        $this->_comment_character_list = explode(',', self::COMMENT_CHARACTER_LIST);
        $this->main_node = new Config_Node(null, 'file', null);

        // set the given parameters
        foreach ($param as $key => $value)
        {
            $node = new Config_Node($this->main_node, $key, $value);
            $this->main_node->add_child($node);
        }

        // get the path of the config
        if (empty($param['config_path'])) {
            $this->__get_config_path();
        }

        $this->load($param);
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function delete($id = '') {
        return true;
    }


    /**
     * gets the needed config files based on the
     * url or parameters given by the console
     *
     * @return array
     */
    protected function __get_config_set()
    {

        if ( empty( $_SERVER ) && empty( $GLOBALS ['argv'] ) )
        {
            return array();
        }

        // default config for all of them
        $_config_set = array();
        // if an apache is running use the http host of it
        if ( !empty( $_SERVER ['HTTP_HOST'] ) )
        {
            $this->host_id = ( string ) $_SERVER ['HTTP_HOST'];
        }
        // else check if there are console parameters
        else
        {
            // split them via spaces
            foreach ( $GLOBALS ['argv'] as $param )
            {

                if (strpos($param, Config_Interface::CLI_COMMAND_DELIMITER) === false) continue;
                // split the input into a key value pair
                $inp = ( array ) explode( Config_Interface::CLI_COMMAND_DELIMITER , $param );
                if ( strtolower( trim( $inp [0] ) ) == Config_Interface::CLI_HOST_VARIABLE )
                {
                    $this->host_id = ( string ) trim( $inp [1] );
                    break;
                }
            }
            unset( $inp, $param );
        }

        /**
         * if there's a specific port remove the port
         *
         * @todo keep in mind that maybe someone needs a port specific behaviour for his app
         */
        if ( ($pos = strpos( $this->host_id, ':' )) !== false )
        {
            $this->host_id = substr( $this->host_id, 0, $pos );
        }


        // split up the server host_id to an array
        $id_part_list = ( array ) explode( self::CONFIG_DELIMITER , $this->host_id );

        // add an extra iteration so there is a specific config for a subdomain
        // and a generic one for all subdomains in this toplevel domain
        $count = ( int ) count( $id_part_list ) + 1;
        $i = 0;

        $config_path = $this->get('config_path');
        // we don't need to rebuild this standard strings all the time
        $config_del = self::HIERACHY_PLACEHOLDER . ( string ) self::CONFIG_DELIMITER;
        $extension = self::CONFIG_DELIMITER . self::FILE_EXTENSION;

        // the first config is the current host id + .cfg
        $config_file = ( string )  $config_path . '/' . ( string ) implode( self::CONFIG_DELIMITER , $id_part_list ) . (string) $extension;

        do
        {
            // shift the first position of the array
            array_shift( $id_part_list );

            // if the file exists add it to the "to be parsed list"
            if ( file_exists( $config_file ) )
            {
                $_config_set [] = ( string ) $config_file;
            }

            $file_name = ( string ) (count($id_part_list) > 0  ? implode( self::CONFIG_DELIMITER , $id_part_list ) .(string) $extension : self::FILE_EXTENSION ) ;
            $config_file = ( string ) $this->get('config_path') . '/' . ( string ) $config_del . $file_name ;
            $i++;
        } while ( $i < $count );

        /**
         * Config sort algorhythm
         *
         * lamnda function for sorting
         *
         * @param $a string
         * @param $b string
         *
         * @return int
         */
        uasort( $_config_set, function ( $a, $b )
        {
            // include to the normal namespace

            if ( substr_count( $a, Config_File::CONFIG_DELIMITER ) == substr_count( $a, Config_File::CONFIG_DELIMITER ) )
            {
                if ( strpos( $a, Config_File::HIERACHY_PLACEHOLDER ) == true && strpos( $b, Config_File::HIERACHY_PLACEHOLDER ) == false )
                {
                    return -1;
                }
                elseif ( strpos( $a, Config_File::HIERACHY_PLACEHOLDER ) == false && strpos( $b, Config_File::HIERACHY_PLACEHOLDER ) == true )
                {
                    return 1;
                }
                else
                {
                    return 0;
                }
            }
            return (substr_count( $a, Config_File::CONFIG_DELIMITER ) > substr_count( $b, Config_File::CONFIG_DELIMITER ) ? -1 : 1);
        } );

        return $_config_set;
    }

    /**
     * checks if it's a comment in the config
     *
     * @param $line
     * @return bool
     */
    private function _is_comment($line)
    {
        // if it's an empty line you might as well skip it
        if (empty($line)) return true;

        $is_comment = false;

        foreach ($this->_comment_character_list as $comment_char)
        {
            if (strpos( trim( $line ), $comment_char ) !== false && strpos( trim( $line ), $comment_char ) <= 3) {
                $is_comment = true;
                break;
            }
        }

        return $is_comment;
    }


    /**
     * loads the config settings
     *
     *
     * @param null $param
     * @throws Config_Exception
     * @return bool
     */
    public function load( $param = null )
    {

        // check if the config path has been set
        if ( !$this->get('config_path') ) return false;

        // if there already has been a config set it means it already
        // has been loaded so why bother retrying ! this is not a dynamic language !
        if ( count($this->config_set) > 0 ) return true;
        $config_path = $this->get('config_path');

        // open config directory for the config files
        $this->dh = opendir($config_path);

        // if the config set already exists don't parse it
        if ( empty($this->config_set) )  {
            //$t =var_export(debug_backtrace(), true);
            //echo nl2br($t);
            // set it to the global config set array
            $this->config_set = $this->__get_config_set();
        }

        if ( empty( $this->config_set ) )
        {
            // set default config set for the default execution
            $this->config_set = array(
                "{$config_path}" . (string) self::HIERACHY_PLACEHOLDER . (string) self::CONFIG_DELIMITER . ( string ) self::FILE_EXTENSION
            );
        }
        $clean_config = array();

        /**
         * create the total config parameter array and merge it recursive
         */
        try
        {
            if ( empty( $this->config_set ) || !is_readable( $this->config_set [0] ) )
            {
                throw new Config_Exception( "No default config file declared {$config_path}/" . self::HIERACHY_PLACEHOLDER . (string) self::CONFIG_DELIMITER . ( string ) self::FILE_EXTENSION );
            }


            if ( !empty($this->config_set) )
            {

                // first insert point
                $main_node = null;
                foreach ($this->config_set as $default_config)
                {
                    if (($fp = fopen( $default_config, 'r' )) === false)
                    {
                        throw new Config_Exception( "Couldn't open file handle for {$config_path}". self::HIERACHY_PLACEHOLDER . (string) self::CONFIG_DELIMITER . ( string ) self::FILE_EXTENSION );
                    }

                    $main_node = new Config_Node($main_node, 'file', $default_config);
                    $this->main_node->add_child($main_node);


                    // if empty just skip it
                    if (!filesize( $default_config )) continue;

                    $config = (string) fread($fp, filesize($default_config));

                    if (strpos( $config, "\n" ) === false)
                    {
                        $current_config = array(
                            $config
                        );
                    }
                    else
                    {
                        $current_config = (array) explode("\n", $config);
                    }

                    fclose($fp);
                    // cleanup so that (A = 'b') == (A='B') == ( A ='B')
                    // still not the best way to go but to cover all ways would
                    // be a bit crazy
                    $count = (int) count( $current_config );

                    for ($i = 0; $i < $count; $i++)
                    {
                        // if there is no assignment ignore the line or if it'S
                        // commented
                        if (strpos($current_config[$i], "=") === false) continue;
                        // check if it's a comment
                        if ($this->_is_comment($current_config[$i])) continue;

                        $match = explode('=', $current_config[$i]);

                        if (!empty($match))
                        {
                            $node = new Config_Node($main_node, strtolower(trim($match[0])),  trim($match [1]));
                            $clean_config [strtolower(trim($match[0]))] = trim($match[1]);
                            $main_node->add_child($node);
                        }

                    }

                    unset($match, $current_config);
                }
            }

        }
        catch (Config_Exception $e)
        {
            throw $e;
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

        if ( empty( $total_cfg ) ) return false;

        foreach ( $total_cfg as $key => $value )
        {
            if ( empty( $value ) ) continue;

            // check if it's not a json array or object
            if ( strpos( trim( $value ), '[' ) === 0 || strpos( trim( $value ), '{' ) === 0 )
            {
                $this->set( strtolower( $key ), json_decode( trim( $value ) ) );
            }
            // check if it's a string
            elseif ( (preg_match( '/^["|\']{1}(.*)["|\']{1}$/', trim( $value ), $match )) === 1 )
            {
                if ( $this->create_define === true )
                {
                    if ( !defined( strtoupper( trim( $key ) ) ) ) define( strtoupper( trim( $key ) ), $match [1] );
                }

                $this->set( $key, ( string ) $match [1] );
            }
            // if it's not numeric but no quotes it's still a tring
            elseif ( !is_numeric( trim( $value ) ) && preg_match( '/^(true|false){1}$/', trim( $value ) ) )
            {
                $bool = (trim( $value ) == "true") ? true : false;
                if ( $this->create_define === true )
                {
                    if ( !defined( strtoupper( $key ) ) ) define( strtoupper( $key ), ( boolean ) $bool );
                }
                $this->set( strtolower( $key ), ( boolean ) $bool );
            }
            elseif ( !is_numeric( trim( $value ) ) )
            {
                if ( $this->create_define === true )
                {
                    if ( !defined( strtoupper( $key ) ) ) define( strtoupper( $key ), trim( $value ) );
                }
                $this->set( strtolower( $key ), ( string ) trim( $value ) );
            }
            // else it hast to be a numeric value
            else
            {
                // integer
                if ( is_numeric( $value ) && strpos( $value, '.' ) === false )
                {
                    // anyway define the constant
                    if ( $this->create_define === true )
                    {
                        if ( !defined( strtoupper( $key ) ) ) define( strtoupper( $key ), ( int ) trim( $value ) );
                    }
                    $this->set( strtolower( $key ), ( int ) trim( $value ) );
                }
                // american decadic system - float
                elseif ( is_numeric( $value ) && strpos( $value, '.' ) > 1 )
                {
                    // anyway define the constant
                    if ( $this->create_define === true )
                    {
                        if ( !defined( strtoupper( $key ) ) ) define( strtoupper( $key ), ( float ) trim( $value ) );
                    }
                    $this->set( strtolower( $key ), ( float ) trim( $value ) );
                }
            }
            unset( $match );
        }
        unset( $total_cfg );

        return true;
    }



    /**
     * load specific modules for scripts so that the config doesn't always need
     * all the settings
     *
     * @param $module_name string
     * @throws Config_Exception
     *
     * @return boolean
     */
    public function load_module( $module_name = null )
    {
        if (empty($module_name)) return false;

        $config_path = $this->get('config_path');
        if ( !file_exists( $config_path . "/module/$module_name" . self::FILE_EXTENSION ) )
        {
            return false;
        }

        if ( ($fp = fopen( $config_path . "/module/$module_name" . self::FILE_EXTENSION, 'r' )) === false )
        {
            throw new Config_Exception( "Couldn't open file handle for " . $config_path . "/module/$module_name" . self::FILE_EXTENSION );
        }

        // if empty just skip it
        if ( !filesize( $config_path . "/module/$module_name.cfg" ) ) return null;

        $config = ( string ) fread( $fp, filesize( $config_path . "/module/$module_name" . self::FILE_EXTENSION ) );
        if ( strpos( $config, "\n" ) === false )
        {
            $current_config = array(
                $config
            );
        }
        else
        {
            $current_config = ( array ) explode( "\n", $config );
        }
        // set clean config
        $clean_config = array();
        // cleanup so that (A = 'b') == (A='B') == ( A ='B')
        // still not the best way to go but to cover all ways would
        // be a bit crazy
        $count = ( int ) count( $current_config );
        for( $i = 0; $i < $count; $i++ )
        {
            // if there is no assignment ignore the line or if it'S commented
            if ( strpos( $current_config [$i], "=" ) === false ) continue;

            if ( $this->_is_comment($current_config[$i]) ) continue;

            $match = explode( '=', $current_config [$i] );
            if ( !empty( $match ) )
            {
                $clean_config [strtolower( trim( $match [0] ) )] = trim( $match [1] );
            }
        }
        // parse the config
        $this->__parse_config( $clean_config );

        return $this;
    }



    /**
     * self recursive function call to match the path pattern to
     * the current list of possible directories based on the execution path
     *
     * @param string $n path_part
     * @param array $tmp path_array
     *
     * @return boolean|string
     */
    protected function _extract_subpath( $n, $tmp )
    {

        if ( empty( $tmp ) || empty( $n ) ) return false;

        if ( strpos( $n, $tmp [0] ) === false ) return false;

        if ( count( $tmp ) == 1 && is_dir( "$n/{$tmp[0]}" ) )
        {
            return "$n/{$tmp[0]}";
        }
        elseif ( is_dir( "$n/{$tmp[0]}" ) )
        {
            return $this->_extract_subpath( "$n/{$tmp[0]}", $tmp );
        }

        return false;
    }



    /**
     * extracts the config path from the execution path
     *
     * @return boolean string Ambigous
     */
    protected function __get_config_path()
    {
        /**
         * if the shop_includes path can be found from the current directory
         */
        if ( ($cut = strpos( __DIR__, self::INCLUDE_PATTERN )) !== false )
        {
            $config_path = new Config_node($this->main_node, 'config_path', substr( __DIR__, 0, $cut ) . self::PATH_PATTERN);
            $this->main_node->add_child($config_path);
            return true;
        }


        // get the current path of execution
        $execution_path = getcwd();

        // get the class constant
        $tmp = self::PATH_PATTERN;

        // if there is no pattern match just quit
        if ( empty( $tmp ) ) return false;

        // if we're on the docroot lets see if it's in the current path
        if ( file_exists( "$execution_path/" . self::PATH_PATTERN ) )
        {
            $config_path = new Config_node($this->main_node, 'config_path', ( string ) "$execution_path/" . self::PATH_PATTERN);
            $this->main_node->add_child($config_path);
            return true;
        }

        // check if it's a multiple path otherwise convert it to an array anyway
        if ( strstr( $tmp, '/' ) !== false )
        {
            $tmp_path = (array) explode( '/', $tmp );
        }
        else
        {
            $tmp_path = array(
                $tmp
            );
        }

        // this is the last resort go downwards till it hits / for the config
        $directory_depth = ( array ) explode( '/', $execution_path );
        $count = ( int ) count( $directory_depth );

        for( $i = 0; $i < $count; $i++ )
        {
            $current_dir = ( string ) array_shift( $directory_depth );
            $parent_dir = ( string ) "/" . implode( '/', $directory_depth );

            // open dir handle and lets try to find the directory
            $dh = @opendir( "$parent_dir/$current_dir" );
            if ( !$dh ) continue;

            // start the loop based on the directory
            while ( ($n = ( string ) readdir( $dh )) !== false )
            {
                // check if it's an directory
                if ( !is_dir( $n ) ) continue;

                // start self recursion on the path pattern
                if ( ($path = $this->_extract_subpath( $execution_path . '/' . $n, $tmp_path )) !== false )
                {
                    // if there's a hit for the config path
                    $config_path = new Config_node($this->main_node, 'config_path', ( string ) $path);
                    $this->main_node->add_child($config_path);
                    return true;
                }
            }
        }

        return false;
    }


    public function save_config_node(){
        return true;
    }

    public function save_config(Config_Node $node = null){
        if (!empty($node)) return $this->save_config_node($node);

        return true;
    }


}