<?php
/**
 * Created by JetBrains PhpStorm.
 * User: J
 * Date: 01.11.13
 * Time: 16:47
 */

class Config_NodeList {

    public $list = null;

    public function __construct(){
        $this->list = [];
    }

    /**
     * @param Config_Node $node
     *
     * @return $this
     */
    public function add_node(Config_Node $node)
    {
        if ($node->get_parent() instanceof Config_Node){
            $pos = $node->get_parent()->get_id();
        }
        else{
            $pos = md5($node->get_id());
        }

        $this->list[$pos][$node->get_key()] = $node;

        return $this;
    }


    /**
     * walks through the child nodes
     *
     * @param $key
     *
     * @return mixed
     */
    public function get_by_key($key)
    {
        if (count($this->list) == 0) return null;

        foreach ($this->list as $list)
        {
            if (isset($list[$key])) return $list[$key]->get_value();

            foreach ($list as $node)
            {
                if ( ($ret = $node->get_by_key($key)) !== null) return $ret;
            }
        }

        return null;
    }


    public function remove_node($mixed)
    {
        return true;
    }
}