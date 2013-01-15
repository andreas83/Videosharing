<?php 
require_once ('init.php');

header("Content-Type: text/html; charset=utf-8");

$modul = (isset($_GET['modul']) ? $_GET['modul'] : false);

if ( !$modul )
{
    $view = new stdClass();
    $view->Title = "Videomanager";
    include_once ("template/" . (string) Config::get('template') . "/header.php");
    include_once ("template/" . (string) Config::get('template') . "/footer.php");
    die();
}

$modul = explode("/", $modul);

$filename = (string) strtolower(implode("/", $modul)) . ".php";

if ( file_exists("./app/controller/" . (string) $filename) )
{
    if ( !in_array(end($modul), Config::get('allowed')) && !isset($_SESSION['login']) && Config::get('permisssion') != false )
    {
        header('Location: /Benutzer/Anmelden');
    }
    else
    {
        include_once ("./app/controller/" . (string) $filename);
        $classname = implode("_", $modul);
        $view = new $classname();
        $view->index();
    }
    
    // include template
    if ( $view->Template['index'] !== "json.php" )
    {
        include_once ("template/" . (string) Config::get('template') . "/header.php");
    }
    else
    {
        header('Content-type: application/json');
    }
    
    if ( !in_array(end($modul), Config::get('allowed')) && !isset($_SESSION['login']) && Config::get('permisssion') != false )
    {
        
        header('Location: /Benutzer/Anmelden');
        die();
    }
    
    // include template
    $template = strtolower($modul[0] . "/" . (string) end($modul) . "/" . (string) $view->Template['index']);
    
    include_once ("./app/views/" . (string) $template);
    
    if ( $view->Template['index'] !== "json.php" )
    {
        // add footer
        include ("template/" . (string) Config::get('template') . "/footer.php");
    }
}
elseif ( file_exists(strtolower("./app/controller/" . (string) implode("/", array_slice($modul, 0, -1))) . ".php") )
{
    
    if ( !in_array(end($modul), Config::get('allowed')) && !isset($_SESSION['login']) && Config::get('permisssion') != false )
    {
        
        header('Location: /Benutzer/Anmelden');
    }
    else
    {
        
        include_once (strtolower("./app/controller/" . (string) strtolower(implode("/", array_slice($modul, 0, -1)))) . ".php");
        $classname = (string) implode("_", array_slice($modul, 0, -1));
        $view = new $classname();
        $method = end($modul);
        $view->$method();
    }

    // include template
    if ( $view->Template[end($modul)] !== "json.php" )
    {
        include_once ("template/" . (string) Config::get('template') . "/header.php");
    }
    else
    {
        header('Content-type: application/json');
    }
    
    $template = strtolower($modul[0] . "/" . (string) end(array_slice($modul, 0, -1)) . "/" . (string) $view->Template[end($modul)]);
    if ( file_exists("./app/views/" . (string) $template) )
    {
        include_once ("./app/views/" . (string) $template);
    }
    else
    {
        echo "Template not found: ./module/" . (string) $template;
    }
    
    if ( $view->Template[end($modul)] !== "json.php" )
    {
        // add footer
        include_once ("template/" . (string) Config::get('template') . "/footer.php");
    }
}
else
{
    echo "Nope.. ";
}
?>
