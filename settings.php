<?php


OC_Util::checkAdminUser();

$params = array('logoutbutton', 'lost_password', 'password_form', 'displayname_form', 'connectFromLogin');
\OC_Log::write('server_session_manager_testMC','CALLED',OC_Log::DEBUG);
if ($_POST) {

	foreach($params as $param) {
		$paramIsSet = 0;
		if (isset($_POST[$param])) {
			$paramIsSet = 1;
			\OC_Log::write('server_session_manager_testMC','VALUE OF '.$param .' is TRUE :' .$_POST[$param],OC_Log::DEBUG);
		} else{
			\OC_Log::write('server_session_manager_testMC','VALUE OF '.$param .' is FASLE',OC_Log::DEBUG);
		} 
		OCP\Config::setAppValue('server_session_manager', $param, $paramIsSet);
	}
}else{
	foreach ($params as $param) {
		OCP\Config::setAppValue('server_session_manager', $param, 0);
	}
}

// fill template
$tmpl = new OCP\Template( 'server_session_manager', 'settings');
foreach ($params as $param) {
		$value = OCP\Config::getAppValue('server_session_manager', $param, '') == 1 ? true : false;
		if($value ){
			$tmpl->assign($param, $value);
		}
		
}

// settings with default values
// foreach($params as $param) {
// 	$paramIsSet = false;
// 	if (OCP\Config::getAppValue('server_session_manager', $param,1) == 1) {
// 		\OC_Log::write('server_session_manager_testMC','VALUE OF '.$param .' is TRUE',OC_Log::DEBUG);
// 		$paramIsSet = true;
// 	}else{
// 		\OC_Log::write('server_session_manager_testMC',' VALUE OF '.$param .' is FALSE',OC_Log::DEBUG);
// 	}
	
// 	$tmpl->assign( $param, $paramIsSet);
// }

// $tmpl->assign( 'lost_password', OCP\Config::getAppValue('server_session_manager', 'lostpassword', 0));
// $tmpl->assign( 'password_form', OCP\Config::getAppValue('server_session_manager', 'passwordform', 0));
// $tmpl->assign( 'displayname_form', OCP\Config::getAppValue('server_session_manager', 'displaynameform', 0));
// $tmpl->assign( 'connectFromLogin', OCP\Config::getAppValue('server_session_manager', 'connectFromLogin', 0));

return $tmpl->fetchPage();
