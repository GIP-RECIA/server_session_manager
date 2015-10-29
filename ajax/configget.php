<?php

// Check user and app status
OCP\JSON::checkAppEnabled('server_session_manager');
OCP\JSON::callCheck();	
$theConf = array();

$theConf['logoutbutton']=(OCP\Config::getAppValue ('server_session_manager','logoutbutton',0)) ? true : false;
$theConf['lost_password']=(OCP\Config::getAppValue ('server_session_manager','lostpassword', 0)) ? true : false;
$theConf['password_form']=(OCP\Config::getAppValue ('server_session_manager','passwordform', 0)) ?true :false;
$theConf['displayname_form']=(OCP\Config::getAppValue ('server_session_manager','displaynameform', 0)) ? true : false ;
$theConf['connectFromLogin']=(OCP\Config::getAppValue ('server_session_manager','connectFromLogin', 0)) ? true : false;
OCP\JSON::success(array('configuration' => $theConf));
exit();