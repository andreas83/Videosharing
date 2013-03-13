<?php
class Video extends BaseApp {

    
    /**
     * @var int
     */
    public $id = 0; 

    /**
     * @var int
     */
    public $user_id = 0; 

    /**
     * @var string
     */
    public $title = ''; 

    /**
     * @var string
     */
    public $descripton = ''; 

    /**
     * @var string
     */
    public $filename = ''; 
    
    /**
     * @var int
     */
    public $visibility_setting = 0; 

    /**
     * @var int
     */
    public $isConverted = 0; 

    /**
     * @var int
     */
    public $thumb = 0; 
    
    protected $db;

        
    /**
     * The constructer
     */
    public function __construct ($id = '')
    {

        parent::__construct();
        if (!empty($id))
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
    public function check($data = '')
    {
        $check = array(
        'id' => FILTER_SANITIZE_NUMBER_INT,
        'user_id' => FILTER_SANITIZE_NUMBER_INT,
        'title' => FILTER_SANITIZE_STRING,
        'descripton' => FILTER_SANITIZE_STRING,
        'filename' => FILTER_SANITIZE_STRING,
        'visibility_setting' => FILTER_SANITIZE_NUMBER_INT,
        'isConverted' => FILTER_SANITIZE_NUMBER_INT,
        'thumb' => FILTER_SANITIZE_NUMBER_INT
        );
        return filter_var_array($data, $check);
    }


    /**
     * Delete a element in the Database
     */
    public function del ()
    {
        if (empty($this->id) || !is_numeric($this->id))
            return false;

        $sql = "DELETE FROM video WHERE id = :id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }
    /**
     * Delete a element in the Database
     */
    public function del_save ()
    {
        if (empty($this->id) || !is_numeric($this->id))
            return false;

        $sql = "DELETE FROM video WHERE id = :id and user_id = :user_id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    
        
    
    public function getPages ($pointer = false, $show = false, $user_id=false)
    {
        if(is_numeric($user_id))
            $sql_extra=" where v.user_id=".$user_id. " and isConverted=1";
        else
            $sql_extra="";
        
        
        if(!$pointer && !$show)
        {
            $sql = "SELECT count(v.id) as allVideos FROM video as v $sql_extra";
            $stmt = $this->dbh->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            foreach ($result as $key=>$val)
            {
                $this->$key=$val;
            }
        
            return true;
        }
        
        $sql = "SELECT v.id, v.user_id, v.title, v.descripton, v.filename, v.visibility_setting, v.isConverted, v.thumb FROM video as v $sql_extra order by id desc limit $pointer, $show";
        
        $stmt = $this->dbh->query($sql);
        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'Video');
        return $obj;
    }
    
    /**
     * Get a list of objects
     *
     * @param array $data optional if you like to get only a subset of data
     *
     * @return obj
     */
    public function get_list ($data = '', $limit = false)
    {
        
        $obj = $this->cache->get(__CLASS__ . '_list' . md5(serialize($data)));
        if ( !empty($obj) ) return unserialize($obj);
        
        $sql = "SELECT v.id, v.user_id, v.title, v.filename, v.descripton, v.visibility_setting, v.isConverted, v.thumb FROM video as v";
        if (is_array($data))
        {
             
            $data = $this->check($data);
            
            $sql .= " WHERE";
            foreach ($data as $key => $value)
            {
                if ($value!==null)
                    $sql .= " v.$key = '$value' and";
            }
            
            $sql = substr($sql, 0, -4);
        }
        
        if($limit != false)
        {
            $sql .=' order by v.id limit '.$limit;
        }
        
        $stmt = $this->dbh->query($sql);
        
        
        $obj = $stmt->fetchALL(PDO::FETCH_CLASS, 'Video');

        $this->cache->set(__CLASS__ . '_list' . md5(serialize($data)), $obj);
        return $obj;

    }


    /**
     * Get one Dataset from the db
     */
    public function getData ($data)
    {
     

        $sql = "SELECT v.id, v.user_id, v.title, v.filename, v.descripton, v.visibility_setting, v.isConverted, v.thumb FROM video as v";
        if (is_array($data))
        {

            $data = $this->check($data);

            $sql .= " WHERE";
            foreach ($data as $key => $value)
            {
                if ($value!==null)
                    $sql .= " v.$key = '$value' and";
            }

            $sql = substr($sql, 0, -4). " limit 1";
        }
        if ( !($result = unserialize($this->cache->get(__CLASS__ . '_' . md5($sql)))) )
        {
            $stmt = $this->dbh->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->cache->set(__CLASS__ . '_' . md5($sql), serialize($result));
        }
    
        foreach ($result as $key=>$val)
        {
            $this->$key=$val;
        }
    }

    /**
     * Get one Dataset from the db
     */
    public function get ()
    {
        if (empty($this->id) || !is_numeric($this->id))
            return false;

        $sql = "SELECT v.id, v.user_id, v.title, v.filename, v.descripton, v.visibility_setting, v.isConverted, v.thumb FROM video as v WHERE
        v.id = $this->id";
        
        if ( !($result = unserialize($this->cache->get(__CLASS__ . '_' . md5($sql)))) )
        {
            $stmt = $this->dbh->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->cache->set(__CLASS__ . '_' . md5($sql), serialize($result));
        }
        
        foreach ($result as $key=>$val)
        {
            $this->$key=$val;
        }
    }
    /**
     * The save method is responsability for
     * saving and updating a dataset
     */
    public function save ()
    {
        
        if (empty($this->id))
        {
            $sql = 'INSERT INTO video 
            (user_id, title, descripton, filename, visibility_setting, isConverted, thumb) VALUES
            (:user_id, :title, :descripton, :filename, :visibility_setting, :isConverted, :thumb)';
            $stmt = $this->dbh->prepare($sql);
        }
        else
        {
            $sql = 'UPDATE video set
                         user_id = :user_id, 
                         title = :title, 
                         descripton = :descripton, 
                         filename = :filename,
                         visibility_setting = :visibility_setting, 
                         isConverted = :isConverted,
                         thumb = :thumb';        
            $sql .= " WHERE id = :id";
            $stmt = $this->dbh->prepare($sql);
            
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        }
        
        $this->cache->delete_from_list(array( __CLASS__ . '_' ));
        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
        $stmt->bindValue(':descripton', $this->descripton, PDO::PARAM_STR);
        $stmt->bindValue(':filename', $this->filename, PDO::PARAM_STR);
        $stmt->bindValue(':visibility_setting', $this->visibility_setting, PDO::PARAM_INT);
        $stmt->bindValue(':isConverted', $this->isConverted, PDO::PARAM_INT);
        $stmt->bindValue(':thumb', $this->thumb, PDO::PARAM_INT);
        $stmt->execute();
        
        if (empty($this->id)) {
         $this->id = $this->dbh->lastInsertId();
        }
    }
    

    /**
     * These function are nessesary to unserialize
     * the Object
     */    
    public function __wakeup()
    {
        $this->load_database_handler();
        $this->load_cache_handler();
    }


    /**
     * These function are nessesary to serialize
     * the Object
     */
    public function __sleep()
    {
        return array('id','user_id','title','descripton', 'filename', 'visibility_setting','isConverted', 'thumb');
    }
    

}
?>