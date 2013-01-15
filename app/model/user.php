<?php
class User extends BaseApp
{

    /**
     *
     * @var int
     */
    public $id = 0;


    /**
     *
     * @var int
     */
    public $group_id = 0;


    /**
     *
     * @var string
     */
    public $username = '';


    /**
     *
     * @var string
     */
    public $password = '';


    /**
     *
     * @var string
     */
    public $email = '';


    /**
     *
     * @var string
     */
    public $firstname = '';


    /**
     *
     * @var string
     */
    public $lastname = '';


    /**
     *
     * @var string
     */
    public $info = '';


    /**
     * The constructer
     */
    public function __construct( $id = '' )
    {
        parent::__construct();
        if ( !empty($id) )
        {
            $this->id = $id;
            $this->get();
        }
    }


    /**
     * Sanitize a input array
     *
     * @param array $data            
     * @return array
     */
    public function check( $data = '' )
    {

        $check = array(
                    'id' => FILTER_SANITIZE_NUMBER_INT, 
                    'group_id' => FILTER_SANITIZE_NUMBER_INT, 
                    'username' => FILTER_SANITIZE_STRING, 
                    'password' => FILTER_SANITIZE_STRING, 
                    'email' => FILTER_SANITIZE_STRING, 
                    'firstname' => FILTER_SANITIZE_STRING, 
                    'lastname' => FILTER_SANITIZE_STRING, 
                    'info' => FILTER_SANITIZE_STRING
        );
        return filter_var_array($data, $check);
    }


    /**
     * Delete a element in the Database
     */
    public function del()
    {

        if ( empty($this->id) || !is_numeric($this->id) ) return false;
        
        $sql = "DELETE FROM user WHERE id = :id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }


    /**
     * Get a list of objects
     *
     * @param array $data
     *            optional if you like to get only a subset of data
     *            
     * @return obj
     */
    public function get_list( $data = '' )
    {

        if ( ($obj = unserialize($this->cache->get(__CLASS__ .'_list'. md5(serialize($data))))) ) return $obj;
        
        $sql = "SELECT u.id, u.group_id, u.username, u.password, u.email, u.firstname, u.lastname, u.info FROM user as u";
        if ( is_array($data) )
        {
            $data = $this->check($data);
            $sql .= " WHERE";
            foreach ( $data as $key => $value )
            {
                if ( $value ) $sql .= " u.$key = '$value' and";
            }
            $sql = substr($sql, 0, -4);
        }
        $stmt = $this->dbh->query($sql);
        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'user');
        $this->cache->set(__CLASS__ .'_list'. md5(serialize($data)), $obj);
        return $obj;
    }


    /**
     * Get one Dataset from the db
     */
    public function get()
    {

        if ( empty($this->id) || !is_numeric($this->id) ) return false;
        
        $sql = "SELECT u.id, u.group_id, u.username, u.password, u.email, u.firstname, u.lastname, u.info FROM user as u WHERE
        u.id = $this->id";
        if ( !($result = unserialize(__CLASS__ .'_'.$this->cache->get(md5($sql)))) )
        {
            $stmt = $this->dbh->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->cache->set(__CLASS__ .'_'.md5($sql), serialize($result));
        }
        
        foreach ( $result as $key => $val )
        {
            $this->$key = $val;
        }
    }


    /**
     * The save method is responsability for
     * saving and updating a dataset
     */
    public function save()
    {

        if ( empty($this->id) )
        {
            $sql = 'INSERT INTO user
            (group_id, username, password, email, firstname, lastname, info) VALUES
            (:group_id, :username, :password, :email, :firstname, :lastname, :info)';
            $stmt = $this->dbh->prepare($sql);
        }
        else
        {
            $sql = 'UPDATE user set
                         group_id = :group_id,
                         username = :username,
                         password = :password,
                         email = :email,
                         firstname = :firstname,
                         lastname = :lastname,
                         info = :info';
            $sql .= " WHERE id = :id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        }
        $this->cache->delete_from_list(__CLASS__ .'_');
        $stmt->bindValue(':group_id', $this->group_id, PDO::PARAM_INT);
        $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindValue(':info', $this->info, PDO::PARAM_STR);
        $stmt->execute();
    }
}
?>