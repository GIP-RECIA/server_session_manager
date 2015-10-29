<?php
OCP\JSON::checkAppEnabled('server_session_manager');
OCP\JSON::callCheck();
$response = OC_User::isLoggedIn ();
OCP\JSON::success(array('logged' => $response));