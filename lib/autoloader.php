<?


/**
 * Auto loader
 * @param string $className
 * @return bool
 */
function autoload( $className )
{

    $class = "app/model/" . str_replace("_", "/", strtolower($className)) . ".php";
    if ( file_exists($class) )
    {
        return require_once ($class);
    }
    $class = "app/controller/" . str_replace("_", "/", strtolower($className)) . ".php";
    if ( file_exists($class) )
    {
        return require_once ($class);
    }
    
    $class = "lib/" . str_replace("_", "/", strtolower($className)) . ".php";
    if ( file_exists($class) )
    {
        return require_once ($class);
    }
    
    $class = "lib/database/" . str_replace("_", "/", strtolower($className)) . ".php";
    
    if ( file_exists($class) )
    {
        return require_once ($class);
    }
    
    $class = "lib/cache/" . str_replace("_", "/", strtolower($className)) . ".php";
    if ( file_exists($class) )
    {
        return require_once ($class);
    }
    
}
spl_autoload_register("autoload");