<?php

class Helper{
    /**
     * Small Helper to include js
     * @param string $params
     * @return string
     */
    public static function js($params){
        return file_get_contents(Config::get('basedir')."/public/js/".$params);
    }
    /**
     * Small Helper to include js
     * @param string $params
     * @return string
     */
    public static function jsScript($params){
        return "<script src=\"".Config::get('address')."/public/js/".$params."\" type=\"text/javascript\"></script>\n\t\t";
    }
    /**
     * Small Helper to include css
     * @param string $params
     * @return string
     */    
    public static function css($params){
        return file_get_contents(Config::get('basedir')."/public/css/".$params);
    }
    /**
     * Small Helper to include css
     * @param string $params
     * @return string
     */        
    public static function cssScript($params){
        return "<link rel=\"stylesheet\" href=\"".Config::get('address')."/public/css/".$params."\" type=\"text/css\"/>\n\t\t";
            
    }        
}
?>
