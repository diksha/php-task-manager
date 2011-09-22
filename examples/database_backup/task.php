<?php
$root = realpath(dirname(dirname(__FILE__)));
$library = "$root/../src";

$path = array($library, get_include_path());
set_include_path(implode(PATH_SEPARATOR, $path));

require_once 'Tasks.php';
require_once 'Task/Abstract.php';
require_once 'Task/DatabaseBackup.php';
require_once 'Zend/Db/Adapter/Mysqli.php';


$db = new Zend_Db_Adapter_Mysqli(array(
    'host'     => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname'   => ''
));

Task_Abstract::setDefaultAdapter($db);


// run the task 
$s3_options = array (
	'aws_access_key' => $settings['AWS_API_KEY'],
	'aws_secret_key' => $settings['AWS_API_SECRET'],
	'bucket_name' => 'backups.domainname.com',
);

$database_options = array (
	'mysqldump_path' => '/usr/bin/',
	'host'      => $config->resources->db->params->host,
    'port'      => $config->resources->db->params->port,
    'username'  => $config->resources->db->params->username,
    'password'  => $config->resources->db->params->password,
    'dbname'    => $config->resources->db->params->dbname
);

$task = new Task_DatabaseBackup($s3_options, $database_options);
$task->run();



