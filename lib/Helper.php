<?php

class Helper{
    
    public static function js($params){
        return file_get_contents(Config::get('basedir')."/js/".$params);
    }
    public static function jsScript($params){
        return "<script href=\"".Config::get('address')."/js/".$params."\" type=\"text/javascript\"></script>\n\t\t";
    }
    public static function css($params){
        return file_get_contents(Config::get('basedir')."/js/".$params);
    }
    public static function cssScript($params){
        return "<link rel=\"stylesheet\" href=\"".Config::get('address')."/css/".$params."\" type=\"text/css\"/>\n\t\t";
            
    }        
}
?>
