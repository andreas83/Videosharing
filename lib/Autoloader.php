<?
/**
 * Auto loader
 * @param string $className
 * @return bool
 */
function autoload($className) 
{
    $class = "app/model/" . str_replace("_", "/", $className) . ".php";
    if (file_exists($class)) {
        require_once($class);
        return true;
    }
    $class = "app/controller/" . str_replace("_", "/", $className) . ".php";
    if (file_exists($class)) {
        require_once($class);
        return true;
    }
    $class = "lib/" . str_replace("_", "/", $className) . ".php";
    if (file_exists($class)) {
        require_once($class);
        return true;
    }
    
    return false;
}
spl_autoload_register("autoload");