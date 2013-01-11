<?php
Config::create_instance();
Config::set('dsn', 'mysql:host=localhost;dbname=boerse');
Config::set('db_user', '');
Config::set('db_pass', '');
Config::set('basedir', '/var/www/');
Config::set('template', 'default');

Config::set('permisssion', true);
Config::set('allowed',  array("Video", "Manager"));


// memcache
Config::set('memcache_config', array(
//array("host" => "localhost", "port" => "11211", "persistent" => true, "weight" => 100, "timeout" => 1)
));

?>
