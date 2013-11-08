<?php

/**
 * Created by JetBrains PhpStorm.
 * User: J
 * Date: 26.10.13
 * Time: 17:54
 *
 * Generic abstract class to implement the Config interface
 * which than is inherited by the children
 *
 */

abstract class Config_Generic implements Config_Interface {

    /**
     * main config node
     *
     * @var Config_Node
     */
    public $main_node = null;

    /**
     * constructor
     *
     * @param mixed $param
     */
    public function __construct($param = null)
    {
        $this->load($param);
    }


    /**
     * loads the config based on the type / source
     *
     * @param mixed $param
     *
     * @return mixed
     */
    abstract public function load($param = null);

    /**
     * deletes a config
     *
     * @param string $id
     * @return mixed
     */
    abstract public function delete($id = '');

    /**
     * gets a specific parameter
     *
     * @param $var
     * @return mixed
     */
     public function get($var) {
         return $this->main_node->get_by_key($var);
     }

    /**
     * sets a specific parameter
     *
     * @param $key
     * @param $val
     * @return mixed
     */
    public function set($key, $val){
        // set the variable
        if ( empty( $key ) ) return $this;

        $node = new Config_Node($this->main_node, $key, $val);

        $this->main_node->add_child($node);

        return $this;

    }

    /**
     * saves the specified config
     *
     * @param Config_Node $node
     * @internal param $array ;
     *
     * @return mixed
     */
    abstract function save_config(Config_Node $node = null);

}