<?php

namespace Chat;

use PHPDaemon\Core\AppInstance;
use PHPDaemon\Servers\WebSocket;

class Application extends AppInstance
{
	public $requestClass = '\Chat\HttpRequest';
    public $sessions=array();

	public function onReady()
	{
		$wsServer = WebSocket\Pool::getInstance(); /* @var WebSocket\Pool $wsServer */

		$wsServer->addRoute('chat', function ($client) {
			$session=new WebSocketRoute($client, $this); // Создаем сессию
            $session->id=uniqid(); // Назначаем ей уникальный ID
			$this->sessions[$session->id]=$session; //Сохраняем в массив
            return $session;
		});
	}
}
