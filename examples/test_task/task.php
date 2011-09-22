<?php
$root = realpath(dirname(dirname(__FILE__)));
$library = "$root/../src";

$path = array($library, get_include_path());
set_include_path(implode(PATH_SEPARATOR, $path));


require_once 'Tasks.php';
require_once 'Task/Abstract.php';
require_once 'Task/Test.php';
require_once 'Zend/Db/Adapter/Mysqli.php';


$db = new Zend_Db_Adapter_Mysqli(array(
    'host'     => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname'   => ''
));

Task_Abstract::setDefaultAdapter($db);


$task = new Task_Test();
$task->run();



