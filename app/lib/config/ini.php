<?php
/**
 * Created by PhpStorm.
 * User: j
 * Date: 02.11.13
 * Time: 12:36
 */

class Config_Ini extends Config_Generic{

    /**
     * name of the config file
     *
     * @var string
     */
    public $config_file = '';

    /**
     * scanner mode
     *
     * @var int
     */
    public $scanner_mode = null;

    /**
     * process sections
     *
     * @var bool
     */
    public $process_sections = null;

    /**
     * loads the config based on the type / source
     *
     * @param mixed $param
     *
     * @throws Config_Exception
     *
     * @return mixed
     */
    public function load( $param = null ){
        try
        {
            if ( empty($param['file']) ) {
                throw new Config_Exception(_('No config file was give please, the parameter '. $param, 0, 1, __METHOD__, __LINE__));
            }
            $this->config_file = (string) $param['file'];

            if ( isset($param['process_sections']) ) {
                $this->process_sections = (bool) $param['process_sections'];
            }
            if ( isset($param['scanner_mode']) ) {
                $this->scanner_mode = (int) $param['scanner_mode'];
            }

            $data = parse_ini_file($this->config_file, $this->process_sections, $this->scanner_mode);

            $this->main_node = new Config_Node(null, 'main_node');
            foreach ($data as $key => $group)
            {
                if( !is_array($group) )
                {
                    $main_node = new Config_Node($this->main_node, $key, $group);
                    $this->main_node->add_child($main_node);
                    continue;
                }

                $main_node = new Config_Node($this->main_node, $key, $key);

                foreach ($group as $name => $value)
                {
                    $node = new Config_Node($main_node, $name, $value);
                    $main_node->add_child($node);
                }
                $this->main_node->add_child($main_node);
            }
        }
        catch (Config_Exception $e)
        {
            throw $e;
        }
    }

    /**
     * deletes a config node
     *
     * @param string $id
     * @return mixed
     */
    public function delete($id = ''){

    }

    /**
     * saves the specified config
     *
     * @param Config_Node $node
     * @internal param $array ;
     *
     * @return mixed
     */
    function save_config(Config_Node $node = null){

    }
}