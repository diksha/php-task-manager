<?php

require_once 'Tasks.php';

abstract class Task_Abstract {
	
	const TASK_ID_DATABASEBACKUP = 1;
	
	/**
	 * unique handler name
	 * @var string
	 */
	protected $_handle;
	
	/**
	 * unique task id
	 * @var int
	 */
	protected $_taskId;
	
	public function __construct() 
	{
         // make sure the task exists
         $modelTasks = new Tasks;
		 $modelTasks->createTask($this->_taskId);
		
         // setup a unique id for the instance
         $this->_handle = md5(uniqid(rand(), true));
	}
	
	
	/**
	 * runs the task by a single server
	 */
	public function run()
	{
         $modelTasks = new Tasks();
         $assigned = $modelTasks->assignHandler($this->_taskId, $this->_handle);
		
         if ($assigned) {
         	// sleep for a few seconds to make sure all servers asked for assignment and then
         	// release the handler & execute the task
         	sleep(5);
         	$this->release();
         	
         	$this->execute();
         }
	}
	
	/**
	 * release the handler after a succesfull assignment
	 */
	private function release()
	{
         $modelTasks = new Tasks;
         $data = array(
         	'handle' => null
         );
         
		 $modelTasks->update($this->_taskId, $data);
	}
	
	/**
	 * the logic of the task, implemented by the task class itself
	 */
	protected function execute()
	{
		
		
	}
	
	
}