<?php

global $project;
$project = 'mysite';

global $database;
$database = 'deploynaut';

require_once('conf/ConfigureFromEnv.php');

MySQLDatabase::set_connection_charset('utf8');

SSViewer::set_theme('deploynaut');

BasicAuth::protect_entire_site(true, "DEPLOYNAUT_ACCESS");

if(defined('IS_SSL') && IS_SSL) Director::forceSSL();

if(!defined('DEPLOYNAUT_LOG_PATH')) {
	// The reason is that sys_get_temp_dir() can be different in cli and webserver environment.
	throw new RuntimeException('You must set the DEPLOYNAUT_LOG_PATH in _ss_environment.php');
}
if(!defined('DEPLOYNAUT_LOCAL_VCS_PATH')) {
	// The reason is that sys_get_temp_dir() can be different in cli and webserver environment.
	throw new RuntimeException('You must set the DEPLOYNAUT_LOCAL_VCS_PATH in _ss_environment.php');
}
if(!file_exists(DEPLOYNAUT_LOG_PATH)) {
	mkdir(DEPLOYNAUT_LOG_PATH);
	// Make sure the cli and www have write access to this folder
	chmod(DEPLOYNAUT_LOG_PATH, 0777);
}
if(!file_exists(DEPLOYNAUT_LOCAL_VCS_PATH)) {
	mkdir(DEPLOYNAUT_LOCAL_VCS_PATH);
	// Make sure the cli and www have write access to this folder
	chmod(DEPLOYNAUT_LOCAL_VCS_PATH, 0777);
}
