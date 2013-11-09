<?php
/**
 * Created by JetBrains PhpStorm.
 * User: j
 * Date: 26.10.13
 * Time: 19:19
 */

class Config_Node {

    /**
     * parent node -> objects are references
     * so it should not blow up the memory space and recursions
     * or accessing the parent node is much easier
     *
     * @var Config_Node
     */
    private $parent_node = null;

    /**
     * Id of this node -> needed for the
     * searching and specific calls
     *
     * @var mixed
     */
    private $id = '';

    /**
     * key value is the
     *
     * @var string
     */
    private $key = '';

    /**
     * Config Node if loaded
     * can be mixed since it should dynamic
     *
     * @var mixed
     */
    private $value = null;

    /**
     * comment string
     *
     * @var string
     */
    private $comment = '';


    /**
     * contains the list of children
     *
     * @var Config_NodeList
     */
    public $children = null;

    /**
     * constructor
     *
     * @param Config_Node $parent_node
     * @param $key
     * @param $value
     * @param string $comment
     *
     * @internal param $parent_id
     */
    public function __construct(Config_Node $parent_node = null, $key, $value, $comment = '') {
        // get the current node
        $this->parent_node = $parent_node;
        // set the current key identifier
        $this->key = $key;
        // set the current value of the node
        $this->__init_type($value);

        // set id as a md5 hash
        if (is_scalar($value)) {
            $this->id = (!empty($this->parent_node)) ? md5($this->parent_node->id.$key.$value) : $this->id = md5(null.$key.$value);
        }
        else
        {
            $this->id = (!empty($this->parent_node)) ? md5($this->parent_node->id.$key.serialize($value)) : $this->id = md5(null.$key.$value);
        }

        // optional comment
        $this->comment = $comment;

        $this->children = new Config_NodeList();
    }

    /**
     * method to set the current type and initializes it
     *
     * @param $value
     *
     * @return bool
     */
    private function __init_type($value)
    {
        if (!is_string($value)) return true;

        // check if it's not a json array or object
        if ( strpos( trim( $value ), '[' ) === 0 || strpos( trim( $value ), '{' ) === 0 )
        {
            $this->value = json_decode( trim( $value ));
        }
        // check if it's a string with quotes on the outside
        elseif ( (preg_match( '/^["|\']{1}(.*)["|\']{1}$/', trim( $value ), $match )) === 1 )
        {
            $this->value = (string) $match[1];
        }
        // if it's not numeric but no quotes it's still a tring
        elseif ( !is_numeric(trim($value)) && preg_match('/^(true|false){1}$/', trim($value)) )
        {
            $this->value = (bool) (trim($value) == "true") ? true : false;
        }
        elseif ( ($tmp = @unserialize($value) !== false) ) {
            $this->value = $tmp;
        }
        elseif ( !is_numeric(trim($value)) )
        {
            $this->value = (string) trim($value);
        }
        // else it hast to be a numeric value
        else
        {
            // integer
            if ( is_numeric($value) && strpos($value, '.') === false )
            {
                $this->value = (int) trim($value);
            }
            // american decadic system - float
            elseif ( is_numeric($value) && strpos($value, '.') > 1 )
            {
                $this->value = (float) trim($value);
            }
        }

        return true;
    }

    /**
     * gets the value
     *
     * @return mixed|null
     */
    public function get_value()
    {
        return $this->value;
    }

    /**
     * gets a config variable
     * out of the depths in the chain
     *
     * @param $key
     * @return mixed|null
     */
    public function get_by_key($key){
        if ($this->key == $key ) return $this->value;
        return $this->children->get_by_key($key);
    }

    /**
     * set the current value
     *
     * @param $value
     *
     * @return $this
     */
    public function set_value($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * set the parent id
     *
     * @param Config_Node $node
     * @internal param $ Config_node|null
     *
     * @return $this
     */
    public function set_parent(Config_node $node = null)
    {
        $this->parent_node = $node;
        return $this;
    }

    /**
     * get the parent id
     *
     * @return int|mixed
     */
    public function get_parent() {
        return $this->parent_node;
    }

    /**
     * get the id
     *
     * @return mixed|string
     */
    public function get_id(){
        return $this->id;
    }

    /**
     * set the id
     *
     * @param $id
     *
     * @return $this
     */
    public function set_id($id){
        $this->id = $id;

        return $this;
    }

    /**
     * get the id
     *
     * @return mixed|string
     */
    public function get_key(){
        return $this->key;
    }

    /**
     * set the id
     *
     * @param $key
     * @internal param $id
     *
     * @return $this;
     */
    public function set_key($key){
        $this->key = $key;

        return $this;
    }

    /**
     * gets a comment
     *
     * @return string
     */
    public function get_comment(){
        return $this->comment;
    }

    /**
     * sets a comment
     *
     * @param $comment
     *
     * @return $this
     */
    public function set_comment($comment) {
        $this->comment = $comment;
        return $this;
    }

    /**
     * adds/replace a node to node children list
     *
     * @param Config_Node $node
     *
     * @return $this
     */
    public function add_child(Config_Node $node)
    {
        $this->children->add_node($node);
        return $this;
    }


    /**
     * adds/replace a node to node children list
     *
     * @param Config_Node $node
     */
    public function remove_child(Config_Node $node)
    {
        $this->children->remove_node($node);
    }
}