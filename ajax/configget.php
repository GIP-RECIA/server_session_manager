<?php

// Check user and app status
OCP\JSON::checkAppEnabled('server_session_manager');
OCP\JSON::callCheck();
OCP\JSON::checkAdminUser();
$theConf = array();
$theConf['logoutbutton']=(OCP\Config::getAppValue ('server_session_manager','logoutbutton',0)) ? true : false;
OCP\JSON::success(array('configuration' => $theConf));
exit();

