<?php
require_once 'Task/Abstract.php';

class Task_Test extends Task_Abstract
{
	
	/**
	 * creates a test task that does nothing
	 * 
	 */
	public function __construct() 
	{
		$this->_taskId = self::TASK_ID_TEST;
		parent::__construct();
	}
	
	
	protected function execute()
	{
		echo 'executed only once!';
		
		return true;
	}

	
}