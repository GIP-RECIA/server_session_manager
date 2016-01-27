<?php


OC_Util::checkAdminUser();

$params = array('logoutbutton', 'lost_password', 'password_form', 'displayname_form');
if ($_POST) {
	foreach($params as $param) {
		$paramIsSet = 0;
		if (isset($_POST[$param])) {
			$paramIsSet = 1;
		}
		OCP\Config::setAppValue('server_session_manager', $param, $paramIsSet);
	}
}else{
	foreach ($params as $param) {
		OCP\Config::setAppValue('server_session_manager', $param, 0);
	}
}
$tmpl = new OCP\Template( 'server_session_manager', 'settings');
foreach ($params as $param) {
		$value = OCP\Config::getAppValue('server_session_manager', $param, '') == 1 ? true : false;
		if($value ){
			$tmpl->assign($param, $value);
		}
		
}
return $tmpl->fetchPage();
