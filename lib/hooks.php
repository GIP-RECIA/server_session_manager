<?php

namespace OCA\Server_session_manager;
    
use OC_Log;
use OC_Util;
use OC;

class Hooks {
    /**
     *
     * @param array $parameters
     * keys :   @var OC\\Session\\Session session ,
     *          @var boolean useCustomSession,
     *          @var string sessionName
     *      objets passés par reference.
     */         
    static public function initSession($parameters) {
                
                
        $request = OC::$server->getRequest();
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
                $reqToken = OC::$server->getRequest ()->getParam ( 'requesttoken' );
                    
        if (isset($reqToken) && $reqToken){
            $_SESSION['requesttoken'] = $reqToken;
                        
        }           
                if( isset ($postUser) && $postUser && !isset($_SESSION['user_form'])){
                    $_SESSION['user_form'] = $postUser;
                    OC::$server = new \OC\Server();
                    OC::$server['urlParams'] = $urlParams;
                        // On peut fermer l'ecriture de la session pour ne pas generer d'erreur si une autre fonction fait un session_start()
                    session_write_close();
                    return;
                }       
                            
        if(isset($_SESSION['user_form'])){
        OC::$server = new \OC\Server();
                        OC::$server['urlParams'] = $urlParams;
        //On peut fermer l'ecriture de la session pour ne pas generer d'erreur si une autre fonction fait un session_start()
                        if(isset($appRequested) && $appRequested === 'user_cas' && !isset($_SESSION['user_id']) ){
                            unset($_SESSION['user_form']);
                        }else {
        
                            session_write_close();
                                return;
                          }
                            }

        foreach ( $_SESSION as $key => $val ) {
            $theService->getSession ()->set ( $key, $val );
        }
        foreach ( get_object_vars ( $parameters ['session'] ) as $key => $val ) {
            $theService->getSession()->set ( $key, $val );
        }
        /**
         *
         * @todo : A gerer le fait qu'on ait une session utilisateur autre que CAS !!!!
         *       Voir les autres sessions possibles et les integrer ou laisser la main a owncloud qui le fait deja
         */
        if (isset($_SESSION ['phpCAS'] ['user'])) {
            $theService->getSession ()->set ( 'user_id', $_SESSION ['phpCAS'] ['user'] );
        } else {

            $theService->getSession ()->remove ( 'user_id' );
            session_unset();
            session_destroy();
        }
        $parameters ['useCustomSession'] = true;
        $parameters ['sessionName'] = session_name ();
        $parameters ['session'] = $theService->getSession();
        OC::$server = new \OC\Server(); // On redemarre l'objet container pour que cela soit pris en compte dans les requetes
        session_write_close ();
    }
}

