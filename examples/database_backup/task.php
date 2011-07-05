<?php
ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . '../src/');

require_once 'Tasks.php';
require_once 'Task/Abstract.php';
require_once 'Task/DatabaseBackup.php';
require_once 'Zend/Db/Adapter/Pdo/Mysql.php';


$db = new Zend_Db_Adapter_Pdo_Mysql(array(
    'host'     => '127.0.0.1',
    'username' => 'root',
    'password' => 'icgweb',
    'dbname'   => 'mb2'
));

Task_Abstract::setDefaultAdapter($db);


// run the task 
$s3_options = array (
	'aws_access_key' => $settings['AWS_API_KEY'],
	'aws_secret_key' => $settings['AWS_API_SECRET'],
	'bucket_name' => 'backups.domainname.com',
);

$database_options = array (
	'mysqldump_path' => '/Applications/XAMPP/xamppfiles/bin/',
	'host'      => $config->resources->db->params->host,
    'port'      => $config->resources->db->params->port,
    'username'  => $config->resources->db->params->username,
    'password'  => $config->resources->db->params->password,
    'dbname'    => $config->resources->db->params->dbname
);

$task = new Task_DatabaseBackup($s3_options, $database_options);
$task->run();

exit;





