<?php
/**
 * Created by JetBrains PhpStorm.
 * User: J
 * Date: 26.10.13
 * Time: 17:54
 * Interface to normalize different types of configs
 * so the user can switch the medium the config is saved with
 * but still has the same standard functionality available
 * within the system
 */


Interface Config_Interface {

    /**
     * default placeholder for hierachy
     *
     * -> since windows cant use the *
     * it's "@" you can change it if you are
     * sure no one is working with windows
     *
     * @var string
     */
    const HIERACHY_PLACEHOLDER = '*';

    /**
     * commandline variable to identify the
     * correct host for the scripts
     *
     * @var string
     */
    const CLI_HOST_VARIABLE = 'host';

    /**
     * commandline delimiter that indicates an variable
     * assignment
     *
     * @var string
     */
    const CLI_COMMAND_DELIMITER = '=';

    /**
     * default list of single line comment characters
     * separated by ,
     * -> list is exploded in the constructor
     *
     * @var string
     */
    const COMMENT_CHARACTER_LIST = '#,//';

    /**
     * config delimiter maybe you don't like
     * the ".' delimiter for the config and wanna
     * glue the structure by coma or exclamation marks
     *
     * @var string
     */
    const CONFIG_DELIMITER = '.';

    /**
     * constructor
     */
    public function __construct($param = null);

    /**
     * loads the config based on the type / source
     *
     * @param mixed $param
     *
     * @return mixed
     */
    public function load($param = null);

    /**
     * deletes a config node
     *
     * @param string
     *
     * @return bool
     */
    public function delete($id = '');

    /**
     * gets a specific parameter
     *
     * @param $var
     * @return mixed
     */
    public function get($var);

    /**
     * sets a specific parameter
     *
     * @param $key
     * @param $val
     * @return mixed
     */
    public function set($key, $val);

    /**
     * saves the specified config
     *
     * @param Config_Node $node
     * @return mixed
     */
    public function save_config(Config_Node $node = null);
}