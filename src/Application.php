<?php

namespace Chat;

use PHPDaemon\Core\AppInstance;
use PHPDaemon\Servers\WebSocket;

class Application extends AppInstance
{
	public $requestClass = '\Chat\HttpRequest';

	public function onReady()
	{
		$wsServer = WebSocket\Pool::getInstance(); /* @var WebSocket\Pool $wsServer */
		$wsServer->addRoute('chat', function ($client) {
			return new WebSocketRoute($client, $this);
		});
	}
}
