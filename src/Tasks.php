<?php
require_once 'Zend/Db/Table/Abstract.php';

class Tasks extends Zend_Db_Table_Abstract
{
    protected $_name		= 'tasks';
	protected $_primary	= 'taskId';
	
	/**
	 * tries to assign an handler
	 * 
	 * @param int $taskId
	 * @param string $handle
	 * 
	 * @return bool if the current handler has been assigned
	 */
	public function assignHandler($taskId, $handle) 
	{
		$db = $this->getAdapter();
		$where = array();
		$where[] = $db->quoteInto('taskId = ? AND handle IS NULL', $taskId);
		
		$data = array(
			'handle' => $handle
		);
		
		return parent::update($data, $where);
	}
	
	public function createTask($taskId) 
	{
		if (!$this->isExists($taskId)) {
			$data = array(
				'taskId' => $taskId,
				'handle' => null
			);
			
			parent::insert($data);
		}
	}
	
	
	/**
	 * is task exists
	 * 
	 * @param string $unit
	 */
	public function isExists($taskId) {
		$select = $this->select();
		
		// searching by title
		$select->where('taskId = ?', (int) $taskId);
		
		$task = $this->fetchRow($select);
		
		if (is_numeric($task['taskId'])) {
			return $task['taskId'];
		}
		
		return false;
		
	}
	
	/**
	 * update a task
	 * 
	 * @param int $userId
	 * @param array $data
	 */
	public function update($taskId, $data) 
	{
		$db = $this->getAdapter();
		$where = array();
		$where[] = $db->quoteInto('taskId = ?', $taskId);
		
		return parent::update($data, $where);
	}

}