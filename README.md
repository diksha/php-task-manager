README
======

This small library provides a framework to run unique tasks over multiple instances in a cloud based environment.
The library allow you to set cron jobs in multiple servers, and only one of the server is will execute the cronjob.

Useful for tasks such as sending daily reports and data aggregation

The project requires:

* Zend Framework 1.11 (http://framework.zend.com/)
* MySQL Database

Installation
============
- Add this table:
{code}

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `taskId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `handle` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`taskId`)
);

{code}

Examples
========
database_backup/task.php - example task that backup a mysql database and upload it to a S3 bucket
test_task/task.php - example task that does nothing!

How to Add a Task
========
- Write a new class, extending Task_Abstract
- Add a unique task id for the new class (see src/Task/Test.php)
- write a small php script which execute the task (see examples/test_test/task.php)
- add the php script to the crontab of all the web servers
