<?php
namespace OCA\server_session_manager\service;

use \OCP\ISession;

class SessionService {

    private $session;
    private $timeFactory;
        CONST COOKIE_NAME = 'oc_sessionPassphrase';
        CONST ENCRYPTED_SESSION_KEY = 'encrypted_session_data';
        private  $data = array();

    public function __construct(ISession $session, $timeFactory){
        $this->session = $session;
        $this->passphrase = \OC::$server->getRequest()->getCookie(self::COOKIE_NAME);
        \OCP\Util::writeLog('ssm_testMC','NEW SESSION SERVICE'.print_r(get_object_vars($session), true),\OCP\Util::DEBUG);
        if($session->exists(self::ENCRYPTED_SESSION_KEY)) {
                $this->data = $this->decryptSession(\OC::$server->getCrypto());
        }
    }

    public function getSession(){
        return $this->session;
    }
    /**
     * Au cas ou il y aurait a ce stade des données cryptées dans la session
     * On les decrypte
     * l'objet de cryptage propre a owncloud :
     *  @param $crypto : \OC\Security\Crypto
     *
     * */
    public function decryptSession($crypto){
        $dataTodecrypt = $this->session->get(self::ENCRYPTED_SESSION_KEY);
        $decryptedData = json_decode($crypto->decrypt($dataTodecrypt, $this->passphrase), true);
        return $decryptedData;
    }
    public function decrypt($crypto, $dataToDecrypt){
        if(!isset ($dataToDecrypt[self::ENCRYPTED_SESSION_KEY])) {
                return array();
        }
        $dataTodecrypt = $dataToDecrypt[self::ENCRYPTED_SESSION_KEY];
        $decryptedData = json_decode($crypto->decrypt($dataTodecrypt, $this->passphrase), true);
        return $decryptedData;
    }
    public function clearSession(){
        $this->data = array();
        $this->session = new \OC\Session\Memory('');
    }

    /**
     * Les donnees de la session doivent etre encodées en json et
     * encryptées avant d'etre envoyées a owncloud
     * l'objet de cryptage propre a owncloud :
     *  @param $crypto : \OC\Security\Crypto
     *
     * */
    public function encryptSession($crypto){

        if(isset($this->data) && !empty($this->data)){
                $jsonObj = json_encode($this->data);
                $cryptedData = $crypto->encrypt($jsonObj, $this->passphrase);
                $this->session->set(self::ENCRYPTED_SESSION_KEY, $cryptedData);
                $_SESSION[self::ENCRYPTED_SESSION_KEY] = $cryptedData;
    }

        return $this->session;
    }

    public function getData(){
        return $this->data;

    }
    public function removeFromData(){
        if (array_key_exists($key, $this->data)){
                unset( $this->data[$key]);
        }
    }
    public function setToData($key, $value){
        $this->data[$key] = $value;
    }
    public function updateTimestamp() {
        $oldTime = 0;

        if (array_key_exists('timestamp', $this->session) ){
            $oldTime = $this->session['timestamp'];
        }

        $newTime = $this->timeFactory->getTime();
        if ($newTime > $oldTime) {
            $this->session['timestamp'] = $newTime;
        }

    }

}

