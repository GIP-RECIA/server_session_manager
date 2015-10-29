<?php
namespace OCA\server_session_manager\service;

use \OCP\ISession;

class SessionService {

    private $session;
    private $timeFactory;

    public function __construct(ISession $session, $timeFactory){
        $this->session = $session;
    }

    public function getSession(){
    	return $this->session;
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