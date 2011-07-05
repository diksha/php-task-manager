<?php
require_once 'Task/Abstract.php';

require_once 'AWSSDKforPHP/sdk.class.php';
require_once 'AWSSDKforPHP/services/s3.class.php';

class Task_DatabaseBackup extends Task_Abstract
{

	/**
	 * s3 storage settings.
	 * @var array
	 */
	private $_s3_options = null;
	
	/**
	 * s3 database connection settings.
	 * @var array
	 */
	private $_database_options = null;
	
	/**
	 * tmp dir to save files
	 * @var string
	 */
	private $_tmp_dir = null;
	
	/**
	 * backup filename
	 * @var string
	 */
	private $_filename = null;
	
	
	/**
	 * creates a task to backup the database
	 * 
	 * @param array $s3_options
	 * @param array $database_options
	 */
	public function __construct($s3_options, $database_options) 
	{

		$this->_tmp_dir = ini_get('upload_tmp_dir');
		if (empty($this->_tmp_dir)) {
			$this->_tmp_dir = '/tmp/';
		}
				
		$this->_filename = 'database_' . date('Y-m-d').".sql";
		$this->_s3_options = $s3_options;
		$this->_database_options = $database_options;
		
		$this->_taskId = self::TASK_ID_DATABASEBACKUP;
		parent::__construct();
	}
	
	
	protected function execute()
	{
		$this->databaseDump();
		$this->uploadToS3();
		
		return true;
	}

	/**
	 * Dump the database into a temp file
	 */
	private function databaseDump()
	{
		// dump the database
		$mysqldump_command = $this->_database_options['mysqldump_path'] . 'mysqldump -h' . 
							 $this->_database_options['host'] . ' -u' . 
							 $this->_database_options['username'] . ' -p' . 
							 $this->_database_options['password'] . ' --databases ' . 
							 $this->_database_options['dbname'] . ' > ' . $this->_tmp_dir . $this->_filename;  
							 
		$output = '';		
		$return_var = 0;		 
		exec($mysqldump_command, $output, $return_var);
		if ($return_var == 2) {
			throw new Exception('mysqldump dump failed');
		}
		
		
		// gzip the results
		$gzip_command = 'tar -zcf ' . $this->_tmp_dir . $this->_filename . '.tar.gz -C ' . $this->_tmp_dir . ' ' . $this->_filename;
		exec($gzip_command);
	}
	
	/**
	 * Upload to amazon s3 bucket.
	 */
	private function uploadToS3()
	{	
		$s3 = new AmazonS3($this->_s3_options['aws_access_key'], $this->_s3_options['aws_secret_key']);  
		$bucket_name = $this->_s3_options['bucket_name'];
		
		// create a new bucket  
		$response = $s3->create_bucket($bucket_name, AmazonS3::REGION_US_E1, AmazonS3::ACL_PRIVATE);  
		
		if (!$response->isOK()) {
			throw new Exception('s3 create bucket failed');
		} 
		//move the file  
		$opt = array (
			'fileUpload' => $this->_tmp_dir . $this->_filename . '.tar.gz',
			'acl' => AmazonS3::ACL_PRIVATE
		);
			
		$response = $s3->create_object($bucket_name, $this->_filename . '.tar.gz', $opt);
		
		if ($response->isOK()) {
			return true;
		} else {
			throw new Exception('s3 upload failed');
		}
	}
	
	
}