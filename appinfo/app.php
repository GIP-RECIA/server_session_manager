<?php

namespace OCA\server_session_manager\AppInfo;

use \OCP\AppFramework\App;
use \OCA\server_session_manager\Service\UserService;
use OC_Log;

global $CASlogoutReqst;
class Application extends App {
	const APP_ID = 'server_session_manager';
	public function __construct(array $urlParams = array()) {
		parent::__construct ( 'server_session_manager', $urlParams );
		
		$container = $this->getContainer ();
		/**
		 * Controllers
		 */
		$container->registerService ( 'UserService', function ($c) {
			return new UserService ( $c->query ( 'UserManager' ) );
		} );
		
		$container->registerService ( 'UserManager', function ($c) {
			return $c->query ( 'ServerContainer' )->getUserManager ();
		} );
		
		$container->registerService ( 'SessionService', function ($c) {
			return new SessionService ( $c->query ( 'Session' ), $c->query ( 'TimeFactory' ) );
		} );
		
		$container->registerService ( 'Session', function ($c) {
			return $c->query ( 'ServerContainer' )->getSession ();
		} );
	}
	public static function init() {
			\OCP\App::registerAdmin ( 'server_session_manager', 'settings' );
			\OCP\Util::connectHook ( 'OC', 'initSession', 'OCA\server_session_manager\Hooks', 'initSession' );
	}
}
if (\OCP\App::isEnabled ( Application::APP_ID )) {
	
	Application::init ();
	/**
	 * FICHIER JAVASCRIPT POUR ENLEVER LES FONCTIONNALITÉES NON SOUHAITÉES DANS OWNCLOUD
	 * i.e. LE BOUTON DECONNECTION
	 * 		LES SETTINGS PERSONNELS 
	 * 		ET LE LOGIN PAR FORMULAIRE
	 */
	\OCP\Util::addScript ( 'server_session_manager', 'utils' );
}
