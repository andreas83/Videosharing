<?php
/**
 * Created by JetBrains PhpStorm.
 * User: j
 * Date: 26.10.13
 * Time: 17:53
 *
 */

class Config
{
    /**
     * Default config as a fallback
     */
    const DEFAULT_CONFIG_TYPE = 'File';

    /**
     * singelton instance check
     *
     * @var object
     */
    public static $instance = null;

    /**
     * Constructor
     *
     * @return \Config
     */
    private function __construct()
    {
        return;
    }


    /**
     * singelton constructor
     *
     * @param null $type
     * @param null $param
     *
     * @return object
     */
    public static function get_instance($type = null, $param = null)
    {
        $configname = (string)  __NAMESPACE__ . (string) '\\Config_' . (string) (!empty($type) ?  $type : self::DEFAULT_CONFIG_TYPE);

        if ( self::$instance === null)
        {
            self::$instance = new $configname($param);
        }

        // return singelton instance
        return self::$instance;
    }

    /**
     * get wrapper
     *
     * @param $var
     * @return mixed
     */
    public static function get ($var)
    {
        return self::$instance->get($var);
    }

    /**
     * set wrapper
     *
     * @param $var
     * @param $value
     *
     * @return mixed
     */
    public static function set ($var, $value)
    {
        return self::$instance->set($var, $value);
    }

    /**
     * load module wrapper
     *
     * @param string $module_name
     * @return mixed
     */
    public function load_module( $module_name = ''){
        return self::$instance->load_module($module_name);
    }

    /**
     * save config wrapper
     *
     * @param Config_Node $node
     */
    public function save_config(Config_Node $node = null)
    {
        self::$instance->save_config($node);
    }

}
?>