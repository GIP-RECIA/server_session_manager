<?php

namespace OCA\Server_session_manager;

use OC;
use \OCP\Util;
use \OC_Util;
use \OC\Session\CryptoSessionData;
class Hooks {
        /**
         *
         * @param array $parameters
         * keys :       @var OC\\Session\\Session session ,
         *                      @var boolean useCustomSession,
         *                      @var string sessionName
         *      objets passés par reference.
         */


        public static function initSession($parameters){
                //On recupere les elements de la requete
                // utile pour recreer le token de securité aprés passage du gc
                // utile aussi pour determiner si on a une requete d'authentification hors CAS (formulaire ou cookie)
                $request = \OC::$server->getRequest();
                $postUser = null;
                $urlParams = array();
                $reqToken = null;
                $appRequested = null;
                if(isset($request)){
                        $postUser = $request->getParam('password');
                        $urlParams = $request->getParams();
                        $reqToken = $request->getParam ( 'requesttoken' );
                        $appRequested = $request->getParam('app');
                }
                $theService = new Service\SessionService ( $parameters['session'], null );
                if(!isset($_SESSION)){
                        session_name($parameters['sessionName']);
                        session_start();
                }
                $webroot = '/';
                if (isset(OC::$WEBROOT) && OC::$WEBROOT){
                        $webroot = OC::$WEBROOT;
                }

                if( isset ($postUser) && !isset($_SESSION['user_form'])){
                        if(isset($reqToken)) {
                                $_SESSION['requesttoken'] = $reqToken;
                        }
                        $_SESSION['user_form'] = $postUser;
                        foreach ( $_SESSION as $key => $val ) {
                                if($key === 'requesttoken'){
                                        $myVar = explode(':',$val);
                                        // On encode le token de securité pour qu'il corresponde au token encodé qu'il y a dans les pages
                                        $deobfuscatedToken = base64_decode($myVar[0] ) ^ $myVar[1];
                                        $theService->setToData($key, $deobfuscatedToken);
                                }else if ($key !== 'encrypted_session_data'){
                                        $theService->setToData ( $key, $val );
                                }
                        }
                        // On encrypt les données de la session parce qu'elle sont decryptées par owncloud a la sortie du hook pour etre
                        // integrées dans sa session propre
                        $parameters['session'] =  $theService->encryptSession(\OC::$server->getCrypto());
                        OC::$server = new \OC\Server($webroot);
                        OC::$server['urlParams'] = $urlParams;
          // On peut fermer l'ecriture de la session pour ne pas generer d'erreur si une autre fonction fait un session_start()
                        session_write_close();
                        return;
                }
                if(isset($_SESSION['user_form'])){
                        OC::$server = new \OC\Server($webroot);
                        OC::$server['urlParams'] = $urlParams;
                        // On peut fermer l'ecriture de la session pour ne pas generer d'erreur si une autre fonction fait un session_start()
                        session_write_close();
                        if(isset($appRequested) && $appRequested === 'user_cas'){
                                unset($_SESSION['user_form']);
                        }else {
                                return;
                        }
                }

                $OC_sessionValues = $theService->decrypt(\OC::$server->getCrypto(), $_SESSION);
                if((!isset($OC_sessionValues['loginname']) || $OC_sessionValues['loginname'] == '') &&
                                (isset ($OC_sessionValues['user_id']) && $OC_sessionValues['user_id']) !==''){
                       
                        $instanceId = OC_Util::getInstanceId();

                        if (isset($reqToken) && $reqToken){
                                $_SESSION['requesttoken'] = $reqToken;
                        }
                        foreach ( $_SESSION as $key => $val ) {
                                if($key === 'requesttoken'){
                                        // On encode le token de securité pour qu'il corresponde au token encodé qu'il y a dans les pages
                                        $myVar = explode(':',$val);
                                        $deobfuscatedToken = base64_decode($myVar[0] ) ^ $myVar[1];
                                        $theService->setToData($key, $deobfuscatedToken);
                                }else if ($key !== 'phpCAS' && $key !== 'encrypted_session_data'){
                                        $theService->setToData ( $key, $val );
                                }
                        }
                        if (isset($_SESSION ['phpCAS'] ['user'])) {
                                $theService->setToData  ( 'user_id', $_SESSION ['phpCAS'] ['user'] );
                        } else {
                                $theService->clearSession();
                                if(isset($_SESSION)){
                                        session_unset();
                                        session_destroy();
                                }
                                $parameters ['useCustomSession'] = true;
                        }
                        $parameters ['useCustomSession'] = true;
                        $parameters ['session'] = $theService->encryptSession(\OC::$server->getCrypto());

                        // On redemarre l'objet container pour que le token soit pris en compte dans les requetes
                        // A ce stade on est obligé de redemarrer le serveur car il se peut qu'on ait des pertes d'informations
                        // liées au gc
                        OC::$server = new \OC\Server($webroot);
                        OC::$server['urlParams'] = $urlParams;

                }
                // On peut fermer l'ecriture de la session pour ne pas generer d'erreur si une autre fonction fait un session_start()
                session_write_close ();
        }
}

