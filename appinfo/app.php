<?php

namespace OCA\server_session_manager\AppInfo;

use \OCP\AppFramework\App;
use \OCA\server_session_manager\Service\UserService;
use OC_Log;

class Application extends App {
        const APP_ID = 'server_session_manager';
        public function __construct(array $urlParams = array()) {
                parent::__construct ( APP_ID, $urlParams );
                $container = $this->getContainer ();

                /**
                 * Controllers
                 */


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
         *
         */
        \OCP\Util::addScript ( 'server_session_manager', 'utils' );
}

