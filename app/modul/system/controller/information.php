<?php

class System_Information {

    var $error = false;

    function __construct() {

        $this->Template["index"] = "index.php";
    }

    function index() {

        $this->check_permissions();
        $this->check_modrewrite();
        $this->check_exec();
        $this->check_jobscheduler();
        $this->check_phpversion();
    }

    
    /**
     * check if public directories are writeable by server
     * 
     */
    function check_permissions() {
        
        if (!is_writable(Config::get('basedir') . "/public/upload/")) {
            $this->error['permissions'][] = _(sprintf("Path %s is not writeable", _(Config::get('basedir') . "/public/upload/")));
        }

        if (!is_writable(Config::get('basedir') . "/public/video/")) {
            $this->error['permissions'][] = _(sprintf("Path %s is not writeable", _(Config::get('basedir') . "/public/video/")));
        }
    }

    /**
     * check if mod_rewrite is enabled
     * mod_rewrite is needed for nice url
     */
    function check_modrewrite() {
        if (!in_array('mod_rewrite', apache_get_modules())) {
            $this->error['mod_rewrite'][] = _("Please make sure mod_rewrite is enabled");
        }
    }

    /**
     * Check if php is able to call execute, some servers disable
     * this function because of security concerns
     * anhow its required to call external app like mplayer && ffmpeg
     */
    function check_exec() {
        $disabled = explode(', ', ini_get('disable_functions'));
        if(in_array('exec', $disabled))
        {
            $this->error['PHP'][] = _("Please make sure that the \"exec()\" function is not disabled in your configuration (php.ini)");
        }
    }
    
    /**
     * check if the job sheduler is running
     *
     */
    function check_jobscheduler()
    {
        if(!file_exists(Config::get('basedir')."/job-pid.lock"))
        {
            $this->error['jobscheduler'][] = _("It looks like the job scheduler is not running");
            return false;
        }
        $pid = file_get_contents(Config::get('basedir')."/job-pid.lock");
        
        if (!file_exists( "/proc/$pid" )){
            $this->error['jobscheduler'][] = _("It looks like the job scheduler is not running");
    
        }
        
    }
    
    /**
     * check for php version 5.4 since we depend
     * on traits 
     */
    
    function check_phpversion()
    {
        
        if (version_compare(PHP_VERSION, '5.4.0', '<=')) {
             $this->error['PHP'][] = _("We require at least PHP Version 5.4 or greater");
        }
    }

}

?>
