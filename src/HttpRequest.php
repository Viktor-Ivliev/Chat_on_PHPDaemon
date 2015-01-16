<?php

namespace Chat;

use PHPDaemon\HTTPRequest\Generic;

class HttpRequest extends Generic
{
	public function run()
	{
		$this->header('Content-Type: text/html');
		$script = <<<'JS'
	(function (w) {
		var socket = new WebSocket('ws://127.0.0.1:8090/chat');
		socket.onopen = function () {
			socket.send('some data');
		};
	})(window);
JS;
		echo "<script>$script</script>";
	}
}