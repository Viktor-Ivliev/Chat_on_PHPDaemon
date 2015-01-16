<?php

namespace Chat;

use PHPDaemon\Core\Daemon;
use PHPDaemon\WebSocket\Route;

class WebSocketRoute extends Route
{
	public function onFrame($data, $type)
	{
		Daemon::log($data);
	}
}