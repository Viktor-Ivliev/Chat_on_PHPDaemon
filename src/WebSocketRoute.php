<?php
namespace Chat;
use PHPDaemon\Core\Daemon;
use PHPDaemon\WebSocket\Route;
class WebSocketRoute extends Route
{
	public $client;
    public $appInstance;
    public $id; // Здесь храним ID сессии


    public function __construct($client,$appInstance) {
        $this->client=$client;
        $this->appInstance=$appInstance;
    }

	public function onFrame($data, $type) {

        foreach($this->appInstance->sessions as $id=>$session) {
            $session->client->sendFrame($data.'<br/>', 'STRING');
        }
	}

	// Этот метод срабатывает при закрытии соединения клиентом
    public function onFinish() {
        // Удаляем сессию из массива
        unset($this->appInstance->sessions[$this->id]);
    }
}