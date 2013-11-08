<?


/**
 * Auto loader
 * @param string $className
 * @return bool
 */
function autoload( $className )
{

    $class = "app/modul/".strtolower($className)."/model/" . str_replace("_", "/", strtolower($className)) . ".php";
    if ( file_exists($class) )
    {
        return require_once ($class);
    }
    
    
    $class = "app/lib/" . str_replace("_", "/", strtolower($className)) . ".php";
    if ( file_exists($class) )
    {
        return require_once ($class);
    }
    
    $class = "app/lib/database/" . str_replace("_", "/", strtolower($className)) . ".php";
    
    if ( file_exists($class) )
    {
        return require_once ($class);
    }
    
    $class = "app/lib/cache/" . str_replace("_", "/", strtolower($className)) . ".php";
    if ( file_exists($class) )
    {
        return require_once ($class);
    }
    
}
spl_autoload_register("autoload");