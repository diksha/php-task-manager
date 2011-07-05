README
======

This project provides a PHP task manager to support uniqe cronjobs tasks with multiple servers.
to ensure only a single server runs the task, a database table is being used.

The project requires:

* Zend Framework 1.11 (http://framework.zend.com/)
* Database

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

