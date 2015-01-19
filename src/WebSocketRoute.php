<?php
namespace Chat;
use PHPDaemon\Core\Daemon;
use PHPDaemon\WebSocket\Route;
class WebSocketRoute extends Route
{
	public $client;
    public $appInstance;
    public $id; // Здесь храним ID сессии
    public $data = "";


    public function __construct($client,$appInstance) {
        $this->client=$client;
        $this->appInstance=$appInstance;
    }
    //какая то странная идеффикация, работает и без нее но с ней не вываливается лишний ворнинг
 //    public function onHandshake() {
	// }

	public function onFrame($data, $type) {
		
        //$this->client->sendFrame('Server receive from client '.$this->id.' # message "'.$data.'"', 'STRING')
        	$this->data = $data;
	}

	// Этот метод срабатывает при закрытии соединения клиентом
    public function onFinish() {
        // Удаляем сессию из массива
        unset($this->appInstance->sessions[$this->id]);
    }
}