<?php

require_once 'Tasks.php';

abstract class Task_Abstract {
	
	const TASK_ID_DATABASEBACKUP = 1;
	
	 /**
     * Default Zend_Db_Adapter_Abstract object.
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected static $_defaultDb;
	
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
         $modelTasks = new Tasks(array('db' => self::$_defaultDb));
		 $modelTasks->createTask($this->_taskId);
		
         // setup a unique id for the instance
         $this->_handle = md5(uniqid(rand(), true));
	}
	
    /**
     * Sets the default Zend_Db_Adapter_Abstract for all Tasks objects.
     *
     * @param  mixed $db Either an Adapter object, or a string naming a Registry key
     * @return void
     */
    public static function setDefaultAdapter($db = null)
    {
        self::$_defaultDb = self::_setupAdapter($db);
    }
	
    /**
     * @param  mixed $db Either an Adapter object, or a string naming a Registry key
     * @return Zend_Db_Adapter_Abstract
     * @throws Exception
     */
    protected static function _setupAdapter($db)
    {
        if ($db === null) {
            return null;
        }
        if (is_string($db)) {
            require_once 'Zend/Registry.php';
            $db = Zend_Registry::get($db);
        }
        if (!$db instanceof Zend_Db_Adapter_Abstract) {
            throw new Exception('Argument must be of type Zend_Db_Adapter_Abstract, or a Registry key where a Zend_Db_Adapter_Abstract object is stored');
        }
        return $db;
    }
	
	
	/**
	 * runs the task by a single server
	 */
	public function run()
	{
         $modelTasks = new Tasks(array('db' => self::$_defaultDb));
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
         $modelTasks = new Tasks(array('db' => self::$_defaultDb));
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