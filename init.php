<?php
// ATTENTION !!!!
// please include only files if you know how the
// autoloader works, usealy there is no need for that
DEFINE('INCLUDE_PATTERN', 'videostream');

/**
 * only needed for cli methods
 * to find it's own relative path to the application
 *
 * @param $path string            
 *
 *
 */
if ( !function_exists('self_aware') )
{


    function self_aware()
    {

        if ( ($cut = strpos(__DIR__, INCLUDE_PATTERN)) !== false )
        {
            return substr(__DIR__, 0, $cut);
        }
    }
}

$base = (string) (!empty($_SERVER['DOCUMENT_ROOT'])) ? $_SERVER['DOCUMENT_ROOT'] : self_aware();

include_once ("lib/Config.php");
Config::create_instance();
include_once ("lib/Autoloader.php");

?>
